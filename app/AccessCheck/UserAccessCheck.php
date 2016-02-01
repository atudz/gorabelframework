<?php


require_once('types/hua/AccessCheckType.php');

class UserAccessCheck extends AccessCheckType
{
	public function getAccessCriteria($action = '')
	{
		$criteria = new DbCriteria();
		
		switch ($action)
		{
			case 'responsibleFor':
				$criteria = $this->getResponsibleLocationCriteria();
				break;
			case 'edit':
				$internalCriteria = $this->getInternalCriteria();
				$externalCriteria = $this->getExternalCriteria();
				$criteria->condition = "IF (`t`.hua_user_is_external = '1'," . $externalCriteria->condition . ', ' . $internalCriteria->condition . ')';
				$criteria->params = array_merge($criteria->params,$internalCriteria->params);
				$criteria->params = array_merge($criteria->params,$externalCriteria->params);
				if ($this->featureLibrary->isFeatureOn('feature_role_vendor'))
				{
					$criteria->join = "INNER JOIN ats_jobseeker AS js ON `t`.hua_user_id = `js`.ats_jobseeker_id INNER JOIN ats_vendor AS vendor ON `js`.ats_vendor_id = `vendor`.ats_vendor_id";
				}
				break;
			case 'jsChangeInfo':
				$criteria->condition = '`t`.hua_user_is_external = 1';
				break;
			default:
				break;
		}
		
		return $criteria;
	}
	
	protected function getVendorCriteria($alias)
	{
		global $atlas;

		$criteria = new DbCriteria();
		
		$vendorUser = ModelFactory::getInstance('VendorUser', 'ats')->byUser($atlas->getCurrentUserId())->find();

		$criteria->condition = $alias . '.ats_vendor_id = :accessCheckVendorId';
		$criteria->params[':accessCheckVendorId'] = $vendorUser->vendorId;
		
		return $criteria;
	}
	
	protected function getResponsibleLocationCriteria()
	{
		$criteria = new DbCriteria();
		
		$responsibleLocation = ModelFactory::getInstance('UserResponsibleLocation')->byUser($this->requestingUser)->findAll();
		$responsibleLocationGroup = ModelFactory::getInstance('UserResponsibleLocationGroup')->byUser($this->requestingUser)->findAll();
		$locationId = array();
		if(empty($responsibleLocation) && !empty($responsibleLocationGroup))
		{
			foreach($responsibleLocationGroup as $locGroup)
			{
				$locationModel = ModelFactory::getInstance('Location')->byLocationGroup($locGroup->hua_location_group_id)->findAll();
				foreach($locationModel as $loc)
				{
					$locationId[] = $loc->hua_location_id;
				}
				unset($locationModel);
			}
		}
		else 
		{
			foreach($responsibleLocation as $location)
			{
				$locationId[] = $location->hua_location_id;
			}
		}
		if(!empty($locationId))
		{
			$criteria->condition = "`t`.hua_location_id IN('" . implode("','",$locationId ) . "')";
		}

		return $criteria;
	}
	
	protected function getExternalCriteria()
	{
		global $atlas;

		$criteria = new DbCriteria();
		
		if($this->featureLibrary->isFeatureOn('feature_view_candidate_resume'))
		{
			$criteria->condition = "`t`.hua_user_is_external = '1'";
		}
		elseif (!$this->featureLibrary->isFeatureOn('feature_view_candidate_resume'))
		{				
			$criteria->condition = '`t`.hua_user_id = :userId';
			$criteria->params[':userId'] = $this->requestingUser;
		}

		if ($this->featureLibrary->isFeatureOn('feature_role_vendor'))
		{
			$alias = 'vendor';
			$vendorCriteria = $this->getVendorCriteria($alias);
			$criteria->mergeWith($vendorCriteria);										
		}

		return $criteria;
	}
	
	protected function getInternalCriteria()
	{
		global $atlas;

		$criteria = new DbCriteria();
		
		$criteria->condition = "`t`.hua_user_is_external = '0'";
		
		if (!$this->featureLibrary->isFeatureOn('feature_employee_management') && $this->featureLibrary->isFeatureOn('feature_limited_employee_management'))
		{
			$responsibleCriteria = $this->getResponsibleLocationCriteria();
			$criteria->mergeWith($responsibleCriteria);
		}		
		
		if ($this->featureLibrary->isFeatureOn('feature_role_vendor'))
		{
			$alias = 'vendor';
			$vendorCriteria = $this->getVendorCriteria($alias);
			$criteria->mergeWith($vendorCriteria);										
		}
		
		return $criteria;
	}
	
	protected function performAccessCheck($rowId, $action = '')
	{
		$criteria = $this->getAccessCriteria($action);
		$result = array('success' => false, 'message' => '');
		
		switch ($action)
		{
			case 'resetPassword':
				if (1 == $rowId)
				{
					// do not allow global admin password to be reset
					$userModel = ModelFactory::getInstance('User')->findByPk($this->requestingUser);
					$result['message'] = 'User ID ' . $this->requestingUser . ' (' . $userModel->hua_user_fullname . ' - ' . $userModel->email . ') attempted to reset password belonging to global admin.';
				}
				elseif ($this->featureLibrary->isFeatureOn('feature_ats_edit_account') || $this->featureLibrary->isFeatureOn('feature_employee_management'))
				{
					$result['success'] = true;
				}
				break;
				
			case 'saveCredential':
				if (1 == $rowId)
				{
					// global admin should never be modified under any circumstances
					$userModel = ModelFactory::getInstance('User')->findByPk($this->requestingUser);
					$result['message'] = 'User ID ' . $this->requestingUser . ' (' . $userModel->hua_user_fullname . ' - ' . $userModel->email . ') attempted to access data belonging to global admin.';
				}
				elseif ($rowId == $this->requestingUser)	// accessing own data
				{
					$result['success'] = true;
				}
				elseif ($this->featureLibrary->isFeatureOn('feature_role_admin'))	// acting on behalf
				{
					$result['success'] = true;
				}				
					break;
				
			case 'saveProfile':
				if ($rowId == $this->requestingUser)	// accessing own data
				{
					$result['success'] = true;
				}
				elseif ('saveProfile' != $action && $this->featureLibrary->isFeatureOn('feature_role_admin'))	// acting on behalf and has the feature and not a profile update
				{
					$result['success'] = true;
				}
				else
				{
					// If not, we failed and should record the error
					$userModel = ModelFactory::getInstance('User')->findByPk($this->requestingUser);
					$targetModel = ModelFactory::getInstance('User')->findByPk($rowId);
		
					$result['message'] = 'User ID ' . $this->requestingUser . ' (' . $userModel->hua_user_fullname . ' - ' . $userModel->email . ') attempted to access data belonging to user ID ' . $targetModel->id . ' (' . $targetModel->hua_user_fullname . ' - ' . $targetModel->email . ')';
				}
				break;
			
			case 'responsibleFor':
				if ($this->requestingUser == 1 || ($this->requestingUser == $rowId && $this->featureLibrary->isFeatureOn('feature_employee_management')) || $this->featureLibrary->isFeatureOn('feature_employee_management'))
				{
					$result['success'] = true;
				}
				elseif( ModelFactory::getInstance('User')->findByPk($rowId, $criteria))
				{
					if($this->featureLibrary->isFeatureOn('feature_employee_management'))
					{
						$result['success'] = true;
					}
					else
					{
						if($this->requestingUser != $rowId)
						{
							$result['success'] = true;
						}
						else
						{
							// If not, we failed and should record the error
							$userModel = ModelFactory::getInstance('User')->findByPk($this->requestingUser);
							$targetModel = ModelFactory::getInstance('User')->findByPk($rowId);
				
							$result['message'] = 'User ID '.$this->requestingUser.' ('.$userModel->hua_user_fullname.' - '.$userModel->email . ') attempted to access data belonging to user ID '.$targetModel->id.' ('.$targetModel->hua_user_fullname.' - '.$targetModel->email.')';
						}
					}
				}
				else
				{
					// If not, we failed and should record the error
					$userModel = ModelFactory::getInstance('User')->findByPk($this->requestingUser);
					$targetModel = ModelFactory::getInstance('User')->findByPk($rowId);
				
					$result['message'] = 'User ID '.$this->requestingUser.' ('.$userModel->hua_user_fullname.' - '.$userModel->email . ') attempted to access data belonging to user ID '.$targetModel->id.' ('.$targetModel->hua_user_fullname.' - '.$targetModel->email.')';
				}
				break;

			// Ideally, couldn't we make this stronger? Check for specific group membership etc.
			case 'deleteJSData':
				if ($rowId == $this->requestingUser)	// accessing own data
				{
					$result['success'] = true;
				}	
				else
				{
					// If not, we failed and should record the error
					$userModel = ModelFactory::getInstance('User')->findByPk($this->requestingUser);
					$targetModel = ModelFactory::getInstance('User')->findByPk($rowId);
		
					$result['message'] = 'User ID ' . $this->requestingUser . ' (' . $userModel->hua_user_fullname . ' - ' . $userModel->email . ') attempted to access data belonging to user ID ' . $targetModel->id . ' (' . $targetModel->hua_user_fullname . ' - ' . $targetModel->email . ')';
				}
				break;
			case 'edit':
				if(ModelFactory::getInstance('User')->findByPk($rowId, $criteria) || $this->requestingUser == $rowId || 1 == $this->requestingUser)
				{
					$result['success'] = true;
				}
				else
				{
					// If external, we failed and should record the error
					$userModel = ModelFactory::getInstance('User')->findByPk($this->requestingUser);
					$targetModel = ModelFactory::getInstance('User')->findByPk($rowId);
		
					$result['message'] = 'User ID ' . $this->requestingUser . ' (' . $userModel->hua_user_fullname . ' - ' . $userModel->email . ') attempted to edit the external user ID ' . $targetModel->id . ' (' . $targetModel->hua_user_fullname . ' - ' . $targetModel->email . ')';
				}
				break;
			case 'view':
				if(!ModelFactory::getInstance('User')->findByPk($rowId)->forceVerifyEmail)
				{
					$result['success'] = true;
				}
				else
				{
					// If external, we failed and should record the error
					$userModel = ModelFactory::getInstance('User');
					$user = $userModel->findByPk($this->requestingUser);
					$target = $userModel->findByPk($rowId);
			
					$result['message'] = 'User ID ' . $this->requestingUser . ' (' . $user->hua_user_fullname . ' - ' . $user->email . ') attempted to view the external user ID ' . $target->id . ' (' . $target->hua_user_fullname . ' - ' . $target->email . ')';
				}
				break;
			case 'createAppraisal':
				if( $this->performGenericCreateAppraisal( $rowId, 'feature_create_appraisal' ) )
				{
					$result['success'] = true;
				}
				break;
			case 'createEpm360':
				if( $this->performGenericCreateAppraisal( $rowId, 'feature_360_manager_create' ) )
				{
					$result['success'] = true;
				}
				break;
			case 'drillDown':
				if( $this->featureLibrary->userHasFeature( $this->requestingUser, 'feature_multiple_appraisals' ) )
				{
					$result['success'] = true;
				}
				
				$manager = KeyedObjectFactory::getInstanceByKey( 'Manager' , '' , 'hua' );
				$manager->loadFromDatabase( $rowId );
				
				if( $manager->directReports
					&& ( $manager->isMatrixManager( $this->requestingUser )
						|| 	$manager->isAnyLevelManager( $this->requestingUser ) ) )
				{
					$result['success'] = true;
				}
				break;
			case 'jsChangeInfo':
				if (1 != $rowId && $rowId == $this->requestingUser && ModelFactory::getInstance('User')->findByPk($rowId, $criteria))
				{
					$result['success'] = true;
				}
				else
				{
					// we failed and should record the error
					$userModel = ModelFactory::getInstance('User');
					$user = $userModel->findByPk($this->requestingUser);
					$target = $userModel->findByPk($rowId);
					if ($user && $target)
					{
						$result['message'] = 'User ID ' . $this->requestingUser . ' (' . $user->hua_user_fullname . ' - ' . $user->email . ') attempted to changed info for ' . $target->id . ' (' . $target->hua_user_fullname . ' - ' . $target->email . ')';
					}
					elseif ($target)
					{
						$result['message'] = 'Unauthorized action attempted to changed info for ' . $target->id . ' (' . $target->hua_user_fullname . ' - ' . $target->email . ')';
					}
					else
					{
						$result['message'] = 'Unauthorized action attempted to changed info for ' . $rowId;
					}
				}
				break;
			default:
				break;
		}
		
		return $result;
	}
    
    public function getUserGroupCriteria()
    {
        $criteria = new DbCriteria();
        
        $userGroupData = ModelFactory::getInstance('UserToGroup')->byUserGroupId($this->requestingUser)->findAll();
                
        $groupId = array();
        foreach($userGroupData as $userGroup)
        {
            $groupId[] = $userGroup->groupId;
        }

        $criteria->addInCondition('hua_group_id', $groupId);
        
        return $criteria;
    }
	
	protected function performGenericCreateAppraisal( $employeeId, $createAppraisalFeature )
    {
		$employee = KeyedObjectFactory::getInstanceByKey( 'Employee' , '' , 'hua' );
		$employee->loadFromDatabase( $employeeId );
			
		//employee cannot create an apprasial for himself if he is the manager of himself or has no manager
		
		if (( !$employee->managerId || $employee->managerId == $this->requestingUser) && $employee->id == $this->requestingUser )
		{
			return false;
		}
		
		if( $this->featureLibrary->userHasFeature( $this->requestingUser, 'feature_multiple_appraisals' ) )
		{
			return true;			
		}
		
		if( !$this->featureLibrary->userHasFeature( $this->requestingUser, $createAppraisalFeature ) )
		{
			return false;
		}
		
		if ( $employee->managerId == $this->requestingUser
			|| ( $employee->isHrbp( $this->requestingUser )
				&& $this->featureLibrary->userHasFeature( $this->requestingUser , 'feature_hrbp_appraisal_administration' ) )
			|| ( $employee->isMatrixManager( $this->requestingUser ) 
				&& $this->featureLibrary->userHasFeature( $this->requestingUser, 'feature_expand_matrix_manager_role' ) )
		)
		{
			return true;
		}
		
		return false;
	}
}


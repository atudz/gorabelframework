<?php

 
require_once('types/hua/AccessCheckType.php');
 
class OfferAccessCheck extends AccessCheckType
{
	
	public function __construct()
	{
		parent::__construct();
	
		$this->availableActions = array(
				'view',
				'resubmit'
				);
	}
	
	protected function getViewAccessCriteria()
	{
		$criteria = new DbCriteria();
	
		if (!$this->featureLibrary->isFeatureOn( 'feature_view_all_offers' ))
		{
			$criteria->with = array ('candidate','candidate.requisition','candidate.requisition.requisitionTeams.teamMembers','approvalChainTarget','approvalChainTarget.offerApprovalChainMember');
			$criteria->condition = 	'ats_offer_creator_id = :accessCheckUserId '.
					'OR requisition.ats_requisition_creator_id = :accessCheckUserId '.
					'OR requisition.ats_requisition_recruiter_id = :accessCheckUserId '.
					'OR requisition.ats_requisition_hiring_manager_id = :accessCheckUserId '.
					'OR (offerApprovalChainMember.hua_approval_chain_member_target = :accessCheckUserId  AND offerApprovalChainMember.hua_approval_chain_member_type = "internal") '.
					'OR teamMembers.hua_user_id = :accessCheckUserId '.
					'OR candidate.ats_jobseeker_id = :accessCheckUserId';
			$criteria->params[':accessCheckUserId'] = $this->requestingUser;
		}
		
		return $criteria;
	}
	
	protected function getResubmitAccessCriteria()
	{
		$criteria = new DbCriteria();
	
		$criteria->condition = "t.ats_offer_final_disposition = 'reject' AND (t.ats_offer_candidate_disposition <> 'reject_permanent' OR t.ats_offer_candidate_disposition IS NULL)";
	
		return $criteria;
	}
	
	
	protected function performResubmitAccessCheck($rowId)
	{
		$criteria = $this->getAccessCriteria('resubmit');
	
		$offerModel = ModelFactory::getInstance('Offer', 'ats');
		if ($offer = $offerModel->findByPk($rowId, $criteria))
		{
			$candidateId = $offer->candidateId;
			$offerModel->resetScope();
			
			// get outstanding offer			
			if($offerModel->byCandidates($candidateId)->with('outstandingOfferStatus')->find())
			{
				return false;
	}
			return true;
		}
		return false;
	}
	
	protected function performViewAccessCheck($rowId)
	{
		$criteria = $this->getAccessCriteria('view');
	
		return $this->genericOfferPerformAccessCheck($rowId, $criteria);
	}
	
	protected function genericOfferPerformAccessCheck($rowId, $criteria)
	{
		if (ModelFactory::getInstance('Offer', 'ats')->findByPk($rowId, $criteria))
		{
			return true;
		}
	
		return false;
	}
	
	
	/**
	 * This method will build an error message when access to a resource is denied so that it can
	 * be recorded in the log.
	 *
	 * @author Abner Tudtud <atudtud@hrsmart.com>
	 * @param  int $rowId The row being accessed. Its meaning varies by action.
	 * @param  string $action The action that the user attempted to take
	 * @return string The access denied message
	 */
	protected function buildDeniedAccessMessage($rowId, $action)
	{
		switch ($action)
		{
			case 'view': // falls through
			case 'resubmit':
				$userModel = ModelFactory::getInstance('User')->findByPk($this->requestingUser);
				$targetModel = ModelFactory::getInstance('Offer', 'ats')->with('candidate.user')->findByPk($rowId);
	
				$message = 'User ID %s (%s - %s) attempted to view the records of candidate ID %s (%s - %s).';
	
				$message = sprintf($message, $this->requestingUser, $userModel->hua_user_fullname, $userModel->email,
						$targetModel->id, $targetModel->candidate->user->hua_user_fullname, $targetModel->candidate->user->email);
				break;			
		}
	
		return $message;
	}	
}

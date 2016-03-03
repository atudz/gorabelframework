<?php

namespace App\Filters;

use App\Core\FilterCore;

class SelectFilter extends FilterCore
{
	/**
	 * Single Select Flag
	 * @var unknown
	 */
	const SINGLE_SELECT = 1;
	
	/**
	 * Multiple Select flag
	 * @var unknown
	 */
	const MULTIPLE_SELECT = 2;
	
	/**
	 * Select type value
	 * @var unknown
	 */
	protected $selectType = self::SINGLE_SELECT;
	
	public function __construct($label,$type='')
	{
		$this->selectType = $type ? $type : self::SINGLE_SELECT;
		parent::__construct($label);	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \App\Core\FilterCore::addFilter()
	 */
	public function addFilter($model, $name, $scope='',$alias='')
	{
		$this->setName($name);
		$this->value = $this->get();
		
		if(!$this->request->has($name) && !$this->getValue())
		{
			return $model;
		}
		elseif($this->request->get($name))
		{
			$value = $this->request->get($name);
			if(!is_array($value) && self::MULTIPLE_SELECT == $this->selectType)
			{
				$value = array($value);
			}
				
			$this->setValue($value);
		//	$this->store();
		}
		
		if($alias)
		{
			$name = $alias;
		}
		elseif($model instanceof Model)
		{
			$name = $model->getTable().'.'.$name;
		}
		elseif($model instanceof Builder)
		{
			$name = $model->from.'.'.$name;
		}
		else
		{
			$name = $model->getModel()->getTable().'.'.$name;
		}
		
		if($scope instanceof \Closure)
		{
			return $scope($this,$model);
		}

		if($this->selectType == self::SINGLE_SELECT)
		{
			
			return $scope ? $this->$scope($model) : $model->where($name,'=',$this->getValue());
		}
		else
			return $scope ? $this->$scope($model) : $model->whereIn($name,$this->getValue());
	}
	
	/**
	 * Render the filter field
	 */
	public function render()
	{
		$multiple = $this->selectType == self::MULTIPLE_SELECT ? true : false;
		return \Form::filterSelect($this->name.'[]',$this->options,$this->label, $this->value, $multiple);
	}
	
	/**
	 * Get select type
	 * @return string
	 */
	public function getSelectType()
	{
		return $this->selectType;
	}
	
	/**
	 * Set select type
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->selectType = $type;
	}	
}

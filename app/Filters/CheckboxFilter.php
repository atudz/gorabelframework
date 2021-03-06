<?php

namespace App\Filters;

use App\Core\FilterCore;

class CheckboxFilter extends FilterCore 
{
	/**
	 * The field value
	 * @var unknown
	 */
	protected $fieldValue = 1;
	
	
	/**
	 * (non-PHPdoc)
	 * @see \App\Core\FilterCore::addFilter()
	 */
	public function addFilter($model, $name, $scope='', $alias='')
	{
		$this->setName($name);
		$this->value = $this->get();
	
		if(!$this->request->has($name) && $this->request->has('submit'))
		{
			$this->value = 0;
			$this->store();
		}
		elseif($this->request->has($name)) 
		{
			$this->setValue($this->request->get($name));
			//$this->store();
		}
	
		if($alias)
		{
			$name = $alias.'.'.$name;
		}
		elseif($model instanceof Model)
		{
			$name = $model->getTable().'.'.$name;
		}
		elseif($model instanceof \Illuminate\Database\Query\Builder)
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
		
		return $scope ? $this->$scope($model) : $model->where($name,'=',$this->getValue());		
	}
	
	/**
	 * Render the filter value
	 */
	public function renderValue()
	{
		if(!$this->value)
		{
			return '';
		}
	
		$value = ($this->value) ? 'Yes' : 'No';
		return \Html::filterValue($this->label, $value);
	}
	
	/**
	 * Render the filter field
	 */
	public function render()
	{
		return \Form::filterCheckbox($this->name,$this->label, $this->fieldValue, $this->value);
	}
}
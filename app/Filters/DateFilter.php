<?php

namespace App\Filters;

use App\Core\FilterCore;
use Illuminate\Database\Query\Builder;

class DateFilter extends FilterCore
{

	/**
	 * (non-PHPdoc)
	 * @see \App\Core\FilterCore::addFilter()
	 */
	public function addFilter($model, $name, $scope='', $alias='')
	{

		$this->setName($name);
		$this->value = $this->get();
		
		if(!$this->request->has($name) || !$this->request->get($name))
		{
			return $model;
		}
		elseif($value = $this->request->get($name))
		{			
			$this->setValue($value);
			//$this->store();
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
				
		return $scope ? $this->$scope($model) : $model->where(\DB::raw('DATE('.$name.')'),'=',$this->getValue());
	}

}
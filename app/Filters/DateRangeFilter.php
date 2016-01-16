<?php
namespace App\Filters;

use App\Core\FilterCore;
use Illuminate\Database\Query\Builder;

class DateRangeFilter extends FilterCore
{

	/**
	 * (non-PHPdoc)
	 * @see \App\Core\FilterCore::addFilter()
	 */
	public function addFilter($model, $name, $scope='')
	{

		$this->setName($name);
		//$this->value = $this->get();

		if(!$this->request->has($name.'_from') || !$this->request->get($name.'_from'))
		{
			return $model;
		}
		elseif($this->request->get($name.'_from'))
		{
			$to = $this->request->get($name.'_to');
			$value = [
					'from' => $this->request->get($name.'_from'),
					'to' => $to ? $to : '9999-12-31'
			];

			$this->setValue($value);
			//$this->store();
		}

		if($model instanceof Model)
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

		return $scope ? $this->$scope($model) : $model->whereBetween(\DB::raw('DATE('.$name.')'),$this->getValue());
	}

}
<?php

namespace App\Libraries;

use App\Core\LibraryCore;
use App\Factories\ModelFactory;

/**
 * This is a library class for DataTree
 *
 * @author abner
 *
 */

class DataTreeLibrary extends LibraryCore
{
	
	/**
	 * The subject model
	 * @var $subjectModel
	 */
	protected $subjectModel;
	
	/**
	 * The relational field to parent
	 * @var $parentField
	 */
	protected $parentColumn;
	
	/**
	 * Sort order
	 * @var unknown
	 */
	protected $sort = 'asc';
	
	/**
	 * Sort column
	 * @var unknown
	 */
	protected $sortColumn;
	
	/**
	 * The resulting data
	 * @var unknown
	 */
	protected $dateTree = [];
	
	/**
	 * Prepared flag
	 * @var unknown
	 */
	protected $prepared = false;
	
	
	/**
	 * The class contructor
	 */
	public function __construct($model=null,$parent=null)
	{
		
		$this->subjectModel = $model;
		$this->parentColumn = $parent;
	}
	
	/**
	 * Sets the subject model
	 * @param unknown $model
	 */
	public function setModel($model)
	{
		$this->subjectModel = $model;
	}
	
	/**
	 * Set the parent column
	 * @param unknown $column
	 */
	public function setParentColumn($column)
	{
		$this->parentColumn = $column;
	}
	
	
	public function addSort($column, $order='asc')
	{
		$this->sortColumn = $column;
		$this->sort = $order;
	}
	
	/**
	 * Get the data tree
	 * @param string $select
	 * @return \App\Libraries\unknown
	 */
	public function getData($select='*')
	{
		// get parent root data		
		$data = [];
		$root = $this->getRoot($select);
		$pkId = $this->subjectModel->getKeyName();
		foreach($root as $index=>$parent)
		{
			$tmp = $parent;
			$tmp['sub'] = $this->getChildren($parent[$pkId],[],$select);
			$data[] = $tmp;
		}
		$this->dateTree = $data;
		$this->prepared = true;
		
		return $this->dateTree;
	}
	
	/**
	 * Get root elements 
	 * @param string $select
	 */
	public function getRoot($select='*')
	{
		$prepare = $this->subjectModel->where($this->parentColumn,'=',0);
		if($this->sortColumn)
		{
			$prepare->orderBy($this->sortColumn, $this->sort);
		}
		
		return $prepare->get($select)->toArray();		
	}
	
	/**
	 * A recursive method for retrieving parent children
	 * @param unknown $parentId
	 * @param unknown $data
	 * @param string $select
	 * @return string
	 */
	public function getChildren($parentId, $data=[], $select='*')
	{
		$prepare = $this->subjectModel->where($this->parentColumn,'=',$parentId);
		if($this->sortColumn)
		{
			$prepare->orderBy($this->sortColumn, $this->sort);
		}
		
		$elements = $prepare->get($select)->toArray();
		if(empty($elements))
		{
			return '';
		}
		
		$pkId = $this->subjectModel->getKeyName();
		foreach($elements as $index => $parent)
		{		
			$tmp = $parent;
			$tmp['sub'] = $this->getChildren($parent[$pkId],$data,$select);
			$data[$index] = $tmp;
		}
		
		return $data;
	}
	
	/**
	 * Render the data tree. Displayed using accordion bootsrap,
	 * @param unknown $data
	 * @return string
	 */
	public function render($data=[])
	{
		$html = '';
		
		if(!$this->prepared)
		{
			$this->getData();
		}
		
		$values = $data ? $data : $this->dateTree;
		if(!$values)
		{
			return $html;
		}
		
		$html = '<accordion close-others="oneAtATime">';
		foreach($values as $value)
		{
			$html .= '<accordion-group>';
			$noSub = empty($value['sub']);
			$html .= $this->getAccordHeading($value['name_en_us'], $noSub, $value['id']);
			if(!$noSub)
			{
				$html .= $this->render($value['sub']);
			}
			$html .= $this->getAccordionData($value['id']);
			$html .= '</accordion-group>';
		}
		$html .= '</accordion>';
		
		return $html;
	}
	
	/**
	 * Get the accordion header
	 * @param unknown $title
	 * @param unknown $disabled
	 * @param unknown $parentId
	 * @return string
	 */
	protected function getAccordHeading($title, $disabled, $parentId)
	{
		$html ='<accordion-heading is-disabled='.$disabled.'>'.				
				$title.
				'<span class="pull-right">'.
				'<a tooltip="View" href="/product/category/show/'.$parentId.'" class="edit-space glyphicon actions glyphicon-eye-open"></a>&nbsp;'.
				\Html::tableAction('edit','/product/category/edit/'.$parentId,'delete','/controller/product/category/destroy/'.$parentId).
				'</span>'.
				'</accordion-heading>';
		return $html;
	}
	
	
	/**
	 * Get the accordion data
	 * @param unknown $parentId
	 * @return string
	 */
	protected function getAccordionData($parentId)
	{
		$categories = ModelFactory::getInstance('ProductCategory')
							->with(['products'=>function($query) {
								$query->select('product.id','product.name_en_us');
			
							}])->where('id','=',$parentId)
							->get(['id']);

		$html = '';
		
		foreach($categories as $category)
		{
			if($category->products->isEmpty())
			{
				$html .= 'No products.';
			}
			else 
			{
				$html .= '<h5>&nbsp;Products</h5><ul>';
				foreach($category->products as $product)
				{
					$html .=  '<li>'.\Html::link('product/show/'.$product->id,$product->name_en_us).'</li>';
				}
				$html .= '</ul>';
			}
		}					
		return $html;					
	}
	
}


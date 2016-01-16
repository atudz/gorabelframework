<?php

namespace App\Types;

use App\Core\TypeCore;
use App\Interfaces\TypeableInterface as Typeable;
use Illuminate\Http\Request;
/**
 *  The Type class for User
 * @author abner
 *
 */
class UserType extends TypeCore implements Typeable
{
	/**
	 * Saves the related type data to database
	 */
	public function save(){}
	
	/**
	 * Deletes the related type data from database
	 */
	public function delete(){}
	
	/**
	 * Load data from database
	 * @param int $id
	 */
	public function load($id){}
	
	/**
	 * Populates data from source
	 */
	public function populate(Request $request){}
	
	/**
	 * Converts type data to array
	 */
	public function toArray($only=[]){}
	
	/**
	 * Converts type data to json
	 */
	public function toJson($only=[]){}
}
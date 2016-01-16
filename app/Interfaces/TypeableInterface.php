<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

/**
 *  The interface for Typeable objects
 * @author abner
 *
 */
interface TypeableInterface
{
	/**
	 * Add functions below
	 */
	
	/**
	 * Saves the related type data to database
	 */
	public function save();
	
	/**
	 * Deletes the related type data from database
	 */
	public function delete();
	
	/**
	 * Load data from database
	 * @param int $id
	 */
	public function load($id);
	
	/**
	 * Populates data from source
	 */
	public function populate(Request $request);
	
	/**
	 * Converts type data to array
	 */
	public function toArray($only=[]);
	
	/**
	 * Converts type data to json
	 */
	public function toJson($only=[]);
}
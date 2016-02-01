<?php

namespace App\Http\Presenters;

use App\Core\PresenterCore;
use App\Factories\ModelFactory;
use App\Factories\LibraryFactory;
use App\Factories\FilterFactory;
use App\Factories\TypeFactory;

class MainPresenter extends PresenterCore
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
    	// Getting Data using factory
    	
        // Getting an instance of a model User example
        $user = ModelFactory::getInstance('User');
    	        
        // Getting an instance of a Library String
        $stringLib = LibraryFactory::getInstance('String');
        
        // Getting an instance of a Filter DateRange
        $dateRange = FilterFactory::getInstance('DateRange');
        
        // Getting an instance of a Type User
        $userType = TypeFactory::getInstance('User');
        
        
        // Getting Data using facade
         
        // Getting an instance of a model User example
        $user = \Model::getInstance('User');
         
        // Getting an instance of a Library String
        $stringLib = \Library::getInstance('String');
        
        // Getting an instance of a Filter DateRange
        $dateRange = \Filter::getInstance('DateRange');
        
        // Getting an instance of a Type User
        $userType = \Type::getInstance('User');
        
    	// Passing data to view example
    	$this->view->fullname = auth()->user()->firstname . ' ' . auth()->user()->lastname;
    	
    	return $this->view('dashboard');
    }
}

<?php

namespace App\Http\Presenters;

use App\Core\PresenterCore;
use App\Factories\ModelFactory;
use App\Factories\LibraryFactory;
use App\Factories\FilterFactory;
use App\Factories\TypeFactory;
use App\Factories\AccessCheckFactory;

class MainPresenter extends PresenterCore
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
    	// Using factory
    	
        // Getting an instance of a model User example
        $user = ModelFactory::getInstance('User');
    	        
        // Getting an instance of a Library String
        $stringLib = LibraryFactory::getInstance('String');
        
        // Getting an instance of a Filter DateRange
        $dateRange = FilterFactory::getInstance('DateRange');
        
        // Getting an instance of a Type User
        $userType = TypeFactory::getInstance('User');
        
        // Perform an access check
        AccessCheckFactory::getInstance('User')->canAccess(auth()->user()->id,'view');
        
        
        // Using facade
         
        // Getting an instance of a model User example
        $user = \Model::getInstance('User');
         
        // Getting an instance of a Library String
        $stringLib = \Library::getInstance('String');
        
        // Getting an instance of a Filter DateRange
        $dateRange = \Filter::getInstance('DateRange');
        
        // Getting an instance of a Type User
        $userType = \Type::getInstance('User');
        
    	// Passing data to view example
    	$this->view->fullname = auth()->user()->fullname;
    	
    	// Perform an access check
    	\AccessCheck::getInstance('User')->canAccess(1,'view');
    
    	return $this->view('dashboard');
    }
}

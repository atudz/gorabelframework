<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Core\LibraryCore;

class MakeLibrary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:library
    						{name : The library name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Library Class.';
	
    /**
     * The template name
     */
    protected $templateName = 'Library.stub';
    
    /**
     * The template merge code
     */
    protected $mergeCode = '{classname}';
    
    /**
     * The Controller class suffix
     */
    protected $suffix = 'Library';
    
    /**
     * The Controller class file extenstion
     */
    protected $ext = '.php';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	//
    	if($classname = $this->argument('name'))
    	{
    		$classname = ucfirst($classname);
    		$template = $this->getTemplateDir().$this->templateName;
    		if(file_exists($template))
    		{
    			if(file_exists(LibraryCore::getLibraryDirectory()))
    			{
    				$text = str_replace($this->mergeCode, $classname, file_get_contents($template));
    				$path = LibraryCore::getLibraryDirectory().$classname.$this->suffix.$this->ext;
    					
    				if(!file_exists($path))
    				{
	    				if(false == file_put_contents($path,$text))
	    				{
	    					$this->error('Can\'t write file to '. LibraryCore::getLibraryDirectory().$path);
	    				}
	    				else
	    				{
	    					chmod($path,0766);
	    					$this->info($classname. ' Library class created.');
	    				}
    				}
    				else 
    				{
    					$this->error($classname.' already exist.');
    				}
    			}
    			else
    			{
    				$this->error('Library class directory not found '. LibraryCore::getLibraryDirectory());
    			}
    		}
    		else
    		{
    			$this->error('Library class template not found '. $template);
    		}
    	}
    	else
    	{
    		$this->error('No classname provided.');
    	}
    }
    
    /**
     * Gets Factory Template directory
     * @return string
     */
    public function getTemplateDir()
    {
    	return __DIR__.'/stubs/';
    }
}

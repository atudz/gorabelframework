var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
   
	/**
     * @tasking sass
     * convert sass to css save directly to public/css
     **/
	mix.sass([
	          'app.scss'],
			  'public/css/all.css')

	 /**
     * @tasking copy
     * Copy file and save to certain folder
     **/
	.copy([
	       './node_modules/bootstrap/fonts',
	       './node_modules/font-awesome/fonts'
	       ],
	       'public/fonts')
	
	 /**
     * @tasking styles
     * Get styles from library convert to one file
     **/
	.styles([
	         './node_modules/bootstrap/dist/css/bootstrap.css',
	         './node_modules/font-awesome/css/font-awesome.css'
	         ],
	         'public/css/lib.css')
	
	
	/**
     * @tasking scripts
     * Get scripts from library convert to one file
     **/
	mix.scripts([
	             './node_modules/jquery/dist/jquery.js',
	             './node_modules/bootstrap/dist/js/bootstrap.js'
	             ],
	             'public/js/lib.js')
     /**
      * @tasking scripts
      * Get scripts from resource convert to one file
      **/
	 .scripts([
	           'workspace.js'],
	           'public/js/common.js')
	           
	           
  	 /**
    * @tasking styles
    * Get styles from library convert to one file
    **/   
   	.version([
   	          'public/css/lib.css', 
   	          'public/js/lib.js'
   	          ]);

});

<?php
namespace ANTIBIOTIC;
class Autoload
{
	// Hooks to autoload.
	protected $autoload = array( 'init', 'admin_init', 'admin_menu' );

	/**
	 * Autoload Helper
	 * This will init the plugin and autoload files.
	 * @return void
	*/
	function __construct() {
		$this->autoload();
	}

	/**
	 * Load hooks
	 * This will load everything :) 
	 * @return void
	*/
	public function autoload() {
		// Get all hooks from protected var $autoload;
		$hooks = $this->autoload;

		// Loop trough hooks and add actions for those who have declared methods.
		foreach ($hooks as $hook) {
			if (method_exists($this, $hook)) 
				add_action($hook, array($this, $hook));
		}
	}

	/**
	 * Init
	 * This function will run when WordPress calls the hook "init".
	 * @return void
	*/
	public function init() {

		// Load all activated modules
		$this->load_modules();
	}	

	/**
	 * Admin Init
	 * This function will run when WordPress calls the hook "admin_init".
	 * @return void
	*/
	public function admin_init() {

		//register our settings
		register_setting( 'antibiotic-settings', 'load_modules', array('ANTIBIOTIC\Modules', 'validate_modules') );
	}

	/**
	 * Admin Menu
	 * This function will run when WordPress calls the hook "admin_menu".
	 * @return void
	*/
	public function admin_menu() {
		add_menu_page( 'DASHBOARD | WordPress Antivirus and Hack Protection', 'Antibiotic', 'administrator', 'antibiotic', array('ANTIBIOTIC\controller', 'modules') , 'dashicons-shield', 3 ); 
		add_submenu_page( 'antibiotic', 'MODULES | WordPress Antivirus and Hack Protection', 'Modules', 'administrator', 'antibiotic', array('ANTIBIOTIC\controller', 'modules') ); 
		add_submenu_page( 'antibiotic', 'PERMISSIONS | WordPress Antivirus and Hack Protection', 'Permissions', 'administrator', 'antibiotic-permissions', array('ANTIBIOTIC\controller', 'permissions') ); 
	}

	/**
	 * Load Modules
	 * This will load all activated modules
	 * @return void
	*/
	public function load_modules() {
		
		// Get all available modules from module class.
		$modules = Modules::$valid_modules;
		$options = get_option('load_modules');

		// Loop trough all available modules and include them if they are activated.
		foreach ($modules as $mod)
		{
			// Check if modules is activated.
			if (isset($options[$mod[0]]))
			{
				// Include module if it exists.
				$path_to_file = ANTIBIOTIC__PLUGIN_DIR . 'src/modules/' . $mod[0] . '.php';
				if (file_exists($path_to_file))
					require_once($path_to_file);
			}
		}

	}
}



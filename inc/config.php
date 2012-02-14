<?php
/**
 * Config
 *
 * @package WordPress
 * @subpackage Ultimate_Maintenance_mode
 * @since 0.1
 */

if ( ! class_exists( 'SeedProd_Ultimate_Maintenance_Mode' ) ) {	
    class SeedProd_Ultimate_Maintenance_Mode extends SeedProd_Framework_UMM {
        private $maintenance_mode_rendered = false;
	
        
        /**
         *  Extend the base construct and add plugin specific hooks
         */
        function __construct(){
            $seedprod_maintenancemode_options = get_option('seedprod_maintenancemode_options');
            parent::__construct();
            add_filter( 'plugin_action_links', array(&$this,'plugin_action_links'), 10, 2);
            if((isset($seedprod_maintenancemode_options['maintenance_enabled']) && in_array('1',$seedprod_maintenancemode_options['maintenance_enabled'])) || (isset($_GET['mm_preview']) && $_GET['mm_preview'] == 'true')){
                add_action('template_redirect', array(&$this,'render_maintenancemode_page'));
            }

        }
        /**
         * Display the maintenance mode page
         */
        function render_maintenancemode_page() {
            $seedprod_maintenancemode_options = get_option('seedprod_maintenancemode_options'); 
            extract($seedprod_maintenancemode_options);


            if(!is_admin()){
                
                if ( !is_user_logged_in() || (isset($_GET['mm_preview']) && $_GET['mm_preview'] == 'true')) {
                    $this->maintenance_mode_rendered = true;
                    $file = UMM_PLUGIN_DIR.'/template/maintenancemode.php';
                    include($file);
                }
                    
            }
     
        }

        function plugin_action_links($links, $file) {
            $plugin_file = 'ultimate-maintenance-mode/ultimate-maintenance-mode.php';
            if ($file == $plugin_file) {
                $settings_link = '<a href="options-general.php?page=seedprod_maintenance_mode">Settings</a>';
                array_push($links, $settings_link);
            }
            return $links;
        }


 
        
        // End of Class					
    }
}

/**
 * Config
 */
$seedprod_umm = new SeedProd_Ultimate_Maintenance_Mode ();
$seedprod_umm->plugin_type = 'free';
$seedprod_umm->plugin_short_url = 'http://bit.ly/wW7vGj';
$seedprod_umm->plugin_name = __('Maintenance Mode', 'ultimate-maintenance-mode');
$seedprod_umm->menu[] = array("type" => "add_options_page",
                         "page_name" => __("Maintenance Mode", 'ultimate-maintenance-mode'),
                         "menu_name" => __("Maintenance Mode", 'ultimate-maintenance-mode'),
                         "capability" => "manage_options",
                         "menu_slug" => "seedprod_maintenance_mode",
                         "callback" => array($seedprod_umm,'option_page'),
                         "icon_url" => plugins_url('framework/seedprod-icon-16x16.png',dirname(__FILE__)),
                        );
                        
/**
 *  Do not replace validate_function. Create unique id and copy menu slug 
 * from menu config. Create 'validate_function' if using custom validation.
 */
$seedprod_umm->options[] = array( "type" => "setting",
                "id" => "seedprod_maintenancemode_options",
				"menu_slug" => "seedprod_maintenance_mode"
				);

/**
 * Create unique id,label, create 'desc_callback' if you need custom description, attach
 * to a menu_slug from menu config.
 */
$seedprod_umm->options[] = array( "type" => "section",
                "id" => "seedprod_section_maintenance_mode",
				"label" => __("Settings", 'ultimate-maintenance-mode'),	
				"menu_slug" => "seedprod_maintenance_mode");


/**
 * Choose type, id, label, attache to a section and setting id.
 * Create 'callback' function if you are creating a custom field.
 * Optional desc, default value, class, option_values, pattern
 * Types image,textbox,select,textarea,radio,checkbox,color,custom
 */
$seedprod_umm->options[] = array( "type" => "checkbox",
                "id" => "maintenance_enabled",
				"label" => __("Enable", 'ultimate-maintenance-mode'),
				"desc" => __("Enable if you enter Maintenance Mode"),
                "option_values" => array('1'=>__('Yes', 'ultimate-maintenance-mode')),
				"section_id" => "seedprod_section_maintenance_mode",
				"setting_id" => "seedprod_maintenancemode_options",
				);
$seedprod_umm->options[] = array( "type" => "wpeditor",
                "id" => "msg",
				"label" => __("Message", 'ultimate-maintenance-mode'),
				"desc" => __("Write a message to be displayed.", 'ultimate-maintenance-mode'),
				"section_id" => "seedprod_section_maintenance_mode",
				"setting_id" => "seedprod_maintenancemode_options",
				);

				
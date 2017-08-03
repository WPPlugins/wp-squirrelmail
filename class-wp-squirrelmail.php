<?php
/*
 * Copyright (C) 2016 Edgar Hernandez
 *
 * WP-SquirrelMail is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Plugin main class
 *
 * @author Edgar Hernandez
 */
class WP_Squirrelmail {
    /**
     * Holds the singleton instance of this class
     * @since 1.0.0
     * @var WP_Squirrelmail The reference to *WP_Squirrelmail* instance of this class
     */
    private static $instance;
    
    private $settings_admin_page;
    private $settings_user_page;
    private $encryption;
    private $login_form;


    /**
     * @return WP_Squirrelmail The *WP_Squirrelmail* instance.
     */
    public static function get_instance() {
        if (null === static::$instance) {
            if ( did_action( 'plugins_loaded' ) ) {
                self::plugin_textdomain();
            } else {
                add_action( 'plugins_loaded', array( __CLASS__, 'plugin_textdomain' ), 99 );
            }
            
            static::$instance = new static();
        }
        
        return static::$instance;
    }
    
    /**
     * Protected constructor to prevent creating a new instance of the
     * *WP_Squirrelmail* via the `new` operator from outside of this class.
     */
    protected function __construct() {
        $this->add_filters();
        $this->add_actions();
        $this->require_class();
        
        // Initialize objects
        $this->encryption = new WP_Squirrelmail_Encrypt();
        $this->login_form = new WP_Squirrelmail_Login_Form();
        $this->settings_admin_page = new WP_Squirrelmail_Admin();
        
        // Add hooks for admin menus
        if( ! $this->plugin_configured() ) {
            add_action( 'admin_notices', array( $this->settings_admin_page,
                'wp_squirrelmail_admin_notices' ) );
        }
        add_action( 'admin_menu', array( $this->settings_admin_page, 'add_actions' ) );
        
        // Check if user belongs to an authorized role
        if( $this->is_allowed() ) {
            $this->settings_user_page = new WP_Squirrelmail_User( $this->encryption );
            add_action( 'admin_menu', array( $this->settings_user_page, 'add_actions' ) );
        }
    }
    
    /**
     * Check user against autorized roles 
     * @return boolean
     */
    protected function is_allowed() {
        $capability = get_option( 'wpsquirrelmail_settings_capability' );
        if( empty( $capability ) ) {
            return false;
        }
        
        $roles = array_keys( $capability );
        $user = wp_get_current_user();
        $user_role = $user->roles;
        
        foreach($roles as $key) {
            $allowed_roles[] = strtolower($key);
        }
        
        if( !array_intersect($allowed_roles, $user_role ) ) {
            return false;
        }
        return true;
    }
    
    /**
     * Private clone method to prevent cloning of the instance of the
     * *WP_Squirrelmail* instance.
     *
     * @return void
     */
    private function __clone() {
    }

    /**
     * Private unserialize method to prevent unserializing of the
     * *WP_Squirrelmail* instance.
     *
     * @return void
     */
    private function __wakeup() {
    }
    
    /**
     * Load language files
     * @return void
     */
    public static function plugin_textdomain() {
        // The third argument must not be hardcoded to account for relocated folders.
        load_plugin_textdomain( 'wp-squirrelmail', false,
                dirname( plugin_basename( WPSQUIRRELMAIL__PLUGIN_FILE ) )
                . '/languages/' );
    }
    
    /**
     * Place a link in the plugins page
     * @param string $links
     * @return string
     */
    public function wpsquirrelmail_settings_link($links) {
        $settings_link = '<a href="options-general.php?page=wpsquirrelmail-admin">'
                . __('Settings', 'wp-squirrelmail') . '</a>';
        array_unshift($links, $settings_link);
        
        return $links; 
    }
    
    /**
     * Returns the location of WPSquirrelMail's lib directory.
     * This filter is applied in require_lib().
     * @filter require_lib_dir
     * @param string $lib_dir
     * @return void
     */
    public function require_lib_dir( $lib_dir ) {
        return WPSQUIRRELMAIL__PLUGIN_DIR . '_inc/lib';
    }
    
    /**
     * Check if option exist in the database
     * @return boolean
     */
    public function plugin_configured() {
        $action = get_option( 'wpsquirrelmail_settings_action' );
        if( ! $action ) {
            return false;
        }
        return true;
    }
    
    /**
     * Initializes WordPress filters
     */
    protected function add_filters() {
        add_filter( 'wpsquirrelmail_require_lib_dir', array( $this, 'require_lib_dir' ) );
        add_filter("plugin_action_links_" . plugin_basename(WPSQUIRRELMAIL__PLUGIN_FILE), 
            array( $this, 'wpsquirrelmail_settings_link' ) );
    }
    
    /**
     * Initializes Wordpress Actions
     */
    protected function add_actions() {
        add_action( "wp_ajax_wp_squirrelmail", array( $this, 'prefix_ajax_wp_squirrelmail' ) );
        add_action( "wp_ajax_nopriv_wp_squirrelmail", array( $this, 'prefix_ajax_wp_squirrelmail' ) );
    }
    
    public function prefix_ajax_wp_squirrelmail() {
        try {
            $user_id = get_current_user_id();
            $profile = get_the_author_meta('wpsquirrelmail_user_settings', $user_id );
            $username = esc_attr( $profile['username'] );
            $password = esc_attr( $profile['password'] );
            $autologin = (int) esc_attr( $profile['autologin'] );
            
            $decrypt = $this->encryption;
            
            $form = $this->login_form;
            $form->setAction( get_option( 'wpsquirrelmail_settings_action' ) );
            $form->setUsername( $decrypt->getDecrypt( $username ) );
            $form->setPassword( $decrypt->getDecrypt( $password ) );
            $form->setAutoLogin($autologin);
            $login_form = $form->getForm();
            
            $data['response'] = true;
            $data['content'] = $login_form;
            $data['autologin'] = $autologin;
            $data['css'] = plugins_url( "wp-squirrelmail/css/bootstrap.min.css" );
            
            wp_send_json( $data );
            
        } catch (Exception $e) {
            $data['response'] = false;
            $data['message'] = __('Unable to load the login form.', 'wp-squirrelmail')
                    . " " . $e->getMessage();
            
            wp_send_json( $data );
        }
    }

    /**
     * add required classes
     */
    protected function require_class() {
        $require_file = new Require_Lib();
        $require_file->require_lib( 'classes/class-wp-squirrelmail-settings' );
        $require_file->require_lib( 'classes/class-wp-squirrelmail-admin' );
        $require_file->require_lib( 'classes/class-wp-squirrelmail-user' );
        $require_file->require_lib( 'classes/class-wp-squirrelmail-encrypt' );
        $require_file->require_lib( 'classes/class-wp-squirrelmail-login-form');
    }
}

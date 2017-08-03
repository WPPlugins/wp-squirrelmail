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
 * Shared logic between WP-SquirrelMail pages
 *
 * @author Edgar Hernandez
 */
abstract class WP_Squirrelmail_Settings {
    /**
     * Add page specific actions given the page hook
     */
    abstract function add_page_actions( $hook );
    
    /**
     * Create a menu item for the page and returns the hook
     */
    abstract function get_page_hook();
    
    /**
     * Enqueue and localize page specific scripts
     */
    abstract function page_scripts();
    
    /**
     * Render page specific HTML
     */
    abstract function page_render();

    /**
     * Prints Admin Note asking the Administrator to complete the plugin setup
     */
    public function wp_squirrelmail_admin_notices() {
        echo '<div id="notice" class="notice notice-success is-dismissible">'
        . '<p><a href="https://wordpress.org/plugins/wp-squirrelmail/" target="_blank">'
                . WPSQUIRRELMAIL__NAME . " " . WPSQUIRRELMAIL__VERSION
                . '</a> '
                . __('is not configured. ', 'wp-squirrelmail')
                . '<a href="options-general.php?page=wpsquirrelmail-admin">'
                . __('Please configure it now.', 'wp-squirrelmail')
                . '</a>'
                . '</p></div>';
    }
    
    /**
     * Add actions common to all admin pages
     */
    public function add_actions() {
        // Initialize menu item for the page in the user
        $hook = $this->get_page_hook();
        
        // Attach hooks common to all WP SquirrelMail User pages based on the created
        add_action( "admin_print_styles-$hook",  array( $this, 'admin_styles'    ) );
        add_action( "admin_print_scripts-$hook", array( $this, 'admin_scripts'   ) );
        
        // Attach page specific actions in addition to the above
        $this->add_page_actions( $hook );
    }
    
    // Enqueue the email stylesheet
    public function admin_styles() {
        wp_enqueue_style( 'wpsquirrelmail-email', plugins_url( "css/bootstrap.min.css", WPSQUIRRELMAIL__PLUGIN_FILE ), false, '3.3.6', 'all' );
    }
    
    // Add page specific scripts
    public function admin_scripts() {
        $this->page_scripts(); // Delegate to inheriting class
    }
    
    /**
     * Render the page specific content
     */
    public function render() {
        $this->page_render();
    }
    
    /**
     * Array of roles authorized by the Administrator to use WPSquirrelMail
     * @return array
     */
    public function allowed_roles() {
        $roles = get_editable_roles();
        
        $roleName = array();
        foreach($roles as $role) {
            $roleName[] = $role['name'];
        }
        
        return $roleName;
    }
}

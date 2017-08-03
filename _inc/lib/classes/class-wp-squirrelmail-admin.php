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
 * Builds the admin settings page and its menu
 *
 * @author Edgar Hernandez
 */
class WP_Squirrelmail_Admin extends WP_Squirrelmail_Settings {
    
    // Register page actions
    public function add_page_actions($hook) {
        add_action( "admin_init", array( $this, 'register_settings_admin' ) );
    }

    public function get_page_hook() {
        add_submenu_page(
                'options-general.php',                  // admin page slug
                WPSQUIRRELMAIL__NAME . " "
                . __( 'Options', 'wp-squirrelmail' ),   // page title
                WPSQUIRRELMAIL__NAME,                   // menu title
                'manage_options',                       // capability required to see the page
                'wpsquirrelmail-admin',                 // admin page slug, e.g. options-general.php?page=wpsquirrelmail_options
                array( $this, 'render' )                // callback function to display the options page
        );
    }

    public function page_scripts() {}

    public function page_render() {
        include WPSQUIRRELMAIL__PLUGIN_DIR . "/_inc/lib/pages/admin_settings.php";
    }
    
    public function register_settings_admin() {
        // ('Settings section', 'Seting Name')
        register_setting( 'wpsquirrelmail_options', 'wpsquirrelmail_settings_action' );
        register_setting( 'wpsquirrelmail_options', 'wpsquirrelmail_settings_capability' );
    }
}

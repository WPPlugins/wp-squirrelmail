<?php
/**
 * Plugin Name: WP SquirrelMail
 * Plugin URI: https://wordpress.org/plugins/wp-squirrelmail/
 * Description: Connect to SquirrelMail from within WordPress.
 * Version: 1.1
 * Author: Edgar Hernandez
 * Author URI: http://edgarjhernandez.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-squirrelmail
 * Domain Path: /languages/
 * Network: false
 * 
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

defined( 'ABSPATH' ) or die( __("I'm sorry, Dave, I'm afraid I can't do that.", 'wp-squirrelmail') );

define( 'WPSQUIRRELMAIL__NAME',         'WP SquirrelMail' );
define( 'WPSQUIRRELMAIL__VERSION',      '1.1' );
define( 'WPSQUIRRELMAIL_MASTER_USER',   true );
define( 'WPSQUIRRELMAIL__PLUGIN_DIR',   plugin_dir_path( __FILE__ ) );
define( 'WPSQUIRRELMAIL__PLUGIN_FILE',  __FILE__ );

if ( is_admin() ) {
    require_once( WPSQUIRRELMAIL__PLUGIN_DIR . 'class-require-lib.php'      );
    require_once( WPSQUIRRELMAIL__PLUGIN_DIR . 'class-wp-squirrelmail.php'  );
    
    add_action( 'init', array( 'WP_Squirrelmail', 'get_instance' ) );
}

<?php
/*
 * Copyright (C) 2016 Edgar Hernandez
 *
 * This program is free software: you can redistribute it and/or modify
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
 * Add require files
 *
 * @author Edgar Hernandez
 */
class Require_Lib {
    
    protected $slug;
    protected $basename;
    protected $choices;

    public function __construct() {
        $this->set_wp_content_dir();
    }
    
    public function require_lib($slug) {
        $this->set_slug($slug);
        $this->set_basename();
        $this->set_choices();
        
        foreach($this->choices as $file_name ) {
            if ( is_readable( $file_name ) ) {
                require_once $file_name;
                
                return;
            }
        }
        
        trigger_error(
            printf(
                /* translators: %s: Name of a slug */
                __('Cannot find a library with slug "%s".', 'wp-squirrelmail'),
                    $slug), E_USER_ERROR );
    }
    
    protected function set_choices() {
        $lib_dir = WP_CONTENT_DIR . '/lib';
        $lib_dir = apply_filters( 'wpsquirrelmail_require_lib_dir', $lib_dir );
        $this->choices = array(
            $lib_dir . "/" . $this->slug . ".php",
            $lib_dir . "/" . $this->slug . "/0-load.php",
            $lib_dir . "/" . $this->slug . "/" . $this->basename . ".php",
        );
    }
    
    protected function set_slug( $slug ) {
        if ( !preg_match( '|^[a-z0-9/_.-]+$|i', $slug ) ) {
            trigger_error(
                    printf( /* translators: %s: Name of a slug */
                            __('Cannot load a library with invalid slug "%s".',
                                    'wp-squirrelmail'), $slug), E_USER_ERROR
                    );
            return;
        }
        
        $this->slug = $slug;
    }
    
    protected function set_basename() {
        $this->basename = basename( $this->slug );
    }
    
    protected function set_wp_content_dir() {
        if ( defined( 'ABSPATH' ) && ! defined( 'WP_CONTENT_DIR' ) ) {
            // no trailing slash, full paths only.
            define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
        }
    }
}

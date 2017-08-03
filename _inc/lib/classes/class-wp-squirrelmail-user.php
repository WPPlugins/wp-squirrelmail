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

class WP_Squirrelmail_User extends WP_Squirrelmail_Settings {
    // Show the settings page only when WPSquirrelMail is connected or in dev mode
    protected $dont_show_if_not_active = true;
    protected $encryption;
    protected $usermeta;
    
    /**
     * Builds the user settings in the profile page and its menu 
     * @param WP_Squirrelmail_Encrypt $encryption
     */
    public function __construct( WP_Squirrelmail_Encrypt $encryption ) {
        $this->encryption = $encryption;
    }

    // Register page actions
    public function add_page_actions( $hook ) {
        add_action( 'show_user_profile', array( $this, 'show_extra_profile_fields' ) );
        add_action( 'edit_user_profile', array( $this, 'show_extra_profile_fields' ) );
        
        add_action( 'personal_options_update', array( $this, 'save_extra_profile_fields' ) );
        add_action( 'edit_user_profile_update', array( $this, 'save_extra_profile_fields' ) );
        // Add JavaScript to User - Profile page
        add_action( "admin_print_scripts-profile.php", array( $this, 'profile_scripts'   ) );
    }
    
    public function profile_scripts() {
        wp_enqueue_script(
                'wp-squirrelmail-profile-js', 
                plugins_url( '_inc/wp-squirrelmail-profile.js', WPSQUIRRELMAIL__PLUGIN_FILE ), 
                array( 'jquery' ), WPSQUIRRELMAIL__VERSION . '-1'
        );
    }
    
    // Adds the Settings sub menu
    public function get_page_hook() {
        $hook = add_submenu_page(
                'users.php',
                WPSQUIRRELMAIL__NAME,
                WPSQUIRRELMAIL__NAME,
                'read',
                'wpsquirrelmail-email',
                array( $this, 'render' )
        );
        
        return $hook;
    }
    
    public function page_scripts() {
        wp_enqueue_script(
                'wp-squirrelmail-iframe-js', 
                plugins_url( '_inc/wp-squirrelmail-iframe.js', WPSQUIRRELMAIL__PLUGIN_FILE ), 
                array( 'jquery' ), WPSQUIRRELMAIL__VERSION . '-1'
        );
    }
    
    public function page_render() {
        ?>
<div class="wrap">
    <span><?php echo $this->page_links(); ?></span>
    <hr />
    <div class="wp-squirrelmail" id="wp-squirrelmail-div">
        <p>Loading...</p>
    </div>
    <div class="footer">
        <hr />
        <span><?php echo $this->page_links(); ?></span>
        <span class="pull-right">
            <?php echo __( 'I hope you enjoy the plugin and give it a good rating.', 'wp-squirrelmail'); ?>
        </span>
    </div>
</div>
<?php
    }
    
    protected function get_usermeta( $user_id ) {
        $this->usermeta = (array) get_the_author_meta('wpsquirrelmail_user_settings', $user_id );
    }
    
    protected function get_field_value( $index ) {
        if( !array_key_exists($index, $this->usermeta) ) {
            $this->usermeta[$index] = false;
        }
        
        return $this->usermeta[$index];
    }
            
    function show_extra_profile_fields( $user ) {
        $decrypt = $this->encryption;
        $this->get_usermeta($user->ID);
        ?>
<a name="wp-squirrelmail-profile"></a>
<h3><?php echo WPSQUIRRELMAIL__NAME . " " . __('Login Credentials.', 'wp-squirrelmail'); ?></h3>
<table class="form-table">
    <tr>
        <th><label for="wp-squirrelmail-username"><?php echo __('Email', 'wp-squirrelmail'); ?></label></th>
        <td>
            <input type="email" name="wp-squirrelmail-username" id="wp-squirrelmail-username"
                    placeholder="<?php echo __('Email', 'wp-squirrelmail'); ?>"
                    value="<?php echo esc_attr( $decrypt->getDecrypt($this->get_field_value('username') ) ); ?>"
                    class="regular-text" /><br />
            <span class="description"><?php echo __('Please enter your Email.', 'wp-squirrelmail'); ?></span>
        </td>
    </tr>
    <tr>
        <th><label for="wp-squirrelmail-password"><?php echo __('Password', 'wp-squirrelmail'); ?></label></th>
        <td>
            <input type="password" name="wp-squirrelmail-password" id="wp-squirrelmail-password"
                    value="<?php echo esc_attr( $decrypt->getDecrypt($this->get_field_value('password') ) ); ?>"
                    placeholder="Password" class="regular-text" /><br />
            <span class="description"><?php echo __('Please enter your Password.',
                    'wp-squirrelmail'); ?></span>
        </td>
    </tr>
    <tr>
        <th><?php echo __('Autologin', 'wp-squirrelmail'); ?></th>
        <td>
            <label for="wp-squirrelmail-autologin">
            <input type="checkbox" name="wp-squirrelmail-autologin" id="wp-squirrelmail-autologin"
                    value="1" <?php echo checked( 1, esc_attr($this->get_field_value('autologin') ) ); ?> />
            <?php echo __('Automatic Login.', 'wp-squirrelmail'); ?>
            </label><br />
            <span class="description">
                <?php echo __('Username and Password is required to'
                        . ' select this option.', 'wp-squirrelmail'); ?>
            </span>
        </td>
    </tr>
</table>
<?php }
    
    /**
     * Create menu bar
     * @return string user menu
     */
    protected function page_links() {
        $plugin = WPSQUIRRELMAIL__NAME . " " . WPSQUIRRELMAIL__VERSION;
        $links = [
            $plugin => 'https://wordpress.org/plugins/wp-squirrelmail/',
            __('Settings', 'wp-squirrelmail') => 'profile.php#wp-squirrelmail-profile',
            __('Donations', 'wp-squirrelmail') => 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9JTUB6PVKFT86',
            ];
        
        $menu = "| ";
        foreach( $links as $label => $link ) {
            $menu.= '<a href="' . $link . '"';
                    if( false !== strpos( $link, 'http' ) ) {
                        $menu.= ' target="_blank"';
                    }
            $menu.= '>' . $label . '</a> | ';
        }
        
        return $menu;
    }

    public function save_extra_profile_fields( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) ) {
                return false;
        }
        
        $username = filter_input( INPUT_POST, 'wp-squirrelmail-username', FILTER_SANITIZE_EMAIL );
        $password = filter_input( INPUT_POST, 'wp-squirrelmail-password', FILTER_SANITIZE_STRING );
        $autologin = filter_input( INPUT_POST, 'wp-squirrelmail-autologin', FILTER_VALIDATE_INT );
        
        $encrypt = $this->encryption;
        $profile_options = array(
            'username'  => $encrypt->getEncrypt( $username ),
            'password'  => $encrypt->getEncrypt( $password ),
            'autologin' => $autologin
        );
        
        update_user_meta( absint( $user_id ), 'wpsquirrelmail_user_settings',
                wp_kses_post( $profile_options ) );
    }
}

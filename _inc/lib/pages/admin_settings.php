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
 * HTML for the Admin Settings form
 *
 * @author Edgar Hernandez
 */
?>
<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields( 'wpsquirrelmail_options' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label class="description" for="wpsquirrelmail_settings_action">
                        <?php echo __( 'Form action: ', 'wp-squirrelmail' ); ?>
                    </label>
                </th>
                <td>
                    <?php $action = get_option( 'wpsquirrelmail_settings_action' ); ?>
                    <input type='text' name='wpsquirrelmail_settings_action'
                           value='<?php echo $action; ?>'
                           placeholder="<?php echo __('Form action attribute', 'wp-squirrelmail'); ?>"
                           required="required"/>
                    <p class="description">
                        <?php _e('The url to summit the login form, e.g.'
                                . ' "webmail/src/redirect.php"', 'wp-squirrelmail'); ?>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <p class="description"><?php _e( 'Capability: ', 'wp-squirrelmail' ); ?></p>
                </th>
                <td>
                    <?php $allowed_roles = (array) get_option( 'wpsquirrelmail_settings_capability', array() ); ?>
                    <?php $roles = $this->allowed_roles(); ?>
                    <?php for($i=0;$i<count($roles);$i++): ?>
                    <?php
                    if ( !array_key_exists( $roles[$i], $allowed_roles)) {
                        $allowed_roles[$roles[$i]] = 0;
                    }
                    ?>
                    <input type="checkbox"
                           name="wpsquirrelmail_settings_capability[<?php echo $roles[$i]; ?>]"
                           value="1" <?php checked( 1, $allowed_roles[$roles[$i]] ); ?> />
                    <label><?php echo $roles[$i]; ?></label><br />
                    <?php endfor; ?>
                    
                    <p class="description">
                        <?php _e('Select the roles that need access.', 'wp-squirrelmail'); ?>
                    </p>
                </td>
            </tr>
            <tr valign="top"><th scope="row"></th>
                <td>
                    <p class="submit">
                        <input type="submit" name="Submit"
                               class="button-primary"
                               value="<?php esc_attr_e('Save Changes', 'wp-squirrelmail') ?>" />
                    </p>
                </td>
            </tr>
        </table>
    </form>
</div><!-- .wrap -->

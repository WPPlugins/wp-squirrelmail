<?php
// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$meta_type  = 'user';
$user_id    = 0; // This will be ignored, since we are deleting for all users.
$meta_key   = 'wpsquirrelmail_user_settings';
$meta_value = ''; // Also ignored. The meta will be deleted regardless of value.
$delete_all = true;

delete_metadata( $meta_type, $user_id, $meta_key, $meta_value, $delete_all );

delete_option( 'wpsquirrelmail_settings_action' );
delete_option( 'wpsquirrelmail_settings_capability' );

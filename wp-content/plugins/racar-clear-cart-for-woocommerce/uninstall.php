<?php 

// if uninstall.php is not called by WordPress, die
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}
 

$options_name = 'rccwoo_settings';
delete_option( $options_name );

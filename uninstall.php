<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die();
}

function delete_plugin_folder() {
    $plugin_folder = plugin_dir_path( __FILE__ );

    if ( is_dir( $plugin_folder ) ) {
        $result = rmdir( $plugin_folder );

        if ( ! $result ) {
            echo 'Error: Unable to delete the plugin folder.';
        }
    }
}

register_uninstall_hook( __FILE__, 'delete_plugin_folder' );
?>

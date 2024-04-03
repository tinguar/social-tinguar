<?php

function st_menu() {
    add_menu_page(
        'Social Tinguar', 
        'Social Tinguar', 
        'manage_options', 
        'st-button-settings',
        'st_settings_page',
        plugin_dir_url(__FILE__) . 'images/logo.png',
    
    );
    add_action('admin_init', 'st_button_register_settings');
}

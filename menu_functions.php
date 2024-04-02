<?php

function st_menu() {
    add_menu_page(
        'Tinguar WhatsApp', 
        'Tinguar WhatsApp', 
        'manage_options', 
        'st-button-settings',
        'st_settings_page');
    add_action('admin_init', 'st_button_register_settings');
}

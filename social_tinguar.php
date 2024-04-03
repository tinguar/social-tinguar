<?php

/*
 * Plugin Name:       Social Tinguar
 * Plugin URI:        https://blog.tinguar.com/
 * Description:       Con este plugin podras poner botones para contacto Fácil, primera version con WhatsApp
 * Version:           1.0.0
 * Requires at least: 4.2
 * Requires PHP:      7.2
 * Author:            Alberto Guaman
 * Author URI:        https://github.com/tinguar
 * License:           GPL v3
 * License URI:       https://github.com/tinguar/social-tinguar/blob/main/LICENSE
 */

require_once(plugin_dir_path(__FILE__) . 'models/countries.php');
require_once(plugin_dir_path(__FILE__) . 'menu_functions.php');

function enqueue_st_button_scripts() {
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css');
    wp_enqueue_style('whatsapp-sticky-button-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('whatsapp-sticky-button-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_st_button_scripts');

function display_st_button() {
    $is_active = get_option('st_button_active', '1');
    if ($is_active !== '1') {
        return; 
    }
    $selected_country = get_option('st_button_country', '593');
    $phone_number = get_option('st_button_phone_number', '998602204');
    $position = get_option('st_button_position', 'bottom-right');
    $button_text = get_option('st_button_text', 'WhatsApp');

    $position_class = esc_attr($position); 
    echo '<div id="whatsapp-sticky-button" class="floating_btn ' . $position_class . '"> <a href="https://api.whatsapp.com/send/?phone=' . esc_attr($selected_country) . '' . esc_attr($phone_number) . '&text=' . urlencode($button_text) . '" target="_blank"> <div class="contact_icon">  <img style="width:40px;" src="' . plugin_dir_url(__FILE__) . 'images/whatsapp-icon.png" alt="WhatsApp">    </div> </a> </div>';
}




add_action('wp_footer', 'display_st_button');

function add_st_button_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=st-button-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename", 'add_st_button_settings_link');

function st_settings_page() {
    $current_user = wp_get_current_user();
    $username = $current_user->user_login; 
    $formatted_username = ucwords(strtolower($username));
    ?>
    <div >
    <link rel="stylesheet" type="text/css" href="<?php echo plugin_dir_url(__FILE__) . 'css/style.css'; ?>">
         <h1>Bienvenido, <?php echo $formatted_username; ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('st_button_settings');
            do_settings_sections('st_button_settings');
            submit_button();
            
            ?>
        </form>

     
        <div style="margin-top: 30px;  20px; border-top: 1px solid #000000; text-align: center;">
            <p id="whatsapp-sticky-button-style" class="footer_section_c" style="color:#000000;">Sugerencias o colaboracion contactar a: <a style="text-decoration: none;" href="https://tinguar.com/index.php/redes-sociales/" target="_blank">Tinguar</a></p>
        </div>
    </div>
    <?php
}


function st_button_register_settings() {
    register_setting('st_button_settings', 'st_button_active');
    register_setting('st_button_settings', 'st_button_country');
    register_setting('st_button_settings', 'st_button_phone_number');
    register_setting('st_button_settings', 'st_button_text');
    register_setting('st_button_settings', 'st_button_position');

    add_settings_section('st_button_main', '', 'st_button_section_text', 'st_button_settings');

    add_settings_field('st_button_active', 'Activar Boton', 'st_button_active_input', 'st_button_settings', 'st_button_main');
    add_settings_field('st_button_country','Selecione Pais','st_button_country_input','st_button_settings','st_button_main');
    add_settings_field('st_button_phone_number','Número de teléfono de WhatsApp', 'st_button_phone_number_input', 'st_button_settings', 'st_button_main');
    add_settings_field('st_button_text', 'Texto Personalizado (OPCIONAL)', 'st_button_text_input', 'st_button_settings', 'st_button_main');
    add_settings_field('st_button_position', 'Posición del botón', 'st_button_position_input', 'st_button_settings', 'st_button_main');
    
}

function st_button_section_text() {
    echo '<p>Ingrese su número de teléfono de WhatsApp, personalice la posición del botón y configure el texto del botón:</p>';
}

function st_button_active_input() {
    $is_active = get_option('st_button_active', '1');
    $checked = checked($is_active, '1', false);
    echo '<label><input type="checkbox" name="st_button_active" value="1" ' . $checked . '> Para visualizacion en su pagina</label>';
}

function st_button_country_input(){
    global $country_list; 
    $selected_country = get_option('st_button_country', '593'); 
    $select_options = '';
    foreach ($country_list as $code => $name) {
        $selected = selected($selected_country, $code, false); 
        $select_options .= '<option value="' . esc_attr($code) . '" ' . $selected . '>' . esc_html($name) . '</option>';
    }

    echo '<select id="country" name="st_button_country">' . $select_options . '</select>';
}


function st_button_phone_number_input() {
    $phone_number = get_option('st_button_phone_number', '998602204');
    echo '<input type="text" name="st_button_phone_number" value="' . esc_attr($phone_number) . '" />';
}

function st_button_position_input() {
    $position = get_option('st_button_position', 'bottom-right');
    $positions = array(
        'bottom-left' => 'Abajo a la izquierda',
        'bottom-right' => 'Abajo a la derecha',
    );

    echo '<select name="st_button_position">';
    foreach ($positions as $value => $label) {
        $selected = selected($value, $position, false);
        echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
}




function st_button_text_input() {
    $button_text = get_option('st_button_text', 'WhatsApp');
    echo '<input type="text" name="st_button_text" value="' . esc_attr($button_text) . '" />';
}

add_action('admin_menu', 'st_menu');
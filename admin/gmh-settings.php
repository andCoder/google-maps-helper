<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.04.2017
 * Time: 10:22
 */
require_once GMH_MAIN_PATH . '/includes/class-plugin-settings.php';

add_action('admin_menu', 'add_gmh_settings_page');
add_action('admin_init', 'display_options');

function add_gmh_settings_page()
{
    add_options_page('Google Maps Helper', 'Google Maps Helper', 'manage_options', 'gmh_settings', 'gmh_settings');
}

function gmh_settings()
{
    ?>
    <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <h1>Google Maps Helper</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('gmh_general_settings');
            do_settings_sections('gmh_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function display_options()
{
    global $tag;
    add_settings_section('general_settings', 'General plugin settings', 'gmh_print_general_section', 'gmh_settings');

    $field_name = \GoogleMapsHelper\Includes\Plugin_Settings::API_KEY_FIELD;
    add_settings_field("{$field_name}", 'Google maps API key', 'gmh_display_api_key_field', 'general_settings');
    register_setting('gmh_general_settings', $field_name);
}

function gmh_print_general_settings()
{
    echo '<h3>General settings</h3>';
}

function gmh_display_api_key_field()
{
    $key = \GoogleMapsHelper\Includes\Plugin_Settings::get_api_key();
    $field_name = \GoogleMapsHelper\Includes\Plugin_Settings::API_KEY_FIELD;
    echo "<input type='text' name='{$field_name}' value='" . !empty($key) ? esc_attr($key) : '' . "'/>";
}

function gmh_plugin_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=google_maps_helper">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

add_filter("plugin_action_links_" . GMH_PLUGIN_NAME, 'gmh_plugin_settings_link');
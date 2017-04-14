<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.04.2017
 * Time: 10:22
 */
if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access is denied.' );
}

use \GoogleMapsHelper\Includes\Gmh_Plugin_Settings;

add_action( 'admin_menu', 'add_gmh_settings_page' );
add_action( 'admin_init', 'gmh_display_settings' );
add_action( 'admin_enqueue_scripts', 'gmh_load_settings_script' );
add_action( 'admin_post_gmh_delete_marker', 'gmh_delete_marker' );

add_filter( 'plugin_action_links_' . GMH_PLUGIN_NAME, 'gmh_plugin_settings_link' );

function gmh_plugin_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=google_maps_helper">Settings</a>';
    array_unshift( $links, $settings_link );
    return $links;
}


function add_gmh_settings_page() {
    add_options_page( 'Google Maps Helper', 'Google Maps Helper', 'manage_options', 'gmh_settings', 'gmh_settings' );
}

function gmh_settings() {
    ?>
    <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <h1>Google Maps Helper</h1>
        <form method="post" action="options.php?action=update" enctype="multipart/form-data">
            <?php
            settings_fields( 'gmh_general_settings_group' );
            do_settings_sections( 'gmh_settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function gmh_display_settings() {
    add_settings_section( 'gmh_general_settings', 'General plugin settings', null, 'gmh_settings' );

    $field_name = Gmh_Plugin_Settings::API_KEY_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'Google maps API key', 'gmh_display_api_key_field', 'gmh_settings', 'gmh_general_settings', array( 'name' => $field_name ) );

    $field_name = Gmh_Plugin_Settings::DISPLAY_STREETMAP_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'Display Street map', 'gmh_print_display_streetmap_field', 'gmh_settings', 'gmh_general_settings', array( 'name' => $field_name ) );

    $field_name = Gmh_Plugin_Settings::JSON_URL_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'JSON source URL', 'gmh_display_json_source_field', 'gmh_settings', 'gmh_general_settings', array( 'name' => $field_name ) );

    $field_name = Gmh_Plugin_Settings::JSON_POINT_LATITUDE_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'JSON point latitude field name', 'gmh_display_json_point_latitude_field', 'gmh_settings', 'gmh_general_settings', array( 'name' => $field_name ) );

    $field_name = Gmh_Plugin_Settings::JSON_POINT_LONGITUDE_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'JSON point longitude field name', 'gmh_display_json_point_longitude_field', 'gmh_settings', 'gmh_general_settings', array( 'name' => $field_name ) );

    add_settings_section( 'gmh_additional_settings', 'Additional plugin settings', null, 'gmh_settings' );

    $field_name = Gmh_Plugin_Settings::JSON_VARIABLES_TO_DISPLAY_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'Enter JSON fields to display', 'gmh_print_input_json_vars_field', 'gmh_settings', 'gmh_additional_settings', array( 'name' => $field_name ) );

    $field_name = Gmh_Plugin_Settings::MARKER_ICON_FILE_NAME_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name, 'gmh_save_marker_icon' );
    add_settings_field( "{$field_name}_field", 'Marker icon', 'gmh_display_marker_file_input', 'gmh_settings', 'gmh_additional_settings', array( 'name' => $field_name ) );
}

function gmh_display_api_key_field( $atts ) {
    $key = Gmh_Plugin_Settings::get_api_key();
    ?>
    <textarea type="text" name="<?php echo $atts['name']; ?>"
              style="width:300px" required><?php echo ! empty( $key ) ? esc_attr( $key ) : ''; ?></textarea>
    <?php
}

function gmh_print_display_streetmap_field( $atts ) {
    $is_displayed = Gmh_Plugin_Settings::display_streetmap();
    ?>
    <input type="checkbox" name="<?php echo $atts['name']; ?>" value="1" <?php checked( 1, $is_displayed, true ); ?>/>
    <?php
}

function gmh_display_json_source_field( $atts ) {
    $url = Gmh_Plugin_Settings::get_json_url();
    ?>
    <input type="url" name="<?php echo $atts['name']; ?>" value="<?php echo ! empty( $url ) ? esc_attr( $url ) : ''; ?>"
           style="width:300px" required/>
    <?php
}

function gmh_display_json_point_latitude_field( $atts ) {
    $field_name = Gmh_Plugin_Settings::get_json_latitude_field_name();
    ?>
    <input type="text" name="<?php echo $atts['name']; ?>"
           value="<?php echo ! empty( $field_name ) ? esc_attr( $field_name ) : ''; ?>" required/>
    <?php
}

function gmh_display_json_point_longitude_field( $atts ) {
    $field_name = Gmh_Plugin_Settings::get_json_longitude_field_name();
    ?>
    <input type="text" name="<?php echo $atts['name']; ?>"
           value="<?php echo ! empty( $field_name ) ? esc_attr( $field_name ) : ''; ?>" required/>
    <?php
}

function gmh_print_input_json_vars_field( $atts ) {
    $vars = Gmh_Plugin_Settings::get_json_variables_to_display();
    ?>
    <textarea type="text" name="<?php echo $atts['name']; ?>"
              style="width:300px"><?php echo ! empty( $vars ) ? esc_attr( $vars ) : ''; ?></textarea>
    <?php
}

function gmh_display_marker_file_input( $atts ) {
    $url = Gmh_Plugin_Settings::get_marker_icon();
    $icon_src = ! empty( $url ) ? esc_attr( $url ) : '';
    ?>
    <div>
        <p>
            <img class="img-responsive" width="45px" src="<?php echo $icon_src; ?>"
                 alt="marker" id="gmh-marker-icon"/>
            <?php if ( ! empty( $icon_src ) ) { ?>
                <a href="#" id="delete-marker-icon">delete</a>
            <?php } ?>
        </p>
        <p>
            <input type="file" name="<?php echo $atts['name']; ?>" value="<?php echo $icon_src; ?>"/>
            <input type="hidden" name="<?php echo $atts['name']; ?>" id="<?php echo $atts['name']; ?>"
                   value="<?php echo $icon_src; ?>"/>
        </p>
    </div>
    <?php
}

function gmh_save_marker_icon( $option ) {
    if ( ! empty( $_FILES[Gmh_Plugin_Settings::MARKER_ICON_FILE_NAME_FIELD]["tmp_name"] ) ) {
        $urls = wp_handle_upload( $_FILES[Gmh_Plugin_Settings::MARKER_ICON_FILE_NAME_FIELD], array( 'test_form' => FALSE ) );
        $temp = $urls["url"];
        return $temp;
    }
    return $option;
}

function gmh_load_settings_script( $hook ) {
    if ( $hook != 'settings_page_gmh_settings' ) {
        return;
    }
    wp_enqueue_script( 'gmh_settings_script', GMH_PLUGIN_URL . '/js/settings.js', array( 'jquery' ), null, true );
}

function gmh_delete_marker() {
    $file_url = Gmh_Plugin_Settings::get_marker_icon();
    if ( ! empty( $file_url ) ) {
        $file_path = str_replace( $_SERVER['HTTP_ORIGIN'], $_SERVER['DOCUMENT_ROOT'], esc_url( $file_url ) );
        if ( file_exists( $file_path ) ) {
            unlink( $file_path );
            Gmh_Plugin_Settings::set_marker_icon( '' );
        }
    }
}


<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.04.2017
 * Time: 10:22
 */

add_action( 'admin_menu', 'add_gmh_settings_page' );
add_action( 'admin_init', 'gmh_display_settings' );
add_filter( "plugin_action_links_" . GMH_PLUGIN_NAME, 'gmh_plugin_settings_link' );

function gmh_plugin_settings_link( $links )
{
    $settings_link = '<a href="options-general.php?page=google_maps_helper">Settings</a>';
    array_unshift( $links, $settings_link );
    return $links;
}


function add_gmh_settings_page()
{
    add_options_page( 'Google Maps Helper', 'Google Maps Helper', 'manage_options', 'gmh_settings', 'gmh_settings' );
}

function gmh_settings()
{
    ?>
    <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <h1>Google Maps Helper</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'gmh_general_settings_group' );
            do_settings_sections( 'gmh_settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function gmh_display_settings()
{
    add_settings_section( 'general_settings', 'General plugin settings', 'gmh_print_general_section', 'gmh_settings' );

    $field_name = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::API_KEY_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'Google maps API key', 'gmh_display_api_key_field', 'gmh_settings', 'general_settings', array( 'name' => $field_name ) );

    $field_name = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::DISPLAY_STREETMAP_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'Display Street map', 'gmh_print_display_streetmap_field', 'gmh_settings', 'general_settings', array( 'name' => $field_name ) );

    $field_name = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::JSON_URL_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'JSON source URL', 'gmh_display_json_source_field', 'gmh_settings', 'general_settings', array( 'name' => $field_name ) );

    $field_name = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::JSON_VARIABLES_TO_DISPLAY_FIELD;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'Enter JSON fields to display', 'gmh_print_input_json_vars_field', 'gmh_settings', 'general_settings', array( 'name' => $field_name ) );

    $field_name = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::MARKER_ICON_FILE_NAME;
    register_setting( 'gmh_general_settings_group', $field_name );
    add_settings_field( "{$field_name}_field", 'Marker icon', 'gmh_display_marker_file_input', 'gmh_settings', 'general_settings', array( 'name' => $field_name ) );
}

function gmh_print_general_settings()
{
    echo '<h3>General settings</h3>';
}

function gmh_display_api_key_field( $atts )
{
    $key = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::get_api_key();
    ?>
    <textarea type="text" name="<?php echo $atts['name']; ?>"
              style="width:300px"><?php echo ! empty( $key ) ? esc_attr( $key ) : ''; ?></textarea>
    <?php
}

//TODO: to make a checkbox work properly
function gmh_print_display_streetmap_field( $atts )
{
    $is_displayed = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::display_streetmap();
    ?>
    <input type="checkbox" name="<?php echo $atts['name']; ?>" value="1" <?php checked( 1, $is_displayed, true ); ?>/>
    <?php
}

function gmh_display_json_source_field( $atts )
{
    $url = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::get_json_url();
    ?>
    <input type="url" name="<?php echo $atts['name']; ?>" value="<?php echo ! empty( $url ) ? esc_attr( $url ) : ''; ?>"
           style="width:300px"/>
    <?php
}

function gmh_print_input_json_vars_field( $atts )
{
    $vars = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::get_json_variables_to_display();
    ?>
    <textarea type="text" name="<?php echo $atts['name']; ?>"
              style="width:300px"><?php echo ! empty( $vars ) ? esc_attr( $vars ) : ''; ?></textarea>
    <?php
}

function gmh_display_marker_file_input( $atts )
{
    $url = \GoogleMapsHelper\Includes\Gmh_Plugin_Settings::get_marker_icon();
    ?>
    <div>
        <p><img class="img-responsive" width="20px" src="<?php echo ! empty( $url ) ? esc_attr( $url ) : ''; ?>"
                alt="marker"/></p>
        <p><input type="file" name="<?php echo $atts['name']; ?>"
                  value="<?php echo ! empty( $url ) ? esc_attr( $url ) : ''; ?>"/></p>
    </div>
    <?php
}


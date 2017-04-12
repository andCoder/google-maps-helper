<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.04.2017
 * Time: 14:13
 */

namespace GoogleMapsHelper\Includes;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access is denied.' );
}

if ( ! defined( 'GMH_TAG' ) ) {
    define( 'GMH_TAG', 'gmh' );
}

class Gmh_Plugin_Settings
{
    //options fields
    const API_KEY_FIELD = GMH_TAG . '_api_key';
    const DISPLAY_STREETMAP_FIELD = GMH_TAG . '_display_streetmap';
    const JSON_URL_FIELD = GMH_TAG . '_json_url';
    const JSON_POINT_LONGITUDE_FIELD = GMH_TAG . '_json_lng';
    const JSON_POINT_LATITUDE_FIELD = GMH_TAG . '_json_lat';
    const JSON_VARIABLES_TO_DISPLAY_FIELD = GMH_TAG . '_json_variables_to_display';
    const MARKER_ICON_FILE_NAME_FIELD = GMH_TAG . '_marker_icon_file_name';

    const DEFAULT_REFRESH_INTERVAL = 30000;

    //for development purposes only!!!
    const DEVELOPER_KEY = 'AIzaSyBBopFVPzEjfKrK_upkbcYlK3wddBwFTXg';

    public static function get_api_key() {
        return get_option( Gmh_Plugin_Settings::API_KEY_FIELD, Gmh_Plugin_Settings::DEVELOPER_KEY );
    }

    public static function display_streetmap() {
        return get_option( Gmh_Plugin_Settings::DISPLAY_STREETMAP_FIELD, 1 );
    }

    public static function get_json_url() {
        return get_option( Gmh_Plugin_Settings::JSON_URL_FIELD, '' );
    }

    public static function get_json_latitude_field_name() {
        return get_option( Gmh_Plugin_Settings::JSON_POINT_LATITUDE_FIELD, 'latitude' );
    }

    public static function get_json_longitude_field_name() {
        return get_option( Gmh_Plugin_Settings::JSON_POINT_LONGITUDE_FIELD, 'longitude' );
    }

    public static function get_json_variables_to_display() {
        return get_option( Gmh_Plugin_Settings::JSON_VARIABLES_TO_DISPLAY_FIELD, 'all' );
    }

    public static function get_marker_icon() {
        return get_option( Gmh_Plugin_Settings::MARKER_ICON_FILE_NAME_FIELD, '' );
    }
}
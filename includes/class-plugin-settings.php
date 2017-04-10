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


class Plugin_Settings {
	const TAG = 'gmh';
    const DEFAULT_REFRESH_INTERVAL = 30000;

	public static function get_api_key() {
		return get_option( Plugin_Settings::TAG . '_api_key', '' );
	}

	public static function display_streetmap() {
		return get_option( Plugin_Settings::TAG . '_display_streetmap', true );
	}

	public static function get_json_url() {
		return get_option( Plugin_Settings::TAG . 'json_url', '' );
	}

	public static function get_json_variables_to_display() {
		return get_option( Plugin_Settings::TAG . '_json_variables_to_display', 'all' );
	}

	public static function get_marker_icon() {
		return get_option( Plugin_Settings::TAG . 'marker_icon_file_name', '' );
	}
}
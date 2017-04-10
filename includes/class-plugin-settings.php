<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.04.2017
 * Time: 14:13
 */

namespace GoogleMapsHelper\Includes;


class Plugin_Settings {
	const TAG = 'gmh';
	private $default_refresh_interval = 30000;

	public function get_api_key() {
		return get_option( Plugin_Settings::TAG . '_api_key', '' );
	}

	public function get_map_title() {
		return get_option( Plugin_Settings::TAG . '_map_title', '' );
	}

	public function get_map_type() {
		return get_option( Plugin_Settings::TAG . '_map_type', '' );
	}

	public function get_map_refresh_interval() {
		return get_option( Plugin_Settings::TAG . '_refresh_interval', $this->default_refresh_interval );
	}

	public function display_streetmap() {
		return get_option( Plugin_Settings::TAG . '_display_streetmap', true );
	}

	public function get_json_url() {
		return get_option( Plugin_Settings::TAG . 'json_url', '' );
	}

	public function get_json_variables_to_display() {
		return get_option( Plugin_Settings::TAG . '_json_variables_to_display', 'all' );
	}

	public function get_marker_icon() {
		return get_option( Plugin_Settings::TAG . 'marker_icon_file_name', '' );
	}
}
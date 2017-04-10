<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.04.2017
 * Time: 13:00
 */

namespace GoogleMapsHelper\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access is denied.' );
}

class Google_Map_Helper {
	private $render;

	public function __construct() {
		$this->add_hooks();
	}

	private function add_hooks() {
		add_action( 'init', array( $this, 'init_plugin' ) );

	}

	private function init_plugin() {
		add_shortcode( 'google_maps_helper', array( $this, 'print_content' ) );

		require 'class-plugin-settings.php';
		require_once 'class-map-render.php';

		$this->render = new Google_Map_Render( Plugin_Settings::get_api_key() );

		add_filter( 'the_content', array( $this->render, 'print_map' ) );
		add_action( 'wp_print_footer_scripts', array( $this->render, 'add_script' ) );
	}

	private function print_content( $atts, $content = null ) {
		$options = shortcode_atts( array(
			'map_title' => '',
			'map_type'  => ''
		), $atts );
	}
}

new Google_Map_Helper();
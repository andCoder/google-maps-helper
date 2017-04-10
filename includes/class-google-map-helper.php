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
        add_shortcode('google-maps-helper', array($this, 'print_content'));

		require 'class-plugin-settings.php';
		require_once 'class-map-render.php';

		$this->render = new Google_Map_Render( Plugin_Settings::get_api_key() );

		add_filter( 'the_content', array( $this->render, 'print_map' ) );
		add_action( 'wp_print_footer_scripts', array( $this->render, 'add_script' ) );
	}

    private function print_content($atts)
    {
		$options = shortcode_atts( array(
            'map_title' => '',
            'map_type' => Google_Map_Render::MAP_TYPE_ROADMAP,
            'refresh_interval' => Plugin_Settings::DEFAULT_REFRESH_INTERVAL
		), $atts );

        $this->render->set_map_title($options['title']);
        $this->render->set_map_type($options['map_type']);
        $this->render->set_refresh_interval($options['refresh_interval']);

        return $this->render->get_map();
	}
}

new Google_Map_Helper();
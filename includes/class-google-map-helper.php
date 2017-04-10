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
        $this->init_plugin();
	}

	private function init_plugin() {
        $this->register_scripts();

		require 'class-plugin-settings.php';
		require_once 'class-map-render.php';

		$this->render = new Google_Map_Render( Plugin_Settings::get_api_key() );
		add_action( 'wp_print_footer_scripts', array( $this->render, 'add_script' ) );
	}

    private function register_scripts()
    {
        wp_register_style('gmh-css', GMH_PLUGIN_URL . '/css/gmh.css');
        wp_enqueue_style('gmh-css');
    }

    public function print_content($atts)
    {
		$options = shortcode_atts( array(
            'title' => '',
            'type' => Google_Map_Render::MAP_TYPE_ROADMAP,
            'refresh_interval' => Plugin_Settings::DEFAULT_REFRESH_INTERVAL
		), $atts );

        $this->render->set_map_title(esc_attr($options['title']));
        $this->render->set_map_type(esc_attr($options['type']));
        $this->render->set_refresh_interval(esc_attr($options['refresh_interval']));

        return $this->render->get_map();
	}
}

$helper = new Google_Map_Helper();
add_shortcode('google-maps-helper', array($helper, 'print_content'));
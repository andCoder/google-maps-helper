<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 10.04.2017
 * Time: 11:48
 */

namespace GoogleMapsHelper\Includes;

/*
* Class adds JS to the footer section
*/

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access is denied.' );
}


class Google_Map_Render
{
    const MAP_TYPE_ROADMAP = 'roadmap';
    const MAP_TYPE_SATELLITE = 'sattelite';
    const MAP_TYPE_HYBRID = 'hybrid';
    const MAP_TYPE_TERRAIN = 'terrain';

    private $map_options = array();

    function __construct( $key ) {
        $this->set_google_api_key( $key );
    }

    public function set_google_api_key( $key ) {
        $this->map_options['api_key'] = $key;
    }

    public function set_map_title( $title ) {
        $this->map_options['title'] = $title;
    }

    public function set_marker_icon( $icon ) {
        $this->map_options['icon'] = $icon;
    }

    public function set_refresh_interval( $intervalInMillis ) {
        $this->map_options['refreshInterval'] = $intervalInMillis;
    }

    public function set_map_type( $type ) {
        $this->map_options['map_type'] = $type;
    }

    public function set_map_center( $center ) {
        $center_arr = mb_split( ';', $center );
        $this->map_options['map_center'][] = trim( $center_arr[0] );
        $this->map_options['map_center'][] = trim( $center_arr[1] );
    }

    public function set_zoom( $zoom ) {
        $this->map_options['zoom'] = $zoom;
    }

    public function set_json_url( $url ) {
        $this->map_options['json_url'] = $url;
    }

    public function set_latitude_field_name( $name ) {
        $this->map_options['json_latitude_field'] = $name;
    }

    public function set_longitude_field_name( $name ) {
        $this->map_options['json_longitude_field'] = $name;
    }

    public function set_json_fields( $fields_string ) {
        $fields = mb_split( ',', $fields_string );
        foreach ( $fields as $field ) {
            $this->map_options['json_fields'][] = trim( $field );
        }
    }

    public function display_streetmap( $display ) {
        $this->map_options['display_streetmap'] = $display;
    }

    private function get_info_window_template() {
        $content = file_get_contents( GMH_MAIN_PATH . '/includes/info_window.tpl' );
        $content = apply_filters( GMH_TAG . '_before_print_info_window', $content );
        return $content;
    }

    public function get_map() {
        $content = '';
        if ( isset( $this->map_options['title'] ) )
            $content .= '<h2>' . $this->map_options['title'] . '</h2>';
        $content .= '<div id="map"></div>';
        return $content;
    }

    public function print_options() {
        $this->map_options['info_window'] = $this->get_info_window_template();
        wp_localize_script( 'gmh-map-js', 'options', $this->map_options );
    }

    public function add_script() {
        if ( isset( $this->map_options['api_key'] ) ) {
            wp_register_script( 'google-map-clusters', "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js", null, false, true );
            wp_enqueue_script( 'google-map-clusters' );

            wp_register_script( 'google-sdk', "https://maps.googleapis.com/maps/api/js?key={$this->map_options['api_key']}", null, false, true );
            wp_enqueue_script( 'google-sdk' );

            wp_register_script( 'gmh-parser-js', GMH_PLUGIN_URL . '/js/template-parser.js', null, false, true );
            wp_enqueue_script( 'gmh-parser-js' );

            wp_register_script( 'gmh-map-js', GMH_PLUGIN_URL . '/js/map.js', array( 'google-map-clusters', 'google-sdk', 'gmh-parser-js', 'jquery' ), false, true );
            wp_enqueue_script( 'gmh-map-js' );
        }
    }


}
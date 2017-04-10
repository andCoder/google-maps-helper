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

	public function get_map() {
		return '<div id="map"></div>';
	}

	public function add_script()
    {
	    if ( isset( $this->map_options['api_key'] ) ) {

		    echo '<script async defer src="https://maps.googleapis.com/maps/api/js?key=' . $this->map_options['api_key']
		         . '&callback=initMap">
    </script>';
            wp_localize_script(GMH_MAIN_PATH . '/js/map.js', 'options', $this->map_options);
            echo '<script src="' . GMH_PLUGIN_URL . '/js/map.js" type="text/javascript"/>';
	    }
    }


}
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

class Google_Map_Render
{
    private $map_key;

    function __construct( $key ) {
        $this->set_google_api_key( $key );
        $this->add_hooks();
    }

    public function set_google_api_key( $key ) {
        $map_key = $key;
    }

    private function add_hooks() {
        add_action( 'wp_print_footer_scripts', array( 'add_script', $this ) );
    }

    public function add_script()
    {

    }
}
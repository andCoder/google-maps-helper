<?php

/*
Plugin Name: Google Maps Helper
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Admin
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access is denied.' );
}

define('GMH_MAIN_PATH', dirname(__FILE__));
define('GMH_PLUGIN_URL', plugins_url('/google-maps-helper'));

require_once 'includes/class-google-map-helper.php';


<?php

if ( !defined( 'ABSPATH' ) ) die();

/*
Plugin Name: bnpolloftheday
Plugin URI:  example.com
Description: Poll of the day module for Divi
Version:     0.0.1
Author:      Blakeman & Nawoor
Author URI:  example.com/author
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: bnpotd-bnpolloftheday
Domain Path: /languages

bnpolloftheday is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

bnpolloftheday is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with bnpolloftheday. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

$data = get_file_data( __FILE__, array( 'Version' => 'Version', 'Domain Path' => 'Domain Path' ) );
define( 'BNPOLLOFTHEDAY_VER', $data[ 'Version' ] );
define( 'BNPOLLOFTHEDAY_DOMAIN_PATH', '/' . trim( $data[ 'Domain Path' ], '/' ) . '/' ); // /languages/

define( 'BNPOLLOFTHEDAY_MAIN_FILE',  __FILE__ );
define( 'BNPOLLOFTHEDAY_URL', plugin_dir_url( BNPOLLOFTHEDAY_MAIN_FILE ) );
define( 'BNPOLLOFTHEDAY_PATH', plugin_dir_path( BNPOLLOFTHEDAY_MAIN_FILE ) );

require_once BNPOLLOFTHEDAY_PATH . 'includes/modules/BNPollOfTheDay/BNPollOfTheDay_Class.php';
require_once BNPOLLOFTHEDAY_PATH . 'includes/modules/BNPollOfTheDay/BNPollOfTheDay_DBFunctions.php';
require_once BNPOLLOFTHEDAY_PATH . 'includes/modules/BNPollOfTheDay/upgrade-activate-functions.php';

register_activation_hook( BNPOLLOFTHEDAY_MAIN_FILE, 'bnpolloftheday_activate' );

if ( ! function_exists( 'bnpotd_initialize_extension' ) ):
	/**
	 * Creates the extension's main class instance.
	 *
	 * @since 1.0.0
	 */
	function bnpotd_initialize_extension()
	{
		require_once plugin_dir_path( __FILE__ ) . 'includes/Bnpolloftheday.php';

		//Plugin starts here...

		bnpolloftheday_setTables();
	}
	add_action( 'divi_extensions_init', 'bnpotd_initialize_extension' );
	add_action( 'wp_enqueue_scripts', 'bnpolloftheday_initScriptStyle' );
endif;

function bnpolloftheday_setTables()
{
	global $wpdb;
	$wpdb->bnpolloftheday_questions   = $wpdb->prefix .'bnpolloftheday_questions';
	$wpdb->bnpolloftheday_options   = $wpdb->prefix .'bnpolloftheday_options';
	$wpdb->bnpolloftheday_iplog = $wpdb->prefix .'bnpolloftheday_iplog';
}

function bnpolloftheday_initScriptStyle()
{
	// Registers the javascript for the plugin
	wp_register_script( 'bnpolloftheday-custom-script', plugins_url( '/includes/modules/BNPollOfTheDay/main.js', __FILE__ ), array('jquery') );
	// Enqueue the script
	wp_enqueue_script( 'bnpolloftheday-custom-script' );

	// Registers the CSS for the plugin
	wp_register_style( 'bnpolloftheday-custom-style', plugins_url( '/includes/modules/BNPollOfTheDay/style.css', __FILE__ ), array(), '20180723', 'all' );
	 // Enqueue the style
	 wp_enqueue_style( 'bnpolloftheday-custom-style' );
}
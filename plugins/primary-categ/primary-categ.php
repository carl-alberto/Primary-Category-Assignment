<?php
/**
 * Plugin Name: Primary Categ
 * Version: 1.0.0
 * Plugin URI: https://carl.alber2.com/
 * Description: This is your starter template for your next WordPress plugin.
 * Author: Carl Alberto
 * Author URI: https://carl.alber2.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: primary-categ
 * Domain Path: /languages/
 *
 * @package Primary Categ
 * @author Carl Alberto
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files.
require_once( 'includes/class-primary-categ.php' );
require_once( 'includes/class-primary-categ-settings.php' );

// Load plugin libraries.
require_once( 'includes/lib/class-primary-categ-admin-api.php' );
require_once( 'includes/lib/class-primary-categ-post-type.php' );
require_once( 'includes/lib/class-primary-categ-taxonomy.php' );

// Load custom functionalities.
require_once( 'includes/class-primary-categ-main.php' );

/**
 * Returns the main instance of Primary_Categ to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Primary_Categ
 */
function primary_categ() {
	// Plugin main variables.
	$latest_plugin_version = '1.0.0';
	$settings_prefix = 'plg1_';

	$pluginoptions = array(
		'settings_prefix' => $settings_prefix,
	);

	$instance = Primary_Categ::instance( __FILE__,
		$latest_plugin_version,
		$pluginoptions
	);

	if ( is_null( $instance->settings ) ) {
		$instance->settings = Primary_Categ_Settings::instance( $instance );
	}

	return $instance;
}

primary_categ();

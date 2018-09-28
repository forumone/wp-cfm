<?php
/**
 * Plugin Name: WooCart Defaults
 * Description: Manage and deploy WordPress + WooCommerce configuration changes.
 * Version:     @##VERSION##@
 * Runtime:     7.2+
 * Author:      WooCart
 * Text Domain: woocart-defaults
 * Domain Path: i18n
 * Author URI:  www.woocart.com
 */


namespace Niteo\WooCart {

	require_once __DIR__ . '/vendor/autoload.php';

	use Niteo\WooCart\Defaults\Filters;
	use Niteo\WooCart\Defaults\Shortcodes;
	use Niteo\WooCart\Defaults\GDPR;
	use Niteo\WooCart\Defaults\AdminDashboard;

	if ( class_exists( 'WP_CLI' ) ) {
		\WP_CLI::add_command( 'wcd', __NAMESPACE__ . '\Defaults\CLI_Command' );
	}

	if ( function_exists( 'add_shortcode' ) ) {
		new Shortcodes();
	}

	if ( function_exists( 'do_shortcode' ) ) {
		new Filters();
	}

	/**
	 * 1. Consent notification to comply with GDPR.
	 * 2. Panel for the store in the WP admin dashboard.
	 */
	if ( function_exists( 'add_action' ) ) {
		new GDPR();
		new AdminDashboard();
	}
}

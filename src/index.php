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

	use Niteo\WooCart\Defaults\Shortcodes;

	if ( class_exists( 'WP_CLI' ) ) {
		\WP_CLI::add_command( 'wcd', __NAMESPACE__ . '\Defaults\CLI_Command' );
	}

	if ( function_exists( 'add_shortcode' ) ) {
		new Shortcodes();
	}
}

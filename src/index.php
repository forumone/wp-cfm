<?php

namespace Niteo\WooCart;

/**
 * Plugin Name: WooCart Defaults
 * Description: Manage and deploy WordPress + WooCommerce configuration changes.
 * Version:     @##VERSION##@
 * Runtime:     7.2+
 * Author:      WooCart
 * Text Domain: woocart-defaults
 * Domain Path: i8n
 * Author URI:  www.woocart.com
 */

\WP_CLI::add_command( 'wcd', __NAMESPACE__ . '\Defaults\CLI_Command' );  // @codeCoverageIgnore

<?php
/**
 * Manage configuration options.
 *
 * @package woocart-defaults
 */
class WCD_CLI_Command extends WP_CLI_Command {


	/**
	 * Pull a bundle into the database
	 *
	 * ## OPTIONS
	 *
	 * <bundle_name>
	 * : The bundle name to import (or use "all")
	 *
	 * ## EXAMPLES
	 *
	 * wp config pull bundle_name
	 *
	 * @synopsis <bundle_name> <path_with_trailing_slash> [--network]
	 *
	 * @access public
	 */
	public function pull( $args, $assoc_args ) {
		if ( isset( $assoc_args['network'] ) ) {
			WooCart\WooCartDefaults\WCD()->options->is_network = true;
		}

		if ( isset( $args[0] ) && isset( $args[1] ) ) {
			$response = WooCart\WooCartDefaults\WCD()->readwrite->pull_bundle( $args[0], $args[1] );

			if ( $response ) {
				WP_CLI::success( 'The bundle has been pulled into the database.' );
			} else {
				WP_CLI::error( 'There was an error in pushing data to the database.' );
			}
		} else {
			WP_CLI::error( 'Bundle name and path are required to pull data from the filesystem.' );
		}
	}

}

WP_CLI::add_command( 'wcd', 'WCD_CLI_Command' );

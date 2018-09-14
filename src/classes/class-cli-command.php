<?php
/**
 * WP-CLI commands.
 *
 * @category   Plugins
 * @package    WordPress
 * @subpackage woocart-defaults
 * @since      1.0.0
 */

namespace Niteo\WooCart\Defaults {

	use WP_CLI;
	use WP_CLI_Command;


	/**
	 * Class CLI_Command
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	class CLI_Command extends WP_CLI_Command {

		/**
		 * Imports bundle to database.
		 *
		 * ## OPTIONS
		 *
		 * <path>
		 * : Zhe path to file that should be imported.
		 * ---
		 * default: success
		 * options:
		 *   - success
		 *   - error
		 * ---
		 *
		 * ## EXAMPLES
		 *
		 *     wp wcd import /my/bundle.yaml
		 *
		 * @when after_wp_load
		 * @param $args array list of command line arguments.
		 * @param $assoc_args array of named command line keys.
		 * @throws WP_CLI\ExitException on wrong command.
		 */
		public function import( $args, $assoc_args ) {
			list($path) = $args;

			if ( ! file_exists( $path ) ) {
				WP_CLI::error( "$path cannot be found." );
			}

			$importer = new Importer();
			try {
				$importer->import( $path );
				WP_CLI::success( 'The bundle has been pulled into the database.' );
			} catch ( \Exception $e ) {
				WP_CLI::error( 'There was an error in pushing data to the database.' );
			}

		}

	}
}



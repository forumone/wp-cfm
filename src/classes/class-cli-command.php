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

    use Niteo\WooCart\Defaults\Importers\SellingLimit;
    use Niteo\WooCart\Defaults\Importers\WooPage;
	use WP_CLI;
	use WP_CLI_Command;


	/**
	 * WooCart Defaults Importer
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
		 * : The path to file that should be imported.
		 *
		 * ## EXAMPLES
		 *
		 *     wp wcd import /my/bundle.yaml
		 *
		 * @codeCoverageIgnore
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
				WP_CLI::success( "The $path has been pulled into the database." );
			} catch ( \Exception $e ) {
				WP_CLI::error( "There was an error in pushing $path to the database." );
			}

		}

		/**
		 * Imports page to database.
		 *
		 * ## OPTIONS
		 *
		 * <path>
		 * : The path to file that should be imported.
		 *
		 * ## EXAMPLES
		 *
		 *     wp wcd insert_page /my/page.html
		 *
		 * @codeCoverageIgnore
		 * @when after_wp_load
		 * @param $args array list of command line arguments.
		 * @param $assoc_args array of named command line keys.
		 * @throws WP_CLI\ExitException on wrong command.
		 */
		public function insert_page( $args, $assoc_args ) {
			list($path) = $args;

			if ( ! file_exists( $path ) ) {
				WP_CLI::error( "$path cannot be found." );
			}

			$page = new WooPage( $path );
			try {
				$meta = $page->getPageMeta();
				$id   = $page->insertPage( $meta );
				WP_CLI::success( "The page $path has been inserted as $id." );
			} catch ( \Exception $e ) {
				WP_CLI::error( "There was an error in pushing $path to the database." );
			}

		}

		/**
		 * Sets the woocommerce_all_except_countries based on shipping region.
		 *
		 * ## OPTIONS
		 *
		 * <region>
		 * : One of the regions in shipping table.
		 *
		 * ## EXAMPLES
		 *
		 *     wp wcd sell_only_to EU
		 *
		 * @codeCoverageIgnore
		 * @when after_wp_load
		 * @param $args array list of command line arguments.
		 * @param $assoc_args array of named command line keys.
		 * @throws WP_CLI\ExitException on wrong command.
		 */
		public function sell_only_to( $args, $assoc_args ) {
			list($zone) = $args;

			$limit =  new SellingLimit($zone);

			if ( ! $limit->zoneID() ) {
				WP_CLI::error( "$zone cannot be found." );
			}

			try {
				$countries = $limit->countries($limit->zoneID());
                update_option("woocommerce_all_except_countries", $countries);
                $list = implode(",", $countries);
				WP_CLI::success( "The region $zone with ($list) has been inserted to woocommerce_all_except_countries." );
			} catch ( \Exception $e ) {
				WP_CLI::error( "There was an error in pushing $zone to the database." );
			}

		}

	}
}

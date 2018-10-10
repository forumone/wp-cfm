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

	use Niteo\WooCart\Defaults\Generators\Product;
	use Niteo\WooCart\Defaults\Importers\SellingLimit;
	use Niteo\WooCart\Defaults\Importers\WooPage;
	use Niteo\WooCart\Defaults\Importers\WooProducts;
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

			$limit = new SellingLimit( $zone );

			if ( ! $limit->zoneID() ) {
				WP_CLI::error( "$zone cannot be found." );
			}

			try {
				$countries = $limit->countries( $limit->zoneID() );
				update_option( 'woocommerce_all_except_countries', $countries );
				$list = implode( ',', $countries );
				WP_CLI::success( "The region $zone with ($list) has been inserted to woocommerce_all_except_countries." );
			} catch ( \Exception $e ) {
				WP_CLI::error( "There was an error in pushing $zone to the database." );
			}

		}

		/**
		 * Generate products.
		 *
		 * ## OPTIONS
		 *
		 * <amount>
		 * : The amount of products to generate
		 * ---
		 * default: 100
		 * ---
		 *
		 * ## EXAMPLES
		 * wp wcd products 100
		 *
		 * @param array $args Argumens specified.
		 * @param array $assoc_args Associative arguments specified.
		 */
		public function products( $args, $assoc_args ) {
			list($amount) = $args;
			$progress     = \WP_CLI\Utils\make_progress_bar( 'Generating products', $amount );
			for ( $i = 1; $i <= $amount; $i++ ) {
				Product::generate();
				$progress->tick();
			}
			$progress->finish();
			WP_CLI::success( $amount . ' products generated.' );
		}

		/**
		 * Import demo products.
		 *
		 * ## OPTIONS
		 *
		 * <path>
		 * : Path to file with products
		 *
		 * [--common=<common_path>]
		 * : Path to .common directory for localizations
		 * ---
		 * default: /provision/localizations/Countries/.common/
		 * ---
		 *
		 * ## EXAMPLES
		 * wp wcd demo_products /provision/localizations/Countries/.common/products-electronics.html
		 *
		 * @param array $args Arguments specified.
		 * @param array $assoc_args Associative arguments specified.
		 */
		public function demo_products( $args, $assoc_args ) {
			list($path) = $args;

			if ( ! file_exists( $path ) ) {
				WP_CLI::error( "$path cannot be found." );
			}

			$products = new WooProducts( $assoc_args['common'] );
			$products->add_products( $path );

			WP_CLI::success( $products->get_product_count() . ' products added.' );
		}

	}
}

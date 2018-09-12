<?php
/**
 * WooCommerce options integration.
 *
 * @package woocart-defaults
 */
class WOO_Options {


	/**
	 * Class Constructor.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct() {
		add_filter( 'wcd_configuration_items', array( &$this, 'configuration_items' ) );
		add_filter( 'wcd_pull_callback', array( &$this, 'pull_callback' ), 10, 2 );
	}

	/**
	 * Get configuration items from the database.
	 *
	 * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query = "
            SELECT option_name, option_value FROM $wpdb->options
            WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
            AND option_name LIKE 'woocommerce_%'
            ORDER BY option_name";

		$results = $wpdb->get_results( $query );

		foreach ( $results as $op ) {
			$items[ 'woo/' . $op->option_name ] = array(
				'value' => $op->option_value,
				'label' => $op->option_name,
				'group' => 'WooCommerce Options',
			);
		}

		return $items;
	}

	/**
	 * Tell WCD to use import_terms() for taxonomy items.
	 *
	 * @access public
	 */
	public function pull_callback( $callback, $callback_params ) {
		if ( 'woo/' == substr( $callback_params['name'], 0, 4 ) ) {
			return array( &$this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) taxonomies into the DB.
	 *
	 * @param string $params['name']
	 * @param string $params['group']
	 * @param string $params['old_value'] The old settings (DB)
	 * @param string $params['new_value'] The new settings (file)
	 *
	 * @access public
	 */
	public function import_terms( $params ) {
		$new_value = str_replace( 'woo/', '', $params['name'] );
		update_option( $new_value, $params['new_value'] );
	}

}

if ( ! defined( 'WCD_TESTS' ) ) {
	new WOO_Options();
}

<?php

namespace WooCart\WooCartDefaults;

/**
 * Registry class.
 *
 * @package woocart-defaults
 */
class WCD_Registry {


	/**
	 * Class Constructor.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __construct() {
		add_filter( 'wcd_configuration_items', array( &$this, 'disallowed_items' ) );
	}

	/**
	 * Each configuration item is a key/value pair.
	 * The `wcd_configuration_items` filter allows devs to
	 * register additional configuration items.
	 *
	 * @access public
	 */
	public function get_configuration_items() {
		global $wpdb;

		$items = array();
		return apply_filters( 'wcd_configuration_items', $items );
	}

	/**
	 * Ignore certain configuration settings.
	 *
	 * @access public
	 */
	public function disallowed_items( $items ) {
		$disallowed_items = array(
			'auth_key',
			'auth_salt',
			'cron',
			'db_version',
			'home',
			'logged_in_key',
			'logged_in_salt',
			'nonce_key',
			'nonce_salt',
			'rewrite_rules',
			'siteurl',
			'active_plugins',
		);

		foreach ( $disallowed_items as $row ) {
			unset( $items[ $row ] );
		}

		return $items;
	}

}

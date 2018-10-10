<?php

namespace Niteo\WooCart\Defaults {


	/**
	 * Class Filters
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	class Filters {

		/**
		 * Filters constructor.
		 */
		public function __construct() {
			add_filter( 'option_woocommerce_checkout_privacy_policy_text', [ &$this, 'do_filter' ] );
			add_filter( 'option_woocommerce_registration_privacy_policy_text', [ &$this, 'do_filter' ] );
		}

		/**
		 * @param $value
		 * @return null
		 */
		function do_filter( string $value ) {
			return do_shortcode( $value );
		}

	}
}

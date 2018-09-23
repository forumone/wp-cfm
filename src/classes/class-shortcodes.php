<?php

namespace Niteo\WooCart\Defaults {


	/**
	 * Class Shortcodes
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	class Shortcodes {


		/**
		 * @param $props
		 * @param null  $content
		 * @return null
		 */
		function page( $props, $content = null ) {
			global $wpdb;

			if ( array_key_exists( 'page', $props ) ) {
				$query   = $wpdb->prepare( "SELECT post_content from $wpdb->posts where post_type = 'page' and post_name = %s", $props['page'] );
				$content = $wpdb->get_var( $query );
			}

			if ( array_key_exists( 'post', $props ) ) {
				$query   = $wpdb->prepare( "SELECT post_content from $wpdb->posts where post_type = 'post' and post_name = %s", $props['post'] );
				$content = $wpdb->get_var( $query );
			}
			return $content;
		}

		/**
		 * @param $props
		 * @param null  $content
		 * @return null
		 */
		function store_name( $props, $content = null ) {
			$content = get_option( 'blogname' );
			return $content;
		}

		/**
		 * @param $props
		 * @param null  $content
		 * @return null
		 */
		function company_name( $props, $content = null ) {
			$content = get_option( 'woocommerce_company_name' );
			return $content;
		}

		/**
		 * @param $props
		 * @param null  $content
		 * @return null
		 */
		function tax_id( $props, $content = null ) {
			$content = get_option( 'woocommerce_tax_id' );
			return $content;
		}

		/**
		 * @param $props
		 * @param null  $content
		 * @return null
		 */
		function store_url( $props, $content = null ) {
			$url     = site_url();
			$name    = get_option( 'blogname' );
			$content = sprintf( '<a href="%s">%s</a>', $url, $name );
			return $content;
		}

		/**
		 * @param $props
		 * @param null  $content
		 * @return null
		 */
		function policy_page( $props, $content = null ) {
			$url     = get_permalink( get_option( 'wp_page_for_privacy_policy' ) );
			$content = sprintf( '<a href="%s">%s</a>', $url, $url );
			return $content;
		}

		/**
		 * Shortcodes constructor.
		 */
		public function __construct() {
			add_shortcode( 'woo-include', [ &$this, 'page' ] );
			add_shortcode( 'company-name', [ &$this, 'company_name' ] );
			add_shortcode( 'tax-id', [ &$this, 'tax_id' ] );
			add_shortcode( 'policy-page', [ &$this, 'policy_page' ] );
			add_shortcode( 'store-url', [ &$this, 'store_url' ] );
			add_shortcode( 'store-name', [ &$this, 'store_name' ] );
		}

	}
}

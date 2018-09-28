<?php

namespace Niteo\WooCart\Defaults {


	/**
	 * Class Gdpr
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	class GDPR {

		/**
		 * Gdpr constructor.
		 */
		public function __construct() {
			add_action( 'wp_footer', [ &$this, 'show_consent' ] );
			add_action( 'wp_enqueue_scripts', [ &$this, 'scripts' ] );
		}

		/**
		 * @return null
		 */
		public function show_consent() {
			$consent = esc_html( get_option( 'woocommerce_allow_tracking' ) );

			if ( 'no' === $consent ) {
				// Grab page ID's with the help of page slug.
				$privacy = $this->get_id_by_slug( 'privacy-policy' );
				$cookies = $this->get_id_by_slug( 'cookie-policy' );

				if ( $privacy && $cookies ) {
					// Get URL's for the page ID's.
					$privacy_page = esc_url( get_permalink( $privacy ) );
					$cookies_page = esc_url( get_permalink( $cookies ) );

					if ( $privacy_page && $cookies_page ) {
			?>
						<div class="wc-defaults-gdpr">
							<p><?php echo sprintf( esc_html__( 'We use cookies to improve your experience on our site. To find out more, read our %sPrivacy Policy%s and %sCookie Policy%s.', 'woocart-defaults' ), '<a href="' . $privacy_page . '" class="wcil">', '</a>', '<a href="' . $cookies_page . '" class="wcil">', '</a>' ); ?> <a href="javascript:;" id="wc-defaults-ok"><?php esc_html_e( 'OK', 'woocart-defaults' ); ?></a></p>
						</div><!-- .wc-defaults-gdpr -->
			<?php
					}
				}
			}
		}

		/**
		 * @return null
		 */
		public function scripts() {
			wp_enqueue_style( 'woocart-cookie', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/front.css', [], WCD_VERSION );

			wp_enqueue_script( 'woocart-cookie', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/js.cookie.min.js', [], WCD_VERSION );
			wp_enqueue_script( 'woocart-front', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/front.js', [ 'jquery', 'woocart-cookie' ], WCD_VERSION, true );
		}

		/**
		 * @param $page_slug
		 * @return string|null
		 */
		public function get_id_by_slug( string $page_slug ) {
			$page = get_page_by_path( $page_slug );

			if ( $page ) {
				return $page->ID;
			} else {
				return null;
			}
		}

	}
}

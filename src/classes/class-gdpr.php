<?php
/**
 * Handles GDPR consent on the plugin frontend.
 *
 * @category   Plugins
 * @package    WordPress
 * @subpackage woocart-defaults
 * @since      1.0.0
 */

namespace Niteo\WooCart\Defaults {


	/**
	 * Class GDPR
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	class GDPR {

		/**
		 * GDPR constructor.
		 */
		public function __construct() {
			add_action( 'wp_footer', [ &$this, 'show_consent' ] );
			add_action( 'wp_enqueue_scripts', [ &$this, 'scripts' ] );
		}

		/**
		 * @return null
		 */
		public function show_consent() {
			$consent = get_option( 'woocommerce_allow_tracking' );

			if ( 'no' === $consent ) {
				// Grab page ID's with the help of page slug.
				$privacy = absint( get_option( 'wp_page_for_privacy_policy' ) );
				$cookies = absint( get_option( 'wp_page_for_cookie_policy' ) );

				if ( $privacy && $cookies ) {
					// Get URL's for the page ID's.
					$privacy_page = esc_url( get_permalink( $privacy ) );
					$cookies_page = esc_url( get_permalink( $cookies ) );

					if ( $privacy_page && $cookies_page ) {
			?>
						<div class="wc-defaults-gdpr">
							<p><?php echo sprintf( __( 'We use cookies to improve your experience on our site. To find out more, read our <a href="%s" class="wcil">Privacy Policy</a> and <a href="%s" class="wcil">Cookie Policy</a>.', 'woocart-defaults' ), $privacy_page, $cookies_page ); ?> <a href="javascript:;" id="wc-defaults-ok"><?php esc_html_e( 'OK', 'woocart-defaults' ); ?></a></p>
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
			wp_enqueue_style( 'woocart-cookie', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/front.css', [], Release::Version );

			wp_enqueue_script( 'woocart-front', plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/front.js', [], Release::Version, true );
		}

	}
}

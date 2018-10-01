<?php
/**
 * Handles content for the admin dashboard panel.
 *
 * @category   Plugins
 * @package    WordPress
 * @subpackage woocart-defaults
 * @since      1.0.0
 */

namespace Niteo\WooCart\Defaults {


	/**
	 * Class AdminDashboard
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	class AdminDashboard {

		/**
		 * AdminDashboard constructor.
		 */
		public function __construct() {
			add_action( 'admin_init', array( &$this, 'init' ) );
		}

		/**
		 * Get fired once the admin panel initializes.
		 */
		public function init() {
			if ( is_admin() ) {
				remove_action( 'welcome_panel', 'wp_welcome_panel' );
				add_action( 'welcome_panel', array( &$this, 'welcome_panel' ) );
			}
		}

		/**
		 * Our customised welcome panel for the store.
		 */
		public function welcome_panel() {
			?>
			<style>
				.welcome-panel-content .welcome-panel-column .welcome-panel-inner,
				.welcome-panel-content h2,
				.welcome-panel-content .about-description {
					padding: 0 10px;
				}
				.welcome-panel-content li {
				    display: inline-block;
				    margin-right: 13px;
				}
			</style>

			<div class="welcome-panel-content">
				<h2><?php esc_html_e( 'Welcome to your new store!', 'woocart-defaults' ); ?></h2>
				<p class="about-description"><?php esc_html_e( 'You are only a few steps away from selling.', 'woocart-defaults' ); ?></p>

				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<div class="welcome-panel-inner">
							<!-- Connect a payment gateway -->
							<h3><?php esc_html_e( 'Connect a Payment Gateway', 'woocart-defaults' ); ?></h3>
							<p><?php esc_html_e( 'To start receiving payments, you\'ll need to set up a payment gateway.', 'woocart-defaults' ); ?></p>
							<p><?php esc_html_e( 'Here are the instructions and the recommended plugins for the popular gateways:', 'woocart-defaults' ); ?></p>
							<ul>
								<li><a href="https://wordpress.org/plugins/paypal-for-woocommerce/" target="_blank"><?php esc_html_e( 'PayPal', 'woocart-defaults' ); ?></a></li>
								<li><a href="https://wordpress.org/plugins/woocommerce-gateway-stripe/" target="_blank"><?php esc_html_e( 'Stripe', 'woocart-defaults' ); ?></a></li>
								<li><a href="https://wordpress.org/plugins/klarna-checkout-for-woocommerce/" target="_blank"><?php esc_html_e( 'Klarna', 'woocart-defaults' ); ?></a></li>
								<li><a href="https://wordpress.org/plugins/woocommerce-gateway-paypal-powered-by-braintree/" target="_blank"><?php esc_html_e( 'BrainTree', 'woocart-defaults' ); ?></a></li>
								<li><a href="https://wordpress.org/plugins/paymill/" target="_blank"><?php esc_html_e( 'Paymill', 'woocart-defaults' ); ?></a></li>
								<li><a href="https://wordpress.org/plugins/woocommerce-payu-paisa/" target="_blank"><?php esc_html_e( 'PayU', 'woocart-defaults' ); ?></a></li>
							</ul>
						</div>
					</div>

					<div class="welcome-panel-column">
						<div class="welcome-panel-inner">
							<!-- Connect a shipping courier -->
							<h3><?php esc_html_e( 'Connect a Shipping Courier', 'woocart-defaults' ); ?></h3>
							<p><?php esc_html_e( 'If you prepare shipping slips automatically, you\'ll need to use a shipping courier plugin.', 'woocart-defaults' ); ?></p>
							<p><?php esc_html_e( 'Here are the recommended plugins for the most popular couriers:', 'woocart-defaults' ); ?></p>
							<ul>
								<li><a href="https://wordpress.org/plugins/dhl-for-woocommerce/" target="_blank"><?php esc_html_e( 'DHL', 'woocart-defaults' ); ?></a></li>
								<li><a href="https://woocommerce.com/products/fedex-shipping-module/" target="_blank"><?php esc_html_e( 'FedEx', 'woocart-defaults' ); ?></a></li>
								<li><a href="https://wordpress.org/plugins/flexible-shipping-ups/" target="_blank"><?php esc_html_e( 'UPS', 'woocart-defaults' ); ?></a></li>
							</ul>
						</div>
					</div>

					<div class="welcome-panel-column welcome-panel-last">
						<div class="welcome-panel-inner">
							<!-- Add your products -->
							<h3><?php esc_html_e( 'Add Your Products', 'woocart-defaults' ); ?></h3>
							<p><?php printf( 
								__('Add your products manually or import a CSV with the <a href="%s">WooCommerce import</a>.', 'woocart-defaults' ),
								esc_url(
									get_admin_url( null, 'edit.php?post_type=product&page=product_importer' )
								)
							); ?></p>
						</div>
					</div>
				</div>

				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<div class="welcome-panel-inner">
							<!-- Logo & slider banners -->
							<h3><?php esc_html_e( 'Add Your Own Logo and Slider Banners', 'woocart-defaults' ); ?></h3>
							<p><?php printf( esc_html__( 'You\'ll want to add your own logo and banners to the store. You can use something like %sthe free tool Canva%s to create these graphics.', 'woocart-defaults' ), '<a href="https://www.canva.com/create/banners/" target="_blank">', '</a>' ); ?></p>
							<ul>
								<li><a href="<?php echo esc_url( get_admin_url( null, 'customize.php' ) ); ?>"><?php esc_html_e( 'Start Customizing', 'woocart-defaults' ); ?></a></li>
							</ul>
						</div>
					</div>

					<div class="welcome-panel-column welcome-panel-last">
						<div class="welcome-panel-inner">
							<!-- Test checkout -->
							<h3><?php esc_html_e( 'Test The Checkout', 'woocart-defaults' ); ?></h3>
							<p><?php esc_html_e( 'Go through the buying process and review that everything is working as it should.', 'woocart-defaults' ); ?></p>
							<ul>
								<li><a href="<?php echo esc_url( get_site_url() ); ?>" target="_blank"><?php esc_html_e( 'Visit Your Store', 'woocart-defaults' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

	}
}

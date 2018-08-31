<?php

namespace WooCart\WooCartDefaults;

/**
 * Plugin Name: Woocart Defaults
 * Description: Manage and deploy WordPress + WooCommerce configuration changes.
 * Version:     1.0.0
 * Runtime:     5.3+
 * Author:      WooCart
 * Text Domain: woocart-defaults
 * Domain Path: /framework/langs/
 * Author URI:  www.woocart.com
 */

/**
 * Checks for PHP version and stop the plugin if the version is < 5.3.0.
 */
if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
    ?>
    <div id="error-page">
        <p><?php esc_html_e( 'This plugin requires PHP 5.3.0 or higher. Please contact your hosting provider about upgrading your server software. Your PHP version is', 'woocart-defaults' ); ?> <b><?php echo esc_html( PHP_VERSION ); ?></b></p>
    </div>
    <?php
    die();
}

/**
 * WooCartDefaults class where all the action happens.
 *
 * @package WordPress
 * @subpackage woocart-defaults
 * @since 1.0.0
 */
class WooCartDefaults {

    const OPTIONNAME = 'WooCartDefaults.Path';

    public $readwrite;
    public $registry;
    public $options;
    public $helper;
    private static $instance;

    /**
     * Class Constructor.
     *
     * @access public
     * @since  1.0.0 
     */
    public function __construct() {
        define( 'WCD_VERSION', '1.0.0' );
        define( 'WCD_DIR', dirname(__FILE__) );

        define( 'WCD_CONFIG_DIR', apply_filters( 'wcd_config_dir', esc_url( get_theme_mod( self::OPTIONNAME, WP_CONTENT_DIR . '/config' ) ) ) );
        define( 'WCD_CONFIG_URL', apply_filters( 'wcd_config_url', WP_CONTENT_URL . '/config' ) );

        define( 'WCD_CONFIG_FORMAT', apply_filters( 'wcd_config_format', 'yaml' ) );
        define( 'WCD_CONFIG_USE_YAML_DIFF', apply_filters( 'wcd_config_use_yaml_diff', true ) );
        define( 'WCD_URL', plugins_url( '', __FILE__ ) );

        /**
         * It's time for action :)
         */
        add_action( 'init', array( &$this, 'init' ) );
    }

    /**
     * Initialize the singleton.
     */
    public static function instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Initialize classes and WP hooks.
     */
    public function init() {
        // i18n.
        $this->load_textdomain();

        // Hooks.
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        add_action( 'network_admin_menu', array( &$this, 'network_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );

        // Required classes.
        $classes = array(
            'wcd_options',
            'wcd_readwrite',
            'wcd_registry',
            'wcd_helper',
            'wcd_ajax'
        );

        foreach ( $classes as $class ) {
            include WCD_DIR . "/framework/classes/class-$class.php";
        }

        // WP-CLI.
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            include WCD_DIR . '/includes/class-wcd_cli.php';
        }

        // Make sure is_plugin_active() is available.
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        // Third party integrations.
        $integrations = scandir( WCD_DIR . '/framework/classes/integrations' );

        foreach ( $integrations as $filename ) {
            if ( '.' != substr( $filename, 0, 1) ) {
                include WCD_DIR . "/framework/classes/integrations/{$filename}";
            }
        }

        $this->options      = new WCD_Options();
        $this->readwrite    = new WCD_Readwrite();
        $this->registry     = new WCD_Registry();
        $this->helper       = new WCD_Helper();
        $ajax               = new WCD_Ajax();
    }

    /**
     * Register the settings page.
     */
    public function admin_menu()
    {
        add_options_page('WP-CFM', 'WP-CFM', 'manage_options', 'wpcfm', array($this, 'settings_page'));
    }

    /**
     * Register the multi-site settings page
     */
    public function network_admin_menu()
    {
        add_submenu_page('settings.php', 'WP-CFM', 'WP-CFM', 'manage_options', 'wpcfm', array($this, 'settings_page'));
    }

    /**
     * Enqueue media CSS
     */
    public function admin_scripts($hook)
    {
        if ('settings_page_wpcfm' == $hook) {
            wp_enqueue_style('media-views');
        }
    }

    /**
     * Route to the correct edit screen
     */
    public function settings_page()
    {
        include WCD_DIR . '/templates/page-settings.php';
    }

    /**
     * i18n support.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'woocart-defaults', false, dirname( plugin_basename( __FILE__ ) ) . '/framework/langs/' );
    }

    /**
     * Attached to the activation hook.
     */
    public function activate_plugin() {
        // Add to `wp_options` table.
        update_option( self::OPTIONNAME, WP_CONTENT_DIR . '/config' );
    }

}

/**
 * Allow direct access to the classes
 * For example, use woocart_defaults()->options to access WCD_Options
 */
if ( ! function_exists( 'woocart_defaults' ) ) :
function woocart_defaults() {
    return WooCartDefaults::instance();
}
endif;

/**
 * Get the instance.
 */
woocart_defaults();

/**
 * On plugin activation.
 */
register_activation_hook( __FILE__, array( 'woocart_defaults', 'activate_plugin' ) );
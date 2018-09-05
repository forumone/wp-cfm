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
 * Include composer autoloader.
 */
if ( PHP_VERSION_ID >= 50604 ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * WooCartDefaults class where all the action happens.
 *
 * @package WordPress
 * @subpackage woocart-defaults
 * @since 1.0.0
 */
class WooCartDefaults {

    const SETTINGNAME   = 'WooCartDefaults.Settings';

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

        // Required classes.
        $classes = array(
            'wcd_options',
            'wcd_readwrite',
            'wcd_registry',
            'wcd_helper'
        );

        foreach ( $classes as $class ) {
            include WCD_DIR . "/framework/classes/class-$class.php";
        }

        // WP-CLI.
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            include WCD_DIR . '/framework/classes/class-wcd_cli.php';
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

        /**
         * Check database option & update as required.
         */
        $bundle = esc_html( get_option( self::SETTINGNAME ) );

        if ( ! $bundle ) {
            /**
             * Let's prepare our bundle :)
             */
            $data = (object) array(
                'bundles' => array(
                    (object) array(
                        'label'     => 'WooCart',
                        'name'      => 'woocart',
                        'config'    => array()
                    )
                )
            );

            $items = apply_filters( 'wcd_configuration_items', array() );

            /**
             * 1. Get all the items.
             * 2. Loop through items and store only keys in the database.
             */
            foreach ( $items as $key => $value ) {
                $data->bundles[0]->config[] = $key;
            }

            update_option( self::SETTINGNAME, stripslashes( json_encode( $data ) ) );
        }
    }

    /**
     * i18n support.
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'woocart-defaults', false, dirname( plugin_basename( __FILE__ ) ) . '/framework/langs/' );
    }

}

/**
 * Allow direct access to the classes
 * For example, use WCD()->options to access WCD_Options
 */
if ( ! function_exists( 'WCD' ) ) :
function WCD() {
    return WooCartDefaults::instance();
}
endif;

/**
 * Get the instance.
 */
WCD();

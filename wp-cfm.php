<?php
/*
Plugin Name: WP-CFM
Plugin URI: https://forumone.github.io/wp-cfm/
Description: WordPress Configuration Management
Version: 1.5.1
Author: Forum One
Author URI: http://forumone.com/
License: GPLv3

Copyright 2016 Forum One

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

defined( 'ABSPATH' ) or exit;

if (PHP_VERSION_ID >= 50604) {
  require_once __DIR__ . '/vendor/autoload.php';
}

class WPCFM_Core
{

    public $readwrite;
    public $registry;
    public $options;
    public $helper;
    private static $instance;


    function __construct() {

        // setup variables
        define( 'WPCFM_VERSION', '1.4.5' );
        define( 'WPCFM_DIR', dirname( __FILE__ ) );
        define( 'WPCFM_CONFIG_DIR', apply_filters( 'wpcfm_config_dir', WP_CONTENT_DIR . '/config' ) );
        define( 'WPCFM_CONFIG_URL', apply_filters( 'wpcfm_config_url', WP_CONTENT_URL . '/config' ) );
        if (PHP_VERSION_ID < 50604) {
          define( 'WPCFM_CONFIG_FORMAT', 'json');
          define( 'WPCFM_CONFIG_FORMAT_REQUESTED',  apply_filters( 'wpcfm_config_format', 'json'));
        } else {
          define( 'WPCFM_CONFIG_FORMAT',  apply_filters( 'wpcfm_config_format', 'json'));
        }
        define( 'WPCFM_CONFIG_USE_YAML_DIFF',  apply_filters( 'wpcfm_config_use_yaml_diff', true));
        define( 'WPCFM_URL', plugins_url( '', __FILE__ ) );

        // WP is loaded
        add_action( 'init', array( $this, 'init' ) );
    }


    /**
     * Initialize the singleton
     */
    public static function instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * Initialize classes and WP hooks
     */
    function init() {

        // i18n
        $this->load_textdomain();

        // hooks
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'network_admin_menu', array( $this, 'network_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

        // includes
        foreach ( array( 'options', 'readwrite', 'registry', 'helper', 'ajax' ) as $class ) {
            include( WPCFM_DIR . "/includes/class-$class.php" );
        }

        // WP-CLI
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            include( WPCFM_DIR . '/includes/class-wp-cli.php' );
        }

        $this->options = new WPCFM_Options();
        $this->readwrite = new WPCFM_Readwrite();
        $this->registry = new WPCFM_Registry();
        $this->helper = new WPCFM_Helper();
        $ajax = new WPCFM_Ajax();

        // Make sure is_plugin_active() is available
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        // Third party integrations
        $integrations = scandir( WPCFM_DIR . '/includes/integrations' );
        foreach ( $integrations as $filename ) {
            if ( '.' != substr( $filename, 0, 1 ) ) {
                include( WPCFM_DIR . "/includes/integrations/$filename" );
            }
        }
    }


    /**
     * Register the settings page
     */
    function admin_menu() {
        add_options_page( 'WP-CFM', 'WP-CFM', 'manage_options', 'wpcfm', array( $this, 'settings_page' ) );
    }


    /**
     * Register the multi-site settings page
     */
    function network_admin_menu() {
        add_submenu_page( 'settings.php', 'WP-CFM', 'WP-CFM', 'manage_options', 'wpcfm', array( $this, 'settings_page' ) );
    }


    /**
     * Enqueue media CSS
     */
    function admin_scripts( $hook ) {
        if ( 'settings_page_wpcfm' == $hook ) {
            wp_enqueue_style( 'media-views' );
        }
    }


    /**
     * Route to the correct edit screen
     */
    function settings_page() {
        include( WPCFM_DIR . '/templates/page-settings.php' );
    }


    /**
     * i18n support
     */
    function load_textdomain() {
        $locale = apply_filters( 'plugin_locale', get_locale(), 'wpcfm' );
        $mofile = WP_LANG_DIR . '/wpcfm-' . $locale . '.mo';

        if ( file_exists( $mofile ) ) {
            load_textdomain( 'wpcfm', $mofile );
        }
        else {
            load_plugin_textdomain( 'wpcfm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }
    }
}

WPCFM();


/**
 * Allow direct access to WPCFM classes
 * For example, use WPCFM()->options to access WPCFM_Options
 */
function WPCFM() {
    return WPCFM_Core::instance();
}

<?php
/*
Plugin Name: WP-CFM
Plugin URI: http://forumone.com/
Description: WordPress Configuration Management
Version: 1.0.0
Author: Forum One Communications
Author URI: http://forumone.com/

Copyright 2014 Forum One Communications

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

defined( 'ABSPATH' ) or die();

class WPCFM
{
    public $registry;
    public $ajax;

    function __construct() {

        // setup variables
        define( 'WPCFM_VERSION', '1.0.0' );
        define( 'WPCFM_DIR', dirname( __FILE__ ) );
        define( 'WPCFM_URL', plugins_url( 'wp-cfm' ) );

        // WP is loaded
        add_action( 'init', array( $this, 'init' ) );
    }


    /**
     * Initialize classes and WP hooks
     */
    function init() {

        // hooks
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );

        // includes
        include( WPCFM_DIR . '/includes/class-registry.php' );
        include( WPCFM_DIR . '/includes/class-ajax.php' );

        $this->registry = new WPCFM_Registry();
        $this->ajax = new WPCFM_Ajax();
    }


    /**
     * Register the FacetWP settings page
     */
    function admin_menu() {
        add_options_page( 'WP-CFM', 'WP-CFM', 'manage_options', 'wp-cfm', array( $this, 'settings_page' ) );
    }


    /**
     * Route to the correct edit screen
     */
    function settings_page() {
        include( WPCFM_DIR . '/templates/page-settings.php' );
    }
}

$wp_cfm = new WPCFM();

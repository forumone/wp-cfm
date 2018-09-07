<?php

use WooCart\WooCartDefaults\WooCartDefaults;

class TestPlugin extends \PHPUnit\Framework\TestCase {

    function setUp() {
        \WP_Mock::setUsePatchwork(true);
        \WP_Mock::setUp();
    }

    function tearDown() {
        $this->addToAssertionCount(
            \Mockery::getContainer()->mockery_getExpectationCount()
        );
        \WP_Mock::tearDown();
    }

    /**
     * Basic testing.
     */
    public function test_constructor() {
        \WP_Mock::wpFunction(
            'wp_normalize_path', array(
                'return' => true
            )
        );

        \WP_Mock::wpFunction(
            'plugins_url', array(
                'return' => true
            )
        );

        \WP_Mock::wpFunction(
            'load_plugin_textdomain', array(
                'return' => true
            )
        );

        $plugin = new WooCartDefaults();

        \WP_Mock::expectActionAdded( 'init', array( $plugin, 'init' ) );

        $plugin->__construct();
        \WP_Mock::assertHooksAdded();
    }

    /**
     * Taxonomy integration.
     */
    public function test_taxonomy_integration() {
        $class = new WCD_Taxonomy();

        \WP_Mock::expectFilterAdded( 'wcd_configuration_items', array( $class, 'configuration_items' ) );
        \WP_Mock::expectFilterAdded( 'wcd_pull_callback', array
            ( $class, 'pull_callback' ), 10, 2 );

        $class->__construct();
        \WP_Mock::assertHooksAdded();
    }

    /**
     * Woo options integration.
     */
    public function test_woo_options_integration() {
        $class = new WOO_Options();

        \WP_Mock::expectFilterAdded( 'wcd_configuration_items', array( $class, 'configuration_items' ) );
        \WP_Mock::expectFilterAdded( 'wcd_pull_callback', array
            ( $class, 'pull_callback' ), 10, 2 );

        $class->__construct();
        \WP_Mock::assertHooksAdded();
    }

    /**
     * Woo shipping integration.
     */
    public function test_woo_shipping_integration() {
        $class = new WOO_Shipping();

        \WP_Mock::expectFilterAdded( 'wcd_configuration_items', array( $class, 'configuration_items' ) );
        \WP_Mock::expectFilterAdded( 'wcd_pull_callback', array
            ( $class, 'pull_callback' ), 10, 2 );

        $class->__construct();
        \WP_Mock::assertHooksAdded();
    }

    /**
     * Woo tax integration.
     */
    public function test_woo_tax_integration() {
        $class = new WOO_Tax();

        \WP_Mock::expectFilterAdded( 'wcd_configuration_items', array( $class, 'configuration_items' ) );
        \WP_Mock::expectFilterAdded( 'wcd_pull_callback', array
            ( $class, 'pull_callback' ), 10, 2 );

        $class->__construct();
        \WP_Mock::assertHooksAdded();
    }

    /**
     * WP options integration.
     */
    public function test_wp_options_integration() {
        $class = new WP_Options();

        \WP_Mock::expectFilterAdded( 'wcd_configuration_items', array( $class, 'get_configuration_items' ) );
        \WP_Mock::expectFilterAdded( 'wcd_pull_callback', array
            ( $class, 'pull_callback' ), 10, 2 );

        $class->__construct();
        \WP_Mock::assertHooksAdded();
    }

}

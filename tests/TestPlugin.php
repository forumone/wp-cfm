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

    public function test_theme_base() {
        \WP_Mock::wpFunction(
            'is_admin', array(
                'return' => false,
            )
        );
    }

}
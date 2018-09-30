<?php


use Niteo\WooCart\Defaults\GDPR;
use PHPUnit\Framework\TestCase;

class GDPRTest extends TestCase
{
    function setUp()
    {
        \WP_Mock::setUp();
    }

    function tearDown()
    {
        $this->addToAssertionCount(
            \Mockery::getContainer()->mockery_getExpectationCount()
        );
        \WP_Mock::tearDown();
        \Mockery::close();
    }


    /**
     * Testing constructor covers the entire GDPR() class.
     * @covers \Niteo\WooCart\Defaults\GDPR::__construct
     * @covers \Niteo\WooCart\Defaults\GDPR::show_consent
     * @covers \Niteo\WooCart\Defaults\GDPR::scripts
     * @covers \Niteo\WooCart\Defaults\GDPR::get_id_by_slug
     */
    public function testConstructor()
    {
        $gdpr = new GDPR();
        \WP_Mock::expectActionAdded( 'wp_footer', [ $gdpr, 'show_consent' ] );
        \WP_Mock::expectActionAdded( 'wp_enqueue_scripts', [ $gdpr, 'scripts' ] );

        \WP_Mock::wpFunction(
            'get_option', array(
                'return' => true
            )
        );
        \WP_Mock::wpFunction(
            'get_permalink', array(
                'return' => true
            )
        );

        $gdpr->__construct();
        \WP_Mock::assertHooksAdded();
    }
}

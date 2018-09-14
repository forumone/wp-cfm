<?php

use Niteo\WooCart\Defaults\Importers\WooOptions;
use Niteo\WooCart\Defaults\Importers\WooOptionsValue;
use PHPUnit\Framework\TestCase;

class WOOOptionsTest extends TestCase
{
    function setUp() {
        \WP_Mock::setUp();
    }

    function tearDown() {
        $this->addToAssertionCount(
            \Mockery::getContainer()->mockery_getExpectationCount()
        );
        \WP_Mock::tearDown();
        \Mockery::close();
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importers\WooOptions::import
     * @covers \Niteo\WooCart\Defaults\Importers\WooOptionsValue::setValue
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedName
     * @covers \Niteo\WooCart\Defaults\Value::getValue
     * @covers \Niteo\WooCart\Defaults\Value::setName
     */
    public function testImport()
    {
        \WP_Mock::userFunction("update_option", [
            'return' => true,
            'args' => ["test_name", "test_value"],
        ]);
        $value = new WooOptionsValue("woo");
        $value->setName("test_name");
        $value->setValue("test_value");
        $o = new WooOptions();
        $o->import($value);
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importers\WooOptions::items
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Importers\WooOptionsValue::setValue
     * @covers \Niteo\WooCart\Defaults\Value::getName
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedName
     * @covers \Niteo\WooCart\Defaults\Value::setName
     */
    public function testItems()
    {
        global $wpdb;

        $option = new stdClass();
        $option->option_name = "test_name";
        $option->option_value = "test_value";

        $wpdb = \Mockery::mock('\WPDB');
        $wpdb->options = 'wp_options';
        $wpdb->shouldReceive('prepare')->andReturn('');
        $wpdb->shouldReceive('get_results')->andReturn([$option, $option, $option, $option]);

        $o = new WooOptions();
        $this->assertCount(4, $o->items());
        $value = $o->items()->current();
        $this->assertEquals( "woo/test_name", $value->getName());
        $this->assertEquals( "test_name", $value->getStrippedName());

    }
}

<?php

use Niteo\WooCart\Defaults\Importers\WPOptions;
use PHPUnit\Framework\TestCase;

class WPOptionsTest extends TestCase
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
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptions::import
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptions::toValue
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptionsValue::setValue
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedKey
     * @covers \Niteo\WooCart\Defaults\Value::getValue
     * @covers \Niteo\WooCart\Defaults\Value::setKey
     */
    public function testImport()
    {
        global $wpdb;

        \WP_Mock::userFunction("get_option", [
            'return' => false,
            'args' => ["test_name"],
        ]);
        $wpdb = \Mockery::mock('\WPDB');
        $wpdb->options = 'wp_options';
        $wpdb->shouldReceive('insert')
            ->with('wp_options', ['option_value' => 'test_value', 'autoload' => 'yes', 'option_name' => 'test_name'], [0 => '%s', 1 => '%s', 2 => '%s'])
            ->andReturn(true);
        $value = WPOptions::toValue("test_name", "test_value");
        $o = new WPOptions();
        $o->import($value);
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptions::items
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Value::getKey
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedKey
     * @covers \Niteo\WooCart\Defaults\Value::setKey
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptionsValue::setValue
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

        $o = new WPOptions();
        $this->assertCount(4, $o->items());
        $value = $o->items()->current();
        $this->assertEquals("test_name", $value->getKey());
        $this->assertEquals("test_name", $value->getStrippedKey());

    }
}

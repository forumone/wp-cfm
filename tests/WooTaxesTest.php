<?php

use Niteo\WooCart\Defaults\Importers\WooTaxes;
use Niteo\WooCart\Defaults\Importers\WooTaxesValue;
use PHPUnit\Framework\TestCase;

class WooTaxesTest extends TestCase
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

    public function testImport()
    {

    }

    /**
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Value::getName
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedName
     * @covers \Niteo\WooCart\Defaults\Value::setName
     * @covers \Niteo\WooCart\Defaults\Importers\FromArray::fromArray
     * @covers \Niteo\WooCart\Defaults\Importers\ToArray::toArray
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxes::items
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::getID
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::getLocations
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::getTax
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::setTax
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::setValue
     */
    public function testItems()
    {
        global $wpdb;

        $tax = new stdClass();
        $tax->tax_rate_id = 1234;
        $tax->tax_rate_country = "tax_rate_country";
        $tax->tax_rate_state = "tax_rate_state";
        $tax->tax_rate = "tax_rate";
        $tax->tax_rate_name = "tax_rate_name";
        $tax->tax_rate_priority = "tax_rate_priority";
        $tax->tax_rate_compound = "tax_rate_compound";
        $tax->tax_rate_shipping = "tax_rate_shipping";
        $tax->tax_rate_order = "tax_rate_order";
        $tax->tax_rate_class = "tax_rate_class";


        $location = new stdClass();
        $location->tax_rate_id = 1234;
        $location->location_code = "location_code";
        $location->location_type = "location_type";

        $wpdb = \Mockery::mock('\WPDB');
        $wpdb->prefix = 'wp_';
        $wpdb->shouldReceive('prepare')->andReturn("");
        $wpdb->shouldReceive('get_results')->andReturn(
            [$tax, $tax, $tax, $tax],
            [$location, $location]
        );


        $o = new WooTaxes();
        $this->assertCount(4, $o->items());

        $wpdb = \Mockery::mock('\WPDB');
        $wpdb->prefix = 'wp_';
        $wpdb->shouldReceive('prepare')->andReturn("");
        $wpdb->shouldReceive('get_results')->andReturn(
            [$tax, $tax, $tax, $tax],
            [$location, $location]
        );
        $value = $o->items()->current();
        $this->assertEquals("wootax/1234", $value->getName());
        $this->assertEquals(1234, $value->getID());
        $this->assertEquals("1234", $value->getStrippedName());

        $this->assertCount(2, $value->getLocations());
        $value = $value->getLocations()->current();
        $this->assertEquals("1234", $value->tax_rate_id);
        $this->assertEquals("location_code", $value->location_code);
        $this->assertEquals("location_type", $value->location_type);

    }
}

<?php

use Niteo\WooCart\Defaults\Importers\Location;
use Niteo\WooCart\Defaults\Importers\Tax;
use Niteo\WooCart\Defaults\Importers\WooTaxes;
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

    /**
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Value::getKey
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedKey
     * @covers \Niteo\WooCart\Defaults\Value::setKey
     * @covers \Niteo\WooCart\Defaults\Importers\FromArray::fromArray
     * @covers \Niteo\WooCart\Defaults\Importers\ToArray::toArray
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxes::toValue
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxes::import
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::getID
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::getLocations
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::getTax
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::setTax
     * @covers \Niteo\WooCart\Defaults\Importers\WooTaxesValue::setValue
     */
    public function testImport()
    {
        global $wpdb;

        $wpdb = \Mockery::mock('\WPDB');
        $wpdb->prefix = 'wp_';
        $wpdb->shouldReceive('prepare')->andReturn("prepare_mock")->with('wp_woocommerce_tax_rates');
        $wpdb->shouldReceive('prepare')->andReturn("prepare_mock")->with('wp_woocommerce_tax_rate_locations');
        $wpdb->shouldReceive('replace')->with(
            "prepare_mock",
            [
                'tax_rate_id' => 0,
                'tax_rate_country' => 'country',
                'tax_rate_state' => 'state',
                'tax_rate' => 'rate',
                'tax_rate_name' => 'name',
                'tax_rate_priority' => 'priority',
                'tax_rate_compound' => 'compound',
                'tax_rate_shipping' => 'shipping',
                'tax_rate_order' => 'order',
                'tax_rate_class' => 'class'
            ], [
                0 => '%d',
                1 => '%s',
                2 => '%s',
                3 => '%s',
                4 => '%s',
                5 => '%s',
                6 => '%s',
                7 => '%s',
                8 => '%s',
                9 => '%s',
                10 => '%s'
            ]);
        $wpdb->shouldReceive('replace')->with(
            "prepare_mock",
            ['tax_rate_id' => 0, 'location_code' => "location_code", 'location_type' => "location_type"],
            [0 => '%d', 1 => '%s', 2 => '%s']
        );

        $tax = new Tax();
        $loc = new Location();
        $loc->tax_rate_id = 1234;
        $loc->location_code="location_code";
        $loc->location_type="location_type";
        $tax->class = "class";
        $tax->priority = "priority";
        $tax->state = "state";
        $tax->compound = "compound";
        $tax->country = "country";
        $tax->name = "name";
        $tax->order = "order";
        $tax->rate = "rate";
        $tax->shipping = "shipping";
        $tax->locations = [$loc->toArray()];

        $value = WooTaxes::toValue("test_name/1", $tax->toArray());
        $o = new WooTaxes();
        $o->import($value);
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Value::getKey
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedKey
     * @covers \Niteo\WooCart\Defaults\Value::setKey
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
        $this->assertEquals("1234", $value->getKey());
        $this->assertEquals(1234, $value->getID());
        $this->assertEquals("1234", $value->getStrippedKey());

        $this->assertCount(2, $value->getLocations());
        $value = $value->getLocations()->current();
        $this->assertEquals("1234", $value->tax_rate_id);
        $this->assertEquals("location_code", $value->location_code);
        $this->assertEquals("location_type", $value->location_type);

    }
}

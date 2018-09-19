<?php

use Niteo\WooCart\Defaults\Importer;
use PHPUnit\Framework\TestCase;

class ImporterTest extends TestCase
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
     * @covers \Niteo\WooCart\Defaults\Importer::read_file
     */
    public function testRead_file_non_serialized()
    {
        $i = new Importer();
        $data = $i->read_file(dirname(__FILE__) . "/fixtures/non_serialized.yaml");
        $this->assertEquals([
            'wp/test_name' => 'test_value',
        ], $data);

    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importer::read_file
     */
    public function testRead_file_serialized()
    {
        $i = new Importer();
        $data = $i->read_file(dirname(__FILE__) . "/fixtures/serialized.yaml");
        $this->assertEquals([
            'wp/test_name' => 'test_value',
            'wp/test_php' => 'i:123456;',
            'wp/test_json' => '["abc"]'
        ], $data);

    }


    /**
     * @covers \Niteo\WooCart\Defaults\Importer::read_file
     * @covers \Niteo\WooCart\Defaults\ConfigsRegistry::get
     * @covers \Niteo\WooCart\Defaults\Importer::resolve
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptions::import
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptions::toValue
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptionsValue::setValue
     * @covers \Niteo\WooCart\Defaults\Importer::parse
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedKey
     * @covers \Niteo\WooCart\Defaults\Value::getValue
     * @covers \Niteo\WooCart\Defaults\Value::setKey
     * @covers \Niteo\WooCart\Defaults\Importer::import
     */
    public function testImport()
    {
        global $wpdb;
        \WP_Mock::userFunction("get_option", [
            'return' => true,
        ]);

        $wpdb = \Mockery::mock('\WPDB');
        $wpdb->options = 'wp_options';
        $wpdb->shouldReceive('update')
            ->with('wp_options', ['option_value' => 'test_value'], ['option_name' => 'test_name'])
            ->andReturn(true);

        $wpdb->shouldReceive('update')
            ->with('wp_options', ['option_value' => 'i:123456;'], ['option_name' => 'test_php'])
            ->andReturn(true);

        $wpdb->shouldReceive('update')
            ->with('wp_options', ['option_value' => '["abc"]'], ['option_name' => 'test_json'])
            ->andReturn(true);

        $i = new Importer();
        $i->import(dirname(__FILE__) . "/fixtures/serialized.yaml");
    }

    /**
     * @expectedException Exception
     * @covers \Niteo\WooCart\Defaults\Importer::resolve
     * @covers \Niteo\WooCart\Defaults\ConfigsRegistry::get
     */
    public function testResolve()
    {
        $i = new Importer();
        $i->resolve("foo/test");
    }
}

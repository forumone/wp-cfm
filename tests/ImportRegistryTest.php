<?php


use Niteo\WooCart\Defaults\ConfigsRegistry;
use PHPUnit\Framework\TestCase;

class ImportRegistryTest extends TestCase
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
     * @covers \Niteo\WooCart\Defaults\ConfigsRegistry::get
     */
    public function testGetImporters()
    {
        foreach (ConfigsRegistry::get() as $importer) {
            $this->assertArrayHasKey("Niteo\WooCart\Defaults\Importers\Configuration", class_implements($importer));
        }
    }

}

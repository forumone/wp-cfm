<?php


use Niteo\WooCart\Defaults\ConfigsRegistry;
use PHPUnit\Framework\TestCase;

class ImportRegistryTest extends TestCase
{

    public function testGetImporters()
    {
        foreach (ConfigsRegistry::get() as $importer) {
            $this->assertArrayHasKey("Niteo\WooCart\Defaults\Importers\Configuration", class_implements($importer));
        }
    }

}

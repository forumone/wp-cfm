<?php


use Niteo\WooCart\Defaults\Importers\PageMeta;
use PHPUnit\Framework\TestCase;

class PageMetaTest extends TestCase
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
     * @covers \Niteo\WooCart\Defaults\Importers\PageMeta::getInsertParams
     * @covers \Niteo\WooCart\Defaults\Importers\ToArray::toArray
     */
    public function testGetInsertParams()
    {
        $p = new PageMeta();
        $p->post_name = "post_name";
        $this->assertEquals([
            'post_name' => 'post_name',
        ], $p->getInsertParams());
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importers\PageMeta::getDefaultsImport
     * @covers \Niteo\WooCart\Defaults\Importers\PageMeta::getInsertParams
     * @covers Niteo\WooCart\Defaults\Importers\ToArray::toArray
     */
    public function testGetDefaultsImport()
    {
        $p = new PageMeta();
        $p->post_name = "post_name";
        $p->woocart_defaults = [
            "wp/key" => '$ID',
        ];
        $this->assertEquals(['wp/key' => 1234], (array)$p->getDefaultsImport(["ID" => 1234]));
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importers\PageMeta::getDefaultsImport
     * @covers \Niteo\WooCart\Defaults\Importers\PageMeta::getInsertParams
     * @covers Niteo\WooCart\Defaults\Importers\ToArray::toArray
     */
    public function testGetDefaultsImportEmpty()
    {
        $p = new PageMeta();
        $p->post_name = "post_name";
        $this->assertEquals([], (array)$p->getDefaultsImport(["ID" => 1234]));
    }
}

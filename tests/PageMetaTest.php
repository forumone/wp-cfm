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
            'post_content' => null,
            'post_title' => null,
            'post_excerpt' => null,
            'post_status' => null,
            'post_type' => null,
            'post_name' => 'post_name',
            'post_category' => null,
            'meta_input' => null,
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
}

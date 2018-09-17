<?php

use Niteo\WooCart\Defaults\Importers\WooPage;
use PHPUnit\Framework\TestCase;

class WooPageTest extends TestCase
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
     * @covers \Niteo\WooCart\Defaults\Importers\WooPage::getPageMeta
     * @covers \Niteo\WooCart\Defaults\Importers\WooPage::__construct
     * @covers \Niteo\WooCart\Defaults\Importers\FromArray::fromArray
     * @covers \Niteo\WooCart\Defaults\Importers\ToArray::toArray
     */
    function testgetMeta()
    {

        $p = new WooPage(dirname(__FILE__) . "/fixtures/page.html");
        $meta = $p->getPageMeta();

        $this->assertEquals([
            'post_title' => 'Cookie Policy',
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_content' => '[company-name] ("us", "we", or "our")',
            'post_name' => 'cookie-policy',
            'post_excerpt' => null,
            'post_category' => null,
            'meta_input' => null,
            'woocart_defaults' => [
                'wp/wp_page_for_privacy_policy' => '$ID',
                'wp/cookie_page' => '$post_name',
            ],
        ], $meta->toArray());
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importers\WooPage::insertPage
     * @covers \Niteo\WooCart\Defaults\Importers\WooPage::getPageMeta
     * @covers \Niteo\WooCart\Defaults\Importers\WooPage::__construct
     * @covers \Niteo\WooCart\Defaults\Importer::parse
     * @covers \Niteo\WooCart\Defaults\Importers\FromArray::fromArray
     * @covers \Niteo\WooCart\Defaults\Importers\PageMeta::getInsertParams
     * @covers \Niteo\WooCart\Defaults\Importers\ToArray::toArray
     * @covers \Niteo\WooCart\Defaults\ConfigsRegistry::get
     * @covers \Niteo\WooCart\Defaults\Importer::resolve
     * @covers \Niteo\WooCart\Defaults\Importers\PageMeta::getDefaultsImport
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptions::import
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptions::toValue
     * @covers \Niteo\WooCart\Defaults\Importers\WPOptionsValue::setValue
     * @covers \Niteo\WooCart\Defaults\Value::__construct
     * @covers \Niteo\WooCart\Defaults\Value::getStrippedKey
     * @covers \Niteo\WooCart\Defaults\Value::getValue
     * @covers \Niteo\WooCart\Defaults\Value::setKey
     */
    function testinsertPage()
    {

        $p = new WooPage(dirname(__FILE__) . "/fixtures/page.html");
        $meta = $p->getPageMeta();
        \WP_Mock::userFunction("wp_insert_post", [
            'return' => 1234,
            'args' => [[
                'post_content' => '[company-name] ("us", "we", or "our")',
                'post_title' => 'Cookie Policy',
                'post_excerpt' => NULL,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => 'cookie-policy',
                'post_category' => NULL,
                'meta_input' => NULL
            ]]]);
        \WP_Mock::userFunction("update_option", [
            'return' => 1,
            'args' => ['wp_page_for_privacy_policy', '1234']
        ]);
        \WP_Mock::userFunction("update_option", [
            'return' => 1,
            'args' => ['cookie_page', 'cookie-policy']
        ]);
        $id = $p->insertPage($meta);

        $this->assertEquals(1234, $id);
    }
}

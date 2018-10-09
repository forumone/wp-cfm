<?php

use Niteo\WooCart\Defaults\Importers\WooProducts;
use PHPUnit\Framework\TestCase;

class WooProductTest extends TestCase
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
     * @covers Niteo\WooCart\Defaults\Importers\WooProducts::__construct
     * @covers \Niteo\WooCart\Defaults\Importers\FromArray::fromArray
     * @covers \Niteo\WooCart\Defaults\Importers\ProductMeta::getInsertParams
     * @covers \Niteo\WooCart\Defaults\Importers\ToArray::toArray
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::get_image_path
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::add_products
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::create_simple_product
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::get_product_count
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::parse_product
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::upload_images
     */
    function testadd_products() {
        $faker = \Mockery::mock();
        $faker->shouldReceive('boolean')->times(18)->andReturn(true);
        $faker->shouldReceive('randomFloat')->times(3)->andReturn(150);
        $faker->shouldReceive('numberBetween')->times(18)->andReturn(175);
        $faker->shouldReceive('iso8601')->times(3)->andReturn('2018-10-08');
        $faker->shouldReceive('randomElement')->times(3)->andReturn('yes');
        $faker->shouldReceive('text')->times(3)->andReturn('product note');
        $faker->ean8 = 45321150;
        $mock_FakerFactory = \Mockery::mock('overload:Faker\Factory');
        $mock_FakerFactory->shouldReceive('create')->andReturn($faker);
        \WP_Mock::userFunction(
            'sanitize_title', array(
                'return'    => 'title'
                )
            );
        $mock_WC_Product = \Mockery::mock();
        $mock_WC_Product->shouldReceive('set_props')->times(3);
        $mock_WC_Product->shouldReceive('save')->times(3);
        $mock = \Mockery::mock('\Niteo\WooCart\Defaults\Importers\WooProducts',
            array('/provision/localizations/Countries/.common/')
        );
        $mock->makePartial();
        $mock->shouldReceive('create_product')
            ->times(3)
            ->andReturn($mock_WC_Product);
        $mock->shouldReceive('upload_image')
            ->once()
            ->with('/provision/localizations/Countries/.common/product-1/image1.jpg')
            ->andReturn(234);
        $mock->shouldReceive('upload_image')
            ->once()
            ->with('/provision/localizations/Countries/.common/product-1/image2.jpg')
            ->andReturn(235);
        $mock->shouldReceive('upload_image')
            ->once()
            ->with('/provision/localizations/Countries/.common/product-1/image3.jpg')
            ->andReturn(236);
        $mock->shouldReceive('upload_image')
            ->once()
            ->with('/provision/localizations/Countries/.common/product-2/image1.jpg')
            ->andReturn(237);
        $mock->shouldReceive('upload_image')
            ->once()
            ->with('/provision/localizations/Countries/.common/product-2/image2.jpg')
            ->andReturn(238);
        $mock->shouldReceive('upload_image')
            ->once()
            ->with('/provision/localizations/Countries/.common/product-3/image.jpg')
            ->andReturn(239);
        $mock->add_products(dirname(__FILE__) . '/fixtures/products.html');
        $this->assertEquals(3, $mock->get_product_count());
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importers\FromArray::fromArray
     * @covers \Niteo\WooCart\Defaults\Importers\ProductMeta::getInsertParams
     * @covers \Niteo\WooCart\Defaults\Importers\ToArray::toArray
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::__construct
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::create_product
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::create_simple_product

     */
    function testcreate_simple_products() {
        $faker = \Mockery::mock();
        $faker->shouldReceive('boolean')->times(6)->andReturn(true);
        $faker->shouldReceive('randomFloat')->times(1)->andReturn(150);
        $faker->shouldReceive('numberBetween')->times(6)->andReturn(175);
        $faker->shouldReceive('iso8601')->times(1)->andReturn('2018-10-08');
        $faker->shouldReceive('randomElement')->times(1)->andReturn('yes');
        $faker->shouldReceive('text')->times(1)->andReturn('product note');
        $faker->ean8 = 45321150;
        $mock_FakerFactory = \Mockery::mock('overload:Faker\Factory');
        $mock_FakerFactory->shouldReceive('create')->andReturn($faker);
        \WP_Mock::userFunction(
            'sanitize_title', array(
                'return'    => 'product-title'
            )
        );

        $data = array(
            'title' => 'Product tilte',
            'price' => 200,
            'image_id' => 3532,
            'gallery' => array(3534, 3535, 3536),
            'description' => 'Product description',
            'details' => 'charger included',
        );

        $props = array(
            'name'               => $data['title'],
            'featured'           => true,
            'catalog_visibility' => 'visible',
            'description'        => $data['description'] . $data['details'],
            'sku'                => 'product-title-45321150',
            'regular_price'      => $data['price'],
            'sale_price'         => 150,
            'date_on_sale_to'    => '2018-10-08',
            'tax_status'         => 'taxable',
            'manage_stock'       => true,
            'stock_quantity'     => 175,
            'stock_status'       => 'instock',
            'backorders'         => 'yes',
            'sold_individually'  => true,
            'weight'             => 175,
            'length'             => 175,
            'width'              => 175,
            'height'             => 175,
            'reviews_allowed'    => true,
            'purchase_note'      => 'product note',
            'menu_order'         => 175,
            'image_id'           => $data['image_id'],
            'gallery_image_ids'  => $data['gallery'],
        );
        $mock_WC_Product = \Mockery::mock('overload:WC_Product');
        $mock_WC_Product->shouldReceive('set_props')->once()->with($props);

        $products = new WooProducts();
        $products->create_simple_product( $data );
    }


    /**
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::__construct
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::upload_image
    */
    function testupload_image_wrong_path() {
        $products = new WooProducts();
        $result = $products->upload_image( 'img.png' );
        $this->assertEquals(0, $result);
    }

    /**
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::__construct
     * @covers \Niteo\WooCart\Defaults\Importers\WooProducts::upload_image
    */
    function testupload_image() {
        $metadata = array('title' => 'image.jpg');
        \WP_Mock::userFunction(
            'wp_upload_bits', array(
                'return'    => array ( 'type' => 'jpeg', 'file' => 'image.jpg')
            )
        );
        \WP_Mock::userFunction(
            'wp_insert_attachment', array(
                'args' => array(
                    array(
						'post_title'     => 'image.jpg',
						'post_mime_type' => 'jpeg',
						'post_status'    => 'publish',
						'post_content'   => '',
					),
					'image.jpg'
                ),
                'return' => 1234
            )
        );
        \WP_Mock::userFunction(
            'wp_generate_attachment_metadata', array(
                'args' => array(1234, 'image.jpg'),
                'return' => $metadata,
            )
        );
        \WP_Mock::userFunction(
            'wp_update_attachment_metadata', array(
                'args' => array(1234, $metadata)
            )
        );
        \WP_Mock::userFunction(
            'update_post_meta', array(
                'args' => array(999, '_thumbnail_id', 1234)
            )
        );

        $products = new WooProducts( dirname(__FILE__) .'/fixtures/');

        $result = $products->upload_image(
            dirname(__FILE__) . '/fixtures/image.jpg', 999
        );
        $this->assertEquals(1234, $result);
    }
}

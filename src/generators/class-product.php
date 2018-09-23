<?php

namespace Niteo\WooCart\Defaults\Generators {


	/**
	 * Class Product
	 *
	 * @package Niteo\WooCart\Defaults\Generators
	 */
	class Product extends Generator {

		/**
		 * Return a new product.
		 *
		 * @param bool $save Save the object before returning or not.
		 * @return \WC_Product The product object consisting of random data.
		 */
		public static function generate( $save = true ) {
			$faker = \Faker\Factory::create();
			// 30% chance of a variable product.
			$is_variable = $faker->boolean( 30 );
			if ( $is_variable ) {
				$product = self::generate_variable_product();
			} else {
				$product = self::generate_simple_product();
			}
			if ( $product ) {
				$product->save();
			}
			return $product;
		}

		/**
		 * Generate a variable product and return it.
		 *
		 * @return \WC_Product_Variable
		 */
		protected static function generate_variable_product() {
			$faker             = \Faker\Factory::create();
			$name              = $faker->words( $faker->numberBetween( 1, 5 ), true );
			$will_manage_stock = $faker->boolean();
			$product           = new \WC_Product_Variable();
			$nr_attributes     = $faker->numberBetween( 1, 3 );
			$attributes        = array();
			$image_id          = self::generateImage();
			$gallery           = self::maybe_get_gallery_image_ids();
			for ( $i = 0; $i < $nr_attributes; $i++ ) {
				$attribute = new \WC_Product_Attribute();
				$attribute->set_id( 0 );
				$attribute->set_name( ucfirst( $faker->words( $faker->numberBetween( 1, 3 ), true ) ) );
				$attribute->set_options( array_filter( $faker->words( $faker->numberBetween( 2, 4 ), false ) ), 'ucfirst' );
				$attribute->set_position( 0 );
				$attribute->set_visible( true );
				$attribute->set_variation( true );
				$attributes[] = $attribute;
			}
			$product->set_props(
				array(
					'name'              => $name,
					'featured'          => $faker->boolean( 10 ),
					'attributes'        => $attributes,
					'tax_status'        => 'taxable',
					'tax_class'         => '',
					'manage_stock'      => $will_manage_stock,
					'stock_quantity'    => $will_manage_stock ? $faker->numberBetween( -100, 100 ) : null,
					'stock_status'      => 'instock',
					'backorders'        => $faker->randomElement( array( 'yes', 'no', 'notify' ) ),
					'sold_individually' => $faker->boolean( 20 ),
					'upsell_ids'        => self::get_existing_product_ids(),
					'cross_sell_ids'    => self::get_existing_product_ids(),
					'image_id'          => $image_id,
					'category_ids'      => self::generate_term_ids( $faker->numberBetween( 1, 10 ), 'product_cat' ),
					'tag_ids'           => self::generate_term_ids( $faker->numberBetween( 1, 10 ), 'product_tag' ),
					'gallery_image_ids' => $gallery,
					'reviews_allowed'   => $faker->boolean(),
					'purchase_note'     => $faker->boolean() ? $faker->text() : '',
					'menu_order'        => $faker->numberBetween( 0, 10000 ),
				)
			);
			// Need to save to get an ID for variations.
			$product->save();
			// Create variations, one for each attribute value combination.
			$variation_attributes = wc_list_pluck( array_filter( $product->get_attributes(), 'wc_attributes_array_filter_variation' ), 'get_slugs' );
			$possible_attributes  = array_reverse( wc_array_cartesian( $variation_attributes ) );
			foreach ( $possible_attributes as $possible_attribute ) {
				$price      = $faker->randomFloat( 2, 1, 1000 );
				$is_on_sale = $faker->boolean( 30 );
				$sale_price = $is_on_sale ? $faker->randomFloat( 2, 0, $price ) : '';
				$is_virtual = $faker->boolean( 20 );
				$variation  = new \WC_Product_Variation();
				$variation->set_props(
					array(
						'parent_id'         => $product->get_id(),
						'attributes'        => $possible_attribute,
						'regular_price'     => $price,
						'sale_price'        => $sale_price,
						'date_on_sale_from' => '',
						'date_on_sale_to'   => $faker->iso8601( date( 'c', strtotime( '+1 month' ) ) ),
						'tax_status'        => 'taxable',
						'tax_class'         => '',
						'manage_stock'      => $will_manage_stock,
						'stock_quantity'    => $will_manage_stock ? $faker->numberBetween( -100, 100 ) : null,
						'stock_status'      => 'instock',
						'weight'            => $is_virtual ? '' : $faker->numberBetween( 1, 200 ),
						'length'            => $is_virtual ? '' : $faker->numberBetween( 1, 200 ),
						'width'             => $is_virtual ? '' : $faker->numberBetween( 1, 200 ),
						'height'            => $is_virtual ? '' : $faker->numberBetween( 1, 200 ),
						'virtual'           => $is_virtual,
						'downloadable'      => false,
						'image_id'          => self::generateImage(),
					)
				);
				$variation->save();
			}
			$data_store = $product->get_data_store();
			$data_store->sort_all_product_variations( $product->get_id() );
			return $product;
		}

		/**
		 * Generate a simple product and return it.
		 *
		 * @return \WC_Product
		 */
		protected static function generate_simple_product() {
			$faker             = \Faker\Factory::create();
			$name              = $faker->words( $faker->numberBetween( 1, 5 ), true );
			$will_manage_stock = $faker->boolean();
			$is_virtual        = $faker->boolean();
			$price             = $faker->randomFloat( 2, 1, 1000 );
			$is_on_sale        = $faker->boolean( 30 );
			$sale_price        = $is_on_sale ? $faker->randomFloat( 2, 0, $price ) : '';
			$product           = new \WC_Product();
			$image_id          = self::generateImage();
			$gallery           = self::maybe_get_gallery_image_ids();
			$product->set_props(
				array(
					'name'               => $name,
					'featured'           => $faker->boolean(),
					'catalog_visibility' => 'visible',
					'description'        => $faker->paragraphs( $faker->numberBetween( 1, 5 ), true ),
					'short_description'  => $faker->text(),
					'sku'                => sanitize_title( $name ) . '-' . $faker->ean8,
					'regular_price'      => $price,
					'sale_price'         => $sale_price,
					'date_on_sale_from'  => '',
					'date_on_sale_to'    => $faker->iso8601( date( 'c', strtotime( '+1 month' ) ) ),
					'total_sales'        => $faker->numberBetween( 0, 10000 ),
					'tax_status'         => 'taxable',
					'tax_class'          => '',
					'manage_stock'       => $will_manage_stock,
					'stock_quantity'     => $will_manage_stock ? $faker->numberBetween( -100, 100 ) : null,
					'stock_status'       => 'instock',
					'backorders'         => $faker->randomElement( array( 'yes', 'no', 'notify' ) ),
					'sold_individually'  => $faker->boolean( 20 ),
					'weight'             => $is_virtual ? '' : $faker->numberBetween( 1, 200 ),
					'length'             => $is_virtual ? '' : $faker->numberBetween( 1, 200 ),
					'width'              => $is_virtual ? '' : $faker->numberBetween( 1, 200 ),
					'height'             => $is_virtual ? '' : $faker->numberBetween( 1, 200 ),
					'upsell_ids'         => self::get_existing_product_ids(),
					'cross_sell_ids'     => self::get_existing_product_ids(),
					'parent_id'          => 0,
					'reviews_allowed'    => $faker->boolean(),
					'purchase_note'      => $faker->boolean() ? $faker->text() : '',
					'menu_order'         => $faker->numberBetween( 0, 10000 ),
					'virtual'            => $is_virtual,
					'downloadable'       => false,
					'category_ids'       => self::generate_term_ids( $faker->numberBetween( 1, 10 ), 'product_cat' ),
					'tag_ids'            => self::generate_term_ids( $faker->numberBetween( 1, 10 ), 'product_tag' ),
					'shipping_class_id'  => 0,
					'image_id'           => $image_id,
					'gallery_image_ids'  => $gallery,
				)
			);
			return $product;
		}

		/**
		 * Generate an image gallery.
		 *
		 * @return array
		 */
		protected static function maybe_get_gallery_image_ids() {
			$faker          = \Faker\Factory::create();
			$gallery        = array();
			$create_gallery = $faker->boolean( 10 );
			if ( ! $create_gallery ) {
				return;
			}
			for ( $i = 0; $i < rand( 1, 3 ); $i++ ) {
				$gallery[] = self::generateImage();
			}
			return $gallery;
		}

		/**
		 * Get some random existing product IDs.
		 *
		 * @param int $limit Number of term IDs to get.
		 * @return array
		 */
		protected static function get_existing_product_ids( $limit = 5 ) {
			$post_ids = get_posts(
				array(
					'numberposts' => $limit * 2,
					'orderby'     => 'date',
					'post_type'   => 'product',
					'fields'      => 'ids',
				)
			);
			if ( ! $post_ids ) {
				return array();
			}
			shuffle( $post_ids );
			return array_slice( $post_ids, 0, max( count( $post_ids ), $limit ) );
		}
	}
}

<?php

namespace Niteo\WooCart\Defaults\Generators {

	/**
	 * Class Generator
	 *
	 * @package Niteo\WooCart\Defaults\Generators
	 */
	abstract class Generator {


		const emojis = [
			'1f642',
			'1f60f',
			'1f60e',
			'1f615',
			'1f61b',
			'1f606',
			'1f605',
			'1f910',
			'1f620',
			'1f609',
			'1f614',
			'1f637',
			'1f629',
			'1f61c',
			'2639',
			'1f60b',
			'1f608',
			'1f607',
			'1f604',
			'1f624',
			'1f61e',
			'1f60d',
			'1f600',
			'1f630',
			'1f917',
			'1f626',
			'1f61d',
			'1f62e',
			'1f612',
			'1f623',
			'1f633',
			'1f617',
			'1f60c',
			'1f62c',
			'1f61a',
			'1f62f',
			'1f627',
			'1f62d',
			'1f641',
			'1f62a',
			'1f616',
			'1f913',
			'1f915',
			'1f613',
			'1f618',
			'1f621',
			'1f644',
			'1f628',
			'1f603',
			'1f643',
			'1f631',
			'1f911',
			'1f611',
			'1f619',
			'1f61f',
			'1f914',
			'1f601',
			'1f632',
			'1f634',
			'263a',
			'1f60a',
			'1f912',
			'1f636',
			'1f62b',
			'1f625',
			'1f610',
			'1f622',
			'1f602',
			'1f635',
			'1f435',
			'1f413',
			'1f40d',
			'1f40a',
			'1f43d',
			'1f432',
			'1f429',
			'1f404',
			'1f40f',
			'1f421',
			'1f42d',
			'1f414',
			'1f41f',
			'1f412',
			'1f40e',
			'1f42c',
			'1f422',
			'1f418',
			'1f981',
			'1f411',
			'1f43b',
			'1f42a',
			'1f424',
			'1f980',
			'1f434',
			'1f431',
			'1f41d',
			'1f437',
			'1f410',
			'1f38f',
			'1f43f',
			'1f40b',
			'1f41e',
			'1f403',
			'1f43e',
			'1f438',
			'1f43c',
			'1f423',
			'1f416',
			'1f430',
			'1f417',
			'1f43a',
			'1f415',
			'1f982',
			'1f41c',
			'1f983',
			'1f426',
			'1f405',
			'1f406',
			'1f984',
			'1f425',
			'1f40c',
			'1f42b',
			'1f427',
			'1f42f',
			'1f402',
			'1f439',
			'1f401',
			'1f42e',
			'1f408',
			'1f428',
			'1f409',
			'1f41a',
			'1f420',
			'1f419',
			'1f407',
			'1f400',
			'1f436',
			'1f433',
			'1f41b',
			'1f47d',
			'1f486-1f3fb',
			'1f481-1f3fd',
			'1f64b-1f3fe',
			'1f478',
			'1f471-1f3fe',
			'1f64a',
			'1f482-1f3ff',
			'1f575-1f3ff',
			'1f64b-1f3fd',
			'1f63a',
			'1f47c-1f3fd',
			'1f479',
			'1f470-1f3fb',
			'1f467-1f3fb',
			'1f470',
			'1f385-1f3fb',
			'1f478-1f3fb',
			'1f64d',
			'1f471-1f3fc',
			'1f385-1f3fc',
			'1f487-1f3fc',
			'1f487-1f3fb',
			'1f646-1f3ff',
			'1f486-1f3fe',
			'1f46e',
			'1f472',
			'1f477-1f3fc',
			'1f648',
			'1f472-1f3fc',
			'1f64b-1f3ff',
			'1f477-1f3fe',
			'1f466-1f3fb',
			'1f477-1f3fd',
			'1f476',
			'1f645-1f3fd',
			'1f473-1f3ff',
			'1f476-1f3fe',
			'1f64e-1f3fd',
			'1f639',
			'1f476-1f3fc',
			'1f575',
			'1f647-1f3ff',
			'1f469',
			'1f646-1f3fe',
			'1f64b-1f3fc',
			'1f647-1f3fc',
			'1f473-1f3fe',
			'1f470-1f3fc',
			'1f46e-1f3fc',
			'1f478-1f3fd',
			'1f575-1f3fe',
			'1f63b',
			'1f487-1f3fe',
			'1f645',
			'1f467-1f3ff',
			'1f468-1f3fe',
			'1f477',
			'1f471-1f3ff',
			'1f469-1f3fb',
			'1f477-1f3ff',
			'1f486-1f3ff',
			'1f475-1f3fb',
			'1f64d-1f3ff',
			'1f474-1f3ff',
			'1f64b-1f3fb',
			'1f467-1f3fe',
			'1f47f',
			'1f647-1f3fd',
			'1f482-1f3fe',
			'1f486-1f3fc',
			'1f646-1f3fc',
			'1f466-1f3fd',
			'1f385-1f3fd',
			'1f575-1f3fc',
			'1f476-1f3ff',
			'1f474-1f3fc',
			'1f916',
			'1f646-1f3fd',
			'1f47c-1f3fb',
			'1f640',
			'1f486-1f3fd',
			'1f466',
			'1f46e-1f3fe',
			'1f478-1f3fc',
			'1f575-1f3fb',
			'1f477-1f3fb',
			'1f473-1f3fb',
			'1f649',
			'1f475-1f3ff',
			'1f481-1f3fe',
			'1f487-1f3fd',
			'1f471-1f3fd',
			'1f469-1f3fd',
			'1f470-1f3ff',
			'1f481',
			'1f472-1f3fe',
			'1f647-1f3fb',
			'1f47c',
			'1f468-1f3fb',
			'1f468-1f3fd',
			'1f64e',
			'1f470-1f3fd',
			'1f475-1f3fe',
			'1f64b',
			'1f645-1f3fb',
			'1f482-1f3fb',
			'1f473',
			'1f466-1f3fe',
			'1f4a9',
			'1f469-1f3fc',
			'1f64d-1f3fb',
			'1f468-1f3fc',
			'1f471-1f3fb',
			'1f482',
			'1f473-1f3fd',
			'1f47c-1f3fc',
			'1f575-1f3fd',
			'1f385-1f3fe',
			'1f466-1f3fc',
			'1f47e',
			'1f64e-1f3fe',
			'1f385-1f3ff',
			'1f474',
			'1f47c-1f3ff',
			'1f63f',
			'1f473-1f3fc',
			'1f63d',
			'1f476-1f3fb',
			'1f64d-1f3fd',
			'1f46e-1f3fb',
			'1f63c',
			'1f64d-1f3fc',
			'1f647',
			'1f482-1f3fd',
			'1f638',
			'1f647-1f3fe',
			'1f478-1f3fe',
			'1f46e-1f3ff',
			'1f63e',
			'1f645-1f3fe',
			'1f487-1f3ff',
			'1f467',
			'1f474-1f3fd',
			'1f468',
			'1f645-1f3fc',
			'1f481-1f3fb',
			'1f468-1f3ff',
			'1f646',
			'1f475',
			'1f47a',
			'1f475-1f3fd',
			'1f487',
			'1f472-1f3fb',
			'1f470-1f3fe',
			'1f474-1f3fe',
			'1f478-1f3ff',
			'1f46e-1f3fd',
			'1f481-1f3fc',
			'1f471',
			'1f64e-1f3fc',
			'1f467-1f3fd',
			'1f47b',
			'1f474-1f3fb',
			'1f472-1f3fd',
			'1f472-1f3ff',
			'1f480',
			'1f385',
			'1f47c-1f3fe',
			'1f466-1f3ff',
			'1f476-1f3fd',
			'1f482-1f3fc',
			'1f467-1f3fc',
			'1f469-1f3fe',
			'1f481-1f3ff',
			'1f646-1f3fb',
			'1f486',
			'1f64e-1f3ff',
			'1f645-1f3ff',
			'1f469-1f3ff',
			'1f64d-1f3fe',
			'1f64e-1f3fb',
			'1f475-1f3fc',
			'1f44d',
			'270a-1f3fe',
			'1f918-1f3fe',
			'270c-1f3fe',
			'1f595-1f3ff',
			'1f64f-1f3fc',
			'1f442-1f3fc',
			'1f596-1f3ff',
			'1f443',
			'270c-1f3fc',
			'1f445',
			'1f442-1f3fd',
			'1f448-1f3fe',
			'1f595',
			'270a-1f3fc',
			'261d-1f3fd',
			'1f590-1f3fc',
			'1f595-1f3fe',
			'1f44a-1f3fe',
			'1f595-1f3fb',
			'270d-1f3fe',
			'1f64c-1f3fe',
			'1f44c-1f3ff',
			'270d',
			'1f449-1f3ff',
			'1f448-1f3fb',
			'1f918-1f3fb',
			'270c',
			'1f4aa-1f3ff',
			'1f64f-1f3ff',
			'270a-1f3fd',
			'1f64c-1f3ff',
			'270a',
			'1f44f-1f3fc',
			'270b-1f3fc',
			'1f446-1f3fd',
			'1f44e-1f3fe',
			'1f44c-1f3fe',
			'1f450-1f3ff',
			'1f450-1f3fd',
			'1f448-1f3ff',
			'270b-1f3fd',
			'1f44c-1f3fd',
			'270d-1f3fc',
			'1f44a-1f3ff',
			'1f64f-1f3fe',
			'1f64c-1f3fb',
			'1f64c-1f3fc',
			'1f44b-1f3fb',
			'1f4aa-1f3fc',
			'1f64f-1f3fb',
			'1f448-1f3fd',
			'1f64c-1f3fd',
			'1f44e-1f3fd',
			'1f446-1f3fe',
			'1f590-1f3ff',
			'270d-1f3fd',
			'1f447-1f3ff',
			'1f918-1f3ff',
			'1f44f-1f3fd',
			'1f442-1f3fe',
			'1f442-1f3fb',
			'1f449-1f3fb',
			'1f4aa-1f3fe',
			'1f443-1f3fd',
			'1f449-1f3fc',
			'261d-1f3fb',
			'1f449',
			'1f443-1f3fc',
			'1f4aa',
			'1f596-1f3fc',
			'1f450-1f3fb',
			'1f44d-1f3fb',
			'1f447',
			'1f596-1f3fd',
			'270b',
			'1f64c',
			'1f44b',
			'1f44f-1f3ff',
			'270a-1f3fb',
			'1f918-1f3fc',
			'1f918-1f3fd',
			'1f64f-1f3fd',
			'1f44d-1f3fc',
			'1f446-1f3fb',
			'1f590',
			'1f447-1f3fb',
			'1f64f',
			'1f4aa-1f3fb',
			'1f443-1f3ff',
			'1f44a',
			'1f448-1f3fc',
			'1f44f-1f3fe',
			'1f450-1f3fc',
			'1f590-1f3fd',
			'1f4aa-1f3fd',
			'261d',
			'1f443-1f3fe',
			'1f596',
			'1f44e',
			'1f44a-1f3fd',
			'1f443-1f3fb',
			'1f44e-1f3ff',
			'1f44b-1f3fd',
			'270a-1f3ff',
			'1f918',
			'1f44f-1f3fb',
			'1f596-1f3fb',
			'1f447-1f3fe',
			'1f44e-1f3fc',
			'270d-1f3ff',
			'270c-1f3ff',
			'270d-1f3fb',
			'270c-1f3fd',
			'1f447-1f3fc',
			'1f446',
			'1f442-1f3ff',
			'261d-1f3ff',
			'1f44f',
			'1f44a-1f3fc',
			'1f44e-1f3fb',
			'270c-1f3fb',
			'1f44a-1f3fb',
			'1f44b-1f3fc',
			'1f44b-1f3fe',
			'1f444',
			'1f447-1f3fd',
			'1f44b-1f3ff',
			'1f44d-1f3fd',
			'1f596-1f3fe',
			'1f44d-1f3ff',
			'1f449-1f3fd',
		];
		/**
		 * Fake image width.
		 */
		const IMAGE_WIDTH = 700;
		/**
		 * Fake image height.
		 */
		const IMAGE_HEIGHT = 400;

		/**
		 * Return a new object of this object type.
		 *
		 * @param bool $save Save the object before returning or not.
		 * @return array
		 */
		abstract public static function generate( $save = true);

		/**
		 * Generate and upload a random image.
		 *
		 * @param int $parent Parent ID.
		 *
		 * @return int The attachment id of the image (0 on failure).
		 */
		protected static function generateImage( int $parent = 0 ) {
			// Build the image.
			$faker            = \Faker\Factory::create();
			$image            = @imagecreatetruecolor( self::IMAGE_WIDTH, self::IMAGE_HEIGHT );
			$background_rgb   = $faker->rgbColorAsArray;
			$background_color = imagecolorallocate( $image, $background_rgb[0], $background_rgb[1], $background_rgb[2] );
			imagefill( $image, 0, 0, $background_color );
			$im = self::GetEmojiImage();
			imagecopymerge( $image, $im, ( self::IMAGE_WIDTH - 72 ) / 2, ( self::IMAGE_HEIGHT - 72 ) / 2, 0, 0, 72, 72, 90 );
			ob_start();
			imagepng( $image );
			$file = ob_get_clean();
			imagedestroy( $image );
			$name          = 'img-' . rand() . '.png';
			$attachment_id = 0;
			// Upload the image.
			$upload = wp_upload_bits( $name, '', $file );
			if ( empty( $upload['error'] ) ) {
				$attachment_id = (int) wp_insert_attachment(
					array(
						'post_title'     => $name,
						'post_mime_type' => $upload['type'],
						'post_status'    => 'publish',
						'post_content'   => '',
					),
					$upload['file']
				);
			}
			if ( $attachment_id ) {
				if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
					include_once ABSPATH . 'wp-admin/includes/image.php';
				}
				$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
				wp_update_attachment_metadata( $attachment_id, $metadata );
				if ( $parent ) {
					update_post_meta( $parent, '_thumbnail_id', $attachment_id );
				}
			}
			return $attachment_id;
		}

		/**
		 * Get a random value from an array based on weight.
		 * Taken from https://stackoverflow.com/questions/445235/generating-random-results-by-weight-in-php
		 *
		 * @param array $weighted_values Array of value => weight options.
		 * @return mixed
		 */
		protected static function random_weighted_element( array $weighted_values ) {
			$rand = mt_rand( 1, (int) array_sum( $weighted_values ) );
			foreach ( $weighted_values as $key => $value ) {
				$rand -= $value;
				if ( $rand <= 0 ) {
					return $key;
				}
			}
		}

		/**
		 * Get random term ids.
		 *
		 * @param int    $limit Number of term IDs to get.
		 * @param string $taxonomy Taxonomy name.
		 * @return array
		 */
		protected static function generate_term_ids( $limit, $taxonomy ) {
			$faker    = \Faker\Factory::create();
			$terms    = $faker->words( $limit );
			$term_ids = array();
			foreach ( $terms as $term ) {
				$existing = get_term_by( 'name', $term, $taxonomy );
				if ( $existing ) {
					$term_ids[] = $existing->term_id;
				} else {
					$term = wp_insert_term( $term, $taxonomy );
					if ( $term && ! is_wp_error( $term ) ) {
						$term_ids[] = $term['term_id'];
					}
				}
			}
			return $term_ids;
		}

		private static function GetEmojiImage() {
			$faker = \Faker\Factory::create();
			$src   = $faker->randomElement( self::emojis );
			$im    = @imagecreatefrompng( "http://twemoji.maxcdn.com/2/72x72/$src.png" );
			if ( ! $im ) {
				return self::GetEmojiImage();
			}
			return $im;
		}
	}
}

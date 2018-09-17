<?php

namespace Niteo\WooCart\Defaults\Importers {

	use Niteo\WooCart\Defaults\Importer;
	use Symfony\Component\Yaml\Yaml;


	/**
	 * Class PageMeta
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class PageMeta {


		use FromArray;
		use ToArray;

		/**
		 * @var string
		 */
		public $post_content;
		/**
		 * @var string
		 */
		public $post_title;
		/**
		 * @var string
		 */
		public $post_excerpt;
		/**
		 * @var string
		 */
		public $post_status;
		/**
		 * @var string
		 */
		public $post_type;
		/**
		 * @var string
		 */
		public $post_name;
		/**
		 * @var string
		 */
		public $post_category;
		/**
		 * @var array
		 */
		public $meta_input;
		/**
		 * @var array
		 */
		public $woocart_defaults;


		/**
		 * wp_insert_post allowed params https://developer.wordpress.org/reference/functions/wp_insert_post/
		 *
		 * @var array
		 */
		const wp_insert_post_params = [
			'post_content',
			'post_title',
			'post_excerpt',
			'post_status',
			'post_type',
			'post_name',
			'post_category',
			'meta_input',
		];

		/**
		 * Return only array with keys valid for wp_insert_post.
		 *
		 * @return array
		 */
		public function getInsertParams(): array {
			$allowed = $this::wp_insert_post_params;
			return array_filter(
				self::toArray(),
				function ( $key ) use ( $allowed ) {
					return in_array( $key, $allowed );
				},
				ARRAY_FILTER_USE_KEY
			);
		}

		/**
		 * woocart-defaults embedded in page spec.
		 *
		 * @param array $extra
		 * @return iterable
		 */
		public function getDefaultsImport( array $extra ): array {
			$out = [];
			foreach ( $this->woocart_defaults as $key => $value ) {
				foreach ( $extra as $k => $v ) {
					if ( $value === '$' . $k ) {
						$value = $v;
					}
				}
				foreach ( $this->getInsertParams() as $k => $v ) {
					if ( $value === '$' . $k ) {
						$value = $v;
					}
				}
				$out[ $key ] = $value;
			}

			return $out;
		}
	}

	/**
	 * Class WooPage
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class WooPage {


		/**
		 * @var string
		 */
		protected $file_path;

		/**
		 * @param string $file_path
		 */
		public function __construct( $file_path ) {
			$this->file_path = $file_path;
		}

		/**
		 * @return PageMeta
		 */
		public function getPageMeta(): PageMeta {
			$contents = file_get_contents( $this->file_path );

			$dom = new \DOMDocument();
			$dom->loadHTML( $contents );

			$xpath   = new \DOMXPath( $dom );
			$comment = $xpath->query( '//comment()' )->item( 0 );
			$text    = $comment->nodeValue;
			$comment->parentNode->removeChild( $comment );
			$meta                 = Yaml::parse( $text );
			$meta['post_content'] = $xpath->query( '//body' )->item( 0 )->nodeValue;
			return PageMeta::fromArray( $meta );
		}

		/**
		 * @param PageMeta $page
		 * @return int
		 * @throws \Exception
		 */
		public function insertPage( PageMeta $page ): int {

			$post_id = wp_insert_post( $page->getInsertParams() );

			$import = new Importer();
			$import->parse( (array) $page->getDefaultsImport( [ 'ID' => $post_id ] ) );

			return $post_id;
		}
	}
}

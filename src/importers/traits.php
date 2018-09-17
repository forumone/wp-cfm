<?php

namespace Niteo\WooCart\Defaults\Importers {

	use Niteo\WooCart\Defaults\Value;

	/**
	 * Trait FromArray
   *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	trait FromArray {

		/**
		 * @param array $data
		 * @return Value
		 */
		public static function fromArray( array $data = [] ) {
			foreach ( get_object_vars( $obj = new self() ) as $property => $default ) {
				if ( ! array_key_exists( $property, $data ) ) {
					continue;
				}
				$obj->{$property} = $data[ $property ]; // assign value to object
			}
			return $obj;
		}
	}

	/**
	 * Trait ToArray
   *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	trait ToArray {

		/**
		 * @return array
		 */
		public function toArray(): array {
			return get_object_vars( $this );
		}
	}
}

<?php

namespace Niteo\WooCart\Defaults\Importers {

	/**
	 * Interface Configuration
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	interface Configuration {


		/**
		 * @return iterable
		 */
		public function items(): iterable;

		/**
		 * @param mixed $item
		 * @return mixed
		 */
		public function import( $item);

		/**
		 * Namespace of this importer.
		 *
		 * @return string This objects namespace.
		 */
		public function getNamespace(): string;

		/**
		 * Return importer specific Value instance.
		 *
		 * @param string $key Name of the kv pair.
		 * @param mixed  $value Value of the kv pair.
		 * @return mixed
		 */
		public function toValue( string $key, $value);
	}

	trait FromArray {

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

	trait ToArray {

		public function toArray(): array {
			return get_object_vars( $this );
		}
	}
}

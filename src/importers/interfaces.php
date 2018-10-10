<?php

namespace Niteo\WooCart\Defaults\Importers {

	/**
	 * Interface Configuration
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	interface Configuration {



		/**
		 * Return importer specific Value instance.
		 *
		 * @param string $key Name of the kv pair.
		 * @param mixed  $value Value of the kv pair.
		 * @return mixed
		 */
		static function toValue( string $key, $value);

		/**
		 * @return iterable
		 */
		public function items(): iterable;

		/**
		 * @param mixed $item
		 * @return mixed
		 */
		public function import( $item);
	}

}

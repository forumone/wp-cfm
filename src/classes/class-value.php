<?php
/**
 * Holds values from yaml.
 *
 * @category   Plugins
 * @package    WordPress
 * @subpackage woocart-defaults
 * @since      1.0.0
 */

namespace Niteo\WooCart\Defaults {


	/**
	 * Class Value
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	abstract class Value {



		/**
		 * @var string name of the value with namespace.
		 */
		protected $key;


		/**
		 * @var string new value for this kv pair.
		 */
		protected $value;

		/**
		 * @var string namespace this kv belongs to.
		 */
		private $namespace;

		/**
		 * Value constructor.
		 *
		 * @param $namespace
		 */
		public function __construct( $namespace ) {
			$this->namespace = $namespace;
		}


		/**
		 * Name without prefix.
		 *
		 * @return string
		 */
		public function getStrippedKey(): string {
			if ( stristr( $this->key, '/' ) ) {
				return substr( $this->key, strlen( $this->namespace ) + 1 );
			}
			return $this->key;
		}

		/**
		 * @return string
		 */
		public function getValue(): string {
			return $this->value;
		}

		/**
		 * Name of the key in kv pair.
		 *
		 * @return string
		 */
		public function getKey(): string {
			return $this->key;
		}

		/**
		 * Sets naem of the value.
		 *
		 * @param string $key name of the key in kv pair.
		 */
		public function setKey( string $key ): void {
			$this->key = $key;

		}

	}
}

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
		protected $name;


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
		public function getStrippedName(): string {
			return substr( $this->name, strlen( $this->namespace ) + 1 );
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
		public function getName(): string {
			return $this->name;
		}

		/**
		 * Sets naem of the value.
		 *
		 * @param string $name name of the key in kv pair.
		 */
		public function setName( string $name ): void {
			$this->name = sprintf( '%s/%s', $this->namespace, $name );

		}

	}
}

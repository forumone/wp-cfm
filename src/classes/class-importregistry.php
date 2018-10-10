<?php
/**
 * Maintains registry of all importers.
 *
 * @category   Plugins
 * @package    WordPress
 * @subpackage woocart-defaults
 * @since      1.0.0
 */

namespace Niteo\WooCart\Defaults {

	use Niteo\WooCart\Defaults\Importers\WooOptions;
	use Niteo\WooCart\Defaults\Importers\WooShipping;
	use Niteo\WooCart\Defaults\Importers\WooTaxes;
	use Niteo\WooCart\Defaults\Importers\WPOptions;


	/**
	 * Class ConfigsRegistry
	 *
	 * @package Niteo\WooCart\Defaults
	 */
	class ConfigsRegistry {



		/**
		 * Return classes of all declared importers.
		 *
		 * @return iterable
		 */
		public static function get(): iterable {
			yield new WPOptions();
			yield new WooOptions();
			yield new WooShipping();
			yield new WooTaxes();
		}

	}
}

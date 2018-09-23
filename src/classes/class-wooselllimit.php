<?php

namespace Niteo\WooCart\Defaults\Importers {

	use Niteo\WooCart\Defaults\Importer;
	use Symfony\Component\Yaml\Yaml;


	/**
	 * Class WooPage
	 *
	 * @package Niteo\WooCart\Defaults\Importers
	 */
	class SellingLimit {

		/**
		 * @var string
		 */
		protected $region;

		/**
		 * @param string $region
		 */
		public function __construct( $region ) {
			$this->region = $region;
		}

		/**
		 * Return ID of the zone selected with region.
		 *
		 * @return int|null
		 */
		public function zoneID(): int {
			global $wpdb;

			$query = $wpdb->prepare(
				"SELECT zone_id FROM {$wpdb->prefix}woocommerce_shipping_zones WHERE zone_name = %s",
				$this->region
			);
			return $wpdb->get_var( $query );

		}

		/**
		 * Limit shipping to selected $zone_id;
		 *
		 * @param int $zone_id
		 * @return array
		 * @throws \Exception
		 */
		public function countries( $zone_id ): array {
			global $wpdb;
			$query = $wpdb->prepare(
				"SELECT location_code FROM {$wpdb->prefix}woocommerce_shipping_zone_locations WHERE zone_id = %d",
				$zone_id
			);
			$out   = [];
			foreach ( $wpdb->get_results( $query, 'ARRAY_A' ) as $result ) {
				$out[] = $result['location_code'];
			}
			return $out;
		}

	}
}

<?php

class WOO_Shipping
{

    public function __construct()
    {
        add_filter('wpcfm_configuration_items', array($this, 'configuration_items'));
        add_filter('wpcfm_pull_callback', array($this, 'pull_callback'), 10, 2);
    }

    /**
     * Register the taxonomies in WP-CFM
     */
    public function configuration_items($items)
    {
        global $wpdb;

        $query = "SELECT * FROM {$wpdb->prefix}woocommerce_shipping_zones";
        $zones = $wpdb->get_results($query);
        foreach ($zones as $zone) {
            $locations = $wpdb->get_results($wpdb->prepare("
				SELECT location_code, location_type
				FROM {$wpdb->prefix}woocommerce_shipping_zone_locations
				WHERE zone_id = %d
            ", $zone->zone_id), "ARRAY_A");

            $methods = $wpdb->get_results($wpdb->prepare("
				SELECT method_id, method_order, is_enabled
				FROM {$wpdb->prefix}woocommerce_shipping_zone_methods
				WHERE zone_id = %d
			", $zone->zone_id), "ARRAY_A");

            $value = array(
                'name' => $zone->zone_name,
                'order' => $zone->zone_order,
                'locations' => $locations,
                'methods' => $methods,
            );

            $items['wooship/' . $zone->zone_id] = array(
                'value' => json_encode($value),
                'label' => "$zone->zone_name",
                'group' => 'WooCommerce Shipping Zones',
            );

        }
        return $items;
    }

    public function pull_callback($callback, $callback_params)
    {
        if ('wooship/' == substr($callback_params['name'], 0, 4)) {
            return array($this, 'import_terms');
        }
        return $callback;
    }

}

new WOO_Shipping();

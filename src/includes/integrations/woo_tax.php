<?php

class WOO_Tax
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
        $query = "SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates";
        $tax_rates = $wpdb->get_results($query);
        foreach ($tax_rates as $tax) {
            $locations = $wpdb->get_results($wpdb->prepare("
				SELECT location_code, location_type
				FROM {$wpdb->prefix}woocommerce_tax_rate_locations
				WHERE tax_rate_id = %d
            ", $tax->tax_rate_id), "ARRAY_A");

            $values = array(
                'country' => $tax->tax_rate_country,
                'state' => $tax->tax_rate_state,
                'rate' => $tax->tax_rate,
                'name' => $tax->tax_rate_name,
                'priority' => $tax->tax_rate_priority,
                'compound' => $tax->tax_rate_compound,
                'shipping' => $tax->tax_rate_shipping,
                'order' => $tax->tax_rate_order,
                'class' => $tax->tax_rate_class,
                'locations' => $locations,
            );

            $items['wootax/' . $tax->tax_rate_id] = array(
                'value' => json_encode($values),
                'label' => "$tax->tax_rate_country - $tax->tax_rate_name - $tax->tax_rate $tax_rate_class",
                'group' => 'WooCommerce Tax Rates',
            );

        }
        return $items;
    }

    public function pull_callback($callback, $callback_params)
    {
        if ('wootax/' == substr($callback_params['name'], 0, 4)) {
            return array($this, 'import_terms');
        }
        return $callback;
    }

}

new WOO_Tax();

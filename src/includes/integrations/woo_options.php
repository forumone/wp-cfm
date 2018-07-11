<?php

class WOO_Options
{

    public function __construct()
    {
        add_filter('wpcfm_configuration_items', array($this, 'get_configuration_items'));
        add_filter('wpcfm_pull_callback', array($this, 'pull_callback'), 10, 2);
    }

    public function get_configuration_items($items)
    {
        global $wpdb;

        $items = array();

        $query = "
        SELECT option_name, option_value FROM $wpdb->options
        WHERE option_name NOT LIKE '_transient%' AND option_name NOT LIKE '_site_transient%'
        AND option_name LIKE 'woocommerce_%'
        ORDER BY option_name";

        $results = $wpdb->get_results($query);
        foreach ($results as $op) {
            # code...

            $items['wooop/' . $op->option_name] = array(
                'value' => $op->option_value,
                'label' => "$op->option_name",
                'group' => 'WooCommerce Options',
            );
        }
        return $items;

    }
}

new WOO_Options;

<?php

class WPCFM_Registry
{

    public function __construct()
    {
        add_filter('wpcfm_configuration_items', array($this, 'disallowed_items'));
    }

    /**
     * Each configuration item is a key/value pair
     * The `wpcfm_configuration_items` filter allows devs to
     * register additional configuration items
     *
     * @since 1.0.0
     */
    public function get_configuration_items()
    {
        global $wpdb;
        $items = array();

        return apply_filters('wpcfm_configuration_items', $items);
    }

    /**
     * Ignore certain configuration settings
     *
     * @since 1.0.0
     */
    public function disallowed_items($items)
    {

        $disallowed_items = array(
            'auth_key',
            'auth_salt',
            'cron',
            'db_version',
            'home',
            'logged_in_key',
            'logged_in_salt',
            'nonce_key',
            'nonce_salt',
            'rewrite_rules',
            'siteurl',
            'active_plugins',
        );

        foreach ($disallowed_items as $row) {
            unset($items[$row]);
        }

        return $items;
    }

    /**
     * Get configuration options stored in multiple bundles
     * @since 1.3
     */
    public function get_duplicates()
    {
        $settings = WPCFM()->options->get('wpcfm_settings');
        $settings = json_decode($settings, true);

        if (empty($settings['bundles'])) {
            return array();
        }

        $result = array();
        foreach ($settings['bundles'] as $bundle) {
            foreach ((array) $bundle['config'] as $option) {
                if (empty($result[$option])) {
                    $result[$option] = array($bundle['name']);
                } else {
                    $result[$option][] = $bundle['name'];
                }
            }
        }

        foreach ($result as $option => $bundles) {
            if (1 == count($bundles)) {
                unset($result[$option]);
            } else {
                sort($result[$option]);
            }
        }
        return $result;
    }
}

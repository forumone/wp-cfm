<?php

class WPCFM_Options
{

	// $network will be set to true if we are in the network admin, by wpcfmoptions_init.
	// If called by wp-cli, with --network argument, class-wp-cli.php must also set $network to true.
	public static $network = false;

	function get($option) {
		if (WPCFM_Options::$network) {
			$result = get_site_option($option);
		} else {
			$result = get_option($option);
		}
		return $result;
	}

	function update($option, $value) {
		if (WPCFM_Options::$network) {
			$result = update_site_option($option, $value);
		} else {
			$result = update_option($option, $value);
		}
		return $result;
	}

}

// Set the network flag if we are working with "site options" not (blog) options:
add_action( 'init', 'wpcfmoptions_init' );
function wpcfmoptions_init() {
	if ( is_network_admin() ) WPCFM_Options::$network = true;
	if (defined('DOING_AJAX') && DOING_AJAX && is_multisite() && preg_match('#^' . network_admin_url() . '#i', $_SERVER['HTTP_REFERER'])) WPCFM_Options::$network = true;
}


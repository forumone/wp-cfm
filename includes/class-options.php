<?php

class WPCFM_Options
{

	function multisite() {
		if ( is_network_admin() ) return true;
		return false;
	}

	function get($option) {
		if (WPCFM_Options::multisite()) return get_site_option($option);
		return get_option($option);
	}

	function update($option, $value) {
		if (WPCFM_Options::multisite()) return update_site_option($option, $value);
		return update_option($option, $value);
	}

}



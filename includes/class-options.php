<?php

function wpcfmlog($msg) {
	$fh = fopen('/tmp/wpcfm', 'a');
	fwrite($fh, $msg . "\n");
	fclose($fh);
}

class WPCFM_Options
{

	function network() {
		if ( is_network_admin() ) return true;
		if (defined('DOING_AJAX') && DOING_AJAX && is_multisite() && preg_match('#^' . network_admin_url() . '#i', $_SERVER['HTTP_REFERER'])) return true;
		return false;
	}

	function get($option) {
		if (WPCFM_Options::network()) {
			$result = get_site_option($option);
			wpcfmlog('get_site_option(' . $option . ') == ' . print_r($result, true));
		} else {
			$result = get_option($option);
			wpcfmlog('get_option(' . $option . ') == ' . print_r($result, true));
		}
		return $result;
	}

	function update($option, $value) {
		if (WPCFM_Options::network()) {
			$result = update_site_option($option, $value);
			wpcfmlog('update_site_option(' . $option . ', ' . $value . ') == ' . print_r($result, true));
		} else {
			$result = update_option($option, $value);
			wpcfmlog('update_option(' . $option . ', ' . $value . ') == ' . print_r($result, true));
		}
		return $result;
	}

}



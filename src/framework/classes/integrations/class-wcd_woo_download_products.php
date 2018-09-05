<?php
/**
 * WooCommerce Downloadable product permissions.
 *
 * @package woocart-defaults
 */
class WOO_Download_Products {

    /**
     * Class Constructor.
     *
     * @access public
     * @since  1.0.0 
     */
	public function __construct() {
		add_filter( 'wcd_configuration_items', array( &$this, 'configuration_items' ) );
		add_filter( 'wcd_pull_callback', array( &$this, 'pull_callback' ), 10, 2 );
	}

	/**
	 * Register the downloadable products in WCD.
     *
     * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query 	= "SELECT * FROM {$wpdb->prefix}woocommerce_downloadable_product_permissions";
		$permissions = $wpdb->get_results( $query );

		foreach ( $permissions as $permission ) {
			$log  = $wpdb->get_results( $wpdb->prepare( "
				SELECT timestamp, user_id, user_ip_address
				FROM {$wpdb->prefix}wc_download_log
				WHERE permission_id = %d
            ", $permission->permission_id), "ARRAY_A" );

			$values     = array(
                'download_id' 			=> $permission->download_id,
				'product_id' 			=> $permission->product_id,
				'order_id' 				=> $permission->order_id,
				'order_key' 			=> $permission->order_key,
				'user_email' 			=> $permission->user_email,
				'user_id' 				=> $permission->user_id,
				'downloads_remaining' 	=> $permission->downloads_remaining,
				'access_granted' 		=> $permission->access_granted,
				'access_expires' 		=> $permission->access_expires,
				'download_count' 		=> $permission->download_count,
				'log' 					=> $log
            );

			$items[ 'woodownloads/' . $permission->permission_id ] = array(
				'value' => json_encode( $values ),
				'label' => sprintf( 'Download - %d', $permission->download_id ),
				'group' => 'WooCommerce Download Permissions',
			);
		}

		return $items;
	}

	/**
	 * Tell WCD to use import_terms() for downloadable products.
     *
     * @access public
	 */
	public function pull_callback( $callback, $callback_params ) {
		if ( 'woodownloads/' == substr( $callback_params['name'], 0, 12 ) ) {
			return array( $this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) downloadable products into the DB
	 *
	 * @param string $params['name']
	 * @param string $params['group']
	 * @param string $params['old_value'] The old settings (DB)
	 * @param string $params['new_value'] The new settings (file)
     *
     * @access public
	 */
	public function import_terms( $params ) {
		global $wpdb;

        $id     = intval(str_replace( 'woodownloads/', '', $params['name'] ) );
        $data   = $params['new_value'];
        $value  = json_decode( $data->value );
        $wpdb->replace(
			"{$wpdb->prefix}woocommerce_downloadable_product_permissions",
			array(
				'permission_id' 		=> $id,
				'download_id' 			=> $value->download_id,
				'product_id' 			=> $value->product_id,
				'order_id' 				=> $value->order_id,
				'order_key' 			=> $value->order_key,
				'user_email' 			=> $value->user_email,
				'user_id' 				=> $value->user_id,
				'downloads_remaining' 	=> $value->downloads_remaining,
				'access_granted' 		=> $value->access_granted,
				'access_expires' 		=> $value->access_expires,
				'download_count' 		=> $value->download_count,
			),
			array(
				'%d',
				'%s',
				'%d',
				'%d',
				'%s',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%d',
			)
		);

		foreach ( $value->log as $log ) {
			$wpdb->replace(
                "{$wpdb->prefix}wc_download_log",
                array(
                    'permission_id' 	=> $id,
                    'timestamp' 		=> $log->timestamp,
                    'user_id' 			=> $log->user_id,
                    'user_ip_address' 	=> $log->user_ip_address,
                ),
                array(
                	'%d',
                    '%s',
                    '%d',
                    '%s',
                )
            );
		}
	}

}

new WOO_Download_Products();

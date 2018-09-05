<?php
/**
 * WooCommerce Webhooks.
 *
 * @package woocart-defaults
 */
class WOO_Web_Hooks {

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
	 * Register the hooks in WCD.
     *
     * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query 	= "SELECT * FROM {$wpdb->prefix}wc_webhooks";
		$hooks 	= $wpdb->get_results( $query );

		foreach ( $hooks as $hook ) {
			$values     = array(
                'status' 			=> $hook->status,
				'user_id' 			=> $hook->user_id,
				'delivery_url' 		=> $hook->delivery_url,
				'secret' 			=> $hook->secret,
				'topic' 			=> $hook->topic,
				'date_created' 		=> $hook->date_created,
				'date_created_gmt' 	=> $hook->date_created_gmt,
				'date_modified' 	=> $hook->date_modified,
				'date_modified_gmt' => $hook->date_modified_gmt,
				'api_version' 		=> $hook->api_version,
				'failure_count' 	=> $hook->failure_count,
				'pending_delivery' 	=> $hook->pending_delivery
            );

			$items[ 'woohooks/' . $hook->webhook_id ] = array(
				'value' 	=> json_encode( $values ),
				'label' 	=> $hook->name,
				'group' 	=> 'WooCommerce Webhooks',
			);
		}

		return $items;
	}

	/**
	 * Tell WCD to use import_terms() for hooks.
     *
     * @access public
	 */
	public function pull_callback( $callback, $callback_params ) {
		if ( 'woohooks/' == substr( $callback_params['name'], 0, 8 ) ) {
			return array( $this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) hooks into the DB
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

        $id     = intval(str_replace( 'woohooks/', '', $params['name'] ) );
        $data   = $params['new_value'];
        $value  = json_decode( $data->value );
        $wpdb->replace(
			"{$wpdb->prefix}wc_webhooks",
			array(
				'webhook_id' 		=> $id,
				'status' 			=> $value->status,
				'name' 				=> $data->label,
				'user_id' 			=> $value->user_id,
				'delivery_url' 		=> $value->delivery_url,
				'secret' 			=> $value->secret,
				'topic' 			=> $value->topic,
				'date_created' 		=> $value->date_created,
				'date_created_gmt' 	=> $value->date_created_gmt,
				'date_modified' 	=> $value->date_modified,
				'date_modified_gmt' => $value->date_modified_gmt,
				'api_version' 		=> $value->api_version,
				'failure_count' 	=> $value->failure_count,
				'pending_delivery' 	=> $value->pending_delivery
			),
			array(
				'%d',
				'%s',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%d',
			)
		);
	}

}

new WOO_Web_Hooks();

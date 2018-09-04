<?php
/**
 * WooCommerce API keys.
 *
 * @package woocart-defaults
 */
class WOO_API_Keys {

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
	 * Register the API keys in WCD.
     *
     * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query 	= "SELECT * FROM {$wpdb->prefix}woocommerce_api_keys";
		$keys 	= $wpdb->get_results( $query );

		foreach ( $keys as $key ) {
			$values     = array(
                'user_id' 			=> $key->user_id,
				'description' 		=> $key->description,
				'permissions' 		=> $key->permissions,
				'consumer_key' 		=> $key->consumer_key,
				'consumer_secret' 	=> $key->consumer_secret,
				'nonces' 			=> $key->nonces,
				'truncated_key' 	=> $key->truncated_key,
				'last_access' 		=> $key->last_access,
            );

			$items[ 'wooapikeys/' . $key->key_id ] = array(
				'value' 	=> json_encode( $values ),
				'label' 	=> $key->key_id,
				'group' 	=> 'WooCommerce API Keys',
			);
		}

		return $items;
	}

	/**
	 * Tell WCD to use import_terms() for API items.
     *
     * @access public
	 */
	public function pull_callback( $callback, $callback_params ) {
		if ( 'wooapikeys/' == substr( $callback_params['name'], 0, 10 ) ) {
			return array( $this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) API keys into the DB
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

        $id     = intval(str_replace( 'wooapikeys/', '', $params['name'] ) );
        $data   = $params['new_value'];
        $value  = json_decode( $data->value );
        $wpdb->replace(
			"{$wpdb->prefix}woocommerce_api_keys",
			array(
				'key_id' 			=> $id,
				'user_id' 			=> $value->user_id,
				'description' 		=> $value->description,
				'permissions' 		=> $value->permissions,
				'consumer_key' 		=> $value->consumer_key,
				'consumer_secret' 	=> $value->consumer_secret,
				'nonces' 			=> $value->nonces,
				'truncated_key' 	=> $value->truncated_key,
				'last_access' 		=> $value->last_access,
			),
			array(
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			)
		);
	}

}

new WOO_API_Keys();

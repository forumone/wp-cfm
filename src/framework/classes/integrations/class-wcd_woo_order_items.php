<?php
/**
 * WooCommerce Order items.
 *
 * @package woocart-defaults
 */
class WOO_Order_Items {

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
	 * Register the order items in WCD.
     *
     * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query 	= "SELECT * FROM {$wpdb->prefix}woocommerce_order_items";
		$orders = $wpdb->get_results( $query );

		foreach ( $orders as $order ) {
			$meta  = $wpdb->get_results( $wpdb->prepare( "
				SELECT meta_key, meta_value
				FROM {$wpdb->prefix}woocommerce_order_itemmeta
				WHERE order_item_id = %d
            ", $order->order_item_id), "ARRAY_A" );

			$values     = array(
                'name' 		=> $order->order_item_name,
				'type' 		=> $order->order_item_type,
				'order_id' 	=> $order->order_id,
				'meta' 		=> $meta,
            );

			$items[ 'wooorderitems/' . $order->order_item_id ] = array(
				'value' => json_encode( $values ),
				'label' => $order->order_item_name,
				'group' => 'WooCommerce Order Items',
			);
		}

		return $items;
	}

	/**
	 * Tell WCD to use import_terms() for order items.
     *
     * @access public
	 */
	public function pull_callback( $callback, $callback_params ) {
		if ( 'wooorderitems/' == substr( $callback_params['name'], 0, 13 ) ) {
			return array( $this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) order items into the DB
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

        $id     = intval(str_replace( 'wooorderitems/', '', $params['name'] ) );
        $data   = $params['new_value'];
        $value  = json_decode( $data->value );
        $wpdb->replace(
			"{$wpdb->prefix}woocommerce_order_items",
			array(
				'order_item_id' 	=> $id,
				'order_item_name' 	=> $value->name,
				'order_item_type' 	=> $value->type,
				'order_id' 			=> $value->order_id,
			),
			array(
				'%d',
				'%s',
				'%s',
				'%d',
			)
		);

		foreach ( $value->meta as $meta ) {
			$wpdb->replace(
                "{$wpdb->prefix}woocommerce_order_itemmeta",
                array(
                    'order_item_id' => $id,
                    'meta_key' 		=> $meta->meta_key,
                    'meta_value' 	=> $meta->meta_value,
                ),
                array(
                	'%d',
                    '%s',
                    '%s',
                )
            );
		}
	}

}

new WOO_Order_Items();

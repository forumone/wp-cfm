<?php
/**
 * WooCommerce Logs.
 *
 * @package woocart-defaults
 */
class WOO_Logs {

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
	 * Register the logs in WCD.
     *
     * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query 	= "SELECT * FROM {$wpdb->prefix}woocommerce_log";
		$logs 	= $wpdb->get_results( $query );

		foreach ( $logs as $log ) {
			$values     = array(
                'timestamp' => $log->timestamp,
				'level' 	=> $log->level,
				'source' 	=> $log->source,
				'message' 	=> $log->message,
				'context' 	=> $log->context,
            );

			$items[ 'woolog/' . $log->log_id ] = array(
				'value' 	=> json_encode( $values ),
				'label' 	=> sprintf( 'Log - %d', $log->log_id ),
				'group' 	=> 'WooCommerce Logs',
			);
		}

		return $items;
	}

	/**
	 * Tell WCD to use import_terms() for logs.
     *
     * @access public
	 */
	public function pull_callback( $callback, $callback_params ) {
		if ( 'woolog/' == substr( $callback_params['name'], 0, 6 ) ) {
			return array( $this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) logs into the DB
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

        $id     = intval(str_replace( 'woolog/', '', $params['name'] ) );
        $data   = $params['new_value'];
        $value  = json_decode( $data->value );
        $wpdb->replace(
			"{$wpdb->prefix}woocommerce_log",
			array(
				'log_id' 	=> $id,
				'timestamp' => $value->timestamp,
				'level' 	=> $value->level,
				'source' 	=> $value->source,
				'message' 	=> $value->message,
				'context' 	=> $value->context,
			),
			array(
				'%d',
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
			)
		);
	}

}

new WOO_Logs();

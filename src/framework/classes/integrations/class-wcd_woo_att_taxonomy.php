<?php
/**
 * WooCommerce Attribute taxonomies.
 *
 * @package woocart-defaults
 */
class WOO_Taxonomy {

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
	 * Register the taxonomies in WCD.
     *
     * @access public
	 */
	public function configuration_items( $items ) {
		global $wpdb;

		$query 		= "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies";
		$taxonomies = $wpdb->get_results( $query );

		foreach ( $taxonomies as $taxonomy ) {
			$values     = array(
                'name' 		=> $taxonomy->attribute_name,
				'type' 		=> $taxonomy->attribute_type,
				'order' 	=> $taxonomy->attribute_orderby,
				'public' 	=> $taxonomy->attribute_public,
            );

			$items[ 'wootaxonomy/' . $taxonomy->attribute_id ] = array(
				'value' 	=> json_encode( $values ),
				'label' 	=> $taxonomy->attribute_label,
				'group' 	=> 'WooCommerce Attribute Taxonomies',
			);
		}

		return $items;
	}

	/**
	 * Tell WCD to use import_terms() for taxonomy items.
     *
     * @access public
	 */
	public function pull_callback( $callback, $callback_params ) {
		if ( 'wootaxonomy/' == substr( $callback_params['name'], 0, 11 ) ) {
			return array( $this, 'import_terms' );
		}

		return $callback;
	}

	/**
	 * Import (overwrite) taxonomies into the DB
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

        $id     = intval(str_replace( 'wootaxonomy/', '', $params['name'] ) );
        $data   = $params['new_value'];
        $value  = json_decode( $data->value );
        $wpdb->replace(
			"{$wpdb->prefix}woocommerce_attribute_taxonomies",
			array(
				'attribute_id' 		=> $id,
				'attribute_label' 	=> $data->label,
				'attribute_name' 	=> $value->name,
				'attribute_type' 	=> $value->type,
				'attribute_orderby' => $value->order,
				'attribute_public' 	=> $value->public,
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
			)
		);
	}

}

new WOO_Taxonomy();

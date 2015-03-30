<?php

class WPCFM_Taxonomy
{

    function __construct() {
        add_filter( 'wpcfm_configuration_items', array( $this, 'configuration_items' ) );
        add_filter( 'wpcfm_pull_callback', array( $this, 'pull_callback' ), 10, 2 );
    }


    function configuration_items( $items ) {
        $taxonomies = get_taxonomies( array(), array( 'output' => 'objects' ) );

        foreach ( $taxonomies as $tax ) {
            $terms = get_terms( $tax->name, array( 'hide_empty' => false ) );

            $items[ 'tax/' . $tax->name ] = array(
                'value' => json_encode( $terms ),
                'label' => $tax->label,
                'group' => 'Taxonomies',
            );

            return $items;
        }
    }


    function pull_callback( $callback, $callback_params ) {
        if ( 'tax/' == substr( $callback_params['name'], 0, 4 ) ) {
            return array( $this, 'import_taxonomy' );
        }
    }


    /**
     * Import (overwrite) taxonomies into the DB
     * @param string $params['name']
     * @param string $params['group']
     * @param string $params['old_value'] The previous settings data
     * @param string $params['new_value'] The new settings data
     */
    function import_taxonomy( $params ) {
        var_dump( $params );
    }
}

new WPCFM_Taxonomy();

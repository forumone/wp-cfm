<?php

class WPCFM_Taxonomy
{

    function __construct() {
        add_filter( 'wpcfm_configuration_items', array( $this, 'configuration_items' ) );
        add_filter( 'wpcfm_pull_callback', array( $this, 'pull_callback' ), 10, 2 );
    }


    /**
     * Register the taxonomies in WP-CFM
     */
    function configuration_items( $items ) {
        $taxonomies = get_taxonomies( array(), array( 'output' => 'objects' ) );

        foreach ( $taxonomies as $tax ) {
            $terms = get_terms( $tax->name, array( 'hide_empty' => false ) );

            $items[ 'tax/' . $tax->name ] = array(
                'value' => json_encode( $terms ),
                'label' => $tax->label,
                'group' => 'Taxonomy Terms',
            );

            return $items;
        }
    }


    /**
     * Tell WP-CFM to use import_terms() for taxonomy items
     */
    function pull_callback( $callback, $callback_params ) {
        if ( 'tax/' == substr( $callback_params['name'], 0, 4 ) ) {
            return array( $this, 'import_terms' );
        }
    }


    /**
     * Import (overwrite) taxonomies into the DB
     * @param string $params['name']
     * @param string $params['group']
     * @param string $params['old_value'] The previous settings data
     * @param string $params['new_value'] The new settings data
     */
    function import_terms( $params ) {

        $lookup = array();
        $taxonomy_name = str_replace( 'tax/', '', $params['name'] );
        $old_terms = json_decode( $params['old_value'], true );
        $new_terms = json_decode( $params['new_value'], true );

        // Create a lookup array to compare differences
        foreach ( $old_terms as $term ) {
            $term_id = $term['term_id'];
            unset( $term['term_id'] );
            $lookup['old']['slug'][ $term['slug'] ] = $term;
            $lookup['old']['id'][ $term['term_id'] ] = $term;
        }

        foreach ( $new_terms as $term ) {
            $term_id = $term['term_id'];
            unset( $term['term_id'] );
            $lookup['new']['slug'][ $term['slug'] ] = $term;
            $lookup['new']['id'][ $term['term_id'] ] = $term;
        }

        // Loop through the "desired" terms
        foreach ( $new_terms as $term ) {
            $term_id = (int) $term['term_id'];
            $parent_id = (int) $term['parent_id'];
            $slug = $term['slug'];

            // By default, create the new term
            $create_term = true;

            /**
             * Find the parent ID (it could have changed)
             */
            if ( 0 < $parent_id ) {
                $old_parent_slug = $lookup['old']['id'][ $parent_id ]['slug'];
                $parent_id = (int) $lookup['new']['slug'][ $old_parent_slug ]['term_id'];
            }

            /**
             * Scenario A: the term ID exists
             */
            if ( isset( $lookup['old']['id'][ $term_id ] ) ) {
                $old_term = $lookup['old']['id'][ $term_id ];

                // If the slug is the same, simply update term details
                if ( $old_term['slug'] == $slug ) {
                    wp_update_term( $term_id, $taxonomy_name, array(
                        'name'          => $term['name'],
                        'description'   => $term['description'],
                        'parent'        => $parent_id,
                    ) );

                    $create_term = false;
                }
            }

            /**
             * Scenario B: the same slug exists
             */
            if ( isset( $lookup['old']['slug'][ $slug ] ) ) {
                $old_term = $lookup['old']['slug'][ $slug ];

                wp_update_term( $term_id, $taxonomy_name, array(
                    'name'          => $term['name'],
                    'description'   => $term['description'],
                    'parent'        => $parent_id,
                ) );

                $create_term = false;
            }

            /**
             * Scenario C: the term is new
             */
            if ( $create_term ) {
                wp_insert_term( $term['name'], $taxonomy_name, array(
                    'description'   => $term['description'],
                    'parent'        => $parent_id,
                    'slug'          => $slug,
                ) );
            }
        }

        exit;
    }
}

new WPCFM_Taxonomy();

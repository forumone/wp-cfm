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

            $values = array();
            foreach ( $terms as $term ) {
                $values[] = array(
                    'term_id'       => $term->term_id,
                    'name'          => $term->name,
                    'slug'          => $term->slug,
                    'description'   => $term->description,
                    'parent'        => $term->parent,
                );
            }

            $items[ 'tax/' . $tax->name ] = array(
                'value' => json_encode( $values ),
                'label' => $tax->label,
                'group' => 'Taxonomy Terms',
            );

        }
        return $items;
    }


    /**
     * Tell WP-CFM to use import_terms() for taxonomy items
     */
    function pull_callback( $callback, $callback_params ) {
        if ( 'tax/' == substr( $callback_params['name'], 0, 4 ) ) {
            return array( $this, 'import_terms' );
        }
        return $callback;
    }


    /**
     * Import (overwrite) taxonomies into the DB
     * @param string $params['name']
     * @param string $params['group']
     * @param string $params['old_value'] The old settings (DB)
     * @param string $params['new_value'] The new settings (file)
     */
    function import_terms( $params ) {

        // Lookup arrays
        $lookup = array();
        $term_id_mapping = array();

        $taxonomy_name = str_replace( 'tax/', '', $params['name'] );
        $old_terms = json_decode( $params['old_value'], true );
        $new_terms = json_decode( $params['new_value'], true );

        // Create a lookup array to compare differences
        foreach ( $old_terms as $term ) {
            $lookup['slug'][ $term['slug'] ] = $term;
            $lookup['id'][ $term['term_id'] ] = $term;
        }

        $new_terms = $this->sort_terms_by_hierarchy( $new_terms );

        // Loop through the "desired" terms
        foreach ( $new_terms as $term ) {
            $term_id = (int) $term['term_id'];
            $parent = (int) $term['parent'];
            $slug = $term['slug'];

            // Defaults
            $create_term = true;

            /**
             * SCENARIO: the term ID and slug are unchanged
             * ACTION: update the term
             */
            if ( isset( $lookup['id'][ $term_id ] ) && $slug == $lookup['id'][ $term_id ]['slug'] ) {
                $create_term = false;
            }

            /**
             * SCENARIO: the slug exists
             * ACTION: update the term
             */
            if ( isset( $lookup['slug'][ $slug ] ) ) {
                $old_term_id = $term_id;
                $term_id = (int) $lookup['slug'][ $slug ]['term_id'];
                $term_id_mapping[ $old_term_id ] = $term_id;
                $create_term = false;
            }

            /**
             * Get the parent term ID (from the mapping array)
             */
            if ( 0 < $parent && isset( $term_id_mapping[ $parent ] ) ) {
                $parent = $term_id_mapping[ $parent ];
            }

            /**
             * Create or update the term
             */
            if ( $create_term ) {
                $response = wp_insert_term( $term['name'], $taxonomy_name, array(
                    'description'   => $term['description'],
                    'parent'        => $parent,
                    'slug'          => $slug,
                ) );

                $term_id_mapping[ $term_id ] = (int) $response['term_id'];
            }
            else {
                $response = wp_update_term( $term_id, $taxonomy_name, array(
                    'description'   => $term['description'],
                    'parent'        => $parent,
                    'name'          => $term['name'],
                ) );
            }
        }
    }

    /**
     * Sorts a flat list of terms to ensure that parent terms will always be created
     * prior to their children. Uses recursion to recurse into multiple levels of the
     * term hierarchy.
     *
     * @param array   $terms The full list of terms to sort.
     * @param integer $parent_id Parent term ID for current iteration.
     * @return array The array sorted with children immediately following their parent
     */
    public function sort_terms_by_hierarchy( $terms, $parent_id = 0 ) {
        $new_terms = array();

        // Get all child terms of the supplied parent.
        $child_terms = array_filter(
            $terms,
            function ( $t ) use ( $parent_id ) {
                return $t['parent'] === $parent_id;
            }
        );

        // Iterate through each child term.
        foreach ( $child_terms as $term ) {
            $new_terms[] = $term;

            // Recurse into potential child terms.
            $deeper_child_terms = $this->sort_terms_by_hierarchy( $terms, $term['term_id'] );

            // Merge such that parent term comes before children.
            // Will include all lower levels.
            $new_terms = array_merge( $new_terms, $deeper_child_terms );
        }

        return $new_terms;
    }
}

new WPCFM_Taxonomy();

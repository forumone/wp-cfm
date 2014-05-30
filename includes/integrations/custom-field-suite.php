<?php

// Registration hook
add_filter( 'wpcfm_configuration_items', 'cfs_configuration_items' );


/**
 * Register CFS configuration options
 */
function cfs_configuration_items( $items ) {

    $items['cfs_field_groups'] = array(
        'value'     => cfs_get_field_groups(),
        'group'     => 'Custom Field Suite',
        'callback'   => 'cfs_import_field_groups',
    );

    return $items;
}


/**
 * Load all field groups into a nice JSON string
 */
function cfs_get_field_groups() {
    global $cfs, $wpdb;

    $field_group_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'cfs'" );
    $field_groups = $cfs->field_group->export( array(
        'field_groups' => $field_group_ids,
    ) );

    return json_encode( $field_groups );
}


/**
 * Import (overwrite) field groups into DB
 * @param string $params['name']
 * @param string $params['group']
 * @param string $params['old_value'] The previous settings data
 * @param string $params['new_value'] The new settings data
 */
function cfs_import_field_groups( $params ) {
    global $cfs;

    $import_code = array();
    $old_value = json_decode( $params['old_value'], true );
    $new_value = json_decode( $params['new_value'], true );
    $next_field_id = (int) get_option( 'cfs_next_field_id' );

    // Store old field names & IDs
    $old_field_groups = array();

    foreach ( $old_value as $field_group ) {
        $old_fields = array();
        foreach ( $field_group['cfs_fields'] as $field ) {
            $old_fields[ $field['name'] ] = $field['id'];
        }
        $old_field_groups[ $field_group['post_name'] ] = $old_fields;
    }

    // Change the field IDs (if needed), then save
    foreach ( $new_value as $field_group ) {
        $group_name = $field_group['post_name'];

        foreach ( $field_group['cfs_fields'] as $key => $field ) {
            $field_name = $field['name'];

            // Preserve the old field ID
            if ( isset( $old_field_groups[ $group_name ][ $field_name ] ) ) {
                $field_id = $old_field_groups[ $group_name ][ $field_name ];
                $field_group['cfs_fields'][ $key ]['id'] = $field_id;
            }
            // Otherwise, increment the field ID counter
            else {
                $field_group['cfs_fields'][ $key ]['id'] = $next_field_id;
                $next_field_id++;
            }
        }

        cfs_save_field_group( $field_group );
    }

    update_option( 'cfs_next_field_id', $next_field_id );
}


/**
 * Save a CFS field group
 */
function cfs_save_field_group( $field_group ) {
    $args = array(
        'name'              => $field_group['post_name'],
        'post_type'         => 'cfs',
        'posts_per_page'    => 1,
        'fields'            => 'ids',
    );

    $posts = get_posts( $args );

    if ( ! empty( $posts ) ) {
        $post_id = $posts[0];
    }
    else {
        $post_id = wp_insert_post( array(
            'post_title'    => $field_group['post_title'],
            'post_name'     => $field_group['post_name'],
            'post_type'     => 'cfs',
            'post_status'   => 'publish',
            'post_content'  => '',
        ) );
    }

    // Insert the postmeta settings
    update_post_meta( $post_id, 'cfs_fields', $field_group['cfs_fields'] );
    update_post_meta( $post_id, 'cfs_rules', $field_group['cfs_rules'] );
    update_post_meta( $post_id, 'cfs_extras', $field_group['cfs_extras'] );
}

<?php

// Skip if CFS isn't active
if ( ! is_plugin_active( 'custom-field-suite/cfs.php' ) ) {
    return;
}


// Registration hook
add_filter( 'wpcfm_configuration_items', 'cfs_configuration_items' );
add_filter( 'wpcfm_pull_callback', 'cfs_pull_callback', 10, 2 );


/**
 * Register CFS configuration options
 */
function cfs_configuration_items( $items ) {

    $field_groups = cfs_get_field_groups();

    foreach ( $field_groups as $name => $group ) {
        $value = json_encode( $group );
        $items[ "cfs_field_group_$name" ] = array(
            'value'     => $value,
            'group'     => 'Custom Field Suite',
            'callback'   => 'cfs_import_field_group',
        );
    }

    return $items;
}


/**
 * When Pulling configuration, make sure that all items
 * beginning with "cfs_field_group" are using the right callback
 */
function cfs_pull_callback( $callback, $callback_params ) {
    if ( false !== strpos( $callback_params['name'], 'cfs_field_group' ) ) {
        $callback = 'cfs_import_field_group';
    }
    return $callback;
}


/**
 * Load all field groups into a nice JSON string
 */
function cfs_get_field_groups() {
    global $cfs, $wpdb;

    $field_groups = array();
    $field_group_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'cfs'" );
    $export = $cfs->field_group->export( array(
        'field_groups' => $field_group_ids,
    ) );

    foreach ( $export as $field_group ) {
        $field_groups[ $field_group['post_name'] ] = $field_group;
    }

    return $field_groups;
}


/**
 * Import (overwrite) field groups into DB
 * @param string $params['name']
 * @param string $params['group']
 * @param string $params['old_value'] The previous settings data
 * @param string $params['new_value'] The new settings data
 */
function cfs_import_field_group( $params ) {
    global $cfs;

    $import_code = array();
    $old_value = json_decode( $params['old_value'], true );
    $new_value = json_decode( $params['new_value'], true );
    $next_field_id = (int) get_option( 'cfs_next_field_id' );

    // Store old field names & IDs
    $old_field_ids = array();
    if ( ! empty( $old_value ) ) {
        foreach ( $old_value['cfs_fields'] as $field ) {
            $old_field_ids[ $field['name'] ] = $field['id'];
        }
    }

    // Change the field IDs (if needed), then save
    $field_group = $new_value;

    foreach ( $field_group['cfs_fields'] as $key => $field ) {
        $field_name = $field['name'];

        // Preserve the old field ID
        if ( isset( $old_field_ids[ $field_name ] ) ) {
            $field_id = $old_field_ids[ $field_name ];
            $field_group['cfs_fields'][ $key ]['id'] = $field_id;
        }
        // Otherwise, increment the field ID counter
        else {
            $field_group['cfs_fields'][ $key ]['id'] = $next_field_id;
            $next_field_id++;
        }
    }

    cfs_save_field_group( $field_group );

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

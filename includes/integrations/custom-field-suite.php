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

    $import_code = json_decode( $params['new_value'], true );

    /*
    $cfs->field_group->import( array(
        'import_code' => $import_code,
    ) );
    */
}

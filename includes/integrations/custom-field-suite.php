<?php

add_filter( 'wpcfm_configuration_items', 'cfs_configuration_items' );


/**
 * Register CFS configuration options
 */
function cfs_configuration_items( $items ) {

    $items['cfs_field_groups'] = array(
        'value'     => cfs_get_field_groups(),
        'group'     => 'Custom Field Suite',
        'handler'   => 'cfs_import_field_groups',
    );

    return $items;
}


/**
 * Load all field groups into a nice JSON string
 */
function cfs_get_field_groups() {
    return 'hello world';
}


/**
 * Import / merge field groups into DB
 * @param string $params['name']
 * @param string $params['group']
 * @param string $params['old_value'] The previous settings data
 * @param string $params['new_value'] The new settings data
 */
function cfs_import_field_groups( $params ) {

}

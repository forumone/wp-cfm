<?php

class WPCFM_Readwrite
{
    public $folder;

    function __construct() {
        $this->folder = WP_CONTENT_DIR . '/wp-cfm';
        if ( ! is_dir( $this->folder ) ) {
            mkdir( $this->folder );
        }

        $content = get_option( 'wpcfm_settings' );
        $this->write_file( 'testy', $content );
    }


    /**
     * Move the file bundle to DB
     */
    function pull_bundle( $bundle_name ) {
        $data = $this->read_file( $bundle_name );
        $this->write_db( $bundle_name, $data );
    }


    /**
     * Move the DB bundle to file
     */
    function push_bundle( $bundle_name ) {
        $data = $this->read_db( $bundle_name );
        $this->write_file( $bundle_name, $data );
    }


    /**
     * Compare the DB vs file versions
     */
    function compare_bundle( $bundle_name ) {
        $file_bundle = $this->read_file( $bundle_name );
        $db_bundle = $this->read_db( $bundle_name );
        // Compare!
    }


    /**
     * Load the file bundle
     */
    function read_file( $bundle_name ) {

    }


    /**
     * Write the bundle to file
     */
    function write_file( $bundle_name, $data ) {
        return file_put_contents( "$this->folder/$bundle_name.json", $data );
    }


    /**
     * Load the DB bundle
     */
    function read_db( $bundle_name ) {
        return file_get_contents( "$this->folder/$bundle_name.json" );
    }


    /**
     * Write the bundle to DB
     */
    function write_db( $bundle_name, $data ) {

    }
}

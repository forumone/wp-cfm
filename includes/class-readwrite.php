<?php

class WPCFM_Readwrite
{
    public $folder;
    public $settings;


    function __construct() {

        // Create the "wp-cfm" folder
        $this->folder = WP_CONTENT_DIR . '/wp-cfm';
        if ( ! is_dir( $this->folder ) ) {
            mkdir( $this->folder );
        }

        $this->push_bundle( 'widgets' );
    }


    /**
     * Move the file bundle to DB
     * Pull is difficult; we need to figure out how to properly
     * import each setting into the database
     */
    function pull_bundle( $bundle_name ) {
        $data = $this->read_file( $bundle_name );
        $this->write_db( $bundle_name, $data );
    }


    /**
     * Move the DB bundle to file
     * Push is easy; we simply write data to file
     */
    function push_bundle( $bundle_name ) {
        $data = $this->read_db( $bundle_name );
        $this->write_file( $bundle_name, json_encode( $data, JSON_PRETTY_PRINT ) );
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
     * @return array
     */
    function read_file( $bundle_name ) {
        $contents = file_get_contents( "$this->folder/$bundle_name.json" );
        return json_decode( $contents, true );
    }


    /**
     * Write the bundle to file
     */
    function write_file( $bundle_name, $data ) {
        return file_put_contents( "$this->folder/$bundle_name.json", $data );
    }


    /**
     * Load the bundle (from database)
     * @return array
     */
    function read_db( $bundle_name ) {
        $registry = new WPCFM_Registry();
        return $registry->get_configuration_items();
    }


    /**
     * Save the bundle (to database)
     */
    function write_db( $bundle_name, $data ) {

    }
}

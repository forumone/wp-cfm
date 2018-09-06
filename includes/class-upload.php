<?php

class WPCFM_Upload
{
    function __construct() {
        add_action( 'wp_ajax_upload_bundle', array($this, 'upload_bundle_ajax'));
        add_action( 'rest_api_init', function () {
            register_rest_route( 'wpcfm/v1', '/bundle/(?P<name>[\\w-_]+)', array(
                'methods' => 'POST',
                'callback' => array($this, 'upload_bundle_rest'),
            ) );
        } );
    }

    function _upload_bundle($bundle_name, $file_content) {
        //if (get_magic_quotes_runtime()) {
            $file_content = stripslashes($file_content);
        //}

        // Check it's valid JSON before writing it away
        json_decode($file_content);

        file_put_contents(WPCFM_CONFIG_DIR . "/" . $bundle_name . ".json", $file_content);
    }

    function upload_bundle_ajax() {
        $bundle_name = $_POST['bundle_name'];
        $file_content = $_POST['file_content'];
        self::_upload_bundle($bundle_name, $file_content);
        echo "OK";
        wp_die();
    }

    function upload_bundle_rest($request) {
        print_r($request);
        $bundle_name = $request['name'];
        $file_content = $request['file_content'];
        self::_upload_bundle($bundle_name, $file_content);
        return "OK";
    }
}

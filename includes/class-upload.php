<?php

class WPCFM_Upload
{
    function __construct() {
        add_action( 'wp_ajax_upload_bundle', array($this, 'upload_bundle'));
    }

    function upload_bundle() {
        $bundle_name = $_POST['bundle_name'];
        $file_content = $_POST['file_content'];
        //if (get_magic_quotes_runtime()) {
            $file_content = stripslashes($file_content);
        //}

        // Check it's valid JSON before writing it away
        json_decode($file_content);
        
        file_put_contents(WPCFM_CONFIG_DIR . "/" . $bundle_name . ".json", $file_content);
        echo "OK";
        wp_die();
    }
}

<?php

class WPCFM_Upload
{

    public $error;


    /**
     * Upload the file bundle to filesytem
     * @param string $bundle_name The bundle name
     */
    function upload_bundle($bundle_name) {
        $file_content = $_POST['file_content'];
        //if (get_magic_quotes_runtime()) {
            $file_content = stripslashes($file_content);
        //}

        // Check it's valid JSON before writing it away
        $validate = json_decode($file_content);
        if($validate === NULL) {
            $this->error = __( 'The uploaded file does not validate as JSON', 'wpcfm' );
            return;
        }

        file_put_contents(WPCFM_CONFIG_DIR . "/" . $bundle_name . ".json", $file_content);
    }
}

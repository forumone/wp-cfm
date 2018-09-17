<?php
$root_dir = dirname(dirname(__FILE__));
require_once "$root_dir/vendor/autoload.php";

WP_Mock::setUsePatchwork(true);
WP_Mock::bootstrap();

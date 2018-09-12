<?php

define( 'ABSPATH', true );
define( 'WCD_TESTS', true );

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/antecedent/patchwork/Patchwork.php';
require_once __DIR__ . '/../src/index.php';
require_once __DIR__ . '/../src/framework/classes/integrations/class-wcd_taxonomy.php';
require_once __DIR__ . '/../src/framework/classes/integrations/class-wcd_woo_options.php';
require_once __DIR__ . '/../src/framework/classes/integrations/class-wcd_woo_shipping.php';
require_once __DIR__ . '/../src/framework/classes/integrations/class-wcd_woo_tax.php';
require_once __DIR__ . '/../src/framework/classes/integrations/class-wcd_wp_options.php';

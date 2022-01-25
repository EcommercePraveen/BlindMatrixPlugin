<?php
define('BP_SLUG', 'BlindMatrix-Api');
define( 'PLUGIN_DIR', dirname(__FILE__).'/' );
define('BP_DIR', WP_PLUGIN_DIR . '/' . BP_SLUG);
define('BP_CONTROLLER', BP_DIR . '/control');
define('BP_MODEL', BP_DIR . '/model');
define('BP_VIEW', BP_DIR . '/view');
define('BP_URL', WP_PLUGIN_URL . '/BlindMatrix-Api');
include(BP_CONTROLLER."/MainController.php");
include(BP_CONTROLLER."/BlindWooc.php");
$blindwooc = new BlindWooc();
?>
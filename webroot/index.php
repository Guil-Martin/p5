<?php 

define('WEBROOT', str_replace("Webroot/index.php", "", $_SERVER["SCRIPT_NAME"]));
define('ROOT', str_replace("Webroot/index.php", "", $_SERVER["SCRIPT_FILENAME"]));

//// Config & Routing
require_once(ROOT . 'Config/core.php');

require_once(ROOT . 'router.php');
require_once(ROOT . 'request.php');
require_once(ROOT . 'dispatcher.php');

$dispatch = new Dispatcher();
$dispatch->dispatch();
////
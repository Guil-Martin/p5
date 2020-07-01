<?php 

//var_dump($_SERVER["SCRIPT_NAME"]);
//var_dump($_SERVER["SCRIPT_FILENAME"]);

define('WEBROOT', str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]));
define('ROOT', str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));

//var_dump(WEBROOT);
//var_dump(ROOT);

//// Config & Routing
require_once(ROOT . 'Config/core.php');
require_once(ROOT . 'router.php');
//require_once(ROOT . 'request.php');
//require_once(ROOT . 'dispatcher.php');

$router = new Router();
$router->dispatch();
////
<?php

use Castoware\Database;
use Castoware\Request;
use Castoware\Util;

header("Access-Control-Allow-Origin: *");

ini_set("error_log", __DIR__ . '/error.log');

require_once("vendor/autoload.php");
$router = new AltoRouter();
$util = new Util;
$request = new Request;
$database = new Database;
$db = $database->db;

require_once(__DIR__ . '/methods/index.php');

$router->addRoutes([
  ['get', '/api/get-contents/[:language]', 'getContents'],
]);

$match = $router->match();

if (is_array($match) && is_callable($match['target'])) {
  $request->params = (object) $match['params'];
  call_user_func_array(
    $match['target'],
    [
      'db' => $db,
      'request' => $request,
      'util' => $util,
    ]
  );
} else {
  // no route was matched
  header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}

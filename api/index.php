<?php

use Castoware\Database;
use Castoware\Request;
use Castoware\Util;

ini_set("error_log", __DIR__ . '/error.log');

require_once("vendor/autoload.php");
$router = new AltoRouter();
$util = new Util;
$request = new Request;
$database = new Database;
$db = $database->db;

require_once(__DIR__ . '/methods/index.php');

$router->addRoutes([
  ['get', '/api/get-admin-data', 'getAdminData'],
  ['get', '/api/get-contents/[:language]', 'getContents'],
  ['post', '/api/sign-in', 'signIn'],
  ['post', '/api/submit-donation', 'submitDonation']
]);

$match = $router->match();

if (is_array($match) && is_callable($match['target'])) {
  if (isset($request->auth)) {
    $user = $db->fetch("SELECT * FROM admin_users WHERE token=?", $request->auth);
    if (!$user) $util->fail("Invalid token");
  }

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

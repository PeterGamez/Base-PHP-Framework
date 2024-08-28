<?php

use System\Database\Connector;

require_once __ROOT__ . '/app/function.php';

$_SERVER['USER_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
$_SERVER['USER_QUERY_STRING'] = $_SERVER['QUERY_STRING'];

date_default_timezone_set("Asia/Bangkok");

spl_autoload_register('autoload');

$conn = Connector::connect();

if (str_starts_with($_SERVER['REQUEST_URI'], '/api')) {
    $_SERVER['REQUEST_URI'] = preg_replace('/^\/api/', '', $_SERVER['REQUEST_URI']);
    require_once __ROOT__ . '/routes/api.php';
} else {
    require_once __ROOT__ . '/routes/web.php';
}

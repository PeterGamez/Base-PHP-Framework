<?php

use System\Helper\Console\Module;

require_once __ROOT__ . '/app/function.php';

spl_autoload_register('autoload');

define("Default", "\033[0m");
define("RED", "\033[31m");
define("GREEN", "\033[32m");
define("YELLOW", "\033[33m");
define("BLUE", "\033[34m");
define("MAGENTA", "\033[35m");
define("CYAN", "\033[36m");
define("WHITE", "\033[37m");

if (!isset($argv[1])) {
    return Module::list();
}

if (!strpos($argv[1], ':')) die("Command not found.\n");

[$class, $method] = explode(':', $argv[1]);

$command = "System\\Helper\\Console\\$class";

if (!class_exists($command)) die("Command not found.\n");
if (!method_exists($command, $method)) die("Method not found.\n");
$command::$method(...array_slice($argv, 2));

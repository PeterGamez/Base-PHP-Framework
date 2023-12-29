<?php

use App\Helper\Console;

require_once __ROOT__ . '/app/function.php';
require_once __ROOT__ . '/app/Helper/Database.php';

define("Default", "\033[0m");
define("RED", "\033[31m");
define("GREEN", "\033[32m");
define("YELLOW", "\033[33m");
define("BLUE", "\033[34m");
define("MAGENTA", "\033[35m");
define("CYAN", "\033[36m");
define("WHITE", "\033[37m");

loaddir(__ROOT__ . '/app/Helper/Console');

if (!isset($argv[1])) {
    return Console\Module::list();
}

if (!strpos($argv[1], ':')) die("Command not found.\n");

$command = explode(':', $argv[1]);
$cmd = $command[0];
$action = $command[1];

if ($cmd == 'config') {
    if ($action == 'make') {
        return Console\Config::make();
    }
} else if ($cmd == 'controller') {
    if ($action == 'make') {
        if (!isset($argv[2])) die("Please enter controller name.");

        return Console\Controller::make($argv[2]); // $argv[2] is the table name
    }
} elseif ($cmd == 'model') {
    if ($action == 'make') {
        if (!isset($argv[2])) die("Please enter table name.");

        return Console\Model::make($argv[2]); // $argv[2] is the table name
    } else if ($action == 'clearTrash') {
        if (config('database.trash.enabled') == false) die("Trash is disabled.\n");

        if (!isset($argv[2])) die("Please enter table name.");

        return Console\Model::clearTrash($argv[2]);
    } else if ($action == 'clearAllTrash') {
        if (config('database.trash.enabled') == false) die("Trash is disabled.\n");

        return Console\Model::clearAllTrash();
    }
} else if ($cmd == 'module') {
    if ($action == 'list') {
        return Console\Module::list();
    }
}
echo "Command not found.\n";

<?php
/**
 * Root path of the project
 */
define("__ROOT__", dirname(__DIR__));

session_start();

if (empty($_SESSION['id'])) {
    $_SESSION['id'] = session_id();
    $_SESSION['login'] = false;
}

require_once __ROOT__ . '/app/autoload.php';

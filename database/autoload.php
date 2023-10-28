<?php

namespace Database;

use Exception;

try {
    $conn = mysqli_connect(config('database.host'), config('database.user'), config('database.password'), config('database.database'));
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }
} catch (Exception $e) {
    die('Exception: database connection failed');
}

mysqli_set_charset($conn, 'utf8');
date_default_timezone_set("Asia/Bangkok");

require_once __ROOT__ . '/database/DataSelect.php';
require_once __ROOT__ . '/database/Model.php';

<?php

namespace Database;

use Exception;

try {
    $conn = mysqli_connect(config('database.host'), config('database.user'), config('database.password'), config('database.database'));
    if (!$conn) {
        throw new Exception('Connection failed: ' . mysqli_connect_error());
    }
} catch (Exception $e) {
    throw new Exception('Exception: database connection failed');
}

mysqli_set_charset($conn, 'utf8mb4');
date_default_timezone_set("Asia/Bangkok");

require_once __ROOT__ . '/database/DataSelect.php';
require_once __ROOT__ . '/database/Model.php';

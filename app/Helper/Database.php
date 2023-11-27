<?php

namespace App\Helper;

use Exception;

class Database
{
    private $conn;

    public function __construct()
    {
        if (!file_exists(__ROOT__ . '/config/database.php'))
            die('Database config not found');

        try {
            $conn = mysqli_connect(config('database.host'), config('database.user'), config('database.password'), config('database.database'));
            if (!$conn) {
                die('Connection failed: ' . mysqli_connect_error());
            }
        } catch (Exception $e) {
            die('Exception: database connection failed');
        }

        mysqli_set_charset($conn, 'utf8mb4');
        date_default_timezone_set("Asia/Bangkok");

        $this->conn = $conn;
    }

    public function create($table)
    {
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `create_by` int(5) NOT NULL,
            `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `update_by` int(5) NOT NULL,
            `isTrash` enum('0','1') NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        mysqli_query($this->conn, $sql);
    }

    public function clearTrash($table)
    {
        $sql = "DELETE FROM `$table` WHERE `isTrash` = '1' AND `update_at` < DATE_SUB(NOW(), INTERVAL " . config('database.trash.day') . " DAY)";

        mysqli_query($this->conn, $sql);
    }

    public function getAllTables()
    {
        $sql = "SHOW TABLES";
        $query = mysqli_query($this->conn, $sql);
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);

        $tables = [];
        foreach ($result as $row) {
            $tables[] = $row['Tables_in_' . config('database.database')];
        }

        return $tables;
    }
}

<?php

namespace System\Helper;

use Exception;
use mysqli_result;
use PDO;
use PDOException;
use PDOStatement;
use System\Database\Connector;

class Database
{
    private $conn;

    public function __construct()
    {
        if (!file_exists(__ROOT__ . '/config/database.php'))
            die('Database config not found');

        $this->conn = Connector::connect();

        date_default_timezone_set("Asia/Bangkok");
    }

    public function create(string $table): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
            `id` int(5) NOT NULL AUTO_INCREMENT,
            `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `create_by` int(5) NOT NULL,
            `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `update_by` int(5) NOT NULL,
            `isTrash` enum('0','1') NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
        );";

        $this->execute($sql);
    }

    public function clearTrash(string $table): void
    {
        $sql = "DELETE FROM `$table` WHERE `isTrash` = '1' AND `update_at` < DATE_SUB(NOW(), INTERVAL " . config('database.trash.day') . " DAY)";

        $this->execute($sql);
    }

    public function getAllTables(): array
    {
        $sql = "SHOW TABLES";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $database = config('database.connections.' . config('database.default') . '.database');
        $tables = [];
        foreach ($result as $row) {
            $tables[] = $row['Tables_in_' . $database];
        }

        return $tables;
    }

    public function getTableStructure(string $table): string
    {
        $sql = "SHOW CREATE TABLE $table";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result[0]['Create Table'];
    }

    public function execute(string $sql): PDOStatement|bool
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
}

<?php

namespace System\Helper\Console;

use System\Helper\Database;

class Dump
{
    public static $description = [
        "run" => "Create dump of all tables"
    ];

    public static function run(): void
    {
        $db = new Database();
        $tables = $db->getAllTables();

        $structure = [];
        $date = date('Y-m-d_H-i-s');
        foreach ($tables as $table) {
            $structure = [...$structure, $db->getTableStructure($table)];
        }
        $structure = implode("\n\n", $structure);
        file_put_contents(__ROOT__ . "/database/dumps/$date.sql", $structure);

        echo "Dump created successfully.\n";
    }
}

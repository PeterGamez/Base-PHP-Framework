<?php

namespace System\Helper;

class Schema
{
    final public static function create(string $table, callable $callback): void
    {
        $db = new Database();

        $stmt = $db->execute("SHOW TABLES LIKE '$table'");
        $tableExists = $stmt->rowCount() > 0;
        if ($tableExists) {
            die("Table $table already exists.\n");
        }

        $sql = $callback();

        $db->execute($sql);
    }

    final public static function dropIfExists(string $table): void
    {
        $db = new Database();

        $query = $db->execute("SHOW TABLES LIKE '$table'");
        $tableExists = $query->rowCount() > 0;

        if ($tableExists) {
            $sql = "DROP TABLE $table";

            $db->execute($sql);
        }
    }
}

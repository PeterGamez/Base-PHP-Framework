<?php

namespace System\Helper;

use System\Fake\Generator;

class Factory
{
    static $count = 1;

    final public static function count(int $count): void
    {
        self::$count = $count;
    }

    final public static function create(string $table, callable $callback): void
    {
        $value = $callback();

        $sql = "INSERT INTO $table ";

        $columns = [];

        foreach ($value as $key => $val) {
            $columns[] = $key;
        }

        $sql .= '(' . implode(', ', $columns) . ') VALUES ';

        $rows = [];

        for ($i = 0; $i < self::$count; $i++) {
            $row = [];

            foreach ($callback() as $key => $val) {
                $row[] = $val;
            }

            $rows[] = "('" . implode("', '", $row) . "')";
        }

        $sql .= implode(', ', $rows);

        $db = new Database();
        $db->execute($sql);
    }

    final public static function fake(): Generator
    {
        return new Generator();
    }
}

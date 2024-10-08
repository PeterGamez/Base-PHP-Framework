<?php

namespace System\Database;

use System\Database\Processors\Count;
use System\Database\Processors\Create;
use System\Database\Processors\Delete;
use System\Database\Processors\Find;
use System\Database\Processors\ForceDelete;
use System\Database\Processors\Restore;
use System\Database\Processors\Update;
use System\Database\Query\BuildClause;

class Model
{
    use BuildClause;

    public static $manager_id = null;

    protected static function getManager(): string
    {
        if (self::$manager_id) {
            $manager_id = self::$manager_id;
            self::$manager_id = null;
            return $manager_id;
        } else {
            return $_SESSION['account']['id'] ?? 1;
        }
    }

    protected static function setManager($manager_id): void
    {
        self::$manager_id = $manager_id;
    }

    public static function create(array $newData): Create
    {
        $table = self::parseTable();
        $manager = self::getManager();

        $instance = new Create($table, $manager, $newData);
        return $instance;
    }

    final public static function find(array $conditions = null): Find
    {
        $table = self::parseTable();
        $instance = new Find($table, $conditions);
        return $instance;
    }

    public static function count(): Count
    {
        $table = self::parseTable();

        $instance = new Count($table);
        return $instance;
    }

    public static function update(): Update
    {
        $table = self::parseTable();
        $manager = self::getManager();

        $instance = new Update($table, $manager);
        return $instance;
    }

    public static function delete(): Delete
    {
        $table = self::parseTable();
        $manager = self::getManager();

        $instance = new Delete($table, $manager);
        return $instance;
    }

    public static function restore(): Restore
    {
        $table = self::parseTable();
        $manager = self::getManager();

        $instance = new Restore($table, $manager);
        return $instance;
    }

    public static function forceDelete(): ForceDelete
    {
        $table = self::parseTable();
        $manager = self::getManager();

        $instance = new ForceDelete($table, $manager);
        return $instance;
    }

    /**
     * Show table status
     */
    final public static function status(): array
    {
        $table = self::parseTable();
        $sql = "SHOW TABLE STATUS LIKE '$table'";
        return self::buildStatus($sql);
    }

    private static function parseTable(): string
    {
        $table = null;
        if (isset(get_called_class()::$table)) {
            $table = get_called_class()::$table;
        } else {
            $table = get_called_class();
            $table = lcfirst($table);
            $table = preg_replace('/(?<!^)[A-Z]/', '_$0', $table); /* add underscore before uppercase */
            $table = strtolower($table);
        }
        return $table;
    }
}

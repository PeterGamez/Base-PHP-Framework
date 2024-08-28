<?php

namespace System\Database\Query;

use PDO;

trait BuildClause
{
    protected static function buildCreate(string $sql, array $bindParams): ?int
    {
        global $conn;

        $stmt = $conn->prepare($sql);
        self::bindParams($stmt, $bindParams);
        $stmt->execute();
        $result = $conn->lastInsertId();

        return $result;
    }

    protected static function buildFind(string $sql, array $bindParams): array
    {
        global $conn;

        $stmt = $conn->prepare($sql);
        if (!empty($bindParams)) {
            self::bindParams($stmt, $bindParams);
        }
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    }

    protected static function buildFindOne(string $sql, array $bindParams): ?array
    {
        return self::buildFind($sql, $bindParams)[0] ?? null;
    }

    protected static function buildFindCount(string $sql, array $bindParams = []): ?int
    {
        return self::buildFind($sql, $bindParams)[0]['count'] ?? null;
    }

    protected static function buildUpdate(string $sql, array $bindParams): bool
    {
        global $conn;

        $stmt = $conn->prepare($sql);
        self::bindParams($stmt, $bindParams);
        $stmt->execute();

        $count = $stmt->rowCount();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected static function buildDelete(string $sql, array $bindParams): bool
    {
        global $conn;

        $stmt = $conn->prepare($sql);
        self::bindParams($stmt, $bindParams);
        $stmt->execute();

        $count = $stmt->rowCount();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected static function buildStatus(string $sql): ?array
    {
        return self::buildFind($sql, [])[0] ?? null;
    }

    private static function bindParams($stmt, array $params): void
    {
        for ($i = 0; $i < count($params); $i++) {
            $count = $i + 1;
            $type = null;

            $params_type = gettype($params[$i]);
            if ($params_type == 'integer') {
                $type = PDO::PARAM_INT;
            } elseif ($params_type == 'string') {
                $type = PDO::PARAM_STR;
            } elseif ($params_type == 'NULL') {
                $type = PDO::PARAM_NULL;
            } elseif ($params_type == 'boolean') {
                $type = PDO::PARAM_BOOL;
            }

            $stmt->bindParam($count, $params[$i], $type);
        }
    }
}

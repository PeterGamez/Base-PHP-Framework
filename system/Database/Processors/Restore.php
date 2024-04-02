<?php

namespace System\Database\Processors;

use Exception;
use System\Database\DataClause;
use System\Database\Query\BuildClause;
use System\Database\Query\WhereClause;

class Restore extends DataClause
{
    use BuildClause, WhereClause;

    public function __construct(string $table, int $manager)
    {
        $this->maintable = $table;
        $this->manager = $manager;

        if (config('database.trash.enabled') === false) {
            throw new Exception("Trash is disabled.");
        }
    }

    protected function query(): void
    {
        $this->query = "UPDATE " . $this->maintable . " SET isTrash = '0', update_by = " . $this->manager;

        if ($this->whereConditions) {
            $this->query .= " WHERE " . implode(' ' . $this->whereOperator . ' ', $this->whereConditions);
            if ($this->isTrash === true) {
                if ($this->whereTrash) {
                    $this->query .= " AND " . $this->whereTrash;
                } else {
                    if ($this->whereTrash === "") {
                    } else {
                        $this->query .= " AND isTrash = '0'";
                    }
                }
            }
        } else {
            if ($this->isTrash === true) {
                if ($this->whereTrash) {
                    $this->query .= " WHERE " . $this->whereTrash;
                } else {
                    if ($this->whereTrash === "") {
                    } else {
                        $this->query .= " WHERE isTrash = '0'";
                    }
                }
            }
        }
    }

    public function run(): bool
    {
        $this->query();
        return self::buildUpdate($this->query, $this->bindParams);
    }
}

<?php

namespace System\Database\Processors;

use System\Database\DataClause;
use System\Database\Query\BuildClause;
use System\Database\Query\WhereClause;

class Delete extends DataClause
{
    use BuildClause, WhereClause;

    public function __construct(string $table, int $manager)
    {
        $this->maintable = $table;
        $this->manager = $manager;

        if (config('database.trash.enabled') == true) {
            $this->isTrash = true;
        } else {
            $this->isTrash = false;
        }
    }

    protected function query(): void
    {
        if ($this->isTrash === true) {
            $this->query = "UPDATE " . $this->maintable . " SET isTrash = '1', update_by = " . $this->manager;
        } else {
            $this->query = "DELETE FROM " . $this->maintable;
        }

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

<?php

namespace System\Database\Processors;

use System\Database\DataClause;
use System\Database\Query\BuildClause;
use System\Database\Query\WhereClause;

class ForceDelete extends DataClause
{
    use BuildClause, WhereClause;

    public function __construct(string $table, int $manager)
    {
        $this->maintable = $table;
        $this->manager = $manager;
    }

    protected function query(): void
    {
        $this->query = "DELETE FROM " . $this->maintable;

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
        return self::buildDelete($this->query, $this->bindParams);
    }
}

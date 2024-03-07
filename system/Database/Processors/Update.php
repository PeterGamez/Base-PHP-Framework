<?php

namespace System\Database\Processors;

use Exception;
use System\Database\DataClause;
use System\Database\Query\BuildClause;
use System\Database\Query\WhereClause;

class Update extends DataClause
{
    use BuildClause, WhereClause;

    public function __construct(string $table, int $manager)
    {
        $this->maintable = $table;
        $this->manager = $manager;

        $this->query = "UPDATE $table";

        if (config('database.trash.enabled') == true) {
            $this->isTrash = true;
        } else {
            $this->isTrash = false;
        }
    }

    final public function set(string $column, string $value): self
    {
        if ($column === 'isTrash') throw new Exception("Column 'isTrash' is not allowed to update.");
        if ($column === 'create_at') throw new Exception("Column 'create_at' is not allowed to update.");
        if ($column === 'create_by') throw new Exception("Column 'create_by' is not allowed to update.");
        if ($column === 'update_at') throw new Exception("Column 'update_at' is not allowed to update.");
        if ($column === 'update_by') throw new Exception("Column 'update_by' is not allowed to update.");

        $this->set[] = "$column = ?";
        $this->bindParams[] = $value;
        return $this;
    }

    protected function query(): void
    {
        $this->query .= " SET " . implode(', ', $this->set) . ", update_by = " . $this->manager;

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

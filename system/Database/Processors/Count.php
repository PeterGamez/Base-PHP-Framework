<?php

namespace System\Database\Processors;

use Exception;
use System\Database\DataClause;
use System\Database\Query\BuildClause;
use System\Database\Query\JoinClause;
use System\Database\Query\WhereClause;

class Count extends DataClause
{
    use BuildClause, JoinClause, WhereClause;

    public function __construct(string $table)
    {
        $this->maintable = $table;
    }

    final public function groupBy(string $columns, string $order = 'ASC'): self
    {
        $order = strtoupper($order);
        if ($order != 'ASC' AND $order != 'DESC') {
            throw new Exception("Order must be ASC or DESC.");
        }
        $this->group[] = "$columns $order";
        return $this;
    }

    protected function query(): void
    {
        $this->query = "SELECT COUNT(*) as count FROM $this->maintable";

        if ($this->jointable) {
            $this->query .= implode(' ', $this->jointable);
        }

        if ($this->whereConditions) {
            $this->query .= " WHERE " . implode(' ' . $this->whereOperator . ' ', $this->whereConditions);
            if ($this->isTrash === true) {
                if ($this->whereTrash) {
                    $this->query .= " AND " . $this->whereTrash;
                } else {
                    if ($this->whereTrash === "") {
                    } elseif ($this->jointable) {
                        $this->query .= " AND " . $this->maintable . ".isTrash = '0'";
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
                    } elseif ($this->jointable) {
                        $this->query .= " WHERE " . $this->maintable . ".isTrash = '0'";
                    } else {
                        $this->query .= " WHERE isTrash = '0'";
                    }
                }
            }
        }

        if ($this->group) {
            $this->query .= " GROUP BY " . implode(', ', $this->group);
        }
    }

    public function run(): int
    {
        $this->query();
        return self::buildFindCount($this->query, $this->bindParams);
    }
}

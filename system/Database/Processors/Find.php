<?php

namespace System\Database\Processors;

use Exception;
use System\Database\DataClause;
use System\Database\Query\BuildClause;
use System\Database\Query\JoinClause;
use System\Database\Query\WhereClause;

class Find extends DataClause
{
    use BuildClause, JoinClause, WhereClause;

    public function __construct(string $table, array $conditions = null)
    {
        $this->maintable = $table;

        if (is_array($conditions)) {
            foreach ($conditions as $field => $value) {
                $this->where($field, $value);
            }
        }
        
        $this->query = "SELECT * FROM $this->maintable";

        if (config('database.trash.enabled') == true) {
            $this->isTrash = true;
        } else {
            $this->isTrash = false;
        }
    }

    final public function select(string ...$fields): self
    {
        $this->query = str_replace("*", implode(", ", $fields), $this->query);
        return $this;
    }

    final public function groupBy(string $columns, string $order = 'ASC'): self
    {
        $order = strtoupper($order);
        if ($order != 'ASC' && $order != 'DESC') throw new Exception("Order must be ASC or DESC.");
        $this->group[] = "$columns $order";
        return $this;
    }

    final public function having(string $columns, string $operator, string $value): self
    {
        $this->having[] = "$columns $operator $value";
        return $this;
    }

    final public function orderBy(string $columns, string $order = 'ASC'): self
    {
        $order = strtoupper($order);
        if ($order != 'ASC' && $order != 'DESC') throw new Exception("Order must be ASC or DESC.");
        $this->order[] = "$columns $order";
        return $this;
    }

    final public function limit(int $value): self
    {
        $this->limit = $value;
        return $this;
    }

    final public function offset(int $value): self
    {
        $this->offset = $value;
        return $this;
    }

    protected function query(): void
    {
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
                    } else if ($this->jointable) {
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
                    } else if ($this->jointable) {
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

        if ($this->having) {
            $this->query .= " HAVING " . implode(', ', $this->having);
        }

        if ($this->order) {
            $this->query .= " ORDER BY " . implode(', ', $this->order);
        }

        if ($this->limit) {
            $this->query .= " LIMIT " . $this->limit;
        }

        if ($this->offset) {
            $this->query .= " OFFSET " . $this->offset;
        }
    }

    /** return query */
    final public function get(): array
    {
        $this->query();
        return self::buildFind($this->query, $this->bindParams);
    }

    final public function getOne(): ?array
    {
        $this->query();
        return self::buildFindOne($this->query, $this->bindParams);
    }
}

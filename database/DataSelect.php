<?php

namespace Database;

use Exception;

class DataSelect
{
    protected $maintable;
    protected $query;
    protected $jointable = [];
    protected $whereConditions = [];
    protected $whereTrash;
    protected $whereOperator = 'AND';
    protected $bindParams = [];
    protected $order = [];
    protected $group = [];
    protected $limit;
    protected $isTrash;

    protected function __construct(string $table, array $conditions = null)
    {
        $this->maintable = $table;
        $this->query = "SELECT * FROM $table";
        if (is_array($conditions)) {
            foreach ($conditions as $field => $value) {
                $this->where($field, $value);
            }
        }
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

    final public function join(string $table, string $column1, string $column2, string $tablealias = null): self
    {
        if ($tablealias) $this->jointable[] = " JOIN $table AS $tablealias ON $tablealias.$column1 = " . $this->maintable . ".$column2";
        else $this->jointable[] = " JOIN $table ON $table.$column1 = " . $this->maintable . ".$column2";
        return $this;
    }

    final public function leftJoin(string $table, string $column1, string $column2, string $tablealias = null): self
    {
        if ($tablealias) $this->jointable[] = " LEFT JOIN $table AS $tablealias ON $tablealias.$column1 = " . $this->maintable . ".$column2";
        else $this->jointable[] = " LEFT JOIN $table ON $table.$column1 = " . $this->maintable . ".$column2";
        return $this;
    }

    final public function rightJoin(string $table, string $column1, string $column2, string $tablealias = null): self
    {
        if ($tablealias) $this->jointable[] = " RIGHT JOIN $table AS $tablealias ON $tablealias.$column1 = " . $this->maintable . ".$column2";
        else $this->jointable[] = " RIGHT JOIN $table ON $table.$column1 = " . $this->maintable . ".$column2";
        return $this;
    }

    final public function fullJoin(string $table, string $column1, string $column2): self
    {
        $this->jointable[] = " FULL JOIN $table ON $table.$column1 = " . $this->maintable . ".$column2";
        return $this;
    }

    final public function where(string $column, string $value): self
    {
        if (is_array($value)) {
            $placeholders = implode(', ', array_fill(0, count($value), '?'));
            $this->whereConditions[] = "$column IN ($placeholders)";
            $this->bindParams = [...$this->bindParams, ...$value];
        } else {
            $this->whereConditions[] = "$column = ?";
            $this->bindParams[] = $value;
        }
        return $this;
    }

    final public function whereNot(string $column, string|array $value): self
    {
        if (is_array($value)) {
            $placeholders = implode(', ', array_fill(0, count($value), '?'));
            $this->whereConditions[] = "$column NOT IN ($placeholders)";
            $this->bindParams = [...$this->bindParams, ...$value];
        } else {
            $this->whereConditions[] = "$column != ?";
            $this->bindParams[] = $value;
        }
        return $this;
    }

    final public function whereLike(string $column, string|array $value): self
    {
        $this->whereConditions[] = "$column LIKE ?";
        $this->bindParams[] = $value;
        return $this;
    }

    final public function whereNotLike(string $column, string $value): self
    {
        $this->whereConditions[] = "$column NOT LIKE ?";
        $this->bindParams[] = $value;
        return $this;
    }

    final public function whereNull(string $column): self
    {
        $this->whereConditions[] = "$column IS NULL";
        return $this;
    }

    final public function whereNotNull(string $column): self
    {
        $this->whereConditions[] = "$column IS NOT NULL";
        return $this;
    }

    final public function whereBetween(string $column, string $value1, string $value2): self
    {
        $this->whereConditions[] = "$column BETWEEN ? AND ?";
        $this->bindParams[] = $value1;
        $this->bindParams[] = $value2;
        return $this;
    }

    final public function whereNotBetween(string $column, string $value1, string $value2): self
    {
        $this->whereConditions[] = "$column NOT BETWEEN ? AND ?";
        $this->bindParams[] = $value1;
        $this->bindParams[] = $value2;
        return $this;
    }

    final public function operator(string $operator): self
    {
        $operator = strtoupper($operator);
        $this->whereOperator = ($operator == 'OR') ? 'OR' : 'AND';
        return $this;
    }

    final public function withTrash(): self
    {
        if ($this->isTrash === false) throw new Exception("Trash is disabled.\n");
        $this->whereTrash = "";
        return $this;
    }

    final public function onlyTrash(): self
    {
        if ($this->isTrash === false) throw new Exception("Trash is disabled.\n");
        if ($this->jointable) {
            $this->whereTrash = $this->maintable . ".isTrash = '1'";
        } else {
            $this->whereTrash = "isTrash = '1'";
        }
        return $this;
    }

    final public function orderBy(string $columns, string $order = 'ASC'): self
    {
        $order = strtoupper($order);
        if ($order != 'ASC' && $order != 'DESC') throw new Exception("Order must be ASC or DESC.");
        $this->order[] = "$columns $order";
        return $this;
    }

    final public function groupBy(string $columns, string $order = 'ASC'): self
    {
        $order = strtoupper($order);
        if ($order != 'ASC' && $order != 'DESC') throw new Exception("Order must be ASC or DESC.");
        $this->group[] = "$columns $order";
        return $this;
    }

    final public function limit(int $value): self
    {
        $this->limit = $value;
        return $this;
    }

    private function query(): void
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

        if ($this->order) {
            $this->query .= " ORDER BY " . implode(', ', $this->order);
        }

        if ($this->group) {
            $this->query .= " GROUP BY " . implode(', ', $this->group);
        }

        if ($this->limit) {
            $this->query .= " LIMIT " . $this->limit;
        }
    }

    /** return query */
    final public function get(): array
    {
        $this->query();
        return Model::buildFind($this->query, $this->bindParams);
    }

    final public function getOne(): ?array
    {
        $this->query();
        return Model::buildFindOne($this->query, $this->bindParams);
    }

    final public function sql(): void
    {
        $this->query();
        echo $this->query;
        exit;
    }
}

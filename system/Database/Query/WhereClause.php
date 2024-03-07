<?php

namespace System\Database\Query;

use Exception;

trait WhereClause
{
    final public function where(string $column, string|array $value): self
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

    final public function whereLike(string $column, string $value): self
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
}

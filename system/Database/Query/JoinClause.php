<?php

namespace System\Database\Query;

trait JoinClause
{
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
        $this->jointable[] = " FULL OUTER JOIN $table ON $table.$column1 = " . $this->maintable . ".$column2";
        return $this;
    }
}

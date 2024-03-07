<?php

namespace System\Database\Processors;

use Exception;
use System\Database\DataClause;
use System\Database\Query\BuildClause;

class Create extends DataClause
{
    use BuildClause;

    public function __construct(string $table, int $manager, array $newData)
    {
        $this->maintable = $table;
        $this->manager = $manager;

        if (isset($newData['create_at'])) throw new Exception("Column 'create_at' is not allowed to create.");
        if (isset($newData['create_by'])) throw new Exception("Column 'create_by' is not allowed to create.");
        if (isset($newData['update_at'])) throw new Exception("Column 'update_at' is not allowed to create.");
        if (isset($newData['update_by'])) throw new Exception("Column 'update_by' is not allowed to create.");
        if (isset($newData['isTrash'])) throw new Exception("Column 'isTrash' is not allowed to create.");

        $this->create = $newData;
    }

    protected function query(): void
    {
        $newData['create_by'] = $this->manager;
        $newData['update_by'] = $this->manager;

        $this->query = "INSERT INTO " . $this->maintable;
        $this->bindParams = array_values($newData);

        $query = [];

        foreach ($newData as $field => $value) {
            $query[] = $field;
        }

        $this->query .= " (" . implode(", ", $query) . ") VALUES (" . implode(", ", array_fill(0, count($query), "?")) . ")";
    }

    public function run(): int
    {
        $this->query();
        return self::buildCreate($this->query, $this->bindParams);
    }
}

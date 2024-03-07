<?php

namespace System\Database;

class DataClause
{
    protected $maintable;
    protected $manager;
    protected $query;
    protected $bindParams = [];
    protected $isTrash;

    protected $jointable = [];

    protected $whereConditions = [];
    protected $whereTrash;
    protected $whereOperator = 'AND';

    protected $group = [];
    protected $having = [];
    protected $order = [];
    protected $limit;
    protected $offset;

    protected $create = [];
    protected $set = [];

    final public function sql(): void
    {
        $called = get_called_class();
        $called::query();
        echo $this->query;
        exit;
    }
}

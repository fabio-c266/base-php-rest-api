<?php

namespace App\Core;

use App\Utils\StringFormatter;

class Model
{
    protected $table = '';

    public function all(array $columns = null)
    {
        if (!$columns) {
            $columns = '*';
        } else {
            $columns = implode(',', $columns);
        }

        return "select {$columns} from {$this->table}";
    }

    public function where($column, $value, $action = '=')
    {
        return " where {$column} {$action} '{$value}'";
    }

    public function insert(array $columns, array $data)
    {
        $columnsJoined = implode(", ", $columns);
        $values = StringFormatter::ArrayToQueryValues($data);

        return "insert into {$this->table} ({$columnsJoined}) values ({$values})";
    }
}

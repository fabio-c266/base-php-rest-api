<?php

namespace App\Core;

use App\Utils\StringHelper;

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

    public function where($column, $action = '=', $value)
    {
        return " where {$column} {$action} '{$value}'";
    }

    public function insert(array $columns, array $data)
    {
        $columnsJoined = implode(", ", $columns);
        $values = StringHelper::paramsStringQuery($data);

        return "insert into {$this->table} ({$columnsJoined}) values ({$values})";
    }
}

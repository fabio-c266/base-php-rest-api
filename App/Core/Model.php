<?php

namespace App\Core;

class Model
{
    protected $table = '';

    // public function all(array $columns = null)
    // {
    //     if ($columns == null) {
    //         $columns = '*';
    //     } else {
    //         $columns = implode(',', $columns);
    //     }

    //     return "select {$columns} from {$this->table}";
    // }

    // public function where($column, $action = '=', $value)
    // {
    //     return ' where ' . $this->table . "." . $column . ' ' . $action . ' ' . $value;
    // }
}

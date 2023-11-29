<?php

namespace App\Models;

use App\Config\Database;
use App\Core\Model;
use App\Utils\StringHelper;
use Exception;

class UserModel extends Model
{
    protected $table = 'users';

    public function create(array $data)
    {
        try {
            $values = StringHelper::paramsStringQuery($data);
            Database::query("INSERT INTO {$this->table} (email, password) " . "VALUES ({$values})");
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }

    public function findByEmail(string $email)
    {
        $user = Database::query("SELECT id, email, password FROM {$this->table} where email = '{$email}'");

        return $user ? $user[0] : null;
    }

    public function findOne(string $id)
    {
        $user = Database::query("SELECT id, email, created_at FROM {$this->table} where id = '{$id}'");

        return $user ? $user[0] : null;
    }
}

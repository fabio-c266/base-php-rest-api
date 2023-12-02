<?php

namespace App\Models;

use App\Config\Database;
use App\Core\Model;
use Exception;

class UserModel extends Model
{
    protected $table = 'users';

    public function create(array $data)
    {
        try {
            $query = $this->insert(columns: ['id', 'name', 'email', 'password', 'phone'], data: $data);

            Database::query($query);
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }

    public function findByEmail(string $email)
    {
        $query = $this->all(columns: ['id', 'email', 'password']) . $this->where(column: 'email', value: $email);
        $user = Database::query($query);

        return $user ? $user[0] : null;
    }

    public function findOne(string $id)
    {
        $query = $this->all(['id', 'name', 'email', 'phone', 'created_at']) . $this->where(column: "id", value: $id);
        $user = Database::query($query);

        return $user ? $user[0] : null;
    }
}

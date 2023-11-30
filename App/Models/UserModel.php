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
            $query = $this->insert(['email', 'password'], $data);

            Database::query($query);
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }

    public function findByEmail(string $email)
    {
        $query = $this->all(['id', 'email', 'password']) . $this->where('email', '=', $email);
        $user = Database::query($query);

        return $user ? $user[0] : null;
    }

    public function findOne(string $id)
    {
        $query = $this->all(['id', 'email', 'created_at']) . $this->where("id", '=', $id);
        $user = Database::query($query);

        return $user ? $user[0] : null;
    }
}

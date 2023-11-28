<?php

namespace App\Controllers;

class UserController
{
    public function index($req)
    {
        return json_encode(["status" => 200, "data" => "teste"]);
    }
}

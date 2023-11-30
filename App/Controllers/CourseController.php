<?php

namespace App\Controllers;

class CourseController
{
    public function index($req)
    {
        $data = [
            "status" => 200,
            "data" => []
        ];

        return json_encode($data);
    }
}

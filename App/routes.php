<?php

namespace App;

use App\Core\Routes;

Routes::post('/auth/login', 'AuthController::authenticate');
Routes::get('/users', 'UserController::get');
Routes::post('/users', 'UserController::create');
Routes::get('/courses', 'CourseController::get', isNeedAuth: true);

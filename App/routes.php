<?php

namespace App;

use App\Core\Routes;

Routes::post('/auth/login', 'AuthController::authenticate');
Routes::get('/users', 'UserController::get', isNeedAuth: true);
Routes::post('/users', 'UserController::create');

<?php

namespace Application;

use App\Config\Database;
use App\Config\Env;
use App\Core\Request;
use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

Env::validate();
Database::connect();

header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if (isset($_REQUEST)) {
    Request::handler($_SERVER);
}

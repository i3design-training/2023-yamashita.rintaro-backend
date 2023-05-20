<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use Slim\Factory\AppFactory;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$app = AppFactory::create();

$routes = require __DIR__ . '/routes.php';
$routes($app);

$manager = new Manager();
$manager->addConnection([
    'driver' => 'pgsql',
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: 5432,
    'database' => getenv('DB_DATABASE') ?: 'todo',
    'username' => getenv('DB_USERNAME') ?: 'todo',
    'password' => getenv('DB_PASSWORD') ?: 'todo',
    'prefix' => '',
]);
$manager->bootEloquent();

return $app;

<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$routes = require __DIR__ . '/routes.php';
$routes($app);

$manager = new Manager();
$manager->addConnection([]);
$manager->bootEloquent();

return $app;

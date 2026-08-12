<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;

$controller = new HomeController();
$uri = $_SERVER['REQUEST_URI'];

if ($uri === '/about') {
    $controller->about();
} else {
    $controller->index();
}

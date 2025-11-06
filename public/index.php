<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

session_start();

$router = require __DIR__ . '/../bootstrap/app.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

$router->dispatch($method, $_SERVER['REQUEST_URI'] ?? '/');

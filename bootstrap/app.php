<?php

use App\Core\Database;
use App\Core\Router;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Models\ProductRepository;

$router = new Router();

$database = new Database(__DIR__ . '/../storage/database.sqlite');
$repository = new ProductRepository($database->connection());
$productController = new ProductController($repository);
$authController = new AuthController();
$landingController = new LandingController();

$router->get('/', fn () => $landingController->show());
$router->get('/dashboard', fn () => $productController->dashboard());
$router->get('/products/create', fn () => $productController->create());
$router->post('/products', fn () => $productController->store());
$router->get('/products/{id}/edit', fn ($id) => $productController->edit($id));
$router->post('/products/{id}', fn ($id) => $productController->update($id));
$router->post('/products/{id}/delete', fn ($id) => $productController->destroy($id));
$router->get('/api/stock', fn () => $productController->stats());

$router->get('/login', fn () => $authController->showLogin());
$router->post('/login', fn () => $authController->login());
$router->post('/logout', fn () => $authController->logout());

return $router;

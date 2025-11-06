<?php

use App\Core\Router;
use App\Http\Controllers\ProductController;
use App\Models\ProductRepository;

$router = new Router();

$repository = new ProductRepository(__DIR__ . '/../storage/products.json');
$controller = new ProductController($repository);

$router->get('/', fn () => $controller->index());
$router->get('/products/create', fn () => $controller->create());
$router->post('/products', fn () => $controller->store());
$router->get('/products/{id}/edit', fn ($id) => $controller->edit($id));
$router->post('/products/{id}', fn ($id) => $controller->update($id));
$router->post('/products/{id}/delete', fn ($id) => $controller->destroy($id));

return $router;

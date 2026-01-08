<?php
declare(strict_types=1);

use App\Middleware\CloudTokenMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->post('/items', function ($request, $response) {
    $response->getBody()->write(json_encode(['status' => 'ok']));
    return $response->withHeader('Content-Type', 'application/json');
})->add(new CloudTokenMiddleware());

$app->run();

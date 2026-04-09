<?php

declare(strict_types=1);

use App\Core\Dispatcher;
use App\Core\Router;

try {
    require_once dirname(__DIR__) . '/app/bootstrap.php';

    $router = new Router();

    $router->get('/', ['IndexController', 'homeAction']);

    $router->get('/jobs', ['JobController', 'listAction']);
    $router->get('/jobs/create', ['JobController', 'createAction']);
    $router->post('/jobs/store', ['JobController', 'storeAction']);
    $router->get('/jobs/{id}', ['JobController', 'showAction']);
    $router->get('/jobs/{id}/edit', ['JobController', 'editAction']);
    $router->post('/jobs/{id}/update', ['JobController', 'updateAction']);
    $router->post('/jobs/{id}/delete', ['JobController', 'deleteAction']);

    $router->get('/categories/{id}/jobs', ['CategoryController', 'showAction']);

    $dispatcher = new Dispatcher();
    $matchedRoute = $router->match(
        $_SERVER['REQUEST_METHOD'] ?? 'GET',
        $_SERVER['REQUEST_URI'] ?? '/'
    );

    $dispatcher->dispatch($matchedRoute);
} catch (Throwable $exception) {
    error_log('[BOOTSTRAP] ' . $exception->getMessage() . PHP_EOL . $exception->getTraceAsString());

    http_response_code(500);

    echo '<!doctype html><html lang="es"><head><meta charset="utf-8"><title>Error 500</title></head><body>';
    echo '<h1>Error de arranque</h1><p>La aplicacion no pudo iniciar correctamente.</p>';

    if (!empty($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
        echo '<pre>' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
    }

    echo '</body></html>';
}

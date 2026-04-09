<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\ErrorController;
use Throwable;

class Dispatcher
{
    public function dispatch(?array $route): void
    {
        try {
            if ($route === null) {
                throw new HttpException('Ruta no encontrada.', 404);
            }

            $controllerClass = 'App\\Controllers\\' . ($route['controller'] ?? '');
            $actionMethod = (string) ($route['action'] ?? '');

            if (!class_exists($controllerClass)) {
                throw new HttpException('Controlador no encontrado.', 404);
            }

            $controller = new $controllerClass();

            if (!method_exists($controller, $actionMethod)) {
                throw new HttpException('Accion no encontrada.', 404);
            }

            $params = array_values((array) ($route['params'] ?? []));
            $controller->{$actionMethod}(...$params);
        } catch (HttpException $exception) {
            $this->handleHttpException($exception);
        } catch (Throwable $exception) {
            $this->handleThrowable($exception);
        }
    }

    private function handleHttpException(HttpException $exception): void
    {
        error_log(sprintf(
            '[HTTP %d] %s',
            $exception->getStatusCode(),
            $exception->getMessage()
        ));

        $errorController = new ErrorController();
        if ($exception->getStatusCode() === 404) {
            $errorController->notFoundAction($exception->getMessage());

            return;
        }

        $errorController->serverErrorAction($exception->getMessage(), $exception);
    }

    private function handleThrowable(Throwable $exception): void
    {
        error_log('[UNCAUGHT] ' . $exception->getMessage() . PHP_EOL . $exception->getTraceAsString());

        $errorController = new ErrorController();
        $errorController->serverErrorAction('Ocurrio un error interno en la aplicacion.', $exception);
    }
}

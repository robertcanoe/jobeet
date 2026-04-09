<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\BaseController;
use Throwable;

class ErrorController extends BaseController
{
    public function notFoundAction(string $message = 'El recurso solicitado no existe.'): void
    {
        http_response_code(404);

        $this->renderHTML('errors/404', [
            'title' => '404 - No encontrado',
            'message' => $message,
        ]);
    }

    public function serverErrorAction(
        string $message = 'Ha ocurrido un error interno. Intenta de nuevo mas tarde.',
        ?Throwable $exception = null
    ): void {
        if (defined('APP_ENV') && APP_ENV === 'dev' && $exception instanceof Throwable) {
            $message .= ' Detalle tecnico: ' . $exception->getMessage();
        }

        http_response_code(500);

        $this->renderHTML('errors/500', [
            'title' => '500 - Error interno',
            'message' => $message,
        ]);
    }
}

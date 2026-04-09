<?php

declare(strict_types=1);

namespace App\Core;

abstract class BaseController
{
    protected function renderHTML(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewPath = VIEW_PATH . '/' . $view . '.php';
        if (!is_file($viewPath)) {
            throw new HttpException('Vista no encontrada: ' . $view, 500);
        }

        $layoutPath = VIEW_PATH . '/layouts/' . $layout . '.php';
        if (!is_file($layoutPath)) {
            throw new HttpException('Layout no encontrado: ' . $layout, 500);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewPath;
        $content = (string) ob_get_clean();

        require $layoutPath;
    }

    protected function redirect(string $url, array $queryParams = []): never
    {
        $targetUrl = $url;

        if ($queryParams !== []) {
            $targetUrl .= (str_contains($targetUrl, '?') ? '&' : '?') . http_build_query($queryParams);
        }

        header('Location: ' . $targetUrl);
        exit;
    }

    protected function requirePostMethod(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($method !== 'POST') {
            throw new HttpException('Metodo HTTP no permitido para esta operacion.', 405);
        }
    }

    protected function ensureValidCsrfToken(): void
    {
        $token = $_POST['_csrf_token'] ?? null;
        if (!\is_valid_csrf(is_string($token) ? $token : null)) {
            throw new HttpException('Token CSRF invalido o ausente.', 403);
        }
    }

    protected function mostrarError(string $message, int $code): void
    {
        http_response_code($code);
        $view = $code === 404 ? 'errors/404' : 'errors/500';
        $this->renderHTML($view, [
            'title' => 'Error ' . $code,
            'message' => $message,
            'code' => $code,
        ]);
    }
}

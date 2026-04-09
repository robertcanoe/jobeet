<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    /** @var array<string, array<int, array<string, mixed>>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void
    {
        $placeholders = [];
        $pattern = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/',
            static function (array $matches) use (&$placeholders): string {
                $placeholders[] = $matches[1];

                return '(?P<' . $matches[1] . '>[^/]+)';
            },
            $path
        );

        $normalizedPath = $this->normalizePath($path);
        $regex = $normalizedPath === '/'
            ? '#^/$#'
            : '#^' . rtrim((string) $pattern, '/') . '/?$#';

        $this->routes[$method][] = [
            'path' => $normalizedPath,
            'handler' => $handler,
            'regex' => $regex,
            'placeholders' => $placeholders,
        ];
    }

    public function match(string $method, string $uri): ?array
    {
        $method = strtoupper($method);
        $cleanPath = $this->sanitizeUri($uri);

        if (!isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $route) {
            if (!preg_match((string) $route['regex'], $cleanPath, $matches)) {
                continue;
            }

            $params = [];
            foreach ($route['placeholders'] as $placeholder) {
                $params[$placeholder] = $matches[$placeholder] ?? null;
            }

            return [
                'controller' => $route['handler'][0] ?? '',
                'action' => $route['handler'][1] ?? '',
                'params' => $params,
                'method' => $method,
                'uri' => $cleanPath,
            ];
        }

        return null;
    }

    private function sanitizeUri(string $uri): string
    {
        $path = (string) (parse_url($uri, PHP_URL_PATH) ?: '/');
        $basePath = $this->resolveBasePath();

        if ($basePath !== '' && $basePath !== '/' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
            if ($path === '' || $path === false) {
                $path = '/';
            }
        }

        // When an installation still exposes /public in the URL, normalize it as a fallback.
        if (str_starts_with($path, '/public/')) {
            $path = substr($path, strlen('/public'));
        } elseif ($path === '/public') {
            $path = '/';
        }

        return $this->normalizePath((string) $path);
    }

    private function resolveBasePath(): string
    {
        $baseUrl = (string) \env('BASE_URL', '');
        if ($baseUrl === '') {
            return '';
        }

        $parsedPath = parse_url($baseUrl, PHP_URL_PATH);
        if (!is_string($parsedPath) || $parsedPath === '' || $parsedPath === '/') {
            return '';
        }

        return rtrim($parsedPath, '/');
    }

    private function normalizePath(string $path): string
    {
        $cleanPath = '/' . trim($path, '/');

        return $cleanPath === '//' || $cleanPath === '' ? '/' : $cleanPath;
    }
}

<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require_once __DIR__ . '/config/paths.php';

$autoloadPath = ROOT_PATH . '/vendor/autoload.php';
if (!is_file($autoloadPath)) {
    throw new RuntimeException('No se encontro vendor/autoload.php. Ejecuta composer install.');
}

require_once $autoloadPath;

$globalHelpersPath = APP_PATH . '/helpers/global.php';
if (is_file($globalHelpersPath)) {
    require_once $globalHelpersPath;
}

$viewHelpersPath = VIEW_PATH . '/helpers';
if (is_dir($viewHelpersPath)) {
    foreach (glob($viewHelpersPath . '/*.php') ?: [] as $viewHelperFile) {
        require_once $viewHelperFile;
    }
}

foreach ([LOG_PATH, CACHE_PATH, UPLOAD_PATH] as $requiredDirectory) {
    if (!is_dir($requiredDirectory) && !mkdir($requiredDirectory, 0775, true) && !is_dir($requiredDirectory)) {
        throw new RuntimeException('No fue posible crear el directorio requerido: ' . $requiredDirectory);
    }
}

if (is_file(ROOT_PATH . '/.env')) {
    Dotenv::createImmutable(ROOT_PATH)->safeLoad();
} elseif (is_file(ROOT_PATH . '/.env.example')) {
    Dotenv::createImmutable(ROOT_PATH, '.env.example')->safeLoad();
}

$requiredEnvVars = [
    'APP_ENV',
    'APP_TIMEZONE',
    'DB_HOST',
    'DB_PORT',
    'DB_NAME',
    'DB_USER',
    'DB_CHARSET',
];

$missingEnvVars = [];
foreach ($requiredEnvVars as $requiredEnvVar) {
    $value = env($requiredEnvVar, null);
    if ($value === null || $value === '') {
        $missingEnvVars[] = $requiredEnvVar;
    }
}

if ($missingEnvVars !== []) {
    throw new RuntimeException(
        'Variables de entorno faltantes: ' . implode(', ', $missingEnvVars)
    );
}

if (!defined('APP_ENV')) {
    define('APP_ENV', (string) env('APP_ENV', 'production'));
}

if (!defined('BASE_URL')) {
    $baseUrl = (string) env('BASE_URL', '');
    if ($baseUrl === '') {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        if ($scriptDir === '.' || $scriptDir === '/') {
            $scriptDir = '';
        }

        if ($scriptDir !== '' && str_ends_with($scriptDir, '/public')) {
            $scriptDir = substr($scriptDir, 0, -7);
        }

        $baseUrl = $scheme . '://' . $host . $scriptDir;
    }

    define('BASE_URL', rtrim($baseUrl, '/'));
}

date_default_timezone_set((string) env('APP_TIMEZONE', 'UTC'));

ini_set('log_errors', '1');
ini_set('error_log', LOG_PATH . '/app.log');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (APP_ENV === 'dev') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    if (class_exists(Run::class)) {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors', '0');
}

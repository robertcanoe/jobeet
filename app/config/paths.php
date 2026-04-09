<?php

declare(strict_types=1);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}

if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', ROOT_PATH . '/public');
}

if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', ROOT_PATH . '/views');
}

if (!defined('LOG_PATH')) {
    define('LOG_PATH', ROOT_PATH . '/logs');
}

if (!defined('CACHE_PATH')) {
    define('CACHE_PATH', ROOT_PATH . '/cache');
}

if (!defined('UPLOAD_PATH')) {
    define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
}

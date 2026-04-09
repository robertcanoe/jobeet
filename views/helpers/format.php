<?php

declare(strict_types=1);

if (!function_exists('format_date')) {
    function format_date(?string $date, string $format = 'Y-m-d'): string
    {
        if ($date === null || trim($date) === '') {
            return '';
        }

        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return '';
        }

        return date($format, $timestamp);
    }
}

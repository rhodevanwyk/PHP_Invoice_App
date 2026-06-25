<?php
declare(strict_types=1);

function url(string $path = ''): string
{
    $base = defined('BASE_PATH') ? BASE_PATH : '';

    if ($path === '' || $path === '/') {
        return $base ?: '/';
    }

    if ($path[0] !== '/') {
        $path = '/' . $path;
    }

    return $base . $path;
}

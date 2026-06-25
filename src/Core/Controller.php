<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    protected function view(string $template, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../../templates/' . $template . '.php';
    }

    protected function redirect(string $url): void
    {
        if ($url !== '' && $url[0] === '/') {
            $url = url($url);
        }
        header('Location: ' . $url);
        exit;
    }
}
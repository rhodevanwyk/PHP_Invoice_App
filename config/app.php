<?php
return [
    'app_env' => $_ENV['APP_ENV'] ?? 'development',
    'app_url' => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'db' => [
        'dsn' => $_ENV['DB_DSN'] ?? 'mysql:host=127.0.0.1;dbname=invoice_app_db;charset=utf8mb4',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASS'] ?? '',
    ],
    'stripe' => [
        'secret_key' => $_ENV['STRIPE_SECRET_KEY'] ?? '',
        'webhook_secret' => $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '',
    ],
    'mail' => [
        'host' => $_ENV['MAIL_HOST'] ?? '',
        'port' => $_ENV['MAIL_PORT'] ?? 587,
        'username' => $_ENV['MAIL_USERNAME'] ?? '',
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
    ],
];
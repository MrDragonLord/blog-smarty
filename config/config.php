<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'blog_smarty',
        'user' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: 'root',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'base_url' => rtrim(getenv('APP_BASE_URL') ?: '', '/'),
        'per_page' => 6,
    ],
];

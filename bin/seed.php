<?php

declare(strict_types=1);

use App\Database;
use App\Seeder;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';
$pdo = Database::make($config['db']);
$seeder = new Seeder($pdo);
$seeder->run();

echo "Seeding finished successfully." . PHP_EOL;

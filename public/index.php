<?php

declare(strict_types=1);

use App\Controllers\ArticleController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;

$container = require __DIR__ . '/../src/bootstrap.php';

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'home':
        $controller = new HomeController(
            $container['view'],
            $container['categoryRepository'],
            $container['articleRepository']
        );
        $controller->index();
        break;
    case 'category':
        $controller = new CategoryController(
            $container['view'],
            $container['categoryRepository'],
            $container['articleRepository'],
            (int) $container['config']['app']['per_page']
        );
        $controller->show(
            (int) ($_GET['id'] ?? 0),
            (string) ($_GET['sort'] ?? 'date'),
            (int) ($_GET['page'] ?? 1)
        );
        break;
    case 'article':
        $controller = new ArticleController(
            $container['view'],
            $container['articleRepository']
        );
        $controller->show((int) ($_GET['id'] ?? 0));
        break;
    default:
        http_response_code(404);
        echo 'Страница не найдена';
}

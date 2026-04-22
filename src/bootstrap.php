<?php

declare(strict_types=1);

use App\Database;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Support\View;
use Smarty\Smarty;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

$pdo = Database::make($config['db']);
$smarty = new Smarty();
$projectRoot = dirname(__DIR__);
$runtimeCacheRoot = $projectRoot . '/var/cache';

if (!is_dir($runtimeCacheRoot) || !is_writable($runtimeCacheRoot)) {
    $runtimeCacheRoot = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . '/blog-smarty-cache';
}

$compileDir = $runtimeCacheRoot . '/compile';
$templateCacheDir = $runtimeCacheRoot . '/templates';
$configCacheDir = $runtimeCacheRoot . '/config';

foreach ([$compileDir, $templateCacheDir, $configCacheDir] as $cacheDir) {
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0775, true);
    }
}

$smarty->setTemplateDir($projectRoot . '/templates');
$smarty->setCompileDir($compileDir);
$smarty->setCacheDir($templateCacheDir);
$smarty->setConfigDir($configCacheDir);
$smarty->setCaching(Smarty::CACHING_OFF);

$view = new View($smarty);
$categoryRepository = new CategoryRepository($pdo);
$articleRepository = new ArticleRepository($pdo);

return [
    'config' => $config,
    'view' => $view,
    'categoryRepository' => $categoryRepository,
    'articleRepository' => $articleRepository,
];

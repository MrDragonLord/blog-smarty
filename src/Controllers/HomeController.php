<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Support\View;

final class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository
    ) {
    }

    public function index(): void
    {
        $categories = $this->categoryRepository->withArticleStats();

        foreach ($categories as &$category) {
            $category['articles'] = $this->articleRepository->latestByCategory((int) $category['id'], 3);
        }
        unset($category);

        $this->view->render('home.tpl', [
            'title' => 'Главная',
            'categories' => $categories,
        ]);
    }
}

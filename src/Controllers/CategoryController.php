<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Support\View;

final class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository,
        private readonly int $perPage
    ) {
    }

    public function show(int $id, string $sort = 'date', int $page = 1): void
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            http_response_code(404);
            echo 'Категория не найдена';
            return;
        }

        $allowedSort = ['date', 'views'];
        $sort = in_array($sort, $allowedSort, true) ? $sort : 'date';
        $page = max(1, $page);

        $articles = $this->articleRepository->byCategoryPaginated($id, $sort, $page, $this->perPage);
        $total = $this->articleRepository->countByCategory($id);
        $totalPages = (int) ceil($total / $this->perPage);

        $this->view->render('category.tpl', [
            'title' => $category['name'],
            'category' => $category,
            'articles' => $articles,
            'sort' => $sort,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}

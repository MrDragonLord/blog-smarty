<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ArticleRepository;
use App\Support\View;

final class ArticleController
{
    public function __construct(
        private readonly View $view,
        private readonly ArticleRepository $articleRepository
    ) {
    }

    public function show(int $id): void
    {
        $article = $this->articleRepository->find($id);

        if (!$article) {
            http_response_code(404);
            echo 'Статья не найдена';
            return;
        }

        $this->articleRepository->incrementViews($id);
        $article['views'] = (int) $article['views'] + 1;

        $similarArticles = $this->articleRepository->similar($id, 3);

        $this->view->render('article.tpl', [
            'title' => $article['title'],
            'article' => $article,
            'similarArticles' => $similarArticles,
        ]);
    }
}

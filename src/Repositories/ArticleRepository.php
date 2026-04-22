<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ArticleRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function latestByCategory(int $categoryId, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                SELECT
                    articles.id,
                    articles.title,
                    articles.description,
                    articles.image,
                    articles.published_at,
                    articles.views
                FROM articles
                INNER JOIN article_category
                    ON article_category.article_id = articles.id
                WHERE article_category.category_id = :category_id
                ORDER BY articles.published_at DESC
                LIMIT :articles_limit
            SQL
        );

        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':articles_limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function byCategoryPaginated(int $categoryId, string $sort, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $orderBy = $sort === 'views' ? 'articles.views DESC' : 'articles.published_at DESC';

        $stmt = $this->pdo->prepare(
            <<<SQL
                SELECT
                    articles.id,
                    articles.title,
                    articles.description,
                    articles.image,
                    articles.published_at,
                    articles.views
                FROM articles
                INNER JOIN article_category
                    ON article_category.article_id = articles.id
                WHERE article_category.category_id = :category_id
                ORDER BY {$orderBy}
                LIMIT :limit
                OFFSET :offset
            SQL
        );

        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                SELECT
                    COUNT(*) AS total_articles
                FROM article_category
                WHERE article_category.category_id = :category_id
            SQL
        );
        $stmt->execute(['category_id' => $categoryId]);

        return (int) $stmt->fetchColumn();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                SELECT
                    articles.id,
                    articles.title,
                    articles.description,
                    articles.content,
                    articles.image,
                    articles.views,
                    articles.published_at
                FROM articles
                WHERE articles.id = :id
                LIMIT 1
            SQL
        );
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch();

        if (!$article) {
            return null;
        }

        $article['categories'] = $this->categoriesForArticle((int) $article['id']);

        return $article;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                UPDATE articles
                SET articles.views = articles.views + 1
                WHERE articles.id = :id
            SQL
        );
        $stmt->execute(['id' => $id]);
    }

    public function similar(int $articleId, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                SELECT DISTINCT
                    articles.id,
                    articles.title,
                    articles.description,
                    articles.image,
                    articles.published_at,
                    articles.views
                FROM articles
                INNER JOIN article_category
                    ON article_category.article_id = articles.id
                WHERE articles.id <> :article_id
                    AND article_category.category_id IN (
                        SELECT
                            article_category.category_id
                        FROM article_category
                        WHERE article_category.article_id = :article_id
                    )
                ORDER BY articles.published_at DESC
                LIMIT :articles_limit
            SQL
        );
        $stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->bindValue(':articles_limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function categoriesForArticle(int $articleId): array
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                SELECT
                    categories.id,
                    categories.name
                FROM categories
                INNER JOIN article_category
                    ON article_category.category_id = categories.id
                WHERE article_category.article_id = :article_id
                ORDER BY categories.name ASC
            SQL
        );
        $stmt->execute(['article_id' => $articleId]);

        return $stmt->fetchAll();
    }
}

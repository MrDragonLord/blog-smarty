<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function withArticleStats(): array
    {
        $sql = <<<SQL
            SELECT
                categories.id,
                categories.name,
                categories.description,
                COUNT(article_category.article_id) AS articles_count
            FROM categories
            INNER JOIN article_category
                ON article_category.category_id = categories.id
            GROUP BY
                categories.id,
                categories.name,
                categories.description
            ORDER BY categories.name ASC
        SQL;

        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            <<<SQL
                SELECT
                    categories.id,
                    categories.name,
                    categories.description
                FROM categories
                WHERE categories.id = :id
                LIMIT 1
            SQL
        );
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();

        return $category ?: null;
    }
}

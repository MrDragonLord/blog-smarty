<?php

declare(strict_types=1);

namespace App;

use PDO;

final class Seeder
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function run(): void
    {
        $this->pdo->beginTransaction();

        $this->pdo->exec('DELETE FROM article_category');
        $this->pdo->exec('DELETE FROM articles');
        $this->pdo->exec('DELETE FROM categories');

        $categoryIds = $this->seedCategories();
        $articleIds = $this->seedArticles();
        $this->seedRelations($categoryIds, $articleIds);

        $this->pdo->commit();
    }

    private function seedCategories(): array
    {
        $categories = [
            ['name' => 'Category 1', 'description' => 'Описание категории 1'],
            ['name' => 'Category 2', 'description' => 'Описание категории 2'],
            ['name' => 'Category 3', 'description' => 'Описание категории 3'],
            ['name' => 'Category 4', 'description' => 'Описание категории 4'],
        ];

        $stmt = $this->pdo->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
        $ids = [];
        foreach ($categories as $category) {
            $stmt->execute($category);
            $ids[] = (int) $this->pdo->lastInsertId();
        }

        return $ids;
    }

    private function seedArticles(): array
    {
        $images = [
            '/assets/img/img1.jpg',
            '/assets/img/img2.jpg',
            '/assets/img/img3.jpg',
        ];

        $stmt = $this->pdo->prepare(
            'INSERT INTO articles (image, title, description, content, views, published_at)
             VALUES (:image, :title, :description, :content, :views, :published_at)'
        );

        $ids = [];
        for ($i = 1; $i <= 16; $i++) {
            $stmt->execute([
                'image' => $images[$i % count($images)],
                'title' => 'Lorem ipsum dolor sit amet #' . $i,
                'description' => 'Краткое описание статьи #' . $i,
                'content' => "Полный текст статьи #{$i}.\n\nLorem ipsum dolor sit amet, consectetur adipisicing elit.",
                'views' => random_int(20, 950),
                'published_at' => date('Y-m-d H:i:s', strtotime("-{$i} days")),
            ]);
            $ids[] = (int) $this->pdo->lastInsertId();
        }

        return $ids;
    }

    private function seedRelations(array $categoryIds, array $articleIds): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO article_category (article_id, category_id) VALUES (:article_id, :category_id)');

        foreach ($articleIds as $index => $articleId) {
            $mainCategory = $categoryIds[$index % count($categoryIds)];
            $secondaryCategory = $categoryIds[($index + 1) % count($categoryIds)];

            $stmt->execute(['article_id' => $articleId, 'category_id' => $mainCategory]);

            if ($index % 2 === 0) {
                $stmt->execute(['article_id' => $articleId, 'category_id' => $secondaryCategory]);
            }
        }
    }
}

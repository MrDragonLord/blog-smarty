CREATE DATABASE IF NOT EXISTS blog_smarty CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog_smarty;

CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content MEDIUMTEXT NOT NULL,
    views INT UNSIGNED NOT NULL DEFAULT 0,
    published_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_articles_published_at (published_at),
    INDEX idx_articles_views (views)
);

CREATE TABLE IF NOT EXISTS article_category (
    article_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (article_id, category_id),
    CONSTRAINT fk_article_category_article FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    CONSTRAINT fk_article_category_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

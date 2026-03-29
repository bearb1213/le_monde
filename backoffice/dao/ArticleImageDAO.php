<?php
require_once __DIR__ . '/../utils/db.php';
require_once __DIR__ . '/../models/ArticleImage.php';

class ArticleImageDAO
{
    protected $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?: getPDO();
    }

    public function create($img)
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO article_images (article_id, chemin, alt) VALUES (:article_id, :chemin, :alt)');
            $stmt->execute([':article_id' => $img->article_id, ':chemin' => $img->chemin, ':alt' => $img->alt]);
            $img->id = (int)$this->pdo->lastInsertId();
            return $img;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to create image: ' . $e->getMessage());
        }
    }

    public function findAllByArticle($articleId)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM article_images WHERE article_id = :aid');
            $stmt->execute([':aid' => $articleId]);
            $rows = $stmt->fetchAll();
            $list = [];
            foreach ($rows as $r) {
                $list[] = ArticleImage::fromArray($r);
            }
            return $list;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to fetch images: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM article_images WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to delete image: ' . $e->getMessage());
        }
    }
}

<?php
require_once __DIR__ . '/../utils/db.php';
require_once __DIR__ . '/../models/ArticleDetail.php';

class ArticleDetailDAO
{
    protected $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?: getPDO();
    }

    public function create($detail)
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO article_details (article_id, details) VALUES (:article_id, :details)');
            $stmt->execute([':article_id' => $detail->article_id, ':details' => $detail->details]);
            $detail->id = (int)$this->pdo->lastInsertId();
            return $detail;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to create detail: ' . $e->getMessage());
        }
    }

    public function findAllByArticle($articleId)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM article_details WHERE article_id = :aid');
            $stmt->execute([':aid' => $articleId]);
            $rows = $stmt->fetchAll();
            $list = [];
            foreach ($rows as $r) {
                $list[] = ArticleDetail::fromArray($r);
            }
            return $list;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to fetch details: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM article_details WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to delete detail: ' . $e->getMessage());
        }
    }

    public function deleteByArticleId($articleId)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM article_details WHERE article_id = :article_id');
            $stmt->execute([':article_id' => $articleId]);
            return true;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to delete details by article ID: ' . $e->getMessage());
        }
    }
}

<?php
require_once __DIR__ . '/../utils/db.php';
require_once __DIR__ . '/../models/Article.php';

class ArticleDAO
{
    protected $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?: getPDO();
    }

    public function create($article)
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO articles (titre, html) VALUES (:titre, :html)');
            $stmt->execute([':titre' => $article->titre, ':html' => $article->html]);
            $article->id = (int)$this->pdo->lastInsertId();
            return $article;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to create article: ' . $e->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $stmt = $this->pdo->query('SELECT * FROM articles');
            $rows = $stmt->fetchAll();
            $list = [];
            foreach ($rows as $r) {
                $list[] = Article::fromArray($r);
            }
            return $list;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to fetch articles: ' . $e->getMessage());
        }
    }

    public function findAllWithPagination(int $limit = 20, int $offset = 0): array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT id, titre FROM articles ORDER BY id DESC LIMIT :limit OFFSET :offset');
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $list = [];
            foreach ($rows as $r) {
                $list[] = Article::fromArray($r);
            }
            return $list;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to fetch paginated articles: ' . $e->getMessage());
        }
    }

    public function countAll(): int
    {
        try {
            $stmt = $this->pdo->query('SELECT COUNT(id) FROM articles');
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to count articles: ' . $e->getMessage());
        }
    }

    public function findById($id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM articles WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            return $row ? Article::fromArray($row) : null;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to fetch article: ' . $e->getMessage());
        }
    }

    public function update($article)
    {
        try {
            $stmt = $this->pdo->prepare('UPDATE articles SET titre = :titre, html = :html WHERE id = :id');
            $stmt->execute([':titre' => $article->titre, ':html' => $article->html, ':id' => $article->id]);
            return true;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to update article: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM articles WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            throw new RuntimeException('Failed to delete article: ' . $e->getMessage());
        }
    }
}

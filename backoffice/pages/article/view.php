<?php
// backoffice/pages/article/view.php

require_once __DIR__ . '/../../dao/ArticleDAO.php';

$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($articleId === 0) {
    header("Location: list.php?error=" . urlencode("ID d'article invalide."));
    exit;
}

try {
    $dao = new ArticleDAO();
    $article = $dao->findById($articleId);
} catch (Exception $e) {
    // Idéalement, logguer l'erreur
    header("Location: list.php?error=" . urlencode("Erreur de base de données."));
    exit;
}

if (!$article) {
    header("Location: list.php?error=" . urlencode("Article non trouvé."));
    exit;
}

$pageTitle = 'Article: ' . htmlspecialchars($article->titre);

// Utilisation du layout global
ob_start();
?>

<style>
    .article-content {
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 2rem;
        margin-top: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .article-content h1, .article-content h2, .article-content h3 {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 0.5rem;
        margin-top: 1.5rem;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
    }
</style>

<a href="list.php">&larr; Retour à la liste</a>

<div class="article-meta" style="margin-top: 1rem; color: #666;">
    <span>Publié le: <?= htmlspecialchars(date('d/m/Y H:i', strtotime($article->date_publication))) ?></span>
    <?php
    // Pour afficher le nom de l'auteur, il faudrait une jointure ou une requête supplémentaire
    // Ici, on affiche juste l'ID de l'auteur pour l'instant.
    if ($article->auteur) {
        echo ' | <span>Auteur ID: ' . htmlspecialchars($article->auteur) . '</span>';
    }
    ?>
</div>

<div class="article-content">
    <?php
    // On affiche directement le HTML stocké en base.
    // ATTENTION : Ceci suppose que le HTML stocké est sûr.
    // Si le HTML peut être entré par des utilisateurs non fiables,
    // il faudrait le nettoyer avec une librairie comme HTML Purifier.
    echo $article->html;
    ?>
</div>



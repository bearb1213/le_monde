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

if (!defined('LAYOUT_INCLUDED')) {
    $pageTitle = 'Article: ' . htmlspecialchars($article->titre);
    $contentFile = __FILE__;
    require __DIR__ . '/../layout.php';
    exit;
}

?>

<style>
    .article-content {
        background-color: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 2.5rem;
        margin-top: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }
    .article-content h1, .article-content h2, .article-content h3 {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.5rem;
        margin-top: 1.5rem;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin: 15px 0;
    }
</style>

<div style="margin-bottom: 15px;">
    <a href="list.php" class="btn" style="background:#e0e0e0; color:#333; padding: 6px 12px; font-size: 0.85rem; border-radius: 4px;">&larr; Retour à la liste</a>
    <a href="edit.php?id=<?= $article->id ?>" class="btn" style="margin-left: 10px; padding: 6px 12px; font-size: 0.85rem;">Modifier</a>
</div>

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



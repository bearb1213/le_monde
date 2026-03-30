<?php
// backoffice/pages/article/list.php
session_start();

require_once __DIR__ . '/../../dao/ArticleDAO.php';

$pageTitle = 'Liste des Articles';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

$dao = new ArticleDAO();
try {
    $articles = $dao->findAllWithPagination($limit, $offset);
    $totalArticles = $dao->countAll();
    $totalPages = ceil($totalArticles / $limit);
} catch (Exception $e) {
    $articles = [];
    $error = 'Erreur lors de la récupération des articles: ' . $e->getMessage();
}

// Récupérer les messages de session
$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? $error ?? null;

// Nettoyer les messages de session
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);


// Pour l'intégration dans le layout
ob_start();
?>

<style>
    .article-list { list-style: none; padding: 0; }
    .article-list li { background: #fff; border: 1px solid #ddd; padding: 10px 15px; margin-bottom: 8px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; }
    .article-list a.title { font-weight: bold; text-decoration: none; color: #333; }
    .article-list .actions a { margin-left: 10px; text-decoration: none; }
    .pagination { margin-top: 20px; }
    .pagination a { padding: 8px 12px; border: 1px solid #ddd; margin: 0 2px; text-decoration: none; color: #337ab7; }
    .pagination a.active { background-color: #337ab7; color: white; border-color: #337ab7; }
    .pagination a.disabled { color: #777; pointer-events: none; }
    .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>

<h1>Liste des Articles</h1>

<?php if ($successMessage): ?>
    <div class="message success"><?= htmlspecialchars($successMessage) ?></div>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <div class="message error"><?= htmlspecialchars($errorMessage) ?></div>
<?php endif; ?>

<ul class="article-list">
    <?php if (empty($articles)): ?>
        <li>Aucun article trouvé.</li>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <li>
                <div>
                    <a href="view.php?id=<?= $article->id ?>" class="title"><?= htmlspecialchars($article->titre) ?></a>
                    <div style="font-size: 0.8em; color: #777;">
                        Publié le: <?= htmlspecialchars(date('d/m/Y', strtotime($article->date_publication))) ?>
                        | Auteur ID: <?= htmlspecialchars($article->auteur) ?>
                    </div>
                </div>
                <div class="actions">
                    <a href="edit.php?id=<?= $article->id ?>">Modifier</a>
                    <a href="delete.php?id=<?= $article->id ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <a href="?page=1" class="<?= $page <= 1 ? 'disabled' : '' ?>">&laquo;</a>
    <a href="?page=<?= $page - 1 ?>" class="<?= $page <= 1 ? 'disabled' : '' ?>">&lsaquo;</a>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" class="<?= $page == $i ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <a href="?page=<?= $page + 1 ?>" class="<?= $page >= $totalPages ? 'disabled' : '' ?>">&rsaquo;</a>
    <a href="?page=<?= $totalPages ?>" class="<?= $page >= $totalPages ? 'disabled' : '' ?>">&raquo;</a>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();

echo $content;
?>

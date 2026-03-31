<?php


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

if (!defined('LAYOUT_INCLUDED')) {
    $pageTitle = 'Liste des Articles';
    $contentFile = __FILE__;
    require __DIR__ . '/../layout.php';
    exit;
}

?>

<style>
    .article-list { list-style: none; padding: 0; }
    .article-list li { background: var(--bg-surface); border: 1px solid var(--border-color); padding: 15px; margin-bottom: 10px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
    .article-list a.title { font-weight: 600; text-decoration: none; color: var(--brand-black); font-size: 1.1rem; display: block; margin-bottom: 5px; }
    .article-list a.title:hover { color: var(--brand-yellow-hover); }
    .article-list .actions a { margin-left: 10px; text-decoration: none; padding: 6px 12px; background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 4px; font-size: 0.85rem; color: var(--text-secondary); font-weight: 500; }
    .article-list .actions a:hover { background: var(--brand-yellow); color: var(--brand-black); border-color: var(--brand-yellow); }
    .pagination { margin-top: 30px; display: flex; gap: 5px; }
    .pagination a { padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px; text-decoration: none; color: var(--brand-black); background: var(--bg-surface); }
    .pagination a:hover { background: var(--bg-main); }
    .pagination a.active { background-color: var(--brand-yellow); color: var(--brand-black); border-color: var(--brand-yellow); font-weight: bold; }
    .pagination a.disabled { color: var(--text-secondary); opacity: 0.5; pointer-events: none; }
    .message { padding: 12px 15px; margin-bottom: 20px; border-radius: 4px; font-weight: 500; }
    .message.success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .message.error { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .btn-create { margin-bottom: 20px; display: inline-block; }
</style>

<div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <a href="insert.php" class="btn btn-create">+ Créer un article</a>
</div>

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

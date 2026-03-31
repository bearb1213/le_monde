<?php
require_once __DIR__ . '/backoffice/dao/ArticleDAO.php';
require_once __DIR__ . '/backoffice/dao/ArticleImageDAO.php';
$articles = [];
try {
    $pdo = getPDO();
    $articleDao = new ArticleDAO($pdo);
    $articleImageDao = new ArticleImageDAO($pdo);
    $articles = $articleDao->findAllWithPagination(10, 0);
    $articles = array_map(function($a) use ($articleImageDao) {
        $a->images = $articleImageDao->findAllByArticle($a->id);
        return $a;
    }, $articles);
} catch (Exception $e) {
    throw new RuntimeException('Erreur de connexion à la base de données : ' . $e->getMessage(), 0, $e);
    // die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guerre en Iran : les dernières nouvelles et analyses sur le conflit en cours, les développements politiques et les implications régionales.</title>
    <meta name="description" content="Guerre en Iran : les dernières nouvelles et analyses sur le conflit en cours, les développements politiques et les implications régionales.">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">

</head>
<body>
    <?php include __DIR__ . '/component/header.php'; ?>

    <div class="page-header">
        <h1>La Guerre en Iran</h1>
        <p>Les derniers rapports en vogue</p>
    </div>

    <main class="articles-grid">
        <?php foreach ($articles as $i => $a): ?>
            <a href="/article/<?= preg_replace('/\s+/', '-', $a->titre) ?>-<?= $a->id ?>.html">
            <article class="article-card card-size-<?= ($i % 5) + 1 ?>">
                <div class="card-image">
                    <?php if (isset($a->images) && !empty($a->images)): ?>
                        <?= $a->images[0]->miniature(400, 300) ?>
                    <?php else: ?>
                        <div class="placeholder-image"></div>
                    <?php endif; ?>
                    <div class="card-overlay"></div>
                </div>
                <div class="card-content">
                    <h2><a href="/article/<?= preg_replace('/\s+/', '-', $a->titre) ?>-<?= $a->id ?>.html"><?= htmlspecialchars($a->titre) ?></a></h2>
                </div>
            </article>
            </a>
        <?php endforeach; ?>
    </main>

    <?php include __DIR__ . '/component/footer.php'; ?>
</body>
</html>
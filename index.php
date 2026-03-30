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
    <link rel="stylesheet" href="/css/article.css">
    <style>
        #login-link {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #007BFF;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <a href="backoffice/pages/login/index.php" id="login-link" >Se connecter</a>
    <h1>La Guerre en Iran les derniers reports en vogue</h1>
    <main>
        <?php foreach ($articles as $a): ?>
            <article>
                <h2><a href="/article/<?= preg_replace('/\s+/', '-', $a->titre) ?>-<?= $a->id ?>.html"><?= htmlspecialchars($a->titre) ?></a></h2>
                <?php if (isset($a->images) && !empty($a->images)): ?>
                    <?= $a->images[0]->miniature(200, 200) ?>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
</body>
</html>
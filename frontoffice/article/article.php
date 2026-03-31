<?php
// require_once __DIR__ . '/../utils/db.php';
require_once __DIR__ . '/../../backoffice/dao/ArticleDAO.php';
require_once __DIR__ . '/../../backoffice/dao/ArticleDetailDAO.php';
require_once __DIR__ . '/../../backoffice/dao/ArticleImageDAO.php';
$article = [];
$details = [];
try {
    $pdo = getPDO();
    $articleDao = new ArticleDAO($pdo);
    $articleDetailDao = new ArticleDetailDAO($pdo);
    $articleImageDao = new ArticleImageDAO($pdo);
    
    // article principal
    $article = $articleDao->findById($_GET['id'] ?? 0);
    //changement du url
    $article->url = preg_replace('/\s+/', '-', $article->titre);
    $article->url = "/article/" . $article->url . "-" . $article->id . ".html";
    // article details (les articles lies)
    $detail_sql = $articleDetailDao->findAllByArticle($_GET['id'] ?? 0);
    if( empty($detail_sql) ) {
        $details = [];
    } else {
        $detail_ids = array_map(function($d)  {
            return $d->details;
        } , $detail_sql);
        
        $details = $articleDao->findByIds($detail_ids);
        
        $details = array_map (function($d) use ($articleImageDao){
            $d->url = preg_replace('/\s+/', '-', $d->titre);
            $d->url = "/article/" . $d->url . "-" . $d->id . ".html";
            $d->images = $articleImageDao->findAllByArticle($d->id);
            return $d;
        } , $details);
    }
    
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
    <meta name="description" content="<?= $article->titre ?? '' ?>">
    <link rel="stylesheet" href="/css/article.css">
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">

    <title><?= $article->titre ?? '' ?></title>
</head>
<body>
    <?php include __DIR__ . '/../../component/header.php'; ?>

    <main class="article-main">
        <article class="article-content">
            <div class="article-body">
                <?= $article->html ?? '' ?>
            </div>
        </article>

        <section class="related-articles">
            <div class="section-header">
                <h2>Articles Liés</h2>
                <div class="header-line"></div>
            </div>
            <?php if (empty($details)): ?>
                <p class="no-articles">Aucun article lié trouvé.</p>
            <?php else: ?>
                <div class="related-grid">
                    <?php foreach ($details as $d): ?>
                        <a href="<?= $d->url ?>" class="related-card">
                            <div class="related-image">
                                <?php if (isset($d->images) && !empty($d->images)) {
                                    echo $d->images[0]->miniature(250, 200);
                                } else { ?>
                                    <div class="placeholder"></div>
                                <?php } ?>
                                <div class="card-badge">Lié</div>
                            </div>
                            <div class="related-title">
                                <?= htmlspecialchars($d->titre) ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <script>
        const bonneUrl = "<?= $article->url ?>";
        if (window.location.pathname !== bonneUrl) {
            history.replaceState({}, "", bonneUrl);
        }
    </script>

    <?php include __DIR__ . '/../../component/footer.php'; ?>
</body>
</html>
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
    <title><?= $article->titre ?? '' ?></title>
</head>
<body>
    <main>  
        <section>
            <div>
                <?= $article->html ?? '' ?>
            </div>
        </section>
        <section>
            <?php if (empty($details)): ?>
                <p>Aucun article lié trouvé.</p>
            <?php else: ?>
            <h2>Articles liés</h2>
            <ul>
                <?php foreach ($details as $d): ?>

                    <a href="<?= $d->url ?>">
                        <li >
                            <?php if (isset($d->images) && !empty($d->images)) {
                                echo $d->images[0]->miniature(100, 100);
                            } ?>
                                <?= htmlspecialchars($d->titre) ?>
                        </li>
                    </a>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>
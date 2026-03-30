<?php
// require_once __DIR__ . '/../utils/db.php';
require_once __DIR__ . '/../../backoffice/dao/ArticleDAO.php';
require_once __DIR__ . '/../../backoffice/dao/ArticleDetailDAO.php';
$article = [];
$details = [];
try {
    $pdo = getPDO();
    // article principal
    $articleDao = new ArticleDAO($pdo);
    $article = $articleDao->findById($_GET['id'] ?? 0);
    // article details (les articles lies)
    $articleDetailDao = new ArticleDetailDAO($pdo);
    $detail_sql = $articleDetailDao->findAllByArticle($_GET['id'] ?? 0);
    if( empty($detail_sql) ) {
        $details = [];
    } else {
        $detail_ids = array_map(function($d) {
            return $d->details;
        } , $detail_sql);
        
        $details = $articleDao->findByIds($detail_ids);
        
        $details = array_map (function($d) {
            $d->url = str_replace(' ', '_', $d->titre);
            $d->url = "/article/" . $d->url . "-" . $d->id . ".html";
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
    <title><?= $article->titre ?? '' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        main {
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        section {
            margin-bottom: 30px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            color: #000000;
            
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
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

                    <li ><a href="<?= $d->url ?>"><?= htmlspecialchars($d->titre) ?></a></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>
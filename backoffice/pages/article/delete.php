<?php
// backoffice/pages/article/delete.php
require_once __DIR__ . '/../../dao/ArticleDAO.php';
require_once __DIR__ . '/../../dao/ArticleImageDAO.php';
require_once __DIR__ . '/../../dao/ArticleDetailDAO.php';
require_once __DIR__ . '/../../utils/db.php'; // Pour la gestion de transaction

session_start();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "ID d'article non spécifié.";
    header('Location: list.php');
    exit;
}

$articleId = (int)$_GET['id'];
$pdo = getPDO();

try {
    $pdo->beginTransaction();

    $imageDAO = new ArticleImageDAO($pdo);
    $articleDAO = new ArticleDAO($pdo);
    $detailDAO = new ArticleDetailDAO($pdo);

    // 1. Supprimer les détails de l'article
    $detailDAO->deleteByArticleId($articleId);

    // 2. Récupérer et supprimer les images associées
    $images = $imageDAO->findAllByArticle($articleId);
    foreach ($images as $image) {
        // Supprimer le fichier physique
        $filePath = __DIR__ . '/../../../../images/articles/' . $image->getChemin();
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    // Supprimer les références dans la base de données
    $imageDAO->deleteByArticleId($articleId);


    // 4. Supprimer l'article lui-même
    $success = $articleDAO->delete($articleId);

    if ($success) {
        $pdo->commit();
        $_SESSION['success_message'] = "L'article et ses données associées ont été supprimés avec succès.";
    } else {
        $pdo->rollBack();
        $_SESSION['error_message'] = "La suppression de l'article a échoué.";
    }
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error_message'] = 'Erreur lors de la suppression de l\'article : ' . $e->getMessage();
}

header('Location: list.php');
exit;
?>

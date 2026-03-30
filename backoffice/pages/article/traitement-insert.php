<?php
// backoffice/pages/article/traitement-insert.php
session_start();

require_once __DIR__ . '/../../dao/ArticleDAO.php';
require_once __DIR__ . '/../../dao/ArticleImageDAO.php';
require_once __DIR__ . '/../../dao/ArticleDetailDAO.php';
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/ArticleImage.php';
require_once __DIR__ . '/../../models/ArticleDetail.php';
require_once __DIR__ . '/../../utils/db.php'; // Pour getPDO

/**
 * Gère la redirection et termine le script.
 * @param string $location L'URL de redirection.
 */
function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

/**
 * Valide et prépare le répertoire d'upload.
 * @return array|null Retourne un tableau ['absolute' => ..., 'web' => ...] ou null si erreur.
 */
function getUploadDirectory(): ?array
{
    $targetWebPath = '/images/articles';
    $targetAbs = '/var/www/html' . $targetWebPath;

    if (!is_dir($targetAbs)) {
        if (!mkdir($targetAbs, 0775, true)) {
            return null; 
        }
    }
    
    if (!is_writable($targetAbs)) {
        return null; 
    }

    return ['absolute' => $targetAbs, 'web' => $targetWebPath];
}

/**
 * Traite l'upload des images.
 * @param int $articleId
 * @param array $files Tableau $_FILES['images']
 * @param array $imageDescs
 * @param ArticleImageDAO $imageDao
 * @param string $uploadDir Chemin absolu du dossier d'upload
 * @param string $webPath Chemin web du dossier d'upload
 * @return array Chemins des fichiers uploadés pour un éventuel nettoyage.
 * @throws Exception si un upload échoue.
 */
function processImageUploads(int $articleId, array $files, array $imageDescs, ArticleImageDAO $imageDao, string $uploadDir, string $webPath): array
{
    $uploadedFiles = [];
    if (empty($files) || !is_array($files['name'])) {
        return $uploadedFiles;
    }

    $count = count($files['name']);
    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $tmp = $files['tmp_name'][$i];
        if (@getimagesize($tmp) === false) {
            continue; // Pas une image valide
        }

        $origName = basename($files['name'][$i]);
        $ext = pathinfo($origName, PATHINFO_EXTENSION);
        $safeName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', pathinfo($origName, PATHINFO_FILENAME));
        $newName = $safeName . '_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . ($ext ?: 'jpg');
        $dest = rtrim($uploadDir, '/') . '/' . $newName;

        if (move_uploaded_file($tmp, $dest)) {
            $uploadedFiles[] = $dest; // Ajouter au suivi pour rollback
            $chemin = rtrim($webPath, '/') . '/' . $newName;
            $alt = isset($imageDescs[$i]) ? trim($imageDescs[$i]) : '';
            $img = new ArticleImage(null, $articleId, $chemin, $alt);
            $imageDao->create($img);
        } else {
            // Si le déplacement échoue, on lève une exception pour tout annuler
            throw new Exception("Echec du déplacement du fichier uploadé.");
        }
    }
    return $uploadedFiles;
}

/**
 * Crée l'article et ses détails (références).
 * @param array $postData Données du formulaire.
 * @param ArticleDAO $articleDao
 * @param ArticleDetailDAO $detailDao
 * @return Article L'objet Article créé.
 */
function createArticleWithDetails(array $postData, ArticleDAO $articleDao, ArticleDetailDAO $detailDao): Article
{
    $titre = $postData['titre'] ?? '';
    $html = $postData['html'] ?? '';
    $ref_articles = $postData['ref_article'] ?? [];

    $article = new Article(null, $titre, $html);
    $createdArticle = $articleDao->create($article);

    foreach ($ref_articles as $ref) {
        $rid = intval($ref);
        if ($rid > 0) {
            $detail = new ArticleDetail(null, $createdArticle->id, $rid);
            $detailDao->create($detail);
        }
    }
    return $createdArticle;
}


// --- Début du script principal ---

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('./insert.php');
}

$titre = isset($_POST['titre']) ? trim($_POST['titre']) : '';
if ($titre === '') {
    redirect('./insert.php?error=' . urlencode('Le titre est requis'));
}

$uploadDirInfo = getUploadDirectory();
if ($uploadDirInfo === null) {
    redirect('./insert.php?error=' . urlencode('Problème avec le répertoire des images.'));
}

$pdo = getPDO();
$uploadedFilesForCleanup = [];

try {
    $pdo->beginTransaction();

    $articleDao = new ArticleDAO($pdo);
    $imageDao = new ArticleImageDAO($pdo);
    $detailDao = new ArticleDetailDAO($pdo);

    // 1. Créer l'article et les références
    $article = createArticleWithDetails($_POST, $articleDao, $detailDao);

    // 2. Gérer les images
    $uploadedFilesForCleanup = processImageUploads(
        $article->id,
        $_FILES['images'] ?? [],
        $_POST['image_desc'] ?? [],
        $imageDao,
        $uploadDirInfo['absolute'],
        $uploadDirInfo['web']
    );

    $pdo->commit();
    redirect('./insert.php?success=' . urlencode('Article créé avec succès'));

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Nettoyer les fichiers déjà uploadés en cas d'erreur
    foreach ($uploadedFilesForCleanup as $filepath) {
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }
    
    // error_log($e->getMessage());
    redirect('./insert.php?error=' . urlencode('Une erreur est survenue: ' . $e->getMessage()));
}

<?php
// backoffice/traitement/article/traitement-edit.php
session_start();

require_once __DIR__ . '/../../dao/ArticleDAO.php';
require_once __DIR__ . '/../../dao/ArticleImageDAO.php';
require_once __DIR__ . '/../../dao/ArticleDetailDAO.php';
require_once __DIR__ . '/../../models/Article.php';
require_once __DIR__ . '/../../models/ArticleImage.php';
require_once __DIR__ . '/../../models/ArticleDetail.php';
require_once __DIR__ . '/../../utils/db.php'; // Pour getPDO

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

function getUploadDirectory(): ?array
{
    $targetWebPath = '/image';
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

function formatBaliseImg(string $html): string 
{
    $pattern = '/(<img\s+[^>]*src=["\'])(\.\.\/\.\.\/\.\.\/image\/)([^"\']*)(["\'][^>]*>)/i';
    $replacement = '$1/image/$3$4';
    $newHtml = preg_replace($pattern, $replacement, $html);
    return $newHtml;
}

function processImageUploads(int $articleId, array $files, array $imageDescs, $imageDao, string $uploadDir, string $webPath): array
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
        $newName = $safeName . '.' . ($ext ?: 'jpg');
        $dest = rtrim($uploadDir, '/') . '/' . $newName;

        if (move_uploaded_file($tmp, $dest)) {
            $uploadedFiles[] = $dest; // pour rollback si besoin
            $chemin = rtrim($webPath, '/') . '/' . $newName;
            $alt = isset($imageDescs[$i]) ? trim($imageDescs[$i]) : '';
            $img = new ArticleImage(null, $articleId, $chemin, $alt);
            $imageDao->create($img);
        } else {
            throw new Exception("Echec du déplacement du fichier uploadé.");
        }
    }
    return $uploadedFiles;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/backoffice/pages/article/list.php');
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    redirect('/backoffice/pages/article/list.php?error=' . urlencode('Identifiant invalide'));
}

$titre = isset($_POST['titre']) ? trim($_POST['titre']) : '';
if ($titre === '') {
    redirect('/backoffice/pages/article/edit.php?id=' . $id . '&error=' . urlencode('Le titre est requis'));
}

$uploadDirInfo = getUploadDirectory();
if ($uploadDirInfo === null) {
    redirect('/backoffice/pages/article/edit.php?id=' . $id . '&error=' . urlencode('Problème avec le répertoire des images.'));
}

$pdo = getPDO();
$uploadedFilesForCleanup = [];

try {
    $pdo->beginTransaction();

    $articleDao = new ArticleDAO($pdo);
    $imageDao = new ArticleImageDAO($pdo);
    $detailDao = new ArticleDetailDAO($pdo);

    // 1. Mettre à jour l'article
    $html = $_POST['html'] ?? '';
    $html = formatBaliseImg($html);
    $auteurId = $_SESSION['user_id'] ?? null; // Récupérer l'ID de l'utilisateur de la session

    if (!$auteurId) {
        throw new Exception("Utilisateur non authentifié.");
    }

    $article = new Article($id, $titre, $html, null, $auteurId);
    $articleDao->update($article);

    // 2. Mettre à jour les références: supprimer les anciennes et recréer
    $detailDao->deleteByArticleId($id);
    $ref_articles = $_POST['ref_article'] ?? [];
    foreach ($ref_articles as $ref) {
        $rid = intval($ref);
        if ($rid > 0) {
            $detail = new ArticleDetail(null, $id, $rid);
            $detailDao->create($detail);
        }
    }

    // 3. Mettre à jour les alts des images existantes (si fournis)
    if (!empty($_POST['existing_image_alt']) && is_array($_POST['existing_image_alt'])) {
        $stmtUpd = $pdo->prepare('UPDATE article_images SET alt = :alt WHERE id = :id');
        foreach ($_POST['existing_image_alt'] as $imgId => $altVal) {
            $stmtUpd->execute([':alt' => trim($altVal), ':id' => (int)$imgId]);
        }
    }

    // 4. Supprimer les images cochées
    $imagesBefore = $imageDao->findAllByArticle($id);
    $mapImages = [];
    foreach ($imagesBefore as $im) { $mapImages[$im->id] = $im->chemin; }

    $toDelete = $_POST['existing_image_delete'] ?? [];
    if (!empty($toDelete) && is_array($toDelete)) {
        foreach ($toDelete as $delId) {
            $did = (int)$delId;
            if ($did <= 0) continue;
            // delete DB entry
            $imageDao->delete($did);
            // delete file on disk if exists
            if (isset($mapImages[$did])) {
                $abs = '/var/www/html' . $mapImages[$did];
                if (file_exists($abs)) {
                    @unlink($abs);
                }
            }
        }
    }

    // 5. Traiter les nouveaux uploads
    $uploadedFilesForCleanup = processImageUploads(
        $id,
        $_FILES['images'] ?? [],
        $_POST['image_desc'] ?? [],
        $imageDao,
        $uploadDirInfo['absolute'],
        $uploadDirInfo['web']
    );

    $pdo->commit();
    redirect('/backoffice/pages/article/list.php?success=' . urlencode('Article mis à jour'));

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // cleanup files
    foreach ($uploadedFilesForCleanup as $f) {
        if (file_exists($f)) { @unlink($f); }
    }
    redirect('/backoffice/pages/article/edit.php?id=' . $id . '&error=' . urlencode('Erreur: ' . $e->getMessage()));
}

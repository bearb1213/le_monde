<?php
// backoffice/layout.php
// Utilisation : avant d'inclure ce fichier, définir (optionnellement) :
//   $pageTitle (string) et $contentFile (chemin absolu ou chemin relatif sous backoffice/pages)
// Exemple minimal :
//   $pageTitle = 'Créer un article';
//   $contentFile = 'article/insert.php';
//   include __DIR__ . '/layout.php';

if (!isset($pageTitle)) {
    $pageTitle = 'Backoffice';
}

define('LAYOUT_INCLUDED', true);

// Si $contentFile est relatif, interpréter par rapport à backoffice/pages
$pagesDir = realpath(__DIR__ . '/pages');
if (!isset($contentFile) || empty($contentFile)) {
    $contentFile = $pagesDir . './accueil.php';
} else {
    // si le chemin donné n'est pas absolu et n'existe pas, tenter pages/
    if (!file_exists($contentFile)) {
        $candidate = __DIR__ . '/pages/' . ltrim($contentFile, '/');
        if (file_exists($candidate)) {
            $contentFile = $candidate;
        }
    }
}

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <link rel="stylesheet" href="/css/backoffice.css">
</head>
<body>
  <aside class="sidebar">
    <div class="brand">Le Monde — Backoffice</div>
    <nav class="nav">
      <a href="/backoffice/pages/accueil.php">Tableau de bord</a>
      <a href="/backoffice/pages/article/insert.php">Créer un article</a>
      <a href="/backoffice/pages/article/list.php">Liste des articles</a>
      <a href="/backoffice/traitement/login/traitement-logout.php">Se déconnecter</a>
    </nav>
    <div class="date-info">Date: <?php echo date('Y-m-d'); ?></div>
  </aside>

  <main class="content">
    <div class="header">
      <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
      <small class="meta">Backoffice — gestion des contenus</small>
    </div>

    <div class="page-body">
      <?php
      // Inclusion sécurisée : n'autoriser que les fichiers sous backoffice/pages
      $resolved = realpath($contentFile);
      if ($resolved && strpos($resolved, $pagesDir) === 0 && is_file($resolved)) {
          include $resolved;
      } else {
          echo '<p>Contenu introuvable ou chemin invalide (<code>' . htmlspecialchars($contentFile) . '</code>).</p>';
      }
      ?>
    </div>
  </main>
</body>
</html>

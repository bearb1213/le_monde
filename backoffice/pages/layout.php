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
  <style>
    :root{--sidebar-bg:#1f2937;--sidebar-color:#e5e7eb;--content-bg:#f7fafc}
    html,body{height:100%;margin:0}
    body{font-family:Segoe UI, Roboto, Arial, sans-serif;display:flex;min-height:100vh}
    .sidebar{width:240px;background:var(--sidebar-bg);color:var(--sidebar-color);padding:18px;box-sizing:border-box}
    .brand{font-weight:700;margin-bottom:12px}
    .nav a{display:block;color:var(--sidebar-color);text-decoration:none;padding:8px 6px;border-radius:4px}
    .nav a:hover{background:rgba(255,255,255,0.04)}
    .content{flex:1;background:var(--content-bg);padding:20px;box-sizing:border-box}
    .header{margin-bottom:18px}
    .page-body{background:#fff;border:1px solid #e6e6e6;padding:16px;border-radius:6px}
    small.meta{color:#666}
  </style>
</head>
<body>
  <aside class="sidebar">
    <div class="brand">Le Monde — Backoffice</div>
    <nav class="nav">
      <a href="/backoffice/pages/index.php">Tableau de bord</a>
      <a href="/backoffice/pages/article/insert.php">Créer un article</a>
      <a href="/backoffice/pages/article/list.php">Liste des articles</a>
      <a href="/login/traitement-logout.php">Se déconnecter</a>
    </nav>
    <div style="margin-top:18px;font-size:0.9rem;color:#cbd5e1">Date: <?php echo date('Y-m-d'); ?></div>
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

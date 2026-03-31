<?php
// backoffice/pages/article/edit.php

require_once __DIR__ . '/../../dao/ArticleDAO.php';
require_once __DIR__ . '/../../dao/ArticleImageDAO.php';
require_once __DIR__ . '/../../dao/ArticleDetailDAO.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo 'Identifiant d\'article manquant.';
    exit;
}

$articleDao = new ArticleDAO();
$imageDao = new ArticleImageDAO();
$detailDao = new ArticleDetailDAO();

try {
    $article = $articleDao->findById($id);
    if (!$article) {
        echo 'Article introuvable.';
        exit;
    }
    $images = $imageDao->findAllByArticle($id);
    $details = $detailDao->findAllByArticle($id);
    $allArticles = $articleDao->findAll();
} catch (Exception $e) {
    echo 'Erreur: ' . htmlspecialchars($e->getMessage());
    exit;
}

if (!defined('LAYOUT_INCLUDED')) {
    $pageTitle = 'Modifier un article';
    $contentFile = __FILE__;
    require __DIR__ . '/../layout.php';
    exit;
}
?>

<script src="/backoffice/pages/tinymce/js/tinymce/tinymce.min.js"></script>
<style>
    .existing-image { display:flex; gap:15px; align-items:center; margin-top:10px; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; background: var(--bg-surface); }
    .existing-image img { max-width:120px; max-height:90px; border:1px solid var(--border-color); border-radius: 4px; object-fit: cover; }
    .note { color: var(--text-secondary); font-size: 0.85em; margin-top: 4px; }
</style>

    <form action="/backoffice/traitement/article/traitement-edit.php" method="post" enctype="multipart/form-data" id="monForm">
        <input type="hidden" name="id" value="<?= (int)$article->id ?>">

        <!-- Titre -->
        <label for="titre">Titre de l'article</label>
        <input id="titre" name="titre" type="text" placeholder="Titre de l'article" required autofocus value="<?= htmlspecialchars($article->titre) ?>">

        <!-- Images existantes -->
        <label>Images existantes</label>
        <?php if (empty($images)): ?>
            <div class="note">Aucune image attachée à cet article.</div>
        <?php else: ?>
            <?php foreach ($images as $img): ?>
                <div class="existing-image">
                    <div><img src="<?= htmlspecialchars($img->chemin) ?>" alt="<?= htmlspecialchars($img->alt) ?>"></div>
                    <div style="flex:1;">
                        <div>
                            <label><input type="checkbox" name="existing_image_delete[]" value="<?= (int)$img->id ?>"> Supprimer</label>
                        </div>
                        <div style="margin-top:6px;">
                            <input type="text" name="existing_image_alt[<?= (int)$img->id ?>]" value="<?= htmlspecialchars($img->alt) ?>" style="width:100%" placeholder="Légende / alt">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Input: Images (multiple) + descriptions -->
        <label for="images">Ajouter des images (optionnel)</label>
        <input id="images" name="images[]" type="file" accept="image/*" multiple>
        <div class="note">Sélectionne des images ; pour chaque image tu peux saisir une description qui sera envoyée comme <code>image_desc[]</code>.</div>
        <div id="images-meta"></div>

        <!-- Input: HTML de l'article -->
        <label for="myEditor">Contenu HTML</label>
        <textarea id="myEditor" name="html"><?= htmlspecialchars($article->html) ?></textarea>

        <!-- Trois selects d'articles de référence -->
        <label>Articles de référence (jusqu'à 3, optionnels)</label>
        <?php
            // Préparer les ids déjà référencés
            $currentRefs = [];
            foreach ($details as $d) { $currentRefs[] = $d->details; }
        ?>
        <?php for ($i = 0; $i < 3; $i++): ?>
            <?php $selected = isset($currentRefs[$i]) ? $currentRefs[$i] : ''; ?>
            <select name="ref_article[]">
                <option value="">-- Aucun --</option>
                <?php foreach ($allArticles as $a): ?>
                    <?php $aid = isset($a->id) ? $a->id : (isset($a['id']) ? $a['id'] : ''); ?>
                    <?php $at = isset($a->titre) ? $a->titre : (isset($a['titre']) ? $a['titre'] : ''); ?>
                    <option value="<?= htmlspecialchars($aid) ?>" <?= ($aid == $selected) ? 'selected' : '' ?>><?= htmlspecialchars($at) ?></option>
                <?php endforeach; ?>
            </select>
        <?php endfor; ?>

        <div style="margin-top:15px;">
            <button type="submit">Mettre à jour</button>
        </div>
    </form>

    <script>
        // Lier les fichiers sélectionnés à des champs de description.
        (function(){
            const input = document.getElementById('images');
            const meta = document.getElementById('images-meta');

            function renderList(files) {
                meta.innerHTML = '';
                if (!files || files.length === 0) return;
                for (let i = 0; i < files.length; i++) {
                    const f = files[i];
                    const wrapper = document.createElement('div');
                    wrapper.style.marginTop = '8px';

                    const name = document.createElement('div');
                    name.textContent = (i+1) + '. ' + f.name + ' (' + Math.round(f.size/1024) + ' KB)';
                    name.style.fontWeight = '600';

                    const desc = document.createElement('input');
                    desc.type = 'text';
                    desc.name = 'image_desc[]';
                    desc.placeholder = 'Description / légende pour ' + f.name;
                    desc.style.width = '100%';
                    desc.style.marginTop = '4px';

                    wrapper.appendChild(name);
                    wrapper.appendChild(desc);
                    meta.appendChild(wrapper);
                }
            }

            input.addEventListener('change', function(e){
                renderList(e.target.files);
            });
        })();
    </script>

    <script>
      // Initialisation de TinyMCE
      tinymce.init({
        selector: '#myEditor',
        plugins: 'lists link image table code fullscreen',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
        height: 600,
        license_key: 'gpl',
        automatic_uploads: true,
        images_upload_url: '/backoffice/traitement/article/traitement-image.php',
        file_picker_types: 'image',
      });
    </script>

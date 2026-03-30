<?php
// backoffice/pages/article/insert.php
// Formulaire d'insertion d'un article

require_once __DIR__ . '/../../dao/ArticleDAO.php';

$dao = new ArticleDAO();
try {
    $articles = $dao->findAll();
} catch (Exception $e) {
    // En cas d'erreur, on tombe sur un tableau vide et on affiche un message
    $articles = [];
    $error = $e->getMessage();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Insérer un article</title>
    <script src="../tinymce/js/tinymce/tinymce.min.js"></script>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; padding: 20px; }
        label { display:block; margin-top:10px; font-weight:600; }
        textarea { width:100%; min-height:200px; }
        select, input[type="file"] { width:100%; }
        .note { color:#666; font-size:0.9em; }
    </style>
</head>
<body>
    <h1>Insérer un article</h1>

    <?php if (!empty($error)): ?>
        <div style="color:crimson;">Erreur lors de la récupération des articles de référence: <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="traitement-insert.php" method="post" enctype="multipart/form-data">

        <!-- Titre -->
        <label for="titre">Titre de l'article</label>
        <input id="titre" name="titre" type="text" placeholder="Titre de l'article" required>

        <!-- Input: Images (multiple) + descriptions -->
        <label for="images">Images (tu peux en sélectionner plusieurs)</label>
        <input id="images" name="images[]" type="file" accept="image/*" multiple>
        <div class="note">Sélectionne des images ; pour chaque image tu peux saisir une description qui sera envoyée comme <code>image_desc[]</code>.</div>

        <!-- Conteneur où on ajoute dynamiquement les descriptions correspondant aux fichiers choisis -->
        <div id="images-meta"></div>

        <!-- Input: HTML de l'article -->
        <label for="myEditor">Contenu HTML</label>
        <textarea id="myEditor" name="html" placeholder="Colle ton HTML ici...">
            <p>Écris ici ton texte...</p>
        </textarea>

        <!-- Trois selects d'articles de référence -->
        <label>Articles de référence (jusqu'à 3, optionnels)</label>
        <?php for ($i = 0; $i < 3; $i++): ?>
            <select name="ref_article[]">
                <option value="">-- Aucun --</option>
                <?php foreach ($articles as $a): ?>
                    <?php $id = isset($a->id) ? $a->id : (isset($a['id']) ? $a['id'] : ''); ?>
                    <?php $titre = isset($a->titre) ? $a->titre : (isset($a['titre']) ? $a['titre'] : ''); ?>
                    <option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($titre) ?></option>
                <?php endforeach; ?>
            </select>
        <?php endfor; ?>

        <div style="margin-top:15px;">
            <button type="submit">Insérer</button>
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
        plugins: 'lists link image',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image',
        height: 300,
        license_key: 'gpl'
      });
    </script>
</body>
</html>

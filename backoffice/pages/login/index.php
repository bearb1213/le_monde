<?php
session_start();
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Backoffice - Login</title>
	<style>
		body { font-family: Arial, sans-serif; padding: 2rem; }
		.form { max-width: 360px; margin: 0 auto; }
		label { display:block; margin-top:0.75rem }
		input { width:100%; padding:0.5rem }
		.msg { margin-bottom:1rem; color: red }
	</style>
</head>
<body>
	<div class="form">
		<h1>Connexion Backoffice</h1>
		<?php if (!empty($_SESSION['user'])): ?>
			<p>Connecté en tant que <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong></p>
			<p><a href="/backoffice/traitement/login/traitement-logout.php">Se déconnecter</a></p>
		<?php else: ?>
			<?php if (!empty($_GET['error'])): ?>
				<div class="msg"><?php echo htmlspecialchars($_GET['error']); ?></div>
			<?php endif; ?>

			<form action="/backoffice/traitement/login/traitement-login.php" method="post">
				<label for="username">Nom d'utilisateur</label>
				<input id="username" name="username" required>

				<label for="password">Mot de passe</label>
				<input id="password" name="password" type="password" required>

				<div style="margin-top:1rem">
					<button type="submit">Se connecter</button>
				</div>
			</form>
		<?php endif; ?>
	</div>
</body>
</html>


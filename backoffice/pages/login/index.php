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
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			background: linear-gradient(180deg, #000000 0%, #1a1a1a 100%);
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 100vh;
			padding: 20px;
		}

		.form {
			max-width: 400px;
			width: 100%;
			background: #ffffff;
			padding: 40px;
			border-radius: 8px;
			box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
			border-left: 4px solid #F59E0B;
		}

		h1 {
			font-size: 28px;
			margin-bottom: 30px;
			color: #000;
			text-transform: uppercase;
			letter-spacing: 1px;
			font-weight: 700;
		}

		label {
			display: block;
			margin-top: 20px;
			margin-bottom: 8px;
			color: #333;
			font-weight: 600;
			font-size: 14px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		input {
			width: 100%;
			padding: 12px 15px;
			border: 1px solid #ddd;
			border-radius: 4px;
			font-size: 15px;
			transition: all 0.3s ease;
		}

		input:focus {
			outline: none;
			border-color: #F59E0B;
			box-shadow: 0 0 10px rgba(245, 158, 11, 0.2);
		}

		button {
			width: 100%;
			margin-top: 25px;
			padding: 12px 20px;
			background: #F59E0B;
			color: #000;
			border: none;
			border-radius: 4px;
			font-size: 15px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 1px;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		button:hover {
			background: #D97706;
			box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
			transform: translateY(-2px);
		}

		.msg {
			margin-bottom: 20px;
			padding: 12px 15px;
			background: #fef2f2;
			border: 1px solid #fca5a5;
			border-radius: 4px;
			color: #dc2626;
			font-size: 14px;
			font-weight: 500;
		}

		.success {
			color: #16a34a;
			padding: 15px;
			background: #f0fdf4;
			border: 1px solid #86efac;
			border-radius: 4px;
			margin-bottom: 20px;
		}

		p {
			color: #666;
			margin: 15px 0;
			line-height: 1.6;
		}

		a {
			color: #F59E0B;
			text-decoration: none;
			font-weight: 600;
			transition: color 0.3s ease;
		}

		a:hover {
			color: #D97706;
		}

		@media (max-width: 480px) {
			.form {
				padding: 30px 20px;
			}

			h1 {
				font-size: 22px;
				margin-bottom: 20px;
			}

			label {
				margin-top: 15px;
			}

			button {
				margin-top: 20px;
				padding: 10px 15px;
				font-size: 13px;
			}
		}
	</style>
</head>
<body>
	<div class="form">
		<h1>Connexion</h1>
		<?php if (!empty($_SESSION['user'])): ?>
			<div class="success">
				<p>Connecté en tant que <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong></p>
			</div>
			<p style="text-align: center;"><a href="/backoffice/traitement/login/traitement-logout.php">Se déconnecter</a></p>
		<?php else: ?>
			<?php if (!empty($_GET['error'])): ?>
				<div class="msg"><?php echo htmlspecialchars($_GET['error']); ?></div>
			<?php endif; ?>

			<form action="/backoffice/traitement/login/traitement-login.php" method="post">
				<label for="username">Nom d'utilisateur</label>
				<input id="username" name="username" value="admin" required autofocus>

				<label for="password">Mot de passe</label>
				<input id="password" name="password" type="password" value="admin" required>

				<button type="submit">Se connecter</button>
			</form>
		<?php endif; ?>
	</div>
</body>
</html>


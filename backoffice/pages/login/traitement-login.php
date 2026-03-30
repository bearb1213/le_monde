<?php
session_start();

// Le DAO des utilisateurs se trouve deux niveaux au-dessus (backoffice/dao)
require_once __DIR__ . '/../../dao/UserDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username === '' || $password === '') {
    header('Location: ../index.php?error=' . urlencode('Veuillez fournir un nom d\'utilisateur et un mot de passe'));
    exit;
}

$dao = new UserDAO();
try {
    $user = $dao->login($username, $password);
    if ($user) {
        // store minimal data in session
        $_SESSION['user'] = ['id' => $user->id, 'username' => $user->username];
        // rediriger vers la page d'accueil du backoffice
        header('Location: ../layout.php');
        exit;
    } else {
        header('Location: ../index.php?error=' . urlencode('Identifiants invalides'));
        exit;
    }
} catch (Exception $e) {
    header('Location: ../index.php?error=' . urlencode('Erreur serveur'));
    exit;
}

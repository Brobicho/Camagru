<?php
require_once('../config/db_connect.php');
session_start();
$_SESSION['create'] = 1;
if (isset($_SESSION['surname']))
    header('Location: account.php');
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
        <title>Bromagru - Inscription</title>
        <link rel="stylesheet" href="../css/register.css">
	</head>
	<body>
        <div id="container">
            <form action="../scripts/reg_check.php" method="POST">
                <h1>Inscription</h1>
                <label><b>Nom</b></label>
                <input type="text" autocomplete='family-name' placeholder="Entrez votre nom" name="name" required>
                <label><b>Prénom</b></label>
                <input type="text" autocomplete='given-name' placeholder="Entrez votre prénom" name="surname" required>
                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrez le mot de passe" name="pwd" required>
                <label><b>Confirmer le mot de passe</b></label>
                <input type="password" placeholder="Entrez le mot de passe" name="pwdconfirm" required>
                <label><b>Email</b></label>
                <input type="email" autocomplete='email' placeholder="Entrer votre email" name="mail" required>
                <input type="submit" id='submit' value='OK'>
                <label class="subscribe-item"><p><a href="login.php">Connectez-vous</a></p></label>
            </form>
        </div>
    </body>
</html>
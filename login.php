<?php
	require_once("config/db_connect.php");
	if (isset($_SESSION['name']) && $_SESSION['name'] != "")
		header('Location: account.php');
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
        <title>Bromagru - Connexion</title>
        <link rel="stylesheet" href="css/login.css">
	</head>
	<body>
        <div id="container">
            <form action="scripts/login_check.php" method="POST">
                <h1>Connexion</h1>
                <label><b>Email</b></label>
                <input type="email" placeholder="Entrez votre email" name="mail" required>
                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrez votre mot de passe" name="pwd" required>
                <input type="submit" id='submit' value='OK'>
                <label class="subscribe-item"><p>Pas de compte ? <a href="register.php">Inscrivez-vous</a></p></label>
                <label class="subscribe-item"><p><a href="pages/mail_reset.php">Mot de passe oublié ?</a></p></label>
            </form>
        </div>
    </body>
</html>
<?php
    require_once('../config/db_connect.php');
    session_start();
    $success = '<div id="container"><p>Mot de passe mis à jour. Redirection vers le menu principal...</p></div>';
    if (isset($_SESSION['reset']) && $_SESSION['reset'] === TRUE && isset($_SESSION['mail']))
{
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
        <title>Bromagru - Réinitialisation de mot de passe</title>
        <link rel="stylesheet" href="../css/register.css">
	</head>
	<body>
        <?php if (isset($_SESSION['success'])) { echo $success; unset($_SESSION['success']); header('refresh:3;url=../index.php'); } ?>
        <div id="container">
            <form action="reset.php" method="POST">
                <h1>Nouveau mot de passe</h1>
                <label><b>Entrez un mot de passe</b></label>
                <input type="password" placeholder="Entrez le mot de passe" name="pwd" required>
                <label><b>Confirmez le mot de passe</b></label>
                <input type="password" placeholder="Entrez le mot de passe" name="pwdconfirm" required>
                <input type="submit" id='submit' value='OK'>
            </form>
        </div>
    </body>
</html>
<?php
	function pwreset($db, $mail, $pwd) {
        $enc = strtoupper(hash("sha256", $pwd));
        $sql = "UPDATE `users` SET `mdp` =" . "'" . $enc . "'" . "WHERE `users`.`mail` =" . "'" . $mail . "'";
        $db->query($sql);
        $sql = "UPDATE `users` SET `henc` = '0' WHERE `users`.`mail` = ". "'" . $mail . "'";
        $db->query($sql);
        $sql = "UPDATE `users` SET `admin` = '0' WHERE `users`.`mail` = ". "'" . $mail . "'";
        $db->query($sql);
        $_SESSION['success'] = TRUE;

		header('refresh:0;url=reset.php');
	}
    
    if (isset($_POST['pwd']) && isset($_POST['pwdconfirm']))
    {
        if ($_POST['pwd'] === $_POST['pwdconfirm'])
            pwreset($db, $_SESSION['mail'], $_POST['pwd']);
        else
        {
            echo "Les mots de passe entrés sont différents. Veuillez réessayer.\n";
            header('refresh:2;url=reset.php');
        }
    }
}
?>
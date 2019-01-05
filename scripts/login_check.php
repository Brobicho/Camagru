<?php
    session_start();
    require_once("../config/db_connect.php");

    function is_here($db, $mail) {
        $sql = "SELECT * FROM users WHERE mail =" ."'".$mail."'";
        $res = $db->query($sql);
        return($res->fetch(PDO::FETCH_OBJ));
}

function connect($db, $res, $mail, $hashed) {
    if (strtoupper($hashed) === $res->mdp) {
		if ($res->admin < 0) {
			echo "Votre compte est inactif. Merci de bien vouloir consulter vos mails ! Vous allez maintenant être redirigé vers la page d'accueil...\n";
			header('refresh:5;url=../index.php'); 
		}
		else {
        $_SESSION['mail'] = $res->mail;
        $_SESSION['name'] = $res->nom;
        echo "Bienvenue, " . $_SESSION['surname'] = $res->prenom . ".";
        $_SESSION['admin'] = $res->admin;
        $_SESSION['id'] = $res->id;
		header('refresh:2;url=../index.php');
		}
    }
    else {
        echo "Mot de passe incorrect.\n";
		header('refresh:2;url=../login.php');
    }
}

$mail = filter_var($_POST['mail'], FILTER_SANITIZE_SPECIAL_CHARS);
$hashed = hash('sha256', $_POST['pwd']);
if (($res = is_here($db, $mail)) && $mail === $_POST['mail']) {
    connect($db, $res, $mail, $hashed);
} else {
    echo "Email inconnu\n";
    header('refresh:2;url=../login.php');
}
?>

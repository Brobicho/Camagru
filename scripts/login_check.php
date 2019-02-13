<?php
    session_start();
    require_once("../config/db_connect.php");

    function is_here($db, $mail) {
        $sql = "SELECT * FROM users WHERE mail = :mail";
        $res = $db->prepare($sql);
        $res->bindParam(":mail", $mail);
        $res->execute();
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
        if (isset($obj[0]))
            return($obj[0]);
        return 0;
}

function connect($db, $res, $mail, $hashed) {
    if (strtoupper($hashed) === $res->mdp) {
		if ($res->henc !== "0") {
			echo "Votre compte est inactif. Merci de bien vouloir consulter vos mails ! Vous allez maintenant être redirigé vers la page d'accueil...\n";
			header('refresh:5;url=../index.php'); 
		}
		else {
        $_SESSION['mail'] = $res->mail;
        $_SESSION['name'] = $res->nom;
        $_SESSION['surname'] = $res->prenom;
        $_SESSION['admin'] = $res->admin;
        $_SESSION['id'] = $res->id;
        echo "Bienvenue, " . $res->prenom . ".";
		header('refresh:2;url=../index.php');
		}
    }
    else {
        echo "Mot de passe incorrect.\n";
		header('refresh:2;url=../pages/login.php');
    }
}
if (isset($_POST['mail']) && isset($_POST['pwd'])) {

    if (filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL) === $_POST['mail']) {
        $mail = $_POST['mail'];
        $hashed = hash('sha256', $_POST['pwd']);
        if (($res = is_here($db, $mail))) {
            connect($db, $res, $mail, $hashed); }
        else {
            echo "Email inconnu\n";
            header('refresh:2;url=../pages/login.php');
        }
    }
}
else
    header('refresh:0;url=../pages/404.php', TRUE, 404);
?>

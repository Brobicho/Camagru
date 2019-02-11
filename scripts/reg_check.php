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

	function namecheck($post) {
		$name = filter_var($post, FILTER_SANITIZE_SPECIAL_CHARS);
		if ($name !== $post) {
			err("Nom invalide.\n");
			return 0; }
		return 1;
	}
	
	function surnamecheck($post) {
		$surname = filter_var($post, FILTER_SANITIZE_SPECIAL_CHARS);
		if ($surname !== $post) {
			err("Prénom invalide.\n");
	 		return 0; }
		return 1;
	}
	
	function pwdcheck($post) {
		return strtoupper(hash('sha256', $post));
	}
	
	function mailcheck($post) {
		$mail = filter_var($post, FILTER_SANITIZE_EMAIL);
		if ($mail !== $post)
			err("Adresse mail invalide.\n");
		return 1;
	}
	
	function err($msg) {
		echo $msg;
		header('refresh:3;url=../pages/register.php');
	}

	function register($db, $name, $surname, $hashed, $mail) {
		$enc = base64_encode(openssl_random_pseudo_bytes(30));
		$henc = hash("sha256", $enc);
		$sql = "INSERT INTO users(nom, prenom, mdp, mail, admin, henc) VALUES (:name, :surname, :pwd, :mail, 0, :henc)"; 
		$res = $db->prepare($sql);
		$res->bindParam(':name', $name);
		$res->bindParam(':surname', $surname);
		$res->bindParam(':pwd', $hashed);
		$res->bindParam(':mail', $mail);
		$res->bindParam(':henc', $henc);
		$res->execute();
		$title = "Bromagru - Inscription";
		$msg = "Veuillez cliquer <a href=\"http://www.localhost:8008/scripts/activate.php?key=" . $henc . "\"" . "> ici </a> afin de confirmer votre inscription\n";
		mail($mail, $title, $msg, null, '-fwebmaster@bromagru.com');
		echo "Bienvenue, " . $surname . " ! Un mail de confirmation vous a été envoyé à l'adresse " . 
			$mail . "." . PHP_EOL;
		echo "Vous allez maintenant être redirigé vers la page d'accueil...\n";
		unset($_SESSION['create']);
		header('refresh:4;url=../index.php');
	}
if (isset($_SESSION['create'])) {
$error = 0;
if ($_POST['pwdconfirm'] !== $_POST['pwd'])
	err("Les mots de passe saisis sont différents.");
else {

	if (namecheck($_POST['name']) && surnamecheck($_POST['surname']) && mailcheck($_POST['mail'])
		&& pwdcheck($_POST['pwd'])) {
		$name = $_POST['name'];
		$surname = $_POST['surname'];
		$mail = $_POST['mail'];
		$hashed = pwdcheck($_POST['pwd']);
	}
	else
		$error = 1;
	if (!$error && !is_here($db, $mail))
		register($db, $name, $surname, $hashed, $mail);
	else
		err("Retour à la création de compte...\n");
	}
}
else {
	header('refresh:3;url=../pages/404.php', TRUE, 404); }
?>


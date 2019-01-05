<?php
    require_once("../config/db_connect.php");

	 function is_here($db, $mail) {
        $sql = "SELECT * FROM users WHERE mail =" ."'".$mail."'";
        $res = $db->query($sql);
        return($res->fetch(PDO::FETCH_OBJ));
	}

	function namecheck($post) {
		$name = filter_var($post, FILTER_SANITIZE_SPECIAL_CHARS);
		if ($name !== $post)
			err("Nom invalide.\n");
		return $name;
	}
	
	function surnamecheck($post) {
		$surname = filter_var($post, FILTER_SANITIZE_SPECIAL_CHARS);
		if ($surname !== $post)
			err("Prénom invalide.\n");
		return $surname;
	}
	
	function pwdcheck($post) {
		$hashed = filter_var($post, FILTER_SANITIZE_SPECIAL_CHARS);
		if ($hashed !== $post)
			err("Mot de passe invalide.\n");
		return strtoupper(hash('sha256', $hashed));
	}
	
	function mailcheck($post) {
		$mail = filter_var($post, FILTER_SANITIZE_SPECIAL_CHARS);
		if ($mail !== $post)
			err("Adresse mail invalide.\n");
		return $mail;
	}
	
	function err($msg) {
		echo $msg;
		//header('refresh:3;url=../register.php');
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
		$msg = "Veuillez cliquer <a href=\"http://www.localhost:8008/reg.php?key=" . $henc . "\"" . "> ici </a> afin de confirmer votre inscription\n";
		mail($mail, $title, $msg, null, '-fwebmaster@bromagru.com');
		echo "Bienvenue, " . $surname . " ! Un mail de confirmation vous a été envoyé à l'adresse " . 
			$mail . "." . PHP_EOL;
		echo "Vous allez maintenant être redirigé vers la page d'accueil...\n";
		//header('refresh:7;url=../index.php');
	}

if ($_POST['pwdconfirm'] !== $_POST['pwd'])
	err("Les mots de passe saisis sont différents.");
else {
$name = namecheck($_POST['name']);
$surname = surnamecheck($_POST['surname']);
$mail = mailcheck($_POST['mail']);
$hashed = pwdcheck($_POST['pwd']);

if (!is_here($db, $mail))
	register($db, $name, $surname, $hashed, $mail);
else 
	err("Mail déjà existant dans la base de données. Veuillez ré-essayer.\n");
}
?>

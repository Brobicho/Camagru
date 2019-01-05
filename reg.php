<?php
    require_once("config/db_connect.php");

    function err() {
        echo "Clé fournie invalide. Merci de contacter le support pour plus d'informations.";
        echo "Vous allez maintenant être redirigé vers la page d'accueil...\n";
		header('refresh:5;url=../index.php');
    }

    function is_here($db, $key) {
        $sql = "SELECT * FROM users WHERE henc =" ."'".$key."'";
        $res = $db->query($sql);
        return($res->fetch(PDO::FETCH_OBJ));
	}

    if (isset($_GET['key']) && $_GET['key'] != "")
    {
        $filter = filter_var($_GET['key'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ($filter !== $_GET['key'])
            err();
        else { 
            if (!is_here($db, $filter))
                err();
            else {
                $sql = "UPDATE `users` SET `henc` = '0' WHERE `users`.`henc` = ". "'" . $filter . "'";
                $db->query($sql);
                print("Compte validé avec succès. Merci de bien vouloir vous connecter.\n");
                header('refresh:3;url=../index.php'); 
            }
        }
    }
    else {
?>
    <br/>
<?php
        err(); }
?>
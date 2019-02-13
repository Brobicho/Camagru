<?php
    require_once("../config/db_connect.php");

    function err($err) {
        echo $err;
        echo "Clé fournie invalide. Merci de contacter le support pour plus d'informations.";
        echo "Vous allez maintenant être redirigé vers la page d'accueil...\n";
		//header('refresh:5;url=../index.php');
    }

function is_here($db, $key) {
    $sql = "SELECT * FROM users WHERE henc = :henc";
    $res = $db->prepare($sql);
    $res->bindParam(':henc', $key);
    $res->execute();
    $obj = $res->fetchAll(PDO::FETCH_OBJ);
    if (isset($obj[0]))
        return($obj[0]);
    return 0;
}

    if (isset($_GET['key']) && $_GET['key'] != "")
    {
        $filter = filter_var($_GET['key'], FILTER_SANITIZE_URL);
        if ($filter !== $_GET['key'])
            err("Données fournies incorrectes.");
        else { 
            if (!is_here($db, $filter))
                err("Données fournies incorrectes.");
            else {

                $sql = "UPDATE `users` SET `henc` = '0' WHERE `users`.`henc` = :henc";
                $res = $db->prepare($sql);
                $res->bindParam(':henc', $filter);
                $res->execute();
                print("Compte validé avec succès. Merci de bien vouloir vous connecter.\n");
                header('refresh:3;url=../index.php'); 
            }
        }
    }
    else {
?>
    <br/>
<?php
        err("Pas de clé fournie."); }
?>
<?php
    require_once("../config/db_connect.php");
    session_start();
    if (!isset($_POST['mail']) || filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) !== $_POST['mail']) {
        echo 'Adresse mail invalide, retour à la gestion du compte...';
        header('refresh:3; url=../pages/account.php');
    }
    else {
        $mail = $_POST['mail'];
        $id = $_SESSION['id'];
        $sql = "UPDATE `users` SET `mail` = :mail WHERE `users`.`id` = :id";
        $res = $db->prepare($sql);
        $res->bindParam(':mail', $mail);
        $res->bindParam(':id', $id);
        $res->execute();
        echo 'Adresse mail changée avec succès. Nouvelle adresse mail : ' . $mail . '<br/>';
        echo 'Retour à la gestion du compte...';
        header('refresh:3; url=../pages/account.php');
    }

    ?>
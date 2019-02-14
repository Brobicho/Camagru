<?php
    require_once('../config/db_connect.php');
    session_start();

    function notifHandler($db, $val) {
        $sql = "UPDATE `users` SET `notif` = :notif WHERE `id` = :id";
        $res = $db->prepare($sql);
        $res->bindParam(':notif', $val);
        $res->bindParam(':id', $_SESSION['id']);
        $res->execute();
    }

    if (isset($_SESSION['id']) && (isset($_POST['on']) || isset($_POST['off']))) {
        $val = 0;
        if (isset($_POST['on']))
            $val = 1;
        notifHandler($db, $val);
        header('refresh:0;url=../pages/account.php');
    }
    else {
        echo 'Une erreur innatendue est survenue, retour au menu principal...';
        header('refresh:3;url=../../index.php');
    }

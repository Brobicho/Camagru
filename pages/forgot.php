<?php
    require_once("../config/db_connect.php");
    session_start();

    function select($db, $content, $field, $select) {
        $sql = "SELECT " . $select . " FROM users WHERE " . $field . "=" . "'" . $content . "'";
        $res = $db->query($sql);
        return($res->fetch(PDO::FETCH_OBJ));
    }

    if (isset($_GET['key']) && ($filter = filter_var($_GET['key'], FILTER_SANITIZE_SPECIAL_CHARS)) === $_GET['key']) //la cle ne comporte pas d'injection
    {
        if (substr($filter, -1) == '/')
            $filter = substr_replace($filter, "", -1);
        if (select($db, $filter, "henc", "*"))                                                              //On lance le reset
        {
            $res = select($db, $filter, "henc", "mail");
            $_SESSION['reset'] = TRUE;
            $_SESSION['mail'] = $res->mail;
            header('refresh:0;url=reset.php');
        }
    }
    else {
        echo "Clé fournie incorrecte, redirection vers le menu principal...\n";
        header('refresh:3;url=../index.php');
    }

?>
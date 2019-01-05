<?php
    require_once("../config/db_connect.php");
    session_start();

    function get_nb($db) {
        $sql = "SELECT ";
    }

function get_image($db) {
        $sql = "SELECT * FROM `gallery`";
        $res = $db->query($sql);
        try {
            $obj = $res->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            return 0;
        }
        $i = 0;
        echo (count($obj));

        while ($i < count($obj)){
            echo '<img src="' . $obj[$i]->data . '" />';
            $i++;
        }
        return (1);
    }

    get_image($db);

    ?>




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
            echo '<a href="comments.php?id=' . $obj[$i]->id . '">Commenter</a>';
            $i++;
        }
        return (1);
    }

    get_image($db);

    ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta content="Gallery" name="title">
<link rel="stylesheet" type="text/css" href=""> <!-- Faire le CSS et include la stylesheet -->
<title>Gallery</title>

<body>
</body>
</html>



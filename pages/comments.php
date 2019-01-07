<?php
    require_once("../config/db_connect.php");
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta content="Comments" name="title">
<link rel="stylesheet" type="text/css" href="../css/comments.css"> <!-- Faire le CSS-->
<title>Comments</title>
<body>

<?php

function display_image($db, $id)
{
    $sql = "SELECT `data` FROM `gallery` WHERE `id` = :id";
    $res = $db->prepare($sql);
    $res->bindParam(':id', $id, PDO::PARAM_INT);
    $res->execute();
    try {
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        return(1); }
    echo '<img src="' . $obj[0]->data . '" />';
    return(0);
}

function load_comments($db, $id) {
    $sql = "SELECT `content` FROM `comments` WHERE `image_id` = :id";
    $res = $db->prepare($sql);
    $res->bindParam(':id', $id, PDO::PARAM_INT);
    $res->execute();
    try {
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        return(1); }
    $i = 0;
    echo 'Commentaires : ' . count($obj);
    while ($i < count($obj)){
        echo $obj[$i]->content;
        $i++;
    }
    return(0);
}

if (isset($_GET['id'])) {
    $img_id = $_GET['id'];
    display_image($db, $img_id);
    load_comments($db, $img_id);
}
?>

</body>
</html>


<?php
    require_once("../config/db_connect.php");
    session_start();
?>

<?php

function is_liked($image_id, $owner, $db)
{
    $sql = "SELECT * FROM likes WHERE image = :image && owner = :owner";
    $res = $db->prepare($sql);
    $res->bindParam(':image', $image_id);
    $res->bindParam(':owner', $owner);
    $res->execute();
    try {
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
    }
    catch (Exception $e) {
        return 0;
    }
    if (!isset($obj[0]->image))
        return (0);
    return (1);
}

function is_clean($content) {
    //check session id
    if (isset($content) && $content !== "")
        return (1);
    return (0);
}

function like_handler($db, $image) {
    if (isset($_POST['like']))
        like($image, $_SESSION['id'],$db);
    else if (isset($_POST['unlike']))
        unlike($image, $_SESSION['id'], $db);
    return (0);
}

function like($image, $owner, $db) {
    // like image req

    if (!is_liked($image, $owner, $db)) {
        $sql = "INSERT INTO likes(owner, image) VALUES (:owner, :image);";
        $res = $db->prepare($sql);
        $res->bindParam(':owner', $owner);
        $res->bindParam(':image', $image);
        try {
            $res->execute(); }
        catch (Exception $e) {
            return (0);
        }
        return (1);
    }
    return 0;
}

function unlike($image_id, $owner, $db) {
    // unlike img req

    $sql = "DELETE FROM likes WHERE image = :image && owner = :owner;";
    $res = $db->prepare($sql);
    $res->bindParam(':image', $image_id);
    $res->bindParam(':owner', $owner);
    try {
        $res->execute(); }
    catch (Exception $e) {
        return (1);
    }
    return ($res);
}

function is_owner($db, $img, $user) {
    // check image owner
    $sql = "SELECT owner_id FROM gallery WHERE id = :image";
    $res = $db->prepare($sql);
    $res->bindParam(':image', $img);
    $res->execute();
    try {
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
    }
    catch (Exception $e) {
        return 0;
    }
    if(isset($obj[0]->owner_id) && $obj[0]->owner_id === $user)
        return 1;
    return 0;
}

if (isset($_SESSION['id'])) {

// Like handler

    if (isset($_POST['like']))
        $val = $_POST['like'];
    else if (isset($_POST['unlike']))
        $val = $_POST['unlike'];
    if ((isset($_POST['like']) || isset($_POST['unlike'])) && isset($_SESSION['id']))
        like_handler($db, $val);

// Comment handler

    if (isset($_SESSION['id']) && isset($_POST['content']) && isset($_POST['img']) && is_numeric($_POST['img'])) {
        $content = htmlspecialchars($_POST['content']);
        if ($content !== $_POST['content'])
            return;
        $img_id = $_POST['img'];
        $sql = "INSERT INTO comments(content, owner_id, image_id) VALUES (:content, :owner_id, :image_id);";
        $res = $db->prepare($sql);
        $res->bindParam(':image_id', $img_id, PDO::PARAM_INT);
        $res->bindParam(':content', $content, PDO::PARAM_STR);
        $res->bindParam(':owner_id', $_SESSION['id'], PDO::PARAM_INT);
        $res->execute();
    }

// Delete scenes

    if (isset($_SESSION['id']) && isset($_POST['delete']) && is_numeric($_POST['delete']) && is_owner($db, $_POST['delete'], $_SESSION['id']))
    {
        $sql = "DELETE FROM gallery WHERE id = :image;";
        $res = $db->prepare($sql);
        $res->bindParam(':image', $_POST['delete']);
        try {
            $res->execute(); }
        catch (Exception $e) {
            return (1);
        }
    }
}


?>

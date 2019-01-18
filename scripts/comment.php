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
    if (!isset($res->image))
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

// Like handler

if (isset($_POST['like']))
    $val = $_POST['like'];
else
    $val = $_POST['unlike'];
if ((isset($_POST['like']) || isset($_POST['unlike'])) && isset($_SESSION['id']))
    like_handler($db, $val);

// Comment handler

if (isset($_SESSION['id']) && isset($_POST['content']) && is_clean($_POST['content'])) {
    $content = $_POST['content'];
    $sql = "INSERT INTO comments(content, owner_id, image_id) VALUES (:content, :owner_id, :image_id);";
    $res = $db->prepare($sql);
    $res->bindParam(':image_id', $img_id, PDO::PARAM_INT);
    $res->bindParam(':content', $content, PDO::PARAM_STR);
    $res->bindParam(':owner_id', $_SESSION['id'], PDO::PARAM_INT);
    $res->execute();
}

?>

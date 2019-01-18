<?php
    require_once("../config/db_connect.php");
    session_start();
?>

<!DOCTYPE html>
<html>
<head></head>
<meta charset="utf-8">
<meta content="Comments" name="title">
<link rel="stylesheet" type="text/css" href="../css/comments.css"> <!-- Faire le CSS-->
<title>Comments</title>
<body>

<script type="text/javascript">



</script>


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
        return (1); }
    echo '<img src="' . $obj[0]->data . '" />';
    return (0);
}

function load_comments($db, $id) {
    $sql = "SELECT `content` FROM `comments` WHERE `image_id` = :id";
    $res = $db->prepare($sql);
    $res->bindParam(':id', $id, PDO::PARAM_INT);
    $res->execute();
    try {
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        return (1); }
    $i = 0;
    echo 'Commentaires : ' . count($obj) . PHP_EOL;
    while ($i < count($obj)){
        echo $obj[$i]->content . PHP_EOL;
        $i++;
    }
    return (0);
}

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

if (isset($_GET['id'])) {
    $img_id = $_GET['id'];
    display_image($db, $img_id);
    load_comments($db, $img_id);


    // Menus handler

    if (isset($_SESSION['name']) && $_SESSION['name'] !== "") {
        echo 'Commentaire: <input type="text" id="content">';
        echo '<button id="submit">Envoyer</button>';
        if (!is_liked($img_id, $_SESSION['id'], $db))
            echo '<button id="btn" onclick="like();">Je n\'aime plus</button>';
        else
            echo '<button id="btn" onclick="like();">J\'aime</button>';
        ?>
        <script type="text/javascript">
                var img = <?php echo $img_id ?>;

                function getXMLHttpRequest() {
                    var xhr = null;
                    if (window.XMLHttpRequest || window.ActiveXObject) {
                        if (window.ActiveXObject) {
                            try {
                                xhr = new ActiveXObject("Msxml2.XMLHTTP");
                            } catch(e) {
                                xhr = new ActiveXObject("Microsoft.XMLHTTP");
                            }
                        } else {
                            xhr = new XMLHttpRequest();
                        }
                    } else {
                        alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
                        return null;
                    }
                    return xhr;
                }

                document.getElementById("submit").addEventListener("click", function() {
                    var xhr = getXMLHttpRequest();
                    xhr.open("POST", "../scripts/comment.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    alert(document.getElementById("content").value);
                    xhr.send("content=" + document.getElementById("content").value);
                });

                function like() {
                    if (document.getElementById("btn").text === "J'aime") {
                        var xhr = getXMLHttpRequest();
                        xhr.open("POST", "../scripts/comment.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        alert('like=' + img);
                        xhr.send("like=" + img);
                        document.getElementById("btn").text = "Je n'aime plus";
                        document.getElementById("btn").value = "Je n'aime plus";
                    }
                    else
                        unlike();
                }

                function unlike() {
                    var xhr = getXMLHttpRequest();
                    xhr.open("POST", "../scripts/comment.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    alert('unlike=' + img);
                    xhr.send("unlike=" + img);
                    document.getElementById("btn").text = "J'aime";
                    document.getElementById("btn").value = "J'aime";
                }
            </script>
<?php
}}
?>
</body>
</html>

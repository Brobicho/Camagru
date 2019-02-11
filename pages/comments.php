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

function like_nb($db, $img) {
    $sql = "SELECT * FROM likes WHERE image = :image";
    $res = $db->prepare($sql);
    $res->bindParam(':image', $img);
    $res->execute();
    try {
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
    }
    catch (Exception $e) {
        return 0;
    }
    return(count($obj));
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
    echo 'Mentions j\'aime : ' . like_nb($db, $id) . '<br/>';
    echo 'Commentaires : ' . count($obj) . '<br/><br/>';
    while ($i < count($obj)){
        echo htmlspecialchars_decode($obj[$i]->content) . '<br/>';
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

function isOk($id, $db) {
    if (!is_numeric($_GET['id']))
        return 0;
    $sql = "SELECT * FROM gallery WHERE id = :image";
    $res = $db->prepare($sql);
    $res->bindParam(':image', $id);
    $res->execute();
    try {
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
    }
    catch (Exception $e) {
        return 0;
    }
    if (!isset($obj[0]->id))
        return (0);
    return (1);
}

// DATA CHECK

if (isset($_GET['id']) && isOk($_GET['id'], $db)) {
    $img_id = $_GET['id'];
    if (isset($_SESSION['id']))
        $_SESSION['like'] = is_liked($img_id, $_SESSION['id'], $db);
    display_image($db, $img_id);
    load_comments($db, $img_id);


    // Menus handler

    if (isset($_SESSION['name']) && $_SESSION['name'] !== "") {
        echo '<input type="text" id="content" placeholder="Commenter...">';
        echo '<button id="submit" class="send">Envoyer</button>';
        echo '<button id="btn" class="like"></button>';
        echo '<form action="../index.php"><button class="return" type="submit">Revenir à l\'index</button></form>';
        echo '<form action="../scripts/logout.php">
                <button class="logout" type="submit" value="submit">
                    Déconnexion
                </button>
            </form>';
        // JAVASCRIPT LIKE & COMMENT HANDLER
        ?>


        <script type="text/javascript">

            var isliked = <?php echo $_SESSION['like']; ?>;
            var img = <?php echo $img_id; ?>;

            if (typeof isliked != null && isliked) {
                document.getElementById("btn").innerHTML = "Je n'aime plus"; }
            else {
                document.getElementById("btn").innerHTML = "J'aime";
            console.log('ok');}

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
            function sleep(milliseconds) {
                var start = new Date().getTime();
                for (var i = 0; i < 1e7; i++) {
                    if ((new Date().getTime() - start) > milliseconds){
                        break;
                    }
                }
            }
                document.getElementById("submit").addEventListener("click", function() {
                    var xhr = getXMLHttpRequest();
                    xhr.open("POST", "../scripts/comment.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("content=" + document.getElementById("content").value + "&img=" + img);
                    sleep(50);
                    location.reload(true);
                });


            document.getElementById("btn").addEventListener("click", function() {
                if (document.getElementById("btn").innerHTML === "J'aime") {
                    var xhr = getXMLHttpRequest();
                    xhr.open("POST", "../scripts/comment.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.send("like=" + img);
                    document.getElementById("btn").innerHTML = "Je n'aime plus";
                    sleep(50);
                    location.reload(true);
                }
                else
                    unlike();
            });

            function unlike() {
                var xhr = getXMLHttpRequest();
                xhr.open("POST", "../scripts/comment.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("unlike=" + img);
                document.getElementById("btn").innerHTML = "J'aime";
                sleep(50);
                location.reload(true);
            }

            </script>
<?php
}
    else
        echo '<form action="../index.php"><button class="return" type="submit">Revenir à l\'index</button></form>';
}
else
    header('refresh:0;url=../pages/404.php', true, 404);
?>
</body>
</html>

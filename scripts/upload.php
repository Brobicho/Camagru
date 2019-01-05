<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="debug" name="title">
    <title>Upload debug page</title>

<body>
<img id="image" >
<img id="filter">
<canvas id="canvas"></canvas>
<?php

require_once("../config/db_connect.php");
session_start();

function data_check($data) {
    if (strncmp($data, "data:image/png;base64", 21)) {?>
        <script type="text/javascript">console.log("invalid data provided, please try again");</script>;<?php
        header('refresh:0;url=../index.php');
        return 0;
    }
    return 1;
}
/* Check et envoi de la requete */

$_SESSION['refresh'] = 1;


?>


<script>

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
    var base64 = "<?php echo $_POST['img']; ?>";

</script> <?php

 if (isset($_POST['img']) && isset($_SESSION['id']) && $_SESSION['id'] !== "" && data_check(($_POST['img']))) {
    $sid = $_SESSION['id'];
    $img = str_replace(' ', '+', $_POST['img']);
    $sql = "INSERT INTO `gallery`(`owner_id`, `data`, `likes`) VALUES (:sid, :img, '0')";
    $res = $db->prepare($sql);
    $res->bindValue(':img', $img, PDO::PARAM_STR);
    $res->bindValue(':sid', $sid, PDO::PARAM_INT);
    $res->execute();
}

else {
    echo "Erreur 404 - Page non trouvée\n";
    header('refresh:3;url=../index.php', null, 404);
}

?>

</body>
</html>
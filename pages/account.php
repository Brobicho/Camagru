<?php
    session_start();
    require_once('../config/db_connect.php');

    function imgCount($db, $user) {
        $sql = "SELECT * FROM gallery WHERE owner_id = :owner";
        $res = $db->prepare($sql);
        $res->bindParam(':owner', $user);
        $res->execute();
        try {
            $obj = $res->fetchAll(PDO::FETCH_OBJ);
        }
        catch (Exception $e) {
            return 0;
        }
        return (count($obj));
    }

    function displayPics($db, $user) {
        $sql = "SELECT * FROM gallery WHERE owner_id = :owner";
        $res = $db->prepare($sql);
        $res->bindParam(':owner', $user);
        $res->execute();
        try {
            $obj = $res->fetchAll(PDO::FETCH_OBJ);
        }
        catch (Exception $e) {
            return 0;
        }
        $i = 0;
        echo '<script>';
        echo '
                function getXMLHttpRequest() {
                var xhr = null;
                if (window.XMLHttpRequest || window.ActiveXObject) {
                    if (window.ActiveXObject) {
                        try {
                            xhr = new ActiveXObject("Msxml2.XMLHTTP");
                        } catch(e) {
                            xhr = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                    } else { xhr = new XMLHttpRequest(); }
                } else {
                    alert("Votre navigateur ne supporte pas l\'objet XMLHTTPRequest...");
                    return null;
                }
                return xhr;
            }

            function dlt(img) {
                var xhr = getXMLHttpRequest();
                xhr.open("POST", "../scripts/comment.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                alert(img);
                xhr.send("delete=" + img);

            }';
        echo '</script>';
        while ($i < count($obj))
            echo '<img src="' . $obj[$i]->data . '" />' . '<input value="Supprimer" type="button" id="btn" onclick="dlt(' . $obj[$i++]->id . ');">';
    }

    if (isset($_SESSION['id']))
    {
        echo "Bienvenue, " . $_SESSION['surname'] . PHP_EOL;
?>      <html>
        <body>
        <div>
            <form action="../scripts/logout.php">
            <button type="submit" value="submit">
            Deconnexion
            </button>
            </form>
        </div>
        <div>
            <form action="../index.php">
            <button type="submit" value="submit">
            Revenir a l'index
            </button>
            </form>
        </div>
        <div>
            Vous avez <?php echo imgCount($db, $_SESSION['id']);?> montage(s) enregistré(s) : <?php displayPics($db, $_SESSION['id']); ?>
        </div>
        </body>
    </html>
        <?php


    }

    else {
        echo "Vous n'êtes pas connecté. Redirection vers le menu principal...\n";
        header('refresh:3;url=../index.php', TRUE, 401); }
?>
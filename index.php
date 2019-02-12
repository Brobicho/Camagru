<?php
  require_once('config/db_connect.php');
    session_start();

  function get_image($db) {
    $sql = "SELECT * FROM `gallery`";
    $res = $db->query($sql);
    try {
        $obj = $res->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        echo '<p>Aucun montage à afficher pour le moment</p>';
        return 0;
    }
    $i = 0;
    while ($i < count($obj)){
        echo '<a href="pages/comments.php?id=' . $obj[$i]->id . '"><img src="' . $obj[$i]->data . '" /></a>';
        $i++;
    }
    if ($i) {
        echo '<script>document.getElementById("css").href="css/main.css"</script>';
        return 0; }
    else
         echo '<script>document.getElementById("css").href="css/loggedempty.css"</script>';
    return (1);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
<link href="css/empty.css" rel="stylesheet" type="text/css" id="css">
</head>
<body>

<div class="col-12 header">
  <h1>Bromagru</h1>
</div>
<?php if(!isset($_SESSION['id'])) { ?>
<div class="row">
<div class="col-10">
</div>
<div class="col-2 log">
    <form action="pages/login.php">
        <button type="submit" value="submit">
            Login  || Register
        </button>
    </form>
</div>
</div>

<?php } else { ?>
<div class="row">
<div class="col-10">
</div>
<div class="col-2 log">
    <form action="pages/account.php">
        <button class="actbtn" type="submit" value="submit">
            Compte
        </button>
    </form>
    <form action="scripts/logout.php">
        <button class="delogbtn" type="submit" value="submit">
            Déconnexion
        </button>
    </form>
    <form action="pages/magnify.php">
        <button class="takebtn" type="submit" value="submit">
            Prendre une photo !
        </button>
    </form>
</div>
</div>
<?php } ?>

<div class="row">
<div class="col-12">
  <h1 class="gallery">Galerie publique</h1>
    <p class="imgisthere">Cliquez sur une image pour accéder à la section commentaires de celle-ci !</p>
    <p class="imgisnotthere">Aucune image à afficher pour le moment...</p>
  <?php
  if (!isset($_SESSION['id'])) {
    echo '<p>Veuillez vous connecter ou vous inscrire pour avoir accès à toutes les fonctionnalités du site !</p>';
?></div>
</div>
<?php
}

get_image($db);

?>

<div class="footer">
  <p class="title">(c)brobicho 2019 </p>
</div>

</body>
</html>
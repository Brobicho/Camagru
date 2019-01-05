<?php
  session_start();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
<link href="css/main.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="col-12 header">
  <h1><center>Bromagru</center></h1>
</div>

<div class="row">

<div class="col-5">
  <h1>Main Title</h1>
  <p>42.</p>
</div>

<div class="col-5">
</div>
<?php
if (!isset($_SESSION['name'])) { 
?>
<div class="col-2 log">
  <form action="login.php">
  <button type="submit" value="submit">
    Login / Register
  </button>
  </form>
</div>
<?php
}
else {
?>
<div class="col-2 log">
<form action="account.php">
  <button type="submit" value="submit">
    Compte
  </button>
  </form>
  <form action="scripts/logout.php">
  <button type="submit" value="submit">
    Déconnexion
  </button>
  </form>
</div>
<?php
}
?>
<div class="col-4">
</div>
<div class="col-3">
  <div class="rightcontent">
    <h2>MMMMH</h2>
    <p>aaaaah.</p>
    <h2>MMMMMH ?</h2>
    <p>aaaah.</p>
    <h2>mmmh.</h2>
    <p>AAAAAAAAAH !!!</p>
  </div>
</div>

</div>

<div class="footer">
  <p>FOOTER</p>
</div>

</body>
</html>
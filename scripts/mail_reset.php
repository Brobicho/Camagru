<?php
require_once("../config/db_connect.php");

function is_here($db, $mail) {
    $sql = "SELECT * FROM users WHERE mail =" ."'".$mail."'";
    $res = $db->query($sql);
    return($res->fetch(PDO::FETCH_OBJ));
}
?>
        <html>
        <div>
            <form action="mail_reset.php" method="POST">
            <label><b>Email</b></label>
            <input type="email" placeholder="Entrez votre email" name="mail" required>
            <input type="submit" id='submit' value='OK'>
            </form>
        </div>
        </html>
<?php
if (isset($_POST['mail'])) {
    if (filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL) === $_POST['mail'])
        $mail = $_POST['mail'];
    else
        return;
if ($mail !== $_POST['mail'] || !is_here($db, $mail))
{
    echo "Adresse mail inconnue, veuillez ré-essayer.\n";
    echo "Vous allez maintenant être redirigé vers la page d'accueil...\n";
    header('refresh:3;url=../index.php');
}
else {
    $enc = base64_encode(openssl_random_pseudo_bytes(30));
    $henc = hash("sha256", $enc);
	mail($mail, 'Bromagru - Mot de passe oublié', 'Veuillez cliquer sur le lien suivant afin de réinitialiser votre mot de passe : http://localhost:8008/pages/forgot.php?key=' . $henc . '/', null,  '-fwebmaster@bromagru.com');
    $sql = "UPDATE `users` SET `henc` =" . "'" . $henc . "'" . "WHERE `users`.`mail` =" . "'" . $mail . "'";
    $db->query($sql);
    echo "Mail envoyé. Vous allez maintenant être redirigé vers la page de connexion.\n";
    header('refresh:3;url=../pages/login.php');
}
}
?>
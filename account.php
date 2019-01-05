<?php
    session_start();

    if (isset($_SESSION['name']) && $_SESSION['name'] != "")
    {
        echo "Bienvenue, " . $_SESSION['surname'] . PHP_EOL;
?>      <html>
        <div>
            <form action="scripts/logout.php">
            <button type="submit" value="submit">
            Deconnexion
            </button>
            </form>
        </div>
        <div>
            <form action="index.php">
            <button type="submit" value="submit">
            Revenir a l'index
            </button>
            </form>
        </div>
        </html><?php
    }

    else {
        echo "Vous n'êtes pas connecté. Redirection vers le menu principal...\n";
        header('refresh:3;url=index.php', TRUE, 401); }
?>
<?php
include_once("db_connect.php");
/*open*/
session_start();
create_users_table($db);

function create_users_table($db) {
    try {
        $sql = "CREATE TABLE IF NOT EXISTS users(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, nom VARCHAR(64) NOT NULL, prenom VARCHAR(64) NOT NULL, mdp VARCHAR(64) NOT NULL, mail VARCHAR(64) UNIQUE NOT NULL, admin INT NOT NULL)";
        $db->query($sql);
        add_admin($db, 'admin', 'admin', hash('sha256', 'admin'));
    }
    catch(PDOException $e) {
        echo "Error: " . $e . PHP_EOL;
        exit;
    }
}

function add_admin($db, $nom, $prenom, $mdp) {
   $sql = "SELECT * FROM users WHERE mail = 'admin@mail.fr'";
   $res = $db->query($sql); 
    if (!($res->fetch(PDO::FETCH_OBJ))) {
        $sql = "INSERT INTO users(nom, prenom, mdp, mail, admin) VALUES ('$nom', '$prenom', '$mdp', 'admin@mail.fr', '2')";
        $db->query($sql);
    }
}

?>

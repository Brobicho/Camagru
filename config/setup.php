<?php
include_once("db_connect.php");

function add_admin($db, $nom, $prenom, $mdp) {
    $sql = "SELECT * FROM users WHERE mail = 'admin@mail.fr'";
    $res = $db->query($sql);
    if (!($res->fetch(PDO::FETCH_OBJ))) {
        $sql = "INSERT INTO users(nom, prenom, mdp, mail, admin, henc) VALUES ('$nom', '$prenom', '$mdp', 'admin@mail.fr', '1', '0')";
        $db->query($sql);
    }
}

function create_users_table($db) {
    try {
        $sql = "DROP TABLE IF EXISTS `users`;
			CREATE TABLE IF NOT EXISTS `users` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`nom` varchar(64) COLLATE latin1_general_ci NOT NULL,
			`prenom` varchar(64) COLLATE latin1_general_ci NOT NULL,
			`mdp` varchar(64) COLLATE latin1_general_ci NOT NULL,
			`mail` varchar(64) COLLATE latin1_general_ci NOT NULL,
			`admin` int(11) NOT NULL,
			`henc` varchar(64) COLLATE latin1_general_ci NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `mail` (`mail`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";
        $db->query($sql);
        add_admin($db, 'admin', 'admin', strtoupper(hash('sha256', 'admin')));
    }
    catch(PDOException $e) {
        echo "Error: " . $e . PHP_EOL;
        exit;
    }
}

function create_gallery_table($db) {
    try {
    $sql = "DROP TABLE IF EXISTS `gallery`;
		CREATE TABLE IF NOT EXISTS `gallery` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`owner_id` int(11) NOT NULL,
		`data` longtext COLLATE latin1_general_ci NOT NULL,
		`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		`likes` int(11) NOT NULL,
		PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";
    $db->query($sql);
    }
    catch(PDOException $e) {
        echo "Error: " . $e . PHP_EOL;
        exit;
    }
}

function create_comments_table($db)
{
    try {
        $sql = "DROP TABLE IF EXISTS `comments`;
			CREATE TABLE IF NOT EXISTS `comments` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`content` longtext COLLATE latin1_general_ci NOT NULL,
			`owner_id` int(11) NOT NULL,
			`image_id` int(11) NOT NULL,
			PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";
        $db->query($sql);
    }
    catch(PDOException $e) {
        echo "Error: " . $e . PHP_EOL;
        exit;
    }
}

function create_likes_table($db) {
    try {
        $sql = "DROP TABLE IF EXISTS `likes`;
			CREATE TABLE IF NOT EXISTS `likes` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`owner` int(11) NOT NULL,
			`image` int(11) NOT NULL,
			`date` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL,
			PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;";
		$db->query($sql);
    }
    catch(PDOException $e) {
        echo "Error: " . $e . PHP_EOL;
        exit;
    }
}

session_start();
create_users_table($db);
create_gallery_table($db);
create_comments_table($db);
create_likes_table($db);

?>

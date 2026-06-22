<?php

// Ako se baza nalazi na drugom poslužitelju ili koristite
// drugo korisničko ime / lozinku, prilagodite podatke ispod.
// =========================================================

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "glasnik";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
    die("Povezivanje s bazom podataka nije uspjelo: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
?>
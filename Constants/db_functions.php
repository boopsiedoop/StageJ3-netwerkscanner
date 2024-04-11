<?php
require_once "config.php";

function getMySQLConnection()
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Database connectie mislukt :( : " . mysqli_connect_error());
    return $conn;
}

?>
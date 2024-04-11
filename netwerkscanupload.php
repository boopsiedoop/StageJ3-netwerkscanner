<?php
// upload de resultaten van de netwerkscanner naar de phpmyadmin database

require_once $_SERVER["DOCUMENT_ROOT"] . "/prototype_website_netwerkscanner/Constants/db_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";

$connection = getMySQLConnection();

    // Stuurt de ingevulde data in de database
    $sql = "INSERT INTO scans( ) 
        VALUES ( )";

    $insertData = mysqli_query($connection, $sql);

    // Als iets verkeert gaat, gaat hij een error uitspugen
    if ($insertData){
        header("Location: " . URL);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }
?>
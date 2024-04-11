<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/prototype_website_netwerkscanner/Constants/db_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";

$Voornaam = $_POST["Voornaam"];
$Achternaam = $_POST["Achternaam"];
$Bedrijfsnaam = $_POST["Bedrijfsnaam"];


$connection = getMySQLConnection();

    // Stuurt de ingevulde data in de database
    $sql = "INSERT INTO aanvragen(Voornaam, Achternaam, Bedrijfsnaam) VALUES ('$Voornaam', '$Achternaam', '$Bedrijfsnaam')";

    $insertData = mysqli_query($connection, $sql);

    // Als iets verkeert gaat, gaat hij een error uitspugen
    if ($insertData){
        $username = getUsername();
        $stmt = mysqli_prepare($connection, "UPDATE users SET Priviledge = 1 WHERE Gebruikersnaam=?");
        
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        header("Location: " . URL. "/aanvraag_netwerkscan.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }
    ?>

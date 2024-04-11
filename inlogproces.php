<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/prototype_website_netwerkscanner/Constants/db_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";

if (isUserAuthenticated()) {
    header("Location: " . URL . "/homepage.php");
    exit();
}

function canUserLogIn(string $username, string $wachtwoord): bool {
    // Maak connectie en haal gebruikersgegevens op
    $conn = getMySQLConnection();
    $stmt = mysqli_prepare($conn, "SELECT Wachtwoord, Gebruikersnaam FROM users WHERE Gebruikersnaam=?");

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $userdata = mysqli_fetch_assoc($result);

    // Check of de gebruiker bestaat
    if ($userdata == null) {
        return false;
    }
    // Check of het wachtwoord klopt met de gehaste versie in de database
    if ($wachtwoord === $userdata["Wachtwoord"]) {
        return true;
    } else {
        return false;
    }
}

function redirectWithError(int $errorNumber): void {
    header("Location: " . URL . "/inlog.php?msg=" . $errorNumber);
    exit();
}

if (isset($_POST["gebruiker"]) && isset($_POST["wachtwoord"])) {
    // Controleer hier de gebruikersnaam en wachtwoord, dit is een eenvoudig voorbeeld
    $username = $_POST["gebruiker"];
    $wachtwoord = $_POST["wachtwoord"];

    if (canUserLogIn($username, $wachtwoord)) {
        // Inloggen geslaagd, doorsturen naar een andere pagina of voer andere acties uit
        authenticateUser($username);
        echo "Inloggen geslaagd!";
        header("Location: " . URL. "homepage.php" );
    } else {
        // Inloggen mislukt
        echo "Ongeldige gebruikersnaam of wachtwoord.";
    } 
} else {
    // Toon het formulier alleen als het niet is verzonden
    header("Location: login.php");
    exit;
}
?>
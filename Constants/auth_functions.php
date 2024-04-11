<?php
require_once "db_functions.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/**
 * Checkt of de gebruiker ingelogd is en returned dat.
 * In tegenstelling tot requireAuthentication(), stuurt deze methode je niet naar een andere pagina.
 *
 * @return bool Gebruiker ingelogd en token geldig
 */
function isUserAuthenticated(): bool{
    return !(empty($_SESSION["token"]));
}

/**
 * Haal de gebruikersnaam van de ingelogde gebruiker op.
 *
 * @return string De gebruikersnaam van de gebruiker, als de gebruiker niet ingelogd is of de gebruiker bestaat niet, wordt null gereturned.
 */
function getUsername(): ?string{
    if (!isUserAuthenticated()) return null;

    return $_SESSION["token"];
}

/**
 * Stuurt gebruiker naar het login formulier als deze niet is ingelogd.
 * Als de gebruiker wel is ingelogd, zal de opgevraagde pagina getoond worden.
 */
function requireAuthentication(): void{
    if (!isUserAuthenticated()) {
        unAuthenticateUser();

        header("Location: " . URL . "/login/index.php?msg=4");
        exit();
    }
}

/**
 * Maakt een authenticatie token en slaat deze op in de huidige sessie en in de database
 * De gebruiker zal hierna beveiligde pagina's kunnen zien, zolang de roleid dit toe staat.
 * Een token is geldig voor zes uur en is gebonden aan het IP.
 *
 * @param string $username Gebruikersnaam van de gebruiker die ingelogd moet worden
 */
function authenticateUser(string $username): void{
    $_SESSION["token"] = $username;
}

/**
 * Logt de gebruiker uit en verwijderd de huidige auth token uit de sessie en uit de database
 *
 */
function unAuthenticateUser(){
    if (!empty($_SESSION["token"])) {
        unset($_SESSION["token"]);
    }
}

/**
 * Haalt de role van de gebruiker op uit de database.
 * De gebruiker moet ingelogd zijn.
 *
 * @return string nummer van de role.
 */
function getUserRole(){
    $username = getUsername();

    $conn = getMySQLConnection();
    $stmt = mysqli_prepare($conn, "SELECT priviledge FROM users WHERE Gebruikersnaam=?");

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    return $data["priviledge"];
}

/**
 * Haalt de role van de gespecificeerde gebruiker op uit de database.
 * De gebruiker moet ingelogd zijn.
 *
 * @param bool $getAsText Wanneer true wordt de naam van de role gereturned, wanneer false het nummer van de role.
 * @return string Naam of nummer van de role.
 */
function getOtherUserRole(string $user, bool $getAsText = false){
    $conn = getMySQLConnection();
    $stmt = mysqli_prepare($conn, "SELECT roleid FROM users WHERE username=?");

    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);

    $data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    if ($getAsText) {
        switch ($data["roleid"]) {
            case 1: return "Gebruiker";
            case 2: return "Admin";
        }
    } else {
        return $data["roleid"];
    }

    return null;
}
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/config.php";

if (!empty($_GET["msg"])) {
    switch ($_GET["msg"]) {
        case 1:
            $msg = "<div>Verkeerde gebruikersnaam/wachtwoord combinatie. Probeer het opnieuw.</div>";
            break;
        case 2:
            $msg = "<div>Je moet ingelogd zijn om deze pagina te bekijken.</div>";
             break;
    }
}
if (isUserAuthenticated()) {
    header("Location: " . URL . "/homepage.php");
     exit();
}
include "templates/header.html"; 
?>
<body>
    <header>
        <h1 id="logo">
            <a><img src="images\MKB_Cyber_Campus_Logo.png" class="logo" alt="logo"/></a>
        </h1>
    </header>
<body>
    <section class="login">
        <h2>Inloggen</h2>
        <form action="inlogproces.php" method="post">
            <label for="gebruiker">Gebruikersnaam:</label>
            <input type="text" id="gebruiker" name="gebruiker" required><br><br>
            <label for="wachtwoord">Wachtwoord:</label>
            <input type="password" id="wachtwoord" name="wachtwoord" required><br><br>
            <input type="submit" value="Submit" class="button">
        </form>
    </section>
</body>
<?php include "templates/footer.html" ?>
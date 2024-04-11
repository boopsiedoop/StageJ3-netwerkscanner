<?php include "templates/header.html"; 
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";

if (!isUserAuthenticated() || getUserRole() == 2) {
    header("Location: " . URL . "/netwerkscan.php");
    exit();
}

?>
<body>
    <header>
        <h1 id="logo">
            <a href="http://localhost/Prototype_website_netwerkscanner/homepage.php" class="logo-link"><img src="images\MKB_Cyber_Campus_Logo.png" class="logo" alt="logo"/></a>
            <nav class="header">
                <a href="http://localhost/Prototype_website_netwerkscanner/Userprofile.php" class="headerlink">Userprofiel</a>
            </nav>
        </h1>
    </header>
    <!-- check of de user een aanvraag heeft gedaan en of deze is goedgekeurt zo ja stuur deze dan door naar de pagina netwerkscan.html-->
    <section class="leftbox">
        <h2>Aanvraag netwerkscan</h2>
         <p>
            Welkom bij de aanvraag voor een netwerkscan
            Bij MKB CyberCampus begrijpen we het belang van een veilig en efficiënt netwerk voor de groei en continuïteit van uw onderneming. Om ervoor te zorgen dat uw digitale infrastructuur optimaal presteert en bestand is tegen de snel veranderende dreigingen, bieden wij professionele netwerkscans aan.
            Waarom een netwerkscan bij MKB CyberCampus?
            Proactieve Beveiliging: Onze netwerkscans identificeren potentiële kwetsbaarheden voordat ze een bedreiging worden. We handelen proactief om uw systemen te beschermen.
            Optimalisatie van Prestaties: Ontdek en adresseer prestatieknelpunten, waardoor uw netwerk soepeler en efficiënter kan functioneren.
            Conformiteit met Beveiligingsnormen: Wij zorgen ervoor dat uw netwerk voldoet aan de hoogste beveiligingsnormen en regelgeving, waardoor u met vertrouwen kunt opereren.
            Investeer in de veiligheid en efficiëntie van uw netwerk. Neem vandaag nog de eerste stap en vraag een netwerkscan aan.
            Samen bouwen we aan een sterke en veilige digitale toekomst voor uw onderneming!
        </p><br>
        <form method="post" action="aanvraag_netwerkscanprocces.php">
            <label for="Voornaam">Voornaam:</label>
            <input type="text" id="Voornaam" name="Voornaam" required><br><br>
            <label for="Achternaam">Achternaam:</label>
            <input type="text" id="Achternaam" name="Achternaam" required><br><br>
            <label for="company">Bedrijfsnaam:</label>
            <input type="text" id="Bedrijfsnaam" name="Bedrijfsnaam" required><br><br>
            <input type="submit" value="Submit" class="button">
            <?php if (getUserRole() == 1){ echo "Aanvraag is in behandeling!"; }?>
    </section>    
</body>
<?php include "templates/footer.html" ?>
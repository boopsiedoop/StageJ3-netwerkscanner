<?php include "templates/header.html";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";

if (!isUserAuthenticated()|| getUserRole() !== 2) {
    header("Location: " . URL . "/inlog.php");
    exit();
}
?>
<body>
    <header>
        <h1 id="logo">
            <a href="http://localhost/Prototype_website_netwerkscanner/homepage.php" class="logo-link"><img src="images\MKB_Cyber_Campus_Logo.png" class="logo" alt="logo"/></a>
            <nav class="header">
                <a href="http://localhost/Prototype_website_netwerkscanner/Scangeschiedenis.php" class="headerlink">Scangeschiedenis</a>
                <a href="http://localhost/Prototype_website_netwerkscanner/Userprofile.php" class="headerlink">Userprofiel</a>
            </nav>
        </h1>
    </header>
    <section class="leftbox">     
        <h2>Netwerkscanner</h2>
        <p>
            Welkom bij de aanvraag voor een netwerkscan
            Bij MKB CyberCampus begrijpen we het belang van een veilig en efficiënt netwerk voor de groei en continuïteit van uw onderneming. Om ervoor te zorgen dat uw digitale infrastructuur optimaal presteert en bestand is tegen de snel veranderende dreigingen, bieden wij professionele netwerkscans aan.
            Waarom een netwerkscan bij MKB CyberCampus?
            Proactieve Beveiliging: Onze netwerkscans identificeren potentiële kwetsbaarheden voordat ze een bedreiging worden. We handelen proactief om uw systemen te beschermen.
            Optimalisatie van Prestaties: Ontdek en adresseer prestatieknelpunten, waardoor uw netwerk soepeler en efficiënter kan functioneren.
            Conformiteit met Beveiligingsnormen: Wij zorgen ervoor dat uw netwerk voldoet aan de hoogste beveiligingsnormen en regelgeving, waardoor u met vertrouwen kunt opereren.                Hoe vraagt u een netwerkscan aan?
            Het aanvragen van een netwerkscan bij MKB CyberCampus is eenvoudig. Vul het onderstaande formulier in en geef ons wat basisinformatie over uw bedrijf en uw specifieke behoeften. Ons deskundige team zal vervolgens contact met u opnemen om de details te bespreken en een op maat gemaakt plan voor uw netwerkscan te creëren.
            Investeer in de veiligheid en efficiëntie van uw netwerk. Neem vandaag nog de eerste stap en vraag een netwerkscan aan.
            Samen bouwen we aan een sterke en veilige digitale toekomst voor uw onderneming!
        </p>    
        <form action="" method="post">
        <input type="submit" value="Start scan" name="submit" class="button"><br>
        <?php
            if(isset($_POST['submit'])) {
                shell_exec("python netwerkscan.py");
                echo "execute scan";
            }
        ?>
    </section>      
</body>
<?php include "templates/footer.html" ?>
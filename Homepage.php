<?php 
include "templates/header.html";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";

if (!isUserAuthenticated()) {
    header("Location: " . URL . "/inlog.php");
    exit();
}


?>
<body>
    <header>
        <h1 id="logo">
            <a href="http://localhost/Prototype_website_netwerkscanner/homepage.php" class="logo-link"><img src="images\MKB_Cyber_Campus_Logo.png" class="logo" alt="logo"/></a>
            <form action="logout.php">
            <input type="submit" value="Logout" class="button"><br>
        </h1>
    </header>
    <section class="leftbox">
        <nav>
            <!--Link naar de verschillende pagina's op de webstie Netwerkscanner zal eerst linken naar de netwerscanner aanvraag pagina deze checkt 
                of de user al een aanvraag heeft gedaan en of deze al is geaccepteerd zo ja dan word deze persoon doorgestuurd naar de netwerkscanner pagina.-->
            <a href="http://localhost/Prototype_website_netwerkscanner/aanvraag_netwerkscan.php" class="menulink">Netwerkscanner</a>
            <a href="http://localhost/Prototype_website_netwerkscanner/security_checklist_level1.php" class="menulink">Security checklist level 1</a>
            <a href="http://localhost/Prototype_website_netwerkscanner/Userprofile.php" class="menulink">Userprofiel</a>
            <br>
            <a href="http://localhost/Prototype_website_netwerkscanner/homepage.php" class="menulink">Website scan</a>
            <a href="http://localhost/Prototype_website_netwerkscanner/homepage.php" class="menulink">Overige tools</a>
        </nav>
    </section>
</body>
<?php include "templates/footer.html" ?>
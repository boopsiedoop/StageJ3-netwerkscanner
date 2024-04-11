<?php include "templates/header.html" ;
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
        </h1>
    </header>
    <section class="leftbox">
        <h2>Userprofiel</h2>
        <p>
            Naam:   John Doe<br>
            Bedrijfsnaam:   Test inc.<br>
            E-mail: JohnDoe@Test.nl<br>    
        </p>
    </section>
</body>
<?php include "templates/footer.html" ?>
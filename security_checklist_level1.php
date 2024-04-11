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
            <nav class="header">
                <a href="http://localhost/Prototype_website_netwerkscanner/Userprofile.php" class="headerlink">Userprofiel</a>
            </nav>
        </h1>
    </header>
    <section class="leftbox">
        <h2>Security checklist level 1</h2>
        <p>
            geef hier aan welke maatregelen al genomen zijn en upload uw bedrijfsplannen<br><br>
            <input type="submit" value="upload bedirijfsplannen" class="button">
        </p>
    </section>
</body>
<?php include "templates/footer.html" ?>
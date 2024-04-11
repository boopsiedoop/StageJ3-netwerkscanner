<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/prototype_website_netwerkscanner/Constants/db_functions.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/config.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";

if (!isUserAuthenticated()) {
    header("Location: " . URL . "/inlog.php");
    exit();
}

$connection = getMySQLConnection();

$sql = "SELECT Datum, Advies FROM Scans ORDER BY Datum DESC ";
        $dataReply = mysqli_query($connection, $sql);

include "templates/header.html";
?>
<body>
    <header>
        <h1 id="logo">
        <a href="http://localhost/Prototype_website_netwerkscanner/homepage.php" class="logo-link"><img src="images\MKB_Cyber_Campus_Logo.png" class="logo" alt="logo"/></a>
        <nav class="header">
            <a href="http://localhost/Prototype_website_netwerkscanner/netwerkscan.php" class="headerlink">Netwerkscanner</a>
            <a href="http://localhost/Prototype_website_netwerkscanner/Userprofile.php" class="headerlink">Userprofiel</a>
        </nav>
        </h1>
    </header>

    <section class="leftbox">
        <table>
            <tr>
                <th>Datum Scan</th>
                <th>Security score</th>
                <th>Download PDF</th>
            </tr>
            <!-- PHP CODE TO FETCH DATA FROM ROWS -->
            <?php 
                // LOOP TILL END OF DATA
                while($rows=$dataReply->fetch_assoc())
                {
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH ROW OF EVERY COLUMN -->
                <td><?php echo $rows['Datum'];?></td>
                <td><?php echo $rows['Advies'];?></td>
                <td><?php echo 'PDF';?></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </section>
</body>
<?php include "templates/footer.html" ?>

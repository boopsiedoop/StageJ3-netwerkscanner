<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/Prototype_website_netwerkscanner/Constants/auth_functions.php";

unAuthenticateUser();

header("Location: " . URL . "/inlog.php");
?>
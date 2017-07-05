<?php

require('ipconfig.php');

$address = "localhost:3306";
$username = "query";
$password = "password";
$dbname = "nursery";

$conn = new mysqli($address, $username, $password, $dbname);

?>
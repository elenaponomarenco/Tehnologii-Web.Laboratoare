<?php

//declaram variabilele pentru a intra in baza de date
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "tw_lab4";

//crearea conectiunii
//mysqli = Represents a connection between PHP and a MySQL database.
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

?>
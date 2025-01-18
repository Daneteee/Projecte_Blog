<?php

// Ens conectem a la base de dades
$host = "mysql";
$usuari = "root";
$password = "1234";
$database = "blog";

$db = mysqli_connect($host, $usuari, $password, $database);

mysqli_query($db, "SET NAMES 'utf8'");

<?php

try {

    $type_bdd   = "mysql";
    $host       = "localhost";
    $dbname     = "php_compte";
    $username   = "root";
    $password   = "";
    $options    = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC /*Ici on définit que le mode de récupération 
        par défaut de données sera fait en associatif*/
    ];

    $bdd = new PDO("$type_bdd:host=$host;dbname=$dbname", $username, $password, $options);
} catch (\Exception $e) {
    die("ERREUR CONNEXION BDD:".$e->getMessage());   
}

// Appel de mes functions

require_once "functions.php";

// Déclaration des variables "globales"

$errorMessage   ="";
$successMessage ="";


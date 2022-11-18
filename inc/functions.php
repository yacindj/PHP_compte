<?php

//Fonction dev qui permet un affichage clair des données
// (array, string, number...)

function debug($value){
    echo "<pre>";
        print_r($value);
    echo "</pre>";
}
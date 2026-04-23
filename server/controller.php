<?php

/** ARCHITECTURE PHP SERVEUR  : Rôle du fichier controller.php
 * 
 *  Dans ce fichier, on va définir les fonctions de contrôle qui vont traiter les requêtes HTTP.
 *  Les requêtes HTTP sont interprétées selon la valeur du paramètre 'todo' de la requête (voir script.php)
 *  Pour chaque valeur différente, on déclarera une fonction de contrôle différente.
 * 
 *  Les fonctions de contrôle vont éventuellement lire les paramètres additionnels de la requête, 
 *  les vérifier, puis appeler les fonctions du modèle (model.php) pour effectuer les opérations
 *  nécessaires sur la base de données.
 *  
 *  Si la fonction échoue à traiter la requête, elle retourne false (mauvais paramètres, erreur de connexion à la BDD, etc.)
 *  Sinon elle retourne le résultat de l'opération (des données ou un message) à includre dans la réponse HTTP.
 */

/** Inclusion du fichier model.php
 *  Pour pouvoir utiliser les fonctions qui y sont déclarées et qui permettent
 *  de faire des opérations sur les données stockées en base de données.
 */
require("model.php");


function readMoviesController(){
    $movies = getAllMovies();

    if ($movies === false || $movies === null) {
        return false; // Indique une erreur dans le traitement de la requête
    }
    return $movies;
}

function addMovieController(){
    if (!isset($_REQUEST['name']) || !isset($_REQUEST['image']) || !isset($_REQUEST['year']) || !isset($_REQUEST['description']) || !isset($_REQUEST['director']) || !isset($_REQUEST['trailer']) || !isset($_REQUEST['min_age']) || !isset($_REQUEST['length'])) {
        return false; // Indique que des paramètres requis sont manquants
    }

    $name = $_REQUEST['name'];
    $image = $_REQUEST['image'];
    $year = $_REQUEST['year'];
    $description = $_REQUEST['description'];
    $director = $_REQUEST['director'];
    $trailer = $_REQUEST['trailer'];
    $min_age = $_REQUEST['min_age'];
    $length = $_REQUEST['length'];

    $ok = addMovie($name, $image, $year, $description, $director, $trailer, $min_age, $length);

    if ($ok !=0){
        return "Le film $name a été ajouté avec succès !";
    } else {
        return false; // Indique une erreur lors de l'ajout du film
    }
}
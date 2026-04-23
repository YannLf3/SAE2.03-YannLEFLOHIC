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
    // Le front admin envoie "title" et "release_year".
    // On garde aussi "name" et "year" pour compatibilite eventuelle.
    $name = $_REQUEST['name'] ?? $_REQUEST['title'] ?? null;
    $image = $_REQUEST['image'] ?? null;
    $year = $_REQUEST['year'] ?? $_REQUEST['release_year'] ?? null;
    $description = $_REQUEST['description'] ?? null;
    $director = $_REQUEST['director'] ?? null;
    $trailer = $_REQUEST['trailer'] ?? null;
    $min_age = $_REQUEST['min_age'] ?? null;
    $length = $_REQUEST['length'] ?? null;

    if ($name === null || $name === '' ||
        $image === null || $image === '' ||
        $year === null || $year === '' ||
        $description === null || $description === '' ||
        $director === null || $director === '' ||
        $trailer === null || $trailer === '' ||
        $min_age === null || $min_age === '' ||
        $length === null || $length === '') {
        return false; // Indique que des paramètres requis sont manquants
    }

    $ok = addMovie($name, $image, $year, $description, $director, $trailer, $min_age, $length);

    if ($ok !=0){
        return "Le film $name a été ajouté avec succès !";
    } else {
        return false; // Indique une erreur lors de l'ajout du film
    }
}
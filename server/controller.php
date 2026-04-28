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

function readCategoriesController(){
    $categories = getAllCategories();
    if ($categories === false || $categories === null) {
        return false; // Indique une erreur dans le traitement de la requête
    }
    return $categories;
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
    $id_category = $_REQUEST['id_category'] ?? null;


    if ($name === null || $name === '' ||
        $image === null || $image === '' ||
        $year === null || $year === '' ||
        $description === null || $description === '' ||
        $director === null || $director === '' ||
        $trailer === null || $trailer === '' ||
        $min_age === null || $min_age === '' ||
        $length === null || $length === '' ||
        $id_category === null || $id_category === '') {
            // Retourne deux choses : False pour indiquer une erreur, et un message d'erreur pour expliquer ce qui s'est mal passé
            return false; // Indique que les paramètres sont manquants ou invalides
            return "Tous les champs sont obligatoires et doivent être valides."; // Message d'erreur pour expliquer que les paramètres sont manquants ou invalides
    }

    $ok = addMovie($name, $image, $year, $description, $director, $trailer, $min_age, $length, $id_category);

    if ($ok !=0){
        return "Le film $name a été ajouté avec succès !";
    } else {
        return "Une erreur est survenue lors de l'ajout du film."; // Indique une erreur lors de l'ajout du film
    }
}

function readMovieDetailController(){
    //lit l'identifiant du film dans $_REQUEST et vérifie qu'il est présent et valide
    // Si 'id' n'est pas envoyé dans la requête, on met null à la place.
    // Équivalent à : isset($_REQUEST['id']) ? $_REQUEST['id'] : null
    $id = $_REQUEST['id'] ?? null;
    //appelle getMovieDetails($id) et retourne false si le film n'existe pas ou si une erreur est survenue, sinon retourne les détails du film
    if ($id === null || $id === '') {
        return false; // Indique que l'identifiant du film est manquant ou invalide
    }
    $movie = getMovieDetails($id);
    if ($movie === false || $movie === null) {
        return false; // Indique que le film n'existe pas ou qu'une erreur est survenue
    }
    return $movie; // Retourne les détails du film
}

function readMoviesGroupedByCategoryController(){
    $age = $_REQUEST['age'] ?? 18;
    $movies = getMoviesGroupedByCategory($age);

    if ($movies === false || $movies === null) {
        return false; // Indique une erreur dans le traitement de la requête
    }
    return $movies;
}

function addProfileController(){
    // '??' signifie : prends la valeur de gauche si elle existe, sinon celle de droite.
    $name    = $_REQUEST['name'] ?? null; // champ obligatoire
    $avatar  = $_REQUEST['avatar'] ?? '';  // champ facultatif
    $min_age = $_REQUEST['min_age'] ?? null; // champ obligatoire

    // Validation : seuls name et min_age sont obligatoires
    if ($name === null || $name === '' ||
        $min_age === null || $min_age === '') {
        return false;
    }

    $ok = addProfile($name, $avatar, $min_age);

    if ($ok) {
        return "Le profil $name a été ajouté avec succès !";
    } else {
        return "Une erreur est survenue lors de l'ajout du profil.";
    }
}

function readProfilesController(){
    $profiles = getAllProfiles();

    if ($profiles === false || $profiles === null) {
        return false; // Indique une erreur dans le traitement de la requête
    }
    return $profiles;
}

function modifyProfileController(){
    // On récupère les champs envoyés par le client ; si un champ manque, on met null.
    $id = $_REQUEST['id'] ?? null;
    $name = $_REQUEST['name'] ?? null;
    $avatar = $_REQUEST['avatar'] ?? null;
    $min_age = $_REQUEST['min_age'] ?? null;

    if ($id === null || $id === '' ||
        $name === null || $name === '' ||
        $min_age === null || $min_age === '') {
        return false; // Indique que les paramètres sont manquants ou invalides
    }

    $ok = modifyProfile($id, $name, $avatar, $min_age);

    if ($ok) {
        return "Le profil a été modifié avec succès !";
    } else {
        return "Une erreur est survenue lors de la modification du profil.";
    }
}
?>
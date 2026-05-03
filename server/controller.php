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
        $name === null || $name === '' ) {
        return false; // Indique que les paramètres sont manquants ou invalides
    }

    $ok = modifyProfile($id, $name, $avatar, $min_age);

    if ($ok) {
        return "Le profil a été modifié avec succès !";
    } else {
        return "Une erreur est survenue lors de la modification du profil.";
    }
}

function addFavoriteController(){
    $id_profile = $_REQUEST['id_profile'] ?? null;
    $id_movie = $_REQUEST['id_movie'] ?? null;

    if ($id_profile === null || $id_profile === '' ||
        $id_movie === null || $id_movie === '') {
        return false;
    }

    // Empêche les doublons en vérifiant si le film est déjà favori.
    $favorites = getFavoritesByProfile($id_profile);
    $alreadyFavorite = false;

    $i = 0;
    $count = count($favorites);
    while ($i < $count) {
        if ((string) $favorites[$i]->id === (string) $id_movie) {
            $alreadyFavorite = true;
            break;
        }
        $i++;
    }

    if ($alreadyFavorite) {
        return "Ce film est déjà dans les favoris.";
    }

    $ok = addFavorite($id_profile, $id_movie);

    if ($ok) {
        return "Le film a été ajouté à vos favoris.";
    }

    return "Une erreur est survenue lors de l'ajout aux favoris.";
}

function readFavoritesController(){
    $id_profile = $_REQUEST['id_profile'] ?? null;

    if ($id_profile === null || $id_profile === '') {
        return false;
    }

    $favorites = getFavoritesByProfile($id_profile);

    if ($favorites === false || $favorites === null) {
        return false;
    }
    
    return $favorites;
}

function removeFavoriteController(){
    $id_profile = $_REQUEST['id_profile'] ?? null;
    $id_movie = $_REQUEST['id_movie'] ?? null;

    if ($id_profile === null || $id_profile === '' ||
        $id_movie === null || $id_movie === '') {
        return false;
    }

    $ok = removeFavorite($id_profile, $id_movie);

    if ($ok) {
        return "Le film a été retiré de vos favoris.";
    }

    return "Une erreur est survenue lors de la suppression des favoris.";
}

function readFeaturedMoviesController(){
    $movies = getFeaturedMovies();

    if ($movies === false || $movies === null) {
        return false; // Indique une erreur dans le traitement de la requête
    }
    return $movies;
}

// Fonctions pour l'itération 12 des stats

function readStatsController(){ // une seule fonction de contrôle pour toutes les stats, qui appelle les fonctions du modèle pour récupérer les différentes statistiques et les retourne dans un tableau associatif.
    return [
        'total_profiles'       => getTotalProfiles(),
        'total_movies'         => getTotalMovies(),
        'avg_favorites'        => getAvgFavoritesPerProfile(),
        'most_favorited_movie' => getMostFavoritedMovie(),
        'most_popular_category'=> getMostPopularCategory(),
    ];
}

function searchMoviesController(){
    $query = $_REQUEST['query'] ?? null;
    if($query === null || $query === ''){
        return false;
    }
    $movies = searchMovies($query);
    
    // On regroupe par catégorie comme dans getMoviesGroupedByCategory
    // Tableau associatif final : clé = nom de la catégorie, valeur = liste simplifiée des films
    $grouped = [];

    $i = 0;
    while ($i < count($movies)) {
        // Film courant (objet récupéré de la requête SQL)
        $movie = $movies[$i];

        // Nom de la catégorie du film courant 
        $cat = $movie->category_name;

        // Si la catégorie n'existe pas encore dans $grouped, on l'initialise avec un tableau vide
        if (!isset($grouped[$cat])) {
            $grouped[$cat] = [];
        }

        // On ajoute une version simplifiée du film (seules les données nécessaires à l'affichage)
        $grouped[$cat][] = [
            'id'    => $movie->id,    // identifiant utilisé pour ouvrir la fiche détaillée
            'name'  => $movie->name,  // titre affiché
            'image' => $movie->image  // chemin/nom de l'image d'illustration
        ];

        $i++;
    }

    // On retourne la structure regroupée par catégorie prête pour le front-end
    return $grouped;
}

function updateFeaturedStatusController(){
    $id       = $_REQUEST['id'] ?? null;
    $featured = $_REQUEST['featured'] ?? null;

    if($id === null || $featured === null){
        return false;
    }

    $ok = updateFeaturedStatus($id, $featured);
    if($ok){
        return "Le statut du film a été mis à jour avec succès.";
    } else {
        return "Une erreur est survenue.";
    }
}

function searchMoviesAdminController(){
    $query = $_REQUEST['query'] ?? null;
    if($query === null || $query === ''){
        return false;
    }
    return searchMovies($query);
}

function addRatingController(){
    $id_profile = $_REQUEST['id_profile'] ?? null;
    $id_movie   = $_REQUEST['id_movie'] ?? null;
    $rating     = $_REQUEST['rating'] ?? null;

    if(!$id_profile || !$id_movie || !$rating){
        return "Données manquantes.";
    }

    // Vérifie si le profil a déjà noté ce film
    if(hasRated($id_profile, $id_movie)){
        return "Vous avez déjà noté ce film.";
    }

    $ok = addRating($id_profile, $id_movie, $rating);
    if($ok){
        return "Votre note a été enregistrée. Merci pour votre contribution !";
    } else {
        return "Une erreur est survenue lors de l'enregistrement de votre note.";
    }
}

function getMovieRatingController(){
    $id_profile = $_REQUEST['id_profile'] ?? 0;
    $id_movie   = $_REQUEST['id_movie'] ?? null;

    if(!$id_movie){
        return false;
    }

    return [
        'average'   => getAverageRating($id_movie),
        'has_rated' => hasRated($id_profile, $id_movie)
    ];
}

function getCommentsByMovieController(){
    $id_movie = $_REQUEST['id_movie'] ?? null;
    if(!$id_movie) return false;
    return getCommentsByMovie($id_movie);
}

function addCommentController(){
    $id_profile = $_REQUEST['id_profile'] ?? null;
    $id_movie   = $_REQUEST['id_movie'] ?? null;
    $content    = $_REQUEST['content'] ?? null;

    if(!$id_profile || !$id_movie || !$content || trim($content) === ''){ //trim() enlève les espaces blancs autour d'un string 
        return "Données manquantes.";
    }

    $ok = addComment($id_profile, $id_movie, $content);
    return $ok ? "Votre commentaire a été ajouté." : "Une erreur est survenue.";
}

function getPendingCommentsController(){
    $comments = getPendingComments();
    if(!$comments) return [];
    return $comments;
}

function approveCommentController(){
    $id = $_REQUEST['id'] ?? null;
    if(!$id) return "Données manquantes.";
    $ok = approveComment($id);
    return $ok ? "Le commentaire a été approuvé avec succès." : "Une erreur est survenue.";
}

function deleteCommentController(){
    $id = $_REQUEST['id'] ?? null;
    if(!$id) return "Données manquantes.";
    $ok = deleteComment($id);
    return $ok ? "Le commentaire a été supprimé." : "Une erreur est survenue.";
}
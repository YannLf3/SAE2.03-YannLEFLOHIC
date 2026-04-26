<?php
/**
 * Ce fichier contient toutes les fonctions qui réalisent des opérations
 * sur la base de données, telles que les requêtes SQL pour insérer, 
 * mettre à jour, supprimer ou récupérer des données.
 */

/**
 * Définition des constantes de connexion à la base de données.
 *
 * HOST : Nom d'hôte du serveur de base de données, ici "localhost".
 * DBNAME : Nom de la base de données
 * DBLOGIN : Nom d'utilisateur pour se connecter à la base de données.
 * DBPWD : Mot de passe pour se connecter à la base de données.
 */
define("HOST", "localhost");
define("DBNAME", "le-flohic4");
define("DBLOGIN", "le-flohic4");
define("DBPWD", "le-flohic4");


function getAllMovies(){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    // Requête SQL pour récupérer le menu avec des paramètres
    $sql = "select id, name, image from SAE203_Movie";
    // Prépare la requête SQL
    $stmt = $cnx->prepare($sql);
    // Exécute la requête SQL
    $stmt->execute();
    // Récupère les résultats de la requête sous forme d'objets
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $res; // Retourne les résultats
}

function getAllCategories(){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    // Requête SQL pour récupérer le menu avec des paramètres
    $sql = "select id, name from SAE203_Category";
    // Prépare la requête SQL
    $stmt = $cnx->prepare($sql);
    // Exécute la requête SQL
    $stmt->execute();
    // Récupère les résultats de la requête sous forme d'objets
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $res; // Retourne les résultats
}

function addMovie($name, $image, $year, $description, $director, $trailer, $min_age, $length, $id_category){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    // Requête SQL pour insérer un nouveau film avec des paramètres
    $sql = "INSERT INTO SAE203_Movie (name, image, year, description, director, trailer, min_age, length, id_category) VALUES (:name, :image, :year, :description, :director, :trailer, :min_age, :length, :id_category)";
    // Prépare la requête SQL
    $stmt = $cnx->prepare($sql);
    // Lie les paramètres à la requête SQL
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':director', $director);
    $stmt->bindParam(':trailer', $trailer);
    $stmt->bindParam(':min_age', $min_age);
    $stmt->bindParam(':length', $length);
    $stmt->bindParam(':id_category', $id_category);
    // Exécute la requête SQL
    return $stmt->execute(); // Retourne true si l'insertion a réussi, sinon false
}

function getMovieDetails($id){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    // Requête SQL pour récupérer les détails d'un film avec un JOIN pour obtenir le nom de la catégorie
    $sql = "SELECT SAE203_Movie.*, SAE203_Category.name as category FROM SAE203_Movie LEFT JOIN SAE203_Category ON SAE203_Movie.id_category = SAE203_Category.id WHERE SAE203_Movie.id = :id";
    // Prépare la requête SQL
    $stmt = $cnx->prepare($sql);
    // Lie le paramètre à la requête SQL
    $stmt->bindParam(':id', $id);
    // Exécute la requête SQL
    $stmt->execute();
    // Récupère les résultats de la requête sous forme d'objets
    $res = $stmt->fetch(PDO::FETCH_OBJ);
    return $res; // Retourne les détails du film avec le nom de la catégorie
}

function getMoviesGroupedByCategory(){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    // On récupère chaque film avec le nom de sa catégorie
    $sql = "SELECT m.id, m.name, m.image, c.name AS category_name 
            FROM SAE203_Movie m
            JOIN SAE203_Category c ON m.id_category = c.id
            ORDER BY c.name, m.name";
    
    // Préparation puis exécution de la requête SQL
    $stmt = $cnx->prepare($sql);
    $stmt->execute();

    // Résultat sous forme d'objets PHP (un objet par ligne)
    $movies = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Tableau final : ["NomCategorie" => [film1, film2, ...]]
    $grouped = [];

    // On parcourt tous les films pour les ranger par catégorie
    $i = 0;
    $moviesCount = count($movies);
    while ($i < $moviesCount) {
        $movie = $movies[$i];
        // Nom de la catégorie du film courant
        $cat = $movie->category_name;

        // Si la catégorie n'existe pas encore, on l'initialise avec un tableau vide
        if (!isset($grouped[$cat])) {
            $grouped[$cat] = [];
        }

        //version simplifiee du film, prête à être envoyée
        // au front : uniquement les informations utiles pour l'affichage en liste.
        // Chaque entrée : une carte film dans une catégorie.
        $grouped[$cat][] = [
            // Identifiant unique du film, pr ouvrir fiche pop up.
            'id'    => $movie->id,
            // Titre affiché sur la carte ou dans la liste.
            'name'  => $movie->name,
            // Nom/chemin de l'image d'illustration du film.
            'image' => $movie->image
        ];

        $i++;
    }

    // On renvoie la structure regroupée par catégorie
    return $grouped;
}

function addProfile($name, $avatar, $min_age){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    // Requête SQL pour insérer un nouveau profil
    $sql = "INSERT INTO SAE203_Profile (name, avatar, min_age)
            VALUES (:name, :avatar, :min_age)";

    // Prépare la requête SQL
    $stmt = $cnx->prepare($sql);

    // Lie les paramètres à la requête SQL
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':avatar', $avatar);
    $stmt->bindParam(':min_age', $min_age);

    // Exécute la requête SQL
    return $stmt->execute();
}

function getAllProfiles(){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    // Requête SQL pour récupérer tous les profils
    $sql = "SELECT id, name, avatar, min_age FROM SAE203_Profile";

    // Prépare la requête SQL
    $stmt = $cnx->prepare($sql);

    // Exécute la requête SQL
    $stmt->execute();

    // Récupère les résultats de la requête sous forme d'objets
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $res; // Retourne les profils
}
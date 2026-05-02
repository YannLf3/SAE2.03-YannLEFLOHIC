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

function getMoviesGroupedByCategory($age){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    // On récupère chaque film avec le nom de sa catégorie
    $sql = "SELECT m.id, m.name, m.image, c.name AS category_name 
            FROM SAE203_Movie m
            JOIN SAE203_Category c ON m.id_category = c.id
            WHERE m.min_age <= :age
            ORDER BY c.name, m.name";
    
    // Préparation puis exécution de la requête SQL
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':age', $age, PDO::PARAM_INT);
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

function modifyProfile($id, $name, $avatar, $min_age){
    // Connexion à la base de données
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    // Requête SQL pour modifier un profil existant
    $sql = "UPDATE SAE203_Profile 
            SET name = :name, avatar = :avatar, min_age = :min_age
            WHERE id = :id";

    // Prépare la requête SQL
    $stmt = $cnx->prepare($sql);

    // Lie les paramètres à la requête SQL
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':avatar', $avatar);
    $stmt->bindParam(':min_age', $min_age);

    // Exécute la requête SQL
    return $stmt->execute();
}

function addFavorite($id_profile, $id_movie){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    $sql = "INSERT INTO SAE203_Favorite (id_profile, id_movie)
            VALUES (:id_profile, :id_movie)";

    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_profile', $id_profile, PDO::PARAM_INT);
    $stmt->bindParam(':id_movie', $id_movie, PDO::PARAM_INT);

    return $stmt->execute();
}

function getFavoritesByProfile($id_profile){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    $sql = "SELECT m.id, m.name, m.image, c.name as category
            FROM SAE203_Favorite f
            JOIN SAE203_Movie m ON f.id_movie = m.id
            LEFT JOIN SAE203_Category c ON m.id_category = c.id
            WHERE f.id_profile = :id_profile
            ORDER BY c.name, m.name";

    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_profile', $id_profile, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function removeFavorite($id_profile, $id_movie){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    $sql = "DELETE FROM SAE203_Favorite
            WHERE id_profile = :id_profile AND id_movie = :id_movie";

    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_profile', $id_profile);
    $stmt->bindParam(':id_movie', $id_movie);

    return $stmt->execute();
}

function getFeaturedMovies(){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);

    $sql = "SELECT id, name, image, description
            FROM SAE203_Movie
            WHERE mis_en_avant = 1
            ORDER BY name";
    
    $stmt = $cnx->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

// Fonctions pour l'itération 12 des stats

// Nombre total de profils
function getTotalProfiles(){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $stmt = $cnx->prepare("SELECT COUNT(*) AS total FROM SAE203_Profile");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ)->total;
}

// Nombre total de films
function getTotalMovies(){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $stmt = $cnx->prepare("SELECT COUNT(*) AS total FROM SAE203_Movie");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ)->total;
}

// Nombre moyen de films en favoris par profil
function getAvgFavoritesPerProfile(){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $stmt = $cnx->prepare("SELECT ROUND(COUNT(*) / (SELECT COUNT(*) FROM SAE203_Profile), 1) AS avg_favorites FROM SAE203_Favorite");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ)->avg_favorites;
}

// Film le plus ajouté aux favoris
function getMostFavoritedMovie(){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $stmt = $cnx->prepare("
        SELECT m.name, COUNT(*) AS total
        FROM SAE203_Favorite f
        JOIN SAE203_Movie m ON f.id_movie = m.id
        GROUP BY f.id_movie
        ORDER BY total DESC
        LIMIT 1
    ");
    $stmt->execute(); // limit en SQL pour ne récupérer que le film le plus ajouté aux favoris limit 1 : on ne récupère que la première ligne du résultat, qui correspond au film le plus ajouté aux favoris.
    $res = $stmt->fetch(PDO::FETCH_OBJ);
    // Si aucun favori en base, on retourne un texte par défaut écriture pour éviter d'avoir une valeur null dans le front. et plus simple que de faire une condition dans le front pour vérifier si la valeur est null ou pas.
    return $res ? $res->name : "Aucun";
}

// Catégorie la plus présente dans les favoris
function getMostPopularCategory(){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $stmt = $cnx->prepare("
        SELECT c.name, COUNT(*) AS total
        FROM SAE203_Favorite f
        JOIN SAE203_Movie m ON f.id_movie = m.id
        JOIN SAE203_Category c ON m.id_category = c.id
        GROUP BY c.id
        ORDER BY total DESC
        LIMIT 1
    ");
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_OBJ);
    return $res ? $res->name : "Aucune";
}

// pour chercher les films à l'aide de la barre de recherche

function searchMovies($query){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $sql = "SELECT m.id, m.name, m.image, m.mis_en_avant, c.name AS category_name
            FROM SAE203_Movie m
            JOIN SAE203_Category c ON m.id_category = c.id
            WHERE m.name LIKE :query
            ORDER BY c.name, m.name";
    $stmt = $cnx->prepare($sql);
    $search = "%" . $query . "%";
    $stmt->bindParam(':query', $search);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function updateFeaturedStatus($id, $mis_en_avant){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $sql = "UPDATE SAE203_Movie SET mis_en_avant = :mis_en_avant WHERE id = :id";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':mis_en_avant', $mis_en_avant);
    return $stmt->execute();
}

function addRating($id_profile, $id_movie, $rating){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $sql = "INSERT INTO SAE203_Rating (id_profile, id_movie, rating)
            VALUES (:id_profile, :id_movie, :rating)";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_profile', $id_profile);
    $stmt->bindParam(':id_movie', $id_movie);
    $stmt->bindParam(':rating', $rating);
    return $stmt->execute();
}

function getAverageRating($id_movie){// nombre moyen de la note d'un film, arrondi à 1 chiffre après la virgule pour les avoir pour itération 19 : plus de stats
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $sql = "SELECT ROUND(AVG(rating), 1) AS average FROM SAE203_Rating WHERE id_movie = :id_movie";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_movie', $id_movie);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_OBJ);
    return $res->average ?? 0;
}

function hasRated($id_profile, $id_movie){// pour vérifier si un profil a déjà noté un film, afin d'empêcher les doublons de notes pour le même film par le même profil
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $sql = "SELECT COUNT(*) AS total FROM SAE203_Rating 
            WHERE id_profile = :id_profile AND id_movie = :id_movie";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_profile', $id_profile);
    $stmt->bindParam(':id_movie', $id_movie);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ)->total > 0;
}

function getCommentsByMovie($id_movie){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $sql = "SELECT c.content, c.created_at, p.name AS profile_name
            FROM SAE203_Comment c
            JOIN SAE203_Profile p ON c.id_profile = p.id
            WHERE c.id_movie = :id_movie
            ORDER BY c.created_at DESC";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_movie', $id_movie);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function addComment($id_profile, $id_movie, $content){
    $cnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, DBLOGIN, DBPWD);
    $sql = "INSERT INTO SAE203_Comment (id_profile, id_movie, content)
            VALUES (:id_profile, :id_movie, :content)";
    $stmt = $cnx->prepare($sql);
    $stmt->bindParam(':id_profile', $id_profile);
    $stmt->bindParam(':id_movie', $id_movie);
    $stmt->bindParam(':content', $content);
    return $stmt->execute();
}
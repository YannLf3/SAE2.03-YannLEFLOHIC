# Documentation Base de Données - Itérations du SAE 2.03

## Vue d'ensemble

Ce document explique l'évolution de la structure de la base de données à travers les itérations du projet, les requêtes SQL utilisées dans le code PHP, et les justifications des choix de conception.

---

## Comparaison : Base originale vs Base finale

### Base originale (SAE2_03.sql)

```
Tableaux : Category, Movie
```

### Base finale (le-flohic4.sql)

```
Tables : SAE203_Category, SAE203_Movie, SAE203_Profile, SAE203_Favorite, SAE203_Comment, SAE203_Rating
```

---

## Modifications apportées

### 1. Préfixe de namespace

**Modification :** Ajout du préfixe `SAE203_` à tous les noms de tables

**Justification :**

- Éviter les conflits de noms en cas de base de données partagée
- Identifier facilement les tables appartenant à ce projet
- Permettre l'existence de plusieurs instances du projet dans une même base

---

### 2. Nouvelle table : SAE203_Profile

**Raison :** Gestion de plusieurs profils d'utilisateurs avec des paramètres de contrôle parental (les min age)

**Structure :**

```sql
CREATE TABLE `SAE203_Profile` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `min_age` int(11) NOT NULL DEFAULT 0
);
```

**Justifications des choix :**

- **id (INT PRIMARY KEY AUTO_INCREMENT)** : Identifiant unique pour chaque profil, auto-incrémenté pour simplifier les insertions
- **name (VARCHAR(255) NOT NULL)** : Nom du profil (ex: "Parent", "Enfant"), limité à 255 caractères (standard pour les noms)
- **avatar (VARCHAR(255) DEFAULT NULL)** : Chemin/nom du fichier image d'avatar, optionnel pour les profils sans avatar
- **min_age (INT DEFAULT 0)** : Âge minimum requis pour ce profil (contrôle parental), défaut à 0 (aucune restriction)

**Requêtes SQL utilisées :**

- **Insertion :** `INSERT INTO SAE203_Profile (name, avatar, min_age) VALUES (:name, :avatar, :min_age)`
- **Récupération :** `SELECT id, name, avatar, min_age FROM SAE203_Profile`
- **Modification :** `UPDATE SAE203_Profile SET name = :name, avatar = :avatar, min_age = :min_age WHERE id = :id` j'ai mis un update alors du replace into mis avant qui posait des problèmes si le profil avait des favoris : ça bloquait

---

### 3. Nouvelle table : SAE203_Favorite

**Raison :** Établir une relation n-n entre Profiles et Movies pour gérer les films favoris par profil

**Structure :**

```sql
CREATE TABLE `SAE203_Favorite` (
  `id_profile` int(11) NOT NULL,
  `id_movie` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_profile`, `id_movie`),
  FOREIGN KEY (`id_profile`) REFERENCES `SAE203_Profile`(`id`),
  FOREIGN KEY (`id_movie`) REFERENCES `SAE203_Movie`(`id`)
);
```

**Justifications des choix :**

- **Clé primaire composée (id_profile, id_movie)** : Garantit qu'un profil ne peut pas ajouter deux fois le même film en favoris
- **created_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)** : Date/heure d'ajout en favoris, utile pour trier les favoris par date
- **Clés étrangères** : Intégrité référentielle - impossible d'ajouter un film ou profil inexistant

**Requêtes SQL utilisées :**

- **Ajout favori :** `INSERT INTO SAE203_Favorite (id_profile, id_movie) VALUES (:id_profile, :id_movie)`
- **Récupération des favoris :** `SELECT m.id, m.name, m.image, c.name as category FROM SAE203_Favorite f JOIN SAE203_Movie m ON f.id_movie = m.id LEFT JOIN SAE203_Category c ON m.id_category = c.id WHERE f.id_profile = :id_profile`
- **Suppression favori :** `DELETE FROM SAE203_Favorite WHERE id_profile = :id_profile AND id_movie = :id_movie`

---

### 4. Modification de la table SAE203_Movie

**Nouvelle colonne :** `length` (duré du film en minutes)

**Avant :**

```sql
CREATE TABLE `Movie` (
  `id`, `name`, `year`, `description`, `director`, `id_category`, `image`, `trailer`, `min_age`
)
```

**Après :**

```sql
CREATE TABLE `SAE203_Movie` (
  `id`, `name`, `year`, `length`, `description`, `director`, `id_category`, `image`, `trailer`, `min_age`
)
```

**Justification :**

- **length (INT)** : Durée du film en minutes, utile pour l'affichage et les filtres de recherche
- Permet aux utilisateurs de voir rapidement la durée sans quitter l'application

**Requêtes SQL utilisées :**

- **Insertion :** `INSERT INTO SAE203_Movie (name, image, year, description, director, trailer, min_age, length, id_category) VALUES (...)`
- **Récupération liste :** `SELECT id, name, image FROM SAE203_Movie`
- **Détails film :** `SELECT SAE203_Movie.*, SAE203_Category.name as category FROM SAE203_Movie LEFT JOIN SAE203_Category ON SAE203_Movie.id_category = SAE203_Category.id WHERE SAE203_Movie.id = :id`
- **Récupération groupée par catégorie :** `SELECT m.id, m.name, m.image, c.name AS category_name FROM SAE203_Movie m JOIN SAE203_Category c ON m.id_category = c.id WHERE m.min_age <= :age ORDER BY c.name, m.name`

---

### 5. Table SAE203_Category (inchangée structurellement)

**Structure :**

```sql
CREATE TABLE `SAE203_Category` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL
);
```

**Justifications :**

- **id (INT PRIMARY KEY)** : Identifiant unique pour chaque catégorie
- **name (VARCHAR(255) NOT NULL)** : Nom de la catégorie (Action, Comédie, etc.), limité à 255 caractères

**Requêtes SQL utilisées :**

- **Récupération :** `SELECT id, name FROM SAE203_Category`

---

### 6. Nouvelle table : SAE203_Comment

**Raison :** Permettre aux profils de commenter les films et de modérer les commentaires avant affichage.

**Structure :**

```sql
CREATE TABLE `SAE203_Comment` (
  `id` int(11) NOT NULL,
  `id_profile` int(11) NOT NULL,
  `id_movie` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(1) NOT NULL DEFAULT 0
);
```

**Justifications des choix :**

- **id (INT PRIMARY KEY AUTO_INCREMENT)** : Identifiant unique de chaque commentaire
- **id_profile / id_movie (INT NOT NULL)** : Liaison directe avec le profil auteur et le film commenté
- **content (TEXT NOT NULL)** : Champ long adapté aux messages utilisateur
- **created_at (DATETIME DEFAULT CURRENT_TIMESTAMP)** : Permet d'afficher les commentaires dans l'ordre chronologique
- **approved (TINYINT(1) DEFAULT 0)** : Statut de modération, 0 = en attente, 1 = validé

**Requêtes SQL utilisées :**

- **Ajout :** `INSERT INTO SAE203_Comment (id_profile, id_movie, content) VALUES (:id_profile, :id_movie, :content)`
- **Lecture par film :** `SELECT c.content, c.created_at, p.name AS profile_name FROM SAE203_Comment c JOIN SAE203_Profile p ON c.id_profile = p.id WHERE c.id_movie = :id_movie AND c.approved = 1 ORDER BY c.created_at DESC`
- **Modération :** `UPDATE SAE203_Comment SET approved = 1 WHERE id = :id`
- **Suppression :** `DELETE FROM SAE203_Comment WHERE id = :id`

**Modifications sur la base :**

- Ajout d'une table dédiée aux commentaires pour séparer les contenus saisis par les utilisateurs des tables métier
- Utilisation d'un champ `approved` pour éviter d'afficher immédiatement tous les commentaires sans validation
- Choix de `TEXT` pour ne pas limiter artificiellement la longueur des messages

---

### 7. Nouvelle table : SAE203_Rating

**Raison :** Permettre à chaque profil de noter un film et exploiter ces notes dans les statistiques.

**Structure :**

```sql
CREATE TABLE `SAE203_Rating` (
  `id` int(11) NOT NULL,
  `id_profile` int(11) NOT NULL,
  `id_movie` int(11) NOT NULL,
  `rating` int(11) NOT NULL
);
```

**Justifications des choix :**

- **id (INT PRIMARY KEY AUTO_INCREMENT)** : Identifiant technique de chaque note
- **id_profile / id_movie (INT NOT NULL)** : Association d'une note à un profil et à un film
- **rating (INT NOT NULL)** : Valeur numérique simple pour calculer des moyennes et des classements

**Requêtes SQL utilisées :**

- **Ajout :** `INSERT INTO SAE203_Rating (id_profile, id_movie, rating) VALUES (:id_profile, :id_movie, :rating)`
- **Moyenne d'un film :** `SELECT ROUND(AVG(rating), 1) AS average FROM SAE203_Rating WHERE id_movie = :id_movie`
- **Vérification des doublons :** `SELECT COUNT(*) AS total FROM SAE203_Rating WHERE id_profile = :id_profile AND id_movie = :id_movie`
- **Film le mieux noté :** `SELECT m.name, ROUND(AVG(r.rating), 1) AS avg_rating FROM SAE203_Rating r JOIN SAE203_Movie m ON r.id_movie = m.id GROUP BY r.id_movie ORDER BY avg_rating DESC LIMIT 1`

**Modifications sur la base :**

- Ajout d'une table de notes distincte pour éviter de stocker plusieurs valeurs dans la table des films
- La note est séparée du commentaire pour garder deux usages différents : évaluation chiffrée et avis textuel
- La table permet aussi de calculer des statistiques globales sans modifier la structure de `SAE203_Movie`

---

### 8. Colonnes ajoutées à SAE203_Movie pour les contenus mis en avant et les nouveautés

**Nouvelles colonnes :** `mis_en_avant`, `created_at`, `is_new`

**Justification :**

- **mis_en_avant (TINYINT(1))** : Sert à marquer les films affichés dans les sections spéciales de la page d'accueil
- **created_at (DATETIME)** : Permet de déterminer si un film est récent
- **is_new (INT)** : Colonne de travail utilisée dans l'application pour l'affichage des nouveautés

**Requêtes SQL utilisées :**

- **Lecture des films mis en avant :** `SELECT id, name, image, description FROM SAE203_Movie WHERE mis_en_avant = 1 ORDER BY name`
- **Mise à jour du statut mis en avant :** `UPDATE SAE203_Movie SET mis_en_avant = :mis_en_avant WHERE id = :id`
- **Recherche :** `SELECT m.id, m.name, m.image, m.mis_en_avant, c.name AS category_name FROM SAE203_Movie m JOIN SAE203_Category c ON m.id_category = c.id WHERE m.name LIKE :query ORDER BY c.name, m.name`
- **Film le plus récent :** `SELECT name FROM SAE203_Movie ORDER BY created_at DESC LIMIT 1`

**Modifications sur la base :**

- Ajout d'un booléen pour distinguer les films mis en avant sans dupliquer les données
- Utilisation de `created_at` pour calculer les films récents directement à partir de la date d'insertion
- Conservation de `name`, `image` et `description` dans les requêtes pour limiter les colonnes lues au strict nécessaire

---

## Cardinalités des relations

### 1. Relation Category ↔ Movie

```
Cardinalité : (1, n)
```

**Explication :**

- **(1, 1) côté Movie vers Category** : Un film appartient à une seule catégorie
- **(1, n) côté Category vers Movie** : Une catégorie peut regrouper plusieurs films
- **Implémentation :** Clé étrangère `id_category` dans SAE203_Movie

**Exemple :**

- Catégorie "Action" → peut contenir 50 films
- Film "Top Gun" → appartient à la catégorie "Action"

---

### 2. Relation Profile ↔ Movie (via Favorite)

```
Cardinalité : (n, n)
```

**Explication :**

- **n côté Profile** : Un profil peut avoir plusieurs films favoris
- **n côté Movie** : Un film peut être ajouté aux favoris par plusieurs profils
- **Implémentation :** Table d'association SAE203_Favorite avec clés étrangères vers Profile et Movie

**Exemple :**

- Profil "Yannlf36" → peut avoir 8 films favoris
- Film "Interstellar" → peut être dans les favoris de 3 profils différents

### 3. Relation Profile ↔ Comment

```
Cardinalité : (1, n)
```

**Explication :**

- **(1, 1) côté Comment vers Profile** : Un commentaire est rédigé par un seul profil
- **(1, n) côté Profile vers Comment** : Un profil peut écrire plusieurs commentaires
- **Implémentation :** Clé étrangère `id_profile` dans SAE203_Comment

### 4. Relation Movie ↔ Comment

```
Cardinalité : (1, n)
```

**Explication :**

- **(1, 1) côté Comment vers Movie** : Un commentaire concerne un seul film
- **(1, n) côté Movie vers Comment** : Un film peut recevoir plusieurs commentaires
- **Implémentation :** Clé étrangère `id_movie` dans SAE203_Comment

### 5. Relation Profile ↔ Rating

```
Cardinalité : (1, n)
```

**Explication :**

- **(1, 1) côté Rating vers Profile** : Une note est donnée par un seul profil
- **(1, n) côté Profile vers Rating** : Un profil peut noter plusieurs films
- **Implémentation :** Clé étrangère `id_profile` dans SAE203_Rating

### 6. Relation Movie ↔ Rating

```
Cardinalité : (1, n)
```

**Explication :**

- **(1, 1) côté Rating vers Movie** : Une note correspond à un seul film
- **(1, n) côté Movie vers Rating** : Un film peut recevoir plusieurs notes
- **Implémentation :** Clé étrangère `id_movie` dans SAE203_Rating

### 7. Relation Profile ↔ Movie (via Favorite)

```
Cardinalité : (n, n)
```

**Explication rapide :**

- Une table d'association est nécessaire car un profil peut aimer plusieurs films et un film peut être aimé par plusieurs profils
- La clé primaire composée dans `SAE203_Favorite` empêche les doublons pour un même couple profil/film

---

## Types de données et longueurs

| Table    | Colonne      | Type      | Longueur | Contraintes   | Justification                           |
| -------- | ------------ | --------- | -------- | ------------- | --------------------------------------- |
| Category | id           | INT       | 11       | PRIMARY KEY   | Identifiant unique                      |
| Category | name         | VARCHAR   | 255      | NOT NULL      | Noms de catégories (Action, Comédie...) |
| Movie    | id           | INT       | 11       | PRIMARY KEY   | Identifiant unique                      |
| Movie    | name         | VARCHAR   | 255      | NOT NULL      | Titre du film                           |
| Movie    | year         | INT       | 11       | DEFAULT NULL  | Année de sortie (1900-2100)             |
| Movie    | length       | INT       | 11       | DEFAULT NULL  | Durée en minutes                        |
| Movie    | description  | TEXT      | -        | DEFAULT NULL  | Description longue du film              |
| Movie    | director     | VARCHAR   | 255      | DEFAULT NULL  | Nom du réalisateur                      |
| Movie    | id_category  | INT       | 11       | FK            | Référence à Category                    |
| Movie    | image        | VARCHAR   | 255      | DEFAULT NULL  | Chemin du fichier image                 |
| Movie    | trailer      | VARCHAR   | 255      | DEFAULT NULL  | URL du trailer (YouTube)                |
| Movie    | min_age      | INT       | 11       | DEFAULT NULL  | Âge minimum requis                      |
| Movie    | mis_en_avant | TINYINT   | 1        | DEFAULT 0     | Mise en avant sur la page d'accueil     |
| Movie    | created_at   | DATETIME  | -        | DEFAULT NOW() | Date de création du film                |
| Movie    | is_new       | INT       | 11       | DEFAULT 0     | Indicateur de nouveauté                 |
| Profile  | id           | INT       | 11       | PRIMARY KEY   | Identifiant unique                      |
| Profile  | name         | VARCHAR   | 255      | NOT NULL      | Nom du profil                           |
| Profile  | avatar       | VARCHAR   | 255      | DEFAULT NULL  | Chemin du fichier avatar                |
| Profile  | min_age      | INT       | 11       | DEFAULT 0     | Âge minimum du contrôle parental        |
| Favorite | id_profile   | INT       | 11       | FK, PK        | Référence à Profile                     |
| Favorite | id_movie     | INT       | 11       | FK, PK        | Référence à Movie                       |
| Favorite | created_at   | TIMESTAMP | -        | DEFAULT NOW() | Date d'ajout en favoris                 |
| Comment  | id           | INT       | 11       | PRIMARY KEY   | Identifiant unique                      |
| Comment  | id_profile   | INT       | 11       | FK            | Référence au profil auteur              |
| Comment  | id_movie     | INT       | 11       | FK            | Référence au film commenté              |
| Comment  | content      | TEXT      | -        | NOT NULL      | Contenu du commentaire                  |
| Comment  | created_at   | DATETIME  | -        | DEFAULT NOW() | Date du commentaire                     |
| Comment  | approved     | TINYINT   | 1        | DEFAULT 0     | Validation de modération                |
| Rating   | id           | INT       | 11       | PRIMARY KEY   | Identifiant unique                      |
| Rating   | id_profile   | INT       | 11       | FK            | Référence au profil notant              |
| Rating   | id_movie     | INT       | 11       | FK            | Référence au film noté                  |
| Rating   | rating       | INT       | 11       | NOT NULL      | Note attribuée                          |

---

## Résumé des itérations

### Itération 1 : Base originale (SAE2_03.sql)

- **Objectif :** Gestion basique de films et catégories
- **Tables :** Category, Movie
- **Fonctionnalités :**
  - Affichage des films par catégorie
  - Affichage des détails d'un film
  - Gestion admin des films

### Itération 2 : Extension avec profils et favoris (le-flohic4.sql)

- **Objectif :** Ajouter le support multi-profils et système de favoris
- **Modifications :**
  - Ajout du préfixe "SAE203\_" pour namespace
  - Création table SAE203_Profile : gestion de profils utilisateurs
  - Création table SAE203_Favorite : relation n-n entre profils et films
  - Ajout colonne `length` à SAE203_Movie : durée des films
  - Ajout colonne `min_age` à SAE203_Profile : contrôle parental
  - Ajout colonne `created_at` à SAE203_Favorite : horodatage des favoris
- **Nouvelles fonctionnalités :**
  - Création/modification de profils
  - Ajout/suppression de films aux favoris
  - Affichage des films selon l'âge du profil
  - Affichage des films favoris par profil
  - Contrôle parental basé sur l'âge du profil et `min_age` du film

### Itération 3 : Commentaires et notes

- **Objectif :** Ajouter les avis utilisateurs et la modération
- **Modifications :**
  - Création de `SAE203_Comment` pour stocker les commentaires
  - Création de `SAE203_Rating` pour stocker les notes par profil et par film
  - Ajout du champ `approved` pour filtrer les commentaires publiés
- **Requêtes SQL principales :**
  - Insertion commentaire : `INSERT INTO SAE203_Comment ...`
  - Lecture commentaires validés : `SELECT c.content, c.created_at, p.name AS profile_name ... WHERE c.approved = 1`
  - Validation commentaire : `UPDATE SAE203_Comment SET approved = 1 WHERE id = :id`
  - Insertion note : `INSERT INTO SAE203_Rating ...`
  - Moyenne d'un film : `SELECT ROUND(AVG(rating), 1) AS average ...`

### Itération 4 : Films mis en avant, recherche et statistiques

- **Objectif :** Améliorer la visibilité des films et produire des statistiques globales
- **Modifications :**
  - Ajout de `mis_en_avant` sur `SAE203_Movie`
  - Exploitation de `created_at` pour identifier les nouveautés
  - Ajout de requêtes de recherche par nom de film
  - Ajout de requêtes statistiques sur les favoris, les notes et les commentaires
- **Requêtes SQL principales :**
  - Films mis en avant : `SELECT id, name, image, description FROM SAE203_Movie WHERE mis_en_avant = 1`
  - Recherche : `SELECT m.id, m.name, m.image, m.mis_en_avant, c.name AS category_name FROM SAE203_Movie m JOIN SAE203_Category c ON m.id_category = c.id WHERE m.name LIKE :query`
  - Film le plus récent : `SELECT name FROM SAE203_Movie ORDER BY created_at DESC LIMIT 1`
  - Statistiques : `SELECT COUNT(*)`, `SELECT ROUND(COUNT(*) / (SELECT COUNT(*) FROM SAE203_Profile), 1)`, `SELECT ROUND(AVG(rating), 1)`

---

## Architecture globale

```
SAE203_Category (1)
    ↓ (1,n)
SAE203_Movie
    ↓ (n,n)
SAE203_Favorite
    ↓ (n)
SAE203_Profile
  ↓ (1,n)
SAE203_Comment
  ↓ (1,n)
SAE203_Rating
```

**Flux de données :**

1. Un utilisateur sélectionne un profil (SAE203_Profile)
2. L'application récupère l'âge min du profil
3. Elle affiche les films accessibles selon cet âge (SAE203_Movie where min_age <= profile.min_age)
4. Les films sont groupés par catégorie (SAE203_Category)
5. L'utilisateur peut ajouter des films aux favoris (SAE203_Favorite)
6. L'application affiche les films favoris du profil actuel

---

## Considérations de sécurité et performance

### Sécurité

- **Requêtes préparées (PDO)** : Toutes les requêtes utilisent des paramètres liés pour prévenir les injections SQL
- **Types de données strictes** : Les paramètres sont typés (PDO::PARAM_INT pour les IDs)
- **Clés étrangères** : Intégrité forcée au niveau base de données

### Performance

- **Clés primaires** : Index automatique sur tous les ID
- **Clés étrangères** : Index sur les colonnes de jointure
- **SELECT spécifiques** : Les requêtes ne retournent que les colonnes nécessaires
- **Groupement par catégorie** : Fait côté application (PHP) pour plus de flexibilité

---

## Évolutions futures possibles

1. **Ajouter une table User** : Authentification et gestion des sessions
2. **Historique des vues** : Table SAE203_ViewHistory pour tracker les films regardés
3. **Notes/Avis** : Table SAE203_Rating pour laisser des avis et notes
4. **Listes de lecture** : Table SAE203_Playlist pour grouper les films en collections
5. **Recherche full-text** : Index FULLTEXT sur name et description pour des recherches rapides
6. **Statistiques** : Vue materialisée pour compter favoris par film

---

## Conclusion

L'évolution de la base de données suit une architecture MVC bien définie, avec une séparation claire entre modèle, contrôle et présentation. Les modifications apportées respectent les bonnes pratiques :

- Normalisation des données (tables séparées, clés étrangères)
- Intégrité référentielle (contraintes FK)
- Scalabilité (structure extensible pour futures évolutions)
- Sécurité (requêtes préparées, types stricts)
- Performance (indexation appropriée)

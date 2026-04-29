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
Tableaux : SAE203_Category, SAE203_Movie, SAE203_Profile, SAE203_Favorite
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

**Raison :** Gestion de plusieurs profils d'utilisateurs avec des paramètres de contrôle parental

**Structure :**

```sql
CREATE TABLE `SAE203_Profile` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `min_age` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

**Justifications des choix :**

- **id (INT PRIMARY KEY AUTO_INCREMENT)** : Identifiant unique pour chaque profil, auto-incrémenté pour simplifier les insertions
- **name (VARCHAR(255) NOT NULL)** : Nom du profil (ex: "Parent", "Enfant"), limité à 255 caractères (standard pour les noms)
- **avatar (VARCHAR(255) DEFAULT NULL)** : Chemin/nom du fichier image d'avatar, optionnel pour les profils sans avatar
- **min_age (INT DEFAULT 0)** : Âge minimum requis pour ce profil (contrôle parental), défaut à 0 (aucune restriction)

**Requêtes SQL utilisées :**

- **Insertion :** `INSERT INTO SAE203_Profile (name, avatar, min_age) VALUES (:name, :avatar, :min_age)`
- **Récupération :** `SELECT id, name, avatar, min_age FROM SAE203_Profile`
- **Modification :** `UPDATE SAE203_Profile SET name = :name, avatar = :avatar, min_age = :min_age WHERE id = :id`

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

**Justifications des choix :**

- **Clé primaire composée (id_profile, id_movie)** : Garantit qu'un profil ne peut pas ajouter deux fois le même film en favoris
- **created_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)** : Date/heure d'ajout en favoris, utile pour trier les favoris par date
- **Clés étrangères** : Intégrité référentielle - impossible d'ajouter un film ou profil inexistant
- **Charset utf8mb4** : Support complet Unicode (emojis, accents, etc.)

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
```

**Justifications :**

- **id (INT PRIMARY KEY)** : Identifiant unique pour chaque catégorie
- **name (VARCHAR(255) NOT NULL)** : Nom de la catégorie (Action, Comédie, etc.), limité à 255 caractères

**Requêtes SQL utilisées :**

- **Récupération :** `SELECT id, name FROM SAE203_Category`

---

## Cardinalités des relations

### 1. Relation Category ↔ Movie

```
Cardinalité : (1, n)
```

**Explication :**

- **1 côté Category** : Une catégorie correspond à exactement 1 ligne dans la table Category
- **n côté Movie** : Un film peut être associé à une seule catégorie (1), mais une catégorie peut avoir plusieurs films (n)
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

---

## Types de données et longueurs

| Table    | Colonne     | Type      | Longueur | Contraintes   | Justification                           |
| -------- | ----------- | --------- | -------- | ------------- | --------------------------------------- |
| Category | id          | INT       | 11       | PRIMARY KEY   | Identifiant unique                      |
| Category | name        | VARCHAR   | 255      | NOT NULL      | Noms de catégories (Action, Comédie...) |
| Movie    | id          | INT       | 11       | PRIMARY KEY   | Identifiant unique                      |
| Movie    | name        | VARCHAR   | 255      | NOT NULL      | Titre du film                           |
| Movie    | year        | INT       | 11       | DEFAULT NULL  | Année de sortie (1900-2100)             |
| Movie    | length      | INT       | 11       | DEFAULT NULL  | Durée en minutes                        |
| Movie    | description | TEXT      | -        | DEFAULT NULL  | Description longue du film              |
| Movie    | director    | VARCHAR   | 255      | DEFAULT NULL  | Nom du réalisateur                      |
| Movie    | id_category | INT       | 11       | FK            | Référence à Category                    |
| Movie    | image       | VARCHAR   | 255      | DEFAULT NULL  | Chemin du fichier image                 |
| Movie    | trailer     | VARCHAR   | 255      | DEFAULT NULL  | URL du trailer (YouTube)                |
| Movie    | min_age     | INT       | 11       | DEFAULT NULL  | Âge minimum requis                      |
| Profile  | id          | INT       | 11       | PRIMARY KEY   | Identifiant unique                      |
| Profile  | name        | VARCHAR   | 255      | NOT NULL      | Nom du profil                           |
| Profile  | avatar      | VARCHAR   | 255      | DEFAULT NULL  | Chemin du fichier avatar                |
| Profile  | min_age     | INT       | 11       | DEFAULT 0     | Âge minimum du contrôle parental        |
| Favorite | id_profile  | INT       | 11       | FK, PK        | Référence à Profile                     |
| Favorite | id_movie    | INT       | 11       | FK, PK        | Référence à Movie                       |
| Favorite | created_at  | TIMESTAMP | -        | DEFAULT NOW() | Date d'ajout en favoris                 |

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
- **Clés étrangères** : Intégrité référentielle forcée au niveau base de données

### Performance

- **Clés primaires** : Index automatique sur tous les ID
- **Clés étrangères** : Index sur les colonnes de jointure
- **SELECT spécifiques** : Les requêtes ne retournent que les colonnes nécessaires
- **Groupement par catégorie** : Fait côté application (PHP) pour plus de flexibilité

---

## Fichier SQL de déploiement

- **le-flohic4.sql** : Contient la structure complète et les données initiales
- **SAE2_03.sql** : Archivé à titre de référence historique

Pour initialiser une nouvelle instance :

```bash
mysql -u utilisateur -p base_de_donnees < le-flohic4.sql
```

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

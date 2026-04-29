-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 29 avr. 2026 à 09:49
-- Version du serveur : 10.11.14-MariaDB-0+deb12u2
-- Version de PHP : 8.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `le-flohic4`
--

-- --------------------------------------------------------

--
-- Structure de la table `SAE203_Category`
--

CREATE TABLE `SAE203_Category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `SAE203_Category`
--

INSERT INTO `SAE203_Category` (`id`, `name`) VALUES
(1, 'Action'),
(2, 'Comédie'),
(3, 'Drame'),
(4, 'Science-fiction'),
(5, 'Animation'),
(6, 'Thriller'),
(7, 'Horreur'),
(8, 'Aventure'),
(9, 'Fantaisie'),
(10, 'Documentaire');

-- --------------------------------------------------------

--
-- Structure de la table `SAE203_Favorite`
--

CREATE TABLE `SAE203_Favorite` (
  `id_profile` int(11) NOT NULL,
  `id_movie` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `SAE203_Favorite`
--

INSERT INTO `SAE203_Favorite` (`id_profile`, `id_movie`, `created_at`) VALUES
(1, 17, '2026-04-29 07:19:57'),
(1, 46, '2026-04-29 08:14:40'),
(1, 47, '2026-04-29 08:31:22'),
(1, 59, '2026-04-29 08:17:15'),
(1, 61, '2026-04-29 08:36:40'),
(1, 73, '2026-04-29 08:32:57'),
(1, 78, '2026-04-29 08:31:39'),
(2, 79, '2026-04-29 08:34:15'),
(4, 38, '2026-04-29 08:36:07');

-- --------------------------------------------------------

--
-- Structure de la table `SAE203_Movie`
--

CREATE TABLE `SAE203_Movie` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `id_category` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `trailer` varchar(255) DEFAULT NULL,
  `min_age` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `SAE203_Movie`
--

INSERT INTO `SAE203_Movie` (`id`, `name`, `year`, `length`, `description`, `director`, `id_category`, `image`, `trailer`, `min_age`) VALUES
(7, 'Interstellar', 2014, 169, 'Un groupe d\'explorateurs voyage à travers un trou de ver pour sauver l\'humanité.', 'Christopher Nolan', 4, 'interstellar.jpg', 'https://www.youtube.com/embed/VaOijhK3CRU?si=76Ke4uw4LYjuLuQ6', 12),
(12, 'La Liste de Schindler', 1993, 195, 'Un industriel allemand sauve des milliers de Juifs pendant l\'Holocauste.', 'Steven Spielberg', 3, 'schindler.webp', 'https://www.youtube.com/embed/ONWtyxzl-GE?si=xC3ASGGPy5Ib-aPn', 16),
(17, 'Your Name', 2016, 107, 'Deux adolescents échangent leurs corps de manière mystérieuse.', 'Makoto Shinkai', 5, 'your_name.jpg', 'https://www.youtube.com/embed/AROOK45LXXg?si=aUQyGk2VMCb_ToUL', 10),
(27, 'Le Bon, la Brute et le Truand', 1966, 161, 'Trois hommes se lancent à la recherche d\'un trésor caché.', 'Sergio Leone', 8, 'bon_brute_truand.jpg', 'https://www.youtube.com/embed/WA1hCZFOPqs?si=TwNZAoM4oj4KpGja', 12),
(35, 'Indiana Jones : Les aventuriers de l\'arche perdue', 1981, 115, '1936. Parti à la recherche d\'une idole sacrée en pleine jungle péruvienne, l\'aventurier Indiana Jones échappe de justesse à une embuscade tendue par son plus coriace adversaire : le Français René Belloq.  Revenu à la vie civile à son poste de professeur universitaire d\'archéologie, il est mandaté par les services secrets et par son ami Marcus Brody, conservateur du National Museum de Washington, pour mettre la main sur le Médaillon de Râ, en possession de son ancienne amante Marion Ravenwood, désormais tenancière d\'un bar au Tibet.  Cet artefact égyptien serait en effet un premier pas sur le chemin de l\'Arche d\'Alliance, celle-là même où Moïse conserva les Dix Commandements. Une pièce historique aux pouvoirs inimaginables dont Hitler cherche à s\'emparer...', 'Steven Spielberg', 8, 'indiana1.jpg', 'https://www.youtube.com/embed/1E6VFegsQ0A?si=X8Tn5UzIllxX7ArQ', 12),
(36, 'Avatar', 2009, 162, 'Malgré sa paralysie, Jake Sully, un ancien marine immobilisé dans un fauteuil roulant, est resté un combattant au plus profond de son être. Il est recruté pour se rendre à des années-lumière de la Terre, sur Pandora, où de puissants groupes industriels exploitent un minerai rarissime destiné à résoudre la crise énergétique sur Terre. Parce que l\'atmosphère de Pandora est toxique pour les humains, ceux-ci ont créé le Programme Avatar, qui permet à des \" pilotes \" humains de lier leur esprit à un avatar, un corps biologique commandé à distance, capable de survivre dans cette atmosphère létale. Ces avatars sont des hybrides créés génétiquement en croisant l\'ADN humain avec celui des Na\'vi, les autochtones de Pandora.\r\n\r\nSous sa forme d\'avatar, Jake peut de nouveau marcher. On lui confie une mission d\'infiltration auprès des Na\'vi, devenus un obstacle trop conséquent à l\'exploitation du précieux minerai. Mais tout va changer lorsque Neytiri, une très belle Na\'vi, sauve la vie de Jake...', 'James Cameron', 4, 'avatar.jpeg', 'https://www.youtube.com/embed/MJ3Up7By5cw?si=CWXGj90JDNIeGI2i', 12),
(38, 'Arthur et les Minimoys', 2006, 94, 'Un garçon découvre un monde miniature et part à l’aventure pour sauver sa maison.', 'Luc Besson', 5, 'arthur.jpg', 'https://www.youtube.com/embed/nwYpQF4ESks?si=ruIgWBecVF5NqFvK', 0),
(39, 'Astérix et Obélix : Mission Cléopâtre', 2002, 107, 'Cléopâtre, piquée au vif par Jules César, lance un défi de construction d’un palais en trois mois.', 'Alain Chabat', 2, 'asterixcleopatre.jpg', 'https://www.youtube.com/embed/7Nd1ZCwB5PI?si=XBkpY0JI_AbEF2n7', 0),
(40, 'Bee Movie', 2007, 91, 'Une abeille intente un procès contre les humains pour exploitation du miel.', 'Simon J. Smith, Steve Hickner', 5, 'beemovie.jpg', 'https://www.youtube.com/embed/VONRQMx78YI?si=v1xnIcLjeu3i18a-', 0),
(41, 'Blade Runner 2049', 2017, 163, 'En 2049, la société est fragilisée par les tensions entre humains et réplicants.', 'Denis Villeneuve', 4, 'bladerunner2049.jpg', 'https://www.youtube.com/embed/O4C5cwSbXZ8?si=Y4fhZOS9YwVyFNl4', 0),
(42, 'Dragons', 2010, 98, 'Un jeune Viking se lie d’amitié avec un dragon.', 'Chris Sanders, Dean DeBlois', 5, 'dragons.jpg', 'https://www.youtube.com/embed/8rR_zgI-cmk?si=5looR7YRSDI4HlRt', 0),
(43, 'Fantastic Mr. Fox', 2009, 87, 'Un renard rusé vole les fermiers voisins.', 'Wes Anderson', 5, 'mrfox.jpg', 'https://www.youtube.com/embed/_AyJLSef4y4?si=ZOmiNxKNrBAIicf-', 0),
(44, 'I Robot', 2004, 115, 'Un policier enquête sur un robot impliqué dans un meurtre.', 'Alex Proyas', 4, 'Irobot.jpg', 'https://www.youtube.com/embed/PjECS38ZGWE?si=8f5svvKpHBmu5KvU', 10),
(45, 'Independence Day', 1996, 145, 'L’humanité affronte une invasion extraterrestre.', 'Roland Emmerich', 4, 'independay.jpg', 'https://www.youtube.com/embed/M7XM597XO94?si=ODIEOPLSkyjeMDNC', 10),
(46, 'John Wick : Chapitre 3', 2019, 130, 'John Wick est en fuite après avoir enfreint les règles du Continental.', 'Chad Stahelski', 1, 'jwc3.jpg', 'https://www.youtube.com/embed/M7XM597XO94?si=ODIEOPLSkyjeMDNC', 12),
(47, 'John Wick : Chapitre 4', 2023, 169, 'John Wick poursuit sa lutte contre la Grande Table.', 'Chad Stahelski', 1, 'jwc4.jpg', 'https://www.youtube.com/embed/6itn_8L6-Z8?si=Bjm9ZphsimN2eljF', 12),
(48, 'Joker', 2019, 122, 'L’histoire d’un homme marginal devenant Joker.', 'Todd Phillips', 3, 'joker.jpg', 'https://www.youtube.com/embed/HL3EuCbcOAo?si=uIYD5zHs_AR2EIgB', 16),
(49, 'Le Labyrinthe', 2014, 113, 'Un groupe d’adolescents tente de s’échapper d’un labyrinthe.', 'Wes Ball', 4, 'labyrinthe1.jpg', 'https://www.youtube.com/embed/LyPiCH_4Al4?si=HhFyKVb8Bt_aOfBN', 12),
(50, 'Le Monde de Nemo', 2003, 100, 'Un poisson clown traverse l’océan pour retrouver son fils.', 'Andrew Stanton', 5, 'nemo.jpg', 'https://www.youtube.com/embed/XtAnXfDIBqY?si=0u8EYu5xAQwGjYoU', 0),
(51, 'Men in Black', 1997, 98, 'Deux agents surveillent les extraterrestres.', 'Barry Sonnenfeld', 4, 'mib1.jpg', 'https://www.youtube.com/embed/UxUTTrU6PA4?si=TcAGoD3Tp9aDepdS', 10),
(52, 'Men in Black II', 2002, 88, 'L’agent J doit retrouver K.', 'Barry Sonnenfeld', 4, 'mib2.jpg', 'https://www.youtube.com/embed/DMHlNR6x2Sw?si=tzAlzDTHSSSSvv04', 10),
(53, 'Men in Black III', 2012, 106, 'Voyage dans le passé pour sauver l’humanité.', 'Barry Sonnenfeld', 4, 'mib3.jpg', 'https://www.youtube.com/embed/IyaFEBI_L24?si=zEWu2jkdJxVAMRWU', 10),
(54, 'Oppenheimer', 2023, 180, 'Biographie du créateur de la bombe atomique.', 'Christopher Nolan', 6, 'oppenheimer.jpg', 'https://www.youtube.com/embed/uYPbbksJxIg?si=lcOJQ_xm-cQj5NaW', 16),
(55, 'Pirates des Caraïbes', 2003, 143, 'Un pirate s’allie à un forgeron.', 'Gore Verbinski', 8, 'pirate.jpg', 'https://www.youtube.com/embed/WiZC7l0ovvk?si=kckHWMCeVow2KTBA', 10),
(56, 'Prince of Persia: The Sands of Time', 2010, 116, 'Un prince protège une dague magique.', 'Mike Newell', 8, 'princepersia.jpg', 'https://www.youtube.com/embed/mRDE5l-PJYY?si=seySexEKLXqRh47I', 10),
(57, 'Ring', 1998, 96, 'Une cassette maudite tue après 7 jours.', 'Hiroshi Takahashi', 7, 'ring.jpg', 'https://www.youtube.com/embed/mRDE5l-PJYY?si=seySexEKLXqRh47I', 12),
(58, 'Robots', 2005, 91, 'Un robot inventeur change le monde.', 'Chris Wedge', 5, 'robots.jpg', 'https://www.youtube.com/embed/zyLI71Z0RF4?si=ho-Z_7n8rWeUCp3q', 0),
(59, 'Rush Hour', 1998, 98, 'Un duo policier improbable.', 'Brett Ratner', 1, 'rushhour.jpg', 'https://www.youtube.com/embed/JMiFsFQcFLE?si=rvrLiaPAu_qxTSoJ', 12),
(60, 'Rush Hour II', 2001, 90, 'Enquête à Hong Kong.', 'Brett Ratner', 1, 'rushhour2.jpg', 'https://www.youtube.com/embed/SCTzYY95Aw4?si=fHMfExpl1CUEXCEc', 12),
(61, 'Scary Movie 1', 2000, 88, 'Parodie de films d’horreur.', 'Shawn Wayans, Marlon Wayans', 2, 'scarymovie1.jpg', 'https://www.youtube.com/embed/SzpGYrrcJZw?si=Y5bPYvzQ1d8f6wq5', 16),
(62, 'Scary Movie 2', 2001, 83, 'Suite parodique.', 'Shawn Wayans, Marlon Wayans', 2, 'scariemovie2.jpg', 'https://www.youtube.com/embed/zCFZUZxBVuI?si=EMsRcZ_OeY1EWyDy', 16),
(63, 'Scary Movie 3', 2003, 85, 'Parodie encore plus absurde.', 'Pat Proft, Craig Mazin', 2, 'scariemovie3.jpg', 'https://www.youtube.com/embed/O21wD8Tzr2k?si=jQpU8uTda80G3-Te', 16),
(64, 'Scary Movie 4', 2006, 83, 'Suite délirante.', 'Craig Mazin, Pat Proft', 2, 'scariemovie4.jpg', 'https://www.youtube.com/embed/-Bwr6LB5Dqw?si=CsMrhDiOITKsnYdy', 16),
(65, 'Scary Movie 5', 2013, 89, 'Nouvelle parodie.', 'John Aboud, Michael Colton', 2, 'scariemovie5.jpg', 'https://www.youtube.com/embed/RMDZ8M47j0I?si=s8Xupn5nMwQr08Vj', 16),
(66, 'Sonic 1, le film', 2020, 99, 'Le hérisson bleu arrive sur Terre.', 'Jeff Fowler', 8, 'sonic1.jpg', 'https://www.youtube.com/embed/szby7ZHLnkA?si=H1AHTqHHQvM97Yo_', 0),
(67, 'Sonic 2, le film', 2022, 122, 'Nouvelle aventure de Sonic.', 'Jeff Fowler', 8, 'sonic2.jpg', 'https://www.youtube.com/embed/G5kzUpWAusI?si=mCmGW5k7YT2XYVDI', 0),
(68, 'Sonic 3, le film', 2024, 109, 'Affrontement contre Shadow.', 'Jeff Fowler', 8, 'sonic3.jpg', 'https://www.youtube.com/embed/qSu6i2iFMO0?si=-hcAWWSTQLedm69Z', 0),
(69, 'Spider-Man: Across the Spider-Verse', 2023, 141, 'Voyage dans le multivers.', 'Phil Lord, Christopher Miller', 5, 'spideracrossverse.jpg', 'https://www.youtube.com/embed/cqGjhVJWtEg?si=8Se4TUReWG6e--F0', 0),
(70, 'Spider-Man: Into the Spider-Verse', 2018, 117, 'Origine de Miles Morales.', 'Phil Lord, Rodney Rothman', 5, 'spiderintoverse.jpg', 'https://www.youtube.com/embed/cqGjhVJWtEg?si=8Se4TUReWG6e--F0', 0),
(71, 'Star Wars, épisode I : La Menace fantôme', 1999, 136, 'Origine d’Anakin Skywalker.', 'George Lucas', 4, 'starwars1.jpg', 'https://www.youtube.com/embed/bD7bpG-zDJQ?si=W5B7P99yUqF1SWtJ', 0),
(72, 'Super Mario Bros, le film', 2023, 92, 'Mario part sauver Luigi.', 'Matthew Fogel', 5, 'mariobros.jpg', 'https://www.youtube.com/embed/iwst-UZn3wM?si=pntF_OzGOCKgcapw', 0),
(73, 'Super Mario Galaxy, le film', 2026, 99, 'Aventure intergalactique.', 'Matthew Fogel', 5, 'mariogalaxy.jpg', 'https://www.youtube.com/embed/BHRN7Oufjw4?si=vwajVGtRqbUUNsIf', 0),
(74, 'The Mask', 1994, 101, 'Un masque magique donne des pouvoirs.', 'Chuck Russell', 2, 'themask.jpg', 'https://www.youtube.com/embed/LZl69yk5lEY?si=FHD0YKy_I9Y8NS7x', 10),
(75, 'The Matrix', 1999, 136, 'Une simulation contrôlée par des machines.', 'Lana Wachowski, Lilly Wachowski', 4, 'matrix1.jpg', 'https://www.youtube.com/embed/m8e-FF8MsqU?si=iPU77uTN7mgJbyos', 12),
(76, 'The Truman Show', 1998, 103, 'Un homme découvre qu’il est filmé.', 'Peter Weir', 4, 'trumanshow.jpg', 'https://www.youtube.com/embed/dlnmQbPGuls?si=f6uF2iGb7rcNgbet', 10),
(77, 'Titanic', 1997, 194, 'Histoire d’amour sur le Titanic.', 'James Cameron', 8, 'titanic.jpg', 'https://www.youtube.com/embed/CHekzSiZjrY?si=TW7cGyQ78jHwp2km', 12),
(78, 'Top Gun', 1986, 110, 'Pilotes de chasse en compétition.', 'Jim Cash, Jack Epps Jr.', 1, 'topgun.jpg', 'https://www.youtube.com/embed/xa_z57UatDY?si=pZuh4guhgxVrxRNA', 12),
(79, 'Top Gun: Maverick', 2022, 131, 'Retour de Maverick.', 'Christopher McQuarrie, Ehren Kruger', 1, 'topgunmav.jpg', 'https://www.youtube.com/embed/qSqVVswa420?si=cabyT0kamRmRbBjY', 0),
(80, 'WALL-E', 2008, 98, 'Un robot solitaire découvre l’amour.', 'Andrew Stanton', 4, 'walle.jpg', 'https://www.youtube.com/embed/CZ1CATNbXg0?si=XZxGp0ckvYLvjRci', 0);

-- --------------------------------------------------------

--
-- Structure de la table `SAE203_Profile`
--

CREATE TABLE `SAE203_Profile` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `min_age` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `SAE203_Profile`
--

INSERT INTO `SAE203_Profile` (`id`, `name`, `avatar`, `min_age`) VALUES
(1, 'Ylf36', '', 18),
(2, 'Anaé', '', 0),
(3, 'Nono', '', 12),
(4, 'Nana', '', 16);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `SAE203_Category`
--
ALTER TABLE `SAE203_Category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `SAE203_Favorite`
--
ALTER TABLE `SAE203_Favorite`
  ADD PRIMARY KEY (`id_profile`,`id_movie`),
  ADD KEY `fk_favorite_movie` (`id_movie`);

--
-- Index pour la table `SAE203_Movie`
--
ALTER TABLE `SAE203_Movie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_category` (`id_category`);

--
-- Index pour la table `SAE203_Profile`
--
ALTER TABLE `SAE203_Profile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `SAE203_Category`
--
ALTER TABLE `SAE203_Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `SAE203_Movie`
--
ALTER TABLE `SAE203_Movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT pour la table `SAE203_Profile`
--
ALTER TABLE `SAE203_Profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `SAE203_Favorite`
--
ALTER TABLE `SAE203_Favorite`
  ADD CONSTRAINT `fk_favorite_movie` FOREIGN KEY (`id_movie`) REFERENCES `SAE203_Movie` (`id`),
  ADD CONSTRAINT `fk_favorite_profile` FOREIGN KEY (`id_profile`) REFERENCES `SAE203_Profile` (`id`);

--
-- Contraintes pour la table `SAE203_Movie`
--
ALTER TABLE `SAE203_Movie`
  ADD CONSTRAINT `movie_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `SAE203_Category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

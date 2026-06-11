-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : ven. 05 juin 2026 à 12:45
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `animaux`
--

-- --------------------------------------------------------

--
-- Structure de la table `animaux`
--

CREATE TABLE `animaux` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `age_unite` varchar(10) DEFAULT 'ans'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `animaux`
--

INSERT INTO `animaux` (`id`, `nom`, `type`, `age`, `image`, `description`, `id_utilisateur`, `age_unite`) VALUES
(9, 'Mimi', 'Chat', 4, '6a0b0e5f1a7e6.avif', 'Chat adorable âgé de 4 ans, situé à Tanger. Très gentil et cherche une nouvelle famille.', 6, 'ans'),
(11, 'chien', 'Chien', 1, '6a0c6c267fff4.jpg', 'Chien adorable âgé de 5 ans, situé à Tanger. Très gentil, joueur', 10, 'ans'),
(17, 'chat', 'Chat', 3, '6a0d82ea6967f.jpg', 'Petit chaton roux de 3 mois, très propre et joueur. Actuellement à Tanger. Si vous êtes intéressé pour l\'adopter, contactez-moi sur WhatsApp.', 10, 'mois'),
(18, 'calopsitte', 'Oiseau', 1, '6a0d926ea8ab8.jpg', '\"Magnifique calopsitte de 1 an, très vive et sociable. Actuellement à Tanger. Si vous êtes intéressé pour l\'adopter, contactez-moi sur WhatsApp.', 10, 'ans'),
(19, 'mimita', 'Chat', 1, '6a0d960fad6e4.jpg', 'Petit chaton gris de 1 mois, très propre et joueur. Actuellement à Tanger. Si vous êtes intéressé pour l\'adopter, contactez-moi sur WhatsApp.', 10, 'mois'),
(20, 'les chatons', 'Chat', 1, '6a0d970b6ff07.jpg', 'Magnifiques chatons de 1 mois, très propres et joueurs. Plusieurs couleurs disponibles. Actuellement à Tanger. Si vous êtes intéressé pour adopter l\'un d\'entre eux, contactez-moi sur WhatsApp', 10, 'mois'),
(22, 'Canari', 'Oiseau', 7, '6a0ed23717a83.jpg', 'Beau canari jaune, très calme et chanteur. Actuellement à Tanger. Si vous êtes intéressé pour l\'adopter, contactez-moi sur WhatsApp.', 10, 'mois'),
(24, 'chien', 'Chien', 2, '6a1d69ab02767.jpg', 'Joli petit chiot de 2 mois, très mignon et joueur. Actuellement à Tanger. Si vous êtes intéressé pour l\'adopter, contactez-moi sur WhatsApp.', 6, 'mois');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `contenu` text DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_animal` int(11) DEFAULT NULL,
  `date_commentaire` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `contenu`, `id_utilisateur`, `id_animal`, `date_commentaire`) VALUES
(3, 'bien', 7, 9, '2026-05-18 13:46:18');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_envoi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `nom`, `email`, `message`, `date_envoi`) VALUES
(1, 'Yassine', 'yassine@gmail.com', 'Site très bien 👍', '2026-05-14 08:52:48');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `telephone`, `whatsapp`, `role`) VALUES
(1, 'Ahmed', 'ahmed@gmail.com', NULL, NULL, '212634545456', 'user'),
(2, 'Sara', 'sara@gmail.com', NULL, NULL, '212623234454', 'user'),
(3, 'souhaila', 'souhaila4@gmail.com', '2222', NULL, NULL, 'user'),
(4, 'souhaila', 'souhaila6@gmail.com', '$2y$10$nTAvwoBHV2UhdA0Kme2yduqVbqaZygMjuygSHCSNG7.vswO0oydly', NULL, NULL, 'user'),
(5, 'souhaila', 'souhaila11@gmail.com', '1111', NULL, NULL, 'user'),
(6, 'souhaila', 'souhaila77@gmail.com', '$2y$10$MYa5cF7aj.NgVidzFQTTXOGZko7yTHt31KDtS4dDpe0xSUoq3y/4u', '0604684773', '0604684773', 'admin'),
(7, 'mouhamed', 'mouhamed11@gmail.com', '$2y$10$Y40pUeLx2qLLcJ7AGoZpyeDdziF/0s7nRmbdii4nRRAQ9Oupl0mAC', '0604684773', '0676765434', 'user'),
(8, 'ahmed', 'ahmed22@gmail.com', '$2y$10$JH0Qch5NCzkTogQlH5N0QOPhTZ26Nd1pYzkZzCqAjf0ysfEdJzDZe', '06989876', '0676767676', 'user'),
(9, 'sara', 'sara2@gmail.com', '$2y$10$anayOT3/ezi39PZJMMGu7uMkRjTsu0JC80YxwgaLCh41jT0DXd3FC', '0604684773', '0676765434', 'user'),
(10, 'kaoutar', 'kaoutar33@gmail.com', '$2y$10$F1PdGwYWjxi10j42GR1UuejcsY5/Go1t87mzaxIsvFNwqNbmhK/JS', '066654545', '066654545', 'user'),
(11, 'mouhamed', 'mouhamed111@gmail.com', '$2y$10$C39YR.FtWIWalfJ3TFk1p.xworo6UGwSjUUToKO6qZruasKOFTSzy', '0604684773', '0676765434', 'user'),
(12, 'souhaila', 'souhailalh77@gmail.coom', '$2y$10$NtsBsOUoeg.wFbrnU5mjPexQffGlcwaheuH51l.VmY6eWSCvW6C8a', '+212604694773', '0676765432', 'user');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `animaux`
--
ALTER TABLE `animaux`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_animal` (`id_animal`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `animaux`
--
ALTER TABLE `animaux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `animaux`
--
ALTER TABLE `animaux`
  ADD CONSTRAINT `animaux_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `animaux_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`id_animal`) REFERENCES `animaux` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

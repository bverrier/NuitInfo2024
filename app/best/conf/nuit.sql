-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
                         `id` int(11) NOT NULL,
                         `prenom` text NOT NULL,
                         `nom` text NOT NULL,
                         `mail` varchar(255) NOT NULL,
                         `login` varchar(30) NOT NULL,
                         `is_admin` tinyint(4) NOT NULL,
                         `is_activ` tinyint(4) NOT NULL,
                         `date_creation_compte` datetime NOT NULL DEFAULT current_timestamp(),
                         `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `users` (`id`, `prenom`, `nom`, `mail`, `login`, `is_admin`, `is_activ`, `date_creation_compte`, `mot_de_passe`) VALUES
    (1, 'Admin', 'Admin', 'no-reply@admin.fr', 'admin', 1, 1, '2024-10-15 08:42:24', '$2y$10$jPfALLctyabOqdqjAHJNJeQO8AmJeDR.6Qq21b3vLYzu018r2Ky8y');

-- --------------------------------------------------------

--
-- Index pour la table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
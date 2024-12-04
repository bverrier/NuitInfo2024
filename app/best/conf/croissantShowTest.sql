-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 18, 2024 at 04:10 PM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `croissantShowTest`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `date_event` date NOT NULL,
  `nb_croissant_total` int(11) NOT NULL,
  `is_vacance` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `date_event`, `nb_croissant_total`, `is_vacance`) VALUES
(1, '2024-01-03', 0, 0),
(2, '2024-01-10', 0, 0),
(3, '2024-01-17', 0, 0),
(4, '2024-01-24', 0, 0),
(5, '2024-01-31', 0, 0),
(6, '2024-02-07', 0, 0),
(7, '2024-02-14', 0, 0),
(8, '2024-02-21', 0, 0),
(9, '2024-02-28', 0, 0),
(10, '2024-03-06', 0, 0),
(11, '2024-03-13', 0, 0),
(12, '2024-03-20', 0, 0),
(13, '2024-03-27', 0, 0),
(14, '2024-04-03', 0, 0),
(15, '2024-04-10', 0, 0),
(16, '2024-04-17', 0, 0),
(17, '2024-04-24', 0, 0),
(18, '2024-05-01', 0, 0),
(19, '2024-05-08', 0, 0),
(20, '2024-05-15', 0, 0),
(21, '2024-05-22', 0, 0),
(22, '2024-05-29', 0, 0),
(23, '2024-06-05', 0, 0),
(24, '2024-06-12', 0, 0),
(25, '2024-06-19', 0, 0),
(26, '2024-06-26', 0, 0),
(27, '2024-07-03', 0, 0),
(28, '2024-07-10', 0, 0),
(29, '2024-07-17', 0, 0),
(30, '2024-07-24', 0, 0),
(31, '2024-07-31', 0, 0),
(32, '2024-08-07', 0, 0),
(33, '2024-08-14', 0, 0),
(34, '2024-08-21', 0, 0),
(35, '2024-08-28', 0, 0),
(36, '2024-09-04', 0, 0),
(37, '2024-09-11', 0, 0),
(38, '2024-09-18', 0, 0),
(39, '2024-09-25', 0, 0),
(40, '2024-10-02', 0, 0),
(41, '2024-10-09', 0, 0),
(42, '2024-10-16', 0, 0),
(43, '2024-10-23', 0, 0),
(44, '2024-10-30', 0, 0),
(45, '2024-11-06', 0, 0),
(46, '2024-11-13', 0, 0),
(47, '2024-11-20', 0, 0),
(48, '2024-11-27', 0, 0),
(49, '2024-12-04', 0, 0),
(50, '2024-12-11', 0, 0),
(51, '2024-12-18', 0, 0),
(52, '2024-12-25', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `messagerie`
--

CREATE TABLE `messagerie` (
  `id` int(11) NOT NULL,
  `usr_from` int(11) NOT NULL,
  `usr_to` int(11) NOT NULL,
  `msg` varchar(255) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

CREATE TABLE `participant` (
  `id_user` int(11) NOT NULL,
  `id_event` int(11) NOT NULL,
  `is_responsable` tinyint(4) NOT NULL,
  `is_present` tinyint(4) NOT NULL,
  `nb_croissant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
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
  `mot_de_passe` varchar(255) NOT NULL,
  `croissant_buy` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `prenom`, `nom`, `mail`, `login`, `is_admin`, `is_activ`, `date_creation_compte`, `mot_de_passe`, `croissant_buy`) VALUES
(1, 'Admin', 'Admin', 'no-reply@croissant.fr', 'admin', 1, 1, '2024-10-15 08:42:24', '$2y$10$jPfALLctyabOqdqjAHJNJeQO8AmJeDR.6Qq21b3vLYzu018r2Ky8y', 50000);

-- --------------------------------------------------------

--
-- Table structure for table `vacances`
--

CREATE TABLE `vacances` (
  `id` int(11) NOT NULL,
  `debut` date NOT NULL,
  `fin` date NOT NULL,
  `annee` int(11) NOT NULL,
  `Nom` varchar(150) NOT NULL,
  `is_activ` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messagerie`
--
ALTER TABLE `messagerie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_from` (`usr_from`),
  ADD KEY `fk_user_to` (`usr_to`);

--
-- Indexes for table `participant`
--
ALTER TABLE `participant`
  ADD PRIMARY KEY (`id_user`,`id_event`),
  ADD KEY `fk_event` (`id_event`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vacances`
--
ALTER TABLE `vacances`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `messagerie`
--
ALTER TABLE `messagerie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vacances`
--
ALTER TABLE `vacances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messagerie`
--
ALTER TABLE `messagerie`
  ADD CONSTRAINT `fk_user_from` FOREIGN KEY (`usr_from`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user_to` FOREIGN KEY (`usr_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `fk_event` FOREIGN KEY (`id_event`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `fk_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

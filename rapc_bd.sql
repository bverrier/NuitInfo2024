SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+01:00";


--
-- Base de données :  `gazette_bd`
--

CREATE USER IF NOT EXISTS 'rapc_user'@'%' IDENTIFIED BY 'rapc_pass';

CREATE DATABASE IF NOT EXISTS `rapc_bd` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

GRANT SELECT, INSERT, UPDATE, DELETE ON `rapc_bd`.* TO "rapc_user"@"%";

USE `rapc_bd`;

-- --------------------------------------------------------

--
-- Table structure for table `CAS`
--

CREATE TABLE `CAS` (
  `ID_CAS` int(3) NOT NULL,
  `ID_PB` int(3) NOT NULL,
  `ID_SOL` int(3) DEFAULT NULL,
  `MALADIE` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `CAS`
--

INSERT INTO `CAS` (`ID_CAS`, `ID_PB`, `ID_SOL`, `MALADIE`) VALUES
(1, 1, 1, 'VARICELLE'),
(2, 2, 1, 'RHINOPHARYNGITE'),
(3, 3, 2, 'GASTRO ENTERITE');

-- --------------------------------------------------------

--
-- Table structure for table `PROBLEMES`
--

CREATE TABLE `PROBLEMES` (
  `ID_PB` int(3) NOT NULL,
  `AGE` int(3) NOT NULL,
  `SEXE` char(1) NOT NULL,
  `POIDS` float NOT NULL,
  `IMC` float NOT NULL,
  `TEMP` float NOT NULL,
  `VOMISSEMENTS` tinyint(1) NOT NULL,
  `TOUX` tinyint(1) NOT NULL,
  `MIGRAINE` tinyint(1) NOT NULL,
  `GORGE` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `PROBLEMES`
--

INSERT INTO `PROBLEMES` (`ID_PB`, `AGE`, `SEXE`, `POIDS`, `IMC`, `TEMP`, `VOMISSEMENTS`, `TOUX`, `MIGRAINE`, `GORGE`) VALUES
(1, 5, 'M', 20, 20, 39, 0, 0, 0, 0),
(2, 22, 'F', 50, 20, 39, 0, 1, 1, 1),
(3, 29, 'F', 55, 21, 38, 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `SOLUTIONS`
--

CREATE TABLE `SOLUTIONS` (
  `ID_SOL` int(3) NOT NULL,
  `MEDIC1` varchar(255) NOT NULL,
  `DUREE1` int(2) NOT NULL,
  `POSOLOGIE1` varchar(255) NOT NULL,
  `MEDIC2` varchar(255) DEFAULT NULL,
  `DUREE2` int(2) DEFAULT NULL,
  `POSOLOGIE2` varchar(255) DEFAULT NULL,
  `MEDIC3` varchar(255) DEFAULT NULL,
  `DUREE3` int(2) DEFAULT NULL,
  `POSOLOGIE3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `SOLUTIONS`
--

INSERT INTO `SOLUTIONS` (`ID_SOL`, `MEDIC1`, `DUREE1`, `POSOLOGIE1`, `MEDIC2`, `DUREE2`, `POSOLOGIE2`, `MEDIC3`, `DUREE3`, `POSOLOGIE3`) VALUES
(1, 'EFFERALGAN', 3, '1 COMPRIME A CHAQUE REPAS', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'SMECTA', 4, 'UN SACHET A CHAQUE REPAS', NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `CAS`
--
ALTER TABLE `CAS`
  ADD PRIMARY KEY (`ID_CAS`),
  ADD KEY `CAS_PB` (`ID_PB`),
  ADD KEY `CAS_SOL` (`ID_SOL`);

--
-- Indexes for table `PROBLEMES`
--
ALTER TABLE `PROBLEMES`
  ADD PRIMARY KEY (`ID_PB`);

--
-- Indexes for table `SOLUTIONS`
--
ALTER TABLE `SOLUTIONS`
  ADD PRIMARY KEY (`ID_SOL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `CAS`
--
ALTER TABLE `CAS`
  MODIFY `ID_CAS` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `PROBLEMES`
--
ALTER TABLE `PROBLEMES`
  MODIFY `ID_PB` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `SOLUTIONS`
--
ALTER TABLE `SOLUTIONS`
  MODIFY `ID_SOL` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CAS`
--
ALTER TABLE `CAS`
  ADD CONSTRAINT `CAS_PB` FOREIGN KEY (`ID_PB`) REFERENCES `PROBLEMES` (`ID_PB`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `CAS_SOL` FOREIGN KEY (`ID_SOL`) REFERENCES `SOLUTIONS` (`ID_SOL`) ON DELETE NO ACTION ON UPDATE NO ACTION;

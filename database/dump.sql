-- Dumping database structure for primeiro_trimestre
CREATE DATABASE IF NOT EXISTS `primeiro_trimestre` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `primeiro_trimestre`;

-- Dumping structure for table primeiro_trimestre.role
CREATE TABLE IF NOT EXISTS `role` (
  `idRole` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `isActive` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idRole`),
  INDEX `fk_role_idRole_idx` (`idRole`),
  UNIQUE (`name`),
  UNIQUE (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table primeiro_trimestre.role: ~0 rows (approximately)

-- Dumping structure for table primeiro_trimestre.user
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mediaUrl` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `isActive` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idUser`),
  INDEX `fk_user_idUser_idx` (`idUser`),
  UNIQUE (`username`),
  UNIQUE (`email`),
  UNIQUE (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping structure for table primeiro_trimestre.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `FK_idUser` int(11) NOT NULL,
  `FK_idRole` int(11) NOT NULL,
  `isActive` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`FK_idUser`, `FK_idRole`),
  INDEX `fk_user_role_idUser_idx` (`FK_idUser`),
  INDEX `fk_user_role_idRole_idx` (`FK_idRole`),
  CONSTRAINT `fk_user_role_idUser` FOREIGN KEY (`FK_idUser`) REFERENCES `user` (`idUser`),
  CONSTRAINT `fk_user_role_idRole` FOREIGN KEY (`FK_idRole`) REFERENCES `role` (`idRole`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table primeiro_trimestre.user_role: ~0 rows (approximately)
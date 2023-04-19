-- Adminer 4.7.9 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `id_event` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_user` int(8) NOT NULL,
  `id_stade` int(11) NOT NULL,
  `id_type_stade` int(11) DEFAULT NULL,
  `date_start` varchar(250) DEFAULT NULL,
  `date_end` varchar(250) DEFAULT NULL,
  `commentaire` longtext DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_event`),
  UNIQUE KEY `id_user_nom_stade_date_start_date_end` (`id_user`,`id_stade`,`date_start`,`date_end`),
  KEY `id_stade` (`id_stade`),
  KEY `id_type_stade` (`id_type_stade`),
  CONSTRAINT `event_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `event_ibfk_4` FOREIGN KEY (`id_stade`) REFERENCES `stade` (`id_stade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `event_ibfk_6` FOREIGN KEY (`id_type_stade`) REFERENCES `stadetype` (`id_type_stade`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id_groups` int(8) NOT NULL AUTO_INCREMENT,
  `nom_groups` varchar(250) NOT NULL,
  PRIMARY KEY (`id_groups`),
  UNIQUE KEY `nom` (`nom_groups`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `groups` (`id_groups`, `nom_groups`) VALUES
(1,	'admin'),
(2,	'user');

DROP TABLE IF EXISTS `stade`;
CREATE TABLE `stade` (
  `id_stade` int(11) NOT NULL AUTO_INCREMENT,
  `id_type_stade` int(11) NOT NULL,
  `nom` varchar(250) NOT NULL,
  `lundi` varchar(250) DEFAULT NULL,
  `mardi` varchar(250) DEFAULT NULL,
  `mercredi` varchar(250) DEFAULT NULL,
  `jeudi` varchar(250) DEFAULT NULL,
  `vendredi` varchar(250) DEFAULT NULL,
  `samedi` varchar(250) DEFAULT NULL,
  `dimanche` varchar(250) DEFAULT NULL,
  `dateend` varchar(250) DEFAULT NULL,
  `actif` int(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_stade`),
  UNIQUE KEY `type_nom` (`id_type_stade`,`nom`),
  CONSTRAINT `stade_ibfk_1` FOREIGN KEY (`id_type_stade`) REFERENCES `stadetype` (`id_type_stade`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `stade` (`id_stade`, `id_type_stade`, `nom`, `lundi`, `mardi`, `mercredi`, `jeudi`, `vendredi`, `samedi`, `dimanche`, `dateend`, `actif`, `timestamp`) VALUES
(1,	1,	'Roland-Garros',	'14h00-16h00',	'14h00-16h00',	'8h00-10h00,10h00-12h00',	'10h00-12h00,14h00-16h00',	'8h00-10h00,14h00-16h00',	'',	'',	'2023-10-05',	1,	'2023-01-23 20:42:19'),
(2,	2,	'Vélodrome',	'',	'8h00-10h00,14h00-16h00',	'8h00-10h00,10h00-12h00',	'8h00-10h00,10h00-12h00',	'8h00-10h00',	'',	'',	'2023-06-30',	1,	'2023-01-23 20:42:19'),
(3,	1,	'US Open',	'8h00-10h00,14h00-16h00',	'8h00-10h00,10h00-12h00',	'8h00-10h00',	'8h00-10h00,10h00-12h00',	'10h00-12h00,14h00-16h00',	'',	'',	'2023-09-30',	1,	'2023-01-23 20:42:19'),
(4,	3,	'Eden Park',	'10h00-12h00',	'',	'',	'',	'',	'',	'',	'2023-02-23',	1,	'2023-01-30 00:23:33'),
(5,	3,	'Cardiff',	'10h00-12h00',	'10h00-12h00',	'10h00-12h00',	'10h00-12h00',	'10h00-12h00',	'',	'',	'2023-09-26',	1,	'2023-04-17 22:50:24'),
(6,	2,	'San Siro',	'10h00-12h00',	'',	'10h00-12h00',	'',	'10h00-12h00',	'',	'',	'2023-10-29',	1,	'2023-04-17 22:52:26');

DROP TABLE IF EXISTS `stadetype`;
CREATE TABLE `stadetype` (
  `id_type_stade` int(11) NOT NULL AUTO_INCREMENT,
  `nom_type` varchar(250) NOT NULL,
  PRIMARY KEY (`id_type_stade`),
  UNIQUE KEY `id_type_nom` (`id_type_stade`,`nom_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `stadetype` (`id_type_stade`, `nom_type`) VALUES
(1,	'tennis'),
(2,	'foot'),
(3,	'rugby');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(250) NOT NULL,
  `prenom` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `token` varchar(250) DEFAULT NULL,
  `tel` varchar(250) DEFAULT NULL,
  `adresse` varchar(250) DEFAULT NULL,
  `actif` varchar(250) NOT NULL,
  `id_groups` int(8) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  KEY `id_groups` (`id_groups`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_groups`) REFERENCES `groups` (`id_groups`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id_user`, `nom`, `prenom`, `password`, `email`, `token`, `tel`, `adresse`, `actif`, `id_groups`, `timestamp`) VALUES
(1,	'admin',	'admin',	'$2y$13$8J/ligDdNAxHi890c8YOCOyDN84nqmQSkkygWZRxkQEln9J1B.1AG',	'admin01@rsvsport.com',	'',	'061306130613',	'',	'1',	1,	'2023-01-23 19:36:04'),
(2,	'user',	'user',	'$2y$13$WbjKvmLNRkAfypFGZ5qIpOD./zJ/0SpJq.jGeBUx7aAuiDaSk2H2i',	'user01@rsvsport.com',	'',	'0601130113',	'',	'1',	2,	'2023-02-01 20:36:08');

-- 2023-04-19 19:41:16

CREATE DATABASE  IF NOT EXISTS `litrix` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `litrix`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: litrix
-- ------------------------------------------------------
-- Server version	5.5.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `grupo`
--

DROP TABLE IF EXISTS `grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Grupo` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo`
--

LOCK TABLES `grupo` WRITE;
/*!40000 ALTER TABLE `grupo` DISABLE KEYS */;
INSERT INTO `grupo` VALUES (1,'app'),(2,'default');
/*!40000 ALTER TABLE `grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `apellidos` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `usuario` varchar(20) COLLATE latin1_spanish_ci DEFAULT NULL,
  `pass` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `role` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `grupo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`),
  KEY `fk_usuarios_grupo1_idx` (`grupo`),
  CONSTRAINT `fk_usuarios_grupo1` FOREIGN KEY (`grupo`) REFERENCES `grupo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin','admin','litrix.mailer@gmail.com','admin','5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==','ROLE_ADMIN',1,2),(26,'test','test','test@test.es','aaaaa','5l6vniUTvA2BkGQn36NDxxsglxeKQGpUI48wOYoLon45s+MlvjaCJz1qVSaNRaX0u562QmDbsefxCMT3WqBxrQ==','ROLE_USER',1,1),(27,'TEST2','testte@t.es','t@t.es','ter','hg6SR5HAv9efHtp5q2pT7DzRnG2x6Rk1AykrtJiblJS/eyCnGDJzG/QDSuYc3rPEK94FaUBen8A+7/rTpSBxzA==','ROLE_USER',1,1),(30,'qwqwq','qwq@qwq.re','erere@rere.es','retrete','zKgdNE7BHguhCKv+42U0WnRCbF8DgMJRQCi2aqzk3vMGfP0ZNIIes6SK+aE6cZtlVm4rEKfY4earvqcNGIMuSA==','ROLE_USER',1,1),(35,'test343','erere','erer@wewew.es','erererere','Fq11T7mNRgeUoy9RRu3SiwLmLkEVxoPnjs66lEk5qQpCigoZjIQ7auzKWjT/pn6YYj8u8aBV0lspicGrbrQQ5g==','ROLE_USER',1,1),(36,'test45454','aepllido','a@a.es','34343','BOx85rOK/EC5YLkg4flT5Rty4ZcyRug8773nbTMwM+TUArss8fJIOQreYWt8RQ3q5awsFUvqdzUpV9489FQxuw==','ROLE_USER',1,1),(37,'rob','rob','rob@rob.es','rob','Eti36Ru/pWG6WfoIPiDFUBxUuyvgMA4L8+LLuGbGyqV9ATuT9brCWPchBqX5vFTF+DgntacecW+sSGD+GZts2A==','ROLE_USER',1,1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'litrix'
--

--
-- Dumping routines for database 'litrix'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-21  9:06:56

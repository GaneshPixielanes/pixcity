-- MySQL dump 10.13  Distrib 5.7.17, for Win32 (AMD64)
--
-- Host: 127.0.0.1    Database: pixielanes
-- ------------------------------------------------------
-- Server version	5.7.17

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
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('20180303171807'),('20180303180927'),('20180303184752'),('20180303191437'),('20180303201208'),('20180308235638'),('20180309202429'),('20180312151103'),('20180315093003'),('20180315112544'),('20180315141202'),('20180316164623'),('20180318140555'),('20180318141042'),('20180318160622'),('20180318162217'),('20180318184914'),('20180320131050'),('20180320132609'),('20180320140050'),('20180320141533'),('20180320142000'),('20180320173754'),('20180320182217'),('20180322185938'),('20180323120137'),('20180323181937'),('20180323190510'),('20180324183332'),('20180326134023'),('20180326150510'),('20180328125634'),('20180328175411'),('20180328181636'),('20180328181729'),('20180330121359'),('20180331095956'),('20180331103048'),('20180331191043'),('20180403133215'),('20180404123712'),('20180404151246'),('20180404151357'),('20180404151414'),('20180404154417'),('20180404155516'),('20180405120141'),('20180405163332'),('20180405174526'),('20180406125800'),('20180406140336'),('20180406161252'),('20180406164803'),('20180406175527'),('20180406202107'),('20180407102623'),('20180409111401'),('20180409141416'),('20180410142742'),('20180410153550'),('20180411115813'),('20180412133314'),('20180412135110'),('20180412140709'),('20180412160212'),('20180412171453'),('20180412174929'),('20180413131524'),('20180413134403'),('20180413172624'),('20180413180340'),('20180415085548'),('20180415175015'),('20180415175610'),('20180415191123');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_address`
--

DROP TABLE IF EXISTS `pxl_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_address`
--

LOCK TABLES `pxl_address` WRITE;
/*!40000 ALTER TABLE `pxl_address` DISABLE KEYS */;
INSERT INTO `pxl_address` VALUES (2,'28 Rue de l\'Avenir','92170','Vanves','France','',''),(3,'Bois de Turelle','91490','Courances','France','48.4356867','2.501465199999984'),(4,'19 La ville Neuve','22800','Saint-Donan','France','48.4511744','-2.9212552999999843');
/*!40000 ALTER TABLE `pxl_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_admin`
--

DROP TABLE IF EXISTS `pxl_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_admin`
--

LOCK TABLES `pxl_admin` WRITE;
/*!40000 ALTER TABLE `pxl_admin` DISABLE KEYS */;
INSERT INTO `pxl_admin` VALUES (1,'Adrien','Lamotte','adrien.lamotte@gmail.com','$2y$13$uSx4m2aV3wRtrO8pmS/ZWec7Gb0dXVjXEidL3oezobglOcUyDIvsC','[\"ROLE_ADMIN\", \"ROLE_MODERATOR\"]','2018-03-05 19:55:37','2018-03-09 21:45:43'),(6,'Jane','Doe','janedoe@gmail.com','$2y$13$qILAIu94x48YXBNUZMnUJOyev7h6zWjHkvoKRNLSB5.0xg9/Ax3HG','[\"ROLE_MODERATOR\"]','2018-03-08 17:57:36','2018-03-09 21:45:28'),(13,'toto','toto','toto@gmail.com','$2y$13$rpa5R6N4sFqqYVP6ZGgm/O1v.IgK0mYT7ugVce4dn0.sTODh4FPza','[\"ROLE_ADMIN\"]','2018-03-09 21:46:04','2018-03-09 21:46:04'),(14,'John','Modé','moderateur@pixielanes.com','$2y$13$hq/QjtFHPK8J7lwUCdhwUujo.sNeYPjsAuatcVHI3VUZMKZMcVJD6','[\"ROLE_MODERATOR\"]','2018-03-31 12:42:00','2018-03-31 12:42:00');
/*!40000 ALTER TABLE `pxl_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card`
--

DROP TABLE IF EXISTS `pxl_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `pixie_id` int(11) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `indexed` tinyint(1) DEFAULT NULL,
  `shares` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `thumb_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E4D00041989D9B62` (`slug`),
  UNIQUE KEY `UNIQ_E4D00041F5B7AF75` (`address_id`),
  UNIQUE KEY `UNIQ_E4D00041C7034EA5` (`thumb_id`),
  KEY `IDX_E4D0004198260155` (`region_id`),
  KEY `IDX_E4D00041AE80F5DF` (`department_id`),
  KEY `IDX_E4D0004131F7C64C` (`pixie_id`),
  KEY `IDX_E4D00041896DBBDE` (`updated_by_id`),
  CONSTRAINT `FK_E4D0004131F7C64C` FOREIGN KEY (`pixie_id`) REFERENCES `pxl_user` (`id`),
  CONSTRAINT `FK_E4D00041896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `pxl_admin` (`id`),
  CONSTRAINT `FK_E4D0004198260155` FOREIGN KEY (`region_id`) REFERENCES `pxl_region` (`id`),
  CONSTRAINT `FK_E4D00041AE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `pxl_region_department` (`id`),
  CONSTRAINT `FK_E4D00041C7034EA5` FOREIGN KEY (`thumb_id`) REFERENCES `pxl_card_media` (`id`),
  CONSTRAINT `FK_E4D00041F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `pxl_address` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card`
--

LOCK TABLES `pxl_card` WRITE;
/*!40000 ALTER TABLE `pxl_card` DISABLE KEYS */;
INSERT INTO `pxl_card` VALUES (1,1,1,6,'submitted','Première card','premiere-card','','',3,'<p>Hello World!</p>',1,0,0,'2018-04-05 18:18:04','2018-04-07 15:45:17',1,8),(3,3,22,8,'submitted','Deuxième card - edited','deuxieme-card','','',4,'<p>Plop</p>',1,0,0,'2018-04-07 14:02:09','2018-04-07 15:52:23',1,9);
/*!40000 ALTER TABLE `pxl_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_category`
--

DROP TABLE IF EXISTS `pxl_card_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C7731134989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_category`
--

LOCK TABLES `pxl_card_category` WRITE;
/*!40000 ALTER TABLE `pxl_card_category` DISABLE KEYS */;
INSERT INTO `pxl_card_category` VALUES (7,0,'Nature','tree','nature','2018-03-24 17:23:19','2018-04-09 13:26:06',0),(8,1,'Site touristique','university','site-touristique','2018-03-24 17:23:37','2018-03-24 20:06:07',0),(9,2,'Manger','ustensils','manger','2018-03-24 17:23:57','2018-03-24 17:23:57',0),(10,3,'Mobilité','wheelchair','mobilite','2018-03-24 17:24:16','2018-03-24 17:24:16',0),(11,4,'Sport','futbol','sport','2018-03-24 17:24:31','2018-03-24 17:24:31',0),(12,5,'Vie nocturne','glass-martini','vie-nocturne','2018-03-24 17:24:49','2018-03-24 17:24:49',0),(13,6,'Art et culture','paint-brush','art-et-culture','2018-03-24 17:25:11','2018-03-24 17:25:11',0),(14,7,'Shopping','shopping-bag','shopping','2018-03-24 17:25:27','2018-03-24 17:25:27',0),(15,8,'Consommer local','local','consommer-local','2018-03-24 17:26:38','2018-03-24 17:26:38',0),(16,9,'Enfants','child','enfants','2018-03-24 17:27:30','2018-03-24 17:27:30',0),(17,10,'Green','leaf','green','2018-03-24 17:27:49','2018-03-24 17:27:49',0),(18,11,'Dormir','bed','dormir','2018-03-24 17:28:10','2018-03-24 17:28:10',0),(19,12,'Bien être et santé','sante','bien-etre-et-sante','2018-03-24 17:28:28','2018-03-24 17:28:28',0),(20,13,'Événement','calendar-alt','evenement','2018-03-24 17:29:02','2018-03-24 17:29:02',0),(21,14,'Éxpériences solidaires','users','experiences-solidaires','2018-03-24 17:29:30','2018-03-24 17:29:30',0);
/*!40000 ALTER TABLE `pxl_card_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_collection`
--

DROP TABLE IF EXISTS `pxl_card_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9B5D39C7989D9B62` (`slug`),
  KEY `IDX_9B5D39C7A76ED395` (`user_id`),
  CONSTRAINT `FK_9B5D39C7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `pxl_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_collection`
--

LOCK TABLES `pxl_card_collection` WRITE;
/*!40000 ALTER TABLE `pxl_card_collection` DISABLE KEYS */;
INSERT INTO `pxl_card_collection` VALUES (1,6,'Test','test',1,'2018-04-10 18:12:57','2018-04-10 21:11:35','<p>hum ?</p>'),(3,NULL,'Collection de test - dup','collection-de-test',1,'2018-04-11 19:41:10','2018-04-11 21:02:07','<p>des</p>'),(4,7,'Autre collection','autre-collection',0,'2018-04-11 19:46:42','2018-04-11 19:46:42',''),(5,NULL,'Collection de test - dup tst 2','collection-de-test-1',1,'2018-04-11 21:03:36','2018-04-11 21:03:36','<p>des</p>'),(6,NULL,'Collection de test - dup tst 3','collection-de-test-1-1',0,'2018-04-11 21:03:49','2018-04-11 21:03:49','<p>des</p>'),(7,NULL,'Collection de test - dup dup dup','collection-de-test-2',1,'2018-04-11 21:09:53','2018-04-11 21:09:53','<p>des</p>');
/*!40000 ALTER TABLE `pxl_card_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_collections_cards`
--

DROP TABLE IF EXISTS `pxl_card_collections_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_collections_cards` (
  `collection_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  PRIMARY KEY (`collection_id`,`card_id`),
  KEY `IDX_FFD99AB54ACC9A20` (`card_id`),
  KEY `IDX_FFD99AB5514956FD` (`collection_id`),
  CONSTRAINT `FK_FFD99AB54ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `pxl_card` (`id`),
  CONSTRAINT `FK_FFD99AB5514956FD` FOREIGN KEY (`collection_id`) REFERENCES `pxl_card_collection` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_collections_cards`
--

LOCK TABLES `pxl_card_collections_cards` WRITE;
/*!40000 ALTER TABLE `pxl_card_collections_cards` DISABLE KEYS */;
INSERT INTO `pxl_card_collections_cards` VALUES (3,1),(5,1),(6,1),(7,1),(3,3),(5,3),(6,3);
/*!40000 ALTER TABLE `pxl_card_collections_cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_info`
--

DROP TABLE IF EXISTS `pxl_card_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_id` int(11) DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_44A4336D4ACC9A20` (`card_id`),
  CONSTRAINT `FK_44A4336D4ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `pxl_card` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_info`
--

LOCK TABLES `pxl_card_info` WRITE;
/*!40000 ALTER TABLE `pxl_card_info` DISABLE KEYS */;
INSERT INTO `pxl_card_info` VALUES (14,'blop ?','text',1,'Blop value'),(17,'a','text',3,'b');
/*!40000 ALTER TABLE `pxl_card_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_media`
--

DROP TABLE IF EXISTS `pxl_card_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ACAF55BC4ACC9A20` (`card_id`),
  CONSTRAINT `FK_ACAF55BC4ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `pxl_card` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_media`
--

LOCK TABLES `pxl_card_media` WRITE;
/*!40000 ALTER TABLE `pxl_card_media` DISABLE KEYS */;
INSERT INTO `pxl_card_media` VALUES (1,1,'46-image-821f58ac7a7de920.png'),(8,NULL,'51f51d44de31679bc9ccdc0f5aa27b6d.jpeg'),(9,NULL,'a6d80f715e644da2b90e17869a3fa27e.jpeg'),(10,3,'0892a272240995ac8f9e7a56ae4e006b.jpeg');
/*!40000 ALTER TABLE `pxl_card_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_project`
--

DROP TABLE IF EXISTS `pxl_card_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pixie_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_photos` int(11) NOT NULL,
  `min_videos` int(11) NOT NULL,
  `min_words` int(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `price` int(11) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `payment_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_71A68ED04ACC9A20` (`card_id`),
  KEY `IDX_71A68ED098260155` (`region_id`),
  KEY `IDX_71A68ED0AE80F5DF` (`department_id`),
  KEY `IDX_71A68ED031F7C64C` (`pixie_id`),
  KEY `IDX_71A68ED0B03A8386` (`created_by_id`),
  KEY `IDX_71A68ED0896DBBDE` (`updated_by_id`),
  CONSTRAINT `FK_71A68ED031F7C64C` FOREIGN KEY (`pixie_id`) REFERENCES `pxl_user` (`id`),
  CONSTRAINT `FK_71A68ED04ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `pxl_card` (`id`),
  CONSTRAINT `FK_71A68ED0896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `pxl_admin` (`id`),
  CONSTRAINT `FK_71A68ED098260155` FOREIGN KEY (`region_id`) REFERENCES `pxl_region` (`id`),
  CONSTRAINT `FK_71A68ED0AE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `pxl_region_department` (`id`),
  CONSTRAINT `FK_71A68ED0B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `pxl_admin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_project`
--

LOCK TABLES `pxl_card_project` WRITE;
/*!40000 ALTER TABLE `pxl_card_project` DISABLE KEYS */;
INSERT INTO `pxl_card_project` VALUES (2,'test',3,22,'submitted',8,'<p>dccvvcvc</p>',0,0,0,'2018-03-13',10,'<p>cvcv</p>',NULL,'2018-03-30 13:41:10','2018-04-05 13:27:09',1,1,''),(3,'Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla',1,3,'validated',10,'<p>cwcwxcxw</p>',0,0,0,'2018-04-12',100,'',NULL,'2018-03-30 13:41:33','2018-04-12 20:00:18',1,1,'topay'),(12,'Hey ! 11',3,23,'template',NULL,'<p>hey :!</p>',10,20,30,'2019-03-15',0,'<p>Comment</p>',NULL,'2018-04-04 19:24:26','2018-04-04 19:24:26',1,1,''),(13,'Test save',3,23,'template',NULL,'<p>hey :!</p>',10,20,30,'2019-03-15',0,'<p>Comment</p>',NULL,'2018-04-04 19:27:36','2018-04-04 19:27:36',1,1,''),(15,'Mon super modèle',1,3,'template',NULL,'<p>cwcwxcxw</p>',0,0,0,'2018-04-12',0,'',NULL,'2018-04-04 19:29:18','2018-04-04 19:29:18',1,1,''),(17,'Demooooo',1,3,'template',NULL,'<p>cwcwxcxw</p>',0,0,0,'2018-04-12',100,'',NULL,'2018-04-07 15:52:04','2018-04-07 15:52:04',1,1,'');
/*!40000 ALTER TABLE `pxl_card_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_project_attachment`
--

DROP TABLE IF EXISTS `pxl_card_project_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_project_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1AC392A2166D1F9C` (`project_id`),
  CONSTRAINT `FK_1AC392A2166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `pxl_card_project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_project_attachment`
--

LOCK TABLES `pxl_card_project_attachment` WRITE;
/*!40000 ALTER TABLE `pxl_card_project_attachment` DISABLE KEYS */;
INSERT INTO `pxl_card_project_attachment` VALUES (21,15,'46-image-6bf87a55d0e1616f.jpg'),(22,15,'46-image-eb1a22f266b2b9f5.png');
/*!40000 ALTER TABLE `pxl_card_project_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_project_info`
--

DROP TABLE IF EXISTS `pxl_card_project_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_project_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F90E9678166D1F9C` (`project_id`),
  CONSTRAINT `FK_F90E9678166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `pxl_card_project` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_project_info`
--

LOCK TABLES `pxl_card_project_info` WRITE;
/*!40000 ALTER TABLE `pxl_card_project_info` DISABLE KEYS */;
INSERT INTO `pxl_card_project_info` VALUES (1,3,'Hey ?','text'),(3,17,'Hey ?','text');
/*!40000 ALTER TABLE `pxl_card_project_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_wall`
--

DROP TABLE IF EXISTS `pxl_card_wall`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `indexed` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9CD8EDCC989D9B62` (`slug`),
  KEY `IDX_9CD8EDCC98260155` (`region_id`),
  KEY `IDX_9CD8EDCCAE80F5DF` (`department_id`),
  CONSTRAINT `FK_9CD8EDCC98260155` FOREIGN KEY (`region_id`) REFERENCES `pxl_region` (`id`),
  CONSTRAINT `FK_9CD8EDCCAE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `pxl_region_department` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_wall`
--

LOCK TABLES `pxl_card_wall` WRITE;
/*!40000 ALTER TABLE `pxl_card_wall` DISABLE KEYS */;
INSERT INTO `pxl_card_wall` VALUES (1,3,22,'Nature en Bretagne','nature-en-bretagne','Nature en Bretagne','Nature en Bretagne','<p><strong><span style=\"color: rgb(239, 82, 133);\">La nature e</span><span style=\"color: rgb(93, 181, 151);\">n Bretagne...</span></strong></p>',1,'2018-04-13 17:27:20','2018-04-13 17:27:20'),(2,10,66,'Surf dans les Landes','surf-dans-les-landes','Surf dans les Landes','Surf dans les Landes','<p>Surf dans les Landes</p>',1,'2018-04-13 17:31:38','2018-04-13 17:31:46'),(3,5,NULL,'Visiter la Corse','visiter-la-corse','Visiter la Corse','Visiter la Corse','<p>Visiter la Corse</p>',1,'2018-04-13 17:47:34','2018-04-13 17:47:34');
/*!40000 ALTER TABLE `pxl_card_wall` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_walls_categories`
--

DROP TABLE IF EXISTS `pxl_card_walls_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_walls_categories` (
  `wall_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`wall_id`,`category_id`),
  KEY `IDX_C3569731C33923F1` (`wall_id`),
  KEY `IDX_C356973112469DE2` (`category_id`),
  CONSTRAINT `FK_C356973112469DE2` FOREIGN KEY (`category_id`) REFERENCES `pxl_card_category` (`id`),
  CONSTRAINT `FK_C3569731C33923F1` FOREIGN KEY (`wall_id`) REFERENCES `pxl_card_wall` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_walls_categories`
--

LOCK TABLES `pxl_card_walls_categories` WRITE;
/*!40000 ALTER TABLE `pxl_card_walls_categories` DISABLE KEYS */;
INSERT INTO `pxl_card_walls_categories` VALUES (1,7),(2,11),(3,7),(3,8),(3,13);
/*!40000 ALTER TABLE `pxl_card_walls_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_cards_categories`
--

DROP TABLE IF EXISTS `pxl_cards_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_cards_categories` (
  `card_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`card_id`,`category_id`),
  KEY `IDX_893336A64ACC9A20` (`card_id`),
  KEY `IDX_893336A612469DE2` (`category_id`),
  CONSTRAINT `FK_893336A612469DE2` FOREIGN KEY (`category_id`) REFERENCES `pxl_card_category` (`id`),
  CONSTRAINT `FK_893336A64ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `pxl_card` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_cards_categories`
--

LOCK TABLES `pxl_cards_categories` WRITE;
/*!40000 ALTER TABLE `pxl_cards_categories` DISABLE KEYS */;
INSERT INTO `pxl_cards_categories` VALUES (1,7),(1,13),(3,7),(3,20),(3,21);
/*!40000 ALTER TABLE `pxl_cards_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_cards_projects_categories`
--

DROP TABLE IF EXISTS `pxl_cards_projects_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_cards_projects_categories` (
  `project_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`category_id`),
  KEY `IDX_5A6088166D1F9C` (`project_id`),
  KEY `IDX_5A608812469DE2` (`category_id`),
  CONSTRAINT `FK_5A608812469DE2` FOREIGN KEY (`category_id`) REFERENCES `pxl_card_category` (`id`),
  CONSTRAINT `FK_5A6088166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `pxl_card_project` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_cards_projects_categories`
--

LOCK TABLES `pxl_cards_projects_categories` WRITE;
/*!40000 ALTER TABLE `pxl_cards_projects_categories` DISABLE KEYS */;
INSERT INTO `pxl_cards_projects_categories` VALUES (2,8),(2,14),(2,19),(3,10),(3,15),(3,21),(12,8),(12,16),(12,21),(13,8),(13,16),(13,21),(15,10),(15,15),(15,21),(17,10),(17,15),(17,21);
/*!40000 ALTER TABLE `pxl_cards_projects_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_menu`
--

DROP TABLE IF EXISTS `pxl_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8FC1A201989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_menu`
--

LOCK TABLES `pxl_menu` WRITE;
/*!40000 ALTER TABLE `pxl_menu` DISABLE KEYS */;
INSERT INTO `pxl_menu` VALUES (1,'Hello Menu','hello-menu'),(2,'menu numéro 2','menu-numero-2'),(3,'New menu','new-menu');
/*!40000 ALTER TABLE `pxl_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_menu_item`
--

DROP TABLE IF EXISTS `pxl_menu_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_menu_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blank` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B44AD5C8C4663E4` (`page_id`),
  KEY `IDX_B44AD5C8CCD7E912` (`menu_id`),
  CONSTRAINT `FK_B44AD5C8C4663E4` FOREIGN KEY (`page_id`) REFERENCES `pxl_page` (`id`),
  CONSTRAINT `FK_B44AD5C8CCD7E912` FOREIGN KEY (`menu_id`) REFERENCES `pxl_menu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_menu_item`
--

LOCK TABLES `pxl_menu_item` WRITE;
/*!40000 ALTER TABLE `pxl_menu_item` DISABLE KEYS */;
INSERT INTO `pxl_menu_item` VALUES (4,4,1,'hum ?','link...',1,2),(5,3,1,'Test','ah ?',1,3),(6,2,1,'','',0,0),(7,NULL,1,'New','',0,1),(8,NULL,3,'Plop 3','',0,0),(9,NULL,3,'Plop 2','',0,1),(10,NULL,3,'Plop 1','',0,2);
/*!40000 ALTER TABLE `pxl_menu_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_option`
--

DROP TABLE IF EXISTS `pxl_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E725019A989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_option`
--

LOCK TABLES `pxl_option` WRITE;
/*!40000 ALTER TABLE `pxl_option` DISABLE KEYS */;
INSERT INTO `pxl_option` VALUES (5,'Lien Facebook','facebook-link','http://www.facebook.com'),(6,'Lien Instagram','instagram-link','http://www.instagram.com'),(7,'Lien Twitter','twitter-link','http://www.twitter.com'),(8,'Copyright','copyright','© 2018 Pixielanes');
/*!40000 ALTER TABLE `pxl_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_page`
--

DROP TABLE IF EXISTS `pxl_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `indexed` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slider_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E6CE2EB2989D9B62` (`slug`),
  KEY `IDX_E6CE2EB22CCC9638` (`slider_id`),
  CONSTRAINT `FK_E6CE2EB22CCC9638` FOREIGN KEY (`slider_id`) REFERENCES `pxl_slider` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_page`
--

LOCK TABLES `pxl_page` WRITE;
/*!40000 ALTER TABLE `pxl_page` DISABLE KEYS */;
INSERT INTO `pxl_page` VALUES (2,'Hello World !','cest-un-super-test-tres-long-hello','c\'est un super test \" très long !! hello','test','<p><img src=\"/upload/78c2bd4d9aaf4afc54ba46fc1fe39f15a7678116.jpeg\" style=\"width: 220px;\" class=\"fr-fic fr-dib\"></p><p><br></p>',0,'2018-03-12 18:19:20','2018-03-15 13:52:02',NULL),(3,'aaa','cest-un-super-test-tres-long-hello-1','aa','aa','<p>aaaa</p>',1,'2018-03-15 12:26:24','2018-03-15 13:57:06',NULL),(4,'bbb','bbbb','bbbb','bbb','<p>bb</p>',1,'2018-03-15 12:58:01','2018-03-15 13:56:40',NULL),(5,'aa','test-de-meta-title','test de meta title','bbbb','<p>ddddd</p>',1,'2018-03-15 13:49:25','2018-03-15 13:49:36',NULL),(6,'Accueil','accueil','Accueil','Description','<p>Hello homepage</p>',1,'2018-04-12 19:26:58','2018-04-12 19:26:58',2);
/*!40000 ALTER TABLE `pxl_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_page_category`
--

DROP TABLE IF EXISTS `pxl_page_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_page_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `indexed` tinyint(1) DEFAULT NULL,
  `hidden` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `thumb_id` int(11) DEFAULT NULL,
  `background_id` int(11) DEFAULT NULL,
  `discover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FEDDD897989D9B62` (`slug`),
  UNIQUE KEY `UNIQ_FEDDD897C7034EA5` (`thumb_id`),
  UNIQUE KEY `UNIQ_FEDDD897C93D69EA` (`background_id`),
  KEY `IDX_FEDDD89798260155` (`region_id`),
  CONSTRAINT `FK_FEDDD89798260155` FOREIGN KEY (`region_id`) REFERENCES `pxl_region` (`id`),
  CONSTRAINT `FK_FEDDD897C7034EA5` FOREIGN KEY (`thumb_id`) REFERENCES `pxl_page_category_media` (`id`),
  CONSTRAINT `FK_FEDDD897C93D69EA` FOREIGN KEY (`background_id`) REFERENCES `pxl_page_category_media` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_page_category`
--

LOCK TABLES `pxl_page_category` WRITE;
/*!40000 ALTER TABLE `pxl_page_category` DISABLE KEYS */;
INSERT INTO `pxl_page_category` VALUES (1,8,'A','e','B','D','',0,1,'2018-04-13 21:12:53','2018-04-13 21:17:10',NULL,NULL,'C',''),(2,3,'Bretagne','bretagne','Bretagne','Bretagne','',1,0,'2018-04-13 21:15:12','2018-04-13 21:17:38',1,2,'Découvrir la Bretagne','https://...');
/*!40000 ALTER TABLE `pxl_page_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_page_category_media`
--

DROP TABLE IF EXISTS `pxl_page_category_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_page_category_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_page_category_media`
--

LOCK TABLES `pxl_page_category_media` WRITE;
/*!40000 ALTER TABLE `pxl_page_category_media` DISABLE KEYS */;
INSERT INTO `pxl_page_category_media` VALUES (1,'2849e7d54045317ad180d7108b70a369.jpeg'),(2,'0e9968031b7d49981bdbaecc357a3e2a.jpeg');
/*!40000 ALTER TABLE `pxl_page_category_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_region`
--

DROP TABLE IF EXISTS `pxl_region`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B2C1F05C989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_region`
--

LOCK TABLES `pxl_region` WRITE;
/*!40000 ALTER TABLE `pxl_region` DISABLE KEYS */;
INSERT INTO `pxl_region` VALUES (1,0,'Auvergne-Rhône-Alpes','auvergne-rhone-alpes','2018-03-24 19:37:35','2018-04-09 13:32:40',0),(2,1,'Bourgogne-Franche-Comté','bourgogne-franche-comte','2018-03-24 19:37:46','2018-03-28 16:14:24',0),(3,2,'Bretagne','bretagne','2018-03-24 19:37:55','2018-03-24 19:37:55',0),(4,3,'Centre-Val de Loire','centre-val-de-loire','2018-03-24 19:38:07','2018-03-24 19:38:07',0),(5,4,'Corse','corse','2018-03-24 19:38:16','2018-03-24 19:38:16',0),(6,5,'Grand Est','grand-est','2018-03-24 19:38:24','2018-03-24 19:38:24',0),(7,6,'Hauts-de-France','hauts-de-france','2018-03-24 19:38:32','2018-03-24 19:38:32',0),(8,7,'Ile-de-France','ile-de-france','2018-03-24 19:38:42','2018-03-24 19:38:42',0),(9,8,'Normandie','normandie','2018-03-24 19:38:48','2018-03-24 19:38:48',0),(10,9,'Nouvelle Aquitaine','nouvelle-aquitaine','2018-03-24 19:38:56','2018-03-24 19:38:56',0),(11,10,'Occitanie','occitanie','2018-03-24 19:39:02','2018-03-24 19:39:02',0),(12,11,'Pays de Loire','pays-de-loire','2018-03-24 19:39:11','2018-03-24 19:39:11',0),(13,12,'Provence-Alpes-Côtes d\'Azur','provence-alpes-cotes-dazur','2018-03-24 19:39:18','2018-03-24 19:39:18',0);
/*!40000 ALTER TABLE `pxl_region` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_region_department`
--

DROP TABLE IF EXISTS `pxl_region_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_region_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_id` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F7D11459989D9B62` (`slug`),
  KEY `IDX_F7D1145998260155` (`region_id`),
  CONSTRAINT `FK_F7D1145998260155` FOREIGN KEY (`region_id`) REFERENCES `pxl_region` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_region_department`
--

LOCK TABLES `pxl_region_department` WRITE;
/*!40000 ALTER TABLE `pxl_region_department` DISABLE KEYS */;
INSERT INTO `pxl_region_department` VALUES (1,1,0,'Ain','ain','2018-03-28 16:27:35','2018-04-09 13:35:01',0),(3,1,1,'Allier','allier','2018-03-28 16:37:34','2018-03-28 17:31:00',0),(4,1,2,'Ardèche','ardeche','2018-03-28 16:37:50','2018-03-28 16:37:50',0),(5,1,3,'Cantal','cantal','2018-03-28 16:37:55','2018-03-28 16:37:55',0),(6,1,4,'Drôme','drome','2018-03-28 16:38:02','2018-03-28 16:38:02',0),(7,1,5,'Isère','isere','2018-03-28 16:38:09','2018-03-28 16:38:09',0),(8,1,6,'Loire','loire','2018-03-28 16:38:15','2018-03-28 16:38:15',0),(9,1,7,'Haute-Loire','haute-loire','2018-03-28 16:38:21','2018-03-28 16:38:21',0),(10,1,8,'Puy-de-Dôme','puy-de-dome','2018-03-28 16:38:30','2018-03-28 16:38:30',0),(11,1,9,'Rhône','rhone','2018-03-28 16:38:35','2018-03-28 16:38:35',0),(12,1,10,'Savoie','savoie','2018-03-28 16:38:47','2018-03-28 16:38:47',0),(13,1,11,'Haute-Savoie','haute-savoie','2018-03-28 16:38:52','2018-03-28 16:38:52',0),(14,2,12,'Côte-d\'Or','cote-dor','2018-03-28 16:41:53','2018-03-28 17:30:21',0),(15,2,13,'Doubs','doubs','2018-03-28 16:41:57','2018-03-28 17:30:54',0),(16,2,14,'Jura','jura','2018-03-28 16:42:03','2018-03-28 16:42:03',0),(17,2,15,'Nièvre','nievre','2018-03-28 16:42:07','2018-03-28 16:42:07',0),(18,2,16,'Haute-Saône','haute-saone','2018-03-28 16:42:12','2018-03-28 16:42:12',0),(19,2,17,'Saône-et-Loire','saone-et-loire','2018-03-28 16:42:17','2018-03-28 16:42:17',0),(20,2,18,'Yonne','yonne','2018-03-28 16:42:24','2018-03-28 16:42:24',0),(21,2,19,'Territoire de Belfort','territoire-de-belfort','2018-03-28 16:42:30','2018-03-28 16:42:30',0),(22,3,20,'Côtes-d\'Armor','cotes-darmor','2018-03-28 16:42:44','2018-03-28 16:42:44',0),(23,3,21,'Finistère','finistere','2018-03-28 16:42:49','2018-03-28 16:42:49',0),(24,3,22,'Ille-et-Vilaine','ille-et-vilaine','2018-03-28 16:42:57','2018-03-28 16:42:57',0),(25,3,23,'Morbihan','morbihan','2018-03-28 16:43:02','2018-03-28 16:43:02',0),(26,4,24,'Cher','cher','2018-03-28 16:43:29','2018-03-28 16:43:29',0),(27,4,25,'Eure-et-Loir','eure-et-loir','2018-03-28 16:43:33','2018-03-28 16:43:33',0),(28,4,26,'Indre','indre','2018-03-28 16:43:38','2018-03-28 16:43:38',0),(29,4,27,'Indre-et-Loire','indre-et-loire','2018-03-28 16:43:43','2018-03-28 16:43:43',0),(30,4,28,'Loir-et-Cher','loir-et-cher','2018-03-28 16:43:50','2018-03-28 16:43:50',0),(31,4,29,'Loiret','loiret','2018-03-28 16:43:56','2018-03-28 16:43:56',0),(32,5,30,'Corse-du-Sud','corse-du-sud','2018-03-28 16:44:10','2018-03-28 16:44:10',0),(33,5,31,'Haute-Corse','haute-corse','2018-03-28 16:44:16','2018-03-28 16:44:16',0),(34,6,32,'Ardennes','ardennes','2018-03-28 16:44:32','2018-03-28 16:44:32',0),(35,6,33,'Aube','aube','2018-03-28 16:44:38','2018-03-28 16:44:38',0),(36,6,34,'Marne','marne','2018-03-28 16:44:43','2018-03-28 16:44:43',0),(37,6,35,'Haute-Marne','haute-marne','2018-03-28 16:44:48','2018-03-28 16:44:48',0),(38,6,36,'Meurthe-et-Moselle','meurthe-et-moselle','2018-03-28 16:44:53','2018-03-28 16:44:53',0),(39,6,37,'Meuse','meuse','2018-03-28 16:44:58','2018-03-28 16:44:58',0),(40,6,38,'Moselle','moselle','2018-03-28 16:45:04','2018-03-28 16:45:04',0),(41,6,39,'Bas-Rhin','bas-rhin','2018-03-28 16:45:09','2018-03-28 16:45:09',0),(42,6,40,'Haut-Rhin','haut-rhin','2018-03-28 16:45:15','2018-03-28 16:45:15',0),(43,6,41,'Vosges','vosges','2018-03-28 16:45:20','2018-03-28 16:45:20',0),(44,7,42,'Aisne','aisne','2018-03-28 16:46:05','2018-03-28 16:46:05',0),(45,7,43,'Nord','nord','2018-03-28 16:46:10','2018-03-28 16:46:10',0),(46,7,44,'Oise','oise','2018-03-28 16:46:15','2018-03-28 16:46:15',0),(47,7,45,'Pas-de-Calais','pas-de-calais','2018-03-28 16:46:20','2018-03-28 16:46:20',0),(48,7,46,'Somme','somme','2018-03-28 16:46:26','2018-03-28 16:46:26',0),(49,8,47,'Paris','paris','2018-03-28 16:46:37','2018-03-28 16:46:37',0),(50,8,48,'Seine-et-Marne','seine-et-marne','2018-03-28 16:46:43','2018-03-28 16:46:43',0),(51,8,49,'Yvelines','yvelines','2018-03-28 16:46:50','2018-03-28 16:46:50',0),(52,8,50,'Essonne','essonne','2018-03-28 16:46:57','2018-03-28 16:46:57',0),(53,8,51,'Hauts-de-Seine','hauts-de-seine','2018-03-28 16:47:02','2018-03-28 16:47:02',0),(54,8,52,'Seine-Saint-Denis','seine-saint-denis','2018-03-28 16:47:07','2018-03-28 16:47:07',0),(55,8,53,'Val-de-Marne','val-de-marne','2018-03-28 16:47:13','2018-03-28 16:47:13',0),(56,8,54,'Val-d\'Oise','val-doise','2018-03-28 16:47:19','2018-03-28 16:47:19',0),(57,9,55,'Manche','manche','2018-03-28 16:47:31','2018-03-28 16:47:31',0),(58,9,56,'Orne','orne','2018-03-28 16:47:35','2018-03-28 16:47:35',0),(59,9,57,'Seine-Maritime','seine-maritime','2018-03-28 16:47:41','2018-03-28 16:47:41',0),(60,10,58,'Charente','charente','2018-03-28 16:47:53','2018-03-28 16:47:53',0),(61,10,59,'Charente-Maritime','charente-maritime','2018-03-28 16:47:57','2018-03-28 16:47:57',0),(62,10,60,'Corrèze','correze','2018-03-28 17:19:41','2018-03-28 17:19:41',0),(63,10,61,'Creuse','creuse','2018-03-28 17:19:46','2018-03-28 17:19:46',0),(64,10,62,'Dordogne','dordogne','2018-03-28 17:19:50','2018-03-28 17:19:50',0),(65,10,63,'Gironde','gironde','2018-03-28 17:19:54','2018-03-28 17:19:54',0),(66,10,64,'Landes','landes','2018-03-28 17:20:01','2018-03-28 17:20:01',0),(67,10,65,'Lot-et-Garonne','lot-et-garonne','2018-03-28 17:20:06','2018-03-28 17:20:06',0),(68,10,66,'Pyrénées-Atlantiques','pyrenees-atlantiques','2018-03-28 17:20:12','2018-03-28 17:20:12',0),(69,10,67,'Deux-Sèvres','deux-sevres','2018-03-28 17:20:21','2018-03-28 17:20:21',0),(70,10,68,'Vienne','vienne','2018-03-28 17:20:27','2018-03-28 17:20:27',0),(71,10,69,'Haute-Vienne','haute-vienne','2018-03-28 17:20:32','2018-03-28 17:20:32',0),(72,11,70,'Ariège','ariege','2018-03-28 17:20:56','2018-03-28 17:20:56',0),(73,11,71,'Aude','aude','2018-03-28 17:21:01','2018-03-28 17:21:01',0),(74,11,72,'Aveyron','aveyron','2018-03-28 17:21:05','2018-03-28 17:21:05',0),(75,11,73,'Gard','gard','2018-03-28 17:21:11','2018-03-28 17:21:11',0),(76,11,74,'Haute-Garonne','haute-garonne','2018-03-28 17:21:18','2018-03-28 17:21:18',0),(77,11,75,'Gers','gers','2018-03-28 17:21:25','2018-03-28 17:21:25',0),(78,11,76,'Hérault','herault','2018-03-28 17:21:31','2018-03-28 17:21:31',0),(79,11,77,'Lot','lot','2018-03-28 17:21:41','2018-03-28 17:21:41',0),(80,11,78,'Lozère','lozere','2018-03-28 17:21:45','2018-03-28 17:21:45',0),(81,11,79,'Hautes-Pyrénées','hautes-pyrenees','2018-03-28 17:21:50','2018-03-28 17:21:50',0),(82,11,80,'Pyrénées-Orientales','pyrenees-orientales','2018-03-28 17:21:59','2018-03-28 17:21:59',0),(83,11,81,'Tarn','tarn','2018-03-28 17:22:04','2018-03-28 17:22:04',0),(84,11,82,'Tarn-et-Garonne','tarn-et-garonne','2018-03-28 17:22:09','2018-03-28 17:22:09',0),(85,12,83,'Loire-Atlantique','loire-atlantique','2018-03-28 17:22:28','2018-03-28 17:22:28',0),(86,12,84,'Maine-et-Loire','maine-et-loire','2018-03-28 17:22:33','2018-03-28 17:22:33',0),(87,12,85,'Mayenne','mayenne','2018-03-28 17:22:38','2018-03-28 17:22:38',0),(88,12,86,'Sarthe','sarthe','2018-03-28 17:22:42','2018-03-28 17:22:42',0),(89,12,87,'Vendée','vendee','2018-03-28 17:22:48','2018-03-28 17:22:48',0),(90,13,88,'Alpes-de-Haute-Provence','alpes-de-haute-provence','2018-03-28 17:23:02','2018-03-28 17:23:02',0),(91,13,89,'Hautes-Alpes','hautes-alpes','2018-03-28 17:23:09','2018-03-28 17:23:09',0),(92,13,90,'Alpes-Maritimes','alpes-maritimes','2018-03-28 17:23:14','2018-03-28 17:23:14',0),(93,13,91,'Bouches-du-Rhône','bouches-du-rhone','2018-03-28 17:23:18','2018-03-28 17:23:18',0),(94,13,92,'Var','var','2018-03-28 17:23:23','2018-03-28 17:23:23',0),(95,13,93,'Vaucluse','vaucluse','2018-03-28 17:23:28','2018-03-28 17:23:28',0);
/*!40000 ALTER TABLE `pxl_region_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_slider`
--

DROP TABLE IF EXISTS `pxl_slider`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_slider`
--

LOCK TABLES `pxl_slider` WRITE;
/*!40000 ALTER TABLE `pxl_slider` DISABLE KEYS */;
INSERT INTO `pxl_slider` VALUES (2,'Slider de la page d\'accueil','2018-04-12 17:11:50','2018-04-12 17:11:50');
/*!40000 ALTER TABLE `pxl_slider` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_slider_media`
--

DROP TABLE IF EXISTS `pxl_slider_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_slider_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_slider_media`
--

LOCK TABLES `pxl_slider_media` WRITE;
/*!40000 ALTER TABLE `pxl_slider_media` DISABLE KEYS */;
INSERT INTO `pxl_slider_media` VALUES (7,'76cd40d3109a7f737fe407ea788c2f18.jpeg'),(8,'1e6c8cd0162a21a4d28cc5e8e01ac29e.jpeg'),(9,'28b9792fb229c18943b1bacf2c61d0ae.jpeg'),(10,'750dbcbd9c68486d857dc53f11007f10.jpeg');
/*!40000 ALTER TABLE `pxl_slider_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_slider_slide`
--

DROP TABLE IF EXISTS `pxl_slider_slide`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_slider_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slider_id` int(11) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `background_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FD54764EC93D69EA` (`background_id`),
  UNIQUE KEY `UNIQ_FD54764E3DA5256D` (`image_id`),
  KEY `IDX_FD54764E2CCC9638` (`slider_id`),
  CONSTRAINT `FK_FD54764E2CCC9638` FOREIGN KEY (`slider_id`) REFERENCES `pxl_slider` (`id`),
  CONSTRAINT `FK_FD54764E3DA5256D` FOREIGN KEY (`image_id`) REFERENCES `pxl_slider_media` (`id`),
  CONSTRAINT `FK_FD54764EC93D69EA` FOREIGN KEY (`background_id`) REFERENCES `pxl_slider_media` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_slider_slide`
--

LOCK TABLES `pxl_slider_slide` WRITE;
/*!40000 ALTER TABLE `pxl_slider_slide` DISABLE KEYS */;
INSERT INTO `pxl_slider_slide` VALUES (1,2,NULL,NULL,'Slide alpha','','2018-04-12 17:33:09','2018-04-12 17:34:45',2),(3,2,9,10,'Slide beta','<p>texte</p>','2018-04-12 17:34:26','2018-04-12 19:16:06',1),(4,2,7,8,'Slide gamma','<p>ddd</p>','2018-04-12 17:34:35','2018-04-12 19:15:19',0);
/*!40000 ALTER TABLE `pxl_slider_slide` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_transaction`
--

DROP TABLE IF EXISTS `pxl_transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CBAF66DDA76ED395` (`user_id`),
  CONSTRAINT `FK_CBAF66DDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `pxl_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_transaction`
--

LOCK TABLES `pxl_transaction` WRITE;
/*!40000 ALTER TABLE `pxl_transaction` DISABLE KEYS */;
INSERT INTO `pxl_transaction` VALUES (1,6,'banktransfer','banktransfer_success','2018-04-15 00:00:00','0000-00-00 00:00:00'),(3,7,'check','check_success','2018-04-12 00:00:00','2018-04-16 14:21:17');
/*!40000 ALTER TABLE `pxl_transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_transaction_history`
--

DROP TABLE IF EXISTS `pxl_transaction_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(11) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5003F1D52FC0CB0F` (`transaction_id`),
  CONSTRAINT `FK_5003F1D52FC0CB0F` FOREIGN KEY (`transaction_id`) REFERENCES `pxl_transaction` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_transaction_history`
--

LOCK TABLES `pxl_transaction_history` WRITE;
/*!40000 ALTER TABLE `pxl_transaction_history` DISABLE KEYS */;
INSERT INTO `pxl_transaction_history` VALUES (1,3,'pixie_asked_check','2018-04-15 00:00:00'),(2,1,'pixie_asked_banktransfer','2018-04-14 00:00:00'),(3,1,'banktransfer_success','2018-04-15 00:00:00'),(4,3,'check_success','2018-04-15 21:16:21'),(5,3,'check_success','2018-04-15 21:16:44'),(6,3,'check_success','2018-04-16 14:12:16'),(7,3,'check_success','2018-04-16 14:21:17');
/*!40000 ALTER TABLE `pxl_transaction_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_transactions_projects`
--

DROP TABLE IF EXISTS `pxl_transactions_projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_transactions_projects` (
  `transaction_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`transaction_id`,`project_id`),
  KEY `IDX_524F76012FC0CB0F` (`transaction_id`),
  KEY `IDX_524F7601166D1F9C` (`project_id`),
  CONSTRAINT `FK_524F7601166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `pxl_card_project` (`id`),
  CONSTRAINT `FK_524F76012FC0CB0F` FOREIGN KEY (`transaction_id`) REFERENCES `pxl_transaction` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_transactions_projects`
--

LOCK TABLES `pxl_transactions_projects` WRITE;
/*!40000 ALTER TABLE `pxl_transactions_projects` DISABLE KEYS */;
INSERT INTO `pxl_transactions_projects` VALUES (1,3),(3,2),(3,17);
/*!40000 ALTER TABLE `pxl_transactions_projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_user`
--

DROP TABLE IF EXISTS `pxl_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `birth_location` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_location` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pixie_id` int(11) DEFAULT NULL,
  `roles` json NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7F574EDB31F7C64C` (`pixie_id`),
  KEY `index_email` (`email`),
  CONSTRAINT `FK_7F574EDB31F7C64C` FOREIGN KEY (`pixie_id`) REFERENCES `pxl_user_pixie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user`
--

LOCK TABLES `pxl_user` WRITE;
/*!40000 ALTER TABLE `pxl_user` DISABLE KEYS */;
INSERT INTO `pxl_user` VALUES (1,'Adrien','Lamotte','0642482947','1906-06-11','75014','91180','female','adrien.lamotte@gmail.com','$2y$13$MuCx.KnIHZCHhGYapDVZt.VY3OzSV5y6mXwW5UVlrIC5t8np8Aov2','392fe3500bd92e197d0db2fdca526c41.jpeg',NULL,'[\"ROLE_USER\"]','2018-03-16 19:11:29','2018-03-18 18:49:39'),(4,'Adrien','Lamotte','642482947','1902-01-01','ffgdfgd dfs','df gsdgdf g','male','lamotte@gmail.com','$2y$13$F2MpRhkAOmHC4o3g9Uur5OjmmtStdzTtqx1KbuKxVSCKCHvxdUzcK','82373de6f27e2ba7a9e0e82a6a9fb4ff.jpeg',1,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-19 18:41:39','2018-03-20 15:16:54'),(5,'test','test','06042621510','1902-01-01','dfsf','sdfsdfsd','female','test@test.com','$2y$13$m6JqAQnlRImJDyOk8XQW1.fPEShBeHP07.eyy5xU8a1w9Gr.YV4Fe','4848952cdf8ca346ba6475cc5c7348ad.jpeg',3,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 15:21:28','2018-03-20 18:47:38'),(6,'Hello','Pixie','aa','1902-01-01','aa','aa','male','hellopixie@gmail.com','$2y$13$oud5B1W0yBB2hFr1KV/Fausht/NdwFe12IuI1QXintRbsj.HDE.IW','e25f8d0c647c4871034911ba06ea7fe0.jpeg',4,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 18:51:51','2018-03-20 18:51:51'),(7,'aaaaa','Lamotte','642482947','1986-01-01','a','a','male','adrien.lamotte222@gmail.com','$2y$13$VK8Gu7fNZ13jlVwyn78/OeYPMTkj60L/iXqNpmQSSM9C.WckfKj5.','eed43964480ff15483e4e4551c0d1fa1.pdf',7,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 20:12:30','2018-03-21 15:20:52'),(8,'Adrien','Lamotte','642482947','1986-01-01','a','78610','male','adrien.lamotteqrthe@gmail.com','$2y$13$BzKdCT19maiKtavg56ynqeb.ni68I4thwusxCnHlVe/X/xubwWmIO','6689254553de34338c615dbbb591d8d4.jpeg',8,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-21 15:24:26','2018-03-21 15:27:05'),(9,'z','z','z','1902-01-01','z','z','male','zzz@zz.zz','$2y$13$JgHDp/eaKuoQsPgGCFQFHexb8SGHAF2abjrJyFSr0H/RURTG0Ud/K','9ccedba538fec83835c1044cab56f295.jpeg',NULL,'[]','2018-03-21 16:19:17','2018-03-21 16:19:17'),(10,'A','B','+33642482947','1986-01-01','a','a','female','adrien.lamotte@gmail.com','$2y$13$c5DtXi5ipxLqQuhSeKp73eAno.OM4RsOyW5yNVFieoeCsEoCaFdOC','585f59da1a162e6ca330329d4dca03b1.jpeg',9,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-21 17:04:11','2018-03-29 20:07:10');
/*!40000 ALTER TABLE `pxl_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_user_link`
--

DROP TABLE IF EXISTS `pxl_user_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_user_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2F33D5A0A76ED395` (`user_id`),
  CONSTRAINT `FK_2F33D5A0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `pxl_user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_link`
--

LOCK TABLES `pxl_user_link` WRITE;
/*!40000 ALTER TABLE `pxl_user_link` DISABLE KEYS */;
INSERT INTO `pxl_user_link` VALUES (9,1,'http://www.google.fr','TEST3333'),(11,1,'http://www.dsfsdf2.fr','gggg'),(17,4,'http://www.facebook.fr','facebook'),(19,4,'http://www.twitter.fr','twitter'),(20,4,'http://instagram.fr','instagram'),(21,7,'http://www.facebbok.fr','facebook');
/*!40000 ALTER TABLE `pxl_user_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_user_pixie`
--

DROP TABLE IF EXISTS `pxl_user_pixie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_user_pixie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `like_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `dislike_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B6AE2DA43B025C87` (`billing_id`),
  CONSTRAINT `FK_B6AE2DA43B025C87` FOREIGN KEY (`billing_id`) REFERENCES `pxl_user_pixie_billing` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_pixie`
--

LOCK TABLES `pxl_user_pixie` WRITE;
/*!40000 ALTER TABLE `pxl_user_pixie` DISABLE KEYS */;
INSERT INTO `pxl_user_pixie` VALUES (1,'<p>sdfgdf dsfgdf&nbsp;</p>','<p>df gsdf g</p>',NULL),(3,'<p>J&#39;aime ceci</p>','<p>J&#39;aime pas cela</p>',1),(4,'<p>test 1</p>','<p>test 2</p>',3),(5,'<p>test</p>','<p>test</p>',4),(7,'<p>test</p>','<p>test</p>',6),(8,'<p>a</p>','<p>a</p>',7),(9,'<p>a</p>','<p>a</p>',8);
/*!40000 ALTER TABLE `pxl_user_pixie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_user_pixie_billing`
--

DROP TABLE IF EXISTS `pxl_user_pixie_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_user_pixie_billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tva` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_iban` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_bic` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rib` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A49691D7F5B7AF75` (`address_id`),
  CONSTRAINT `FK_A49691D7F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `pxl_address` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_pixie_billing`
--

LOCK TABLES `pxl_user_pixie_billing` WRITE;
/*!40000 ALTER TABLE `pxl_user_pixie_billing` DISABLE KEYS */;
INSERT INTO `pxl_user_pixie_billing` VALUES (1,'Mon statut','','','','','','','','','','','',NULL),(3,'company','a','b','c','d','i','banktransfer','k','l','m','n','e3af9c43b116ee29cdc2389ed42aa4e4.pdf',NULL),(4,'test','test','test','test','test','test','test','test','test','test','test','test',NULL),(6,'individualregistration','a','b','c','d','i','banktransfer','k','l','m','n','8426318ab3dbe98662ccf343d413d984.png',NULL),(7,'company','a','a','a','a','a','check','a','a','a','a','a59c2471549ac2e08d01b4d300107bfa.pdf',NULL),(8,'individualregistration','','Adrien','Lamotte','AAAAA','+33642482947','check','Adrien Lamotte','','','','',2);
/*!40000 ALTER TABLE `pxl_user_pixie_billing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_user_pixies_regions`
--

DROP TABLE IF EXISTS `pxl_user_pixies_regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_user_pixies_regions` (
  `pixie_id` int(11) NOT NULL,
  `region_id` int(11) NOT NULL,
  PRIMARY KEY (`pixie_id`,`region_id`),
  KEY `IDX_9C16506531F7C64C` (`pixie_id`),
  KEY `IDX_9C16506598260155` (`region_id`),
  CONSTRAINT `FK_9C16506531F7C64C` FOREIGN KEY (`pixie_id`) REFERENCES `pxl_user_pixie` (`id`),
  CONSTRAINT `FK_9C16506598260155` FOREIGN KEY (`region_id`) REFERENCES `pxl_region` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_pixies_regions`
--

LOCK TABLES `pxl_user_pixies_regions` WRITE;
/*!40000 ALTER TABLE `pxl_user_pixies_regions` DISABLE KEYS */;
INSERT INTO `pxl_user_pixies_regions` VALUES (4,1),(4,9),(4,13),(8,3),(8,5),(8,11),(9,1),(9,3),(9,10),(9,13);
/*!40000 ALTER TABLE `pxl_user_pixies_regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_users_cardcategories`
--

DROP TABLE IF EXISTS `pxl_users_cardcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_users_cardcategories` (
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`category_id`),
  KEY `IDX_246DB33EA76ED395` (`user_id`),
  KEY `IDX_246DB33E12469DE2` (`category_id`),
  CONSTRAINT `FK_246DB33E12469DE2` FOREIGN KEY (`category_id`) REFERENCES `pxl_card_category` (`id`),
  CONSTRAINT `FK_246DB33EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `pxl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_users_cardcategories`
--

LOCK TABLES `pxl_users_cardcategories` WRITE;
/*!40000 ALTER TABLE `pxl_users_cardcategories` DISABLE KEYS */;
/*!40000 ALTER TABLE `pxl_users_cardcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_users_favorites`
--

DROP TABLE IF EXISTS `pxl_users_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_users_favorites` (
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`card_id`),
  KEY `IDX_8976DBEFA76ED395` (`user_id`),
  KEY `IDX_8976DBEF4ACC9A20` (`card_id`),
  CONSTRAINT `FK_8976DBEF4ACC9A20` FOREIGN KEY (`card_id`) REFERENCES `pxl_card` (`id`),
  CONSTRAINT `FK_8976DBEFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `pxl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_users_favorites`
--

LOCK TABLES `pxl_users_favorites` WRITE;
/*!40000 ALTER TABLE `pxl_users_favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `pxl_users_favorites` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-16 21:08:43

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
INSERT INTO `migration_versions` VALUES ('20180303171807'),('20180303180927'),('20180303184752'),('20180303191437'),('20180303201208'),('20180308235638'),('20180309202429'),('20180312151103'),('20180315093003'),('20180315112544'),('20180315141202'),('20180316164623'),('20180318140555'),('20180318141042'),('20180318160622'),('20180318162217'),('20180318184914'),('20180320131050'),('20180320132609'),('20180320140050'),('20180320141533'),('20180320142000'),('20180320173754'),('20180320182217'),('20180322185938'),('20180323120137'),('20180323181937'),('20180323190510'),('20180324183332'),('20180326134023'),('20180326150510'),('20180328125634'),('20180328175411'),('20180328181636'),('20180328181729'),('20180330121359'),('20180331095956'),('20180331103048'),('20180331191043'),('20180403133215');
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
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
INSERT INTO `pxl_admin` VALUES (1,'Adrien','Lamotte','adrien.lamotte@gmail.com','$2y$13$uSx4m2aV3wRtrO8pmS/ZWec7Gb0dXVjXEidL3oezobglOcUyDIvsC','[\"ROLE_ADMIN\", \"ROLE_MODERATOR\"]','2018-03-05 19:55:37','2018-03-09 21:45:43'),(5,'John','Doe','johndoe@gmail.com','$2y$13$BaTzX7tj3ira1QvQE94qNeUwfDBPdb4HLXywExrDinKoMXqBh4jIe','[]','2018-03-08 17:09:16','2018-03-09 21:45:35'),(6,'Jane','Doe','janedoe@gmail.com','$2y$13$qILAIu94x48YXBNUZMnUJOyev7h6zWjHkvoKRNLSB5.0xg9/Ax3HG','[\"ROLE_MODERATOR\"]','2018-03-08 17:57:36','2018-03-09 21:45:28'),(7,'James3','Doe','james@gmail.com','$2y$13$mvqTJiwiz0StKkZ2B7AgY.4Ffhmd0YQz6CbI7jdCtIxO.GrPGpuzG','null','2018-03-08 20:12:52','2018-03-09 01:00:42'),(10,'Adrien222','Lamotte222','adrien.lamotte2@gmail.com','$2y$13$qHQlPHnLe3V5Oja.PetMtu9v.awWe1I4SSgp86nBJYLvZsDsGrdr2','null','2018-03-09 00:22:21','2018-03-09 01:00:33'),(11,'test','test','test@test.com','$2y$13$efSuWv2rkZCuVTFmzRUYWepCZsTmXT9h144xWxs8ka/f6qtoS71wO','null','2018-03-09 18:48:41','2018-03-09 18:48:41'),(12,'TEST1','TEST1','test@bb.bb','$2y$13$RzXNVmTM2gJU4990/U1Smu1GkR03i6hrazBwRKJM74jiur/CH05Je','null','2018-03-09 19:01:51','2018-03-09 20:58:50'),(13,'toto','toto','toto@gmail.com','$2y$13$rpa5R6N4sFqqYVP6ZGgm/O1v.IgK0mYT7ugVce4dn0.sTODh4FPza','[\"ROLE_ADMIN\"]','2018-03-09 21:46:04','2018-03-09 21:46:04'),(14,'John','Modé','moderateur@pixielanes.com','$2y$13$hq/QjtFHPK8J7lwUCdhwUujo.sNeYPjsAuatcVHI3VUZMKZMcVJD6','[\"ROLE_MODERATOR\"]','2018-03-31 12:42:00','2018-03-31 12:42:00');
/*!40000 ALTER TABLE `pxl_admin` ENABLE KEYS */;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C7731134989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_category`
--

LOCK TABLES `pxl_card_category` WRITE;
/*!40000 ALTER TABLE `pxl_card_category` DISABLE KEYS */;
INSERT INTO `pxl_card_category` VALUES (7,0,'Nature','tree','nature','2018-03-24 17:23:19','2018-03-24 17:30:40'),(8,1,'Site touristique','university','site-touristique','2018-03-24 17:23:37','2018-03-24 20:06:07'),(9,2,'Manger','ustensils','manger','2018-03-24 17:23:57','2018-03-24 17:23:57'),(10,3,'Mobilité','wheelchair','mobilite','2018-03-24 17:24:16','2018-03-24 17:24:16'),(11,4,'Sport','futbol','sport','2018-03-24 17:24:31','2018-03-24 17:24:31'),(12,5,'Vie nocturne','glass-martini','vie-nocturne','2018-03-24 17:24:49','2018-03-24 17:24:49'),(13,6,'Art et culture','paint-brush','art-et-culture','2018-03-24 17:25:11','2018-03-24 17:25:11'),(14,7,'Shopping','shopping-bag','shopping','2018-03-24 17:25:27','2018-03-24 17:25:27'),(15,8,'Consommer local','local','consommer-local','2018-03-24 17:26:38','2018-03-24 17:26:38'),(16,9,'Enfants','child','enfants','2018-03-24 17:27:30','2018-03-24 17:27:30'),(17,10,'Green','leaf','green','2018-03-24 17:27:49','2018-03-24 17:27:49'),(18,11,'Dormir','bed','dormir','2018-03-24 17:28:10','2018-03-24 17:28:10'),(19,12,'Bien être et santé','sante','bien-etre-et-sante','2018-03-24 17:28:28','2018-03-24 17:28:28'),(20,13,'Événement','calendar-alt','evenement','2018-03-24 17:29:02','2018-03-24 17:29:02'),(21,14,'Éxpériences solidaires','users','experiences-solidaires','2018-03-24 17:29:30','2018-03-24 17:29:30');
/*!40000 ALTER TABLE `pxl_card_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_info`
--

DROP TABLE IF EXISTS `pxl_card_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_44A4336D5DA0FB8` (`template_id`),
  KEY `IDX_44A4336D166D1F9C` (`project_id`),
  CONSTRAINT `FK_44A4336D166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `pxl_card_project` (`id`),
  CONSTRAINT `FK_44A4336D5DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `pxl_card_template` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_info`
--

LOCK TABLES `pxl_card_info` WRITE;
/*!40000 ALTER TABLE `pxl_card_info` DISABLE KEYS */;
INSERT INTO `pxl_card_info` VALUES (1,3,'Test texte','text',NULL),(2,NULL,'zegfdgdf','text',1),(3,NULL,'Email de l\'établissement','email',4);
/*!40000 ALTER TABLE `pxl_card_info` ENABLE KEYS */;
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
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pixie_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_photos` int(11) NOT NULL,
  `min_videos` int(11) NOT NULL,
  `min_words` int(11) NOT NULL,
  `delivery_date` date NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_71A68ED098260155` (`region_id`),
  KEY `IDX_71A68ED0AE80F5DF` (`department_id`),
  KEY `IDX_71A68ED031F7C64C` (`pixie_id`),
  KEY `IDX_71A68ED0B03A8386` (`created_by_id`),
  KEY `IDX_71A68ED0896DBBDE` (`updated_by_id`),
  CONSTRAINT `FK_71A68ED031F7C64C` FOREIGN KEY (`pixie_id`) REFERENCES `pxl_user` (`id`),
  CONSTRAINT `FK_71A68ED0896DBBDE` FOREIGN KEY (`updated_by_id`) REFERENCES `pxl_admin` (`id`),
  CONSTRAINT `FK_71A68ED098260155` FOREIGN KEY (`region_id`) REFERENCES `pxl_region` (`id`),
  CONSTRAINT `FK_71A68ED0AE80F5DF` FOREIGN KEY (`department_id`) REFERENCES `pxl_region_department` (`id`),
  CONSTRAINT `FK_71A68ED0B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `pxl_admin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_project`
--

LOCK TABLES `pxl_card_project` WRITE;
/*!40000 ALTER TABLE `pxl_card_project` DISABLE KEYS */;
INSERT INTO `pxl_card_project` VALUES (1,'Hello card project 2 un nom un peu long',3,22,'validated',4,'<p><strong><em><u>TEST</u></em></strong></p>',2,2,3,'2018-03-09','<p>blabla</p>','2018-03-28 18:25:07','2018-04-03 14:31:56',1,1,20),(2,'test',3,22,'submitted',4,'<p>dccvvcvc</p>',0,0,0,'2018-03-13','<p>cvcv</p>','2018-03-30 13:41:10','2018-04-03 14:31:56',1,1,10),(3,'Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla',1,3,'refused',4,'<p>cwcwxcxw</p>',0,0,0,'2018-04-12','','2018-03-30 13:41:33','2018-04-03 14:31:56',1,1,0),(4,'Hey ! 11',3,23,'assigned',4,'<p>hey :!</p>',10,20,30,'2019-03-15','<p>Comment</p>','2018-03-30 17:27:22','2018-04-03 14:28:24',1,1,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_project_attachment`
--

LOCK TABLES `pxl_card_project_attachment` WRITE;
/*!40000 ALTER TABLE `pxl_card_project_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `pxl_card_project_attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_card_template`
--

DROP TABLE IF EXISTS `pxl_card_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_template`
--

LOCK TABLES `pxl_card_template` WRITE;
/*!40000 ALTER TABLE `pxl_card_template` DISABLE KEYS */;
INSERT INTO `pxl_card_template` VALUES (1,'Demo template edited','2018-03-26 15:44:17','2018-03-26 15:44:26'),(3,'Test...','2018-03-26 18:15:01','2018-03-26 18:15:01');
/*!40000 ALTER TABLE `pxl_card_template` ENABLE KEYS */;
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
INSERT INTO `pxl_cards_projects_categories` VALUES (1,7),(1,8),(1,9),(2,8),(2,14),(2,19),(3,10),(3,15),(3,21),(4,8),(4,16),(4,21);
/*!40000 ALTER TABLE `pxl_cards_projects_categories` ENABLE KEYS */;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E6CE2EB2989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_page`
--

LOCK TABLES `pxl_page` WRITE;
/*!40000 ALTER TABLE `pxl_page` DISABLE KEYS */;
INSERT INTO `pxl_page` VALUES (2,'Hello World !','cest-un-super-test-tres-long-hello','c\'est un super test \" très long !! hello','test','<p><img src=\"/upload/78c2bd4d9aaf4afc54ba46fc1fe39f15a7678116.jpeg\" style=\"width: 220px;\" class=\"fr-fic fr-dib\"></p><p><br></p>',0,'2018-03-12 18:19:20','2018-03-15 13:52:02'),(3,'aaa','cest-un-super-test-tres-long-hello-1','aa','aa','<p>aaaa</p>',1,'2018-03-15 12:26:24','2018-03-15 13:57:06'),(4,'bbb','bbbb','bbbb','bbb','<p>bb</p>',1,'2018-03-15 12:58:01','2018-03-15 13:56:40'),(5,'aa','test-de-meta-title','test de meta title','bbbb','<p>ddddd</p>',1,'2018-03-15 13:49:25','2018-03-15 13:49:36'),(6,'fdqfds','sdfsd-sqg-dfgdfg-sdf-gfd-aaaaaa','sdfsd sqg dfgdfg sdf gfd aaaaaa','gsd','<p>df gsdf</p>',1,'2018-03-15 14:04:52','2018-03-24 16:58:38');
/*!40000 ALTER TABLE `pxl_page` ENABLE KEYS */;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B2C1F05C989D9B62` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_region`
--

LOCK TABLES `pxl_region` WRITE;
/*!40000 ALTER TABLE `pxl_region` DISABLE KEYS */;
INSERT INTO `pxl_region` VALUES (1,0,'Auvergne-Rhône-Alpes','auvergne-rhone-alpes','2018-03-24 19:37:35','2018-03-28 16:14:31'),(2,1,'Bourgogne-Franche-Comté','bourgogne-franche-comte','2018-03-24 19:37:46','2018-03-28 16:14:24'),(3,2,'Bretagne','bretagne','2018-03-24 19:37:55','2018-03-24 19:37:55'),(4,3,'Centre-Val de Loire','centre-val-de-loire','2018-03-24 19:38:07','2018-03-24 19:38:07'),(5,4,'Corse','corse','2018-03-24 19:38:16','2018-03-24 19:38:16'),(6,5,'Grand Est','grand-est','2018-03-24 19:38:24','2018-03-24 19:38:24'),(7,6,'Hauts-de-France','hauts-de-france','2018-03-24 19:38:32','2018-03-24 19:38:32'),(8,7,'Ile-de-France','ile-de-france','2018-03-24 19:38:42','2018-03-24 19:38:42'),(9,8,'Normandie','normandie','2018-03-24 19:38:48','2018-03-24 19:38:48'),(10,9,'Nouvelle Aquitaine','nouvelle-aquitaine','2018-03-24 19:38:56','2018-03-24 19:38:56'),(11,10,'Occitanie','occitanie','2018-03-24 19:39:02','2018-03-24 19:39:02'),(12,11,'Pays de Loire','pays-de-loire','2018-03-24 19:39:11','2018-03-24 19:39:11'),(13,12,'Provence-Alpes-Côtes d\'Azur','provence-alpes-cotes-dazur','2018-03-24 19:39:18','2018-03-24 19:39:18');
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
INSERT INTO `pxl_region_department` VALUES (1,1,0,'Ain','ain','2018-03-28 16:27:35','2018-03-28 17:30:34'),(3,1,1,'Allier','allier','2018-03-28 16:37:34','2018-03-28 17:31:00'),(4,1,2,'Ardèche','ardeche','2018-03-28 16:37:50','2018-03-28 16:37:50'),(5,1,3,'Cantal','cantal','2018-03-28 16:37:55','2018-03-28 16:37:55'),(6,1,4,'Drôme','drome','2018-03-28 16:38:02','2018-03-28 16:38:02'),(7,1,5,'Isère','isere','2018-03-28 16:38:09','2018-03-28 16:38:09'),(8,1,6,'Loire','loire','2018-03-28 16:38:15','2018-03-28 16:38:15'),(9,1,7,'Haute-Loire','haute-loire','2018-03-28 16:38:21','2018-03-28 16:38:21'),(10,1,8,'Puy-de-Dôme','puy-de-dome','2018-03-28 16:38:30','2018-03-28 16:38:30'),(11,1,9,'Rhône','rhone','2018-03-28 16:38:35','2018-03-28 16:38:35'),(12,1,10,'Savoie','savoie','2018-03-28 16:38:47','2018-03-28 16:38:47'),(13,1,11,'Haute-Savoie','haute-savoie','2018-03-28 16:38:52','2018-03-28 16:38:52'),(14,2,12,'Côte-d\'Or','cote-dor','2018-03-28 16:41:53','2018-03-28 17:30:21'),(15,2,13,'Doubs','doubs','2018-03-28 16:41:57','2018-03-28 17:30:54'),(16,2,14,'Jura','jura','2018-03-28 16:42:03','2018-03-28 16:42:03'),(17,2,15,'Nièvre','nievre','2018-03-28 16:42:07','2018-03-28 16:42:07'),(18,2,16,'Haute-Saône','haute-saone','2018-03-28 16:42:12','2018-03-28 16:42:12'),(19,2,17,'Saône-et-Loire','saone-et-loire','2018-03-28 16:42:17','2018-03-28 16:42:17'),(20,2,18,'Yonne','yonne','2018-03-28 16:42:24','2018-03-28 16:42:24'),(21,2,19,'Territoire de Belfort','territoire-de-belfort','2018-03-28 16:42:30','2018-03-28 16:42:30'),(22,3,20,'Côtes-d\'Armor','cotes-darmor','2018-03-28 16:42:44','2018-03-28 16:42:44'),(23,3,21,'Finistère','finistere','2018-03-28 16:42:49','2018-03-28 16:42:49'),(24,3,22,'Ille-et-Vilaine','ille-et-vilaine','2018-03-28 16:42:57','2018-03-28 16:42:57'),(25,3,23,'Morbihan','morbihan','2018-03-28 16:43:02','2018-03-28 16:43:02'),(26,4,24,'Cher','cher','2018-03-28 16:43:29','2018-03-28 16:43:29'),(27,4,25,'Eure-et-Loir','eure-et-loir','2018-03-28 16:43:33','2018-03-28 16:43:33'),(28,4,26,'Indre','indre','2018-03-28 16:43:38','2018-03-28 16:43:38'),(29,4,27,'Indre-et-Loire','indre-et-loire','2018-03-28 16:43:43','2018-03-28 16:43:43'),(30,4,28,'Loir-et-Cher','loir-et-cher','2018-03-28 16:43:50','2018-03-28 16:43:50'),(31,4,29,'Loiret','loiret','2018-03-28 16:43:56','2018-03-28 16:43:56'),(32,5,30,'Corse-du-Sud','corse-du-sud','2018-03-28 16:44:10','2018-03-28 16:44:10'),(33,5,31,'Haute-Corse','haute-corse','2018-03-28 16:44:16','2018-03-28 16:44:16'),(34,6,32,'Ardennes','ardennes','2018-03-28 16:44:32','2018-03-28 16:44:32'),(35,6,33,'Aube','aube','2018-03-28 16:44:38','2018-03-28 16:44:38'),(36,6,34,'Marne','marne','2018-03-28 16:44:43','2018-03-28 16:44:43'),(37,6,35,'Haute-Marne','haute-marne','2018-03-28 16:44:48','2018-03-28 16:44:48'),(38,6,36,'Meurthe-et-Moselle','meurthe-et-moselle','2018-03-28 16:44:53','2018-03-28 16:44:53'),(39,6,37,'Meuse','meuse','2018-03-28 16:44:58','2018-03-28 16:44:58'),(40,6,38,'Moselle','moselle','2018-03-28 16:45:04','2018-03-28 16:45:04'),(41,6,39,'Bas-Rhin','bas-rhin','2018-03-28 16:45:09','2018-03-28 16:45:09'),(42,6,40,'Haut-Rhin','haut-rhin','2018-03-28 16:45:15','2018-03-28 16:45:15'),(43,6,41,'Vosges','vosges','2018-03-28 16:45:20','2018-03-28 16:45:20'),(44,7,42,'Aisne','aisne','2018-03-28 16:46:05','2018-03-28 16:46:05'),(45,7,43,'Nord','nord','2018-03-28 16:46:10','2018-03-28 16:46:10'),(46,7,44,'Oise','oise','2018-03-28 16:46:15','2018-03-28 16:46:15'),(47,7,45,'Pas-de-Calais','pas-de-calais','2018-03-28 16:46:20','2018-03-28 16:46:20'),(48,7,46,'Somme','somme','2018-03-28 16:46:26','2018-03-28 16:46:26'),(49,8,47,'Paris','paris','2018-03-28 16:46:37','2018-03-28 16:46:37'),(50,8,48,'Seine-et-Marne','seine-et-marne','2018-03-28 16:46:43','2018-03-28 16:46:43'),(51,8,49,'Yvelines','yvelines','2018-03-28 16:46:50','2018-03-28 16:46:50'),(52,8,50,'Essonne','essonne','2018-03-28 16:46:57','2018-03-28 16:46:57'),(53,8,51,'Hauts-de-Seine','hauts-de-seine','2018-03-28 16:47:02','2018-03-28 16:47:02'),(54,8,52,'Seine-Saint-Denis','seine-saint-denis','2018-03-28 16:47:07','2018-03-28 16:47:07'),(55,8,53,'Val-de-Marne','val-de-marne','2018-03-28 16:47:13','2018-03-28 16:47:13'),(56,8,54,'Val-d\'Oise','val-doise','2018-03-28 16:47:19','2018-03-28 16:47:19'),(57,9,55,'Manche','manche','2018-03-28 16:47:31','2018-03-28 16:47:31'),(58,9,56,'Orne','orne','2018-03-28 16:47:35','2018-03-28 16:47:35'),(59,9,57,'Seine-Maritime','seine-maritime','2018-03-28 16:47:41','2018-03-28 16:47:41'),(60,10,58,'Charente','charente','2018-03-28 16:47:53','2018-03-28 16:47:53'),(61,10,59,'Charente-Maritime','charente-maritime','2018-03-28 16:47:57','2018-03-28 16:47:57'),(62,10,60,'Corrèze','correze','2018-03-28 17:19:41','2018-03-28 17:19:41'),(63,10,61,'Creuse','creuse','2018-03-28 17:19:46','2018-03-28 17:19:46'),(64,10,62,'Dordogne','dordogne','2018-03-28 17:19:50','2018-03-28 17:19:50'),(65,10,63,'Gironde','gironde','2018-03-28 17:19:54','2018-03-28 17:19:54'),(66,10,64,'Landes','landes','2018-03-28 17:20:01','2018-03-28 17:20:01'),(67,10,65,'Lot-et-Garonne','lot-et-garonne','2018-03-28 17:20:06','2018-03-28 17:20:06'),(68,10,66,'Pyrénées-Atlantiques','pyrenees-atlantiques','2018-03-28 17:20:12','2018-03-28 17:20:12'),(69,10,67,'Deux-Sèvres','deux-sevres','2018-03-28 17:20:21','2018-03-28 17:20:21'),(70,10,68,'Vienne','vienne','2018-03-28 17:20:27','2018-03-28 17:20:27'),(71,10,69,'Haute-Vienne','haute-vienne','2018-03-28 17:20:32','2018-03-28 17:20:32'),(72,11,70,'Ariège','ariege','2018-03-28 17:20:56','2018-03-28 17:20:56'),(73,11,71,'Aude','aude','2018-03-28 17:21:01','2018-03-28 17:21:01'),(74,11,72,'Aveyron','aveyron','2018-03-28 17:21:05','2018-03-28 17:21:05'),(75,11,73,'Gard','gard','2018-03-28 17:21:11','2018-03-28 17:21:11'),(76,11,74,'Haute-Garonne','haute-garonne','2018-03-28 17:21:18','2018-03-28 17:21:18'),(77,11,75,'Gers','gers','2018-03-28 17:21:25','2018-03-28 17:21:25'),(78,11,76,'Hérault','herault','2018-03-28 17:21:31','2018-03-28 17:21:31'),(79,11,77,'Lot','lot','2018-03-28 17:21:41','2018-03-28 17:21:41'),(80,11,78,'Lozère','lozere','2018-03-28 17:21:45','2018-03-28 17:21:45'),(81,11,79,'Hautes-Pyrénées','hautes-pyrenees','2018-03-28 17:21:50','2018-03-28 17:21:50'),(82,11,80,'Pyrénées-Orientales','pyrenees-orientales','2018-03-28 17:21:59','2018-03-28 17:21:59'),(83,11,81,'Tarn','tarn','2018-03-28 17:22:04','2018-03-28 17:22:04'),(84,11,82,'Tarn-et-Garonne','tarn-et-garonne','2018-03-28 17:22:09','2018-03-28 17:22:09'),(85,12,83,'Loire-Atlantique','loire-atlantique','2018-03-28 17:22:28','2018-03-28 17:22:28'),(86,12,84,'Maine-et-Loire','maine-et-loire','2018-03-28 17:22:33','2018-03-28 17:22:33'),(87,12,85,'Mayenne','mayenne','2018-03-28 17:22:38','2018-03-28 17:22:38'),(88,12,86,'Sarthe','sarthe','2018-03-28 17:22:42','2018-03-28 17:22:42'),(89,12,87,'Vendée','vendee','2018-03-28 17:22:48','2018-03-28 17:22:48'),(90,13,88,'Alpes-de-Haute-Provence','alpes-de-haute-provence','2018-03-28 17:23:02','2018-03-28 17:23:02'),(91,13,89,'Hautes-Alpes','hautes-alpes','2018-03-28 17:23:09','2018-03-28 17:23:09'),(92,13,90,'Alpes-Maritimes','alpes-maritimes','2018-03-28 17:23:14','2018-03-28 17:23:14'),(93,13,91,'Bouches-du-Rhône','bouches-du-rhone','2018-03-28 17:23:18','2018-03-28 17:23:18'),(94,13,92,'Var','var','2018-03-28 17:23:23','2018-03-28 17:23:23'),(95,13,93,'Vaucluse','vaucluse','2018-03-28 17:23:28','2018-03-28 17:23:28');
/*!40000 ALTER TABLE `pxl_region_department` ENABLE KEYS */;
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
INSERT INTO `pxl_user` VALUES (1,'Adrien','Lamotte','0642482947','1906-06-11','75014','91180','female','adrien.lamotte@gmail.com','$2y$13$MuCx.KnIHZCHhGYapDVZt.VY3OzSV5y6mXwW5UVlrIC5t8np8Aov2','392fe3500bd92e197d0db2fdca526c41.jpeg',NULL,'[\"ROLE_USER\"]','2018-03-16 19:11:29','2018-03-18 18:49:39'),(2,'Adrien','Lamotte','642482947','1986-01-01','75014','Doe','male','adrien.lamottdrdrdfe@gmail.com','$2y$13$VYsR7jjF1eBIxocBCq/zJuyDgyhUAG4qz7r4IZMB9axCWubPtMA1.','6f17c7e1109143564ef29601cd81eee4.jpeg',NULL,'[]','2018-03-19 15:27:07','2018-03-19 15:27:07'),(3,'Adrien','Lamotte','642482947','2010-07-01','zqsd','qdsdsq','male','adrien.lamottedsfsdfdd@gmail.com','$2y$13$zfRScF5rpQU1AZLIWpC1UeL.ja76vuJN12brsE91ejdWmnX0fVgA2','09694d4f223ad6f1e95697c2479b10da.jpeg',NULL,'[]','2018-03-19 16:00:32','2018-03-24 18:46:00'),(4,'Adrien','Lamotte','642482947','1902-01-01','ffgdfgd dfs','df gsdgdf g','male','lamotte@gmail.com','$2y$13$F2MpRhkAOmHC4o3g9Uur5OjmmtStdzTtqx1KbuKxVSCKCHvxdUzcK','82373de6f27e2ba7a9e0e82a6a9fb4ff.jpeg',1,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-19 18:41:39','2018-03-20 15:16:54'),(5,'test','test','06042621510','1902-01-01','dfsf','sdfsdfsd','female','test@test.com','$2y$13$m6JqAQnlRImJDyOk8XQW1.fPEShBeHP07.eyy5xU8a1w9Gr.YV4Fe','4848952cdf8ca346ba6475cc5c7348ad.jpeg',3,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 15:21:28','2018-03-20 18:47:38'),(6,'Hello','Pixie','aa','1902-01-01','aa','aa','male','hellopixie@gmail.com','$2y$13$oud5B1W0yBB2hFr1KV/Fausht/NdwFe12IuI1QXintRbsj.HDE.IW','e25f8d0c647c4871034911ba06ea7fe0.jpeg',4,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 18:51:51','2018-03-20 18:51:51'),(7,'aaaaa','Lamotte','642482947','1986-01-01','a','a','male','adrien.lamotte222@gmail.com','$2y$13$VK8Gu7fNZ13jlVwyn78/OeYPMTkj60L/iXqNpmQSSM9C.WckfKj5.','eed43964480ff15483e4e4551c0d1fa1.pdf',7,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 20:12:30','2018-03-21 15:20:52'),(8,'Adrien','Lamotte','642482947','1986-01-01','a','78610','male','adrien.lamotteqrthe@gmail.com','$2y$13$BzKdCT19maiKtavg56ynqeb.ni68I4thwusxCnHlVe/X/xubwWmIO','6689254553de34338c615dbbb591d8d4.jpeg',8,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-21 15:24:26','2018-03-21 15:27:05'),(9,'z','z','z','1902-01-01','z','z','male','zzz@zz.zz','$2y$13$JgHDp/eaKuoQsPgGCFQFHexb8SGHAF2abjrJyFSr0H/RURTG0Ud/K','9ccedba538fec83835c1044cab56f295.jpeg',NULL,'[]','2018-03-21 16:19:17','2018-03-21 16:19:17'),(10,'A','B','+33642482947','1986-01-01','a','a','female','adrien.lamotte@gmail.com','$2y$13$c5DtXi5ipxLqQuhSeKp73eAno.OM4RsOyW5yNVFieoeCsEoCaFdOC','585f59da1a162e6ca330329d4dca03b1.jpeg',9,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-21 17:04:11','2018-03-29 20:07:10');
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
INSERT INTO `pxl_user_link` VALUES (9,1,'http://www.google.fr','TEST3333'),(11,1,'http://www.dsfsdf2.fr','gggg'),(12,2,'http://www.google1111.fr','222'),(13,2,'http://www.google3333.fr','444'),(17,4,'http://www.facebook.fr','facebook'),(19,4,'http://www.twitter.fr','twitter'),(20,4,'http://instagram.fr','instagram'),(21,7,'http://www.facebbok.fr','facebook');
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
  `address` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_country` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_iban` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_bic` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rib` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_pixie_billing`
--

LOCK TABLES `pxl_user_pixie_billing` WRITE;
/*!40000 ALTER TABLE `pxl_user_pixie_billing` DISABLE KEYS */;
INSERT INTO `pxl_user_pixie_billing` VALUES (1,'Mon statut','','','','','','','','','','','','','','',''),(3,'company','a','b','c','d','e','f','g','h','i','banktransfer','k','l','m','n','e3af9c43b116ee29cdc2389ed42aa4e4.pdf'),(4,'test','test','test','test','test','test','test','test','test','test','test','test','test','test','test','test'),(6,'individualregistration','a','b','c','d','e','f','g','h','i','banktransfer','k','l','m','n','8426318ab3dbe98662ccf343d413d984.png'),(7,'company','a','a','a','a','a','a','a','a','a','check','a','a','a','a','a59c2471549ac2e08d01b4d300107bfa.pdf'),(8,'individualregistration','','A','B','AAAAA','28 Rue de l\'Avenir','91180','Saint-Germain-lès-Arpajon','France','06','check','11111111111','','','','');
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
INSERT INTO `pxl_users_cardcategories` VALUES (3,9);
/*!40000 ALTER TABLE `pxl_users_cardcategories` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-04 13:38:21

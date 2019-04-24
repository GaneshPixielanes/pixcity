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
INSERT INTO `migration_versions` VALUES ('20180303171807'),('20180303180927'),('20180303184752'),('20180303191437'),('20180303201208'),('20180308235638'),('20180309202429'),('20180312151103'),('20180315093003'),('20180315112544'),('20180315141202'),('20180316164623'),('20180318140555'),('20180318141042'),('20180318160622'),('20180318162217'),('20180318184914');
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_admin`
--

LOCK TABLES `pxl_admin` WRITE;
/*!40000 ALTER TABLE `pxl_admin` DISABLE KEYS */;
INSERT INTO `pxl_admin` VALUES (1,'Adrien','Lamotte','adrien.lamotte@gmail.com','$2y$13$uSx4m2aV3wRtrO8pmS/ZWec7Gb0dXVjXEidL3oezobglOcUyDIvsC','[\"ROLE_ADMIN\", \"ROLE_MODERATOR\"]','2018-03-05 19:55:37','2018-03-09 21:45:43'),(5,'John','Doe','johndoe@gmail.com','$2y$13$BaTzX7tj3ira1QvQE94qNeUwfDBPdb4HLXywExrDinKoMXqBh4jIe','[]','2018-03-08 17:09:16','2018-03-09 21:45:35'),(6,'Jane','Doe','janedoe@gmail.com','$2y$13$qILAIu94x48YXBNUZMnUJOyev7h6zWjHkvoKRNLSB5.0xg9/Ax3HG','[\"ROLE_MODERATOR\"]','2018-03-08 17:57:36','2018-03-09 21:45:28'),(7,'James3','Doe','james@gmail.com','$2y$13$mvqTJiwiz0StKkZ2B7AgY.4Ffhmd0YQz6CbI7jdCtIxO.GrPGpuzG','null','2018-03-08 20:12:52','2018-03-09 01:00:42'),(10,'Adrien222','Lamotte222','adrien.lamotte2@gmail.com','$2y$13$qHQlPHnLe3V5Oja.PetMtu9v.awWe1I4SSgp86nBJYLvZsDsGrdr2','null','2018-03-09 00:22:21','2018-03-09 01:00:33'),(11,'test','test','test@test.com','$2y$13$efSuWv2rkZCuVTFmzRUYWepCZsTmXT9h144xWxs8ka/f6qtoS71wO','null','2018-03-09 18:48:41','2018-03-09 18:48:41'),(12,'TEST1','TEST1','test@bb.bb','$2y$13$RzXNVmTM2gJU4990/U1Smu1GkR03i6hrazBwRKJM74jiur/CH05Je','null','2018-03-09 19:01:51','2018-03-09 20:58:50'),(13,'toto','toto','toto@gmail.com','$2y$13$rpa5R6N4sFqqYVP6ZGgm/O1v.IgK0mYT7ugVce4dn0.sTODh4FPza','[\"ROLE_ADMIN\"]','2018-03-09 21:46:04','2018-03-09 21:46:04');
/*!40000 ALTER TABLE `pxl_admin` ENABLE KEYS */;
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
INSERT INTO `pxl_page` VALUES (2,'Hello World !','cest-un-super-test-tres-long-hello','c\'est un super test \" très long !! hello','test','<p><img src=\"/upload/78c2bd4d9aaf4afc54ba46fc1fe39f15a7678116.jpeg\" style=\"width: 220px;\" class=\"fr-fic fr-dib\"></p><p><br></p>',0,'2018-03-12 18:19:20','2018-03-15 13:52:02'),(3,'aaa','cest-un-super-test-tres-long-hello-1','aa','aa','<p>aaaa</p>',1,'2018-03-15 12:26:24','2018-03-15 13:57:06'),(4,'bbb','bbbb','bbbb','bbb','<p>bb</p>',1,'2018-03-15 12:58:01','2018-03-15 13:56:40'),(5,'aa','test-de-meta-title','test de meta title','bbbb','<p>ddddd</p>',1,'2018-03-15 13:49:25','2018-03-15 13:49:36'),(6,'fdqfds','sdfsd-sqg-dfgdfg-sdf-gfd','sdfsd sqg dfgdfg sdf gfd','gsd','<p>df gsdf</p>',1,'2018-03-15 14:04:52','2018-03-15 14:04:52');
/*!40000 ALTER TABLE `pxl_page` ENABLE KEYS */;
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
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` json NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user`
--

LOCK TABLES `pxl_user` WRITE;
/*!40000 ALTER TABLE `pxl_user` DISABLE KEYS */;
INSERT INTO `pxl_user` VALUES (1,'Adrien','Lamotte','0642482947','1906-06-11','75014','91180','female','adrien.lamotte@gmail.com','$2y$13$MuCx.KnIHZCHhGYapDVZt.VY3OzSV5y6mXwW5UVlrIC5t8np8Aov2','392fe3500bd92e197d0db2fdca526c41.jpeg','{\"0\": \"ROLE_USER\", \"1\": \"ROLE_PIXIE\"}','2018-03-16 19:11:29','2018-03-18 18:49:39'),(2,'Adrien','Lamotte','642482947','1986-01-01','75014','Doe','male','adrien.lamottdrdrdfe@gmail.com','$2y$13$VYsR7jjF1eBIxocBCq/zJuyDgyhUAG4qz7r4IZMB9axCWubPtMA1.','6f17c7e1109143564ef29601cd81eee4.jpeg','[]','2018-03-19 15:27:07','2018-03-19 15:27:07'),(3,'Adrien','Lamotte','642482947','2010-07-01','zqsd','qdsdsq','male','adrien.lamottedsfsdfdd@gmail.com','$2y$13$zfRScF5rpQU1AZLIWpC1UeL.ja76vuJN12brsE91ejdWmnX0fVgA2','09694d4f223ad6f1e95697c2479b10da.jpeg','[]','2018-03-19 16:00:32','2018-03-19 16:00:32'),(4,'Adrien','Lamotte','642482947','1902-01-01','ffgdfgd dfs','df gsdgdf g','male','lamotte@gmail.com','$2y$13$F2MpRhkAOmHC4o3g9Uur5OjmmtStdzTtqx1KbuKxVSCKCHvxdUzcK','82373de6f27e2ba7a9e0e82a6a9fb4ff.jpeg','[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-19 18:41:39','2018-03-19 18:41:39');
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_link`
--

LOCK TABLES `pxl_user_link` WRITE;
/*!40000 ALTER TABLE `pxl_user_link` DISABLE KEYS */;
INSERT INTO `pxl_user_link` VALUES (9,1,'http://www.google.fr','TEST3333'),(11,1,'http://www.dsfsdf2.fr','gggg'),(12,2,'http://www.google1111.fr','222'),(13,2,'http://www.google3333.fr','444'),(14,3,'http://www.google4.fr','test typee'),(15,3,'http://www.google3333.fr','dfsfsdfsdfsdfsdfsdfsddfd'),(16,3,'http://www.dsfsdf.fr','sdfsdfsddssdfsdfsdfdsfsdf sfsd fsdf sfsd f'),(17,4,'http://www.facebook.fr','facebook'),(19,4,'http://www.twitter.fr','twitter'),(20,4,'http://instagram.fr','instagram');
/*!40000 ALTER TABLE `pxl_user_link` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-19 20:05:17

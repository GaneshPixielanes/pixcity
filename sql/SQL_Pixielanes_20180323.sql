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
INSERT INTO `migration_versions` VALUES ('20180303171807'),('20180303180927'),('20180303184752'),('20180303191437'),('20180303201208'),('20180308235638'),('20180309202429'),('20180312151103'),('20180315093003'),('20180315112544'),('20180315141202'),('20180316164623'),('20180318140555'),('20180318141042'),('20180318160622'),('20180318162217'),('20180318184914'),('20180320131050'),('20180320132609'),('20180320140050'),('20180320141533'),('20180320142000'),('20180320173754'),('20180320182217'),('20180322185938'),('20180323120137');
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
-- Table structure for table `pxl_card_category`
--

DROP TABLE IF EXISTS `pxl_card_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_card_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_category`
--

LOCK TABLES `pxl_card_category` WRITE;
/*!40000 ALTER TABLE `pxl_card_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `pxl_card_category` ENABLE KEYS */;
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
INSERT INTO `pxl_user` VALUES (1,'Adrien','Lamotte','0642482947','1906-06-11','75014','91180','female','adrien.lamotte@gmail.com','$2y$13$MuCx.KnIHZCHhGYapDVZt.VY3OzSV5y6mXwW5UVlrIC5t8np8Aov2','392fe3500bd92e197d0db2fdca526c41.jpeg',NULL,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-16 19:11:29','2018-03-18 18:49:39'),(2,'Adrien','Lamotte','642482947','1986-01-01','75014','Doe','male','adrien.lamottdrdrdfe@gmail.com','$2y$13$VYsR7jjF1eBIxocBCq/zJuyDgyhUAG4qz7r4IZMB9axCWubPtMA1.','6f17c7e1109143564ef29601cd81eee4.jpeg',NULL,'[]','2018-03-19 15:27:07','2018-03-19 15:27:07'),(3,'Adrien','Lamotte','642482947','2010-07-01','zqsd','qdsdsq','male','adrien.lamottedsfsdfdd@gmail.com','$2y$13$zfRScF5rpQU1AZLIWpC1UeL.ja76vuJN12brsE91ejdWmnX0fVgA2','09694d4f223ad6f1e95697c2479b10da.jpeg',NULL,'[]','2018-03-19 16:00:32','2018-03-19 16:00:32'),(4,'Adrien','Lamotte','642482947','1902-01-01','ffgdfgd dfs','df gsdgdf g','male','lamotte@gmail.com','$2y$13$F2MpRhkAOmHC4o3g9Uur5OjmmtStdzTtqx1KbuKxVSCKCHvxdUzcK','82373de6f27e2ba7a9e0e82a6a9fb4ff.jpeg',1,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-19 18:41:39','2018-03-20 15:16:54'),(5,'test','test','06042621510','1902-01-01','dfsf','sdfsdfsd','female','test@test.com','$2y$13$m6JqAQnlRImJDyOk8XQW1.fPEShBeHP07.eyy5xU8a1w9Gr.YV4Fe','4848952cdf8ca346ba6475cc5c7348ad.jpeg',3,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 15:21:28','2018-03-20 18:47:38'),(6,'Hello','Pixie','aa','1902-01-01','aa','aa','male','hellopixie@gmail.com','$2y$13$oud5B1W0yBB2hFr1KV/Fausht/NdwFe12IuI1QXintRbsj.HDE.IW','e25f8d0c647c4871034911ba06ea7fe0.jpeg',4,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 18:51:51','2018-03-20 18:51:51'),(7,'aaaaa','Lamotte','642482947','1986-01-01','a','a','male','adrien.lamotte222@gmail.com','$2y$13$VK8Gu7fNZ13jlVwyn78/OeYPMTkj60L/iXqNpmQSSM9C.WckfKj5.','eed43964480ff15483e4e4551c0d1fa1.pdf',7,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 20:12:30','2018-03-21 15:20:52'),(8,'Adrien','Lamotte','642482947','1986-01-01','a','78610','male','adrien.lamotteqrthe@gmail.com','$2y$13$BzKdCT19maiKtavg56ynqeb.ni68I4thwusxCnHlVe/X/xubwWmIO','6689254553de34338c615dbbb591d8d4.jpeg',8,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-21 15:24:26','2018-03-21 15:27:05'),(9,'z','z','z','1902-01-01','z','z','male','zzz@zz.zz','$2y$13$JgHDp/eaKuoQsPgGCFQFHexb8SGHAF2abjrJyFSr0H/RURTG0Ud/K','9ccedba538fec83835c1044cab56f295.jpeg',NULL,'[]','2018-03-21 16:19:17','2018-03-21 16:19:17'),(10,'Adrien','Lamotte','642482947','1986-01-01','a','a','male','adrien.lamottccccce@gmail.com','$2y$13$c5DtXi5ipxLqQuhSeKp73eAno.OM4RsOyW5yNVFieoeCsEoCaFdOC','585f59da1a162e6ca330329d4dca03b1.jpeg',9,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-21 17:04:11','2018-03-22 17:50:05');
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
INSERT INTO `pxl_user_link` VALUES (9,1,'http://www.google.fr','TEST3333'),(11,1,'http://www.dsfsdf2.fr','gggg'),(12,2,'http://www.google1111.fr','222'),(13,2,'http://www.google3333.fr','444'),(14,3,'http://www.google4.fr','test typee'),(15,3,'http://www.google3333.fr','dfsfsdfsdfsdfsdfsdfsddfd'),(16,3,'http://www.dsfsdf.fr','sdfsdfsddssdfsdfsdfdsfsdf sfsd fsdf sfsd f'),(17,4,'http://www.facebook.fr','facebook'),(19,4,'http://www.twitter.fr','twitter'),(20,4,'http://instagram.fr','instagram'),(21,7,'http://www.facebbok.fr','facebook');
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-23 17:42:11

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
INSERT INTO `migration_versions` VALUES ('20180303171807'),('20180303180927'),('20180303184752'),('20180303191437'),('20180303201208'),('20180308235638'),('20180309202429'),('20180312151103'),('20180315093003'),('20180315112544'),('20180315141202'),('20180316164623'),('20180318140555'),('20180318141042'),('20180318160622'),('20180318162217'),('20180318184914'),('20180320131050'),('20180320132609'),('20180320140050'),('20180320141533'),('20180320142000'),('20180320173754'),('20180320182217'),('20180322185938'),('20180323120137'),('20180323181937'),('20180323190510'),('20180324183332'),('20180326134023'),('20180326150510'),('20180328125634'),('20180328175411'),('20180328181636'),('20180328181729'),('20180330121359'),('20180331095956'),('20180331103048'),('20180331191043'),('20180403133215'),('20180404123712'),('20180404151246'),('20180404151357'),('20180404151414'),('20180404154417'),('20180404155516'),('20180405120141'),('20180405163332'),('20180405174526'),('20180406125800'),('20180406140336'),('20180406161252'),('20180406164803'),('20180406175527'),('20180406202107'),('20180407102623'),('20180409111401'),('20180409141416'),('20180410142742'),('20180410153550'),('20180411115813'),('20180412133314'),('20180412135110'),('20180412140709'),('20180412160212'),('20180412171453'),('20180412174929'),('20180413131524'),('20180413134403'),('20180413172624'),('20180413180340'),('20180415085548'),('20180415175015'),('20180415175610'),('20180415191123'),('20180622170626'),('20180622181513'),('20180622182838'),('20180626165459'),('20180628161404'),('20180629131957'),('20180630114926');
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_address`
--

LOCK TABLES `pxl_address` WRITE;
/*!40000 ALTER TABLE `pxl_address` DISABLE KEYS */;
INSERT INTO `pxl_address` VALUES (2,'28 Rue de l\'Avenir','92170','Vanves','France','',''),(3,'Bois de Turelle','91490','Courances','France','48.4356867','2.501465199999984'),(4,'19 La ville Neuve','22800','Saint-Donan','France','48.4511744','-2.9212552999999843'),(5,'28 Rue de l\'Avenir','91180','ST GERMAIN LES ARPAJON','France','',''),(6,'28 Rue de l\'Avenir','92170','Vanves','France','',''),(7,'Unnamed Road','61550','Couvains','France','48.87085339999999','0.5483636999999817'),(8,'38 La Jaunet','41290','Viévy-le-Rayé','France','47.8911386','1.342058599999973'),(9,'Avenue de Barsbuttel','29490','Guipavas','France','48.4350422','-4.414787599999954'),(10,'Les Aunays','35850','Gévezé','France','48.1695404','-1.8216555000000199'),(11,'487 Route des Mailles','76480','Saint-Pierre-de-Varengeville','France','49.5054248','0.94672259999993'),(12,'D65','51460','Somme-Vesle','France','48.9877593','4.577601299999969'),(13,'D19.7','28310','Santilly','France','48.1425919','1.9126433000000134'),(14,'4 Rue Willy Brandt','77184','Émerainville','France','48.8104997','2.614909300000022'),(15,'D84','37310','Reignac-sur-Indre','France','47.2088916','0.8775256000000127'),(16,'D281','49520','Combrée','France','47.7155064','-1.0112080000000105'),(17,'Saint-Michel','44810','Héric','France','47.4487287','-1.6256571000000122'),(18,'947 Rue Grande Rue','76110','Bénarville','France','49.6737498','0.4838377999999466'),(19,'D33','22140','Prat','France','48.684798','-3.318136699999968'),(20,'16 Avenue Ho Chi Minh','56600','Lanester','France','47.7754833','-3.338484900000026'),(21,'Pembo','56500','Remungol','France','47.9378596','-2.878635099999997'),(22,'483-484 La Grande Boissière','29140','Rosporden','France','47.9664098','-3.779874100000029'),(23,'7 Saint-Doué','56230','Questembert','France','47.6713528','-2.4822642000000315'),(24,'308 Ménez Lavarec','29710','Peumerit','France','47.9663376','-4.284588799999938'),(25,'27 Rue du Haut de Genève','77260','Ussy-sur-Marne','France','48.955731','3.1051886000000195'),(26,'925 Lescoulouarn','29720','Plonéour-Lanvern','France','47.8792812','-4.260140099999944'),(27,'590 Le Moulin Neuf','28330','La Bazoche-Gouet','France','48.1295358','0.9234744000000319'),(28,'28 Voie Communale Boissin','45340','Boiscommun','France','48.0373238','2.377287199999955'),(29,'28 Rue de l\'Avenir','91180','Saint-Germain-lès-Arpajon','France','',''),(30,'28 Rue de l\'Avenir','91180','Saint-Germain-lès-Arpajon','France','',''),(31,'7 Saint-Doué','56230','Questembert','France','',''),(32,'AAA','56230','Questembert','France','',''),(33,'28 Rue de l\'Avenir','92170','Vanves','France','',''),(34,'28 Rue de l\'Avenir','92170','Vanves','France','',''),(35,'28 Rue de l\'Avenir','92170','Vanves','France','',''),(36,'28 Rue de l\'Avenir','92170','Vanves','France','',''),(38,'28 Rue de l\'Avenir','91180','Saint-Germain-lès-Arpajon','France','','');
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
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card`
--

LOCK TABLES `pxl_card` WRITE;
/*!40000 ALTER TABLE `pxl_card` DISABLE KEYS */;
INSERT INTO `pxl_card` VALUES (1,1,1,6,'validated','Première card','premiere-card','','',3,'<p>Hello World!</p>',1,0,0,'2018-04-05 18:18:04','2018-05-10 16:50:54',1,8),(3,3,22,8,'validated','Deuxième card - edited','deuxieme-card','','',4,'<p>Plop</p>',1,0,0,'2018-04-07 14:02:09','2018-05-09 20:59:22',1,9),(4,3,22,8,'validated','Test Bretagne','test-bretagne','','',NULL,'<p>TEST</p>',1,0,0,'2018-05-09 17:02:19','2018-05-10 16:50:42',1,11),(5,1,1,9,'validated','eros nec tellus. Nunc lectus','card-5','pulvinar arcu et pede. Nunc sed orci lobortis augue scelerisque','tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec',NULL,'accumsan laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus ante dictum mi, ac mattis velit justo nec ante. Maecenas mi felis, adipiscing fringilla, porttitor vulputate, posuere vulputate, lacus. Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse non leo. Vivamus nibh dolor, nonummy ac, feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam nunc, ullamcorper eu, euismod ac, fermentum vel, mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque tellus, imperdiet non, vestibulum nec, euismod in, dolor. Fusce feugiat. Lorem ipsum dolor',1,2393,1955,'2018-01-07 23:56:41','2018-02-17 08:09:24',1,NULL),(6,3,22,5,'validated','orci. Ut sagittis lobortis mauris.','card-6','ornare, elit elit fermentum risus, at fringilla purus mauris a','lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras',NULL,'ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim',1,2944,2197,'2018-10-13 16:54:47','2018-09-24 16:05:52',1,NULL),(7,1,1,5,'validated','ultrices. Duis volutpat nunc sit','card-7','vel, vulputate eu, odio. Phasellus at augue id ante dictum','ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla',NULL,'mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia',1,1537,375,'2018-12-25 22:53:03','2018-06-14 00:40:22',1,NULL),(8,3,22,9,'validated','adipiscing lacus. Ut nec urna','card-8','nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus','tristique neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed',NULL,'Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In',0,2705,1597,'2018-05-02 19:24:00','2017-08-29 15:39:09',1,NULL),(9,3,1,7,'validated','diam. Pellentesque habitant morbi tristique','card-9','luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed','Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue,',NULL,'consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod enim. Etiam gravida molestie arcu. Sed eu nibh vulputate mauris sagittis placerat. Cras dictum ultricies ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo. Praesent luctus. Curabitur egestas nunc',1,953,388,'2017-11-16 09:35:35','2018-04-23 00:54:22',1,NULL),(10,3,22,6,'validated','Morbi quis urna. Nunc quis','card-10','bibendum fermentum metus. Aenean sed pede nec ante blandit viverra.','dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit',NULL,'ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique neque',0,2244,2745,'2018-05-02 17:07:42','2019-01-07 05:11:08',1,NULL),(11,1,22,8,'validated','felis orci, adipiscing non, luctus','card-11','montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc','mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy',NULL,'elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque non quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam fringilla cursus',0,2526,697,'2017-06-23 05:11:46','2017-10-12 12:28:07',1,NULL),(12,1,22,6,'validated','nunc. In at pede. Cras','card-12','interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt','a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque',NULL,'netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu,',0,549,1463,'2018-03-24 22:32:30','2019-02-07 12:36:52',1,NULL),(13,1,22,6,'validated','urna, nec luctus felis purus','card-13','luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc','mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam',NULL,'leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus',1,1191,2402,'2018-06-07 19:16:24','2019-03-30 09:23:48',1,NULL),(14,1,1,10,'validated','molestie orci tincidunt adipiscing. Mauris','card-14','porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor','nibh. Phasellus nulla. Integer vulputate, risus a ultricies adipiscing, enim',NULL,'ut eros non enim commodo hendrerit. Donec porttitor tellus non magna. Nam ligula elit, pretium et, rutrum non, hendrerit id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu',0,268,1069,'2017-08-06 06:37:59','2017-10-14 19:23:50',1,NULL),(15,3,22,8,'validated','urna suscipit nonummy. Fusce fermentum','card-15','Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum.','diam. Pellentesque habitant morbi tristique senectus et netus et malesuada',NULL,'sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec',1,489,2791,'2018-03-01 08:21:06','2017-12-27 03:31:23',1,NULL),(16,3,22,4,'validated','a nunc. In at pede.','card-16','tempor, est ac mattis semper, dui lectus rutrum urna, nec','ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget',7,'<p>sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod enim. Etiam gravida molestie arcu. Sed eu nibh vulputate mauris sagittis placerat. Cras dictum ultricies ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo. Praesent luctus. Curabitur egestas nunc sed libero. Proin sed turpis nec mauris blandit mattis. Cras eget nisi dictum augue malesuada malesuada. Integer id magna et ipsum cursus vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros non enim commodo hendrerit. Donec porttitor tellus non magna. Nam ligula elit, pretium</p>',0,943,1556,'2019-05-12 03:19:09','2018-05-17 19:32:01',1,12),(17,1,22,10,'validated','ridiculus mus. Proin vel arcu','card-17','pede. Praesent eu dui. Cum sociis natoque penatibus et magnis','libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus',NULL,'ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis',0,1875,2608,'2017-11-28 21:33:18','2019-02-25 16:21:02',1,NULL),(18,1,22,4,'validated','convallis dolor. Quisque tincidunt pede','card-18','Morbi neque tellus, imperdiet non, vestibulum nec, euismod in, dolor.','Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh',NULL,'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis',1,2037,58,'2018-04-08 16:28:50','2018-12-16 05:25:42',1,NULL),(19,1,1,9,'validated','quis diam. Pellentesque habitant morbi','card-19','risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi','montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique',NULL,'dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam',1,2137,2712,'2018-01-27 06:25:25','2019-05-05 18:06:44',1,NULL),(20,3,22,7,'validated','suscipit, est ac facilisis facilisis,','card-20','vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros','ridiculus mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus.',NULL,'Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus',0,87,2562,'2017-09-12 14:43:47','2018-03-08 21:31:12',1,NULL),(21,1,1,4,'validated','risus. Morbi metus. Vivamus euismod','card-21','non enim commodo hendrerit. Donec porttitor tellus non magna. Nam','neque non quam. Pellentesque habitant morbi tristique senectus et netus',NULL,'magna. Duis dignissim tempor arcu. Vestibulum ut eros non enim commodo hendrerit. Donec porttitor tellus non magna. Nam ligula elit, pretium et, rutrum non, hendrerit id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit,',1,2438,801,'2019-03-07 13:28:00','2017-06-25 10:21:24',1,NULL),(22,3,1,6,'validated','ornare egestas ligula. Nullam feugiat','card-22','tempus eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi.','Cum sociis natoque penatibus et magnis dis parturient montes, nascetur',NULL,'tincidunt vehicula risus. Nulla eget metus eu erat semper rutrum. Fusce dolor quam, elementum at, egestas a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc sed orci lobortis augue scelerisque mollis. Phasellus libero mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget',1,347,2801,'2018-11-03 09:08:49','2017-11-14 15:01:47',1,NULL),(23,1,22,5,'validated','at, iaculis quis, pede. Praesent','card-23','aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque','Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra.',NULL,'sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat',0,2908,1243,'2018-10-18 04:43:51','2017-08-29 17:32:42',1,NULL),(24,3,22,5,'validated','turpis nec mauris blandit mattis.','card-24','pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit','Phasellus nulla. Integer vulputate, risus a ultricies adipiscing, enim mi',NULL,'Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit amet ultricies sem',0,1605,2587,'2018-02-01 08:20:05','2018-03-20 01:00:03',1,NULL),(25,1,1,8,'validated','massa. Vestibulum accumsan neque et','card-25','Sed congue, elit sed consequat auctor, nunc nulla vulputate dui,','diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec,',NULL,'vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue,',1,1202,597,'2017-11-21 07:03:22','2017-06-14 22:33:11',1,NULL),(26,3,22,10,'validated','rutrum non, hendrerit id, ante.','card-26','eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla','congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit',12,'<p>Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit</p>',1,2113,944,'2019-03-13 02:54:04','2018-05-17 19:34:56',1,17),(27,3,22,10,'validated','at, egestas a, scelerisque sed,','card-27','lacus. Ut nec urna et arcu imperdiet ullamcorper. Duis at','facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus',11,'<p>volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed</p>',1,137,769,'2019-04-09 00:17:00','2018-05-17 19:33:36',1,16),(28,1,1,6,'validated','pede ac urna. Ut tincidunt','card-28','felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit','magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla',NULL,'ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate',1,1858,633,'2018-01-23 16:57:23','2018-01-14 03:56:43',1,NULL),(29,3,22,8,'validated','felis, adipiscing fringilla, porttitor vulputate,','card-29','fames ac turpis egestas. Fusce aliquet magna a neque. Nullam','convallis in, cursus et, eros. Proin ultrices. Duis volutpat nunc',9,'<p>aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut,</p>',0,1771,1570,'2019-05-01 02:20:38','2018-05-28 17:24:41',1,14),(30,3,22,4,'validated','Nam tempor diam dictum sapien.','card-30','non, luctus sit amet, faucibus ut, nulla. Cras eu tellus','sit amet diam eu dolor egestas rhoncus. Proin nisl sem,',27,'<p>adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis</p>',1,2240,589,'2017-06-20 03:13:29','2018-05-25 23:29:05',1,32),(31,1,22,10,'validated','magna. Ut tincidunt orci quis','card-31','turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut,','purus. Nullam scelerisque neque sed sem egestas blandit. Nam nulla',NULL,'aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat',1,2910,190,'2018-01-03 13:37:56','2017-11-30 17:27:27',1,NULL),(32,1,1,10,'validated','montes, nascetur ridiculus mus. Proin','card-32','ultricies adipiscing, enim mi tempor lorem, eget mollis lectus pede','nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet',NULL,'elementum at, egestas a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc sed orci lobortis augue scelerisque mollis. Phasellus libero mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper',0,2037,1857,'2019-02-11 08:45:02','2018-08-28 07:40:38',1,NULL),(33,1,1,8,'validated','Nunc mauris. Morbi non sapien','card-33','at lacus. Quisque purus sapien, gravida non, sollicitudin a, malesuada','adipiscing lobortis risus. In mi pede, nonummy ut, molestie in,',NULL,'mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod enim. Etiam gravida molestie arcu. Sed eu nibh vulputate mauris sagittis placerat. Cras dictum ultricies ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo. Praesent luctus. Curabitur egestas nunc sed libero. Proin sed turpis nec mauris blandit mattis. Cras eget nisi dictum augue malesuada malesuada. Integer id magna et ipsum cursus vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros non enim commodo hendrerit. Donec porttitor tellus non magna. Nam ligula elit, pretium et, rutrum non, hendrerit id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada',1,2661,777,'2019-02-20 16:05:36','2019-03-31 03:12:06',1,NULL),(34,3,1,8,'validated','aliquet. Phasellus fermentum convallis ligula.','card-34','odio. Nam interdum enim non nisi. Aenean eget metus. In','erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla',NULL,'nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede,',1,765,973,'2018-01-11 12:41:21','2017-10-04 09:53:52',1,NULL),(35,1,1,9,'validated','nonummy. Fusce fermentum fermentum arcu.','card-35','viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum','Nam interdum enim non nisi. Aenean eget metus. In nec',NULL,'arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer',0,2767,136,'2018-10-15 07:40:10','2017-11-24 08:49:35',1,NULL),(36,3,22,4,'validated','Lorem ipsum dolor sit amet,','card-36','consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus','sit amet nulla. Donec non justo. Proin non massa non',19,'<p>lorem, sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio.</p>',1,2457,1682,'2018-11-28 17:04:50','2018-05-18 18:28:39',1,24),(37,3,22,4,'validated','Nunc mauris elit, dictum eu,','card-37','quis arcu vel quam dignissim pharetra. Nam ac nulla. In','posuere vulputate, lacus. Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse',NULL,'risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue',0,1099,1906,'2019-04-07 03:52:35','2017-07-30 21:26:34',1,NULL),(38,1,22,4,'validated','risus. Nulla eget metus eu','card-38','molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris.','suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis',NULL,'eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque non quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque neque sed sem egestas blandit. Nam nulla magna, malesuada vel, convallis in, cursus et, eros. Proin ultrices. Duis volutpat nunc sit amet metus. Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh. Phasellus nulla. Integer vulputate,',1,368,1369,'2017-05-22 11:18:43','2018-12-03 18:51:06',1,NULL),(39,1,1,8,'validated','eget nisi dictum augue malesuada','card-39','quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam','posuere, enim nisl elementum purus, accumsan interdum libero dui nec',NULL,'lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque non quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque neque sed sem egestas blandit. Nam nulla magna, malesuada vel, convallis in, cursus et, eros. Proin ultrices. Duis volutpat nunc sit amet metus. Aliquam erat volutpat. Nulla facilisis.',0,1429,237,'2019-02-17 15:24:52','2019-01-23 14:02:34',1,NULL),(40,3,23,4,'validated','neque pellentesque massa lobortis ultrices.','card-40','aliquet, metus urna convallis erat, eget tincidunt dui augue eu','ac mattis semper, dui lectus rutrum urna, nec luctus felis',25,'<p>augue scelerisque mollis. Phasellus libero mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin</p>',0,206,2696,'2018-08-12 11:38:43','2018-05-18 21:54:56',1,30),(41,1,1,8,'validated','lacus. Nulla tincidunt, neque vitae','card-41','Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo,','Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh. Phasellus',NULL,'egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque non quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque',0,1441,2622,'2017-08-22 09:24:08','2017-08-27 23:05:34',1,NULL),(42,3,23,4,'validated','non sapien molestie orci tincidunt','card-42','ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et','ante lectus convallis est, vitae sodales nisi magna sed dui.',21,'<p>orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis</p>',1,119,125,'2018-09-20 07:53:17','2018-05-18 21:53:52',1,26),(43,1,22,4,'validated','sem. Nulla interdum. Curabitur dictum.','card-43','rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at,','elementum sem, vitae aliquam eros turpis non enim. Mauris quis',NULL,'Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut,',0,6,533,'2017-06-28 22:53:41','2018-06-24 00:33:03',1,NULL),(44,3,22,4,'validated','tellus. Nunc lectus pede, ultrices','card-44','Donec felis orci, adipiscing non, luctus sit amet, faucibus ut,','facilisis, magna tellus faucibus leo, in lobortis tellus justo sit',23,'<p>lacus, varius et, euismod et, commodo at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus ante dictum mi, ac mattis velit justo nec ante. Maecenas mi felis, adipiscing fringilla, porttitor vulputate, posuere vulputate, lacus. Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse non leo. Vivamus nibh dolor, nonummy ac, feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam nunc, ullamcorper eu, euismod ac, fermentum vel, mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque tellus, imperdiet non, vestibulum nec, euismod in, dolor.</p>',1,1144,2301,'2018-09-16 16:25:43','2018-05-18 21:54:24',1,28),(45,1,1,6,'validated','tortor, dictum eu, placerat eget,','card-45','Duis elementum, dui quis accumsan convallis, ante lectus convallis est,','urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum',NULL,'nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora',0,2284,1887,'2017-11-09 17:01:50','2017-07-01 09:37:49',1,NULL),(46,3,22,10,'validated','Vestibulum ante ipsum primis in','card-46','lectus. Cum sociis natoque penatibus et magnis dis parturient montes,','eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer',NULL,'risus. Quisque libero lacus, varius et, euismod et, commodo at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus ante dictum mi, ac mattis velit justo nec ante. Maecenas',1,2340,2373,'2018-04-23 05:59:49','2017-11-19 19:53:31',1,NULL),(47,3,22,8,'validated','ipsum leo elementum sem, vitae','card-47','ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel','metus eu erat semper rutrum. Fusce dolor quam, elementum at,',17,'<p>neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec</p>',0,120,1404,'2018-12-10 07:31:38','2018-05-18 18:27:02',1,22),(48,1,22,7,'validated','dui nec urna suscipit nonummy.','card-48','lorem vitae odio sagittis semper. Nam tempor diam dictum sapien.','nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque',NULL,'cursus purus. Nullam scelerisque neque sed sem egestas blandit. Nam nulla magna, malesuada vel, convallis in, cursus et, eros. Proin ultrices. Duis volutpat nunc sit amet metus. Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh. Phasellus nulla. Integer vulputate, risus a ultricies adipiscing, enim mi tempor lorem, eget mollis lectus pede et risus. Quisque libero lacus, varius et, euismod et, commodo at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus ante dictum mi, ac mattis',1,976,1963,'2017-08-05 03:32:57','2018-06-21 13:13:10',1,NULL),(49,1,1,8,'validated','Proin vel arcu eu odio','card-49','mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie','eget metus eu erat semper rutrum. Fusce dolor quam, elementum',NULL,'Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse',0,665,1855,'2018-07-11 09:23:31','2018-09-03 11:08:41',1,NULL),(50,3,22,6,'validated','lacus. Aliquam rutrum lorem ac','card-50','parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique','sem mollis dui, in sodales elit erat vitae risus. Duis',NULL,'pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy',1,1072,464,'2017-10-10 06:43:48','2018-08-27 20:23:51',1,NULL),(51,1,1,7,'validated','Sed neque. Sed eget lacus.','card-51','Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum','eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in',NULL,'natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec',0,1741,2881,'2017-11-05 21:36:29','2019-03-21 04:56:27',1,NULL),(52,1,22,6,'validated','Nullam enim. Sed nulla ante,','card-52','urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus','ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus.',NULL,'metus eu erat semper rutrum. Fusce dolor quam, elementum at, egestas a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc sed orci lobortis augue scelerisque mollis. Phasellus libero mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio.',1,1041,349,'2017-09-07 02:00:47','2017-11-25 04:19:09',1,NULL),(53,3,22,4,'validated','Donec tincidunt. Donec vitae erat','card-53','posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet','a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc',20,'<p>amet metus. Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh. Phasellus nulla. Integer vulputate, risus a ultricies adipiscing, enim mi tempor lorem, eget mollis lectus pede et risus. Quisque libero lacus, varius et, euismod et, commodo at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus ante dictum mi, ac mattis velit justo nec ante. Maecenas mi felis, adipiscing</p>',0,1742,1580,'2018-11-08 22:55:45','2018-05-18 18:29:03',1,25),(54,3,22,4,'validated','lobortis ultrices. Vivamus rhoncus. Donec','card-54','tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id,','dolor. Quisque tincidunt pede ac urna. Ut tincidunt vehicula risus.',24,'<p>laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id enim. Curabitur massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac sem ut dolor dapibus gravida. Aliquam tincidunt, nunc ac mattis ornare, lectus ante dictum mi, ac mattis velit justo nec</p>',1,739,187,'2018-08-19 09:07:43','2018-05-18 21:54:40',1,29),(55,3,1,10,'validated','Fusce aliquet magna a neque.','card-55','sem mollis dui, in sodales elit erat vitae risus. Duis','non, luctus sit amet, faucibus ut, nulla. Cras eu tellus',NULL,'Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse non leo. Vivamus nibh dolor, nonummy ac, feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam nunc, ullamcorper eu, euismod ac, fermentum vel, mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque tellus, imperdiet non, vestibulum nec, euismod in, dolor. Fusce feugiat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor, velit',0,1065,866,'2017-08-20 02:19:47','2017-12-27 12:10:55',1,NULL),(56,3,22,8,'validated','sodales nisi magna sed dui.','card-56','Quisque ornare tortor at risus. Nunc ac sem ut dolor','non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris',14,'<p>a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus.</p>',0,612,2879,'2019-02-28 00:07:41','2018-05-18 18:26:07',1,19),(57,3,1,8,'validated','mauris elit, dictum eu, eleifend','card-57','nunc ac mattis ornare, lectus ante dictum mi, ac mattis','pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac',NULL,'malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec',1,2072,2221,'2018-10-11 17:04:16','2018-05-04 04:43:14',1,NULL),(58,3,22,6,'validated','consectetuer euismod est arcu ac','card-58','vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros','lacus. Quisque purus sapien, gravida non, sollicitudin a, malesuada id,',NULL,'Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy',1,1532,1875,'2017-09-03 22:26:38','2019-02-03 19:22:57',1,NULL),(59,1,1,6,'validated','aliquet vel, vulputate eu, odio.','card-59','Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin','mattis. Cras eget nisi dictum augue malesuada malesuada. Integer id',NULL,'ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod enim. Etiam gravida',0,2263,1285,'2019-01-11 02:43:55','2019-03-13 04:17:51',1,NULL),(60,1,22,10,'validated','lobortis quis, pede. Suspendisse dui.','card-60','turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut,','pede sagittis augue, eu tempor erat neque non quam. Pellentesque',NULL,'ornare placerat, orci lacus vestibulum lorem, sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat.',0,1146,801,'2018-01-13 18:06:05','2018-12-24 01:26:30',1,NULL),(61,1,22,9,'validated','cursus et, eros. Proin ultrices.','card-61','arcu iaculis enim, sit amet ornare lectus justo eu arcu.','ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec',NULL,'nisi dictum augue malesuada malesuada. Integer id magna et ipsum cursus vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros non enim commodo hendrerit. Donec porttitor tellus non magna. Nam ligula elit, pretium et, rutrum non, hendrerit id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes,',1,599,2720,'2019-03-29 16:20:19','2019-02-17 13:14:32',1,NULL),(62,1,1,6,'validated','eget mollis lectus pede et','card-62','tellus sem mollis dui, in sodales elit erat vitae risus.','eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut',8,'<p>sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi</p>',1,1885,961,'2019-05-05 00:49:55','2018-05-17 19:34:19',1,13),(63,3,22,6,'validated','tristique aliquet. Phasellus fermentum convallis','card-63','varius orci, in consequat enim diam vel arcu. Curabitur ut','elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu',NULL,'et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus',0,2257,1756,'2018-02-19 12:25:41','2018-08-05 11:30:50',1,NULL),(64,3,22,9,'validated','in magna. Phasellus dolor elit,','card-64','dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu','fringilla purus mauris a nunc. In at pede. Cras vulputate',NULL,'Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae',0,2097,815,'2018-09-29 00:20:50','2018-11-30 17:35:46',1,NULL),(65,3,1,4,'validated','blandit congue. In scelerisque scelerisque','card-65','eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer','rutrum magna. Cras convallis convallis dolor. Quisque tincidunt pede ac',NULL,'facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor',0,2201,2110,'2018-01-22 13:18:16','2018-06-05 00:59:41',1,NULL),(66,1,1,10,'validated','nisi magna sed dui. Fusce','card-66','semper rutrum. Fusce dolor quam, elementum at, egestas a, scelerisque','faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare.',NULL,'nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra.',1,2909,1585,'2017-09-24 07:39:42','2018-02-24 04:09:53',1,NULL),(67,1,22,8,'validated','purus, in molestie tortor nibh','card-67','vestibulum lorem, sit amet ultricies sem magna nec quam. Curabitur','magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum metus.',NULL,'odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum',1,1260,1655,'2018-05-11 15:57:36','2019-01-27 02:33:48',1,NULL),(68,1,1,9,'validated','Curabitur egestas nunc sed libero.','card-68','ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor','est, vitae sodales nisi magna sed dui. Fusce aliquam, enim',NULL,'rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus',1,2838,2443,'2017-09-04 04:17:50','2018-04-09 11:11:40',1,NULL),(69,1,1,7,'validated','molestie dapibus ligula. Aliquam erat','card-69','ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu','tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio.',NULL,'eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque non quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque neque sed sem egestas blandit. Nam nulla magna, malesuada vel, convallis in, cursus et, eros. Proin ultrices. Duis volutpat nunc sit amet metus. Aliquam erat volutpat. Nulla facilisis. Suspendisse commodo tincidunt nibh. Phasellus nulla. Integer vulputate, risus a ultricies adipiscing, enim mi tempor lorem, eget mollis lectus pede et risus. Quisque libero lacus, varius et, euismod et, commodo at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi sem semper erat, in consectetuer ipsum nunc id',1,1172,483,'2018-04-11 21:44:52','2017-06-09 18:29:24',1,NULL),(70,3,22,8,'validated','Nullam suscipit, est ac facilisis','card-70','Proin vel arcu eu odio tristique pharetra. Quisque ac libero','cursus purus. Nullam scelerisque neque sed sem egestas blandit. Nam',16,'<p>felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque non quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam fringilla cursus purus. Nullam scelerisque</p>',1,1081,648,'2019-02-05 23:38:45','2018-05-18 18:26:45',1,21),(71,3,22,9,'validated','mi eleifend egestas. Sed pharetra,','card-71','Duis risus odio, auctor vitae, aliquet nec, imperdiet nec, leo.','vitae erat vel pede blandit congue. In scelerisque scelerisque dui.',NULL,'ipsum. Phasellus vitae mauris sit amet lorem semper auctor. Mauris vel turpis. Aliquam adipiscing lobortis risus. In mi pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu',0,1007,2437,'2018-01-12 15:32:51','2017-09-03 02:10:13',1,NULL),(72,1,22,8,'validated','nascetur ridiculus mus. Aenean eget','card-72','congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit','mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin',NULL,'sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac',0,1292,199,'2018-04-07 23:06:12','2018-11-25 15:34:17',1,NULL),(73,1,1,4,'validated','a mi fringilla mi lacinia','card-73','nec, euismod in, dolor. Fusce feugiat. Lorem ipsum dolor sit','Proin ultrices. Duis volutpat nunc sit amet metus. Aliquam erat',NULL,'volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus',1,2994,2368,'2017-08-15 09:20:15','2018-11-07 21:17:12',1,NULL),(74,1,22,6,'validated','ullamcorper, velit in aliquet lobortis,','card-74','lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi.','Cum sociis natoque penatibus et magnis dis parturient montes, nascetur',NULL,'rutrum magna. Cras convallis convallis dolor. Quisque tincidunt pede ac urna. Ut tincidunt vehicula risus. Nulla eget metus eu erat semper rutrum. Fusce dolor quam, elementum at, egestas a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc sed orci lobortis augue scelerisque mollis. Phasellus libero mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam',1,1422,2645,'2018-05-21 12:48:40','2018-02-07 07:53:36',1,NULL),(75,1,1,6,'validated','Aliquam adipiscing lobortis risus. In','card-75','eros turpis non enim. Mauris quis turpis vitae purus gravida','Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse non leo. Vivamus',10,'<p>accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec est mauris, rhoncus id, mollis nec, cursus a, enim. Suspendisse aliquet, sem ut cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis non</p>',0,2947,2112,'2019-04-09 10:09:03','2018-05-17 19:33:14',1,15),(76,3,22,4,'validated','tempor diam dictum sapien. Aenean','card-76','placerat. Cras dictum ultricies ligula. Nullam enim. Sed nulla ante,','tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim',22,'<p>ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu</p>',1,1808,369,'2018-09-18 04:06:34','2018-05-18 21:54:09',1,27),(77,3,1,4,'validated','sem ut dolor dapibus gravida.','card-77','Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci,','enim consequat purus. Maecenas libero est, congue a, aliquet vel,',NULL,'nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies ornare, elit elit fermentum risus, at fringilla purus mauris a nunc. In at pede. Cras vulputate velit eu sem. Pellentesque ut ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius ultrices, mauris ipsum porta elit, a feugiat tellus lorem eu metus. In lorem. Donec elementum, lorem ut aliquam iaculis, lacus pede sagittis augue, eu tempor erat neque non',1,2678,2755,'2018-05-22 12:27:19','2017-07-06 01:02:54',1,NULL),(78,1,22,6,'validated','lorem ac risus. Morbi metus.','card-78','mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus','arcu. Vestibulum ante ipsum primis in faucibus orci luctus et',NULL,'Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit.',0,1163,2853,'2019-03-19 05:39:49','2018-03-31 05:41:37',1,NULL),(79,1,1,6,'validated','ultrices, mauris ipsum porta elit,','card-79','velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod','id magna et ipsum cursus vestibulum. Mauris magna. Duis dignissim',NULL,'mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor',0,1777,2378,'2019-01-09 12:09:27','2019-01-28 08:44:14',1,NULL),(80,1,1,7,'validated','eu lacus. Quisque imperdiet, erat','card-80','sed, sapien. Nunc pulvinar arcu et pede. Nunc sed orci','pede, nonummy ut, molestie in, tempus eu, ligula. Aenean euismod',NULL,'malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus',0,589,2429,'2018-03-25 11:58:10','2018-05-04 01:53:01',1,NULL),(81,1,22,8,'validated','et tristique pellentesque, tellus sem','card-81','convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum','Ut tincidunt vehicula risus. Nulla eget metus eu erat semper',NULL,'ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue,',1,1652,2579,'2017-08-17 16:28:50','2017-07-22 00:21:26',1,NULL),(82,3,1,5,'validated','ac risus. Morbi metus. Vivamus','card-82','dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl.','eu tellus eu augue porttitor interdum. Sed auctor odio a',NULL,'suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis.',0,81,2256,'2017-09-05 10:34:22','2018-07-30 17:31:07',1,NULL),(83,3,22,4,'validated','enim nisl elementum purus, accumsan','card-83','pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi.','risus. Duis a mi fringilla mi lacinia mattis. Integer eu',NULL,'eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a,',1,2976,1184,'2018-04-19 20:03:07','2018-01-09 03:06:35',1,NULL),(84,1,22,6,'validated','pretium et, rutrum non, hendrerit','card-84','et pede. Nunc sed orci lobortis augue scelerisque mollis. Phasellus','Sed nunc est, mollis non, cursus non, egestas a, dui.',NULL,'ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed neque. Sed eget lacus. Mauris non dui nec urna suscipit nonummy. Fusce fermentum fermentum arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue.',0,129,1900,'2018-12-03 17:44:49','2019-03-14 18:17:54',1,NULL),(85,3,22,4,'validated','vehicula et, rutrum eu, ultrices','card-85','et risus. Quisque libero lacus, varius et, euismod et, commodo','id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor,',NULL,'lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed leo. Cras vehicula aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce',1,1271,598,'2018-01-30 07:10:35','2018-07-22 03:21:26',1,NULL),(86,3,1,5,'validated','ac orci. Ut semper pretium','card-86','massa. Vestibulum accumsan neque et nunc. Quisque ornare tortor at','odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque',NULL,'libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque',1,2384,1965,'2018-04-03 07:37:09','2017-12-26 18:52:26',1,NULL),(87,1,1,5,'validated','rhoncus id, mollis nec, cursus','card-87','a ultricies adipiscing, enim mi tempor lorem, eget mollis lectus','at, libero. Morbi accumsan laoreet ipsum. Curabitur consequat, lectus sit',NULL,'senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi a odio semper cursus. Integer mollis. Integer tincidunt aliquam arcu. Aliquam ultrices iaculis odio. Nam interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat',1,1013,1583,'2018-11-08 06:03:43','2018-09-03 22:41:26',1,NULL),(88,3,1,7,'validated','lectus quis massa. Mauris vestibulum,','card-88','in, dolor. Fusce feugiat. Lorem ipsum dolor sit amet, consectetuer','nisl arcu iaculis enim, sit amet ornare lectus justo eu',NULL,'Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat',0,1615,316,'2018-06-25 13:52:11','2018-05-04 18:25:17',1,NULL),(89,3,22,4,'validated','lorem. Donec elementum, lorem ut','card-89','a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque','sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed',28,'<p>vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros non enim commodo hendrerit. Donec porttitor tellus non magna. Nam ligula elit, pretium et, rutrum non, hendrerit id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus id, libero. Donec consectetuer mauris id sapien. Cras dolor dolor, tempus non, lacinia at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique</p>',0,352,846,'2017-06-14 16:18:01','2018-05-25 23:33:45',1,33),(90,3,22,4,'validated','nunc sed pede. Cum sociis','card-90','Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut','eu, ligula. Aenean euismod mauris eu elit. Nulla facilisi. Sed',13,'<p>ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis mauris. Suspendisse aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod enim. Etiam gravida molestie arcu. Sed eu nibh vulputate mauris sagittis placerat. Cras dictum ultricies ligula. Nullam enim. Sed nulla ante,</p>',1,2178,2483,'2019-04-03 18:30:46','2018-05-18 18:25:44',1,18),(91,3,22,5,'validated','consectetuer rhoncus. Nullam velit dui,','card-91','cursus luctus, ipsum leo elementum sem, vitae aliquam eros turpis','auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis',NULL,'aliquet libero. Integer in magna. Phasellus dolor elit, pellentesque a, facilisis non, bibendum sed, est. Nunc laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci, in consequat enim diam vel arcu. Curabitur ut odio vel est tempor bibendum. Donec felis orci, adipiscing non, luctus sit amet, faucibus ut, nulla. Cras eu tellus eu augue porttitor interdum. Sed auctor odio a purus. Duis elementum, dui quis accumsan convallis, ante lectus convallis est, vitae sodales nisi magna sed dui. Fusce aliquam, enim nec tempus scelerisque, lorem ipsum sodales purus, in molestie tortor nibh sit amet orci. Ut sagittis lobortis',0,2407,175,'2018-11-06 00:06:39','2017-06-14 15:15:34',1,NULL),(92,3,22,8,'validated','eros non enim commodo hendrerit.','card-92','Mauris vestibulum, neque sed dictum eleifend, nunc risus varius orci,','Quisque fringilla euismod enim. Etiam gravida molestie arcu. Sed eu',15,'<p>nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque</p>',0,2365,1730,'2019-02-18 22:27:03','2018-05-18 18:26:26',1,20),(93,1,22,10,'validated','arcu ac orci. Ut semper','card-93','ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede.','eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut',NULL,'aliquet molestie tellus. Aenean egestas hendrerit neque. In ornare sagittis felis. Donec tempor, est ac mattis semper, dui lectus rutrum urna, nec luctus felis purus ac tellus. Suspendisse sed dolor. Fusce mi lorem, vehicula et, rutrum eu, ultrices sit amet, risus. Donec nibh enim, gravida sit amet, dapibus id, blandit at, nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod enim. Etiam gravida molestie arcu. Sed eu nibh vulputate mauris sagittis placerat. Cras dictum ultricies ligula. Nullam enim. Sed nulla ante, iaculis nec, eleifend non, dapibus rutrum, justo. Praesent luctus. Curabitur egestas nunc sed libero. Proin sed turpis nec mauris blandit mattis. Cras eget nisi dictum augue malesuada malesuada. Integer id magna et ipsum cursus vestibulum. Mauris magna. Duis dignissim tempor arcu. Vestibulum ut eros non enim commodo hendrerit. Donec porttitor tellus non magna. Nam ligula elit, pretium et, rutrum non, hendrerit id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo.',0,1051,641,'2018-03-26 23:13:39','2017-11-28 08:36:00',1,NULL),(94,3,1,4,'validated','Sed eu nibh vulputate mauris','card-94','non enim. Mauris quis turpis vitae purus gravida sagittis. Duis','mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin',NULL,'interdum enim non nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque nonummy ipsum non arcu. Vivamus sit amet risus. Donec egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est, mollis non, cursus non, egestas a, dui. Cras pellentesque. Sed dictum. Proin eget odio. Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor vitae dolor. Donec fringilla. Donec feugiat metus sit amet ante. Vivamus non lorem vitae odio sagittis semper. Nam tempor diam dictum sapien. Aenean massa. Integer vitae nibh. Donec',1,83,212,'2018-03-22 16:35:40','2018-12-02 07:36:21',1,NULL),(95,1,22,4,'validated','amet ultricies sem magna nec','card-95','enim. Etiam gravida molestie arcu. Sed eu nibh vulputate mauris','tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec,',NULL,'rutrum non, hendrerit id, ante. Nunc mauris sapien, cursus in, hendrerit consectetuer, cursus et, magna. Praesent interdum ligula eu enim. Etiam imperdiet dictum magna. Ut tincidunt orci quis lectus. Nullam suscipit, est ac facilisis facilisis, magna tellus faucibus leo, in lobortis tellus justo sit amet nulla. Donec non justo. Proin non massa non ante bibendum ullamcorper. Duis cursus, diam at pretium aliquet, metus urna convallis erat, eget tincidunt dui augue eu tellus. Phasellus elit pede, malesuada vel, venenatis vel, faucibus',1,2023,2470,'2017-12-07 09:00:20','2018-09-07 12:56:20',1,NULL),(96,1,22,5,'validated','convallis est, vitae sodales nisi','card-96','montes, nascetur ridiculus mus. Proin vel nisl. Quisque fringilla euismod','lorem tristique aliquet. Phasellus fermentum convallis ligula. Donec luctus aliquet',NULL,'libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu turpis. Nulla aliquet. Proin velit. Sed malesuada augue ut lacus. Nulla tincidunt, neque vitae semper egestas, urna justo faucibus lectus, a sollicitudin orci sem eget massa. Suspendisse eleifend. Cras sed',0,1206,2053,'2018-12-28 04:19:24','2018-04-05 20:55:26',1,NULL),(97,3,22,4,'validated','eleifend vitae, erat. Vivamus nisi.','card-97','et pede. Nunc sed orci lobortis augue scelerisque mollis. Phasellus','tellus faucibus leo, in lobortis tellus justo sit amet nulla.',26,'<p>ornare lectus justo eu arcu. Morbi sit amet massa. Quisque porttitor eros nec tellus. Nunc lectus pede, ultrices a, auctor non, feugiat nec, diam. Duis mi enim, condimentum eget, volutpat ornare, facilisis eget, ipsum. Donec sollicitudin adipiscing ligula. Aenean gravida nunc sed pede. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin vel arcu eu odio tristique pharetra. Quisque ac libero nec ligula consectetuer rhoncus. Nullam velit dui, semper et, lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna. Nullam lobortis quam a felis ullamcorper viverra. Maecenas iaculis aliquet diam. Sed diam lorem, auctor quis, tristique ac, eleifend vitae, erat. Vivamus nisi. Mauris nulla. Integer urna. Vivamus molestie dapibus ligula. Aliquam erat volutpat. Nulla dignissim. Maecenas ornare egestas ligula. Nullam feugiat placerat velit. Quisque varius. Nam porttitor scelerisque neque. Nullam nisl. Maecenas malesuada fringilla est. Mauris eu</p>',0,1380,2730,'2018-08-07 06:50:28','2018-05-18 21:55:17',1,31),(98,3,22,9,'validated','tellus lorem eu metus. In','card-98','Aliquam vulputate ullamcorper magna. Sed eu eros. Nam consequat dolor','in sodales elit erat vitae risus. Duis a mi fringilla',NULL,'arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus ornare. Fusce mollis. Duis sit amet diam eu dolor egestas rhoncus. Proin nisl sem, consequat nec, mollis vitae, posuere at, velit. Cras lorem lorem, luctus ut, pellentesque eget, dictum placerat, augue. Sed molestie. Sed id risus quis diam luctus lobortis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris ut quam vel sapien imperdiet ornare. In faucibus. Morbi vehicula. Pellentesque tincidunt tempus risus. Donec egestas. Duis ac arcu. Nunc mauris. Morbi non sapien molestie orci tincidunt adipiscing. Mauris molestie pharetra nibh. Aliquam ornare, libero at auctor ullamcorper, nisl arcu iaculis enim, sit amet ornare lectus justo eu arcu. Morbi',1,2102,2437,'2017-12-30 11:35:40','2017-11-20 17:12:37',1,NULL),(99,3,22,10,'validated','et pede. Nunc sed orci','card-99','sodales purus, in molestie tortor nibh sit amet orci. Ut','nisi. Aenean eget metus. In nec orci. Donec nibh. Quisque',NULL,'lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi quis urna. Nunc quis arcu vel quam dignissim pharetra. Nam ac nulla. In tincidunt congue turpis. In condimentum. Donec at arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec tincidunt. Donec vitae erat vel pede blandit congue. In scelerisque scelerisque dui. Suspendisse ac metus vitae velit egestas lacinia. Sed congue, elit sed consequat auctor, nunc nulla vulputate dui, nec tempus mauris erat eget ipsum. Suspendisse sagittis. Nullam vitae diam. Proin dolor. Nulla semper tellus id nunc interdum feugiat. Sed nec metus facilisis lorem',0,1256,1729,'2018-06-23 11:15:22','2018-04-01 20:49:11',1,NULL),(100,1,22,6,'validated','sagittis semper. Nam tempor diam','card-100','senectus et netus et malesuada fames ac turpis egestas. Aliquam','egestas. Aliquam nec enim. Nunc ut erat. Sed nunc est,',NULL,'justo nec ante. Maecenas mi felis, adipiscing fringilla, porttitor vulputate, posuere vulputate, lacus. Cras interdum. Nunc sollicitudin commodo ipsum. Suspendisse non leo. Vivamus nibh dolor, nonummy ac, feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam nunc, ullamcorper eu, euismod ac, fermentum vel, mauris. Integer sem elit, pharetra ut, pharetra sed, hendrerit a, arcu. Sed et libero. Proin mi. Aliquam gravida mauris ut mi. Duis risus odio, auctor vitae, aliquet nec, imperdiet nec, leo. Morbi neque tellus, imperdiet non, vestibulum nec, euismod in, dolor. Fusce feugiat. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam auctor, velit eget laoreet posuere, enim nisl elementum purus, accumsan',1,1387,2395,'2017-12-15 06:34:58','2019-02-05 22:25:10',1,NULL),(101,3,22,9,'validated','aliquet vel, vulputate eu, odio.','card-101','fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat','laoreet lectus quis massa. Mauris vestibulum, neque sed dictum eleifend,',NULL,'at, iaculis quis, pede. Praesent eu dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aenean eget magna. Suspendisse tristique neque venenatis lacus. Etiam bibendum fermentum metus. Aenean sed pede nec ante blandit viverra. Donec tempus, lorem fringilla ornare placerat, orci lacus vestibulum lorem, sit amet ultricies sem magna nec quam. Curabitur vel lectus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec dignissim magna a tortor. Nunc commodo auctor velit. Aliquam nisl. Nulla eu neque pellentesque massa lobortis ultrices. Vivamus rhoncus. Donec est. Nunc ullamcorper, velit in aliquet lobortis, nisi nibh lacinia orci, consectetuer euismod est arcu ac orci. Ut semper pretium neque. Morbi',0,13,185,'2017-10-03 21:54:04','2019-03-20 22:50:25',1,NULL),(102,3,22,4,'validated','ultricies ligula. Nullam enim. Sed','card-102','Curabitur egestas nunc sed libero. Proin sed turpis nec mauris','ac, feugiat non, lobortis quis, pede. Suspendisse dui. Fusce diam',NULL,'elementum sem, vitae aliquam eros turpis non enim. Mauris quis turpis vitae purus gravida sagittis. Duis gravida. Praesent eu nulla at sem molestie sodales. Mauris blandit enim consequat purus. Maecenas libero est, congue a, aliquet vel, vulputate eu, odio. Phasellus at augue id ante dictum cursus. Nunc mauris elit, dictum eu, eleifend nec, malesuada ut, sem. Nulla interdum. Curabitur dictum. Phasellus in felis. Nulla tempor augue ac ipsum.',0,2415,2052,'2018-01-12 03:27:40','2017-06-06 01:42:36',1,NULL),(103,3,22,4,'validated','posuere cubilia Curae; Donec tincidunt.','card-103','est, congue a, aliquet vel, vulputate eu, odio. Phasellus at','ipsum ac mi eleifend egestas. Sed pharetra, felis eget varius',18,'<p>Phasellus fermentum convallis ligula. Donec luctus aliquet odio. Etiam ligula tortor, dictum eu, placerat eget, venenatis a, magna. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat vitae risus. Duis a mi fringilla mi lacinia mattis. Integer eu lacus. Quisque imperdiet, erat nonummy ultricies</p>',0,1640,194,'2018-12-07 00:20:36','2018-05-18 18:27:56',1,23),(104,3,1,4,'validated','urna, nec luctus felis purus','card-104','lacinia vitae, sodales at, velit. Pellentesque ultricies dignissim lacus. Aliquam','laoreet ipsum. Curabitur consequat, lectus sit amet luctus vulputate, nisi',NULL,'Nulla eget metus eu erat semper rutrum. Fusce dolor quam, elementum at, egestas a, scelerisque sed, sapien. Nunc pulvinar arcu et pede. Nunc sed orci lobortis augue scelerisque mollis. Phasellus libero mauris, aliquam eu, accumsan sed, facilisis vitae, orci. Phasellus dapibus quam quis diam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce aliquet magna a neque. Nullam ut nisi',1,620,2597,'2017-07-16 10:16:57','2017-08-09 03:08:00',1,NULL);
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
INSERT INTO `pxl_card_category` VALUES (7,0,'Nature','fa-tree','nature','2018-03-24 17:23:19','2018-04-09 13:26:06',0),(8,1,'Site touristique','fa-university','site-touristique','2018-03-24 17:23:37','2018-03-24 20:06:07',0),(9,2,'Manger','fa-utensils','manger','2018-03-24 17:23:57','2018-03-24 17:23:57',0),(10,3,'Mobilité','fa-wheelchair','mobilite','2018-03-24 17:24:16','2018-03-24 17:24:16',0),(11,4,'Sport','fa-futbol','sport','2018-03-24 17:24:31','2018-03-24 17:24:31',0),(12,5,'Vie nocturne','fa-glass-martini','vie-nocturne','2018-03-24 17:24:49','2018-03-24 17:24:49',0),(13,6,'Art et culture','fa-paint-brush','art-et-culture','2018-03-24 17:25:11','2018-03-24 17:25:11',0),(14,7,'Shopping','fa-shopping-bag','shopping','2018-03-24 17:25:27','2018-03-24 17:25:27',0),(15,8,'Consommer local','local','consommer-local','2018-03-24 17:26:38','2018-03-24 17:26:38',0),(16,9,'Enfants','fa-child','enfants','2018-03-24 17:27:30','2018-03-24 17:27:30',0),(17,10,'Green','fa-leaf','green','2018-03-24 17:27:49','2018-03-24 17:27:49',0),(18,11,'Dormir','fa-bed','dormir','2018-03-24 17:28:10','2018-03-24 17:28:10',0),(19,12,'Bien être et santé','sante','bien-etre-et-sante','2018-03-24 17:28:28','2018-03-24 17:28:28',0),(20,13,'Événement','fa-calendar-alt','evenement','2018-03-24 17:29:02','2018-03-24 17:29:02',0),(21,14,'Éxpériences solidaires','fa-users','experiences-solidaires','2018-03-24 17:29:30','2018-03-24 17:29:30',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_info`
--

LOCK TABLES `pxl_card_info` WRITE;
/*!40000 ALTER TABLE `pxl_card_info` DISABLE KEYS */;
INSERT INTO `pxl_card_info` VALUES (14,'blop ?','text',1,'Blop value'),(17,'a','text',3,'b'),(18,'Dates','date',56,'Du 20/11/2017 au 01/12/2017'),(19,'Horaires','time',56,'08:30 > 20:30'),(20,'Prix','price',56,'90'),(21,'Téléphone','phone',56,'+33 (4) 01 02 03 04'),(22,'Email','email',56,'johndoe@gmail.com'),(23,'Lien','link',56,'http://www.google.com');
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_card_media`
--

LOCK TABLES `pxl_card_media` WRITE;
/*!40000 ALTER TABLE `pxl_card_media` DISABLE KEYS */;
INSERT INTO `pxl_card_media` VALUES (1,1,'46-image-821f58ac7a7de920.png'),(8,NULL,'51f51d44de31679bc9ccdc0f5aa27b6d.jpeg'),(9,NULL,'a6d80f715e644da2b90e17869a3fa27e.jpeg'),(10,3,'0892a272240995ac8f9e7a56ae4e006b.jpeg'),(11,NULL,'c66bc5900b7fad196469b1e0e9dad6d5.jpg'),(12,NULL,'6700dff906d8e29ddb60e52dd4cdc260.jpg'),(13,NULL,'655a6559b5088a933c50f4e586087deb.jpg'),(14,NULL,'aef94a51e57023dcbbf2a8abc8ff8e7c.jpg'),(15,NULL,'58905340f8710c41a80c1bfcb5b311c2.jpg'),(16,NULL,'b2b9a4f2d91088767497b7557cc5c185.jpg'),(17,NULL,'5cd0689ca2d588bcbd1dc6d9abf178e2.jpg'),(18,NULL,'b9d332ed2dc316df17e78da45858c1be.jpg'),(19,NULL,'eb1eb4d9da87012f3dd3560f0949a61b.jpg'),(20,NULL,'29d82084795657e023c4e29fc5db9b7d.jpg'),(21,NULL,'639ccc76d6948b871012b36240e6c7c8.jpg'),(22,NULL,'6a25beba25bab8efb8a35520a6c29817.jpg'),(23,NULL,'b575a32acb723df40512a61aba804962.jpg'),(24,NULL,'6067a5065e955488ddf858012dbefa03.jpg'),(25,NULL,'25e8afa255f28d4fd2893e4f93d0fa8b.png'),(26,NULL,'cbe4543a6bc6e0bf774dbdcb15fdc3b2.png'),(27,NULL,'e5c2cd8186f5d46997ceff4732739567.png'),(28,NULL,'0b4b00a3362e11d2823e9658854da6bc.jpg'),(29,NULL,'bd42f4f96bf41d3e8827b6d68846c979.png'),(30,NULL,'06ad3f956e83a2324f5beac6c8fb02ec.png'),(31,NULL,'3be40eb86d0620d7ccfa08bba000f8db.jpg'),(32,NULL,'fe3b9eaccab354029ed6635181b53cee.jpg'),(33,NULL,'07878d7cccdd5d7fe85902f41e5802db.jpg'),(34,56,'d125a81009058e8f1380c2047c00e0e9.jpg'),(35,56,'e21155f152313e5779ea62e857bbb278.jpg'),(36,56,'dae9e1b462d339c4ef43a2b880af654f.jpg'),(37,56,'039b3e895896c66f6b8a022292573b0f.jpg');
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
INSERT INTO `pxl_cards_categories` VALUES (1,13),(1,16),(1,19),(3,7),(3,20),(3,21),(4,8),(4,19),(4,21),(16,10),(16,17),(16,20),(26,7),(26,8),(26,10),(26,16),(26,17),(26,18),(27,8),(27,9),(27,16),(27,17),(27,18),(29,7),(29,12),(29,17),(29,18),(29,21),(30,8),(30,9),(30,21),(36,9),(36,12),(36,13),(47,7),(47,20),(47,21),(53,16),(53,17),(53,20),(54,8),(54,13),(54,19),(56,12),(56,13),(56,14),(56,15),(62,7),(62,9),(62,16),(62,21),(75,12),(75,13),(75,14),(75,15),(75,16),(76,13),(76,18),(76,19),(89,7),(89,12),(89,13),(89,14),(90,9),(90,20),(90,21),(92,7),(92,8),(92,9),(97,14),(97,15),(103,7),(103,20),(103,21);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_menu`
--

LOCK TABLES `pxl_menu` WRITE;
/*!40000 ALTER TABLE `pxl_menu` DISABLE KEYS */;
INSERT INTO `pxl_menu` VALUES (1,'Hello Menu','hello-menu'),(2,'menu numéro 2','menu-numero-2'),(3,'New menu','new-menu'),(7,'Menu Footer 1','footer-1'),(8,'Menu Footer 2','footer-2');
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_menu_item`
--

LOCK TABLES `pxl_menu_item` WRITE;
/*!40000 ALTER TABLE `pxl_menu_item` DISABLE KEYS */;
INSERT INTO `pxl_menu_item` VALUES (4,4,1,'hum ?','link...',1,2),(5,3,1,'Test','ah ?',1,3),(6,2,1,'','',0,0),(7,NULL,1,'New','',0,1),(8,NULL,3,'Plop 3','',0,0),(9,NULL,3,'Plop 2','',0,1),(10,NULL,3,'Plop 1','',0,2),(11,2,7,'','',0,0),(12,3,7,'','',0,2),(13,4,7,'','',0,1),(14,6,8,'','',0,0),(15,5,8,'','',0,0),(16,NULL,8,'Demo','http://www.google.fr',1,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_option`
--

LOCK TABLES `pxl_option` WRITE;
/*!40000 ALTER TABLE `pxl_option` DISABLE KEYS */;
INSERT INTO `pxl_option` VALUES (5,'Lien Facebook','facebook-link','http://www.facebook.com'),(6,'Lien Instagram','instagram-link','http://www.instagram.com'),(7,'Lien Twitter','twitter-link','http://www.twitter.com'),(8,'Copyright','copyright','© 2018 Pix.City'),(9,'Instagram Token','instagram_token','2138708826.09f1085.79dfcdc5a0e2403081e69f83c07a71b2');
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
INSERT INTO `pxl_page` VALUES (2,'Hello World !','hello-world','c\'est un super test \" très long !! hello','test','<h1>Hello World !</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a accumsan justo, eu lobortis mauris. Nam consequat euismod tellus, vel tincidunt augue malesuada eget. Praesent tempus nibh id est eleifend vehicula quis in leo. Donec dapibus ultricies viverra. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam risus augue, fermentum non tincidunt ac, elementum at erat. Etiam elementum, tellus eget placerat posuere, massa massa pulvinar lectus, vel tincidunt sem ipsum nec sapien. Duis a sollicitudin ligula. Phasellus tincidunt enim sed quam bibendum tincidunt. Nulla tortor lectus, facilisis at est dictum, dignissim rutrum risus. Vivamus eget auctor ex. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec a venenatis metus. Pellentesque nec nisi vel neque luctus malesuada quis sit amet tellus. Vivamus tincidunt lobortis elit vitae tincidunt. Praesent sed sem urna.</p><p>Etiam tincidunt bibendum rhoncus. Sed imperdiet ornare convallis. Etiam luctus condimentum justo in placerat. Vestibulum sit amet blandit urna, sed viverra dolor. Morbi scelerisque massa quis ullamcorper pulvinar. Etiam pretium fringilla tortor volutpat blandit. Proin eu semper leo. Morbi interdum ipsum non quam dignissim, sed semper elit condimentum. Maecenas ut suscipit dui. Vivamus auctor finibus purus, in venenatis est gravida a. Nulla rutrum tincidunt volutpat.</p><p><img src=\"/upload/1b2b304a69b6d8901e005db63a7e8f1586354e5a.jpeg\" class=\"fr-fic fr-dib fr-rounded\" style=\"width: 50%;\"></p><p>Vestibulum quis ex eget magna laoreet commodo vel ac enim. Curabitur in scelerisque lectus, sit amet volutpat libero. Nullam ultrices ipsum vel mattis varius. Vivamus eu velit felis. Sed id cursus nunc, quis vulputate lorem. Maecenas sed ultrices diam. Nullam volutpat lectus non efficitur laoreet. Curabitur in magna malesuada, porttitor augue et, rutrum odio. Suspendisse finibus eleifend elit, non porttitor purus ultrices sit amet.</p><p>Curabitur sed odio ex. Mauris condimentum porttitor dolor eget maximus. Aenean velit quam, venenatis facilisis sollicitudin eu, finibus in neque. Nulla bibendum lectus euismod consectetur bibendum. Curabitur placerat velit a eros congue laoreet. Cras augue lectus, consectetur eget erat sed, posuere varius dui. Nunc eu nisl lacus. Nulla nec accumsan neque. Donec tempus quam nec ligula venenatis efficitur. Proin nec semper diam, et maximus eros. Pellentesque ornare, velit in dignissim sodales, lectus urna vehicula erat, sit amet bibendum mauris elit ac nisi. Fusce consequat tincidunt libero, sed tristique magna hendrerit eu.</p><p>Nulla vestibulum, sem sit amet rutrum tempor, tellus arcu vehicula sem, ullamcorper tristique neque risus vitae purus. Duis ut dolor id eros accumsan elementum. Aliquam congue eleifend nibh, quis accumsan felis aliquam et. Duis et elit vel enim tristique pulvinar. Nulla tincidunt bibendum nunc sed convallis. Quisque luctus porttitor ligula quis maximus. Quisque a elit gravida, luctus sapien eu, sodales metus. Donec dolor massa, condimentum id interdum sed, tempus vel sem. Suspendisse laoreet nisl nibh, sit amet placerat lacus suscipit vitae.</p><h2>Hello World 2 !</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a accumsan justo, eu lobortis mauris. Nam consequat euismod tellus, vel tincidunt augue malesuada eget. Praesent tempus nibh id est eleifend vehicula quis in leo. Donec dapibus ultricies viverra. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam risus augue, fermentum non tincidunt ac, elementum at erat. Etiam elementum, tellus eget placerat posuere, massa massa pulvinar lectus, vel tincidunt sem ipsum nec sapien. Duis a sollicitudin ligula. Phasellus tincidunt enim sed quam bibendum tincidunt. Nulla tortor lectus, facilisis at est dictum, dignissim rutrum risus. Vivamus eget auctor ex. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec a venenatis metus. Pellentesque nec nisi vel neque luctus malesuada quis sit amet tellus. Vivamus tincidunt lobortis elit vitae tincidunt. Praesent sed sem urna.</p><p>Etiam tincidunt bibendum rhoncus. Sed imperdiet ornare convallis. Etiam luctus condimentum justo in placerat. Vestibulum sit amet blandit urna, sed viverra dolor. Morbi scelerisque massa quis ullamcorper pulvinar. Etiam pretium fringilla tortor volutpat blandit. Proin eu semper leo. Morbi interdum ipsum non quam dignissim, sed semper elit condimentum. Maecenas ut suscipit dui. Vivamus auctor finibus purus, in venenatis est gravida a. Nulla rutrum tincidunt volutpat.</p><p><img src=\"/uploads/img/654ee2a2714a2509a23592ba366aa17da246359f.png\" style=\"width: 300px;\" class=\"fr-fic fr-dib fr-fil\"></p><p>Vestibulum quis ex eget magna laoreet commodo vel ac enim. Curabitur in scelerisque lectus, sit amet volutpat libero. Nullam ultrices ipsum vel mattis varius. Vivamus eu velit felis. Sed id cursus nunc, quis vulputate lorem. Maecenas sed ultrices diam. Nullam volutpat lectus non efficitur laoreet. Curabitur in magna malesuada, porttitor augue et, rutrum odio. Suspendisse finibus eleifend elit, non porttitor purus ultrices sit amet.</p><p>Curabitur sed odio ex. Mauris condimentum porttitor dolor eget maximus. Aenean velit quam, venenatis facilisis sollicitudin eu, finibus in neque. Nulla bibendum lectus euismod consectetur bibendum. Curabitur placerat velit a eros congue laoreet. Cras augue lectus, consectetur eget erat sed, posuere varius dui. Nunc eu nisl lacus. Nulla nec accumsan neque. Donec tempus quam nec ligula venenatis efficitur. Proin nec semper diam, et maximus eros. Pellentesque ornare, velit in dignissim sodales, lectus urna vehicula erat, sit amet bibendum mauris elit ac nisi. Fusce consequat tincidunt libero, sed tristique magna hendrerit eu.</p><p>Nulla vestibulum, sem sit amet rutrum tempor, tellus arcu vehicula sem, ullamcorper tristique neque risus vitae purus. Duis ut dolor id eros accumsan elementum. Aliquam congue eleifend nibh, quis accumsan felis aliquam et. Duis et elit vel enim tristique pulvinar. Nulla tincidunt bibendum nunc sed convallis. Quisque luctus porttitor ligula quis maximus. Quisque a elit gravida, luctus sapien eu, sodales metus. Donec dolor massa, condimentum id interdum sed, tempus vel sem. Suspendisse laoreet nisl nibh, sit amet placerat lacus suscipit vitae.</p>',1,'2018-03-12 18:19:20','2018-05-10 21:07:24',2),(3,'aaa','cest-un-super-test-tres-long-hello-1','aa','aa','<p>aaaa</p>',0,'2018-03-15 12:26:24','2018-05-10 20:04:45',NULL),(4,'bbb','bbbb','bbbb','bbb','<p>bb</p>',1,'2018-03-15 12:58:01','2018-03-15 13:56:40',NULL),(5,'aa','test-de-meta-title','test de meta title','bbbb','<p>ddddd</p>',1,'2018-03-15 13:49:25','2018-03-15 13:49:36',NULL),(6,'Accueil','accueil','Accueil','Description','<p>Hello homepage</p>',1,'2018-04-12 19:26:58','2018-04-12 19:26:58',2);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_page_category`
--

LOCK TABLES `pxl_page_category` WRITE;
/*!40000 ALTER TABLE `pxl_page_category` DISABLE KEYS */;
INSERT INTO `pxl_page_category` VALUES (1,8,'A','e','B','D','',0,1,'2018-04-13 21:12:53','2018-04-13 21:17:10',NULL,NULL,'C',''),(2,3,'Bretagne','bretagne','Bretagne','Bretagne','<p>Vivamus dui tortor, mattis venenatis porta eu, laoreet ac risus. In iaculis dignissim metus non tempus. Vestibulum velit dolor, tristique vitae enim id, pretium vulputate sem. Aenean et ante eu lectus facilisis rutrum eget at nisl.</p>',1,0,'2018-04-13 21:15:12','2018-05-12 17:55:41',1,2,'Découvrir la Bretagne','https://...'),(3,1,'Auvergne-Rhône-Alpes','auvergne-rhone-alpes','Auvergne-Rhône-Alpes','Auvergne-Rhône-Alpes','',1,0,'2018-05-09 15:55:16','2018-05-09 15:55:16',3,4,'Découvez l\'Auvergne-Rhône-Alpes','');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_page_category_media`
--

LOCK TABLES `pxl_page_category_media` WRITE;
/*!40000 ALTER TABLE `pxl_page_category_media` DISABLE KEYS */;
INSERT INTO `pxl_page_category_media` VALUES (1,'2849e7d54045317ad180d7108b70a369.jpeg'),(2,'0e9968031b7d49981bdbaecc357a3e2a.jpeg'),(3,'5e73415877349013e609144b16db234e.jpg'),(4,'49563ea86c8c83021bb5b8bfa7266f48.jpg');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_slider_media`
--

LOCK TABLES `pxl_slider_media` WRITE;
/*!40000 ALTER TABLE `pxl_slider_media` DISABLE KEYS */;
INSERT INTO `pxl_slider_media` VALUES (9,'2ce92ca3730a30c43858e1af66a44f4a.svg'),(13,'a7e9eae332f2cf48fcde818cf6053c05.svg'),(14,'728bb6fe7b3077528d470610d804aaec.jpeg');
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
INSERT INTO `pxl_slider_slide` VALUES (3,2,9,NULL,'C\'est quoi Pix.City ?','<p>Suspendisse id dictum massa. Proin ut libero justo. Duis est nibh, sagittis in placerat id, volutpat sed nulla. Cras luctus et leo ut convallis. Suspendisse consectetur consectetur lacus sed blandit. </p>','2018-04-12 17:34:26','2018-05-08 20:58:04',1),(4,2,13,14,'C\'est quoi Pix.City ?','<p>Suspendisse id dictum massa. Proin ut libero justo. Duis est nibh, sagittis in placerat id, volutpat sed nulla. Cras luctus et leo ut convallis.</p>','2018-04-12 17:34:35','2018-05-08 21:00:32',0);
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
  `current_location` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pixie_id` int(11) DEFAULT NULL,
  `roles` json NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `reset_password_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reset_password_token_expire` datetime DEFAULT NULL,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `register_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_location_id` int(11) DEFAULT NULL,
  `optin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7F574EDB31F7C64C` (`pixie_id`),
  UNIQUE KEY `UNIQ_7F574EDB86383B10` (`avatar_id`),
  KEY `index_email` (`email`),
  KEY `IDX_7F574EDBA0C0BE62` (`birth_location_id`),
  CONSTRAINT `FK_7F574EDB31F7C64C` FOREIGN KEY (`pixie_id`) REFERENCES `pxl_user_pixie` (`id`),
  CONSTRAINT `FK_7F574EDB86383B10` FOREIGN KEY (`avatar_id`) REFERENCES `pxl_user_media` (`id`),
  CONSTRAINT `FK_7F574EDBA0C0BE62` FOREIGN KEY (`birth_location_id`) REFERENCES `pxl_region` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user`
--

LOCK TABLES `pxl_user` WRITE;
/*!40000 ALTER TABLE `pxl_user` DISABLE KEYS */;
INSERT INTO `pxl_user` VALUES (1,'Adrien','Lamotte','0642482947','1906-06-11','91180','female','adrien.lamotte@gmail.com','$2y$13$T71uus/bULEf.Hdl9Cswd.4qSawLGOsiQYjgS9Gs9MzygLwgEjSq.',21,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-16 19:11:29','2018-07-05 20:25:33','',NULL,'','118041428434273725177',NULL,1,'',3,NULL),(4,'Adrien','Lamotte','642482947','1902-01-01','df gsdgdf g','male','lamotte@gmail.com','$2y$13$F2MpRhkAOmHC4o3g9Uur5OjmmtStdzTtqx1KbuKxVSCKCHvxdUzcK',1,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-19 18:41:39','2018-03-20 15:16:54','','0000-00-00 00:00:00',NULL,NULL,NULL,NULL,'',NULL,NULL),(5,'test','test','06042621510','1902-01-01','sdfsdfsd','female','test@test.com','$2y$13$m6JqAQnlRImJDyOk8XQW1.fPEShBeHP07.eyy5xU8a1w9Gr.YV4Fe',3,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 15:21:28','2018-03-20 18:47:38','','0000-00-00 00:00:00',NULL,NULL,NULL,NULL,'',NULL,NULL),(6,'Hello','Pixie','aa','1902-01-01','aa','male','hellopixie@gmail.com','$2y$13$oud5B1W0yBB2hFr1KV/Fausht/NdwFe12IuI1QXintRbsj.HDE.IW',4,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 18:51:51','2018-05-09 18:16:06','','0000-00-00 00:00:00',NULL,NULL,NULL,NULL,'',NULL,NULL),(7,'aaaaa','Lamotte','642482947','1986-01-01','a','male','adrien.lamotte222@gmail.com','$2y$13$VK8Gu7fNZ13jlVwyn78/OeYPMTkj60L/iXqNpmQSSM9C.WckfKj5.',7,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-20 20:12:30','2018-05-09 19:18:07','','0000-00-00 00:00:00',NULL,NULL,NULL,NULL,'',NULL,NULL),(8,'Adrien','Lamotte','642482947','1986-11-05','78610','male','adrien.lamotteqrthe@gmail.com','$2y$13$BzKdCT19maiKtavg56ynqeb.ni68I4thwusxCnHlVe/X/xubwWmIO',8,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-21 15:24:26','2018-05-30 17:08:00','','0000-00-00 00:00:00',NULL,NULL,NULL,NULL,'',NULL,NULL),(9,'z','z','z','1902-01-01','z','male','zzz@zz.zz2','$2y$13$JgHDp/eaKuoQsPgGCFQFHexb8SGHAF2abjrJyFSr0H/RURTG0Ud/K',10,'[\"ROLE_PIXIE\"]','2018-03-21 16:19:17','2018-05-12 20:58:19','','0000-00-00 00:00:00',NULL,NULL,NULL,NULL,'',NULL,NULL),(10,'A','B','+33642482947','1986-01-01','a','female','adrien.lamotte.pixie@gmail.com','$2y$13$c5DtXi5ipxLqQuhSeKp73eAno.OM4RsOyW5yNVFieoeCsEoCaFdOC',9,'[\"ROLE_USER\", \"ROLE_PIXIE\"]','2018-03-21 17:04:11','2018-06-29 15:27:16','','0000-00-00 00:00:00',NULL,NULL,1,NULL,'',8,NULL),(11,'Adrien','Lamotte','0642482947','1986-11-05','91180','male','adrien.lamotte.heydemo@gmail.com','$2y$13$0jzhwe9A3pzarfDr8tG6TO9VytKowwR.pu0ps6/UhEc.j4qphyCEi',NULL,'[]','2018-06-29 19:51:26','2018-06-29 19:51:26','',NULL,NULL,NULL,2,0,'',8,NULL),(12,'Adrien','Lamotte','0642482947','1915-03-14','12345','male','adrien.la.m.otte@gmail.com','$2y$13$eqqpOq/7sJaaskczh3FZ/uAPA39lqqm8EMQaphcJh5FTdRZQUL7.2',NULL,'[]','2018-06-30 14:13:30','2018-06-30 14:13:30','',NULL,NULL,NULL,3,0,'690ae8a0744a34b063bb213db9e4d783',4,0),(13,'Adrien','Lamotte','0642482947','1915-03-14','12345','male','ad.rien.la.m.otte@gmail.com','$2y$13$d9PxvJhhbKUcHlInoJ9Kme4dpaEIRSyZIjSAnFLXfgoLE15OL9H4u',NULL,'[]','2018-06-30 14:15:38','2018-06-30 14:15:38','',NULL,NULL,NULL,4,0,'ec96562af22c19f16303fb32a1f2a1c4',4,0),(14,'Adrien','Lamotte','0642482947','1915-03-14','12345','male','a.d.rien.la.m.otte@gmail.com','$2y$13$en269V9eI9kwDgzoojJ1ieLEF0Flb4LwnmDKXaE5z1AFxqcQIGvKm',NULL,'[]','2018-06-30 14:48:40','2018-06-30 14:48:40','',NULL,NULL,NULL,5,0,'32a04fe74aadfc6a32fe25ef6fb1e453',4,0),(15,'Adrien','Lamotte','0642482947','1915-03-14','12345','male','adrienlamotte@gmail.com','$2y$13$tyfeJzuDA4FtN/CTJWuyEuJW2XVYrmx1F4sa5/SjRMbiqiFRpT/OW',NULL,'[]','2018-06-30 14:56:00','2018-06-30 18:08:42','',NULL,NULL,NULL,6,1,'fb9f21b2399f9700dfadbe9a87442613',4,1),(17,'Adrien','Lamotte','+33642482947','1904-03-02','91180','male','adrien.lamotteXXXXX@gmail.com','$2y$13$EDlsYHzPoKAkgbEIXpRaUO8O108PVRn757jB3TxAs29W9dxaH072W',11,'[]','2018-07-04 18:25:24','2018-07-04 18:25:24','',NULL,NULL,NULL,9,0,'07000aa0bb9f76089384c3b967e97892',10,0),(18,'Adrien','Lamotte','+33642482947','1904-03-02','91180','male','adrien.lamotteXXXXX@gmail.com','$2y$13$Z60hLaFap2R3fkiUoDyOUeeNn83W5HZssxjJPkGi7gEvTuh0txykm',12,'[\"ROLE_PIXIE\"]','2018-07-04 18:32:32','2018-07-04 18:32:32','',NULL,NULL,NULL,10,0,'fd1c21e825cff0dead01a17848819cdf',10,0),(19,'Adrien','Lamotte','+33642482947','1904-02-02','11111','male','adrien.lamotteXXXXX@gmail.com','$2y$13$nQNQSEhOoFMaWG1ACJWXl.j3pLtz..5IyTntvyOgVSHzEG5o.c/.S',13,'[\"ROLE_PIXIE\"]','2018-07-04 18:36:49','2018-07-04 18:36:49','',NULL,NULL,NULL,11,0,'76023580300ae413878a8b60b5bb83e2',2,0),(20,'Adrien','Lamotte','+33642482947','1904-02-02','11111','male','adrien.lamotteXXXXX@gmail.com','$2y$13$x13zGSIGpsSO46908fDMTe7dhTXXyYfGSds60sxZy8GFkhLlhliEu',14,'[\"ROLE_PIXIE\"]','2018-07-04 18:39:19','2018-07-04 18:39:19','',NULL,NULL,NULL,12,0,'c9cb4cf561de8f610f4ba4190eed81ac',2,0),(21,'Adrien','Lamotte','+33642482947','1904-02-02','11111','male','adrien.lamotteXXXXX2@gmail.com','$2y$13$6EqPVkeGh6AIH4mDxL1vReyDsNb4mN.5ChJvTIxxfP3QMbh/RWdT2',15,'[\"ROLE_PIXIE\"]','2018-07-04 19:00:01','2018-07-04 19:00:01','',NULL,NULL,NULL,13,0,'7b1fba3f767e6a81ac65b471578e41d5',2,0),(22,'Adrien','Lamotte','+33642482947','1904-02-02','11111','male','adrien.lamotteXXXXX5@gmail.com','$2y$13$26m1pmLyHFO81QhH0tPRhO35L.BCbQpX8jX4z036Jn9z.VPQIyGCS',16,'[\"ROLE_PIXIE\"]','2018-07-04 20:11:16','2018-07-04 20:11:16','',NULL,NULL,NULL,14,0,'0599adc302f155db4428401ab4d1a070',2,0),(23,'Adrien','Lamotte','+33642482947','1904-02-02','11111','male','adrienlamot.te@gmail.com','$2y$13$cpOZualqasKuGYT/Y7qLb.YN.S8.TlzGjiNdH1FIXSsajNEMW7cB2',17,'[\"ROLE_PIXIE\"]','2018-07-04 21:06:14','2018-07-04 21:06:32','',NULL,NULL,NULL,15,1,'c45f7a5fe91f8bf8fa7b668ca55bd742',2,0),(24,'Adrien','Lamotte','0642482947','1905-02-02','91180','male','adrien@lesindependants.net','$2y$13$TWB6qyNYgs0o4v5KrqpbOup/EwJfP6ZZeE5vnGiMJP9W93bJnhJWS',18,'[\"ROLE_PIXIE\"]','2018-07-05 17:33:59','2018-07-05 17:35:42','',NULL,NULL,'107448157671312317697',16,1,'1d9d162add8b03e398fa6a2581b5a078',10,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_link`
--

LOCK TABLES `pxl_user_link` WRITE;
/*!40000 ALTER TABLE `pxl_user_link` DISABLE KEYS */;
INSERT INTO `pxl_user_link` VALUES (17,4,'http://www.facebook.fr','facebook'),(19,4,'http://www.twitter.fr','twitter'),(20,4,'http://instagram.fr','instagram'),(21,7,'http://www.facebbok.fr','facebook'),(22,6,'http://www.instagram.com','instagram'),(23,6,'http://www.facebook.com','facebook'),(24,6,'http://www.blog.fr','blog'),(29,8,'http://www.instagram2.com','instagram'),(30,8,'http://www.facebook.com','facebook'),(31,8,'http://www.twitter.com','twitter'),(32,8,'http://www.blog.com','blog'),(33,22,'http://www.instagram.com','instagram'),(35,1,'http://www.instagram.com','instagram');
/*!40000 ALTER TABLE `pxl_user_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pxl_user_media`
--

DROP TABLE IF EXISTS `pxl_user_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pxl_user_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_media`
--

LOCK TABLES `pxl_user_media` WRITE;
/*!40000 ALTER TABLE `pxl_user_media` DISABLE KEYS */;
INSERT INTO `pxl_user_media` VALUES (1,'d265b91d349f89717abdb3136fc23d87.jpg'),(2,'45981a72a1f8c31e3fbdd8a536681ca7.jpg'),(3,'7e894c8c1bd6d3a94fb976b59eb5c33b.png'),(4,'7e894c8c1bd6d3a94fb976b59eb5c33b.png'),(5,'7e894c8c1bd6d3a94fb976b59eb5c33b.png'),(6,'7e894c8c1bd6d3a94fb976b59eb5c33b.png'),(9,'82c85253b8b4a05f0a65f4c0be9c8c08.png'),(10,'82c85253b8b4a05f0a65f4c0be9c8c08.png'),(11,'6891e02e895f908eac0a66614cfb692b.jpg'),(12,'6891e02e895f908eac0a66614cfb692b.jpg'),(13,'6891e02e895f908eac0a66614cfb692b.jpg'),(14,'6891e02e895f908eac0a66614cfb692b.jpg'),(15,'6891e02e895f908eac0a66614cfb692b.jpg'),(16,'31675f27f39fbd6d6f39bcc3215feeda.png');
/*!40000 ALTER TABLE `pxl_user_media` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_pixie`
--

LOCK TABLES `pxl_user_pixie` WRITE;
/*!40000 ALTER TABLE `pxl_user_pixie` DISABLE KEYS */;
INSERT INTO `pxl_user_pixie` VALUES (1,'<p>sdfgdf dsfgdf&nbsp;</p>','<p>df gsdf g</p>',9),(3,'<p>J&#39;aime ceci</p>','<p>J&#39;aime pas cela</p>',1),(4,'<p>test 1</p>','<p>test 2</p>',3),(5,'<p>test</p>','<p>test</p>',4),(7,'<p>test</p>','<p>test</p>',6),(8,'<p>a</p>','<p>a</p>',7),(9,'<p>a</p>','<p>a</p>',8),(10,'<p>test</p>','<p>test</p>',10),(11,'<p>1</p>','<p>2</p>',11),(12,'<p>1</p>','<p>2</p>',12),(13,'<p>zzzz</p>','<p>zzz</p>',13),(14,'<p>zzzz</p>','<p>zzz</p>',14),(15,'<p>zzzz</p>','<p>zzz</p>',15),(16,'<p>zzzz</p>','<p>zzz</p>',16),(17,'<p>zzzz</p>','<p>zzz</p>',17),(18,'<p>Hey</p>','<p>Hey2</p>',18),(21,'<p>a</p>','<p>b</p>',21);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pxl_user_pixie_billing`
--

LOCK TABLES `pxl_user_pixie_billing` WRITE;
/*!40000 ALTER TABLE `pxl_user_pixie_billing` DISABLE KEYS */;
INSERT INTO `pxl_user_pixie_billing` VALUES (1,'individualregistration','','Adrien','Lamotte','A','0642482947','check','Adrien Lamotte','','','','',5),(3,'company','a','b','c','d','i','banktransfer','k','l','m','n','e3af9c43b116ee29cdc2389ed42aa4e4.pdf',NULL),(4,'test','test','test','test','test','test','test','test','test','test','test','test',NULL),(6,'individualregistration','a','b','c','d','i','banktransfer','k','l','m','n','8426318ab3dbe98662ccf343d413d984.png',NULL),(7,'company','a','a','a','a','a','check','a','a','a','a','a59c2471549ac2e08d01b4d300107bfa.pdf',NULL),(8,'individualregistration','','Adrien','Lamotte','AAAAA','+33642482947','check','Adrien Lamotte','','','','',2),(9,'company','A','','','A','A','check','A','','','','',NULL),(10,'individualregistration','test','Ad','La','test','test','check','test','','','','',6),(11,'company','AL','','','123456789','0642482947','check','Adrien Lamotte','','','','',29),(12,'company','AL','','','123456789','0642482947','check','Adrien Lamotte','','','','',30),(13,'company','aaa','','','123','aaa','check','Adrien Lamotte','','','','',31),(14,'company','aaa','','','123','aaa','check','Adrien Lamotte','','','','',32),(15,'company','eKimiya','','','123','+33642482947','check','Adrien Lamotte','','','','',33),(16,'company','eKimiya','','','123','+33642482947','check','Adrien Lamotte','','','','',34),(17,'company','eKimiya','','','123','+33642482947','check','Adrien Lamotte','','','','',35),(18,'individualregistration','','Adrien','Lamotte','123456','0102030405','banktransfer','Adrien Lamotte','France','11332455','121545454','',36),(21,'company','eKimiya','','','123456','+33642482947','check','Adrien Lamotte','','','','',38);
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
INSERT INTO `pxl_user_pixies_regions` VALUES (1,3),(3,4),(4,1),(4,9),(4,13),(7,1),(7,5),(8,3),(8,5),(8,11),(9,1),(9,3),(9,10),(9,13),(11,3),(11,11),(12,3),(12,11),(13,3),(13,7),(14,2),(14,7),(15,2),(15,7),(16,2),(16,7),(17,2),(17,7),(18,13),(21,1),(21,13);
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
INSERT INTO `pxl_users_cardcategories` VALUES (1,7),(1,8),(1,9),(1,10),(8,7),(8,9),(8,12),(9,10),(9,12),(9,19),(10,7),(10,8),(10,9),(11,7),(11,8),(11,9),(12,7),(12,8),(13,7),(13,8),(14,7),(14,8),(15,7),(15,8),(17,8),(17,9),(17,10),(18,8),(18,9),(18,10),(19,8),(19,9),(20,8),(20,9),(20,13),(21,9),(22,9),(23,9),(24,8),(24,9);
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

-- Dump completed on 2018-07-05 21:18:44

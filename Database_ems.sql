-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: ems
-- ------------------------------------------------------
-- Server version	5.7.43-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `leaving`
--

DROP TABLE IF EXISTS `leaving`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaving` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `leaving_date` date DEFAULT NULL,
  `leaving_from` time DEFAULT NULL,
  `leaving_to` time DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaving`
--

LOCK TABLES `leaving` WRITE;
/*!40000 ALTER TABLE `leaving` DISABLE KEYS */;
INSERT INTO `leaving` VALUES (7,3,'2024-01-25','13:20:00','13:59:00',39,1,'2024-01-25 09:40:06','2024-01-28 10:26:41'),(8,3,'2024-01-25','14:20:00','16:02:00',102,1,'2024-01-25 09:44:01','2024-01-25 09:44:50'),(9,3,'2024-01-25','16:43:00','17:43:00',60,1,'2024-01-25 13:43:34','2024-01-28 10:27:16'),(10,3,'2024-01-17','12:47:00','14:47:00',120,1,'2024-01-28 06:47:33','2024-01-28 10:28:41'),(11,3,'2024-01-19','14:28:00','15:28:00',60,1,'2024-01-28 11:28:50','2024-01-28 11:40:09');
/*!40000 ALTER TABLE `leaving` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `expiration_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES (29,5,'12a3251a65363b2a1774dd1fbfb0e4e4ed4f1ff34dbf4429b53f76414013a36b','2024-01-22 14:48:58','2024-01-22 12:48:58'),(30,5,'39236e4a8b7aafd234a49b3abca9430e83698f5825d55d3e1252307f89b87b84','2024-01-22 14:55:42','2024-01-22 12:55:42'),(31,5,'47c339cb6434422dddce9d0797de50b914d04c019d6b2b75118a7586f0ca0918','2024-01-22 15:10:23','2024-01-22 13:10:23'),(32,5,'59be97923cfd88913155a086b8e6e297863d0758d09bb85fe6cbb4a88284937b','2024-01-22 15:16:52','2024-01-22 13:16:52'),(33,5,'645392008f1d49facd4deda96456275cc0853ad88c2c205ee97fc434ceb3df57','2024-01-22 15:19:38','2024-01-22 13:19:38'),(34,5,'5e4ae342b7fbc218e07ce446e0c121e6c3954dbcb3e8738e5c06486fefa893da','2024-01-22 15:21:47','2024-01-22 13:21:47'),(35,5,'fce5c468584f130ce609d06305dd18176270b5a767fd30e7612fdeba59cb7e3c','2024-01-22 15:26:41','2024-01-22 13:26:41'),(36,5,'9aae0c1c71a4bc98c026086ee9bac08b0e75e8caec65592ae548bb314a429c3d','2024-01-22 15:31:23','2024-01-22 13:31:23'),(37,5,'070e38e1c05793d52c066dab4cb504ec2fa3176b1f90b057aeba50e2a53d881d','2024-01-22 15:45:00','2024-01-22 13:45:00'),(38,5,'238c1e5f4692911f21b1be63147f8bc3f4ef62f868a3058752ffb733b3b439a8','2024-01-23 08:13:23','2024-01-23 06:13:23'),(39,5,'125c85ad61d0505a49093bece80c3ad70e7ef831909c2b976a7968dbe48461c7','2024-01-23 08:21:27','2024-01-23 06:21:27'),(40,3,'0ffc6a4a5e50163e60a689dc4f44ac1a180099b3d8706472758666d58163d037','2024-01-23 08:27:15','2024-01-23 06:27:15'),(41,5,'313dca23187cc5ac8f17f6517950bcf387766b04941a147f52d9c11b81553baa','2024-01-23 08:41:12','2024-01-23 06:41:12'),(42,5,'c59721898af63c313a376505f986bcbaf7713fb5264939a27e1b03c5feb6c3b5','2024-01-23 08:42:33','2024-01-23 06:42:33'),(43,5,'39abc8533fa35f6ff9f2e4559a41f95d46fba5cacdc3f581acc26b7f8dc53d12','2024-01-23 08:43:45','2024-01-23 06:43:45'),(44,5,'da4e92f60fb972288879c893b996da9c20ec2616d18f1cf5c298e90e62a959ed','2024-01-23 09:01:45','2024-01-23 07:01:45'),(45,3,'856e39bc12496990ccdd6b4a45c0dc3668cd53456a0a340e21e13c66714d8991','2024-01-23 09:08:55','2024-01-23 07:08:55'),(46,5,'ce3b85907d3a4c13f274ee8c4f391cd528bef121cafd0160384b6187d5a31ff5','2024-01-24 14:50:29','2024-01-24 12:50:29');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role_id` int(11) DEFAULT '0',
  `active` int(11) DEFAULT '0',
  `is_logged_in` tinyint(1) DEFAULT '0',
  `login_time` timestamp NULL DEFAULT NULL,
  `logout_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'mohammad','moh','$2y$10$1Z8g.Z.8G46ZrDQs.pi2W.agyEHKM3bqSjxK8kdBCs/O.UlJFUXnS','mh.ba91@gmail.com','17452224',0,1,0,'2024-01-28 06:46:05','2024-01-23 10:11:15'),(4,'Ali','admin','$2y$10$BJ2xRjg70M5gL8vqdFiR6OTbRNNkJZ2UoNaS1sgUWMLNETdiohTLe','laithalledawe1@gmail.com','0788811695',1,1,1,NULL,NULL),(5,'mohannad','moh512','$2y$10$VmP3nKRTY1BmWh2WSFvyuuPMGOYUvQRdU/o.BVEYj.vIEmAxeR1Fy','yazan.arabii95@gmail.com','123456',0,1,0,'2024-01-25 06:18:29',NULL),(6,'Ahmad','israr10','$2y$10$MoDNeGbKrhz9K11f9sh/0OQLtDZdjA.B2JVnSckUdIV8UbyPRTTsm','a@gmail.com','0788811695',0,1,0,'2024-01-25 06:22:40',NULL),(7,'Ali','ali5','$2y$10$zedm8k0TqSVf97gpE.4l8.CGCG5EUFi27xdwFlI6cXxT.bM0p3ETS','ali5@gmail.com','0788811695',0,1,0,NULL,NULL),(8,'Assel','assel','$2y$10$KMMNaTjnVQZlXGGMVavfPeqdvENL3jnvJUE2tu05tOi55DvNkOTE2','assel@gmail.com','7845445445',0,1,0,NULL,NULL),(9,'aber','aber','$2y$10$8DLJ8lnBcoOd//fI/onzme7aE5pblqcjPz7eHOv8iscVuzANic0tO','aber@gmail.com','078454416',0,1,0,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacation`
--

DROP TABLE IF EXISTS `vacation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vacation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `vacation_from` date DEFAULT NULL,
  `vacation_to` date DEFAULT NULL,
  `duration` int(11) DEFAULT '0',
  `the_reason` text,
  `status` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacation`
--

LOCK TABLES `vacation` WRITE;
/*!40000 ALTER TABLE `vacation` DISABLE KEYS */;
INSERT INTO `vacation` VALUES (9,3,'2024-01-25','2024-01-27',2,'test',1,'2024-01-25 11:50:26','2024-01-25 12:15:17'),(10,3,'2024-01-29','2024-01-31',3,'test',1,'2024-01-28 11:32:46','2024-01-28 12:00:34');
/*!40000 ALTER TABLE `vacation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `working_hours`
--

DROP TABLE IF EXISTS `working_hours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `working_hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_of_day` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `working_hours`
--

LOCK TABLES `working_hours` WRITE;
/*!40000 ALTER TABLE `working_hours` DISABLE KEYS */;
INSERT INTO `working_hours` VALUES (24,3,'16:36:15','17:11:45','00:35:30','2024-01-24 11:39:23','2024-01-24 14:14:08','2024-01-23'),(26,5,'14:48:26','15:07:01','00:18:35','2024-01-24 11:45:31','2024-01-24 12:07:01','2024-01-24'),(30,3,'16:43:03',NULL,'03:36:59','2024-01-25 06:14:44','2024-01-25 13:43:03','2024-01-25'),(31,5,'09:18:29','09:19:52','00:01:23','2024-01-25 06:18:29','2024-01-25 06:19:52','2024-01-25'),(32,6,'09:22:40','09:23:35','00:00:55','2024-01-25 06:22:40','2024-01-25 06:23:35','2024-01-25'),(35,3,'09:46:05','15:24:34','05:38:29','2024-01-28 06:46:05','2024-01-28 12:24:34','2024-01-28');
/*!40000 ALTER TABLE `working_hours` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-01-28 16:41:55

CREATE DATABASE  IF NOT EXISTS `socialnetwork` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `socialnetwork`;
-- MySQL dump 10.13  Distrib 8.0.11, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: socialnetwork
-- ------------------------------------------------------
-- Server version	8.0.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment` varchar(200) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `posted_at` datetime DEFAULT NULL,
  `post_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_index` (`comment`,`user_id`,`posted_at`,`post_id`),
  KEY `user_id3_idx` (`user_id`),
  KEY `post_id_idx` (`post_id`),
  CONSTRAINT `post_id2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `user_id3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (37,'bug test',7,'2018-05-23 00:00:00',7),(77,'BUG TEST',7,'2018-05-23 00:00:00',9),(41,'bug test 2',7,'2018-05-23 00:00:00',7),(46,'bug test 3',7,'2018-05-23 00:00:00',7),(52,'bug test 4',7,'2018-05-23 00:00:00',7),(53,'bug test 5',7,'2018-05-23 00:00:00',7),(64,'BUG TEST 6',7,'2018-05-23 00:00:00',7),(10,'hello test',7,'2018-04-18 00:00:00',12),(5,'keep test',7,'2018-04-18 00:00:00',9),(23,'keep testing',7,'2018-05-23 00:00:00',9),(87,'looks nice',7,'2018-05-23 13:43:06',46),(96,'new bug appear',7,'2018-05-23 13:44:11',46),(12,'noo~~~~',7,'2018-04-18 00:00:00',12),(104,'reset 4',7,'2018-05-28 08:49:31',14),(105,'reset 5',7,'2018-05-28 08:49:42',35),(106,'reset 6',7,'2018-05-28 08:49:53',35),(78,'see if it fixed',7,'2018-05-23 13:41:45',7),(111,'test',7,'2018-05-29 15:55:48',1),(113,'test',14,'2018-05-29 16:02:00',53),(1,'Test Comment',7,'2018-04-18 00:00:00',10),(114,'test comment on profile page',14,'2018-05-29 16:02:12',53),(112,'test for yeoc',14,'2018-05-29 15:57:28',7),(109,'test form reset 1',7,'2018-05-28 08:57:27',9),(110,'test form reset 2',7,'2018-05-28 08:57:39',35),(20,'test new comment',7,'2018-05-23 00:00:00',7),(107,'test reset',7,'2018-05-28 08:53:35',14),(101,'test reset 1',7,'2018-05-28 08:46:13',14),(102,'test reset 2',7,'2018-05-28 08:46:28',10),(103,'test reset 3',7,'2018-05-28 08:46:48',14),(108,'test reset NO.8',7,'2018-05-28 08:54:00',10),(3,'What?',7,'2018-04-18 00:00:00',7),(11,'wtf?',7,'2018-04-18 00:00:00',14);
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `followers`
--

DROP TABLE IF EXISTS `followers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `followers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `followers`
--

LOCK TABLES `followers` WRITE;
/*!40000 ALTER TABLE `followers` DISABLE KEYS */;
INSERT INTO `followers` VALUES (3,4,2),(8,3,6),(10,2,6),(13,7,2),(14,5,2),(15,3,2),(16,7,3),(17,7,6),(18,2,7),(19,3,7),(21,5,7),(22,7,12),(23,12,7),(24,7,13),(25,5,13),(26,2,13),(27,7,14),(28,13,14),(29,2,14),(30,13,7);
/*!40000 ALTER TABLE `followers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_tokens`
--

DROP TABLE IF EXISTS `login_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `login_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `token_UNIQUE` (`token`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_tokens`
--

LOCK TABLES `login_tokens` WRITE;
/*!40000 ALTER TABLE `login_tokens` DISABLE KEYS */;
INSERT INTO `login_tokens` VALUES (48,'9e4cccabf9a589989da739824922cf90cecb081d',3),(49,'c1b2e78da0edb2c8d1cf1c8655f1d46ace98720f',2),(54,'64d9e488563a3bb0dc293d92804b3934233906c4',12),(55,'17ebaefc8aeb089b347045db51664abc73820d73',12),(56,'e1d1ba4382eb9b7cd51b732ba9975ebc3481e2b0',12),(75,'0a95e0b6edd2bfe34891524a84e3d9ab9606ddc1',13),(76,'04e98276c1427ba826d5cef39cdd4c8a3530f750',13),(77,'e9e0b5c654453cab1c736c021dbb90f3af9cb1b0',13),(80,'f36347da0f7a15aa99473881e4abfaa28421f60f',14),(88,'095bc16e8ff71cbe2ba04818cb349e92f61e8681',7),(89,'297c69e41dcac47f8e1d3ffbff453c3cd645b148',7),(97,'6fcbc17eb09fcae1d5e6f06517e232dd9893aaaa',2),(98,'b945465f4e8dc5975154e0b64d3f19788fa3e1b7',7);
/*!40000 ALTER TABLE `login_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `body` text,
  `sender` int(10) unsigned DEFAULT NULL,
  `receiver` int(10) unsigned DEFAULT NULL,
  `read` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'hello world',2,2,1),(2,'good day',2,7,1),(3,'good day to you',2,2,0),(4,'test back',7,2,1),(7,'test',2,2,0),(8,'hh',2,7,0),(9,'it works',7,2,0),(10,'hehe',7,7,0);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `receiver` int(10) unsigned NOT NULL,
  `sender` int(10) unsigned NOT NULL,
  `extra` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,1,2,7,NULL),(2,1,2,7,NULL),(3,1,7,2,' { \"postbody\": \"@Shenghong good day\" } '),(4,2,2,7,''),(5,2,3,7,''),(6,1,7,7,' { \"postbody\": \"@Shenghong  test notify\" } ');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_tokens`
--

DROP TABLE IF EXISTS `password_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `password_tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_UNIQUE` (`token`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_tokens`
--

LOCK TABLES `password_tokens` WRITE;
/*!40000 ALTER TABLE `password_tokens` DISABLE KEYS */;
INSERT INTO `password_tokens` VALUES (10,'ff0cb6e6ea9c0c06a80514a0433f76f4f5c25e5c',7),(11,'71c8c4a23315df269917f055374dad8ab162bd2f',7),(12,'1ea4e25d70a61c4ec2745b126c3ab0d9717a26a7',7);
/*!40000 ALTER TABLE `password_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_likes`
--

DROP TABLE IF EXISTS `post_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `post_likes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `user_id2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_likes`
--

LOCK TABLES `post_likes` WRITE;
/*!40000 ALTER TABLE `post_likes` DISABLE KEYS */;
INSERT INTO `post_likes` VALUES (7,8,2),(9,10,2),(10,11,2),(11,10,5),(14,7,5),(15,9,5),(16,12,3),(21,16,7),(41,9,7),(47,23,7),(48,35,7),(50,39,7),(53,14,7),(67,13,7),(68,14,2),(75,7,2),(79,7,7),(83,46,7),(84,47,7),(87,49,12),(88,42,12),(89,40,12),(90,34,12),(92,50,12),(93,11,12),(94,7,12),(95,9,12),(96,10,12),(97,50,7),(98,52,13),(99,53,13),(101,53,7),(102,49,7),(103,48,7),(105,57,7);
/*!40000 ALTER TABLE `post_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `body` varchar(200) NOT NULL,
  `posted_at` datetime DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `likes` int(10) unsigned DEFAULT NULL,
  `postimg` varchar(255) DEFAULT NULL,
  `topics` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  CONSTRAINT `` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'good day.','2018-04-17 14:27:52',6,0,NULL,NULL),(2,'Hello World','2018-04-17 14:44:56',6,0,NULL,NULL),(3,'Hello World','2018-04-17 14:45:44',6,0,NULL,NULL),(4,'Hello World','2018-04-17 14:46:59',6,0,NULL,NULL),(5,'what?','2018-04-17 14:47:52',6,0,NULL,NULL),(6,'what?','2018-04-17 14:48:17',6,0,NULL,NULL),(7,'hello','2018-04-17 15:25:22',7,4,NULL,NULL),(8,'He He','2018-04-17 16:20:58',7,1,NULL,NULL),(9,'Nice afternood','2018-04-17 16:21:12',7,3,NULL,NULL),(10,'TEXT','2018-04-17 16:21:37',7,3,NULL,NULL),(11,'Test','2018-04-17 16:21:43',7,2,NULL,NULL),(12,'test 1','2018-04-17 16:40:50',3,1,NULL,NULL),(13,'test 2','2018-04-17 16:41:04',3,1,NULL,NULL),(14,'test 3','2018-04-17 16:41:57',5,2,NULL,NULL),(15,'test 4','2018-04-17 16:42:46',7,0,NULL,NULL),(16,'Hello World from Post.php','2018-04-18 15:13:41',7,1,NULL,NULL),(17,'66666','2018-04-19 16:54:07',7,0,NULL,NULL),(21,'','2018-04-19 17:51:04',7,0,'https://i.imgur.com/Tcoqhr1.png',NULL),(23,'@bear hello','2018-04-20 14:38:11',7,1,NULL,NULL),(24,'test hashtag #PHP','2018-04-20 14:57:20',7,0,NULL,'PHP'),(34,'test #PHP #Java','2018-04-20 15:25:42',7,1,NULL,'PHP,Java,'),(35,'hello #PHP','2018-04-20 15:51:36',7,1,NULL,'PHP,'),(37,'@bear hello','2018-04-20 16:33:24',7,0,NULL,''),(38,'@bear hello again','2018-04-23 15:43:32',7,0,NULL,''),(39,'@Shenghong good day','2018-04-23 15:52:13',2,1,NULL,''),(40,'123456789','2018-05-19 20:19:54',7,1,NULL,''),(41,'123456789','2018-05-19 20:24:48',7,0,NULL,''),(42,'13435','2018-05-19 20:45:54',7,1,NULL,''),(43,'49845','2018-05-19 20:46:04',7,0,NULL,''),(44,'how?','2018-05-19 20:56:35',7,0,NULL,''),(45,'img test','2018-05-19 21:04:32',7,0,NULL,''),(46,'again test','2018-05-19 21:19:29',7,1,'https://i.imgur.com/W25NX1e.jpg',''),(47,'test img','2018-05-19 21:34:05',7,1,'https://i.imgur.com/hYlZlY1.jpg',''),(48,'test img','2018-05-19 21:40:38',7,1,'https://i.imgur.com/2LUT0bO.jpg',''),(49,'test hah','2018-05-21 22:15:19',7,2,NULL,''),(50,'seems it a success','2018-05-28 21:14:14',12,2,NULL,''),(52,'shit','2018-05-29 15:07:40',13,1,NULL,'123'),(53,'try again','2018-05-29 15:35:31',13,2,NULL,''),(55,'te','2018-05-29 15:37:28',14,0,NULL,''),(56,'Ops! It is empty~',NULL,NULL,NULL,NULL,NULL),(57,'so happy','2018-05-30 14:00:57',7,1,'https://i.imgur.com/OK8ZuEz.jpg',''),(58,'','2018-05-30 14:02:26',2,0,'https://i.imgur.com/NN41Civ.jpg',''),(59,'','2018-05-30 14:02:49',2,0,'https://i.imgur.com/ENEHhDT.jpg',''),(60,'@Shenghong  test notify','2018-05-30 14:32:00',7,0,NULL,'');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` text NOT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT '0',
  `profileimg` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,'bear','$2y$10$rw6Dx1936H88x4fMpazXje.anhbplwCUbnfXj1i56b8L7TajNbiHm','bear@gmail.com',1,NULL),(3,'bear1','$2y$10$eHPtjdMjiRTRKn0EiSi54O.WOYUuKCHHYF5DpQAxrId4eNc.9c4JK','bear@gmail.com',1,NULL),(5,'xiaoyu','$2y$10$GzOYBCWOfcF11odjUhYeouxbPyt1CFcmI7mmoEh//cIV.KR38n/Q6','xiaoyu@gmail.com',1,NULL),(6,'verified','$2y$10$YgSGVXP//S.GgSSbCThgMuY/2K..aJe9W3adUPSQtj.Ua4Aqz8B2a','verified@local.com',0,NULL),(7,'Shenghong','$2y$10$WhgCz2qduvvV3I3QF2SK2OQGRQc6fUzn8qJqA1ZYVDxDDKfqIFb3u','yeo09898@gmail.com',1,'https://i.imgur.com/hRktp13.jpg'),(8,'Yaoqi_Du','$2y$10$81vVx6lGNSOFHGggVITUGedxmo/NYI3lGvucSRSmuyLMRs8DFcd/m','run12@gmail.com',0,NULL),(9,'testAccount','$2y$10$wdyyYoxS2YEMtoLI.3PQiOS0WJQNDpGnt.bmTvpgsfhHWrpUNVyy2','testAccount@goodluck.com',0,NULL),(10,'fransis','$2y$10$msRiVutC1b/dWjzoLyJxHexg9RR7G1NsETaiTmjWrueM4KOWW5mfq','fransis@dj.com',0,NULL),(11,'yeo09898','$2y$10$bNfPYtCSg79i1Fhf2i0rCebW4Mvw2KXGUqU.TjDQ2RkQjOtavE0o.','yeo09898@outlook.com',0,NULL),(12,'horse','$2y$10$832rhjtiGOrozatL/lOeh.UiP2lhN0QYjiH.ZilsZEGXC0yZ6y8z2','horse@666.com',0,NULL),(13,'yqd123','$2y$10$khArWyu0mo.nbO/nd9UdEe069oMty1KKcw9P9r1arTvrlhkWk96kS','yqd@outlook.com',0,NULL),(14,'yeoc','$2y$10$qnbf/RCztQ61HbwjjpPXi.dkpEhnSgRbX6QdWvFWv8dXTUvb6f.jO','yeoc@555.com',0,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-30 15:39:29

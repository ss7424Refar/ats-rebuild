-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: localhost    Database: tpms
-- ------------------------------------------------------
-- Server version	5.7.24-0ubuntu0.18.04.1

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
-- Table structure for table `ats_session_analyse`
--

DROP TABLE IF EXISTS `ats_session_analyse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ats_session_analyse` (
  `date` varchar(12) CHARACTER SET latin1 NOT NULL,
  `sessions` text CHARACTER SET latin1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ats_session_analyse`
--

-- LOCK TABLES `ats_session_analyse` WRITE;
-- /*!40000 ALTER TABLE `ats_session_analyse` DISABLE KEYS */;
-- INSERT INTO `ats_session_analyse` VALUES ('2019-06-08','{\"Zhu Jiafeng\":1,\"Zhu Lin\":1,\"admin\":8}'),('2019-06-09','{\"Zhu Jiafeng\":1,\"Zhu Lin\":1,\"admin\":5,\"Zhao Tianer\":5}'),('2019-06-10','{\"Zhu Jiafeng\":1,\"Zhu Lin\":1,\"admin\":5,\"Zhao Tianer\":5}'),('2019-06-11','{\"Zhu Jiafeng\":1,\"Zhu Lin\":1,\"admin\":5,\"Zhao Tianer\":3}'),('2019-06-11','{\"Zhu Jiafeng\":1,\"Zhu Lin\":1,\"admin\":5,\"ssss\":3}'),('2019-06-12','{\"bbb\":1,\"aaa\":1,\"admin\":5,\"ssss\":3}'),('2019-06-13','{\"bbb\":1,\"aaa\":1,\"admin\":5,\"ddd\":3}'),('2019-06-14','{\"bbb\":1,\"aaa\":1,\"admin\":5,\"rrrr\":3}'),('2019-06-15','{\"bbb\":1,\"aaa\":1,\"admin\":5,\"dddxx\":3}'),('2019-06-16','{\"bbb\":1,\"aaa\":1,\"admin\":5,\"dddxx\":3}'),('2019-06-17','{\"bbb\":1,\"aaa\":1,\"admin\":5,\"dddxx\":3}');
-- /*!40000 ALTER TABLE `ats_session_analyse` ENABLE KEYS */;
-- UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-06-08 21:30:08

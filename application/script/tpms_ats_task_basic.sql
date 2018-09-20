-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: tpms
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu1

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
-- Table structure for table `ats_task_basic`
--

DROP TABLE IF EXISTS `ats_task_basic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ats_task_basic` (
  `task_id` bigint(10) NOT NULL AUTO_INCREMENT,
  `machine_id` int(7) DEFAULT NULL,
  `machine_name` varchar(30) DEFAULT NULL,
  `switch_id` int(2) DEFAULT NULL,
  `lan_ip` varchar(16) DEFAULT NULL,
  `shelf_id` int(1) DEFAULT NULL,
  `dmi_product_name` varchar(30) DEFAULT NULL,
  `dmi_part_number` varchar(30) DEFAULT NULL,
  `dmi_serial_number` varchar(20) DEFAULT NULL,
  `dmi_oem_string` varchar(100) DEFAULT NULL,
  `dmi_system_config` varchar(20) DEFAULT NULL,
  `bios_ec` varchar(20) DEFAULT NULL,
  `result` varchar(8) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `task_start_time` datetime DEFAULT NULL,
  `task_end_time` datetime DEFAULT NULL,
  `tester` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ats_task_basic`
--

LOCK TABLES `ats_task_basic` WRITE;
/*!40000 ALTER TABLE `ats_task_basic` DISABLE KEYS */;
/*!40000 ALTER TABLE `ats_task_basic` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-20 16:18:06

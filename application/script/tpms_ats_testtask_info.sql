-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: tpms
-- ------------------------------------------------------
-- Server version	5.7.21-1ubuntu1

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
-- Table structure for table `ats_testtask_info`
--

DROP TABLE IF EXISTS `ats_testtask_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ats_testtask_info` (
  `TaskID` bigint(10) NOT NULL AUTO_INCREMENT,
  `TestImage` varchar(60) DEFAULT NULL,
  `ExecuteJob` varchar(60) DEFAULT NULL,
  `OSActivation` varchar(8) DEFAULT NULL,
  `DMI_ProductName` varchar(30) DEFAULT NULL,
  `DMI_PartNumber` varchar(30) DEFAULT NULL,
  `DMI_SerialNumber` varchar(20) DEFAULT NULL,
  `DMI_OEMString` varchar(100) DEFAULT NULL,
  `DMI_SystemConfig` varchar(20) DEFAULT NULL,
  `BIOS_EC` varchar(20) DEFAULT NULL,
  `TestItem` varchar(20) DEFAULT NULL,
  `TestMachine` varchar(30) DEFAULT NULL,
  `MachineID` int(7) DEFAULT NULL,
  `SwitchId` int(2) DEFAULT NULL,
  `LANIP` varchar(16) DEFAULT NULL,
  `ShelfID` int(1) DEFAULT NULL,
  `TestResult` varchar(8) DEFAULT NULL,
  `TestResultPath` varchar(40) DEFAULT NULL,
  `TestStartTime` datetime DEFAULT NULL,
  `TestEndTime` datetime DEFAULT NULL,
  `TaskStatus` int(1) DEFAULT NULL,
  `Tester` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`TaskID`),
  UNIQUE KEY `TaskID_UNIQUE` (`TaskID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ats_testtask_info`
--

LOCK TABLES `ats_testtask_info` WRITE;
/*!40000 ALTER TABLE `ats_testtask_info` DISABLE KEYS */;
INSERT INTO `ats_testtask_info` VALUES (1,'.mozilla','Fast Startup,Standby,Microsoft Edge','yes','dynabook Tab S61/NG','PS68NNP-NXC111','7G1217893','PRT10U-AAAA6,PCN3381CCZ01FA1/S3A-----X--','1','234','JumpStart','Altair TX CS3',1607124,11,'192.168.1.109',1,NULL,NULL,NULL,NULL,0,'Zhu Lin'),(2,'java_error_in_PHPSTORM.hprof','Fast Startup,Standby,Microsoft Edge','yes','dynabook Tab S61/NG','PS68NNP-NXC','7G1217893','PRT10U-AAAA6,PCN3381CCZ01FA1/S3A-----X--','1','234','JumpStart','Altair TX CS3',1607124,11,'192.168.1.109',1,NULL,NULL,'2018-09-07 13:34:49','2018-09-09 13:35:38',5,'admin'),(3,'java_error_in_PHPSTORM.hprof','Fast Startup,Standby,Microsoft Edge','yes','dynabook Tab S61/NG','PS68NNP-NXC','7G1217893','PRT10U-AAAA6,PCN3381CCZ01FA1/S3A-----X--','1','234','JumpStart','Altair TX CS3',1607124,11,'192.168.1.109',1,NULL,NULL,'2018-09-07 13:34:47','2018-09-09 13:36:41',5,'admin'),(4,'java_error_in_PHPSTORM.hprof','Fast Startup,Standby,Microsoft Edge','yes','dynabook Tab S60/NG','PS68NNP-NXB','7G121789','PRT10U-AAAA5,PCN3381CCZ01FA1F/S3A-----X--','2','234','JumpStart','Altair TX CS1',1607123,10,'192.168.1.109',1,NULL,NULL,'2018-09-07 13:34:00','2018-09-09 13:39:50',5,'admin'),(5,'java_error_in_PHPSTORM.hprof','Fast Startup,Standby,Microsoft Edge','yes','dynabook Tab S61/NG','PS68NNP-NXC','7G1217893','PRT10U-AAAA6,PCN3381CCZ01FA1/S3A-----X--','1','234','JumpStart','Altair TX CS3',1607124,11,'192.168.1.109',1,NULL,NULL,'2018-09-07 13:38:35','2018-09-09 13:41:04',5,'admin'),(6,'.mozilla','','no','dynabook Tab S60/NG','PS68NNP-NXB','7G121789','PRT10U-AAAA5,PCN3381CCZ01FA1F/S3A-----X--','2','234','Recovery','Altair TX CS1',1607123,10,'192.168.1.109',1,NULL,NULL,'2018-09-07 13:38:35','2018-09-09 13:42:29',5,'admin'),(7,'.mozilla','','no','dynabook Tab S61/NG','PS68NNP-NXC','7G1217893','PRT10U-AAAA6,PCN3381CCZ01FA1/S3A-----X--','1','234','Recovery','Altair TX CS3',1607124,11,'192.168.1.109',1,NULL,NULL,'2018-09-07 13:38:35',NULL,1,'admin'),(8,'下载','Fast Startup,Standby,Microsoft Edge','yes','dynabook Tab S61/NG','PS68NNP-NXC','7G1217893','PRT10U-AAAA6,PCN3381CCZ01FA1/S3A-----X--','1','','JumpStart','Altair TX CS3',1607124,11,'192.168.1.109',1,NULL,NULL,NULL,NULL,0,'admin');
/*!40000 ALTER TABLE `ats_testtask_info` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-07 19:56:22

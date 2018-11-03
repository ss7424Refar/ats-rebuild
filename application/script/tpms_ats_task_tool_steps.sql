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
-- Table structure for table `ats_task_tool_steps`
--

DROP TABLE IF EXISTS `ats_task_tool_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ats_task_tool_steps` (
  `task_id` bigint(10) NOT NULL,
  `tool_name` varchar(10) DEFAULT NULL COMMENT '	',
  `status` int(1) DEFAULT NULL,
  `steps` int(3) DEFAULT NULL,
  `element_json` json DEFAULT NULL,
  `result` varchar(8) DEFAULT NULL,
  `result_path` varchar(40) DEFAULT NULL,
  `tool_start_time` datetime DEFAULT NULL,
  `tool_end_time` datetime DEFAULT NULL,
  KEY `fk_task_id` (`task_id`),
  CONSTRAINT `fk_task_id` FOREIGN KEY (`task_id`) REFERENCES `ats_task_basic` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ats_task_tool_steps`
--

LOCK TABLES `ats_task_tool_steps` WRITE;
/*!40000 ALTER TABLE `ats_task_tool_steps` DISABLE KEYS */;
INSERT INTO `ats_task_tool_steps` VALUES (28,'JumpStart',0,1,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"java_error_in_PHPSTORM.hprof\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,'2018-11-03 16:28:40',NULL),(28,'JumpStart',0,2,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"KeepCurrentImage\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(28,'JumpStart',0,3,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"java_error_in_PHPSTORM.hprof\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(27,'JumpStart',0,1,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,'2018-11-03 16:32:26',NULL),(27,'JumpStart',0,2,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(27,'JumpStart',0,3,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(27,'JumpStart',0,4,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(27,'JumpStart',0,5,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(27,'JumpStart',0,6,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(27,'JumpStart',0,7,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\"}',NULL,NULL,NULL,NULL),(27,'JumpStart',0,8,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \".mozilla\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(27,'JumpStart',0,9,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"java_error_in_PHPSTORM.hprof\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(14,'Recovery',0,1,'{\"Tool_Type\": \"Recovery\", \"Test_Image\": \".mozilla\", \"OS_Activation\": \"NO\"}',NULL,NULL,'2018-11-03 16:43:30',NULL),(25,'JumpStart',0,1,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,'2018-11-03 16:43:56',NULL),(29,'JumpStart',0,1,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup\", \"OS_Activation\": \"YES\"}',NULL,NULL,'2018-11-03 19:19:07',NULL),(29,'JumpStart',0,2,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"桌面\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(26,'JumpStart',0,1,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,'2018-11-03 19:20:22',NULL),(26,'JumpStart',0,2,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,NULL,NULL),(24,'JumpStart',0,1,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \"Keep Current Image\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,'2018-11-03 19:35:01',NULL),(24,'Recovery',0,2,'{\"Tool_Type\": \"Recovery\", \"Test_Image\": \"java_error_in_PHPSTORM.hprof\", \"OS_Activation\": \"NO\"}',NULL,NULL,NULL,NULL),(24,'C-Test',0,3,'{\"Day\": \"3\", \"Min\": \"1\", \"Hour\": \"1\", \"End_After\": \"Interval\", \"Tool_Type\": \"C-Test\"}',NULL,NULL,NULL,NULL),(23,'JumpStart',0,1,'{\"Tool_Type\": \"JumpStart\", \"Test_Image\": \".presage\", \"Execute_Job\": \"Fast Startup,Standby,Microsoft Edge\", \"OS_Activation\": \"YES\"}',NULL,NULL,'2018-11-03 19:45:00',NULL),(23,'Recovery',0,2,'{\"Tool_Type\": \"Recovery\", \"Test_Image\": \".kingsoft\", \"OS_Activation\": \"NO\"}',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ats_task_tool_steps` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-03 19:51:28

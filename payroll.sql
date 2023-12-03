-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: spm
-- ------------------------------------------------------
-- Server version	8.1.0

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
-- Table structure for table `payroll`
--

DROP TABLE IF EXISTS `payroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `position` varchar(45) NOT NULL,
  `employee` varchar(45) NOT NULL,
  `official_dept` varchar(45) NOT NULL,
  `compensation` varchar(45) NOT NULL,
  `lirpGOVT` varchar(45) DEFAULT NULL,
  `ecc` varchar(45) DEFAULT NULL,
  `phlhlthGS` varchar(45) DEFAULT NULL,
  `pgibigGS` varchar(45) DEFAULT NULL,
  `tax` varchar(45) DEFAULT NULL,
  `rlipps` varchar(45) DEFAULT NULL,
  `gsisREG` varchar(45) DEFAULT NULL,
  `gsisCONSO` varchar(45) DEFAULT NULL,
  `gsisMPL` varchar(45) DEFAULT NULL,
  `gsisLOAN` varchar(45) DEFAULT NULL,
  `gsisPOLICY` varchar(45) DEFAULT NULL,
  `gsisGFAL` varchar(45) DEFAULT NULL,
  `gsisEMERG` varchar(45) DEFAULT NULL,
  `phhlthPS` varchar(45) DEFAULT NULL,
  `pgibigHDMF` varchar(45) DEFAULT NULL,
  `pgibigMP2` varchar(45) DEFAULT NULL,
  `pgibigPS` varchar(45) DEFAULT NULL,
  `pgibigMPL` varchar(45) DEFAULT NULL,
  `lbpLOAN` varchar(45) DEFAULT NULL,
  `tempcoLOAN` varchar(45) DEFAULT NULL,
  `tempcoBUILDUP` varchar(45) DEFAULT NULL,
  `tempcoINTEREST` varchar(45) DEFAULT NULL,
  `tameFEE` varchar(45) DEFAULT NULL,
  `sss` varchar(45) DEFAULT NULL,
  `tameLOAN` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll`
--

LOCK TABLES `payroll` WRITE;
/*!40000 ALTER TABLE `payroll` DISABLE KEYS */;
/*!40000 ALTER TABLE `payroll` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-20 17:19:02

-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: memorycoin
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.1

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
-- Table structure for table `blocks`
--

DROP TABLE IF EXISTS `blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blocks` (
  `height` int(11) NOT NULL,
  `hash` char(64) NOT NULL,
  `confirmations` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `merkleroot` char(64) NOT NULL,
  `time` int(11) NOT NULL,
  `nonce` int(11) NOT NULL,
  `bits` varchar(16) NOT NULL,
  `difficulty` decimal(16,8) NOT NULL,
  `totalvalue` decimal(16,8) NOT NULL,
  `totalfee` decimal(16,8) NOT NULL,
  `transactions` int(11) NOT NULL,
  `previousblockhash` char(64) NOT NULL,
  `nextblockhash` char(64) NOT NULL,
  `rawblock` text NOT NULL,
  PRIMARY KEY (`hash`),
  UNIQUE KEY `height` (`height`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inputs`
--

DROP TABLE IF EXISTS `inputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inputs` (
  `tx` char(64) NOT NULL,
  `prev` char(64) NOT NULL,
  `index` int(11) NOT NULL,
  `value` decimal(16,8) NOT NULL,
  `scriptsig` text NOT NULL,
  `hash160` char(64) NOT NULL,
  `type` text NOT NULL,
  `block` char(64) NOT NULL,
  KEY `tx` (`tx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `keys`
--

DROP TABLE IF EXISTS `keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keys` (
  `hash160` text NOT NULL,
  `address` varchar(34) NOT NULL,
  `pubkey` text NOT NULL,
  `firstseen` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nodes`
--

DROP TABLE IF EXISTS `nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nodes` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `port` int(5) NOT NULL,
  `dns` varchar(32) NOT NULL,
  `geo` varchar(32) NOT NULL,
  `churn` int(1) NOT NULL,
  `official` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `orphans`
--

DROP TABLE IF EXISTS `orphans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orphans` (
  `height` int(11) NOT NULL,
  `hash` char(64) NOT NULL,
  UNIQUE KEY `height` (`height`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `outputs`
--

DROP TABLE IF EXISTS `outputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outputs` (
  `tx` char(64) NOT NULL,
  `index` int(11) NOT NULL,
  `value` decimal(16,8) NOT NULL,
  `scriptpubkey` text NOT NULL,
  `hash160` text NOT NULL,
  `type` char(32) NOT NULL,
  `block` char(64) NOT NULL,
  KEY `tx` (`tx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `hash` char(64) NOT NULL,
  `block` char(64) NOT NULL,
  `confirmations` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `fee` decimal(16,8) NOT NULL,
  `txraw` text NOT NULL,
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-30 14:56:16

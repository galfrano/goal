-- MySQL dump 10.13  Distrib 5.5.60, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: octo
-- ------------------------------------------------------
-- Server version	5.5.60-0ubuntu0.14.04.1

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
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `name` varchar(255) DEFAULT NULL,
  `id` int(8) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `name` varchar(511) NOT NULL,
  `address` varchar(1023) NOT NULL,
  `dic` varchar(31) NOT NULL,
  `ic` varchar(31) NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=206 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES ('Las Adelitas s.r.o.','Americka 684/8, 12000, Praha 2, ÄŒeskÃ¡ Republika','CZ28965361','2896536-1',1),('bvhhjkvjhg','cgjcvgh','fghfgh','fgjk',3),('name-4','address-4','dic-4','ic-4',109),('name-5','address-5','dic-5','ic-5',110),('cliente prueba','address-6','dic-6','ic-6',111),('name-7','address-7','dic-7','ic-7',112),('name-9','address-9','dic-9','ic-9',114),('name-11','address-11','dic-11','ic-11',116),('name-12','address-12','dic-12','ic-12',117),('name-13','address-13','dic-13','ic-13',118),('name-14','address-14','dic-14','ic-14',119),('name-15','address-15','dic-15','ic-15',120),('name-16','address-16','dic-16','ic-16',121),('name-17','address-17','dic-17','ic-17',122),('name-18','address-18','dic-18','ic-18',123),('name-19','address-19','dic-19','ic-19',124),('name-20','address-20','dic-20','ic-20',125),('name-21','address-21','dic-21','ic-21',126),('name-22','address-22','dic-22','ic-22',127),('name-23','address-23','dic-23','ic-23',128),('name-24','address-24','dic-24','ic-24',129),('name-25','address-25','dic-25','ic-25',130),('name-26','address-26','dic-26','ic-26',131),('name-27','address-27','dic-27','ic-27',132),('name-28','address-28','dic-28','ic-28',133),('name-29','address-29','dic-29','ic-29',134),('name-30','address-30','dic-30','ic-30',135),('name-31','address-31','dic-31','ic-31',136),('name-32','address-32','dic-32','ic-32',137),('name-33','address-33','dic-33','ic-33',138),('name-34','address-34','dic-34','ic-34',139),('name-35','address-35','dic-35','ic-35',140),('name-36','address-36','dic-36','ic-36',141),('name-37','address-37','dic-37','ic-37',142),('name-38','address-38','dic-38','ic-38',143),('name-39','address-39','dic-39','ic-39',144),('name-40','address-40','dic-40','ic-40',145),('name-41','address-41','dic-41','ic-41',146),('name-42','address-42','dic-42','ic-42',147),('name-43','address-43','dic-43','ic-43',148),('name-44','address-44','dic-44','ic-44',149),('name-45','address-45','dic-45','ic-45',150),('name-46','address-46','dic-46','ic-46',151),('name-47','address-47','dic-47','ic-47',152),('name-48','address-48','dic-48','ic-48',153),('name-49','address-49','dic-49','ic-49',154),('name-50','address-50','dic-50','ic-50',155),('name-51','address-51','dic-51','ic-51',156),('name-52','address-52','dic-52','ic-52',157),('name-53','address-53','dic-53','ic-53',158),('name-54','address-54','dic-54','ic-54',159),('name-55','address-55','dic-55','ic-55',160),('name-56','address-56','dic-56','ic-56',161),('name-57','address-57','dic-57','ic-57',162),('name-58','address-58','dic-58','ic-58',163),('name-59','address-59','dic-59','ic-59',164),('name-60','address-60','dic-60','ic-60',165),('name-61','address-61','dic-61','ic-61',166),('name-62','address-62','dic-62','ic-62',167),('name-63','address-63','dic-63','ic-63',168),('name-64','address-64','dic-64','ic-64',169),('name-65','address-65','dic-65','ic-65',170),('name-66','address-66','dic-66','ic-66',171),('name-67','address-67','dic-67','ic-67',172),('name-68','address-68','dic-68','ic-68',173),('name-69','address-69','dic-69','ic-69',174),('name-70','address-70','dic-70','ic-70',175),('name-71','address-71','dic-71','ic-71',176),('name-72','address-72','dic-72','ic-72',177),('name-73','address-73','dic-73','ic-73',178),('name-74','address-74','dic-74','ic-74',179),('name-75','address-75','dic-75','ic-75',180),('name-76','address-76','dic-76','ic-76',181),('name-77','address-77','dic-77','ic-77',182),('name-78','address-78','dic-78','ic-78',183),('name-79','address-79','dic-79','ic-79',184),('name-80','address-80','dic-80','ic-80',185),('name-81','address-81','dic-81','ic-81',186),('name-82','address-82','dic-82','ic-82',187),('name-83','address-83','dic-83','ic-83',188),('name-84','address-84','dic-84','ic-84',189),('name-85','address-85','dic-85','ic-85',190),('name-86','address-86','dic-86','ic-86',191),('name-87','address-87','dic-87','ic-87',192),('name-88','address-88','dic-88','ic-88',193),('name-89','address-89','dic-89','ic-89',194),('name-90','address-90','dic-90','ic-90',195),('name-91','address-91','dic-91','ic-91',196),('name-92','address-92','dic-92','ic-92',197),('name-93','address-93','dic-93','ic-93',198),('name-94','address-94','dic-94','ic-94',199),('name-95','address-95','dic-95','ic-95',200),('name-96','address-96','dic-96','ic-96',201),('name-97','address-97','dic-97','ic-97',202),('name-98','address-98','dic-98','ic-98',203),('name-99','address-99','dic-99','ic-99',204),('name-100','address-100','dic-100','ic-100',205);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `name` varchar(511) NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES ('finance',1),('customer management',2),('accounting',3);
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `department` int(11) unsigned NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `department` (`department`),
  CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`department`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employees` (
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `department` int(11) unsigned NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `department` (`department`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`department`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES ('fgsddsa@cfsdas.com','Juan','Garcia',2,1);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entries`
--

DROP TABLE IF EXISTS `entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `product` int(11) unsigned NOT NULL,
  `quantity` int(3) NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  CONSTRAINT `entries_ibfk_1` FOREIGN KEY (`product`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entries`
--

LOCK TABLES `entries` WRITE;
/*!40000 ALTER TABLE `entries` DISABLE KEYS */;
INSERT INTO `entries` VALUES (2,200,1);
/*!40000 ALTER TABLE `entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_products`
--

DROP TABLE IF EXISTS `invoice_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_products` (
  `invoice` int(11) unsigned NOT NULL,
  `product` int(11) unsigned NOT NULL,
  `quantity` int(3) NOT NULL,
  `price_no_dph` float(9,2) NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `invoice` (`invoice`),
  KEY `product` (`product`),
  CONSTRAINT `invoice_products_ibfk_1` FOREIGN KEY (`invoice`) REFERENCES `invoices` (`id`),
  CONSTRAINT `invoice_products_ibfk_2` FOREIGN KEY (`product`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_products`
--

LOCK TABLES `invoice_products` WRITE;
/*!40000 ALTER TABLE `invoice_products` DISABLE KEYS */;
INSERT INTO `invoice_products` VALUES (3,2,3,500.00,5),(3,1,2,525.00,6),(1,1,2,557.00,7),(1,2,3,1000.00,8),(4,1,2,100.00,10),(4,2,3,500.00,11),(2,4,1,200.00,12);
/*!40000 ALTER TABLE `invoice_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `invoice_number` varchar(15) NOT NULL,
  `creation_date` date NOT NULL,
  `transaction_date` date NOT NULL,
  `payment_date` date NOT NULL,
  `customer` int(11) unsigned NOT NULL,
  `discount` int(2) DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `customer` (`customer`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES ('jhgfjh-2547','2018-04-10','2018-04-25','2018-04-21',3,0,1),('gfd-412','2018-05-03','2018-03-27','2018-04-29',118,9,2),('29082017','2018-05-03','2018-03-27','2018-04-29',1,6,3),('2575623745','2018-04-11','2018-04-11','2018-04-17',1,0,4);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `name` varchar(255) NOT NULL,
  `price_no_dph` float(9,2) NOT NULL,
  `price_dph` float(9,2) NOT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES ('Orgullo latino blanco',723.55,800.00,1),('Santo infierno',1300.00,1500.00,2),('Orgullo latino reposado',156.00,160.00,3),('mexal alipus',156.00,44.00,4);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `clearance` int(1) DEFAULT NULL,
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('galfrano@gmail.com','P4$$w0rd',9,1),('elputo@octo.com','pass123',2,2),('test2@octo.com','pass123',3,3);
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

-- Dump completed on 2018-04-30 10:02:57

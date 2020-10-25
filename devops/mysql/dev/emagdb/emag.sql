CREATE DATABASE  IF NOT EXISTS `emag` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `emag`;
-- MySQL dump 10.13  Distrib 8.0.21, for Linux (x86_64)
--
-- Host: localhost    Database: emag
-- ------------------------------------------------------
-- Server version	5.7.31

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
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subCategoryId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_uri` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subCat fk_idx` (`subCategoryId`),
  KEY `name` (`name`),
  CONSTRAINT `subCat fk` FOREIGN KEY (`subCategoryId`) REFERENCES `sub_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,1,'Apple','apple.png'),(2,1,'Samsung','samsung.png'),(3,1,'Nokia','nokia.png'),(4,2,'Apple','apple.png'),(5,2,'Lenovo','lenovo.png'),(6,2,'Samsung','samsung.png'),(7,3,'Asus','asus.png'),(8,3,'Apple','apple.png'),(9,3,'Dell','dell.png'),(10,4,'Gorenje','gorenje.png'),(11,4,'Bosch','bosch.png'),(12,4,'Liebherr','liebherr.png'),(13,5,'Whirpool','whirpool.png'),(14,5,'Gorenje','gorenje.png'),(15,5,'Hansa','hansa.png'),(16,6,'Whirpool','whirpool.png'),(17,6,'Beko','beko.png'),(18,6,'Indesit','indesit.png'),(19,7,'Versace','versace.png'),(20,7,'Armani','armani.png'),(21,7,'Philipp Plein','philipp_plain'),(22,8,'Versace','versace.png'),(23,8,'Armani','armani.png'),(24,8,'Philipp Plein','philipp_plain.png'),(25,9,'D&G','d&g.png'),(26,9,'Armani Junior','armani.png'),(27,9,'Guess Kids','guess.png'),(28,10,'Organic','organic.png'),(29,10,'Urban Organic','urbanorganic.png'),(30,11,'Johnny Walker','johnniewalker.png'),(31,11,'Beluga','beluga.png'),(32,11,'Karnobatska','karnobatska.jpg'),(33,3,'Nokia','nokia.png'),(34,1,'LG','lg.png'),(35,4,'Beko','beko.png');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Mobile Devices'),(2,'Large Apliances'),(3,'Fashion'),(4,'Food and Drinks');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favourites`
--

DROP TABLE IF EXISTS `favourites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favourites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userId_2` (`userId`,`productId`),
  KEY `userId` (`userId`),
  KEY `productId` (`productId`),
  CONSTRAINT `favourites_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  CONSTRAINT `favourites_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favourites`
--

LOCK TABLES `favourites` WRITE;
/*!40000 ALTER TABLE `favourites` DISABLE KEYS */;
INSERT INTO `favourites` VALUES (12,4,16),(15,4,72);
/*!40000 ALTER TABLE `favourites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `models`
--

DROP TABLE IF EXISTS `models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brandId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `brandId` (`brandId`),
  KEY `name` (`name`),
  CONSTRAINT `models_ibfk_1` FOREIGN KEY (`brandId`) REFERENCES `brands` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `models`
--

LOCK TABLES `models` WRITE;
/*!40000 ALTER TABLE `models` DISABLE KEYS */;
INSERT INTO `models` VALUES (1,1,'Iphone X'),(2,1,'Iphone XI'),(3,2,'Galaxy S10'),(4,2,'Galaxy S11'),(5,3,'8 Sirocco'),(6,3,'7 Plus'),(7,4,'iPad Pro 2018'),(8,4,'iPad Pro 2019'),(9,5,'Yoga Book C930'),(10,5,'Idea Pad Mix D330'),(11,6,'Galaxy Tab S4'),(12,6,'Galaxy Tab S5'),(13,7,'RoG GL552VX'),(14,7,'RoG GL771HZ'),(15,8,'Mac Air 13'),(16,8,'Mac Air 17'),(17,9,'Alien Ware 17'),(18,9,'Alien Ware 15'),(19,10,'RX432'),(20,10,'RX456'),(21,11,'CL812K'),(22,11,'CL918Z'),(23,12,'DAO444'),(24,12,'DAO991'),(25,13,'DB332'),(26,13,'DB87Z'),(27,14,'MVC13'),(28,14,'MVC17'),(29,15,'CLASS90'),(30,15,'CLASS100ML'),(31,16,'ABSTRACT7'),(32,16,'ABSTRACT10'),(33,17,'JK1269'),(34,17,'JK1399'),(35,18,'BS1112R'),(36,18,'BS1221Z'),(37,19,'Jeans'),(38,19,'Shoes'),(39,20,'Shirt'),(40,20,'Underwear'),(41,21,'T-shirt'),(42,21,'Sport Shoes'),(43,22,'Shoes'),(44,22,'Underwear'),(45,23,'Short Skirt'),(46,24,'Underwear'),(47,24,'Short Skirt'),(48,25,'Sport Shoes'),(49,25,'T-shirt'),(50,26,'T-shirt'),(51,26,'Jeans'),(52,27,'Hat'),(53,27,'Jacket'),(54,28,'Yoghurt'),(55,28,'Milk'),(56,29,'Helzeinut Tahan'),(57,29,'Vegetables'),(58,30,'Gold Label'),(59,30,'Blue Label'),(60,31,'Noble'),(61,31,'Transatlantic'),(62,32,'Grape'),(63,32,'Plum'),(65,1,'iPhone XII'),(66,1,'iPhone XIII'),(67,1,'iPhone XIV'),(68,32,'Pineapple'),(69,32,'Strawberry'),(70,33,'DSA 123'),(71,7,'RoG D2-Miracle!'),(72,30,'Red Label'),(73,34,'12'),(74,19,'Hat'),(75,1,'ZXC'),(76,8,'Mac Air 20'),(77,34,'13'),(78,1,'Watch 3d'),(79,35,'ZXC'),(80,1,'qwe'),(81,20,'Wallets'),(82,1,'IT Season X');
/*!40000 ALTER TABLE `models` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordered_products`
--

DROP TABLE IF EXISTS `ordered_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordered_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `singlePrice` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orderId` (`orderId`),
  KEY `productId` (`productId`),
  CONSTRAINT `ordered_products_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `orders` (`id`),
  CONSTRAINT `ordered_products_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordered_products`
--

LOCK TABLES `ordered_products` WRITE;
/*!40000 ALTER TABLE `ordered_products` DISABLE KEYS */;
INSERT INTO `ordered_products` VALUES (29,25,3,1,2100),(30,26,9,1,1600),(31,26,16,1,2600),(32,26,20,1,800),(33,26,71,1,200),(34,27,75,1,10),(35,28,72,1,4555),(36,29,71,1,200),(37,29,72,1,4555),(38,30,72,1,4555),(39,31,72,1,4555),(40,32,72,1,4555),(41,33,71,1,200),(42,34,16,1,2600),(43,35,13,3,2000),(44,36,8,1,1500),(45,36,71,1,200),(46,37,13,1,2000),(47,38,9,1,1600),(48,39,13,2,2000),(49,40,71,33,200),(50,41,8,15,1500),(51,41,13,14,2000),(52,41,71,1,200),(53,42,7,1,1400),(54,43,8,1,1500),(55,43,71,1,200),(56,43,72,1,20),(57,44,3,1,2100),(58,45,8,1,1500),(59,46,72,2,20),(60,47,58,2,200),(61,47,59,1,300);
/*!40000 ALTER TABLE `ordered_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (21,1,'2019-03-09 11:54:35'),(23,1,'2019-03-09 12:01:22'),(25,1,'2019-03-09 12:03:27'),(26,4,'2019-03-09 13:20:27'),(27,4,'2019-03-09 13:23:55'),(28,4,'2019-03-09 13:36:09'),(29,4,'2019-03-10 11:08:20'),(30,4,'2019-03-10 15:46:07'),(31,4,'2019-03-10 15:47:10'),(32,4,'2019-03-10 15:47:21'),(33,4,'2019-03-10 15:47:47'),(34,4,'2019-03-10 15:48:44'),(35,4,'2019-03-10 17:14:30'),(36,4,'2019-03-10 17:57:43'),(37,4,'2019-03-10 17:59:25'),(38,4,'2019-03-10 17:59:55'),(39,1,'2019-03-10 18:40:24'),(40,1,'2019-03-10 19:26:39'),(41,1,'2019-03-10 19:56:10'),(42,1,'2019-03-10 20:18:57'),(43,4,'2019-03-11 16:29:31'),(44,1,'2019-03-11 19:33:40'),(45,1,'2019-03-11 19:36:47'),(46,4,'2019-03-11 20:12:22'),(47,15,'2019-03-12 16:44:27');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_spec`
--

DROP TABLE IF EXISTS `product_spec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subCategoryId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subCategoryId` (`subCategoryId`),
  CONSTRAINT `product_spec_ibfk_1` FOREIGN KEY (`subCategoryId`) REFERENCES `sub_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_spec`
--

LOCK TABLES `product_spec` WRITE;
/*!40000 ALTER TABLE `product_spec` DISABLE KEYS */;
INSERT INTO `product_spec` VALUES (1,1,'Display'),(2,1,'Camera'),(3,2,'Display'),(4,2,'RAM'),(5,3,'Processor'),(6,3,'Video Card'),(7,4,'Volume'),(8,4,'Energy Class'),(9,5,'Size'),(10,5,'Energy Class'),(11,6,'Energy Class'),(12,6,'Capacity'),(13,7,'Material'),(14,7,'Size'),(15,8,'Material'),(16,8,'Size'),(17,9,'Material'),(18,9,'Size'),(19,10,'Country'),(20,10,'Weight'),(21,11,'Years Old'),(22,11,'Bottle Size');
/*!40000 ALTER TABLE `product_spec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subCategoryId` int(11) NOT NULL,
  `modelId` int(11) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `modelId` (`modelId`),
  KEY `subCatgegoryId` (`subCategoryId`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`modelId`) REFERENCES `models` (`id`),
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subCategoryId`) REFERENCES `sub_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,2000,239),(2,1,2,1650,18),(3,1,3,500,250),(4,1,4,2050,24),(5,1,5,1780,543),(6,1,6,1850,432),(7,2,7,1400,431),(8,2,8,1500,58),(9,2,9,1600,109),(10,2,10,1700,123),(11,2,11,1450,323),(12,2,12,1550,222),(13,3,13,2000,110),(14,3,14,2200,332),(15,3,15,2400,554),(16,3,16,2600,42),(17,3,17,2700,445),(18,3,18,3000,321),(19,4,19,1000,32),(20,4,20,800,122),(21,4,21,1500,31),(22,4,22,999,123),(23,4,23,1220,321),(24,4,24,1550,123),(25,5,25,1000,112),(26,5,26,1000,432),(27,5,27,1100,232),(28,5,28,560,123),(29,5,29,700,322),(30,5,30,450,321),(31,6,31,600,25),(32,6,32,900,324),(33,6,33,900,321),(34,6,34,770,123),(35,6,35,1230,657),(36,6,36,1999,123),(37,7,37,100,4324),(38,7,38,150,42342),(39,7,39,300,2342),(40,7,40,255,234),(41,7,41,500,300),(42,7,42,321,23423),(43,8,43,222,234423),(44,8,44,100,2342),(45,8,45,1000,432),(46,8,46,1200,4432),(47,8,47,1400,234),(48,9,48,50,443),(49,9,49,30,555),(50,9,50,20,656),(51,9,51,99,545),(52,9,52,32,888),(53,9,53,67,765),(54,10,54,20,32),(55,10,55,15,55),(56,10,56,11,432),(57,10,57,7,432),(58,11,58,200,430),(59,11,59,300,4321),(60,11,60,220,4322),(61,11,61,320,465),(62,11,62,20,333),(63,11,63,15,4343),(68,1,65,3000,5),(69,1,66,3500,5),(70,1,67,4000,12),(71,11,68,200,161),(72,11,69,20,47),(73,3,70,1000,180),(74,3,71,3200,20),(75,11,72,20,1999),(76,1,73,1000,100),(77,7,74,100,100),(78,1,75,1300,10),(79,3,76,3000,20),(80,1,77,2000,20),(81,1,78,1000,100),(82,4,79,400,44),(83,1,80,1230,32),(84,7,81,100,10),(85,1,82,250,200);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_images`
--

DROP TABLE IF EXISTS `products_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productId` int(11) NOT NULL,
  `img_uri` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id fk_idx` (`productId`),
  CONSTRAINT `product_id fk` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_images`
--

LOCK TABLES `products_images` WRITE;
/*!40000 ALTER TABLE `products_images` DISABLE KEYS */;
INSERT INTO `products_images` VALUES (1,1,'smartphones.jpg'),(2,2,'smartphones.jpg'),(3,3,'smartphones.jpg'),(4,4,'smartphones.jpg'),(5,5,'smartphones.jpg'),(6,6,'smartphones.jpg'),(7,7,'tablets.jpg'),(8,8,'tablets.jpg'),(9,9,'tablets.jpg'),(10,10,'tablets.jpg'),(11,11,'tablets.jpg'),(12,12,'tablets.jpg'),(13,13,'laptops.jpg'),(14,14,'laptops.jpg'),(15,15,'laptops.jpg'),(16,16,'laptops.jpg'),(17,17,'laptops.jpg'),(18,18,'laptops.jpg'),(19,19,'fridges.jpg'),(20,20,'fridges.jpg'),(21,21,'fridges.jpg'),(22,22,'fridges.jpg'),(23,23,'fridges.jpg'),(24,24,'fridges.jpg'),(25,25,'ovens.jpg'),(26,26,'ovens.jpg'),(27,27,'ovens.jpg'),(28,28,'ovens.jpg'),(29,29,'ovens.jpg'),(30,30,'ovens.jpg'),(31,31,'washing_machine.jpg'),(32,32,'washing_machine.jpg'),(33,33,'washing_machine.jpg'),(34,34,'washing_machine.jpg'),(35,35,'washing_machine.jpg'),(36,36,'washing_machine.jpg'),(37,37,'versace.png'),(38,38,'versace.png'),(39,39,'armani.png'),(40,40,'armani.png'),(41,41,'philipp_plain.png'),(42,42,'philipp_plain.png'),(43,43,'versace.png'),(44,44,'versace.png'),(45,45,'armani.png'),(46,46,'philipp_plain.png'),(47,47,'philipp_plain.png'),(48,48,'d&g.png'),(49,49,'d&g.png'),(50,50,'armani.png'),(51,51,'armani.png'),(52,52,'guess.png'),(53,53,'guess.png'),(54,54,'yogourt.jpg'),(55,55,'milk.jpg'),(56,56,'tahan.jpeg'),(57,57,'vegetables.jpg'),(58,58,'goldlabel.jpeg'),(59,59,'bluelabel.jpg'),(60,60,'beluganobel.jpg'),(61,61,'translantic.jpg'),(62,62,'karnobatska.jpg'),(63,63,'karnobatska.jpg'),(64,68,'smartphones.jpg'),(65,69,'smartphones.jpg'),(66,70,'smartphones.jpg'),(67,71,'1551898967.jpg'),(68,72,'1551899653.jpg'),(69,73,'1551954197.jpg'),(70,74,'1551970622.jpg'),(71,75,'redlabel.jpg'),(72,76,'smartphones.jpg'),(73,77,'1552124513.jpg'),(74,78,'smartphones.jpg'),(75,79,'laptops.jpg'),(76,80,'smartphones.jpg'),(77,81,'1552238075.jpg'),(78,82,'1552239916.jpg'),(79,83,'1552245029.jpg'),(80,84,'1552392103.jpg'),(81,85,'1552401544.jpg');
/*!40000 ALTER TABLE `products_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spec_values`
--

DROP TABLE IF EXISTS `spec_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spec_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productId` int(11) NOT NULL,
  `specId` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `productId` (`productId`),
  KEY `spec id fk_idx` (`specId`),
  CONSTRAINT `spec id fk` FOREIGN KEY (`specId`) REFERENCES `product_spec` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `spec_values_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spec_values`
--

LOCK TABLES `spec_values` WRITE;
/*!40000 ALTER TABLE `spec_values` DISABLE KEYS */;
INSERT INTO `spec_values` VALUES (1,1,1,'5,5 inches'),(2,1,2,'12 MP'),(3,2,1,'5,8 inches'),(4,2,2,'15 MP'),(5,3,1,'6,0 inches'),(6,3,2,'14 MP'),(7,4,1,'5,8 inches'),(8,4,2,'14 MP'),(9,5,1,'5,8 inches'),(10,5,2,'17 MP'),(11,6,1,'4,0 inches'),(12,6,2,'30 MP'),(13,7,3,'8 inches'),(14,7,4,'2 GB'),(15,8,3,'9 inches'),(16,8,4,'4GB'),(17,9,3,'8 inches'),(18,9,4,'4 GB'),(19,10,3,'10 inches'),(20,10,4,'2 GB'),(21,11,3,'7,7 inches'),(22,11,4,'8 GB'),(23,12,3,'8,9 inches'),(24,12,4,'16 GB'),(25,13,5,'Intel Dual Core 2GHz'),(26,13,6,'nVidia GeForce 970M'),(27,14,5,'Intel i7 4 core 3,45 GHz'),(28,14,6,'nVidia GeForce 1080 Ti'),(29,15,5,'Intel i5 3 core 2,97 GHz'),(30,15,6,'nVidia GeForce 1080 Ti'),(31,16,5,'Intel i5 3 core 2,97 GHz'),(32,16,6,'nVidia GeForce 970M'),(33,17,5,'Intel i5 3 core 2,97 GHz'),(34,17,6,'nVidia GeForce 1080 Ti'),(35,18,5,'Intel i7 4 core 3,45 GHz'),(36,18,6,'nVidia GeForce 2080 Ti'),(37,19,7,'250l'),(38,19,8,'A+++'),(39,20,7,'250l'),(40,20,8,'A++'),(41,21,7,'300l'),(42,21,8,'A+'),(43,22,7,'230l'),(44,22,8,'A++'),(45,23,7,'300l'),(46,23,8,'B'),(47,24,7,'300l'),(48,24,8,'A++'),(49,25,9,'100/80/75'),(50,25,10,'A++'),(51,26,9,'100/80/75'),(52,26,10,'A++'),(53,27,9,'100/80/75'),(54,27,10,'A+'),(55,28,9,'100/80/75'),(56,28,10,'A+++'),(57,29,9,'100/80/75'),(58,29,10,'A+'),(59,30,9,'100/80/75'),(60,30,10,'B'),(61,31,11,'A+'),(62,31,12,'5kg'),(63,32,11,'A+++'),(64,32,12,'6kg'),(65,33,11,'A+'),(66,33,12,'7kg'),(67,34,11,'A+++'),(68,34,12,'8kg'),(69,35,11,'A+'),(70,35,12,'5kg'),(71,36,11,'B'),(72,36,12,'7kg'),(73,37,13,'100% cotton'),(74,37,14,'M/X/XL'),(75,38,13,'leather'),(76,38,14,'40,41,42,44'),(77,39,13,'100% cotton'),(78,39,14,'M/X/XL'),(79,40,13,'100% silk'),(80,40,14,'M/X/XL/XXL'),(81,41,13,'100% cotton'),(82,41,14,'M/X/XL'),(83,42,13,'mesh'),(84,42,14,'40,41,42,44'),(85,43,15,'leather'),(86,43,16,'40,41,42,44'),(87,44,15,'100% silk'),(88,44,16,'M/X/XL/XXL'),(89,45,15,'elastic cotton'),(90,45,16,'M/X/XL/XXL'),(91,46,15,'100% silk'),(92,46,16,'M/X/XL/XXL'),(93,47,15,'100% cotton'),(94,47,16,'M/X/XL/XXL'),(95,48,17,'mesh'),(96,48,18,'40,41,42,44'),(97,49,17,'100% cotton'),(98,49,18,'M/X/XL/XXL'),(99,50,17,'100% cotton'),(100,50,18,'M/X/XL/XXL'),(101,51,17,'100% cotton'),(102,51,18,'M/X/XL/XXL'),(103,52,17,'leather'),(104,52,18,'S/M'),(105,53,17,'wool'),(106,53,18,'M/X/XL/XXL'),(107,54,19,'Bulgaria'),(108,54,20,'400gr.'),(109,55,19,'Greece'),(110,55,20,'1l.'),(111,56,19,'Norway'),(112,56,20,'100gr.'),(113,57,19,'China'),(114,57,20,'500gr.'),(115,58,21,'12'),(116,58,22,'0,7l'),(117,59,21,'15'),(118,59,22,'0,7'),(119,60,21,'12'),(120,60,22,'0,7l'),(121,61,21,'15'),(122,61,22,'0,7'),(123,62,21,'5'),(124,62,22,'0,7'),(125,63,21,'3'),(126,63,22,'0,7'),(133,68,1,'5,5 inches'),(134,68,2,'100MP'),(135,69,1,'5,5 inches'),(136,69,2,'200MP'),(137,70,1,'5,5 inches'),(138,70,2,'100MP'),(139,71,21,'33'),(140,71,22,'2.7'),(141,72,21,'44'),(142,72,22,'5'),(143,73,5,'5Ghz'),(144,73,6,'Nvidia'),(145,74,5,'7 GHz'),(146,74,6,'Nvidia geForce RTX 3005'),(147,75,21,'12'),(148,75,22,'0.7L'),(149,76,1,'5,5 inches'),(150,76,2,'100MP'),(151,77,13,'Leather'),(152,77,14,'30'),(153,78,1,'5,5 inches'),(154,78,2,'100MP'),(155,79,5,'7 GHz'),(156,79,6,'Intel Graphic Family'),(157,80,1,'5,5 inches'),(158,80,2,'100MP'),(159,81,1,'5,5 inches'),(160,81,2,'100MP'),(161,82,7,'123L'),(162,82,8,'A++'),(163,83,1,'5,5 inches'),(164,83,2,'asd'),(165,84,13,'Leather'),(166,84,14,'30'),(167,85,1,'5,5 inches'),(168,85,2,'5');
/*!40000 ALTER TABLE `spec_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_categories`
--

DROP TABLE IF EXISTS `sub_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `categoryId` (`categoryId`),
  CONSTRAINT `sub_categories_ibfk_1` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_categories`
--

LOCK TABLES `sub_categories` WRITE;
/*!40000 ALTER TABLE `sub_categories` DISABLE KEYS */;
INSERT INTO `sub_categories` VALUES (1,1,'Smartphones'),(2,1,'Tablets'),(3,1,'Laptops'),(4,2,'Fridges'),(5,2,'Ovens'),(6,2,'Washing Machines'),(7,3,'Men'),(8,3,'Women'),(9,3,'Kids'),(10,4,'Bio Foods'),(11,4,'Alcohol Drinks');
/*!40000 ALTER TABLE `sub_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `isAdmin` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@emag.bg','$2y$05$Z.cwq83YF/knjgXwqfKSXuJHV7gXdxWoHj8HzhahhPd04UIzQgPWe','admin','adminov',NULL,1),(4,'asd@asd.com','$2y$05$5tAOLUoWEEdTKnPt.7jR9OUmL009KPy283XmquHUy66pz6XLrl/Lm','asd','asd','Harmanli',0),(5,'asd1@asd.com','$2y$05$QbBFEOGI9orN1VPYK.bh/eVoukFY0j8iuvJP4F8KCW8O8fAeZPM1a','asd1','asd1',NULL,0),(6,'asd2@asd.com','$2y$05$XHMtIOPUcTOqrU76AYSpNuGuhZbO3gX8jotML2RBzGEjUfj8Dtt/6','asd2','asd2',NULL,0),(7,'asd3@asd.com','$2y$05$vJWA8PHeUr8xPJqFEJqdvOqf1EjVg6zLFDmYX8PDMCab1IK039Q86','asd3','asd3',NULL,0),(8,'asd10@asd.com','$2y$05$4JibGYE/tNjfYrCLROI8GuG.dJigb2j4pk7LkR5OJ5x11BN/PJthW','Asdfghjk','asdfghjk',NULL,0),(9,'asd@asd.bg','$2y$05$UB2BP68A02UxhE8IHPTw0.x5KgO8VEQw/3Y4BEIUiv7ahi4ZO.hsO','Bai','Stavri',NULL,0),(10,'georgi@asd.com','$2y$05$SFUOLTxSOchD.rHIPl0xHuiz5eTn7NOq/ZnhtW6ScYA.AjZEMpdOu','Ivaylo','Karastoyanov',NULL,0),(14,'DELETED\'14','DELETED','DELETED','DELETED','DELETED',0),(15,'dakata@abv.bg','$2y$05$2rYK05mWLfiVIrodxINf2eBZOX1F3Aveb/rej6EUVIrqoD1yCUNU.','Iordan','Mihaylov','Harmanli',0),(16,'naiden@asd.com','$2y$05$1Y7qNFEn6yFa3oZBfeQfres6PwxxJj8.myigDLkXR0wT2hPhhVunq','Dimitrov','Dimitrov',NULL,0),(17,'DELETED\'17','DELETED','DELETED','DELETED','DELETED',0),(18,'DELETED\'18','DELETED','DELETED','DELETED','DELETED',0),(19,'DELETED\'19','DELETED','DELETED','DELETED','DELETED',0),(20,'zxc@zxc.zxc','$2y$05$Cb2s6ViXwrv2Dt50FycyO.4j.JjMrIhYaqlToZ8c4qoC7umsCMVGO','zxc','zxcc',NULL,0),(21,'DELETED\'21','DELETED','DELETED','DELETED','DELETED',0);
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

-- Dump completed on 2020-10-19 17:11:59

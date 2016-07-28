-- MySQL dump 10.16  Distrib 10.1.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: oscar
-- ------------------------------------------------------
-- Server version	10.1.14-MariaDB-1~jessie

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
-- Table structure for table `acl`
--

DROP TABLE IF EXISTS `acl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl` (
  `acl_asset` int(6) unsigned NOT NULL DEFAULT '0' COMMENT 'Asset number',
  `acl_gid` int(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Group ID',
  `access` enum('none','part-read','full-read','write') NOT NULL DEFAULT 'none' COMMENT 'Access permissions',
  PRIMARY KEY (`acl_asset`,`acl_gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `asset`
--

DROP TABLE IF EXISTS `asset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset` (
  `asset` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `artno` int(6) NOT NULL DEFAULT '0' COMMENT 'Own article no',
  `productcode` char(16) DEFAULT '(none)',
  `prodcode` char(4) DEFAULT NULL,
  `prodno` int(3) DEFAULT NULL,
  `manufacturer` varchar(30) NOT NULL COMMENT 'Name of manufacturer',
  `model` varchar(30) DEFAULT NULL COMMENT 'Manufacturers model name',
  `serial` varchar(30) NOT NULL DEFAULT 'UNKNOWN' COMMENT 'Serial no',
  `date_code` varchar(16) NOT NULL DEFAULT 'N/A' COMMENT 'Date code',
  `description` varchar(255) NOT NULL DEFAULT 'NOT SPECIFIED',
  `long_description` text,
  `category` int(10) unsigned NOT NULL DEFAULT '1',
  `original_location` varchar(30) DEFAULT NULL,
  `current_location` varchar(30) DEFAULT NULL,
  `network_port` varchar(16) DEFAULT NULL,
  `computer_user` varchar(32) DEFAULT NULL,
  `client` tinyint(3) DEFAULT NULL,
  `parent_id` varchar(32) NOT NULL DEFAULT '0',
  `license_type` int(3) DEFAULT NULL,
  `license_key` varchar(64) DEFAULT NULL,
  `active` enum('Yes','No') DEFAULT 'No',
  `introduced` date DEFAULT NULL,
  `disposition` date DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `status_change` datetime DEFAULT NULL,
  `owner_dep` varchar(30) NOT NULL DEFAULT 'NOT SPECIFIED',
  `owner_name` varchar(30) NOT NULL DEFAULT 'NOT SPECIFIED',
  `po_number` varchar(16) DEFAULT NULL,
  `supplier` varchar(32) DEFAULT NULL COMMENT 'Supplier of product',
  `manuf_invoice` varchar(30) DEFAULT NULL,
  `product_code` varchar(128) DEFAULT NULL COMMENT 'Mnufacturers product code',
  `manuf_artno` varchar(16) DEFAULT NULL COMMENT 'Manufacturers article no',
  `supplier_artno` varchar(16) DEFAULT NULL COMMENT 'Suppliers article no',
  `barcode` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL DEFAULT 'NOT SPECIFIED',
  `comment` text,
  `user` int(6) DEFAULT NULL COMMENT 'Owner/creator of the asset',
  `perm_user` enum('none','read','write') DEFAULT 'none' COMMENT 'Permissions for owner/creator',
  `perm_group` enum('none','read','write') DEFAULT 'none' COMMENT 'Permissions of user group',
  `perm_other` enum('none','read','write') DEFAULT 'none' COMMENT 'Permisions of other users',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `asset_entry_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `asset_entry_created_by` varchar(45) NOT NULL DEFAULT '',
  `asset_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `asset_modified_by` varchar(45) NOT NULL DEFAULT '',
  `asset_created_host_ip` varchar(16) NOT NULL DEFAULT '',
  `asset_created_server_ip` varchar(16) NOT NULL DEFAULT '',
  `asset_modified_host_ip` varchar(16) DEFAULT NULL,
  `asset_modified_server_ip` varchar(16) DEFAULT NULL,
  `asset_created_session_id` varchar(64) NOT NULL,
  PRIMARY KEY (`asset`),
  KEY `asset` (`asset`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `asset_history`
--

DROP TABLE IF EXISTS `asset_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_history` (
  `hid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset` int(6) unsigned NOT NULL DEFAULT '0' COMMENT 'Asset ID, as per the asset table',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT 'A comment explaining why the asset was updated',
  `updated_by` varchar(45) NOT NULL DEFAULT '' COMMENT 'Person who inserted the comment (typically the same as who modified the asset)',
  `updated_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date and time the comment was added',
  PRIMARY KEY (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Log any modifications made to the asset entries';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `asset_tracking`
--

DROP TABLE IF EXISTS `asset_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asset_tracking` (
  `tid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `asset` int(6) unsigned DEFAULT NULL,
  `new_location` varchar(30) DEFAULT NULL,
  `move_date` datetime DEFAULT NULL,
  `comments` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tid`),
  UNIQUE KEY `tid` (`tid`),
  KEY `tid_2` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL DEFAULT '',
  `description` text,
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`category`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `cid` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `client` varchar(30) CHARACTER SET latin1 DEFAULT NULL,
  `client_full_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `info` text CHARACTER SET latin1,
  `active` enum('No','Yes') CHARACTER SET latin1 DEFAULT 'No',
  `contract_start` date DEFAULT NULL,
  `contract_end` date DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `cid` (`cid`),
  KEY `cid_2` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `dep_id` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ID for the department.',
  `dep_name` char(20) NOT NULL DEFAULT '',
  `active` enum('Yes','No') DEFAULT 'Yes',
  PRIMARY KEY (`dep_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='List of departments within the organisation';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(200) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `image_description` varchar(200) DEFAULT NULL,
  `entry_created` datetime DEFAULT NULL,
  `entry_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `licenses`
--

DROP TABLE IF EXISTS `licenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(10) unsigned NOT NULL DEFAULT '0',
  `software_name` varchar(50) NOT NULL DEFAULT 'Noname',
  `serial` varchar(50) NOT NULL DEFAULT 'No serial' COMMENT 'Serial/license number',
  `license_count` int(10) unsigned DEFAULT '1' COMMENT 'Number of users/seats',
  `purchase_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `comments` text,
  `entry_create` datetime DEFAULT NULL,
  `entry_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `photo` mediumblob,
  `photo_info` varchar(255) DEFAULT NULL,
  `date_recorded` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_stamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`,`user_name`) USING BTREE,
  KEY `user_name` (`user_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `po_items`
--

DROP TABLE IF EXISTS `po_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `po_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `po_id` int(10) unsigned DEFAULT NULL,
  `item` varchar(100) DEFAULT NULL,
  `description` varchar(400) DEFAULT NULL,
  `supplier_artno` varchar(20) DEFAULT NULL,
  `qty` int(10) unsigned NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `entry_created` datetime DEFAULT NULL,
  `entry_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `prodid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prodcode` char(4) NOT NULL DEFAULT '',
  `prodno` int(10) unsigned NOT NULL DEFAULT '0',
  `manufacturer` varchar(30) DEFAULT NULL,
  `model` varchar(30) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`prodid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aq_supplier` int(10) unsigned DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `ordered_by` varchar(20) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `receive_date` date DEFAULT NULL,
  `supplier_invoice_no` varchar(20) DEFAULT NULL,
  `entry_created` datetime DEFAULT NULL,
  `entry_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `stat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` char(32) DEFAULT NULL,
  `description` char(100) NOT NULL DEFAULT '' COMMENT 'Describe the purpose of the status level.',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  PRIMARY KEY (`stat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Asset status levels';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `supp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplier` varchar(32) NOT NULL DEFAULT '' COMMENT 'Name of supplier',
  `long_name` varchar(100) DEFAULT NULL COMMENT 'Long/complete name of supplier (if applicable)',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Brief description of the supplier',
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `post_code` varchar(16) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `telephone_main` varchar(32) DEFAULT NULL COMMENT 'Main telephone number (switchboard or similar)',
  `fax_main` varchar(32) DEFAULT NULL COMMENT 'Main fax number',
  `email_main` varchar(255) DEFAULT NULL COMMENT 'Main email address for company',
  `website` varchar(255) DEFAULT NULL COMMENT 'URL to company website',
  `active` enum('No','Yes') DEFAULT 'Yes',
  `contact_name_1` varchar(64) DEFAULT NULL COMMENT 'First contact',
  `contact_title_1` varchar(32) DEFAULT NULL,
  `contact_tel_1` varchar(32) DEFAULT NULL,
  `contact_fax_1` varchar(32) DEFAULT NULL,
  `contact_email_1` varchar(255) DEFAULT NULL,
  `contact_preferred_1` enum('No','Yes') NOT NULL DEFAULT 'No' COMMENT 'Is this the preferred contact?',
  `contact_name_2` varchar(64) DEFAULT NULL,
  `contact_title_2` varchar(32) DEFAULT NULL,
  `contact_tel_2` varchar(32) DEFAULT NULL,
  `contact_fax_2` varchar(32) DEFAULT NULL,
  `contact_email_2` varchar(255) DEFAULT NULL,
  `contact_preferred_2` enum('No','Yes') DEFAULT NULL,
  `contact_name_3` varchar(64) DEFAULT NULL,
  `contact_title_3` varchar(32) DEFAULT NULL,
  `contact_tel_3` varchar(32) DEFAULT NULL,
  `contact_fax_3` varchar(32) DEFAULT NULL,
  `contact_email_3` varchar(255) DEFAULT NULL,
  `contact_preferred_3` enum('Yes','No') DEFAULT NULL,
  `entry_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `entry_created_by` varchar(45) NOT NULL DEFAULT '',
  `entry_modified` datetime DEFAULT NULL,
  `entry_modified_by` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`supp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Suppliers of equipment';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_groups` (
  `gid` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Group ID',
  `gname` varchar(45) NOT NULL DEFAULT '' COMMENT 'Group name',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date/time when records changed',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User groups';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT 'Noname',
  `surname` varchar(50) DEFAULT NULL,
  `password` varchar(80) NOT NULL,
  `role` enum('User','Admin','SuperUser') DEFAULT 'User',
  `last_login` datetime DEFAULT CURRENT_TIMESTAMP,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','Admin','0','8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918','SuperUser','2016-01-12 11:35:04','2015-12-28 11:51:35','2016-07-27 08:00:15');
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

-- Dump completed on 2016-07-27 11:37:20

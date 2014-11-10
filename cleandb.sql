/*
SQLyog Ultimate v11.42 (64 bit)
MySQL - 5.1.73 : Database - bz_prod
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `ac_role` */

DROP TABLE IF EXISTS `ac_role`;

CREATE TABLE `ac_role` (
  `ac_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `ac_role_name` varchar(128) NOT NULL,
  `ac_role_properties` varchar(128) DEFAULT NULL,
  `ac_role_desc` varchar(128) DEFAULT NULL,
  `ac_role_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ac_role_id`,`ac_role_name`),
  UNIQUE KEY `role_id` (`ac_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `bulks` */

DROP TABLE IF EXISTS `bulks`;

CREATE TABLE `bulks` (
  `m_id` int(11) NOT NULL AUTO_INCREMENT,
  `m_desc_json` varchar(256) NOT NULL DEFAULT '{"mail_type":"N/A","mail_desc":"N/A","mail_country":"N/A"}',
  `m_file_path` varchar(128) NOT NULL,
  `m_price` double NOT NULL,
  `m_used_by` int(11) NOT NULL DEFAULT '0',
  `is_download` int(11) DEFAULT '0',
  `m_used_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

/*Table structure for table `cards` */

DROP TABLE IF EXISTS `cards`;

CREATE TABLE `cards` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `card_info` varchar(512) NOT NULL,
  `card_type` int(11) NOT NULL,
  `card_city` varchar(128) NOT NULL,
  `card_state` varchar(128) NOT NULL,
  `card_zipcode` varchar(128) NOT NULL,
  `card_dob` int(11) DEFAULT '0',
  `card_ssn` int(11) DEFAULT '0',
  `card_fullz` int(11) DEFAULT '0',
  `card_digital` varchar(128) NOT NULL,
  `card_exp_month` varchar(32) NOT NULL,
  `card_exp_year` varchar(32) NOT NULL,
  `card_cvv` varchar(32) NOT NULL,
  `card_country` varchar(128) NOT NULL,
  `card_price` double NOT NULL,
  `card_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `card_used_by` int(11) DEFAULT '0',
  `card_used_date` timestamp NULL DEFAULT NULL,
  `card_comments` varchar(512) DEFAULT NULL,
  `card_used_status` int(11) DEFAULT '0',
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1771 DEFAULT CHARSET=latin1;

/*Table structure for table `carts` */

DROP TABLE IF EXISTS `carts`;

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_session` varchar(120) DEFAULT NULL,
  `cart_item` varchar(512) CHARACTER SET utf8 NOT NULL,
  `cart_price` double NOT NULL,
  `cart_type` int(11) NOT NULL,
  `is_paid` int(11) NOT NULL DEFAULT '0',
  `cart_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8040 DEFAULT CHARSET=latin1;

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type_id` int(11) NOT NULL,
  `category_name` varchar(128) NOT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `category_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `configs` */

DROP TABLE IF EXISTS `configs`;

CREATE TABLE `configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(128) NOT NULL,
  `params` varchar(128) NOT NULL,
  `type` enum('Perfect','System') NOT NULL,
  `enable` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`config_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Table structure for table `cron_tasks` */

DROP TABLE IF EXISTS `cron_tasks`;

CREATE TABLE `cron_tasks` (
  `cron_task_id` int(11) NOT NULL AUTO_INCREMENT,
  `cron_task_name` varchar(128) DEFAULT NULL,
  `cron_task_descriptor` text,
  `cron_task_param` varchar(128) DEFAULT NULL,
  `cron_task_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` int(11) DEFAULT '1',
  PRIMARY KEY (`cron_task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `cron_tasks_processor` */

DROP TABLE IF EXISTS `cron_tasks_processor`;

CREATE TABLE `cron_tasks_processor` (
  `processor_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `processor_session` varchar(128) NOT NULL,
  `processor_type` int(11) NOT NULL,
  `processor_item_id` int(11) NOT NULL,
  `processor_item_price` double DEFAULT NULL,
  `processor_infor` varchar(512) NOT NULL,
  `processor_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`processor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1157 DEFAULT CHARSET=latin1;

/*Table structure for table `data_lookups` */

DROP TABLE IF EXISTS `data_lookups`;

CREATE TABLE `data_lookups` (
  `data_lookup_id` int(11) NOT NULL AUTO_INCREMENT,
  `bin_lookup` int(6) NOT NULL,
  `bank_lookup` varchar(250) NOT NULL,
  `type_lookup` varchar(250) NOT NULL,
  `level_lookup` varchar(50) NOT NULL,
  `country_lookup` varchar(100) NOT NULL,
  `countrycode2_lookup` varchar(2) NOT NULL,
  `countrycode3_lookup` varchar(3) NOT NULL,
  `countrynumber_lookup` varchar(3) NOT NULL,
  `aci_lookup` varchar(200) NOT NULL,
  `bankphone_lookup` varchar(100) NOT NULL,
  PRIMARY KEY (`data_lookup_id`),
  UNIQUE KEY `bin` (`bin_lookup`)
) ENGINE=MyISAM AUTO_INCREMENT=71683 DEFAULT CHARSET=utf8;

/*Table structure for table `image_mapping` */

DROP TABLE IF EXISTS `image_mapping`;

CREATE TABLE `image_mapping` (
  `image_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_map_name` varchar(128) NOT NULL,
  `image_map_uri` varchar(128) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `image_map_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Table structure for table `item_types` */

DROP TABLE IF EXISTS `item_types`;

CREATE TABLE `item_types` (
  `item_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type_name` varchar(128) NOT NULL,
  `item_type_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Table structure for table `logs` */

DROP TABLE IF EXISTS `logs`;

CREATE TABLE `logs` (
  `logs_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `logs_message` varchar(256) CHARACTER SET utf8 NOT NULL,
  `logs_level` int(11) DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `logs_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`logs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19412 DEFAULT CHARSET=latin1;

/*Table structure for table `news` */

DROP TABLE IF EXISTS `news`;

CREATE TABLE `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_title` varchar(256) DEFAULT NULL,
  `news_content` mediumtext,
  `news_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `payment_amount` double NOT NULL DEFAULT '0',
  `payment_batch` varchar(128) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_token` varchar(128) NOT NULL,
  `payment_account` varchar(128) NOT NULL,
  `payment_updated` double NOT NULL DEFAULT '0',
  `payment_commis` double NOT NULL DEFAULT '0',
  `payment_method_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `orderBatch` (`payment_batch`)
) ENGINE=MyISAM AUTO_INCREMENT=3362 DEFAULT CHARSET=latin1;

/*Table structure for table `paypals` */

DROP TABLE IF EXISTS `paypals`;

CREATE TABLE `paypals` (
  `paypal_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL DEFAULT '5',
  `paypal_used_by` int(11) NOT NULL DEFAULT '0',
  `paypal_info` varchar(512) NOT NULL,
  `paypal_email` varchar(128) NOT NULL,
  `paypal_type` varchar(128) NOT NULL,
  `paypal_country` varchar(128) NOT NULL,
  `paypal_balance` varchar(128) NOT NULL,
  `paypal_is_email` int(11) NOT NULL,
  `paypal_is_card` int(11) NOT NULL,
  `paypal_is_bank` int(11) NOT NULL,
  `paypal_price` double NOT NULL DEFAULT '0',
  `paypal_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `paypal_used_date` timestamp NULL DEFAULT NULL,
  `paypal_comments` varchar(512) DEFAULT NULL,
  `paypal_used_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`paypal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2281 DEFAULT CHARSET=latin1;

/*Table structure for table `ticket` */

DROP TABLE IF EXISTS `ticket`;

CREATE TABLE `ticket` (
  `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_subject` varchar(128) DEFAULT NULL,
  `ticket_status_id` int(11) NOT NULL DEFAULT '1',
  `ticket_priority` int(11) DEFAULT NULL,
  `ticket_item_info` text,
  `ticket_item_type` int(11) DEFAULT NULL,
  `ticket_item_id` int(11) DEFAULT NULL,
  `ticket_item_price` double NOT NULL,
  `user_id` int(11) NOT NULL,
  `ac_seller_id` int(11) DEFAULT '1',
  `date_submited` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ticket_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2127 DEFAULT CHARSET=latin1;

/*Table structure for table `ticket_comments` */

DROP TABLE IF EXISTS `ticket_comments`;

CREATE TABLE `ticket_comments` (
  `ticket_comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_comment` text NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ticket_comment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ticket_comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2879 DEFAULT CHARSET=latin1;

/*Table structure for table `ticket_status` */

DROP TABLE IF EXISTS `ticket_status`;

CREATE TABLE `ticket_status` (
  `ticket_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_status_name` varchar(128) NOT NULL,
  PRIMARY KEY (`ticket_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(128) NOT NULL,
  `user_password` varchar(512) NOT NULL,
  `user_email` varchar(256) NOT NULL,
  `ac_role_id` int(11) NOT NULL DEFAULT '2',
  `user_credits` double NOT NULL DEFAULT '0',
  `user_regdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_status` int(11) NOT NULL DEFAULT '0',
  `is_reset_password` varchar(256) DEFAULT NULL,
  `user_active_token` varchar(256) NOT NULL,
  `user_ref_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`user_name`),
  UNIQUE KEY `email` (`user_email`)
) ENGINE=MyISAM AUTO_INCREMENT=7740 DEFAULT CHARSET=latin1;

/*Table structure for table `users_login` */

DROP TABLE IF EXISTS `users_login`;

CREATE TABLE `users_login` (
  `user_login_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `login_broswer` varchar(128) DEFAULT NULL,
  `login_system` varchar(128) DEFAULT NULL,
  `login_ip` varchar(128) NOT NULL,
  `login_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login_token` varchar(128) NOT NULL,
  PRIMARY KEY (`user_login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

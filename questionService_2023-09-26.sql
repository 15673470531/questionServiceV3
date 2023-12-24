# ************************************************************
# Sequel Ace SQL dump
# 版本号： 20051
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# 主机: 82.156.139.209 (MySQL 8.1.0)
# 数据库: questionService
# 生成时间: 2023-09-25 16:37:23 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# 转储表 q_answer
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_answer`;

CREATE TABLE `q_answer` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `content` mediumtext,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# 转储表 q_bill
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_bill`;

CREATE TABLE `q_bill` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` int DEFAULT NULL,
  `token` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `money` int DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_bill` WRITE;
/*!40000 ALTER TABLE `q_bill` DISABLE KEYS */;

INSERT INTO `q_bill` (`id`, `type`, `token`, `user_id`, `money`, `remark`)
VALUES
	(1,1,500,1,0,'注册送token');

/*!40000 ALTER TABLE `q_bill` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_code
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_code`;

CREATE TABLE `q_code` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(50) DEFAULT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `msg` varchar(50) DEFAULT NULL,
  `deleted_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_code` WRITE;
/*!40000 ALTER TABLE `q_code` DISABLE KEYS */;

INSERT INTO `q_code` (`id`, `mobile`, `code`, `created_time`, `user_id`, `msg`, `deleted_time`)
VALUES
	(1,'0','335244','2023-09-25 16:35:44',1,'6',NULL),
	(2,'0','360239','2023-09-25 16:36:08',1,'1468','2023-09-25 16:36:17');

/*!40000 ALTER TABLE `q_code` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_help
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_help`;

CREATE TABLE `q_help` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `content` mediumtext,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# 转储表 q_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_question`;

CREATE TABLE `q_question` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title_spent` int DEFAULT NULL,
  `content_spent` int DEFAULT NULL,
  `spent_tokens` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `question` mediumtext,
  `created_time` datetime DEFAULT NULL,
  `created_uid` int DEFAULT NULL,
  `reply_content` mediumtext,
  `delete` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# 转储表 q_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_token`;

CREATE TABLE `q_token` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_token` WRITE;
/*!40000 ALTER TABLE `q_token` DISABLE KEYS */;

INSERT INTO `q_token` (`id`, `user_id`, `access_token`, `created_time`)
VALUES
	(1,1,'fe96aca522c699fa7542','2023-09-25 16:36:17');

/*!40000 ALTER TABLE `q_token` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_user`;

CREATE TABLE `q_user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `open_id` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `balance_token` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `q_user` WRITE;
/*!40000 ALTER TABLE `q_user` DISABLE KEYS */;

INSERT INTO `q_user` (`id`, `order_id`, `open_id`, `created_time`, `balance_token`)
VALUES
	(1,0,'oh0-H6NPz6h9OBeUo8J0NK8iNT0E','2023-09-25 16:35:44',500);

/*!40000 ALTER TABLE `q_user` ENABLE KEYS */;
UNLOCK TABLES;


# 转储表 q_wx_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `q_wx_token`;

CREATE TABLE `q_wx_token` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `access_token` varchar(255) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

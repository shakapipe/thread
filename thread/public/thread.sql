-- データベースの作成

--Autoインクリメントの制御
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- データベース: `thread`
--
CREATE DATABASE `thread` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `thread`;


/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50532
Source Host           : localhost:3306
Source Database       : thread

Target Server Type    : MYSQL
Target Server Version : 50532
File Encoding         : 65001

Date: 2015-08-05 15:33:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for thread
-- ----------------------------
DROP TABLE IF EXISTS `thread`;
CREATE TABLE `thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `reply_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `body` text,
  `reply_title` varchar(100) DEFAULT NULL,
  `reply_body` text,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE,
  KEY `reply_id` (`reply_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

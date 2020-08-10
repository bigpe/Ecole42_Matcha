/*
 Navicat Premium Data Transfer

 Source Server         : Camagru(Inet)
 Source Server Type    : MySQL
 Source Server Version : 100038
 Source Host           : 37.204.240.149
 Source Database       : Matcha

 Target Server Type    : MySQL
 Target Server Version : 100038
 File Encoding         : utf-8

 Date: 04/29/2020 17:59:40 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `TUTORIALS`
-- ----------------------------
DROP TABLE IF EXISTS `TUTORIALS`;
CREATE TABLE `TUTORIALS` (
  `tutorial_id` int(11) NOT NULL AUTO_INCREMENT,
  `tutorial_name` text,
  `tutorial_controller` text,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tutorial_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `TUTORIALS`
-- ----------------------------
BEGIN;
INSERT INTO `TUTORIALS` VALUES ('1', 'first_login', '/First_Login', '2020-03-16 12:59:31', '2020-04-04 00:56:00');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

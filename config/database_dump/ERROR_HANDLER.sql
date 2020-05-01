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

 Date: 04/29/2020 17:54:29 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `ERROR_HANDLER`
-- ----------------------------
DROP TABLE IF EXISTS `ERROR_HANDLER`;
CREATE TABLE `ERROR_HANDLER` (
  `error_id` int(11) NOT NULL AUTO_INCREMENT,
  `error_name` text,
  PRIMARY KEY (`error_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `ERROR_HANDLER`
-- ----------------------------
BEGIN;
INSERT INTO `ERROR_HANDLER` VALUES ('1', 'Message server busy, try later!'), ('2', 'Token is invalid!'), ('3', 'Email is invalid!'), ('4', 'Login too short!'), ('5', 'Password too short!'), ('6', 'Passwords doesn\'t match'), ('7', 'Email not validating!'), ('8', 'Login already exists!'), ('9', 'Email is already exists!'), ('10', 'Login or password incorrect!'), ('11', 'Request error!'), ('12', 'Password invalid!'), ('13', 'Oops! Try later!'), ('14', 'User not found!');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

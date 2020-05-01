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

 Date: 04/29/2020 17:59:50 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `USER_ACTIONS`
-- ----------------------------
DROP TABLE IF EXISTS `USER_ACTIONS`;
CREATE TABLE `USER_ACTIONS` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` text,
  `action_icon` text,
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `USER_ACTIONS`
-- ----------------------------
BEGIN;
INSERT INTO `USER_ACTIONS` VALUES ('1', 'Visit Profile', '<i class=\"far fa-eye\"></i>'), ('2', 'Set Like', '<i class=\"fas fa-heart\"></i>'), ('3', 'Unset Like', '<i class=\"fas fa-heart-broken\"></i>'), ('4', 'Open Self Profile', null), ('5', 'Open Another Profile', null), ('6', 'Change Email', '<i class=\"fas fa-at\"></i>'), ('7', 'Change Password', '<i class=\"fas fa-key\"></i>'), ('8', 'Change Full Name', '<i class=\"fas fa-mask\"></i>'), ('9', 'Block User', '<i class=\"fas fa-user-slash\"></i>'), ('11', 'Send Message', '<i class=\"far fa-envelope\"></i>'), ('12', 'Sing in', null), ('13', 'Sign_out', null), ('14', 'Find Another People', null), ('15', 'Get Matcha', null), ('16', 'Check History', ''), ('17', 'Search people', null), ('18', 'Join in chat', null), ('19', 'Read message', null);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

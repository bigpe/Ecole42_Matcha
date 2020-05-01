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

 Date: 04/29/2020 17:59:13 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `SEX_PREFERENCE`
-- ----------------------------
DROP TABLE IF EXISTS `SEX_PREFERENCE`;
CREATE TABLE `SEX_PREFERENCE` (
  `sex_preference_id` int(11) NOT NULL,
  `sex_preference_name` text,
  `sex_preference_icon` text,
  `sex_preference_color` text,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sex_preference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `SEX_PREFERENCE`
-- ----------------------------
BEGIN;
INSERT INTO `SEX_PREFERENCE` VALUES ('0', 'Bi', '<i class=\"fas fa-transgender\"></i>', 'firebrick', '2020-03-16 15:58:29', '2020-03-29 18:01:53'), ('2', 'Gay', '<i class=\"fas fa-mars-double\"></i>', 'aqua', '2020-03-16 15:58:29', '2020-03-29 18:01:53'), ('3', 'Hetero', '<i class=\"fas fa-venus-mars\"></i>', 'yellowgreen', '2020-03-16 15:58:29', '2020-03-29 18:01:53'), ('4', 'Lesbian', '<i class=\"fas fa-venus-double\"></i>', 'darkorchid', '2020-03-16 15:58:29', '2020-03-29 18:01:53');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

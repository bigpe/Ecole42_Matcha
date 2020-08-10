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

 Date: 04/29/2020 17:58:46 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `FAME_RATING`
-- ----------------------------
DROP TABLE IF EXISTS `FAME_RATING`;
CREATE TABLE `FAME_RATING` (
  `fame_rating_id` int(11) NOT NULL AUTO_INCREMENT,
  `fame_rating_start` int(11) DEFAULT NULL,
  `fame_rating_end` int(11) DEFAULT NULL,
  `fame_rating_name` text,
  `fame_rating_icon` text,
  `fame_rating_color` text,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fame_rating_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `FAME_RATING`
-- ----------------------------
BEGIN;
INSERT INTO `FAME_RATING` VALUES ('1', '0', '0', 'Very Low', '<i class=\"fas fa-battery-empty\"></i>', 'lightgrey', '2020-03-19 20:04:53', '2020-03-29 18:05:07'), ('2', '1', '9', 'Low', '<i class=\"fas fa-battery-quarter\"></i>', 'crimson', '2020-03-19 20:04:53', '2020-03-29 17:49:15'), ('3', '10', '19', 'Medium', '<i class=\"fas fa-battery-half\"></i>', 'darkorange', '2020-03-19 20:04:53', '2020-03-29 17:49:15'), ('4', '20', '29', 'High', '<i class=\"fas fa-battery-three-quarters\"></i>', '#5fe15f', '2020-03-19 20:04:53', '2020-03-29 17:49:15'), ('5', '30', '999', 'Famous', '<i class=\"fas fa-battery-full\"></i>', '#c640a4', '2020-03-19 20:18:23', '2020-03-29 17:49:15');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

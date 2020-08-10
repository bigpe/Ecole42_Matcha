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

 Date: 04/29/2020 17:59:23 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `TAGS`
-- ----------------------------
DROP TABLE IF EXISTS `TAGS`;
CREATE TABLE `TAGS` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) NOT NULL,
  `tag_icon` text,
  `tag_color` varchar(255) NOT NULL DEFAULT 'black',
  `tag_rate` int(11) DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `TAGS_tag_name_uindex` (`tag_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `TAGS`
-- ----------------------------
BEGIN;
INSERT INTO `TAGS` VALUES ('1', '#Vegan', '<i class=\"fas fa-seedling\"></i>', 'green', '5'), ('2', '#Sport', '<i class=\"fas fa-volleyball-ball\"></i>', 'orange', '1'), ('3', '#VideoGames', '<i class=\"fas fa-gamepad\"></i>', 'purple', '5'), ('4', '#Movie', '<i class=\"fas fa-film\"></i>', 'violet', '1'), ('5', '#Books', '<i class=\"fas fa-book\"></i>', 'brown', '1'), ('6', '#Pizza', '<i class=\"fas fa-pizza-slice\"></i>', 'red', '5'), ('7', '#Coocking', '<i class=\"fas fa-utensils\"></i>', 'gray', '1'), ('8', '#Alcohol', '<i class=\"fas fa-wine-glass-alt\"></i>', 'crimson', '2'), ('9', '#Painting', '<i class=\"fas fa-paint-brush\"></i>', 'blue', '1'), ('10', '#Fastfood', '<i class=\"fas fa-hamburger\"></i>', '#ff6000', '1'), ('11', '#Mountaineering', '<i class=\"fas fa-mountain\"></i>', 'salmon', '1'), ('12', '#Clubs', '<i class=\"fas fa-cocktail\"></i>', 'fuchsia', '1'), ('13', '#Guitar', '<i class=\"fas fa-guitar\"></i>', 'orangered', '1'), ('14', '#Tennis', '<i class=\"fas fa-table-tennis\"></i>', '#820000', '1'), ('15', '#IceCream', '<i class=\"fas fa-ice-cream\"></i>', 'mediumturquoise', '1'), ('16', '#Medicine', '<i class=\"fas fa-book-medical\"></i>', 'dodgerblue', '1');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

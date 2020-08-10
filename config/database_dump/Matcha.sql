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

 Date: 04/29/2020 17:52:16 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `CHATS`
-- ----------------------------
DROP TABLE IF EXISTS `CHATS`;
CREATE TABLE `CHATS` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_one` int(11) NOT NULL,
  `user_id_two` int(11) NOT NULL,
  PRIMARY KEY (`chat_id`),
  KEY `CHATS_USERS_user_id_fk` (`user_id_one`),
  KEY `CHATS_USERS_user_id_fk_2` (`user_id_two`),
  CONSTRAINT `CHATS_USERS_user_id_fk` FOREIGN KEY (`user_id_one`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CHATS_USERS_user_id_fk_2` FOREIGN KEY (`user_id_two`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `NOTIFICATIONS`
-- ----------------------------
DROP TABLE IF EXISTS `NOTIFICATIONS`;
CREATE TABLE `NOTIFICATIONS` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_to` int(11) DEFAULT NULL,
  `user_id_from` int(11) DEFAULT NULL,
  `action` int(11) DEFAULT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1118 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `PASSWORD_TEMP`
-- ----------------------------
DROP TABLE IF EXISTS `PASSWORD_TEMP`;
CREATE TABLE `PASSWORD_TEMP` (
  `password_temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `token_id` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`password_temp_id`),
  KEY `PASSWORD_TEMP_TOKENS_token_id_fk` (`token_id`),
  KEY `PASSWORD_TEMP_USERS_user_id_fk` (`user_id`),
  CONSTRAINT `PASSWORD_TEMP_TOKENS_token_id_fk` FOREIGN KEY (`token_id`) REFERENCES `TOKENS` (`token_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `PASSWORD_TEMP_USERS_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `TOKENS`
-- ----------------------------
DROP TABLE IF EXISTS `TOKENS`;
CREATE TABLE `TOKENS` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `token_type` int(11) DEFAULT NULL,
  `token` text,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USERS`
-- ----------------------------
DROP TABLE IF EXISTS `USERS`;
CREATE TABLE `USERS` (
                         `user_id` int(11) NOT NULL AUTO_INCREMENT,
                         `root` int(11) DEFAULT '0',
                         `login` varchar(255) CHARACTER SET latin1 NOT NULL,
                         `email` varchar(255) CHARACTER SET latin1 NOT NULL,
                         `password` varchar(255) CHARACTER SET latin1 NOT NULL,
                         `age` timestamp NULL DEFAULT NULL,
                         `full_name` varchar(255) DEFAULT NULL,
                         `sex` tinyint(4) DEFAULT '0',
                         `sex_preference` tinyint(4) DEFAULT '0',
                         `info` text,
                         `geo` text,
                         `geo_longitude` float DEFAULT NULL,
                         `geo_latitude` float DEFAULT NULL,
                         `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                         `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                         PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=898 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `USERS_MATCHED`
-- ----------------------------
DROP TABLE IF EXISTS `USERS_MATCHED`;
CREATE TABLE `USERS_MATCHED` (
  `users_matched_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_one` int(11) DEFAULT NULL,
  `user_id_two` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`users_matched_id`)
) ENGINE=InnoDB AUTO_INCREMENT=367 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USERS_SESSIONS`
-- ----------------------------
DROP TABLE IF EXISTS `USERS_SESSIONS`;
CREATE TABLE `USERS_SESSIONS` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  KEY `USERS_SESSIONS_USERS_user_id_fk` (`user_id`),
  CONSTRAINT `USERS_SESSIONS_USERS_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_BLACK_LIST`
-- ----------------------------
DROP TABLE IF EXISTS `USER_BLACK_LIST`;
CREATE TABLE `USER_BLACK_LIST` (
  `black_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_id_blocked` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`black_list_id`),
  UNIQUE KEY `USER_BLACK_LIST_user_id_user_id_blocked_uindex` (`user_id`,`user_id_blocked`),
  KEY `USER_BLACK_LIST_USERS_user_id_fk_2` (`user_id_blocked`),
  CONSTRAINT `USER_BLACK_LIST_USERS_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `USER_BLACK_LIST_USERS_user_id_fk_2` FOREIGN KEY (`user_id_blocked`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_FILTERS`
-- ----------------------------
DROP TABLE IF EXISTS `USER_FILTERS`;
CREATE TABLE `USER_FILTERS` (
  `user_filter_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `age_from` int(11) DEFAULT NULL,
  `age_to` int(11) DEFAULT NULL,
  `age_sort` int(11) DEFAULT '-1',
  `geo` text,
  `geo_sort` int(11) DEFAULT '-1',
  `geo_from` int(11) DEFAULT NULL,
  `geo_to` int(11) DEFAULT NULL,
  `tags` text,
  `fame_rating` int(11) NOT NULL DEFAULT '0',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_filter_id`),
  UNIQUE KEY `USER_FILTERS_user_id_uindex` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `USER_HISTORY`
-- ----------------------------
DROP TABLE IF EXISTS `USER_HISTORY`;
CREATE TABLE `USER_HISTORY` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `alfa_user_id` int(11) NOT NULL,
  `omega_user_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`),
  KEY `USER_HISTORY_USER_ACTIONS_action_id_fk` (`action_id`),
  CONSTRAINT `USER_HISTORY_USER_ACTIONS_action_id_fk` FOREIGN KEY (`action_id`) REFERENCES `USER_ACTIONS` (`action_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8166 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_LAST_ACTION`
-- ----------------------------
DROP TABLE IF EXISTS `USER_LAST_ACTION`;
CREATE TABLE `USER_LAST_ACTION` (
  `last_action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`last_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_MAIN_PHOTO`
-- ----------------------------
DROP TABLE IF EXISTS `USER_MAIN_PHOTO`;
CREATE TABLE `USER_MAIN_PHOTO` (
  `user_main_photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_main_photo_id`),
  UNIQUE KEY `USER_MAIN_PHOTO_user_id_user_main_photo_id_uindex` (`user_id`,`user_main_photo_id`),
  UNIQUE KEY `USER_MAIN_PHOTO_USERS_user_id_fk` (`user_id`),
  KEY `USER_MAIN_PHOTO_USER_PHOTO_photo_id_fk` (`photo_id`),
  CONSTRAINT `USER_MAIN_PHOTO_USERS_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `USER_MAIN_PHOTO_USER_PHOTO_photo_id_fk` FOREIGN KEY (`photo_id`) REFERENCES `USER_PHOTO` (`photo_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_MESSAGE`
-- ----------------------------
DROP TABLE IF EXISTS `USER_MESSAGE`;
CREATE TABLE `USER_MESSAGE` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL,
  `user_id_from` int(11) NOT NULL,
  `user_id_to` int(11) NOT NULL,
  `status_message` tinyint(4) DEFAULT '0',
  `text_message` text CHARACTER SET utf8 NOT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_message`),
  KEY `USER_MESSAGE_USERS_user_id_fk` (`user_id_to`),
  KEY `USER_MESSAGE_USERS_user_id_fk_2` (`user_id_from`),
  KEY `USER_MESSAGE_CHATS_chat_id_fk` (`chat_id`),
  CONSTRAINT `USER_MESSAGE_CHATS_chat_id_fk` FOREIGN KEY (`chat_id`) REFERENCES `CHATS` (`chat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `USER_MESSAGE_USERS_user_id_fk` FOREIGN KEY (`user_id_to`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `USER_MESSAGE_USERS_user_id_fk_2` FOREIGN KEY (`user_id_from`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=640 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_PHOTO`
-- ----------------------------
DROP TABLE IF EXISTS `USER_PHOTO`;
CREATE TABLE `USER_PHOTO` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `photo_token` text,
  `photo_src` text,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`photo_id`),
  KEY `USER_PHOTO_USERS_user_id_fk` (`user_id`),
  CONSTRAINT `USER_PHOTO_USERS_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=430 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_REPORT`
-- ----------------------------
DROP TABLE IF EXISTS `USER_REPORT`;
CREATE TABLE `USER_REPORT` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `login_to` varchar(255) DEFAULT NULL,
  `login_from` varchar(255) DEFAULT NULL,
  `creation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_TAGS`
-- ----------------------------
DROP TABLE IF EXISTS `USER_TAGS`;
CREATE TABLE `USER_TAGS` (
  `user_tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`tag_id`),
  UNIQUE KEY `USER_TAGS_user_tag_id_user_id_uidex` (`user_tag_id`,`user_id`),
  KEY `USERS_TAGS_TAGS_tag_id_fk` (`tag_id`),
  CONSTRAINT `USERS_TAGS_TAGS_tag_id_fk` FOREIGN KEY (`tag_id`) REFERENCES `TAGS` (`tag_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `USERS_TAGS_USERS_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=468 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_TEMP`
-- ----------------------------
DROP TABLE IF EXISTS `USER_TEMP`;
CREATE TABLE `USER_TEMP` (
  `user_temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `token_id` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_temp_id`),
  KEY `USER_TEMP_TOKENS_token_id_fk` (`token_id`),
  CONSTRAINT `USER_TEMP_TOKENS_token_id_fk` FOREIGN KEY (`token_id`) REFERENCES `TOKENS` (`token_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `USER_TUTORIAL`
-- ----------------------------
DROP TABLE IF EXISTS `USER_TUTORIAL`;
CREATE TABLE `USER_TUTORIAL` (
  `user_tutorial_id` int(11) NOT NULL AUTO_INCREMENT,
  `tutorial_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_tutorial_id`),
  KEY `USER_TUTORIAL_TUTORIALS_tutorial_id_fk` (`tutorial_id`),
  KEY `USER_TUTORIAL_USERS_user_id_fk` (`user_id`),
  CONSTRAINT `USER_TUTORIAL_TUTORIALS_tutorial_id_fk` FOREIGN KEY (`tutorial_id`) REFERENCES `TUTORIALS` (`tutorial_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `USER_TUTORIAL_USERS_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `USERS` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;

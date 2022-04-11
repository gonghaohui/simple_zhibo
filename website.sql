/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : simple_zhibo

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 11/04/2022 13:19:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for think_website
-- ----------------------------
DROP TABLE IF EXISTS `think_website`;
CREATE TABLE `think_website`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of think_website
-- ----------------------------
INSERT INTO `think_website` VALUES (1, 'http://zbc.04xr.cn');

SET FOREIGN_KEY_CHECKS = 1;

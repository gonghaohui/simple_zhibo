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

 Date: 11/04/2022 00:56:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for think_admin
-- ----------------------------
DROP TABLE IF EXISTS `think_admin`;
CREATE TABLE `think_admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id',
  `username` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT '管理员名',
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '密码',
  `portrait` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `loginnum` int(11) NULL DEFAULT 0 COMMENT '登陆次数',
  `last_login_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NULL DEFAULT NULL COMMENT '最后登录时间',
  `real_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '真实姓名',
  `phone` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `status` int(1) NULL DEFAULT NULL COMMENT '状态 1：开启  2:禁用',
  `groupid` int(11) NULL DEFAULT 1 COMMENT '用户角色id',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 64 CHARACTER SET = utf8 COLLATE = utf8_bin COMMENT = '后台管理员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_admin
-- ----------------------------
INSERT INTO `think_admin` VALUES (1, 'admin', '17c8eeaf652fdb28e49df45e6f0842d8', '/uploads/face/20181116/63a0f7d99db522e14873f9b0b4e7ff85.png', 1850, '127.0.0.1', 1649601057, 'PC', '13057633120', 1, 1, 1523792661, 1636335525);
INSERT INTO `think_admin` VALUES (63, 'kevin', '4dc94196fd5bd03a167758456ba47fb9', '/uploads/face/20181116/8470a4efd862cbad73754f9ca7a5596f.png', 285, '127.0.0.1', 1542159154, 'SDSA', '13057633125', 1, 2, 1523792666, 1542335768);

-- ----------------------------
-- Table structure for think_area
-- ----------------------------
DROP TABLE IF EXISTS `think_area`;
CREATE TABLE `think_area`  (
  `district_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '地区id',
  `district` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '地区名称',
  `pid` int(11) NULL DEFAULT NULL COMMENT '父id',
  `level` tinyint(1) NULL DEFAULT NULL COMMENT '1:省  2:市  3:区',
  PRIMARY KEY (`district_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4480 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '地区表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_area
-- ----------------------------

-- ----------------------------
-- Table structure for think_article
-- ----------------------------
DROP TABLE IF EXISTS `think_article`;
CREATE TABLE `think_article`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章逻辑ID',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章标题',
  `cate_id` int(11) NOT NULL DEFAULT 1 COMMENT '文章类别',
  `photo` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '文章图片',
  `remark` varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '文章描述',
  `keyword` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '文章关键字',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章内容',
  `views` int(11) NOT NULL DEFAULT 1 COMMENT '浏览量',
  `type` int(1) NOT NULL DEFAULT 1 COMMENT '文章类型',
  `is_tui` int(1) NULL DEFAULT 0 COMMENT '是否推荐',
  `from` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '来源',
  `writer` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '作者',
  `ip` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态 1：开启   2：关闭',
  `music` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '音频',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `a_title`(`title`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 120 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_article
-- ----------------------------

-- ----------------------------
-- Table structure for think_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `think_article_cate`;
CREATE TABLE `think_article_cate`  (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分类名称',
  `orderby` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '100' COMMENT '排序',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态  1：开启  2：禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文章分类表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_article_cate
-- ----------------------------

-- ----------------------------
-- Table structure for think_article_category
-- ----------------------------
DROP TABLE IF EXISTS `think_article_category`;
CREATE TABLE `think_article_category`  (
  `category_id` int(5) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `category_name_cn` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '中文',
  `category_name_jp` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '日文',
  `category_url` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '主要用来做url优化',
  `pid` int(5) NOT NULL DEFAULT 0 COMMENT '父分类ID,0为顶级目录',
  `show_navigation` tinyint(1) NULL DEFAULT 0 COMMENT '是否在导航栏显示,只有顶级分类这个字段才生效',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否显示',
  `sort` int(4) NULL DEFAULT 0 COMMENT '排序，数据越小排在越前',
  `keywords_jp` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分类关键词jp',
  `description_jp` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分类描述jp',
  `thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '缩略图',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`category_id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 79 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_article_category
-- ----------------------------

-- ----------------------------
-- Table structure for think_article_tags
-- ----------------------------
DROP TABLE IF EXISTS `think_article_tags`;
CREATE TABLE `think_article_tags`  (
  `tag_id` int(6) NOT NULL AUTO_INCREMENT,
  `tag_name_jp` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `count` int(8) NULL DEFAULT 0 COMMENT '统计数量',
  PRIMARY KEY (`tag_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 815 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_article_tags
-- ----------------------------

-- ----------------------------
-- Table structure for think_article_tags_relative
-- ----------------------------
DROP TABLE IF EXISTS `think_article_tags_relative`;
CREATE TABLE `think_article_tags_relative`  (
  `r_id` int(8) NOT NULL AUTO_INCREMENT,
  `article_id` int(8) NOT NULL,
  `tag_id` int(6) NOT NULL,
  PRIMARY KEY (`r_id`) USING BTREE,
  INDEX `article_id`(`article_id`) USING BTREE,
  INDEX `tag_id`(`tag_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1408 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of think_article_tags_relative
-- ----------------------------

-- ----------------------------
-- Table structure for think_articles
-- ----------------------------
DROP TABLE IF EXISTS `think_articles`;
CREATE TABLE `think_articles`  (
  `article_id` int(8) NOT NULL AUTO_INCREMENT,
  `first_category` int(3) NULL DEFAULT 0,
  `second_category` int(4) NULL DEFAULT 0,
  `third_category` int(4) NULL DEFAULT 0,
  `title` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `author` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `content` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `keywords` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0不显示 1显示',
  `views` int(8) NULL DEFAULT 0 COMMENT '浏览数',
  `caiji_id` int(8) NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL COMMENT '发布时间',
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`article_id`) USING BTREE,
  INDEX `first_category`(`first_category`) USING BTREE,
  INDEX `second_category`(`second_category`) USING BTREE,
  INDEX `third_category`(`third_category`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 548 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_articles
-- ----------------------------

-- ----------------------------
-- Table structure for think_articles_sort
-- ----------------------------
DROP TABLE IF EXISTS `think_articles_sort`;
CREATE TABLE `think_articles_sort`  (
  `sort_id` int(8) NOT NULL AUTO_INCREMENT,
  `sort_type` tinyint(1) NOT NULL COMMENT '0:全站 1:一级分类 2:二级分类 3:三级分类 4:标签',
  `category` int(3) NOT NULL,
  `article_id` int(8) NOT NULL,
  PRIMARY KEY (`sort_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4297 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = FIXED;

-- ----------------------------
-- Records of think_articles_sort
-- ----------------------------

-- ----------------------------
-- Table structure for think_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_group`;
CREATE TABLE `think_auth_group`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `title` char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `status` tinyint(1) NOT NULL COMMENT '角色状态 1：开启   2：禁用',
  `rules` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色权限   SUPERAUTH：超级权限',
  `describe` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '角色描述',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '生成时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户组数据表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_auth_group
-- ----------------------------
INSERT INTO `think_auth_group` VALUES (1, '超级管理员', 1, 'SUPERAUTH', '至高无上的权利', 1446535750, 1541729583);
INSERT INTO `think_auth_group` VALUES (2, '内容管理员', 1, '1,2,10,3,30,31,32,103,34,4,35,36,37,104,39,126,61,62,63,64,105,66,85,91,5,6,27,70,71,75,77,24,25,26,44,45,46,109,47,140,141,48,49,54,13,14,117,123,118,119,120,121,122,124,125,147', '负责layui后台内容管理', 1446535751, 1542010118);
INSERT INTO `think_auth_group` VALUES (3, '系统维护员', 1, '1,2,9,10,11,12,3,30,31,32,33,34,4,35,36,37,38,39,5,6,7,8,27,28,29,13,14,22,24,25,40,41,42,43,26,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,70,71,72,73,74,80,75,76,77,78,79', '负责layui后台系统维护', 1446535752, 1540970649);
INSERT INTO `think_auth_group` VALUES (4, '系统测试员', 1, '1,2,9,10,11,102,12,127,128,3,30,31,32,33,34,4,35,36,37,38,39,61,62,63,64,65,66,5,6,7,8,27,28,29,70,71,72,73,74,80,75,76,77,78,79,24,25,40,41,42,43,26,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,13,14,22', '负责layui后台系统测试', 1446535753, 1541729577);

-- ----------------------------
-- Table structure for think_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_group_access`;
CREATE TABLE `think_auth_group_access`  (
  `uid` mediumint(8) UNSIGNED NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) UNSIGNED NOT NULL COMMENT '角色权限id',
  UNIQUE INDEX `uid_group_id`(`uid`, `group_id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户-用户组关系表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_auth_group_access
-- ----------------------------
INSERT INTO `think_auth_group_access` VALUES (1, 1);
INSERT INTO `think_auth_group_access` VALUES (63, 2);

-- ----------------------------
-- Table structure for think_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_rule`;
CREATE TABLE `think_auth_rule`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '模块/控制器/方法',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '权限规则名称',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1：菜单  2：按钮',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态  1：开启  2：禁用',
  `css` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图标样式',
  `condition` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父栏目ID',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '修改',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 185 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '权限规则表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_auth_rule
-- ----------------------------
INSERT INTO `think_auth_rule` VALUES (1, '#', '系统管理', 1, 1, 'fa fa-cog', '', 0, 1, 1446535750, 1541121645);
INSERT INTO `think_auth_rule` VALUES (2, 'admin/user/index', '管理员管理', 1, 1, 'fa fa-ban', '', 1, 10, 1446535750, 1540544544);
INSERT INTO `think_auth_rule` VALUES (3, 'admin/role/index', '角色管理', 1, 1, 'fa fa-ban', '', 1, 20, 1446535750, 1540434868);
INSERT INTO `think_auth_rule` VALUES (4, 'admin/menu/index', '菜单管理', 1, 1, 'fa fa-ban', '', 1, 30, 1446535750, 1540434958);
INSERT INTO `think_auth_rule` VALUES (5, '#', '数据库管理', 1, 1, 'fa fa-database', '', 0, 2, 1446535750, 1477312169);
INSERT INTO `think_auth_rule` VALUES (6, 'admin/data/index', '数据库备份', 1, 1, 'fa fa-ban', '', 5, 10, 1446535750, 1540435124);
INSERT INTO `think_auth_rule` VALUES (7, 'admin/data/optimize', '优化表', 1, 1, 'fa fa-ban', '', 6, 50, 1477312169, 1540435130);
INSERT INTO `think_auth_rule` VALUES (8, 'admin/data/repair', '修复表', 1, 1, 'fa fa-ban', '', 6, 50, 1477312169, 1540435138);
INSERT INTO `think_auth_rule` VALUES (9, 'admin/user/useradd', '添加管理员', 1, 1, 'fa fa-ban', '', 2, 50, 1477312169, 1540434790);
INSERT INTO `think_auth_rule` VALUES (10, 'admin/user/useredit', '编辑管理员', 1, 1, 'fa fa-ban', '', 2, 50, 1477312169, 1540434808);
INSERT INTO `think_auth_rule` VALUES (11, 'admin/user/userdel', '删除管理员', 1, 1, 'fa fa-ban', '', 2, 50, 1477312169, 1540434818);
INSERT INTO `think_auth_rule` VALUES (12, 'admin/user/user_state', '管理员状态', 1, 1, 'fa fa-ban', '', 2, 50, 1477312169, 1540434834);
INSERT INTO `think_auth_rule` VALUES (13, '#', '日志管理', 1, 1, 'fa fa-tasks', '', 0, 7, 1477312169, 1477312169);
INSERT INTO `think_auth_rule` VALUES (14, 'admin/log/operate_log', '行为日志', 1, 1, 'fa fa-ban', '', 13, 10, 1477312169, 1540435216);
INSERT INTO `think_auth_rule` VALUES (22, 'admin/log/del_log', '删除日志', 1, 1, 'fa fa-ban', '', 14, 50, 1477312169, 1540435224);
INSERT INTO `think_auth_rule` VALUES (24, '#', '文章管理', 1, 2, 'fa fa-paste', '', 0, 4, 1477312169, 1477312169);
INSERT INTO `think_auth_rule` VALUES (26, 'admin/article/index', '文章列表_old', 1, 2, '', '', 24, 20, 1477312333, 1477312333);
INSERT INTO `think_auth_rule` VALUES (27, 'admin/data/import', '数据库还原', 1, 1, 'fa fa-ban', '', 5, 20, 1477639870, 1540435152);
INSERT INTO `think_auth_rule` VALUES (28, 'admin/data/revert', '还原备份', 1, 1, 'fa fa-ban', '', 27, 50, 1477639972, 1540435166);
INSERT INTO `think_auth_rule` VALUES (29, 'admin/data/deldata', '删除备份', 1, 1, 'fa fa-ban', '', 27, 50, 1477640011, 1540435179);
INSERT INTO `think_auth_rule` VALUES (30, 'admin/role/roleAdd', '添加角色', 1, 1, 'fa fa-ban', '', 3, 50, 1477640011, 1540434875);
INSERT INTO `think_auth_rule` VALUES (31, 'admin/role/roleEdit', '编辑角色', 1, 1, 'fa fa-ban', '', 3, 50, 1477640011, 1540434889);
INSERT INTO `think_auth_rule` VALUES (32, 'admin/role/roleDel', '删除角色', 1, 1, 'fa fa-ban', '', 3, 50, 1477640011, 1540434901);
INSERT INTO `think_auth_rule` VALUES (33, 'admin/role/role_state', '角色状态', 1, 1, 'fa fa-ban', '', 3, 50, 1477640011, 1540434918);
INSERT INTO `think_auth_rule` VALUES (34, 'admin/role/giveAccess', '权限分配', 1, 1, 'fa fa-ban', '', 3, 50, 1477640011, 1540434950);
INSERT INTO `think_auth_rule` VALUES (35, 'admin/menu/add_rule', '添加菜单', 1, 1, 'fa fa-ban', '', 4, 50, 1477640011, 1540434966);
INSERT INTO `think_auth_rule` VALUES (36, 'admin/menu/edit_rule', '编辑菜单', 1, 1, 'fa fa-ban', '', 4, 50, 1477640011, 1540434982);
INSERT INTO `think_auth_rule` VALUES (37, 'admin/menu/del_rule', '删除菜单', 1, 1, 'fa fa-ban', '', 4, 50, 1477640011, 1540434991);
INSERT INTO `think_auth_rule` VALUES (38, 'admin/menu/rule_state', '菜单状态', 1, 2, 'fa fa-ban', '', 4, 50, 1477640011, 1540435007);
INSERT INTO `think_auth_rule` VALUES (39, 'admin/menu/ruleorder', '菜单排序', 1, 1, 'fa fa-ban', '', 4, 50, 1477640011, 1540435014);
INSERT INTO `think_auth_rule` VALUES (44, 'admin/article/add_article', '添加文章', 1, 2, 'fa fa-ban', '', 26, 50, 1477640011, 1627722226);
INSERT INTO `think_auth_rule` VALUES (45, 'admin/article/edit_article', '编辑文章', 1, 2, '', '', 26, 50, 1477640011, 1477640011);
INSERT INTO `think_auth_rule` VALUES (46, 'admin/article/del_article', '删除文章', 1, 2, '', '', 26, 50, 1477640011, 1477640011);
INSERT INTO `think_auth_rule` VALUES (47, 'admin/article/article_state', '文章状态', 1, 2, '', '', 26, 50, 1477640011, 1477640011);
INSERT INTO `think_auth_rule` VALUES (61, 'admin/config/index', '配置管理', 1, 1, 'fa fa-ban', '', 1, 40, 1479908607, 1540435030);
INSERT INTO `think_auth_rule` VALUES (62, 'admin/config/add_config', '添加配置', 1, 1, 'fa fa-ban', '', 61, 50, 1479908607, 1540435036);
INSERT INTO `think_auth_rule` VALUES (63, 'admin/config/edit_config', '编辑配置', 1, 1, 'fa fa-ban', '', 61, 50, 1479908607, 1540435042);
INSERT INTO `think_auth_rule` VALUES (64, 'admin/config/del_config', '删除配置', 1, 1, 'fa fa-ban', '', 61, 50, 1479908607, 1540435049);
INSERT INTO `think_auth_rule` VALUES (65, 'admin/config/status_config', '配置状态', 1, 1, 'fa fa-ban', '', 61, 50, 1479908607, 1540435066);
INSERT INTO `think_auth_rule` VALUES (66, 'admin/config/group', '网站配置', 1, 2, 'fa fa-ban', '', 1, 50, 1480316438, 1540435096);
INSERT INTO `think_auth_rule` VALUES (85, 'admin/index/clear', '清除缓存', 1, 1, 'fa fa-ban', '', 66, 50, 1522485859, 1540435103);
INSERT INTO `think_auth_rule` VALUES (91, 'admin/config/save', '保存配置', 1, 1, 'fa fa-ban', '', 66, 50, 1522824567, 1540435110);
INSERT INTO `think_auth_rule` VALUES (92, 'admin/data/export', '备份表', 1, 1, 'fa fa-ban', '', 6, 50, 1523161011, 1540435145);
INSERT INTO `think_auth_rule` VALUES (102, 'admin/user/batchdeluser', '批量删除', 1, 1, 'fa fa-ban', '', 11, 50, 1524645295, 1540434827);
INSERT INTO `think_auth_rule` VALUES (103, 'admin/role/batchdelrole', '批量删除', 1, 1, 'fa fa-ban', '', 32, 50, 1524648181, 1540434911);
INSERT INTO `think_auth_rule` VALUES (104, 'admin/menu/batchdelmenu', '批量删除', 1, 1, 'fa fa-ban', '', 37, 50, 1524653771, 1540434997);
INSERT INTO `think_auth_rule` VALUES (105, 'admin/config/batchdelconfig', '批量删除', 1, 1, 'fa fa-ban', '', 64, 50, 1524653826, 1540435059);
INSERT INTO `think_auth_rule` VALUES (109, 'admin/article/batchdelarticle', '批量删除', 1, 2, '', '', 46, 50, 1524654090, 1530680741);
INSERT INTO `think_auth_rule` VALUES (112, 'admin/log/batchdellog', '批量删除', 1, 1, 'fa fa-ban', '', 14, 50, 1524654233, 1540435231);
INSERT INTO `think_auth_rule` VALUES (116, 'admin/data/batchdeldata', '批量删除', 1, 1, 'fa fa-ban', '', 27, 50, 1524805218, 1540435185);
INSERT INTO `think_auth_rule` VALUES (124, 'admin/index/webuploader', '多图上传', 1, 2, 'fa fa-cloud-upload', '', 0, 9, 1524886803, 1542267144);
INSERT INTO `think_auth_rule` VALUES (125, 'admin/upload/showimg', '多图修改', 1, 2, 'fa fa-exchange', '', 0, 10, 1526277389, 1542267155);
INSERT INTO `think_auth_rule` VALUES (126, 'admin/menu/editfield', '快捷编辑', 1, 1, 'fa fa-ban', '', 4, 50, 1529631518, 1540519615);
INSERT INTO `think_auth_rule` VALUES (127, 'admin/user/forbiddenadmin', '批量禁用', 1, 1, 'fa fa-ban', '', 12, 50, 1530238799, 1540434840);
INSERT INTO `think_auth_rule` VALUES (128, 'admin/user/usingadmin', '批量启用', 1, 1, 'fa fa-ban', '', 12, 50, 1530238799, 1540434847);
INSERT INTO `think_auth_rule` VALUES (130, 'admin/role/forbiddenrole', '批量禁用', 1, 1, 'fa fa-ban', '', 33, 50, 1530248275, 1540434928);
INSERT INTO `think_auth_rule` VALUES (131, 'admin/role/usingrole', '批量启用', 1, 1, 'fa fa-ban', '', 33, 50, 1530248275, 1540434940);
INSERT INTO `think_auth_rule` VALUES (132, 'admin/config/forbiddenconfig', '批量禁用', 1, 1, 'fa fa-ban', '', 65, 50, 1530262327, 1540435073);
INSERT INTO `think_auth_rule` VALUES (133, 'admin/config/usingconfig', '批量启用', 1, 1, 'fa fa-ban', '', 65, 50, 1530262327, 1540435089);
INSERT INTO `think_auth_rule` VALUES (140, 'admin/article/forbiddenarticle', '批量禁用', 1, 2, '#', '', 47, 50, 1530681605, 1530681605);
INSERT INTO `think_auth_rule` VALUES (141, 'admin/article/usingarticle', '批量启用', 1, 2, '#', '', 47, 50, 1530681605, 1530681605);
INSERT INTO `think_auth_rule` VALUES (146, 'admin/user/exceladmin', '导出excel', 1, 1, 'fa fa-ban', '', 2, 50, 1531280281, 1540434858);
INSERT INTO `think_auth_rule` VALUES (169, 'admin/article/category_list', '文章分类', 1, 2, 'fa fa-ban', '', 24, 5, 1627722033, 1627722033);
INSERT INTO `think_auth_rule` VALUES (170, 'admin/caiji/data_list', '采集列表管理', 1, 2, 'fa fa-ban', '', 24, 6, 1628415403, 1628415403);
INSERT INTO `think_auth_rule` VALUES (171, 'admin/articles/data_list', '文章列表', 1, 2, 'fa fa-ban', '', 24, 3, 1629017240, 1629017290);
INSERT INTO `think_auth_rule` VALUES (172, 'admin/tags/data_list', '标签管理', 1, 2, 'fa fa-ban', '', 24, 7, 1629185488, 1629185725);
INSERT INTO `think_auth_rule` VALUES (173, '#', '外链管理', 1, 1, 'fa fa-chain', '', 0, 6, 1629343028, 1629343177);
INSERT INTO `think_auth_rule` VALUES (174, 'admin/link/data_list', '友情链接', 1, 1, 'fa fa-ban', '', 173, 10, 1629343150, 1629343150);
INSERT INTO `think_auth_rule` VALUES (175, 'admin/comment/data_list', '评论管理', 1, 2, 'fa fa-ban', '', 24, 8, 1629434893, 1629434893);
INSERT INTO `think_auth_rule` VALUES (176, 'admin/articles/sort', '前端排序生成', 1, 2, 'fa fa-ban', '', 24, 9, 1631764400, 1631764400);
INSERT INTO `think_auth_rule` VALUES (177, 'admin/articles/create_sitemap', '网站地图生成', 1, 2, 'fa fa-ban', '', 24, 10, 1634002854, 1634002854);
INSERT INTO `think_auth_rule` VALUES (178, 'admin/caiji/data_ready_list', '准备发布列表', 1, 2, 'fa fa-ban', '', 24, 6, 1635745399, 1635745399);
INSERT INTO `think_auth_rule` VALUES (179, 'admin/seo/search_engine', '推送搜索引擎', 1, 2, 'fa fa-ban', '', 24, 11, 1637721175, 1637721175);
INSERT INTO `think_auth_rule` VALUES (180, '#', '会员管理', 1, 2, 'fa fa-user-circle', '', 0, 3, 1649305473, 1649305473);
INSERT INTO `think_auth_rule` VALUES (181, 'admin/users/index', '会员列表', 1, 2, 'fa fa-ban', '', 180, 1, 1649305725, 1649305725);
INSERT INTO `think_auth_rule` VALUES (182, '#', '直播管理', 1, 1, 'fa fa-podcast', '', 0, 3, 1649307347, 1649307347);
INSERT INTO `think_auth_rule` VALUES (183, 'admin/live/index', '直播间列表', 1, 1, 'fa fa-ban', '', 182, 1, 1649307423, 1649307423);
INSERT INTO `think_auth_rule` VALUES (184, 'admin/live/forbidden', '聊天禁词', 1, 2, 'fa fa-ban', '', 182, 5, 1649308339, 1649308339);

-- ----------------------------
-- Table structure for think_caiji_datas
-- ----------------------------
DROP TABLE IF EXISTS `think_caiji_datas`;
CREATE TABLE `think_caiji_datas`  (
  `caiji_id` int(8) NOT NULL AUTO_INCREMENT,
  `web_type` tinyint(2) NOT NULL COMMENT '采集的网站对象',
  `web_url` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `first_category` int(3) NULL DEFAULT 0,
  `second_category` int(4) NULL DEFAULT 0,
  `third_category` int(4) NULL DEFAULT 0,
  `title_cn` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title_jp` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `author_cn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发布者',
  `author_jp` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `intro_cn` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '简介',
  `intro_jp` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `content_cn` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_jp` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `tags_cn` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '多个标签用，隔开',
  `tags_jp` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '多个标签用，隔开',
  `caiji_images` tinyint(1) NULL DEFAULT 0 COMMENT '0未采集 1已采集或者没有图片需要下载，只有为1的情况下才给发布',
  `publish` tinyint(1) NULL DEFAULT 0 COMMENT '0未发布 1已发布 2删除 只有caiji_images为1时和各个jp字段不为空时才给发布,3准备发布',
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`caiji_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 12937 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_caiji_datas
-- ----------------------------

-- ----------------------------
-- Table structure for think_comment
-- ----------------------------
DROP TABLE IF EXISTS `think_comment`;
CREATE TABLE `think_comment`  (
  `comment_id` int(8) NOT NULL AUTO_INCREMENT,
  `article_id` int(8) NOT NULL,
  `pid` int(8) NULL DEFAULT 0 COMMENT '父级评论ID:只支持三级',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '0:不显示 1:显示',
  `down_comment` tinyint(1) NULL DEFAULT 0 COMMENT '0:没有回复 1:有回复 用这个来判断这个评论有没有回复',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '发布时间',
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`comment_id`) USING BTREE,
  INDEX `article_id_and_pid`(`article_id`, `pid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_comment
-- ----------------------------

-- ----------------------------
-- Table structure for think_config
-- ----------------------------
DROP TABLE IF EXISTS `think_config`;
CREATE TABLE `think_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置类型',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置标题',
  `group` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '配置分组',
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(4) NOT NULL COMMENT '状态   1：开启   2：禁用',
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '配置值',
  `sort` smallint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_name`(`name`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `group`(`group`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 50 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '配置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_config
-- ----------------------------
INSERT INTO `think_config` VALUES (1, 'web_site_title', 1, '网站标题', 1, '', '网站标题前台显示标题', 1378898976, 1480575456, 1, '后台管理', 0);
INSERT INTO `think_config` VALUES (2, 'web_site_description', 2, '网站描述', 1, '', '网站搜索引擎描述', 1378898976, 1379235841, 1, '后台管理', 1);
INSERT INTO `think_config` VALUES (3, 'web_site_keyword', 2, '网站关键字', 1, '', '网站搜索引擎关键字', 1378898976, 1381390100, 1, 'ThinkPHP,layuiAdmin', 8);
INSERT INTO `think_config` VALUES (4, 'web_site_close', 4, '站点状态', 1, '0:关闭,1:开启', '站点关闭后其他管理员不能访问，超级管理员可以正常访问', 1378898976, 1529630265, 1, '1', 0);
INSERT INTO `think_config` VALUES (9, 'config_type_list', 3, '配置类型列表', 4, '', '主要用于数据解析和页面表单的生成', 1378898976, 1379235348, 1, '0:数字\n1:字符\n2:文本\n3:数组\n4:枚举', 2);
INSERT INTO `think_config` VALUES (10, 'web_site_icp', 1, '网站备案号', 1, '', '设置在网站底部显示的备案号，如“ 陇ICP备15002349号-1', 1378900335, 1480643159, 1, ' 京ICP备15002349号', 0);
INSERT INTO `think_config` VALUES (20, 'config_group_list', 3, '配置分组', 4, '', '配置分组', 1379228036, 1384418383, 1, '1:基本\n2:内容\n3:用户\n4:系统', 4);
INSERT INTO `think_config` VALUES (25, 'pages', 0, '每页记录数', 2, '', '后台数据每页显示记录数', 1379503896, 1533521664, 1, '10', 0);
INSERT INTO `think_config` VALUES (26, 'user_allow_register', 4, '开放注册', 3, '0:关闭注册\n1:允许注册', '是否开放用户注册', 1379504487, 1533521585, 1, '0', 3);
INSERT INTO `think_config` VALUES (28, 'data_backup_path', 1, '备份根路径', 4, '', '数据库备份根路径，路径必须以 / 结尾', 1381482411, 1533521561, 1, './data/', 5);
INSERT INTO `think_config` VALUES (29, 'data_backup_part_size', 0, '备份卷大小', 4, '', '数据库备份卷大小，该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1533521547, 1, '20971520', 7);
INSERT INTO `think_config` VALUES (30, 'data_backup_compress', 4, '是否启用压缩', 4, '0:不压缩\n1:启用压缩', '数据库压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1381713345, 1533521364, 1, '0', 9);
INSERT INTO `think_config` VALUES (31, 'data_backup_compress_level', 4, '备份压缩级别', 4, '1:普通\n4:一般\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1381713408, 1533521328, 1, '9', 10);
INSERT INTO `think_config` VALUES (36, 'admin_allow_ip', 2, '禁止访问IP', 4, '', '后台禁止访问IP，多个用逗号分隔，如果不配置表示不限制IP访问', 1387165454, 1533521226, 1, '0.0.0.0', 0);
INSERT INTO `think_config` VALUES (37, 'app_trace', 4, 'Trace', 4, '0:关闭\n1:开启', '是否显示页面Trace信息', 1387165685, 1537846673, 1, '0', 0);
INSERT INTO `think_config` VALUES (49, 'log_std', 4, '本地日志', 1, '0:关闭,1:开启', '是否开启记录日志文件', 1540200530, 1540264970, 1, '0', 50);

-- ----------------------------
-- Table structure for think_forbidden_words
-- ----------------------------
DROP TABLE IF EXISTS `think_forbidden_words`;
CREATE TABLE `think_forbidden_words`  (
  `fid` int(8) NOT NULL AUTO_INCREMENT,
  `word` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '禁词',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0:禁用 1:开启',
  PRIMARY KEY (`fid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_forbidden_words
-- ----------------------------

-- ----------------------------
-- Table structure for think_friend_link
-- ----------------------------
DROP TABLE IF EXISTS `think_friend_link`;
CREATE TABLE `think_friend_link`  (
  `link_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `link_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `link_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `orderby` smallint(5) NULL DEFAULT 0 COMMENT '数字越大排越前面',
  `is_show` tinyint(1) NULL DEFAULT 1,
  PRIMARY KEY (`link_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_friend_link
-- ----------------------------

-- ----------------------------
-- Table structure for think_img
-- ----------------------------
DROP TABLE IF EXISTS `think_img`;
CREATE TABLE `think_img`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '测试表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_img
-- ----------------------------
INSERT INTO `think_img` VALUES (1, 'http://pi7sdygmd.bkt.clouddn.com/db51bfa50e84f9fbb7382cbca77f1ce5.png,http://pi7sdygmd.bkt.clouddn.com/8e773b8598e5cadb3d4d57e8d818b2ec.jpg,http://pi7sdygmd.bkt.clouddn.com/6a06c57deca4c8055ea79eace8847708.jpg,http://pi7sdygmd.bkt.clouddn.com/0aab10c831f8010ffa86c8453248c504.gif');

-- ----------------------------
-- Table structure for think_live_house
-- ----------------------------
DROP TABLE IF EXISTS `think_live_house`;
CREATE TABLE `think_live_house`  (
  `lid` int(5) NOT NULL AUTO_INCREMENT,
  `live_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '直播房间名',
  `live_source` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '直播源',
  `live_pwd` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '房间密码',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '0:直播间禁用 1:直播间启用',
  `create_time` int(11) NOT NULL COMMENT '直播间添加时间',
  PRIMARY KEY (`lid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_live_house
-- ----------------------------
INSERT INTO `think_live_house` VALUES (1, '1号房间', 'http://vlc.04xr.cn/psv/live101.flv', '123', 1, 1634725586);
INSERT INTO `think_live_house` VALUES (4, '2号房间', 'http://47.109.19.60:808/ps/11.flv', '456', 0, 1634725586);
INSERT INTO `think_live_house` VALUES (5, '5号房间', 'http://47.109.19.60:808/ps/11.flv', '678', 0, 1649601597);

-- ----------------------------
-- Table structure for think_log
-- ----------------------------
DROP TABLE IF EXISTS `think_log`;
CREATE TABLE `think_log`  (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NULL DEFAULT NULL COMMENT '用户ID',
  `admin_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户姓名',
  `description` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  `ip` char(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'IP地址',
  `status` int(3) NULL DEFAULT NULL COMMENT '200 成功 100 失败',
  `add_time` int(11) NULL DEFAULT NULL COMMENT '添加时间',
  `ipaddr` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ip地区信息',
  PRIMARY KEY (`log_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4721 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '日志表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_log
-- ----------------------------
INSERT INTO `think_log` VALUES (4709, 1, 'admin', '退出登录', '127.0.0.1', 200, 1649582619, '内网IP');
INSERT INTO `think_log` VALUES (4710, 1, 'admin', '管理员【admin】登录成功', '127.0.0.1', 200, 1649582628, '内网IP');
INSERT INTO `think_log` VALUES (4711, 1, 'admin', '管理员【admin】登录成功', '127.0.0.1', 200, 1649599998, '内网IP');
INSERT INTO `think_log` VALUES (4712, 1, 'admin', '退出登录', '127.0.0.1', 200, 1649600007, '内网IP');
INSERT INTO `think_log` VALUES (4713, 1, 'admin', '管理员【admin】登录成功', '127.0.0.1', 200, 1649600063, '内网IP');
INSERT INTO `think_log` VALUES (4714, 1, 'admin', '菜单【会员管理】禁用成功', '127.0.0.1', 200, 1649600192, '内网IP');
INSERT INTO `think_log` VALUES (4715, 1, 'admin', '菜单【会员列表】禁用成功', '127.0.0.1', 200, 1649600193, '内网IP');
INSERT INTO `think_log` VALUES (4716, 1, 'admin', '菜单【聊天禁词】禁用成功', '127.0.0.1', 200, 1649600228, '内网IP');
INSERT INTO `think_log` VALUES (4717, 1, 'admin', '管理员【admin】登录成功', '127.0.0.1', 200, 1649600365, '内网IP');
INSERT INTO `think_log` VALUES (4718, 1, 'admin', '退出登录', '127.0.0.1', 200, 1649600763, '内网IP');
INSERT INTO `think_log` VALUES (4719, 1, 'admin', '管理员【admin】登录成功', '127.0.0.1', 200, 1649601057, '内网IP');
INSERT INTO `think_log` VALUES (4720, 1, 'admin', '菜单【网站配置】禁用成功', '127.0.0.1', 200, 1649601774, '内网IP');

-- ----------------------------
-- Table structure for think_test
-- ----------------------------
DROP TABLE IF EXISTS `think_test`;
CREATE TABLE `think_test`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 100001 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_test
-- ----------------------------
INSERT INTO `think_test` VALUES (1, 'aa');
INSERT INTO `think_test` VALUES (2, 'bb');

-- ----------------------------
-- Table structure for think_users
-- ----------------------------
DROP TABLE IF EXISTS `think_users`;
CREATE TABLE `think_users`  (
  `uid` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用户别名(随机生成2-8个中文字)',
  `explorer_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '浏览器唯一标识',
  `online_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0:不在线 1:在线',
  `chat_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0:禁言 1:不禁言',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0:禁用用户 1:用户正常',
  `ip` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '最近登陆ip',
  `create_time` int(11) NOT NULL COMMENT '用户创建时间',
  PRIMARY KEY (`uid`) USING BTREE,
  INDEX `explorer_key`(`explorer_key`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 13 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_users
-- ----------------------------
INSERT INTO `think_users` VALUES (1, '十一', 'sdfs345rsfsdfsdfsdf', 1, 1, 1, '127.0.0.1', 1634725586);
INSERT INTO `think_users` VALUES (2, '张三', 'sdfhfghe4ergdfg', 1, 0, 1, '127.0.0.2', 1634725586);
INSERT INTO `think_users` VALUES (9, '谐形', '14abfab7f2cce1791cdca230fff975a2', 1, 0, 1, '127.0.0.1', 1649571924);
INSERT INTO `think_users` VALUES (8, '襄绩', '07c8002858905d277be802dfdc3eb3ca', 1, 0, 1, '127.0.0.1', 1649571466);
INSERT INTO `think_users` VALUES (10, '手背歇', '0c0888628259fe2a5668d8e57291bd7d', 1, 0, 1, '127.0.0.1', 1649574966);
INSERT INTO `think_users` VALUES (11, '掇痢论', '96df5d8fb5a21fcf06f8b5e1faa22fd6', 1, 0, 1, '127.0.0.1', 1649600532);
INSERT INTO `think_users` VALUES (12, '漠厂伺墨射', '40b7a5169fa276305f326e5502bb187e', 1, 0, 1, '127.0.0.1', 1649602999);

-- ----------------------------
-- Table structure for think_word
-- ----------------------------
DROP TABLE IF EXISTS `think_word`;
CREATE TABLE `think_word`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '敏感词id',
  `word` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '敏感词',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of think_word
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;

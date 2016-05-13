/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50161
Source Host           : localhost:3306
Source Database       : csmdb

Target Server Type    : MYSQL
Target Server Version : 50161
File Encoding         : 65001

Date: 2016-05-12 18:26:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sys_admin`
-- ----------------------------
DROP TABLE IF EXISTS `sys_admin`;
CREATE TABLE `sys_admin` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `sys_user` varchar(20) NOT NULL COMMENT '管理员',
  `sys_pwd` char(32) NOT NULL COMMENT '管理密码',
  `sex` tinyint(1) NOT NULL COMMENT '性别',
  `telphone` varchar(20) DEFAULT NULL COMMENT '联系方式',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '开启状态',
  `group_id` int(4) DEFAULT NULL COMMENT '组id',
  `super` tinyint(1) NOT NULL DEFAULT '0' COMMENT '超级管理员',
  `power` varchar(200) DEFAULT NULL COMMENT '权限',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_sys_user` (`sys_user`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_admin
-- ----------------------------
INSERT INTO `sys_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1', '', '1', '1', '1', null);
INSERT INTO `sys_admin` VALUES ('19', 'csm2', '84517febe4c78084945cdf1c53cb8f0c', '0', '', '0', '0', '0', null);
INSERT INTO `sys_admin` VALUES ('17', 'csm', '84517febe4c78084945cdf1c53cb8f0c', '1', '', '1', '0', '0', null);
INSERT INTO `sys_admin` VALUES ('12', 'test', '098f6bcd4621d373cade4e832627b4f6', '1', '', '1', '0', '0', '45|46|51|59|60|61|66|52|62|63|64|67|47|56|57|58|68|55|65|69|70|71|53|54');
INSERT INTO `sys_admin` VALUES ('18', 'xiaocai', '84517febe4c78084945cdf1c53cb8f0c', '0', '', '1', '3', '0', null);

-- ----------------------------
-- Table structure for `sys_advert`
-- ----------------------------
DROP TABLE IF EXISTS `sys_advert`;
CREATE TABLE `sys_advert` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `img` varchar(100) DEFAULT NULL,
  `note` varchar(30) DEFAULT NULL,
  `into_time` int(4) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(4) DEFAULT '0',
  `type_id` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_advert
-- ----------------------------
INSERT INTO `sys_advert` VALUES ('1', '第一张广告', '1.jpg', '#326598', '1236548941', '1', '0', '1');
INSERT INTO `sys_advert` VALUES ('2', '第二张广告', null, '#124578', '1462872716', '1', '1', '2');
INSERT INTO `sys_advert` VALUES ('3', '第三张广告', null, '#569874', '1462872981', '1', '2', '1');

-- ----------------------------
-- Table structure for `sys_dictionary`
-- ----------------------------
DROP TABLE IF EXISTS `sys_dictionary`;
CREATE TABLE `sys_dictionary` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `dicti_name` varchar(30) NOT NULL,
  `dicti_val` int(4) NOT NULL DEFAULT '0',
  `parent_id` int(4) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_dictionary
-- ----------------------------
INSERT INTO `sys_dictionary` VALUES ('1', '系统日志', '0', '0', '1');
INSERT INTO `sys_dictionary` VALUES ('2', '自动', '1', '1', '1');
INSERT INTO `sys_dictionary` VALUES ('4', '手动', '2', '1', '1');
INSERT INTO `sys_dictionary` VALUES ('5', '广告归属', '0', '0', '1');
INSERT INTO `sys_dictionary` VALUES ('6', '首页', '1', '5', '1');
INSERT INTO `sys_dictionary` VALUES ('7', '二级页', '2', '5', '0');

-- ----------------------------
-- Table structure for `sys_group`
-- ----------------------------
DROP TABLE IF EXISTS `sys_group`;
CREATE TABLE `sys_group` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `sys_group` varchar(20) NOT NULL COMMENT '组名',
  `sort` int(4) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '开启状态',
  `parent_id` int(4) DEFAULT '0',
  `power` varchar(200) DEFAULT NULL COMMENT '权限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_group
-- ----------------------------
INSERT INTO `sys_group` VALUES ('1', '互联网事业部', '0', '1', '0', '');
INSERT INTO `sys_group` VALUES ('2', '美工组', '0', '1', '1', '');
INSERT INTO `sys_group` VALUES ('3', '程序组', '0', '1', '1', '45|46|51|59|60|61|66');
INSERT INTO `sys_group` VALUES ('4', '市场组', '0', '1', '1', null);
INSERT INTO `sys_group` VALUES ('5', '产品组', '0', '1', '1', null);
INSERT INTO `sys_group` VALUES ('6', '账务部', '0', '1', '0', null);
INSERT INTO `sys_group` VALUES ('7', '出纳组', '0', '1', '6', null);
INSERT INTO `sys_group` VALUES ('8', '会计组', '0', '1', '6', '');

-- ----------------------------
-- Table structure for `sys_log`
-- ----------------------------
DROP TABLE IF EXISTS `sys_log`;
CREATE TABLE `sys_log` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(4) NOT NULL COMMENT '操作者id',
  `menu_id` int(4) NOT NULL COMMENT '对象id',
  `target_id` int(4) DEFAULT NULL COMMENT '目标id',
  `note` varchar(100) DEFAULT NULL COMMENT '备注',
  `add_time` int(10) NOT NULL,
  `add_ip` varchar(20) DEFAULT NULL,
  `log_type_id` int(4) NOT NULL COMMENT '日志类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_log
-- ----------------------------
INSERT INTO `sys_log` VALUES ('1', '12', '66', null, null, '1438760890', '127.0.0.1', '1');
INSERT INTO `sys_log` VALUES ('2', '12', '59', null, null, '1438760951', '127.0.0.1', '1');
INSERT INTO `sys_log` VALUES ('3', '12', '61', null, null, '1438760966', '127.0.0.1', '1');
INSERT INTO `sys_log` VALUES ('4', '12', '66', null, null, '1438827491', '127.0.0.1', '1');
INSERT INTO `sys_log` VALUES ('5', '12', '71', null, null, '1438827522', '127.0.0.1', '1');
INSERT INTO `sys_log` VALUES ('6', '12', '71', null, null, '1441874863', '127.0.0.1', '1');
INSERT INTO `sys_log` VALUES ('7', '12', '71', null, null, '1441874930', '127.0.0.1', '1');
INSERT INTO `sys_log` VALUES ('8', '18', '66', null, null, '1456144329', '127.0.0.1', '1');

-- ----------------------------
-- Table structure for `sys_menu`
-- ----------------------------
DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE `sys_menu` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sys_menu` varchar(20) NOT NULL COMMENT '系统菜单',
  `sys_url` varchar(100) DEFAULT NULL COMMENT '菜单地址',
  `ident` varchar(100) DEFAULT NULL COMMENT '标识',
  `parent_id` int(4) NOT NULL DEFAULT '0' COMMENT '父级id',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：菜单 1：功能',
  `order` int(4) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：开启 0：关闭',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_menu
-- ----------------------------
INSERT INTO `sys_menu` VALUES ('45', '系统设置', '', null, '0', '0', '0', '1');
INSERT INTO `sys_menu` VALUES ('46', '基本配置', '', null, '45', '0', '0', '1');
INSERT INTO `sys_menu` VALUES ('47', '系统菜单', '/index.php/System/sysMenuIndex', null, '46', '0', '2', '1');
INSERT INTO `sys_menu` VALUES ('48', '用户管理', '', null, '0', '0', '1', '1');
INSERT INTO `sys_menu` VALUES ('49', '数据信息', '', null, '48', '0', '0', '1');
INSERT INTO `sys_menu` VALUES ('50', '用户列表', '', null, '49', '0', '0', '1');
INSERT INTO `sys_menu` VALUES ('51', '管理员', '/index.php/System/sysAdminIndex', null, '46', '0', '0', '1');
INSERT INTO `sys_menu` VALUES ('52', '管理组', '/index.php/System/sysGroupIndex', null, '46', '0', '1', '1');
INSERT INTO `sys_menu` VALUES ('53', '网站管理', '', null, '45', '0', '0', '1');
INSERT INTO `sys_menu` VALUES ('54', '字典配置', '/index.php/System/sysDictionaryIndex', null, '53', '0', '0', '1');
INSERT INTO `sys_menu` VALUES ('55', '权限', '/index.php/System/sysPowerIndex', null, '46', '1', '3', '1');
INSERT INTO `sys_menu` VALUES ('56', '菜单添加', '/index.php/System/sysMenuAdd', null, '47', '1', '0', '1');
INSERT INTO `sys_menu` VALUES ('57', '菜单更新', '/index.php/System/sysMenuUp', null, '47', '1', '1', '1');
INSERT INTO `sys_menu` VALUES ('58', '菜单删除', '/index.php/System/sysMenuDel', null, '47', '1', '2', '1');
INSERT INTO `sys_menu` VALUES ('59', '管理员添加', '/index.php/System/sysAdminAdd', null, '51', '1', '0', '1');
INSERT INTO `sys_menu` VALUES ('60', '管理员更新', '/index.php/System/sysAdminUp', null, '51', '1', '1', '1');
INSERT INTO `sys_menu` VALUES ('61', '管理员删除', '/index.php/System/sysAdminDel', null, '51', '1', '2', '1');
INSERT INTO `sys_menu` VALUES ('62', '管理组添加', '/index.php/System/sysGroupAdd', null, '52', '1', '0', '1');
INSERT INTO `sys_menu` VALUES ('63', '管理组更新', '/index.php/System/sysGroupUp', null, '52', '1', '1', '1');
INSERT INTO `sys_menu` VALUES ('64', '管理组删除', '/index.php/System/sysGroupDel', null, '52', '1', '2', '1');
INSERT INTO `sys_menu` VALUES ('65', '授权', '/index.php/System/sysPowerSet', null, '55', '1', '0', '1');
INSERT INTO `sys_menu` VALUES ('66', '管理员列表', '/index.php/System/sysAdminIndex', null, '51', '1', '3', '1');
INSERT INTO `sys_menu` VALUES ('67', '管理组列表', '/index.php/System/sysGroupIndex', null, '52', '1', '3', '1');
INSERT INTO `sys_menu` VALUES ('68', '菜单列表', '/index.php/System/sysMenuIndex', null, '47', '1', '3', '1');
INSERT INTO `sys_menu` VALUES ('69', '权限列表', '/index.php/System/sysPowerIndex', null, '55', '1', '1', '1');
INSERT INTO `sys_menu` VALUES ('70', '系统日志', '/index.php/System/sysLogIndex', null, '46', '0', '4', '1');
INSERT INTO `sys_menu` VALUES ('71', '日志列表', '/index.php/System/sysLogIndex', null, '70', '1', '0', '1');
INSERT INTO `sys_menu` VALUES ('72', '广告管理', '', null, '0', '0', '2', '1');
INSERT INTO `sys_menu` VALUES ('73', '广告设置', '', null, '72', '0', '0', '1');
INSERT INTO `sys_menu` VALUES ('74', '广告列表', '/index.php/Advert/index', null, '73', '0', '0', '1');

-- ----------------------------
-- Table structure for `sys_news`
-- ----------------------------
DROP TABLE IF EXISTS `sys_news`;
CREATE TABLE `sys_news` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `type_id` int(4) NOT NULL,
  `into_time` int(4) NOT NULL,
  `into_ip` varchar(16) DEFAULT NULL,
  `up_time` int(4) NOT NULL,
  `up_ip` varchar(16) DEFAULT NULL,
  `kit_num` int(4) DEFAULT '0',
  `sort` int(4) DEFAULT '0',
  `status` bit(1) NOT NULL DEFAULT b'1',
  `admin_id` int(4) DEFAULT NULL,
  `up_admin_id` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_news
-- ----------------------------

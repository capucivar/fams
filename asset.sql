/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : fams

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-11-13 19:03:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for asset
-- ----------------------------
DROP TABLE IF EXISTS `asset`;
CREATE TABLE `asset` (
  `assetid` bigint(20) NOT NULL,
  `typeid` bigint(20) NOT NULL,
  `typeid2` bigint(10) DEFAULT NULL,
  `placeid` bigint(20) NOT NULL,
  `assetcode` varchar(10) NOT NULL,
  `assetname` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `size` varchar(100) NOT NULL,
  `unitprice` decimal(10,0) NOT NULL,
  `storenum` int(11) DEFAULT NULL,
  `note` varchar(300) DEFAULT NULL,
  `isvalid` bit(1) NOT NULL DEFAULT b'1',
  `isdisposable` bit(1) DEFAULT NULL,
  `ctime` varchar(19) DEFAULT NULL,
  `mtime` varchar(19) DEFAULT NULL,
  PRIMARY KEY (`assetid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of asset
-- ----------------------------
INSERT INTO `asset` VALUES ('7021767099', '8387262081', '6368371086', '1', 'OFDEVICE01', '3D打印机', '极光尔沃', 'A8工业级金属3D打印机', '14500', '1', '', '', '\0', '2018-11-13 13:21:52', '2018-11-13 13:21:52');
INSERT INTO `asset` VALUES ('5762297857', '5881440615', '7634425016', '1', 'OFFICE0200', '记事本', '得力', '25K 112张', '15', '98', '', '', '', '2018-11-13 12:53:07', '2018-11-13 12:53:07');
INSERT INTO `asset` VALUES ('7601257512', '8027492089', '7634425016', '1', 'OFFICE0100', '得力 珊瑚海 A4', '得力', '70g A4/包', '55', '100', '', '', '', '2018-11-13 12:55:37', '2018-11-13 12:55:37');
INSERT INTO `asset` VALUES ('2967124839', '9539376353', '0', '1', 'DEVICE001', '华为手机', '华为', 'V8', '2000', '1', '', '', '\0', '2018-11-13 12:56:28', '2018-11-13 12:56:28');
INSERT INTO `asset` VALUES ('4097458894', '2226164652', '6368371086', '1', 'OFDEVICE02', '办公桌', '办公桌', '80*140', '200', '4', '', '', '\0', '2018-11-13 13:02:09', '2018-11-13 13:02:09');
INSERT INTO `asset` VALUES ('3711901085', '2226164652', '6368371086', '1', 'OFDEVICE02', '办公桌', '办公桌', '75*120', '140', '50', '', '', '\0', '2018-11-13 13:02:52', '2018-11-13 13:02:52');
INSERT INTO `asset` VALUES ('4435859211', '2226164652', '6368371086', '1', 'OFDEVICE02', '办公椅', '办公椅', '个', '150', '50', '', '', '\0', '2018-11-13 13:04:54', '2018-11-13 13:21:21');
INSERT INTO `asset` VALUES ('6760402183', '9539376353', '0', '1', 'DEVICE002', '苹果手机', 'apple', 'iphone 7', '8000', '0', 'APP 测试使用', '', '\0', '2018-11-13 17:09:51', '2018-11-13 17:09:51');

-- ----------------------------
-- Table structure for assetype
-- ----------------------------
DROP TABLE IF EXISTS `assetype`;
CREATE TABLE `assetype` (
  `typeid` bigint(20) NOT NULL,
  `typename` varchar(20) NOT NULL,
  `typecode` varchar(100) NOT NULL,
  `parentid` bigint(20) NOT NULL,
  `isdisposable` bit(1) NOT NULL,
  `isvalid` bit(1) NOT NULL DEFAULT b'1',
  `ctime` varchar(19) DEFAULT NULL,
  `mtime` varchar(19) DEFAULT NULL,
  PRIMARY KEY (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of assetype
-- ----------------------------
INSERT INTO `assetype` VALUES ('7634425016', '办公耗材', 'OFFICE', '0', '\0', '', '2018-11-13 12:44:54', '2018-11-13 12:44:54');
INSERT INTO `assetype` VALUES ('4672233549', '硬件设备', 'HARDW', '0', '\0', '', '2018-11-13 12:45:44', '2018-11-13 12:45:44');
INSERT INTO `assetype` VALUES ('9539376353', '测试手机', 'DEVICE', '0', '\0', '', '2018-11-13 12:46:10', '2018-11-13 12:46:10');
INSERT INTO `assetype` VALUES ('8027492089', 'A4打印纸', 'OFFICE01', '7634425016', '\0', '', '2018-11-13 12:47:08', '2018-11-13 12:47:08');
INSERT INTO `assetype` VALUES ('5881440615', '记事本', 'OFFICE02', '7634425016', '\0', '', '2018-11-13 12:47:29', '2018-11-13 12:47:29');
INSERT INTO `assetype` VALUES ('6368371086', '办公设备', 'OFDEVICE', '0', '\0', '', '2018-11-13 12:49:26', '2018-11-13 12:49:26');
INSERT INTO `assetype` VALUES ('8387262081', '打印机', 'OFDEVICE01', '6368371086', '\0', '', '2018-11-13 12:50:25', '2018-11-13 12:50:25');
INSERT INTO `assetype` VALUES ('2226164652', '办公桌椅', 'OFDEVICE02', '6368371086', '\0', '', '2018-11-13 12:59:46', '2018-11-13 12:59:46');
INSERT INTO `assetype` VALUES ('2392319546', '电脑', 'COMPUTER', '4672233549', '\0', '', '2018-11-13 13:01:06', '2018-11-13 13:01:06');

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `deptid` bigint(20) NOT NULL,
  `deptname` varchar(20) NOT NULL,
  `parentid` bigint(20) NOT NULL,
  `isvalid` bit(1) NOT NULL DEFAULT b'1',
  `ctime` varchar(19) DEFAULT NULL,
  `mtime` varchar(19) DEFAULT NULL,
  PRIMARY KEY (`deptid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('100001', '产品事业部', '100', '', '2018-08-23 10:22:23', '2018-11-11 00:04:13');
INSERT INTO `department` VALUES ('100', '公司', '0', '', '2018-08-23 10:00', '2018-08-23 10:01');
INSERT INTO `department` VALUES ('100002', '软件组', '100', '', '2018-08-23 10:10', '2018-11-13 15:54:01');
INSERT INTO `department` VALUES ('100003', '测试部', '100', '', '2018-11-11 00:04:13', '2018-11-11 00:04:13');
INSERT INTO `department` VALUES ('100001001', '产品事业一部', '100001', '', '2018-11-13 15:35:46', '2018-11-13 15:35:46');
INSERT INTO `department` VALUES ('100004', '硬件部', '100', '', '2018-11-11 00:14:27', '2018-11-11 00:14:27');
INSERT INTO `department` VALUES ('100001002', '产品事业二部', '100001', '', '2018-11-13 15:40:05', '2018-11-13 15:40:05');
INSERT INTO `department` VALUES ('100002001', '研发一部', '100002', '', '2018-11-13 15:41:21', '2018-11-13 15:41:21');
INSERT INTO `department` VALUES ('100003001', '测试一部', '100003', '', '2018-11-13 15:41:40', '2018-11-13 15:41:40');
INSERT INTO `department` VALUES ('100004001', '硬件一部', '100004', '', '2018-11-13 15:41:48', '2018-11-13 15:41:48');
INSERT INTO `department` VALUES ('100004002', '硬件二部', '100004', '', '2018-11-13 15:41:55', '2018-11-13 15:41:55');
INSERT INTO `department` VALUES ('100002002', '运维部', '100002', '', '2018-11-13 15:54:14', '2018-11-13 15:54:14');

-- ----------------------------
-- Table structure for receive_form
-- ----------------------------
DROP TABLE IF EXISTS `receive_form`;
CREATE TABLE `receive_form` (
  `formid` bigint(20) NOT NULL,
  `assetid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `num` int(10) NOT NULL,
  `note` varchar(100) DEFAULT NULL,
  `state` tinyint(4) DEFAULT '1' COMMENT '1：领用；2：归还；0：易耗品不需要归还',
  `isvalid` bit(1) DEFAULT b'1',
  `ctime` varchar(19) DEFAULT NULL,
  `mtime` varchar(19) DEFAULT NULL,
  PRIMARY KEY (`formid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of receive_form
-- ----------------------------
INSERT INTO `receive_form` VALUES ('3895511352', '6760402183', '7983448907', '1', '', '1', '', '2018-11-13 17:10:20', '2018-11-13 17:10:20');
INSERT INTO `receive_form` VALUES ('7523615742', '5762297857', '7705443667', '2', '', '0', '', '2018-11-13 15:55:18', '2018-11-13 17:29:08');
INSERT INTO `receive_form` VALUES ('4527160152', '2967124839', '7983448907', '1', '测试使用', '2', '', '2018-11-13 15:56:38', '2018-11-13 17:28:30');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userid` bigint(20) NOT NULL,
  `deptid` bigint(20) NOT NULL,
  `usercode` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lastlogin` varchar(19) DEFAULT NULL,
  `isadmin` bit(1) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1',
  `isvalid` bit(1) NOT NULL,
  `ctime` varchar(19) DEFAULT NULL,
  `mtime` varchar(19) DEFAULT NULL,
  `deptid2` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('100101', '100001', '1000', '123456', 'capucivar', '2', '18610022014', 'capucivar@126.com', null, '', '1', '', '2018-08-23 10:22:23', '2018-11-13 18:59:15', null);
INSERT INTO `user` VALUES ('7983448907', '100001', '1001', '123456', '管管', '1', '18610022015', 'gg@126.com', null, '\0', '1', '', '2018-11-12 01:34:32', '2018-11-13 15:42:51', null);
INSERT INTO `user` VALUES ('7676872901', '100003', '1002', '123456', '哈哈', '0', '18610022016', '123444', null, '\0', '0', '', '2018-11-12 01:37:16', '2018-11-12 02:03:19', '100003004');
INSERT INTO `user` VALUES ('7705443667', '100002', '1003', '123456', '李勇', '0', '18610022016', 'ly@126.com', null, '\0', '1', '', '2018-11-13 15:54:47', '2018-11-13 15:54:47', null);

-- ----------------------------
-- Table structure for user_token
-- ----------------------------
DROP TABLE IF EXISTS `user_token`;
CREATE TABLE `user_token` (
  `tokenid` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `token` varchar(10) NOT NULL,
  `isvalid` bit(1) NOT NULL DEFAULT b'1',
  `ctime` varchar(19) DEFAULT NULL,
  `mtime` varchar(19) DEFAULT NULL,
  PRIMARY KEY (`tokenid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_token
-- ----------------------------
INSERT INTO `user_token` VALUES ('3694425417', '100101', '3694425417', '', '2018-11-13 18:59:33', '2018-11-13 18:59:33');

-- ----------------------------
-- Table structure for vcode
-- ----------------------------
DROP TABLE IF EXISTS `vcode`;
CREATE TABLE `vcode` (
  `vcodeid` varchar(32) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `vcode` int(10) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `isvalid` bit(1) NOT NULL DEFAULT b'1',
  `ctime` varchar(19) DEFAULT NULL,
  `mtime` varchar(19) DEFAULT NULL,
  PRIMARY KEY (`vcodeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of vcode
-- ----------------------------
INSERT INTO `vcode` VALUES ('1953431615', '18610022014', '996982', '0', '', '2018-11-13 18:18:07', '2018-11-13 18:59:15');
INSERT INTO `vcode` VALUES ('2243318524', '18610022014', '961699', '0', '', '2018-11-13 18:23:12', '2018-11-13 18:59:15');
INSERT INTO `vcode` VALUES ('850179067', '18610022014', '108162', '0', '', '2018-11-13 18:51:45', '2018-11-13 18:59:15');
INSERT INTO `vcode` VALUES ('1030449097', '18610022014', '622189', '0', '', '2018-11-13 18:58:49', '2018-11-13 18:59:15');

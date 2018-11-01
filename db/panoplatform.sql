/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : panoplatform

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-10-22 13:05:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for basicmateriallayer
-- ----------------------------
DROP TABLE IF EXISTS `basicmateriallayer`;
CREATE TABLE `basicmateriallayer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '素材图片图层分类类名',
  `resourceTypeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8E02A9313DDB0708` (`resourceTypeId`),
  CONSTRAINT `FK_8E02A9313DDB0708` FOREIGN KEY (`resourceTypeId`) REFERENCES `panoramaresourcetype` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of basicmateriallayer
-- ----------------------------
INSERT INTO `basicmateriallayer` VALUES ('1', '默认分类', '2');
INSERT INTO `basicmateriallayer` VALUES ('2', '1111', '2');

-- ----------------------------
-- Table structure for basicmaterialresource
-- ----------------------------
DROP TABLE IF EXISTS `basicmaterialresource`;
CREATE TABLE `basicmaterialresource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resType` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源类型',
  `resFilePathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源路径',
  `resUploadPerson` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传人',
  `resUploaderTime` datetime NOT NULL COMMENT '上传时间',
  `resFileServerName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传文件名',
  `layerId` int(11) DEFAULT NULL,
  `resThumbFilePathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '缩率图资源路径',
  PRIMARY KEY (`id`),
  KEY `IDX_84DC254ED6F94DAC` (`layerId`),
  CONSTRAINT `FK_84DC254ED6F94DAC` FOREIGN KEY (`layerId`) REFERENCES `basicmateriallayer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of basicmaterialresource
-- ----------------------------
INSERT INTO `basicmaterialresource` VALUES ('1', '图片', '../../resource/BasicMaterialLayer/1/CR-IWIKI.jpg', '李长明', '2018-09-30 09:21:26', 'Penguins.jpg', '1', '../../resource/BasicMaterialLayer/1/CR-IWIKI.jpg');

-- ----------------------------
-- Table structure for editmenutype
-- ----------------------------
DROP TABLE IF EXISTS `editmenutype`;
CREATE TABLE `editmenutype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeDesc` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源类型描述',
  `entityClassName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源类型实体名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of editmenutype
-- ----------------------------

-- ----------------------------
-- Table structure for panoimglayer
-- ----------------------------
DROP TABLE IF EXISTS `panoimglayer`;
CREATE TABLE `panoimglayer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '全景图图层列表名',
  `resourceTypeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B07A9D2F3DDB0708` (`resourceTypeId`),
  CONSTRAINT `FK_B07A9D2F3DDB0708` FOREIGN KEY (`resourceTypeId`) REFERENCES `panoramaresourcetype` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of panoimglayer
-- ----------------------------
INSERT INTO `panoimglayer` VALUES ('1', '默认分类', '1');
INSERT INTO `panoimglayer` VALUES ('24', '一层', '1');
INSERT INTO `panoimglayer` VALUES ('25', '3333', '1');
INSERT INTO `panoimglayer` VALUES ('27', '李梦恬', '1');
INSERT INTO `panoimglayer` VALUES ('28', '4444', '1');

-- ----------------------------
-- Table structure for panoimgresource
-- ----------------------------
DROP TABLE IF EXISTS `panoimgresource`;
CREATE TABLE `panoimgresource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resType` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源类型',
  `resFilePathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源路径',
  `resUploadPerson` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传人',
  `resUploaderTime` datetime NOT NULL COMMENT '上传时间',
  `resFileServerName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传文件名',
  `layerId` int(11) DEFAULT NULL,
  `resThumbFilePathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '缩率图资源路径',
  `resPanoPathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '全景图切图资源路径',
  `resSceneString` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '资源全景场景描述',
  PRIMARY KEY (`id`),
  KEY `IDX_7790A1F3D6F94DAC` (`layerId`),
  CONSTRAINT `FK_7790A1F3D6F94DAC` FOREIGN KEY (`layerId`) REFERENCES `panoimglayer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of panoimgresource
-- ----------------------------

-- ----------------------------
-- Table structure for panoramaresourcetype
-- ----------------------------
DROP TABLE IF EXISTS `panoramaresourcetype`;
CREATE TABLE `panoramaresourcetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeDesc` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源类型描述',
  `entityClassName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源类型实体名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of panoramaresourcetype
-- ----------------------------
INSERT INTO `panoramaresourcetype` VALUES ('1', '全景图片', 'PanoImgLayer');
INSERT INTO `panoramaresourcetype` VALUES ('2', '素材图片', 'BasicMaterialLayer');
INSERT INTO `panoramaresourcetype` VALUES ('3', '音乐语音', 'VoiceLayer');
INSERT INTO `panoramaresourcetype` VALUES ('4', '视屏', 'VideoLayer');

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '项目名称',
  `createPerson` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '项目创建人',
  `createTime` datetime NOT NULL COMMENT '创建时间',
  `projectInfo` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '项目描述',
  `projectTypeName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '项目分类名称',
  `viewNumber` int(11) NOT NULL COMMENT '浏览次数',
  `uploadedNumber` int(11) NOT NULL COMMENT '下载次数',
  `projectPathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '项目路径',
  `projectLayerId` int(11) DEFAULT NULL,
  `projectTypeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E00EE972F7D378F9` (`projectLayerId`),
  KEY `IDX_E00EE97253574A4` (`projectTypeId`),
  CONSTRAINT `FK_E00EE97253574A4` FOREIGN KEY (`projectTypeId`) REFERENCES `projecttype` (`id`),
  CONSTRAINT `FK_E00EE972F7D378F9` FOREIGN KEY (`projectLayerId`) REFERENCES `projectlayer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of project
-- ----------------------------

-- ----------------------------
-- Table structure for projectlayer
-- ----------------------------
DROP TABLE IF EXISTS `projectlayer`;
CREATE TABLE `projectlayer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '全景图图层列表名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of projectlayer
-- ----------------------------
INSERT INTO `projectlayer` VALUES ('1', '默认图层');
INSERT INTO `projectlayer` VALUES ('4', '图层8');
INSERT INTO `projectlayer` VALUES ('5', '图层4');
INSERT INTO `projectlayer` VALUES ('6', 'llllll');

-- ----------------------------
-- Table structure for projecttype
-- ----------------------------
DROP TABLE IF EXISTS `projecttype`;
CREATE TABLE `projecttype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '全景图图层列表名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of projecttype
-- ----------------------------
INSERT INTO `projecttype` VALUES ('1', '室内设计');
INSERT INTO `projecttype` VALUES ('2', '景区');
INSERT INTO `projecttype` VALUES ('3', '城市');
INSERT INTO `projecttype` VALUES ('4', '高校');
INSERT INTO `projecttype` VALUES ('5', '中小学');
INSERT INTO `projecttype` VALUES ('6', '建筑');
INSERT INTO `projecttype` VALUES ('7', '酒店');

-- ----------------------------
-- Table structure for videolayer
-- ----------------------------
DROP TABLE IF EXISTS `videolayer`;
CREATE TABLE `videolayer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '音乐语音图层分类类名',
  `resourceTypeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_30F520093DDB0708` (`resourceTypeId`),
  CONSTRAINT `FK_30F520093DDB0708` FOREIGN KEY (`resourceTypeId`) REFERENCES `panoramaresourcetype` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of videolayer
-- ----------------------------
INSERT INTO `videolayer` VALUES ('1', '默认分类', '4');

-- ----------------------------
-- Table structure for videoresource
-- ----------------------------
DROP TABLE IF EXISTS `videoresource`;
CREATE TABLE `videoresource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resType` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源类型',
  `resFilePathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源路径',
  `resUploadPerson` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传人',
  `resUploaderTime` datetime NOT NULL COMMENT '上传时间',
  `resFileServerName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传文件名',
  `layerId` int(11) DEFAULT NULL,
  `resThumbFilePathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '缩率图资源路径',
  PRIMARY KEY (`id`),
  KEY `IDX_673CD565D6F94DAC` (`layerId`),
  CONSTRAINT `FK_673CD565D6F94DAC` FOREIGN KEY (`layerId`) REFERENCES `videolayer` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of videoresource
-- ----------------------------

-- ----------------------------
-- Table structure for voicelayer
-- ----------------------------
DROP TABLE IF EXISTS `voicelayer`;
CREATE TABLE `voicelayer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '音乐语音图层分类类名',
  `resourceTypeId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1AA15A0E3DDB0708` (`resourceTypeId`),
  CONSTRAINT `FK_1AA15A0E3DDB0708` FOREIGN KEY (`resourceTypeId`) REFERENCES `panoramaresourcetype` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of voicelayer
-- ----------------------------
INSERT INTO `voicelayer` VALUES ('1', '默认分类', '3');
INSERT INTO `voicelayer` VALUES ('2', '1111', '3');
INSERT INTO `voicelayer` VALUES ('3', '111111', '3');

-- ----------------------------
-- Table structure for voiceresource
-- ----------------------------
DROP TABLE IF EXISTS `voiceresource`;
CREATE TABLE `voiceresource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resType` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源类型',
  `resFilePathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '资源路径',
  `resUploadPerson` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传人',
  `resUploaderTime` datetime NOT NULL COMMENT '上传时间',
  `resFileServerName` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '上传文件名',
  `layerId` int(11) DEFAULT NULL,
  `resThumbFilePathInServer` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '缩率图资源路径',
  PRIMARY KEY (`id`),
  KEY `IDX_DBA5C75BD6F94DAC` (`layerId`),
  CONSTRAINT `FK_DBA5C75BD6F94DAC` FOREIGN KEY (`layerId`) REFERENCES `voicelayer` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of voiceresource
-- ----------------------------

/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50714
Source Host           : localhost:3306
Source Database       : panoplatform

Target Server Type    : MYSQL
Target Server Version : 50714
File Encoding         : 65001

Date: 2018-10-08 16:36:02
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
  PRIMARY KEY (`id`),
  KEY `IDX_7790A1F3D6F94DAC` (`layerId`),
  CONSTRAINT `FK_7790A1F3D6F94DAC` FOREIGN KEY (`layerId`) REFERENCES `panoimglayer` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of panoimgresource
-- ----------------------------
INSERT INTO `panoimgresource` VALUES ('180', '图片', '../../resource/PanoImgLayer/24/CR-mdANa.jpg', '李长明', '2018-09-21 08:12:29', '宝龙城720全景【顶楼】2.JPG', '24', '../../resource/PanoImgLayer/24/thumb/CR-mdANa.jpg');
INSERT INTO `panoimgresource` VALUES ('181', '图片', '../../resource/PanoImgLayer/24/CR-67lta.jpg', '李长明', '2018-09-21 08:12:29', '宝龙城720全景【顶楼】6.JPG', '24', '../../resource/PanoImgLayer/24/thumb/CR-67lta.jpg');
INSERT INTO `panoimgresource` VALUES ('183', '图片', '../../resource/PanoImgLayer/24/CR-f726m.jpg', '李长明', '2018-09-21 08:12:29', '宝龙城720全景【顶楼】5.JPG', '24', '../../resource/PanoImgLayer/24/thumb/CR-f726m.jpg');
INSERT INTO `panoimgresource` VALUES ('184', '图片', '../../resource/PanoImgLayer/25/CR-AcANg.jpg', '李长明', '2018-09-21 08:12:31', '宝龙城720全景【顶楼】10.JPG', '25', '../../resource/PanoImgLayer/25/thumb/CR-AcANg.jpg');
INSERT INTO `panoimgresource` VALUES ('185', '图片', '../../resource/PanoImgLayer/25/CR-kJRSW.jpg', '李长明', '2018-09-21 08:12:31', '宝龙城720全景【顶楼】7.JPG', '25', '../../resource/PanoImgLayer/25/thumb/CR-kJRSW.jpg');
INSERT INTO `panoimgresource` VALUES ('186', '图片', '../../resource/PanoImgLayer/25/CR-Aqxyi.jpg', '李长明', '2018-09-21 08:12:31', '宝龙城720全景【顶楼】8.JPG', '25', '../../resource/PanoImgLayer/25/thumb/CR-Aqxyi.jpg');
INSERT INTO `panoimgresource` VALUES ('190', '图片', '../../resource/PanoImgLayer/27/CR-597Fn.jpg', '李长明', '2018-09-21 08:12:33', '宝龙城720全景【顶楼】13.JPG', '27', '../../resource/PanoImgLayer/27/thumb/CR-597Fn.jpg');
INSERT INTO `panoimgresource` VALUES ('191', '图片', '../../resource/PanoImgLayer/27/CR-Ovtwf.jpg', '李长明', '2018-09-21 08:12:33', '宝龙城720全景【顶楼】15.JPG', '27', '../../resource/PanoImgLayer/27/thumb/CR-Ovtwf.jpg');
INSERT INTO `panoimgresource` VALUES ('192', '图片', '../../resource/PanoImgLayer/1/CR-GVNoC.jpg', '李长明', '2018-09-21 08:12:33', '宝龙城720全景【顶楼】16.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-GVNoC.jpg');
INSERT INTO `panoimgresource` VALUES ('193', '图片', '../../resource/PanoImgLayer/1/CR-r1jfc.jpg', '李长明', '2018-09-21 08:12:33', '宝龙城720全景【顶楼】14.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-r1jfc.jpg');
INSERT INTO `panoimgresource` VALUES ('194', '图片', '../../resource/PanoImgLayer/1/CR-0TgWb.jpg', '李长明', '2018-09-21 08:12:34', '宝龙城720全景【顶楼】17.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-0TgWb.jpg');
INSERT INTO `panoimgresource` VALUES ('195', '图片', '../../resource/PanoImgLayer/1/CR-mdeQS.jpg', '李长明', '2018-09-21 08:12:35', '宝龙城720全景【顶楼】19.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-mdeQS.jpg');
INSERT INTO `panoimgresource` VALUES ('196', '图片', '../../resource/PanoImgLayer/1/CR-LntSH.jpg', '李长明', '2018-09-21 08:12:35', '宝龙城720全景【顶楼】21.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-LntSH.jpg');
INSERT INTO `panoimgresource` VALUES ('197', '图片', '../../resource/PanoImgLayer/1/CR-BlgHq.jpg', '李长明', '2018-09-21 08:12:35', '宝龙城720全景【顶楼】18.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-BlgHq.jpg');
INSERT INTO `panoimgresource` VALUES ('198', '图片', '../../resource/PanoImgLayer/1/CR-L8KVG.jpg', '李长明', '2018-09-21 08:12:36', '宝龙城720全景【顶楼】20.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-L8KVG.jpg');
INSERT INTO `panoimgresource` VALUES ('199', '图片', '../../resource/PanoImgLayer/1/CR-xB58n.jpg', '李长明', '2018-09-21 08:12:36', '宝龙城720全景【顶楼】22.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-xB58n.jpg');
INSERT INTO `panoimgresource` VALUES ('200', '图片', '../../resource/PanoImgLayer/1/CR-RXgdE.jpg', '李长明', '2018-09-21 08:12:36', '宝龙城720全景【顶楼】23.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-RXgdE.jpg');
INSERT INTO `panoimgresource` VALUES ('201', '图片', '../../resource/PanoImgLayer/1/CR-2I2WB.jpg', '李长明', '2018-09-21 08:12:37', '宝龙城720全景【顶楼】24.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-2I2WB.jpg');
INSERT INTO `panoimgresource` VALUES ('202', '图片', '../../resource/PanoImgLayer/1/CR-jQ6mi.jpg', '李长明', '2018-09-21 08:12:37', '宝龙城720全景【顶楼】25.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-jQ6mi.jpg');
INSERT INTO `panoimgresource` VALUES ('203', '图片', '../../resource/PanoImgLayer/1/CR-WPhvs.jpg', '李长明', '2018-09-21 08:12:38', '宝龙城720全景【顶楼】27.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-WPhvs.jpg');
INSERT INTO `panoimgresource` VALUES ('204', '图片', '../../resource/PanoImgLayer/1/CR-ob0WV.jpg', '李长明', '2018-09-21 08:12:38', '宝龙城720全景【顶楼】28.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-ob0WV.jpg');
INSERT INTO `panoimgresource` VALUES ('205', '图片', '../../resource/PanoImgLayer/1/CR-iV8C0.jpg', '李长明', '2018-09-21 08:12:38', '宝龙城720全景【顶楼】29.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-iV8C0.jpg');
INSERT INTO `panoimgresource` VALUES ('206', '图片', '../../resource/PanoImgLayer/1/CR-7ovlG.jpg', '李长明', '2018-09-21 08:12:38', '宝龙城720全景【顶楼】26.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-7ovlG.jpg');
INSERT INTO `panoimgresource` VALUES ('207', '图片', '../../resource/PanoImgLayer/1/CR-FSDfy.jpg', '李长明', '2018-09-21 08:12:39', '宝龙城720全景【顶楼】31.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-FSDfy.jpg');
INSERT INTO `panoimgresource` VALUES ('208', '图片', '../../resource/PanoImgLayer/1/CR-UEe4j.jpg', '李长明', '2018-09-21 08:12:39', '宝龙城720全景【顶楼】30.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-UEe4j.jpg');
INSERT INTO `panoimgresource` VALUES ('209', '图片', '../../resource/PanoImgLayer/1/CR-UWr34.jpg', '李长明', '2018-09-21 08:12:40', '宝龙城720全景【顶楼】32.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-UWr34.jpg');
INSERT INTO `panoimgresource` VALUES ('210', '图片', '../../resource/PanoImgLayer/1/CR-wVUWt.jpg', '李长明', '2018-09-21 08:12:41', '宝龙城720全景【顶楼】33.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-wVUWt.jpg');
INSERT INTO `panoimgresource` VALUES ('211', '图片', '../../resource/PanoImgLayer/1/CR-WuiQy.jpg', '李长明', '2018-09-21 08:12:41', '宝龙城720全景【顶楼】34.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-WuiQy.jpg');
INSERT INTO `panoimgresource` VALUES ('212', '图片', '../../resource/PanoImgLayer/1/CR-IdOAW.jpg', '李长明', '2018-09-21 08:12:41', '宝龙城720全景【顶楼】36.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-IdOAW.jpg');
INSERT INTO `panoimgresource` VALUES ('213', '图片', '../../resource/PanoImgLayer/1/CR-ic4sP.jpg', '李长明', '2018-09-21 08:12:41', '宝龙城720全景【顶楼】35.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-ic4sP.jpg');
INSERT INTO `panoimgresource` VALUES ('214', '图片', '../../resource/PanoImgLayer/1/CR-q49WD.jpg', '李长明', '2018-09-21 08:12:41', '宝龙城720全景【顶楼】37.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-q49WD.jpg');
INSERT INTO `panoimgresource` VALUES ('215', '图片', '../../resource/PanoImgLayer/1/CR-isTdU.jpg', '李长明', '2018-09-21 08:12:43', '宝龙城720全景【顶楼】41.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-isTdU.jpg');
INSERT INTO `panoimgresource` VALUES ('216', '图片', '../../resource/PanoImgLayer/1/CR-9Pp9b.jpg', '李长明', '2018-09-21 08:12:43', '宝龙城720全景【顶楼】38.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-9Pp9b.jpg');
INSERT INTO `panoimgresource` VALUES ('217', '图片', '../../resource/PanoImgLayer/1/CR-rLHBU.jpg', '李长明', '2018-09-21 08:12:43', '宝龙城720全景【顶楼】40.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-rLHBU.jpg');
INSERT INTO `panoimgresource` VALUES ('218', '图片', '../../resource/PanoImgLayer/1/CR-AwPxv.jpg', '李长明', '2018-09-21 08:12:43', '宝龙城720全景【顶楼】39.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-AwPxv.jpg');
INSERT INTO `panoimgresource` VALUES ('219', '图片', '../../resource/PanoImgLayer/1/CR-AS8Rn.jpg', '李长明', '2018-09-21 08:12:43', '宝龙城720全景【顶楼】42.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-AS8Rn.jpg');
INSERT INTO `panoimgresource` VALUES ('220', '图片', '../../resource/PanoImgLayer/1/CR-NblGr.jpg', '李长明', '2018-09-21 08:12:44', '宝龙城720全景【顶楼】43.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-NblGr.jpg');
INSERT INTO `panoimgresource` VALUES ('221', '图片', '../../resource/PanoImgLayer/1/CR-ayWDJ.jpg', '李长明', '2018-09-21 08:12:44', '宝龙城720全景【顶楼】44.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-ayWDJ.jpg');
INSERT INTO `panoimgresource` VALUES ('222', '图片', '../../resource/PanoImgLayer/1/CR-OEw5d.jpg', '李长明', '2018-09-21 08:12:45', '宝龙城720全景【顶楼】45.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-OEw5d.jpg');
INSERT INTO `panoimgresource` VALUES ('223', '图片', '../../resource/PanoImgLayer/1/CR-YoTsZ.jpg', '李长明', '2018-09-21 08:12:45', '宝龙城720全景【顶楼】46.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-YoTsZ.jpg');
INSERT INTO `panoimgresource` VALUES ('224', '图片', '../../resource/PanoImgLayer/1/CR-soBZ5.jpg', '李长明', '2018-09-21 08:12:45', '宝龙城720全景【顶楼】47.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-soBZ5.jpg');
INSERT INTO `panoimgresource` VALUES ('225', '图片', '../../resource/PanoImgLayer/1/CR-u1bLt.jpg', '李长明', '2018-09-21 08:12:46', '宝龙城720全景【顶楼】49.JPG', '1', '../../resource/PanoImgLayer/1/thumb/CR-u1bLt.jpg');
INSERT INTO `panoimgresource` VALUES ('226', '图片', '../../resource/PanoImgLayer/24/CR-bTb0d.jpg', '李长明', '2018-09-30 09:58:00', '宝龙城720全景【顶楼】4.JPG', '24', '../../resource/PanoImgLayer/24/thumb/CR-bTb0d.jpg');
INSERT INTO `panoimgresource` VALUES ('227', '图片', '../../resource/PanoImgLayer/24/CR-NrgoD.jpg', '李长明', '2018-09-30 09:58:01', '宝龙城720全景【顶楼】2.JPG', '24', '../../resource/PanoImgLayer/24/thumb/CR-NrgoD.jpg');
INSERT INTO `panoimgresource` VALUES ('228', '图片', '../../resource/PanoImgLayer/24/CR-uL58H.jpg', '李长明', '2018-09-30 09:58:01', '宝龙城720全景【顶楼】3.JPG', '24', '../../resource/PanoImgLayer/24/thumb/CR-uL58H.jpg');
INSERT INTO `panoimgresource` VALUES ('229', '图片', '../../resource/PanoImgLayer/28/CR-caV7E.jpg', '李长明', '2018-09-30 10:00:57', '宝龙城720全景【顶楼】24.JPG', '28', '../../resource/PanoImgLayer/28/thumb/CR-caV7E.jpg');
INSERT INTO `panoimgresource` VALUES ('230', '图片', '../../resource/PanoImgLayer/28/CR-DFIYc.jpg', '李长明', '2018-09-30 10:00:57', '宝龙城720全景【顶楼】23.JPG', '28', '../../resource/PanoImgLayer/28/thumb/CR-DFIYc.jpg');
INSERT INTO `panoimgresource` VALUES ('231', '图片', '../../resource/PanoImgLayer/28/CR-Fbcy0.jpg', '李长明', '2018-09-30 10:00:57', '宝龙城720全景【顶楼】22.JPG', '28', '../../resource/PanoImgLayer/28/thumb/CR-Fbcy0.jpg');
INSERT INTO `panoimgresource` VALUES ('232', '图片', '../../resource/PanoImgLayer/28/CR-IdwD8.jpg', '李长明', '2018-09-30 10:00:58', '宝龙城720全景【顶楼】9.JPG', '28', '../../resource/PanoImgLayer/28/thumb/CR-IdwD8.jpg');
INSERT INTO `panoimgresource` VALUES ('233', '图片', '../../resource/PanoImgLayer/28/CR-DnVpA.jpg', '李长明', '2018-09-30 10:00:58', '宝龙城720全景【顶楼】7.JPG', '28', '../../resource/PanoImgLayer/28/thumb/CR-DnVpA.jpg');
INSERT INTO `panoimgresource` VALUES ('234', '图片', '../../resource/PanoImgLayer/28/CR-nf5Ev.jpg', '李长明', '2018-09-30 10:00:58', '宝龙城720全景【顶楼】8.JPG', '28', '../../resource/PanoImgLayer/28/thumb/CR-nf5Ev.jpg');

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of project
-- ----------------------------
INSERT INTO `project` VALUES ('31', '111', '李长明', '2018-09-27 09:41:39', '这是全景项目', '景区', '1', '1', 'data/krpano/4/31/tour.html', '4', '2');
INSERT INTO `project` VALUES ('32', '测试', '李长明', '2018-09-28 00:52:17', '这是全景项目', '室内设计', '1', '1', 'data/krpano/1/32/tour.html', '1', '1');
INSERT INTO `project` VALUES ('33', '11111', '李长明', '2018-09-30 03:41:12', '这是全景项目', '室内设计', '1', '1', 'data/krpano/1/33/tour.html', '1', '1');
INSERT INTO `project` VALUES ('34', '345365', '李长明', '2018-09-30 09:02:40', '这是全景项目', '景区', '1', '1', 'data/krpano/1/34/tour.html', '1', '2');

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
INSERT INTO `projectlayer` VALUES ('6', '图层5');

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

/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50610
Source Host           : localhost:3306
Source Database       : rockxinhu_fabu

Target Server Type    : MYSQL
Target Server Version : 50610
File Encoding         : 65001

Date: 2021-10-29 14:05:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `xinhu_admin`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_admin`;
CREATE TABLE `xinhu_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` varchar(20) DEFAULT NULL COMMENT '编号',
  `user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `pass` varchar(100) DEFAULT NULL COMMENT '密码',
  `loginci` smallint(6) DEFAULT '0' COMMENT '登录次数',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否启用',
  `type` tinyint(1) DEFAULT '0' COMMENT '0普通1管理员',
  `sex` varchar(10) DEFAULT NULL COMMENT '性别',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话',
  `face` varchar(300) DEFAULT NULL COMMENT '头像地址',
  `deptid` int(11) DEFAULT '0' COMMENT '主部门ID',
  `deptname` varchar(50) DEFAULT NULL COMMENT '部门',
  `deptids` varchar(50) DEFAULT NULL COMMENT '其他部门ID',
  `deptnames` varchar(100) DEFAULT NULL COMMENT '多部门',
  `rankings` varchar(100) DEFAULT NULL COMMENT '多职位',
  `deptallname` varchar(200) DEFAULT NULL COMMENT '部门全部路径',
  `superid` varchar(100) DEFAULT NULL,
  `superman` varchar(100) DEFAULT NULL COMMENT '上级主管',
  `ranking` varchar(255) DEFAULT NULL COMMENT '岗位',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `deptpath` varchar(100) DEFAULT NULL COMMENT '部门路径',
  `superpath` varchar(100) DEFAULT NULL COMMENT '上级主管路径',
  `groupname` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `apptx` tinyint(1) DEFAULT '1' COMMENT '是否app提醒',
  `workdate` date DEFAULT NULL COMMENT '入职时间',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `lastpush` datetime DEFAULT NULL COMMENT '最后app推送时间',
  `adddt` datetime DEFAULT NULL COMMENT '新增时间',
  `weixinid` varchar(50) DEFAULT NULL COMMENT '微信号',
  `quitdt` date DEFAULT NULL COMMENT '离职日期',
  `style` tinyint(2) DEFAULT '0' COMMENT '默认样式',
  `pingyin` varchar(50) DEFAULT NULL COMMENT '名字拼音',
  `emailpass` varchar(100) DEFAULT NULL COMMENT '邮箱密码',
  `companyid` int(11) DEFAULT '0' COMMENT '所在公司单位Id',
  `online` tinyint(1) DEFAULT '0' COMMENT '在线状态',
  `lastonline` datetime DEFAULT NULL COMMENT '最后在线时间',
  `isvcard` tinyint(1) DEFAULT '1' COMMENT '是否在通讯录上显示',
  `randslat` varchar(50) DEFAULT NULL COMMENT '随机字符串',
  `dwid` varchar(50) DEFAULT NULL COMMENT '加入单位id',
  `editpass` int(11) DEFAULT '0' COMMENT '修改密码次数',
  `comid` smallint(6) DEFAULT '0' COMMENT '默认单位Id',
  PRIMARY KEY (`id`,`user`),
  KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of xinhu_admin
-- ----------------------------
INSERT INTO `xinhu_admin` VALUES ('1', 'A001', 'admin', '管理员', 'e10adc3949ba59abbe56e057f20f883e', '4114', '1', '1', '男', '0592-1234569', 'upload/face/1.jpg', '2', '开发部', null, null, null, '信呼开发团队/开发部', '5', '磐石', '项目主管', '1', '[1],[2]', '[5]', '1,3,4', '15800000023', '1', '2016-06-01', null, '2017-03-08 09:52:30', null, null, null, '0', 'guanliyuan', null, '1', '0', null, '1', null, null, '0', '1');
INSERT INTO `xinhu_admin` VALUES ('2', null, 'diaochan', '貂蝉', 'e10adc3949ba59abbe56e057f20f883e', '154', '1', '0', '女', '0592-123456', null, '3', '财务部', null, null, null, '信呼开发团队/财务部', '5', '磐石', '财务总监', '1', '[1],[3]', '[5]', '1,3', '15800000007', '1', '2016-08-09', null, null, null, null, null, '0', 'diaochan', null, '2', '0', null, '1', null, null, '0', '1');
INSERT INTO `xinhu_admin` VALUES ('3', null, 'xiaoqiao', '小乔', 'e10adc3949ba59abbe56e057f20f883e', '278', '1', '0', '女', null, null, '3', '财务部', null, null, null, '信呼开发团队/财务部', '2', '貂蝉', '出纳', '1', '[1],[3]', '[5],[2]', '1', '15800000001', '1', '2017-05-02', null, '2016-08-30 20:34:23', null, null, null, '0', 'xiaoqiao', null, '2', '0', null, '1', null, null, '0', '1');
INSERT INTO `xinhu_admin` VALUES ('4', null, 'daqiao', '大乔', 'e10adc3949ba59abbe56e057f20f883e', '384', '1', '0', '女', null, null, '4', '行政人事部', '3', '财务部', '会计', '信呼开发团队/行政人事部', '5', '磐石', '人事主管', '1', '[1],[4],[3]', '[5]', '1,3', '15800000002', '1', '2017-07-01', null, '2016-10-20 22:27:51', null, null, null, '0', 'daqiao', null, '2', '0', null, '1', null, null, '0', '1');
INSERT INTO `xinhu_admin` VALUES ('5', null, 'rock', '磐石', 'e10adc3949ba59abbe56e057f20f883e', '400', '1', '0', '男', null, null, '5', '管理层', null, null, null, '信呼开发团队/管理层', null, null, '董事长', '0', '[1],[5]', null, '2,3', '15800000003', '1', '2015-11-01', null, null, null, null, null, '0', 'panshi', null, '1', '0', null, '1', null, '1,2,3,4', '0', '1');
INSERT INTO `xinhu_admin` VALUES ('6', null, 'zhangfei', '张飞', 'e10adc3949ba59abbe56e057f20f883e', '192', '1', '0', '男', null, null, '2', '开发部', null, null, null, '信呼开发团队/开发部', '7', '赵子龙', '程序员', '100', '[1],[2]', '[5],[1],[7]', '1', '15800000004', '1', '2017-07-01', null, '2016-11-01 20:15:52', null, null, null, '0', 'zhangfei', null, '1', '0', null, '1', null, null, '0', '0');
INSERT INTO `xinhu_admin` VALUES ('7', null, 'zhaozl', '赵子龙', 'e10adc3949ba59abbe56e057f20f883e', '238', '1', '0', '男', null, null, '2', '开发部', null, null, null, '信呼开发团队/开发部', '1', '管理员', '高级程序员', '100', '[1],[2]', '[5],[1]', '1', '15800000005', '1', '2016-07-01', null, '2017-06-28 15:34:26', null, null, null, '0', 'zhaozilong', null, '1', '0', null, '1', null, null, '0', '0');
INSERT INTO `xinhu_admin` VALUES ('8', null, 'xinhu', '信呼客服', '6846860684f05029abccc09a53cd66f1', '411', '1', '1', '女', null, null, '2', '开发部', null, null, null, '信呼开发团队/开发部', '1', '管理员', '客服', '200', '[1],[2]', '[5],[1]', '1,4', '15800000006', '1', '2018-12-12', null, null, null, 'rainrock829', null, '0', 'xinhukefu', null, '1', '0', null, '1', null, '2', '0', '1');

-- ----------------------------
-- Table structure for `xinhu_assetm`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_assetm`;
CREATE TABLE `xinhu_assetm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` smallint(6) DEFAULT '0' COMMENT '类别',
  `title` varchar(50) DEFAULT NULL COMMENT '名称',
  `num` varchar(20) DEFAULT NULL COMMENT '编号',
  `brand` varchar(20) DEFAULT NULL COMMENT '品牌',
  `model` varchar(100) DEFAULT NULL COMMENT '规格型号',
  `laiyuan` varchar(50) DEFAULT NULL COMMENT '来源',
  `shuname` varchar(50) DEFAULT NULL COMMENT '所属部门',
  `dt` date DEFAULT NULL COMMENT '日期',
  `ckid` smallint(50) DEFAULT '0' COMMENT '存放地点',
  `state` tinyint(1) DEFAULT '0' COMMENT '状态',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `adddt` datetime DEFAULT NULL COMMENT '添加时间',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `buydt` date DEFAULT NULL COMMENT '购进日期',
  `price` decimal(10,2) DEFAULT NULL COMMENT '价格',
  `optid` smallint(6) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `useid` varchar(50) DEFAULT '0',
  `usename` varchar(50) DEFAULT NULL COMMENT '使用人',
  `fengmian` varchar(50) DEFAULT NULL COMMENT '封面图片',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `address` varchar(50) DEFAULT NULL COMMENT '所在位置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='固定资产表';

-- ----------------------------
-- Records of xinhu_assetm
-- ----------------------------
INSERT INTO `xinhu_assetm` VALUES ('1', '204', '公用打印机', 'DYJ-001', '联想', null, '购入', null, null, '0', '1', null, '管理员', '2020-04-20 20:45:41', '2020-04-21 11:13:18', null, '0.00', '1', '1', 'd1', '信呼开发团队', 'images/noimg.jpg', '1', '前台');
INSERT INTO `xinhu_assetm` VALUES ('2', '355', '公用复印机', 'FYJ-001', '联想', null, '购入', null, null, '0', '0', null, '管理员', '2020-04-21 11:30:02', '2020-04-21 11:30:02', null, '0.00', '1', '1', null, null, null, '1', '前台');

-- ----------------------------
-- Table structure for `xinhu_bianjian`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_bianjian`;
CREATE TABLE `xinhu_bianjian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `uid` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(50) DEFAULT NULL COMMENT '操作人',
  `content` varchar(2000) DEFAULT NULL COMMENT '内容',
  `state` tinyint(4) DEFAULT '0' COMMENT '状态',
  `adddt` datetime DEFAULT NULL COMMENT '添加时间',
  `suodt` datetime DEFAULT NULL COMMENT '所属时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xinhu_bianjian
-- ----------------------------
INSERT INTO `xinhu_bianjian` VALUES ('10', '1', '4', '2021-08-31 18:09:12', '大乔', '测试一下这个便笺，很好很不错的哦', '0', '2021-08-31 18:09:12', '2021-08-31 18:08:56');
INSERT INTO `xinhu_bianjian` VALUES ('11', '1', '4', '2021-08-31 18:10:36', '大乔', '下班喽', '0', '2021-08-31 18:10:36', '2021-08-31 18:10:30');
INSERT INTO `xinhu_bianjian` VALUES ('6', '1', '1', '2021-08-31 12:15:44', '管理员', '处理问题的拉', '0', '2021-08-31 12:15:44', '2021-09-01 12:15:20');
INSERT INTO `xinhu_bianjian` VALUES ('5', '1', '1', '2021-08-31 18:08:13', '管理员', '便笺模块完善，添加到首页项里面。', '1', '2021-08-30 22:47:39', '2021-08-31 12:15:11');
INSERT INTO `xinhu_bianjian` VALUES ('9', '1', '1', '2021-08-31 18:07:08', '管理员', '今日做很多事情，打育苗等。', '0', '2021-08-31 18:07:08', '2021-08-31 18:06:34');
INSERT INTO `xinhu_bianjian` VALUES ('8', '1', '1', '2021-08-31 17:48:38', '管理员', '哈哈哈', '0', '2021-08-31 17:48:38', '2021-08-11 17:48:31');
INSERT INTO `xinhu_bianjian` VALUES ('12', '1', '4', '2021-08-31 18:14:51', '大乔', '开学啦！', '0', '2021-08-31 18:14:51', '2021-09-02 18:14:31');
INSERT INTO `xinhu_bianjian` VALUES ('13', '1', '1', '2021-08-31 18:17:50', '管理员', '下班喽', '0', '2021-08-31 18:17:50', '2021-08-31 18:17:45');
INSERT INTO `xinhu_bianjian` VALUES ('14', '1', '1', '2021-08-31 18:32:19', '管理员', '下班检查服务器运行清空才能走。', '2', '2021-08-31 18:32:19', '2021-08-31 18:32:00');
INSERT INTO `xinhu_bianjian` VALUES ('15', '1', '7', '2021-08-31 21:16:23', '赵子龙', '真不错的哦', '0', '2021-08-31 21:16:23', '2021-08-31 21:16:04');
INSERT INTO `xinhu_bianjian` VALUES ('16', '1', '1', '2021-09-01 09:17:58', '管理员', '开学啦，很好很不错。', '0', '2021-09-01 09:17:58', '2021-09-01 08:17:44');

-- ----------------------------
-- Table structure for `xinhu_book`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_book`;
CREATE TABLE `xinhu_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '书名',
  `typeid` smallint(6) DEFAULT '0' COMMENT '对应分类',
  `num` varchar(50) DEFAULT NULL COMMENT '编号',
  `author` varchar(20) DEFAULT NULL COMMENT '作者',
  `chuban` varchar(50) DEFAULT NULL COMMENT '出版社',
  `cbdt` date DEFAULT NULL COMMENT '出版日期',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '价格',
  `weizhi` varchar(50) DEFAULT NULL COMMENT '存放位置',
  `shul` smallint(6) DEFAULT '0' COMMENT '数量',
  `adddt` datetime DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `optid` smallint(6) DEFAULT '0',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `isbn` varchar(30) DEFAULT NULL,
  `jieshu` smallint(6) DEFAULT '0' COMMENT '被借阅数',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='图书表';

-- ----------------------------
-- Records of xinhu_book
-- ----------------------------
INSERT INTO `xinhu_book` VALUES ('1', 'C语言程序设计', '232', 'TS-001', '巨同升', '中国日报', '2012-10-01', '20.00', '忘了', '1', '2016-10-28 23:18:36', '2016-10-28 23:21:53', '管理员', '1', '本书在内容编排上，采用“应用驱动知识”的方式，即根据每一章应用目标的需求，合理地安排每一个知识主题的引入点，从而将C语言中枯燥难懂的语法知识分解到全书各章并融入到丰富的实例中。本书在编程方法的讲解上，采用“逐步构造法”，即通过问题分析、算法设计、程序原型等环节一步一步地构造出完整的程序，从而加深读者对编程方法的理解和掌握。', null, '0', '0');
INSERT INTO `xinhu_book` VALUES ('2', 'PHP从入门到精通', '232', 'TS-002', '潘凯华', '化学工业出版社', '2008-10-01', '68.00', '忘了', '1', '2016-10-28 23:25:22', '2016-10-28 23:27:07', '管理员', '1', null, '9787302179597', '0', '0');
INSERT INTO `xinhu_book` VALUES ('4', 'PHP从入门到放弃', '357', 'TS-003', '磐石', '信呼开发团队', '2017-01-17', '49.90', '不知道', '5', '2017-08-20 12:32:43', '2017-08-20 12:32:43', '管理员', '1', null, 'PANSHI-001', '0', '0');
INSERT INTO `xinhu_book` VALUES ('5', 'Android从入门到放弃', '464', 'TS-004', '磐石', '信呼开发团队', '2017-01-17', '49.90', '不知道', '5', '2019-06-07 13:31:04', '2019-06-07 13:31:04', '大乔', '4', null, 'PANSHI-001', '0', '2');

-- ----------------------------
-- Table structure for `xinhu_bookborrow`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_bookborrow`;
CREATE TABLE `xinhu_bookborrow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `bookid` smallint(6) DEFAULT '0',
  `bookname` varchar(50) DEFAULT NULL COMMENT '书名',
  `jydt` date DEFAULT NULL COMMENT '借阅日期',
  `yjdt` date DEFAULT NULL COMMENT '预计归还',
  `ghtime` datetime DEFAULT NULL COMMENT '归还时间',
  `isgh` tinyint(1) DEFAULT '0' COMMENT '是否归返',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='图书借阅';

-- ----------------------------
-- Records of xinhu_bookborrow
-- ----------------------------
INSERT INTO `xinhu_bookborrow` VALUES ('1', '1', '2016-10-29 13:08:44', '1', '管理员', '2016-10-29', null, '1', '1', '2', 'PHP从入门到精通', '2016-10-29', '2016-10-31', '2016-10-29 19:42:43', '1', '0');
INSERT INTO `xinhu_bookborrow` VALUES ('2', '8', '2016-10-29 13:14:35', '8', '信呼客服', '2016-10-29', '拿来看看把！', '1', '1', '1', '刘备的资料', '2016-10-29', '2016-10-30', '2020-07-01 17:27:00', '1', '0');
INSERT INTO `xinhu_bookborrow` VALUES ('3', '1', '2016-10-30 11:58:59', '1', '管理员', '2016-10-30', null, '1', '1', '2', 'PHP从入门到精通', '2016-10-30', '2016-11-30', '2017-03-31 09:36:00', '1', '0');
INSERT INTO `xinhu_bookborrow` VALUES ('4', '1', '2018-11-07 19:50:40', '1', '管理员', '2018-11-06', '借来看看。', '1', '1', '2', 'PHP从入门到精通', '2018-11-06', '2018-11-08', '2019-09-17 18:32:36', '1', '0');

-- ----------------------------
-- Table structure for `xinhu_carm`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_carm`;
CREATE TABLE `xinhu_carm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carnum` varchar(10) DEFAULT NULL COMMENT '车牌号',
  `carbrand` varchar(20) DEFAULT NULL COMMENT '车辆品牌',
  `carmode` varchar(30) DEFAULT NULL COMMENT '型号',
  `cartype` varchar(10) DEFAULT NULL COMMENT '车辆类型',
  `buydt` date DEFAULT NULL COMMENT '购买日期',
  `buyprice` mediumint(9) DEFAULT NULL COMMENT '购买价格',
  `enginenb` varchar(50) DEFAULT NULL COMMENT '发动机号',
  `ispublic` tinyint(1) DEFAULT '1' COMMENT '是否公开使用',
  `optdt` datetime DEFAULT NULL,
  `optid` smallint(6) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL,
  `adddt` datetime DEFAULT NULL,
  `createname` varchar(20) DEFAULT NULL,
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `state` tinyint(1) DEFAULT '0' COMMENT '状态@0|办理中,1|可用,2|维修中,3|报废',
  `framenum` varchar(50) DEFAULT NULL COMMENT '车架号',
  `status` tinyint(4) DEFAULT '1',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='车辆表';

-- ----------------------------
-- Records of xinhu_carm
-- ----------------------------
INSERT INTO `xinhu_carm` VALUES ('1', '闽D·123QB', '奥迪', 'N7', '小型车', '2015-09-01', '20000', '不知道', '1', '2016-10-27 13:31:00', '1', '管理员', '2015-09-16 10:51:56', '管理员', null, '1', null, '1', '0');
INSERT INTO `xinhu_carm` VALUES ('2', '闽D·123455', '奔驰', 'A4L', '轿车', '2015-01-01', '20000', null, '1', '2016-10-28 13:28:01', '1', '管理员', '2015-09-17 09:54:49', '管理员', null, '1', 'aodiche', '1', '0');

-- ----------------------------
-- Table structure for `xinhu_carmang`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_carmang`;
CREATE TABLE `xinhu_carmang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `carid` smallint(6) DEFAULT '0' COMMENT '维修车辆',
  `reason` varchar(500) DEFAULT NULL COMMENT '维修原因',
  `address` varchar(50) DEFAULT NULL COMMENT '维修地点',
  `jianame` varchar(50) DEFAULT NULL COMMENT '驾驶员',
  `jiaid` varchar(50) DEFAULT NULL,
  `bujian` varchar(200) DEFAULT NULL COMMENT '更换部件',
  `startdt` datetime DEFAULT NULL COMMENT '维修时间',
  `enddt` datetime DEFAULT NULL COMMENT '维修截止时间',
  `money` decimal(8,2) DEFAULT '0.00' COMMENT '维修金额',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型@0|车辆维修,1|保养',
  `nextdt` date DEFAULT NULL COMMENT '下次保养日期',
  `kmshu` varchar(20) DEFAULT NULL COMMENT '当前保养公里数',
  `kmnshu` varchar(20) DEFAULT NULL COMMENT '下次保养公里数',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='车辆维修保养记录表';

-- ----------------------------
-- Records of xinhu_carmang
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_carmrese`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_carmrese`;
CREATE TABLE `xinhu_carmrese` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` int(11) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '0',
  `isturn` tinyint(1) DEFAULT '0',
  `useid` varchar(200) DEFAULT NULL,
  `usename` varchar(200) DEFAULT NULL COMMENT '使用者',
  `useren` smallint(6) DEFAULT '0' COMMENT '使用人数',
  `startdt` datetime DEFAULT NULL COMMENT '开始时间',
  `enddt` datetime DEFAULT NULL COMMENT '截止时间',
  `address` varchar(50) DEFAULT NULL COMMENT '目的地',
  `carid` smallint(6) DEFAULT NULL,
  `carnum` varchar(10) DEFAULT NULL COMMENT '使用车辆',
  `xianlines` varchar(200) DEFAULT NULL COMMENT '线路',
  `jiaid` varchar(200) DEFAULT NULL,
  `jianame` varchar(200) DEFAULT NULL COMMENT '驾驶员',
  `kmstart` varchar(20) DEFAULT NULL COMMENT '起始公里数',
  `kmend` varchar(20) DEFAULT NULL COMMENT '结束公里数',
  `returndt` datetime DEFAULT NULL COMMENT '归还时间',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='车辆预定';

-- ----------------------------
-- Records of xinhu_carmrese
-- ----------------------------
INSERT INTO `xinhu_carmrese` VALUES ('1', '1', '2018-09-03 19:51:16', '1', '管理员', '2018-09-03', null, '0', '1', 'u5', '磐石', '5', '2018-09-03 19:50:00', '2018-09-03 19:50:57', '不知道', '2', '闽D·123455', '不知道', '6', '张飞', '20', null, null, '0');

-- ----------------------------
-- Table structure for `xinhu_carms`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_carms`;
CREATE TABLE `xinhu_carms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `carid` int(11) DEFAULT '0' COMMENT '车辆Id',
  `otype` varchar(20) DEFAULT NULL COMMENT '类型,加油',
  `startdt` date DEFAULT NULL,
  `enddt` date DEFAULT NULL COMMENT '截止时间',
  `money` decimal(8,2) DEFAULT '0.00' COMMENT '花费金额',
  `optid` int(11) DEFAULT '0' COMMENT '添加人id',
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '添加人',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `unitname` varchar(50) DEFAULT NULL COMMENT '对应名称',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `address` varchar(100) DEFAULT NULL COMMENT '地点',
  `jiaid` varchar(30) DEFAULT NULL,
  `jianame` varchar(30) DEFAULT NULL COMMENT '驾驶员',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='车辆信息登记表';

-- ----------------------------
-- Records of xinhu_carms
-- ----------------------------
INSERT INTO `xinhu_carms` VALUES ('1', '1', '加油', '2017-06-22', null, '200.00', '1', '2017-06-22 12:47:42', '管理员', '1', '去加油了', null, '2017-06-22', '中石化', '5', '磐石', '0');
INSERT INTO `xinhu_carms` VALUES ('2', '1', '强险', '2016-06-24', '2017-08-29', '900.00', '1', '2017-08-23 14:52:00', '管理员', '1', null, null, '2017-06-22', null, null, null, '0');
INSERT INTO `xinhu_carms` VALUES ('3', '1', '商业险', '2016-06-22', '2017-08-28', '3000.00', '1', '2017-08-23 14:52:11', '管理员', '1', null, null, '2017-06-22', null, null, null, '0');
INSERT INTO `xinhu_carms` VALUES ('4', '2', '加油', '2020-09-26', null, '50.00', '1', '2020-09-26 10:57:42', '管理员', '1', null, null, '2020-09-26', null, null, null, '1');

-- ----------------------------
-- Table structure for `xinhu_chargems`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_chargems`;
CREATE TABLE `xinhu_chargems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT '0',
  `mid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL,
  `updatedt` datetime DEFAULT NULL,
  `key` varchar(200) DEFAULT NULL,
  `modeid` smallint(6) DEFAULT '0' COMMENT '对应安装模块Id',
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='系统升级';

-- ----------------------------
-- Records of xinhu_chargems
-- ----------------------------
INSERT INTO `xinhu_chargems` VALUES ('1', '0', '1', '2016-12-28 15:24:19', '2021-10-29 14:05:30', null, '1');
INSERT INTO `xinhu_chargems` VALUES ('2', '1', '1', '2016-12-28 15:21:14', '2021-10-29 14:05:30', null, '0');

-- ----------------------------
-- Table structure for `xinhu_city`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_city`;
CREATE TABLE `xinhu_city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级ＩＤ',
  `name` varchar(50) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '类型0国家,1省,2市,3县',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序',
  `pinyin` varchar(50) DEFAULT NULL,
  `pinyins` varchar(50) DEFAULT NULL COMMENT '拼音简称',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`pid`),
  KEY `region_type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='城市表';

-- ----------------------------
-- Records of xinhu_city
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_company`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_company`;
CREATE TABLE `xinhu_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo` varchar(200) DEFAULT NULL COMMENT '对应logo',
  `name` varchar(100) DEFAULT NULL COMMENT '公司名称',
  `nameen` varchar(200) DEFAULT NULL COMMENT '英文名',
  `oaname` varchar(100) DEFAULT NULL COMMENT '显示OA名称',
  `oanemes` varchar(100) DEFAULT NULL COMMENT '移动端显示名称',
  `tel` varchar(30) DEFAULT NULL COMMENT '电话',
  `fax` varchar(30) DEFAULT NULL COMMENT '传真',
  `pid` int(11) DEFAULT '0' COMMENT '对应上级',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `fuzeid` varchar(30) DEFAULT NULL COMMENT '对应负责人Id',
  `fuzename` varchar(30) DEFAULT NULL COMMENT '对应负责人',
  `address` varchar(50) DEFAULT NULL COMMENT '地址',
  `city` varchar(30) DEFAULT NULL COMMENT '所在城市',
  `num` varchar(50) DEFAULT NULL COMMENT '单位编号',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `iscreate` tinyint(1) DEFAULT '0',
  `yuming` varchar(100) DEFAULT NULL COMMENT '使用域名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='公司单位表';

-- ----------------------------
-- Records of xinhu_company
-- ----------------------------
INSERT INTO `xinhu_company` VALUES ('1', 'images/logo.png', '信呼开发团队', null, null, null, '0592-123456', '0592-123456', '0', '0', '5', '磐石', '软件园', '厦门', null, '0', '0', null);
INSERT INTO `xinhu_company` VALUES ('2', 'images/logohong.png', '信呼开发团队(泉州分公司)', null, '信呼办公OA泉州分公司', null, null, null, '1', '0', null, null, null, null, 'qz', '1', '0', null);
INSERT INTO `xinhu_company` VALUES ('3', null, '信呼开发团队(北京分公司)', null, '信呼办公OA北京分公司', null, null, null, '1', '0', null, null, null, null, 'bj', '0', '0', null);
INSERT INTO `xinhu_company` VALUES ('4', 'images/apply.png', '房租家软件公司', null, '房租家软件内部OA系统', null, null, null, '0', '0', null, null, null, null, 'ruan', '0', '0', null);

-- ----------------------------
-- Table structure for `xinhu_custappy`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_custappy`;
CREATE TABLE `xinhu_custappy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `custid` int(11) DEFAULT '0' COMMENT '客户ID',
  `custname` varchar(50) DEFAULT NULL COMMENT '客户名称',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `custid` (`custid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='客户申请使用';

-- ----------------------------
-- Records of xinhu_custappy
-- ----------------------------
INSERT INTO `xinhu_custappy` VALUES ('1', '1', '2018-03-06 15:57:45', '1', '管理员', '2018-03-06', '我要申请使用哦！', '1', '1', '16', '百度', '0');
INSERT INTO `xinhu_custappy` VALUES ('2', '7', '2018-03-06 17:42:35', '7', '赵子龙', '2018-03-06', '啦啦啦', '1', '1', '1', '信呼办公软件', '0');
INSERT INTO `xinhu_custappy` VALUES ('3', '7', '2018-03-07 12:28:31', '7', '赵子龙', '2018-03-07', 'enene', '5', '1', '15', '信呼', '0');
INSERT INTO `xinhu_custappy` VALUES ('4', '7', '2018-03-07 12:41:46', '7', '赵子龙', '2018-03-07', 'weewewwe', '5', '1', '15', '信呼', '0');

-- ----------------------------
-- Table structure for `xinhu_custfina`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_custfina`;
CREATE TABLE `xinhu_custfina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `htid` int(11) DEFAULT '0' COMMENT '合同ID',
  `htnum` varchar(20) DEFAULT NULL,
  `dt` date DEFAULT NULL COMMENT '所属日期',
  `uid` smallint(6) DEFAULT '0',
  `custid` smallint(6) DEFAULT '0',
  `custname` varchar(50) DEFAULT NULL COMMENT '客户名称',
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(10) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT '0.00',
  `type` tinyint(1) DEFAULT '0' COMMENT '0收款单,1付款单',
  `ispay` tinyint(1) DEFAULT '0' COMMENT '是否收款,付款',
  `paydt` datetime DEFAULT NULL COMMENT '收付款时间',
  `explain` varchar(200) DEFAULT NULL COMMENT '说明',
  `createdt` datetime DEFAULT NULL COMMENT '创建时间',
  `createname` varchar(10) DEFAULT NULL,
  `createid` smallint(6) DEFAULT '0',
  `ismove` tinyint(1) DEFAULT '0' COMMENT '是否转移的',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `htid` (`htid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='客户收付款';

-- ----------------------------
-- Records of xinhu_custfina
-- ----------------------------
INSERT INTO `xinhu_custfina` VALUES ('2', '0', 'CF-20190604-001', '2016-10-15', '1', '1', '信呼办公软件', '2016-10-15 18:40:20', '管理员', '2100.00', '1', '1', '2016-10-19 18:42:22', '服务器费用', '2016-10-15 18:40:20', '管理员', '1', '1', '0');
INSERT INTO `xinhu_custfina` VALUES ('3', '2', 'HT-002', '2016-10-15', '7', '3', '微软公司', '2016-10-15 19:10:04', '赵子龙', '20000.00', '0', '1', '2016-10-05 19:10:20', null, '2016-10-15 19:10:04', '管理员', '1', '1', '0');
INSERT INTO `xinhu_custfina` VALUES ('4', '3', 'HT-003', '2016-10-15', '1', '1', '信呼办公软件', '2016-10-19 16:05:06', '管理员', '25.00', '1', '1', '2017-06-26 15:54:00', null, '2016-10-15 19:36:08', '管理员', '1', '1', '0');
INSERT INTO `xinhu_custfina` VALUES ('5', '2', 'HT-002', '2016-10-16', '7', '3', '微软公司', '2016-10-16 13:32:11', '赵子龙', '30000.00', '0', '0', null, null, '2016-10-16 13:32:11', '管理员', '1', '0', '0');
INSERT INTO `xinhu_custfina` VALUES ('6', '4', 'HT-004', '2016-10-16', '6', '5', '腾讯科技', '2016-10-16 16:01:11', '张飞', '2300.00', '0', '0', null, null, '2016-10-16 16:01:11', '张飞', '6', '0', '0');
INSERT INTO `xinhu_custfina` VALUES ('7', '-11', 'CF-20190509-001', '2019-05-09', '1', '15', '信呼', '2019-05-12 00:00:18', '管理员', '12.00', '0', '1', '2019-05-12 00:00:44', null, '2019-05-12 00:00:18', '管理员', '1', '1', '0');
INSERT INTO `xinhu_custfina` VALUES ('8', '-11', 'CF-20190509-001', '2019-05-09', '1', '15', '信呼', '2019-05-12 00:02:44', '管理员', '12.00', '0', '1', '2019-05-15 00:02:00', null, '2019-05-12 00:02:44', '管理员', '1', '1', '0');
INSERT INTO `xinhu_custfina` VALUES ('9', '6', 'HT-006', '2018-09-15', '1', '1', '信呼办公软件', '2019-05-12 14:37:02', '管理员', '10.00', '0', '1', '2019-05-12 14:37:01', null, '2019-05-12 14:37:02', '管理员', '1', '0', '0');
INSERT INTO `xinhu_custfina` VALUES ('10', '6', 'HT-006', '2018-09-15', '1', '1', '信呼办公软件', '2019-05-12 14:37:23', '管理员', '48.00', '0', '0', null, null, '2019-05-12 14:37:23', '管理员', '1', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_customer`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_customer`;
CREATE TABLE `xinhu_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `type` varchar(20) DEFAULT NULL COMMENT '客户类型',
  `uid` smallint(6) DEFAULT '0' COMMENT '所属人',
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(10) DEFAULT NULL,
  `linkname` varchar(20) DEFAULT NULL COMMENT '联系人',
  `unitname` varchar(100) DEFAULT NULL COMMENT '单位名称',
  `laiyuan` varchar(20) DEFAULT NULL COMMENT '来源',
  `tel` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `explain` varchar(500) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `routeline` varchar(100) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL COMMENT '主页地址',
  `status` tinyint(4) DEFAULT '1' COMMENT '启用停用',
  `adddt` datetime DEFAULT NULL COMMENT '添加时间',
  `createname` varchar(10) DEFAULT NULL,
  `createid` smallint(6) DEFAULT '0',
  `shate` varchar(50) DEFAULT NULL COMMENT '共享给',
  `shateid` varchar(50) DEFAULT NULL,
  `isgys` tinyint(1) DEFAULT '0' COMMENT '是否供应商',
  `isstat` tinyint(1) DEFAULT '0' COMMENT '是否标*客户',
  `fzid` smallint(6) DEFAULT '0',
  `fzname` varchar(20) DEFAULT NULL COMMENT '客户负责人',
  `htshu` smallint(6) DEFAULT '0' COMMENT '合同数',
  `moneyz` decimal(10,2) DEFAULT '0.00' COMMENT '销售总额',
  `moneyd` decimal(10,2) DEFAULT '0.00' COMMENT '待收金额',
  `sheng` varchar(50) DEFAULT NULL COMMENT '所在省',
  `shi` varchar(50) DEFAULT NULL COMMENT '所在市',
  `isgh` tinyint(1) DEFAULT '0' COMMENT '是否放入公海',
  `lastdt` datetime DEFAULT NULL COMMENT '最后跟进时间',
  `shibieid` varchar(30) DEFAULT NULL COMMENT '纳税识别号',
  `openbank` varchar(100) DEFAULT NULL COMMENT '开户行',
  `cardid` varchar(50) DEFAULT NULL COMMENT '开户帐号',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `addresslatlng` varchar(200) DEFAULT NULL COMMENT '地址经纬度',
  `suoname` varchar(50) DEFAULT NULL COMMENT '所属人',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='客户';

-- ----------------------------
-- Records of xinhu_customer
-- ----------------------------
INSERT INTO `xinhu_customer` VALUES ('1', '信呼办公软件', '互联网', '1', '2021-04-08 10:26:24', '管理员', null, '厦门信呼科技有限公司', '网上开拓', '0592-123456', '18559769829', 'admin@rockoa.com', null, '福建厦门', null, null, '1', null, '管理员', '1', null, null, '0', '1', '0', null, '2', '58.00', '48.00', null, null, '0', '2019-12-16 11:53:05', null, null, null, '1', null, '管理员');
INSERT INTO `xinhu_customer` VALUES ('2', 'Rock', '软件', '1', '2016-12-16 10:45:25', '管理员', null, 'RockOA开发团队', '电话开拓', '0592-123456', '15888888', 'xinhu@rockoa.com', null, '厦门', null, null, '0', null, '管理员', '1', null, null, '1', '0', '0', null, '0', '0.00', '0.00', null, null, '0', null, null, null, null, '0', null, '管理员');
INSERT INTO `xinhu_customer` VALUES ('3', '微软公司', '软件', '3', '2016-10-17 09:24:22', '小乔', null, null, '网上开拓', null, null, null, null, '美国伦敦', null, null, '1', null, '管理员', '1', null, null, '1', '0', '0', null, '1', '50000.00', '30000.00', null, null, '0', null, null, null, null, '0', null, '小乔');
INSERT INTO `xinhu_customer` VALUES ('4', '谷歌公司', '个人', '1', '2017-11-11 12:02:10', '管理员', null, null, '网上开拓', null, null, null, null, '美国chrome', null, null, '1', null, '信呼客服', '6', null, null, '1', '0', '0', null, '1', '0.00', '0.00', null, null, '0', null, null, null, null, '0', null, '管理员');
INSERT INTO `xinhu_customer` VALUES ('5', '腾讯科技', null, '0', '2016-10-16 16:00:31', null, null, null, '网上开拓', null, null, null, null, '深圳', null, null, '1', '2016-10-16 16:00:31', '张飞', '6', null, null, '0', '0', '0', null, '1', '2300.00', '2300.00', null, null, '1', null, null, null, null, '0', null, null);
INSERT INTO `xinhu_customer` VALUES ('15', '信呼', '软件', '1', '2020-11-16 15:30:46', '管理员', '磐石', '厦门信呼软件开发工作室', '网上开拓', '0592-123456', '15800000000', 'admin@rockoa.com', null, '筼筜街道湖滨北路', null, null, '1', '2017-08-20 13:43:23', '管理员', '1', null, null, '0', '0', '0', null, '0', '24.00', '0.00', '福建省', '厦门市', '0', '2020-01-02 16:55:00', '92350206MA320FG000', '招行', '6552885927360000', '1', '24.47933726229204,118.0894660949707,13', '管理员');
INSERT INTO `xinhu_customer` VALUES ('16', '百度', '互联网', '1', '2020-11-07 10:51:10', '管理员', '李彦宏', '百度在线网络技术（北京）有限公司', '网上开拓', '010-123456', '15800000001', 'admin@rockoa.com', null, '北京软件园百度大厦', null, null, '1', '2017-08-20 13:43:23', '管理员', '1', null, null, '0', '0', '0', null, '1', '0.00', '0.00', '北京', '昌平区', '0', '2018-07-05 22:45:44', null, null, null, '1', null, '管理员');
INSERT INTO `xinhu_customer` VALUES ('17', '厦门市人民政府', '政府机构', '1', '2021-01-07 21:25:52', '管理员', null, null, '网上开拓', '010-123456', '15800000002', '1111@qq.com', null, '福建厦门', null, null, '1', '2017-08-20 13:43:23', '管理员', '1', null, null, '0', '0', '0', null, '0', '0.00', '0.00', '福建', '厦门', '0', null, null, null, null, '1', null, '管理员');

-- ----------------------------
-- Table structure for `xinhu_custplan`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_custplan`;
CREATE TABLE `xinhu_custplan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `custid` int(11) DEFAULT '0' COMMENT '客户Id',
  `custname` varchar(200) DEFAULT NULL COMMENT '客户名称',
  `optid` int(11) DEFAULT '0',
  `optname` varchar(50) DEFAULT NULL COMMENT '跟进人',
  `optdt` datetime DEFAULT NULL,
  `plandt` datetime DEFAULT NULL COMMENT '计划时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态0计划,1完成,5取消',
  `explain` varchar(500) DEFAULT NULL,
  `gentype` varchar(50) DEFAULT NULL COMMENT '跟进方式',
  `uid` int(11) DEFAULT '0',
  `findt` datetime DEFAULT NULL COMMENT '完成时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='跟进计划';

-- ----------------------------
-- Records of xinhu_custplan
-- ----------------------------
INSERT INTO `xinhu_custplan` VALUES ('1', '1', '15', '信呼', '1', '管理员', '2020-01-02 18:55:50', '2020-01-02 19:55:00', '0', null, '上门拜访', '1', null);

-- ----------------------------
-- Table structure for `xinhu_custract`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_custract`;
CREATE TABLE `xinhu_custract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `num` varchar(30) DEFAULT NULL COMMENT '合同编号',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `custid` int(11) DEFAULT '0',
  `custname` varchar(255) DEFAULT NULL COMMENT '客户名称',
  `linkman` varchar(20) DEFAULT NULL COMMENT '客户联系人',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '合同金额',
  `moneys` decimal(10,2) DEFAULT '0.00' COMMENT '待收金额',
  `startdt` date DEFAULT NULL COMMENT '生效日期',
  `enddt` date DEFAULT NULL COMMENT '截止日期',
  `content` text COMMENT '合同内容',
  `saleid` smallint(6) DEFAULT '0' COMMENT '销售机会Id',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `signdt` date DEFAULT NULL COMMENT '签约日期',
  `type` tinyint(1) DEFAULT '0' COMMENT '0收款合同，1付款合同',
  `ispay` tinyint(1) DEFAULT '0' COMMENT '0待,1已完成,2部分',
  `isover` tinyint(1) DEFAULT '0' COMMENT '是否已全部创建收付款单',
  `createname` varchar(20) DEFAULT NULL,
  `createid` smallint(6) DEFAULT '0',
  `xiaoid` int(11) DEFAULT '0' COMMENT '对应销售goodm.id',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `fenlei` varchar(50) DEFAULT NULL COMMENT '合同分类',
  `htfileid` varchar(50) DEFAULT NULL COMMENT '合同文件',
  `isqr` tinyint(1) DEFAULT '0' COMMENT '客户是否确认',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='客户合同';

-- ----------------------------
-- Records of xinhu_custract
-- ----------------------------
INSERT INTO `xinhu_custract` VALUES ('6', '1', 'HT-006', '2019-05-10 14:18:26', '管理员', '2018-09-15', null, '1', '1', '信呼办公软件', null, '58.00', '48.00', '2019-05-10', '2020-05-10', null, '4', '1', '2018-09-15', '0', '2', '1', '管理员', '1', '11', '0', null, '2', '0');
INSERT INTO `xinhu_custract` VALUES ('2', '7', 'HT-002', '2016-10-13 20:18:04', '赵子龙', '2016-10-13', null, '1', '3', '微软公司', null, '50000.00', '30000.00', '2016-10-01', '2017-10-31', null, '2', '1', '2016-10-13', '0', '2', '1', '管理员', '1', '0', '0', null, null, '0');
INSERT INTO `xinhu_custract` VALUES ('3', '1', 'HT-003', '2016-10-19 16:02:53', '管理员', '2016-10-15', null, '1', '1', '信呼办公软件', null, '25.00', '0.00', '2016-10-01', '2016-10-31', '采购了啥！', null, '1', '2016-10-15', '1', '1', '1', '管理员', '1', '0', '0', null, null, '0');
INSERT INTO `xinhu_custract` VALUES ('4', '6', 'HT-004', '2016-10-16 16:01:06', '张飞', '2016-10-16', null, '1', '5', '腾讯科技', null, '2300.00', '2300.00', '2015-10-01', '2017-10-31', null, null, '1', '2016-10-16', '0', '0', '1', '张飞', '6', '0', '0', null, null, '0');
INSERT INTO `xinhu_custract` VALUES ('5', '1', 'HT-005', '2019-05-17 14:38:15', '管理员', '2017-06-26', null, '1', '4', '谷歌公司', null, '2351.00', '2351.00', '2017-06-01', '2017-07-01', null, null, '1', '2017-06-26', '0', '0', '0', '管理员', '1', '0', '0', null, null, '0');
INSERT INTO `xinhu_custract` VALUES ('7', '1', 'HT-007', '2020-12-25 11:40:55', '管理员', '2020-12-24', null, '1', '16', '百度', null, '6900.00', '6900.00', '2021-01-01', '2021-12-31', '给百度开发一个即时通讯软件。', null, '1', '2020-12-24', '0', '0', '0', '管理员', '1', '0', '1', '普通合同', '13', '0');

-- ----------------------------
-- Table structure for `xinhu_custsale`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_custsale`;
CREATE TABLE `xinhu_custsale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custid` int(11) DEFAULT '0',
  `custname` varchar(50) DEFAULT NULL,
  `uid` smallint(6) DEFAULT '0',
  `optname` varchar(10) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `state` tinyint(4) DEFAULT '0' COMMENT '状态',
  `explain` varchar(500) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT '0.00',
  `applydt` date DEFAULT NULL,
  `dealdt` datetime DEFAULT NULL COMMENT '成交时间',
  `adddt` datetime DEFAULT NULL,
  `laiyuan` varchar(20) DEFAULT NULL COMMENT '销售来源',
  `createid` smallint(6) DEFAULT '0',
  `createname` varchar(20) DEFAULT NULL,
  `htid` smallint(6) DEFAULT '0' COMMENT '合同ID',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='销售机会';

-- ----------------------------
-- Records of xinhu_custsale
-- ----------------------------
INSERT INTO `xinhu_custsale` VALUES ('1', '1', '信呼办公软件', '3', '小乔', '2016-07-17 22:12:06', '0', '说要买模块定制哦！', '50.00', '2016-07-17', null, '2016-07-17 22:12:06', null, '3', '小乔', '0', '0');
INSERT INTO `xinhu_custsale` VALUES ('2', '3', '微软公司', '7', '赵子龙', '2016-07-18 10:38:21', '1', '说要收购软件信呼，呵呵！', '50000.00', '2016-07-18', '2016-07-18 14:02:15', '2016-07-18 10:38:21', '主动来访', '1', '管理员', '2', '0');
INSERT INTO `xinhu_custsale` VALUES ('3', '4', '谷歌公司', '8', '信呼客服', '2016-07-18 14:13:17', '1', '可能买安卓手机一部', '500.00', '2016-07-18', '2016-07-18 14:13:39', '2016-07-18 14:13:17', '主动来访', '8', '信呼客服', '0', '0');
INSERT INTO `xinhu_custsale` VALUES ('4', '1', '信呼办公软件', '1', '管理员', '2016-07-18 16:33:17', '1', '呵呵', '58.00', '2016-07-18', '2016-07-29 13:14:41', '2016-07-18 16:33:17', '电话销售', '1', '管理员', '6', '0');
INSERT INTO `xinhu_custsale` VALUES ('5', '3', '微软公司', '4', '大乔', '2016-07-18 20:53:18', '0', '梦梦搭', '511.00', '2016-07-18', null, '2016-07-18 20:53:18', '网上联系', '4', '大乔', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_daily`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_daily`;
CREATE TABLE `xinhu_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` date DEFAULT NULL COMMENT '日期',
  `content` varchar(4000) DEFAULT NULL COMMENT '内容',
  `adddt` datetime DEFAULT NULL COMMENT '新增时间',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `uid` int(11) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '姓名',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型@0|日报,1|周报,2|月报,3|年报',
  `plan` varchar(2000) DEFAULT NULL COMMENT '明日计划',
  `status` tinyint(1) DEFAULT '0',
  `enddt` date DEFAULT NULL,
  `optid` int(11) DEFAULT NULL,
  `mark` smallint(6) DEFAULT '0' COMMENT '得分',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `mtplid` int(11) DEFAULT '0' COMMENT '对应多模版flow_modetpl.id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='日报';

-- ----------------------------
-- Records of xinhu_daily
-- ----------------------------
INSERT INTO `xinhu_daily` VALUES ('2', '2016-04-08', '1、核心模块流程添加转办给其他人功能。\n2、REIM开发添加手机端提醒。', '2016-04-08 23:01:24', '2016-04-08 23:01:24', '1', '管理员', '0', null, '1', null, '1', '0', '0', '0');
INSERT INTO `xinhu_daily` VALUES ('38', '2021-08-21', '今天天不错。', '2021-08-21 13:15:20', '2021-08-21 13:15:20', '1', '管理员', '0', null, '1', null, '1', '0', '1', '0');
INSERT INTO `xinhu_daily` VALUES ('6', '2016-06-17', '1、救了世子和刘备夫人，不慎他跳井了。\n2、被曹操追上，死里逃生啊。', null, '2016-06-17 10:55:07', '7', '赵子龙', '0', null, '1', null, '7', '0', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_dailyfx`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_dailyfx`;
CREATE TABLE `xinhu_dailyfx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `month` varchar(10) DEFAULT NULL COMMENT '月份',
  `optdt` datetime DEFAULT NULL COMMENT '分析时间',
  `day1` tinyint(1) DEFAULT '0' COMMENT '0未写,1已写,2请假,3休息日',
  `day2` tinyint(1) DEFAULT '0',
  `day3` tinyint(1) DEFAULT '0',
  `day4` tinyint(1) DEFAULT '0',
  `day5` tinyint(1) DEFAULT '0',
  `day6` tinyint(1) DEFAULT '0',
  `day7` tinyint(1) DEFAULT '0',
  `day8` tinyint(1) DEFAULT '0',
  `day9` tinyint(1) DEFAULT '0',
  `day10` tinyint(1) DEFAULT '0',
  `day11` tinyint(1) DEFAULT '0',
  `day12` tinyint(1) DEFAULT '0',
  `day13` tinyint(1) DEFAULT '0',
  `day14` tinyint(1) DEFAULT '0',
  `day15` tinyint(1) DEFAULT '0',
  `day16` tinyint(1) DEFAULT '0',
  `day17` tinyint(1) DEFAULT '0',
  `day18` tinyint(1) DEFAULT '0',
  `day19` tinyint(1) DEFAULT '0',
  `day20` tinyint(1) DEFAULT '0',
  `day21` tinyint(1) DEFAULT '0',
  `day22` tinyint(1) DEFAULT '0',
  `day23` tinyint(1) DEFAULT '0',
  `day24` tinyint(1) DEFAULT '0',
  `day25` tinyint(1) DEFAULT '0',
  `day26` tinyint(1) DEFAULT '0',
  `day27` tinyint(1) DEFAULT '0',
  `day28` tinyint(1) DEFAULT '0',
  `day29` tinyint(1) DEFAULT '0',
  `day30` tinyint(1) DEFAULT '0',
  `day31` tinyint(1) DEFAULT '0',
  `totaly` smallint(6) DEFAULT '0' COMMENT '应写次数',
  `totalx` smallint(6) DEFAULT '0' COMMENT '已写次数',
  `totalw` smallint(6) DEFAULT '0' COMMENT '未写次数',
  `explain` varchar(200) DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`month`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='日报分析统计表';

-- ----------------------------
-- Records of xinhu_dailyfx
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_demo`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_demo`;
CREATE TABLE `xinhu_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sheng` varchar(50) DEFAULT NULL COMMENT '省',
  `shi` varchar(50) DEFAULT NULL COMMENT '市',
  `xian` varchar(50) DEFAULT NULL COMMENT '县(区)',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `tanxuan` varchar(50) DEFAULT NULL COMMENT '弹出下来单选',
  `tanxuancheck` varchar(50) DEFAULT NULL COMMENT '弹框下拉多选',
  `upfile1` varchar(50) DEFAULT NULL COMMENT '文件上传1',
  `upfile2` varchar(50) DEFAULT NULL COMMENT '文件上传2',
  `testfirs` varchar(50) DEFAULT NULL COMMENT '测试字段',
  `custname` varchar(100) DEFAULT NULL COMMENT '客户',
  `custid` int(11) DEFAULT '0' COMMENT '客户id',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `tanxuanid` smallint(6) DEFAULT NULL COMMENT '单选弹出选择id',
  `fengmian` varchar(200) DEFAULT NULL COMMENT '封面图片',
  `num` varchar(50) DEFAULT NULL COMMENT '编号',
  `mid` int(11) DEFAULT '0',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `sort` int(11) DEFAULT '0' COMMENT '排序号',
  `sslx` tinyint(1) DEFAULT '0' COMMENT '0多行子表1,1多行子表2',
  `htmlcont` text COMMENT 'html编辑器',
  `xuanren` varchar(50) DEFAULT NULL COMMENT '选择人员',
  `xuanrenid` varchar(50) DEFAULT NULL COMMENT '选择人员的ID',
  `duoxuanla` varchar(50) DEFAULT NULL COMMENT '多选字段2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='演示测试模块';

-- ----------------------------
-- Records of xinhu_demo
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_dept`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_dept`;
CREATE TABLE `xinhu_dept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` varchar(30) DEFAULT NULL COMMENT '编号',
  `name` varchar(100) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL,
  `headman` varchar(50) DEFAULT NULL COMMENT '负责人',
  `headid` varchar(50) DEFAULT NULL,
  `companyid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='组织结构部门';

-- ----------------------------
-- Records of xinhu_dept
-- ----------------------------
INSERT INTO `xinhu_dept` VALUES ('1', null, '信呼开发团队', '0', '0', null, null, null, '0');
INSERT INTO `xinhu_dept` VALUES ('2', 'dev', '开发部', '1', '1', null, '管理员', '1', '0');
INSERT INTO `xinhu_dept` VALUES ('3', null, '财务部', '1', '2', null, null, null, '0');
INSERT INTO `xinhu_dept` VALUES ('4', null, '行政人事部', '1', '3', null, '大乔', '4', '2');
INSERT INTO `xinhu_dept` VALUES ('5', null, '管理层', '1', '0', null, '磐石', '5', '1');

-- ----------------------------
-- Table structure for `xinhu_editrecord`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_editrecord`;
CREATE TABLE `xinhu_editrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fieldsname` varchar(50) DEFAULT NULL COMMENT '字段名称',
  `oldval` text COMMENT '原来值',
  `newval` text COMMENT '新值',
  `table` varchar(30) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT NULL COMMENT '操作人Id',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `editci` smallint(6) DEFAULT '0' COMMENT '第几次修改记录',
  PRIMARY KEY (`id`),
  KEY `table` (`table`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='修改记录表';

-- ----------------------------
-- Records of xinhu_editrecord
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_emailm`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_emailm`;
CREATE TABLE `xinhu_emailm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0' COMMENT '用户iD',
  `title` varchar(220) DEFAULT NULL COMMENT '主题',
  `content` longtext COMMENT '邮件内容',
  `sendid` smallint(6) DEFAULT NULL,
  `sendname` varchar(100) DEFAULT NULL COMMENT '发送人',
  `senddt` datetime DEFAULT NULL COMMENT '发送时间',
  `receid` varchar(2000) DEFAULT NULL,
  `recename` varchar(4000) DEFAULT NULL COMMENT '接收人',
  `isturn` tinyint(1) DEFAULT '0' COMMENT '@0|草稿,1|已发送',
  `hid` smallint(6) DEFAULT '0' COMMENT '@回复id',
  `isfile` tinyint(1) DEFAULT '0' COMMENT '@是否有附件',
  `applydt` date DEFAULT NULL,
  `message_id` varchar(100) DEFAULT NULL COMMENT '邮件Id',
  `fromemail` varchar(500) DEFAULT NULL COMMENT '发送人邮件',
  `toemail` varchar(500) DEFAULT NULL COMMENT '发给邮件人',
  `reply_toemail` varchar(500) DEFAULT NULL COMMENT '回复邮件',
  `ccemail` varchar(500) DEFAULT NULL COMMENT '抄送给',
  `size` int(11) DEFAULT '0' COMMENT '邮件大小',
  `ccname` varchar(100) DEFAULT NULL COMMENT '抄送给',
  `ccid` varchar(100) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '0内部邮件,1用邮件外发',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `numoi` int(11) DEFAULT '0',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `outzt` tinyint(1) DEFAULT '1' COMMENT '外发状态',
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件主表';

-- ----------------------------
-- Records of xinhu_emailm
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_emails`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_emails`;
CREATE TABLE `xinhu_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0',
  `uid` smallint(6) DEFAULT '0',
  `zt` tinyint(1) DEFAULT '0' COMMENT '@0|未读,1|已读',
  `type` tinyint(1) DEFAULT '0' COMMENT '0接收,1抄送,2发送者',
  `ishui` tinyint(1) DEFAULT '0' COMMENT '是否回复',
  `isdel` tinyint(1) DEFAULT '0' COMMENT '@0|未删,1|已删',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `email` varchar(50) DEFAULT NULL,
  `personal` varchar(20) DEFAULT NULL COMMENT '人员',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`,`email`,`type`),
  KEY `mid_2` (`mid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件子表';

-- ----------------------------
-- Records of xinhu_emails
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_email_cont`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_email_cont`;
CREATE TABLE `xinhu_email_cont` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `body` longtext COMMENT '内容',
  `receid` varchar(500) DEFAULT '0',
  `recename` varchar(500) DEFAULT NULL COMMENT '接收人',
  `receemail` varchar(500) DEFAULT NULL COMMENT '接收邮件',
  `optdt` datetime DEFAULT NULL,
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '添加人',
  `status` tinyint(1) DEFAULT '0' COMMENT '0待发送,1成功,2失败',
  `senddt` datetime DEFAULT NULL COMMENT '发送时间',
  `ccname` varchar(500) DEFAULT NULL COMMENT '抄送给',
  `ccemail` varchar(500) DEFAULT NULL COMMENT '抄送邮件',
  `attachpath` varchar(2000) DEFAULT NULL COMMENT '附件路径',
  `attachname` varchar(2000) DEFAULT NULL COMMENT '附件名称',
  `mid` int(11) DEFAULT '0' COMMENT 'emailm.id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统发送邮件内容';

-- ----------------------------
-- Records of xinhu_email_cont
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_file`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_file`;
CREATE TABLE `xinhu_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filenum` varchar(20) DEFAULT NULL COMMENT '文件编号',
  `valid` tinyint(4) DEFAULT '1',
  `filename` varchar(200) DEFAULT NULL,
  `filetype` varchar(50) DEFAULT NULL,
  `fileext` varchar(20) DEFAULT NULL,
  `filesize` int(11) DEFAULT NULL,
  `filesizecn` varchar(30) DEFAULT NULL,
  `filepath` varchar(100) DEFAULT NULL,
  `thumbpath` varchar(100) DEFAULT NULL,
  `optid` int(11) DEFAULT NULL,
  `optname` varchar(50) DEFAULT NULL COMMENT '上传者',
  `adddt` datetime DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `web` varchar(300) DEFAULT NULL,
  `mtype` varchar(50) DEFAULT NULL COMMENT '对应类型',
  `mid` int(11) DEFAULT '0' COMMENT '管理id',
  `downci` int(11) DEFAULT '0' COMMENT '下载次数',
  `keyoi` varchar(20) DEFAULT NULL COMMENT '对应序号邮件附件中用到',
  `pdfpath` varchar(100) DEFAULT NULL COMMENT '转pdf后路径',
  `oid` int(11) DEFAULT '0' COMMENT '旧ID',
  `mknum` varchar(30) DEFAULT NULL COMMENT '模块编号',
  `comid` smallint(6) DEFAULT '0' COMMENT '所属单位',
  `thumbplat` varchar(100) DEFAULT NULL COMMENT '上传到平台上缩略图',
  `onlynum` varchar(50) DEFAULT NULL,
  `filepathout` varchar(500) DEFAULT NULL COMMENT '远程路径',
  `explain` varchar(200) DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='上传文件记录表';

-- ----------------------------
-- Records of xinhu_file
-- ----------------------------
INSERT INTO `xinhu_file` VALUES ('1', null, '1', '系统logo.png', null, 'png', '2000', '2.36 KB', 'images/logo.png', 'images/logo.png', '1', '管理员', '2016-11-23 22:26:21', '127.0.0.1', 'Chrome', 'word', '1', '17', '0', null, '0', null, '0', null, 'e8a64cad974a73c79b6b0f19fc00f6bc', null, null);
INSERT INTO `xinhu_file` VALUES ('2', null, '1', 'daiban.png', null, 'png', '2000', '2.21 KB', 'images/daiban.png', 'images/daiban.png', '1', '管理员', '2016-11-23 22:26:26', '127.0.0.1', 'Chrome', 'word', '2', '18', '0', null, '0', null, '0', null, null, null, null);
INSERT INTO `xinhu_file` VALUES ('3', '', '1', '信呼开发团队介绍.pptx', 'application/msword', 'pptx', '37837', '36.95 KB', 'http://www.rockoa.com/upload/upgfile/xinhuppt.pptx', '', '1', '管理员', '2019-03-09 19:20:41', '127.0.0.1', 'Chrome', 'wordxie', '1', '16', '', '', '0', 'wordxie', '0', '', '4a79f31d1eeec342e97c50093eeda803', null, null);
INSERT INTO `xinhu_file` VALUES ('4', '', '1', '信呼使用文档.docx', 'application/msword', 'docx', '10840', '10.59 KB', 'http://www.rockoa.com/upload/upgfile/xinhuuse.docx', '', '1', '管理员', '2019-03-09 20:45:33', '127.0.0.1', 'Chrome', 'wordxie', '2', '6', '', '', '0', 'wordxie', '0', '', '044de19ffa3b1d74fe5db901c587c06f', null, null);
INSERT INTO `xinhu_file` VALUES ('5', null, '1', '员工劳动合同.docx', 'application/msword', 'docx', '1000', '10KB', 'http://www.rockoa.com/upload/upgfile/xinhuhetong.docx', null, '1', '管理员', '2020-10-28 19:44:34', '127.0.0.1', 'Chrome', 'wordxie', '4', '0', null, null, '0', 'wordxie', '0', null, 'eb043a7b7067f10735d3a0bf256a073f', null, null);
INSERT INTO `xinhu_file` VALUES ('6', null, '1', '标准红头文件模版.docx', 'application/msword', 'docx', '17979', '17.56 KB', 'http://www.rockoa.com/upload/upgfile/hongwj.docx', null, '1', '管理员', '2020-10-31 22:16:15', '127.0.0.1', 'Chrome', 'wordxie', '5', '0', null, '', '0', 'wordxie', '1', null, 'a192b95275b47cf467f08262a28cc9db', null, null);
INSERT INTO `xinhu_file` VALUES ('7', null, '1', '客户合同模版.docx', 'application/msword', 'docx', '24064', '23.50 KB', 'http://www.rockoa.com/upload/upgfile/custhetong.docx', null, '1', '管理员', '2020-12-18 12:21:53', '127.0.0.1', 'Chrome', 'wordxie', '6', '0', null, '', '0', 'wordxie', '1', null, 'd49da14ee17d2e8dfac481ca827844ad', null, null);

-- ----------------------------
-- Table structure for `xinhu_files`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_files`;
CREATE TABLE `xinhu_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `optname` varchar(50) DEFAULT NULL COMMENT '操作人',
  `fileid` int(11) DEFAULT '0' COMMENT '关联file.id',
  `optid` int(11) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '0预览,1下载',
  `ip` varchar(100) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fileid` (`fileid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件记录下载和预览';

-- ----------------------------
-- Records of xinhu_files
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_fininfom`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_fininfom`;
CREATE TABLE `xinhu_fininfom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) DEFAULT '0' COMMENT '类型@0|报销单,1|出差报销,2|借款单,3|还款单,4|付款申请,5|开票申请',
  `uid` int(11) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `moneycn` varchar(100) DEFAULT NULL COMMENT '大写金额',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` int(11) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '0',
  `isturn` tinyint(1) DEFAULT '0',
  `bills` smallint(6) DEFAULT NULL COMMENT '附单据(张)',
  `paytype` varchar(20) DEFAULT NULL COMMENT '付款方式',
  `fullname` varchar(100) DEFAULT NULL COMMENT '收款人全称',
  `cardid` varchar(50) DEFAULT NULL COMMENT '收款帐号',
  `openbank` varchar(50) DEFAULT NULL COMMENT '开户行',
  `purpose` varchar(100) DEFAULT NULL COMMENT '用途',
  `purresult` varchar(100) DEFAULT NULL,
  `paydt` date DEFAULT NULL COMMENT '付款日期',
  `num` varchar(30) DEFAULT NULL COMMENT '合同号/订单号',
  `name` varchar(50) DEFAULT NULL COMMENT '开票名称',
  `shibieid` varchar(50) DEFAULT NULL COMMENT '纳税识别号',
  `address` varchar(100) DEFAULT NULL COMMENT '公司地址',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话',
  `custid` int(11) DEFAULT '0' COMMENT '对应客户ID',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='费用报销';

-- ----------------------------
-- Records of xinhu_fininfom
-- ----------------------------
INSERT INTO `xinhu_fininfom` VALUES ('1', '0', '1', '12.00', '壹拾贰元整', '2016-05-05 20:19:42', '1', '管理员', '2016-05-05', null, '1', '1', '0', '支付宝', 'RockOA开发团队', 'admin@rockoa.com', '阿里巴巴', null, null, null, null, null, null, null, null, '0', '0');
INSERT INTO `xinhu_fininfom` VALUES ('3', '1', '1', '500.00', '伍佰元整', '2016-09-03 11:43:36', '1', '管理员', '2016-05-05', null, '1', '1', '0', '支付宝', 'RockOA开发团队', 'admin@rockoa.com', '阿里巴巴', '北京会见马云', '聊的不错', null, null, null, null, null, null, '0', '0');
INSERT INTO `xinhu_fininfom` VALUES ('4', '2', '1', '500.00', '伍佰元整', '2016-09-03 15:43:28', '1', '管理员', '2016-05-08', '启动资金', '1', '1', '1', '支付宝', 'RockOA开发团队', 'admin@rockoa.com', '阿里巴巴公司', '买房', null, null, null, null, null, null, null, '0', '0');
INSERT INTO `xinhu_fininfom` VALUES ('5', '0', '1', '101.00', '壹佰零壹元整', '2016-09-02 22:54:13', '1', '管理员', '2016-09-02', null, '0', '1', '0', '支付宝', '信呼', 'admin@rockoa.com', '支付宝', null, null, null, null, null, null, null, null, '0', '0');
INSERT INTO `xinhu_fininfom` VALUES ('6', '2', '1', '35000.00', '叁万伍仟元整', '2016-09-03 16:52:52', '1', '管理员', '2016-09-03', null, '1', '1', null, '支付宝', 'RockOA开发团队', 'admin@rockoa.com', '阿里巴巴', '买车', null, null, null, null, null, null, null, '0', '0');
INSERT INTO `xinhu_fininfom` VALUES ('7', '3', '1', '35000.00', '叁万伍仟元整', '2016-09-03 17:17:44', '1', '管理员', '2016-09-03', null, '1', '1', null, '支付宝', 'RockOA开发团队', 'admin@rockoa.com', null, null, null, null, null, null, null, null, null, '0', '0');
INSERT INTO `xinhu_fininfom` VALUES ('8', '0', '1', '63.00', '陆拾叁元整', '2017-05-14 18:50:58', '1', '管理员', '2016-09-28', null, '1', '1', '0', '支付宝', '信呼开发团队', 'admin@rockoa.com', '阿里巴巴', null, null, null, null, null, null, null, null, '0', '0');
INSERT INTO `xinhu_fininfom` VALUES ('9', '0', '4', '560.00', '伍佰陆拾元整', '2020-06-15 20:29:43', '4', '大乔', '2017-05-08', null, '1', '1', '0', '现金', '大乔公司', '无', '无', null, null, null, null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_fininfom` VALUES ('10', '4', '1', '50.00', null, '2018-10-22 21:46:31', '1', '管理员', '2017-08-07', '付钱申请', '1', '1', null, '银行转账', '厦门信呼软件开发工作室', '6552885927360000', '招行', null, null, '2017-08-07', 'abx123', null, null, null, null, '15', '0');
INSERT INTO `xinhu_fininfom` VALUES ('12', '5', '1', '50.00', null, '2018-10-22 20:24:32', '1', '管理员', '2018-10-22', null, '0', '1', null, '增值税普通发票', '厦门信呼软件开发工作室', '6552885927360000', '招行', null, null, '2018-10-22', null, null, '92350206MA320FG000', '福建厦门思明区软件园', '0592-123456', '15', '0');
INSERT INTO `xinhu_fininfom` VALUES ('13', '6', '1', '1000.00', '壹仟元整', '2020-09-25 12:01:05', '1', '管理员', '2020-09-25', null, '1', '1', '2020', null, '', null, null, '2020年', null, null, null, '开发部', null, null, null, '2', '1');
INSERT INTO `xinhu_fininfom` VALUES ('14', '6', '1', '122.00', '壹佰贰拾贰元整', '2020-09-25 15:03:09', '1', '管理员', '2020-09-25', null, '1', '1', '2020', null, '', null, null, null, null, null, null, '管理层', null, null, null, '5', '1');
INSERT INTO `xinhu_fininfom` VALUES ('15', '7', '1', '500.00', '伍佰元整', '2020-09-25 18:58:55', '1', '管理员', '2020-09-25', null, '1', '1', '1', '现金', '信呼团队', '4565689', '无', '购买服务器备用', null, null, '管理员', '开发部', null, null, null, '2', '1');
INSERT INTO `xinhu_fininfom` VALUES ('16', '8', '1', '22.00', '贰拾贰元整', '2020-09-25 20:25:35', '1', '管理员', '2020-09-25', null, '1', '1', null, null, null, null, null, null, null, null, null, '开发部', null, null, null, '2', '1');
INSERT INTO `xinhu_fininfom` VALUES ('17', '7', '1', '233.00', '贰佰叁拾叁元整', '2020-09-25 21:24:23', '1', '管理员', '2020-09-25', null, '1', '1', '1', '现金', '信呼团队', '4565689', '无', null, null, null, '管理员', '财务部', null, null, null, '3', '1');

-- ----------------------------
-- Table structure for `xinhu_fininfos`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_fininfos`;
CREATE TABLE `xinhu_fininfos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表fininfom.id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `sdt` date DEFAULT NULL COMMENT '发生日期',
  `name` varchar(20) DEFAULT NULL COMMENT '所属项目',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `sm` varchar(100) DEFAULT NULL COMMENT '说明',
  `didian` varchar(50) DEFAULT NULL COMMENT '发生地点',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='费用报销子表';

-- ----------------------------
-- Records of xinhu_fininfos
-- ----------------------------
INSERT INTO `xinhu_fininfos` VALUES ('1', '5', '0', '2016-09-02', '市内交通费', '12.00', null, null, '0');
INSERT INTO `xinhu_fininfos` VALUES ('2', '5', '1', '2016-09-02', '停车费', '89.00', null, null, '0');
INSERT INTO `xinhu_fininfos` VALUES ('3', '4', '0', '2016-05-05', '电话费', '500.00', '国外电话费贵啊', null, '0');
INSERT INTO `xinhu_fininfos` VALUES ('4', '3', '0', '2016-09-01', '电话费', '45.00', null, '北京', '0');
INSERT INTO `xinhu_fininfos` VALUES ('5', '3', '1', '2016-09-01', '快递费', '455.00', null, '北京', '0');
INSERT INTO `xinhu_fininfos` VALUES ('6', '8', '0', '2016-09-01', '电话费', '30.00', null, null, '0');
INSERT INTO `xinhu_fininfos` VALUES ('7', '8', '1', '2017-01-05', '电话费', '33.00', null, null, '0');
INSERT INTO `xinhu_fininfos` VALUES ('8', '9', '0', '2017-05-01', '市内交通费', '50.00', null, null, '1');
INSERT INTO `xinhu_fininfos` VALUES ('9', '9', '1', '2017-05-08', '停车费', '500.00', null, null, '1');
INSERT INTO `xinhu_fininfos` VALUES ('10', '9', '2', '2020-06-15', '快递费', '10.00', '发票快递', null, '1');
INSERT INTO `xinhu_fininfos` VALUES ('11', '13', '0', null, '服务器购买', '500.00', null, null, '1');
INSERT INTO `xinhu_fininfos` VALUES ('12', '13', '1', null, '域名续费', '500.00', null, null, '1');
INSERT INTO `xinhu_fininfos` VALUES ('13', '14', '0', null, '出差', '122.00', null, null, '1');
INSERT INTO `xinhu_fininfos` VALUES ('14', '16', '0', '2020-09-25', '出差', '22.00', null, null, '1');

-- ----------------------------
-- Table structure for `xinhu_finpiao`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_finpiao`;
CREATE TABLE `xinhu_finpiao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `optname` varchar(50) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `optid` int(11) DEFAULT '0',
  `custid` int(11) DEFAULT '0' COMMENT '对应客户ID',
  `maicustname` varchar(100) DEFAULT NULL COMMENT '购买方',
  `maicustid` int(11) DEFAULT '0' COMMENT '购买方Id',
  `type` tinyint(1) DEFAULT '0' COMMENT '0开,1收到',
  `custname` varchar(100) DEFAULT NULL COMMENT '客户名称',
  `daima` varchar(50) DEFAULT NULL COMMENT '发票代码',
  `haoma` varchar(50) DEFAULT NULL COMMENT '发票号码',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `opendt` date DEFAULT NULL COMMENT '开票日期',
  `ptype` varchar(50) DEFAULT NULL COMMENT '发票类型',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `kainame` varchar(50) DEFAULT NULL COMMENT '开票人',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `opendt` (`opendt`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='发票管理表';

-- ----------------------------
-- Records of xinhu_finpiao
-- ----------------------------
INSERT INTO `xinhu_finpiao` VALUES ('1', '管理员', '2018-11-19 13:38:07', '1', '16', '厦门信呼软件开发工作室', '15', '1', '百度在线网络技术（北京）有限公司', null, null, '688.00', '2018-10-18', '增值税普通发票', '1', '位置', '云服务器', '0');
INSERT INTO `xinhu_finpiao` VALUES ('2', '管理员', '2020-11-07 11:53:05', '1', '15', '百度在线网络技术（北京）有限公司', '16', '0', '厦门信呼软件开发工作室', '0002', '12356', '2998.00', '2018-11-19', '增值税普通发票', '1', '磐石', '信呼OA系统授权', '1');

-- ----------------------------
-- Table structure for `xinhu_flow_bill`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_bill`;
CREATE TABLE `xinhu_flow_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sericnum` varchar(50) DEFAULT NULL COMMENT '单号',
  `table` varchar(50) DEFAULT NULL COMMENT '对应表',
  `mid` int(11) DEFAULT NULL COMMENT '主id',
  `modeid` smallint(6) DEFAULT '0' COMMENT '模块id',
  `modename` varchar(20) DEFAULT NULL COMMENT '模块名称',
  `uname` varchar(20) DEFAULT NULL COMMENT '申请人姓名',
  `uid` smallint(6) DEFAULT NULL COMMENT '用户id',
  `udeptid` int(11) DEFAULT '0' COMMENT '部门0为主部门',
  `udeptname` varchar(30) DEFAULT NULL COMMENT '申请人部门',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0' COMMENT '操作人Id',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `allcheckid` varchar(500) DEFAULT NULL COMMENT '@所有审核人',
  `isdel` tinyint(4) DEFAULT '0' COMMENT '是否删除',
  `nstatus` tinyint(1) DEFAULT '0' COMMENT '当前状态值',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `nstatustext` varchar(100) DEFAULT NULL COMMENT '当前状态',
  `status` tinyint(1) DEFAULT '0',
  `nowcourseid` int(11) DEFAULT '0' COMMENT '当前步骤Id',
  `nowcheckid` varchar(500) DEFAULT NULL COMMENT '当前审核人id',
  `nowcheckname` varchar(500) DEFAULT NULL COMMENT '当前审核人',
  `checksm` varchar(200) DEFAULT NULL COMMENT '最后审核说明',
  `updt` datetime DEFAULT NULL COMMENT '最后更新时间',
  `createdt` datetime DEFAULT NULL COMMENT '创建时间',
  `tuiid` int(11) DEFAULT '0' COMMENT '退回flow_log.Id',
  `isturn` tinyint(1) DEFAULT '0' COMMENT '是否已提交了',
  PRIMARY KEY (`id`),
  KEY `sericnum` (`sericnum`),
  KEY `table` (`table`,`mid`) USING BTREE,
  KEY `status` (`status`),
  KEY `modeid` (`modeid`)
) ENGINE=MyISAM AUTO_INCREMENT=290 DEFAULT CHARSET=utf8 COMMENT='流程单据';

-- ----------------------------
-- Records of xinhu_flow_bill
-- ----------------------------
INSERT INTO `xinhu_flow_bill` VALUES ('1', 'KA-20160729-0001', 'kqinfo', '1', '5', '请假条', '管理员', '1', '0', '开发部', '2016-08-17 22:50:22', '1', '管理员', '4,5', '0', '1', '2016-07-29', '大乔通过', '1', '0', null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('2', 'KB-20160729-0001', 'kqinfo', '2', '6', '加班单', '管理员', '1', '0', '开发部', '2016-07-29 10:56:58', '1', '管理员', '5', '0', '1', '2016-07-29', '磐石通过', '1', '0', null, null, '好的', null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('3', 'KA-20160807-0001', 'kqinfo', '3', '5', '请假条', '张飞', '6', '0', '开发部', '2016-08-07 18:37:02', '6', '张飞', '4,1', '0', '1', '2016-08-07', '大乔通过', '1', '0', null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('4', 'KA-20160815-0001', 'kqinfo', '4', '5', '请假条', '管理员', '1', '0', '开发部', '2016-09-13 16:05:38', '1', '管理员', '4,5', '0', '1', '2016-09-13', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:38', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('5', 'KA-20160825-0001', 'kqinfo', '5', '5', '请假条', '管理员', '1', '0', '开发部', '2016-08-25 16:01:52', '1', '管理员', '4,5', '0', '1', '2016-08-25', '大乔通过', '1', '0', null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('6', 'KA-20160826-0001', 'kqinfo', '6', '5', '请假条', '信呼客服', '8', '0', '开发部', '2016-08-26 22:45:35', '8', '信呼客服', '4,1', '0', '1', '2016-08-26', '大乔通过', '1', '0', null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('8', 'KB-20160827-0001', 'kqinfo', '7', '6', '加班单', '管理员', '1', '0', '开发部', '2016-08-27 14:06:56', '1', '管理员', '5', '0', '1', '2016-08-27', '磐石通过', '1', '0', null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('9', 'KD-20160827-0001', 'kqout', '1', '23', '外出出差', '管理员', '1', '0', '开发部', '2016-08-27 15:22:51', '1', '管理员', '5', '0', '1', '2016-08-27', '磐石通过', '1', '0', null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('10', 'KA-20160830-0001', 'kqinfo', '8', '5', '请假条', '张飞', '6', '0', '开发部', '2016-08-30 20:12:33', '6', '张飞', '4,6,1', '0', '1', '2016-08-30', '大乔处理通过', '1', '0', null, null, '好的', null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('235', 'WA-20200331-001', 'goodm', '17', '24', '物品领用', '管理员', '1', '0', '开发部', '2020-03-31 22:18:38', '1', '管理员', '4', '0', '1', '2020-03-31', '大乔处理发放', '1', '0', null, null, null, '2020-03-31 22:18:46', '2020-03-31 22:18:38', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('119', 'WB-20171112-0001', 'goodm', '3', '28', '物品采购', '管理员', '1', '0', '开发部', '2018-02-07 23:05:49', '1', '管理员', '5,4', '0', '1', '2017-11-12', '待磐石处理', '0', '20', '5', '磐石', null, '2018-08-29 16:44:39', '2017-11-12 18:37:02', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('18', 'FA-20160902-0001', 'fininfom', '5', '11', '费用报销', '管理员', '1', '0', '开发部', '2016-09-12 14:52:28', '1', '管理员', '4,5,2', '0', '1', '2016-09-02', '待貂蝉处理', '0', '9', '2', '貂蝉', null, '2019-06-04 10:59:38', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('19', 'FA-20160508-0001', 'fininfom', '4', '26', '借款单', '管理员', '1', '0', '开发部', '2016-09-03 15:43:28', '1', '管理员', '2,4,5', '0', '1', '2016-05-08', '貂蝉处理通过', '1', '0', null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('20', 'FB-20160505-0001', 'fininfom', '3', '25', '出差报销', '管理员', '1', '0', '开发部', '2016-09-03 11:43:36', '1', '管理员', '2,8,5,4', '0', '1', '2016-05-05', '貂蝉处理通过', '1', '0', null, null, null, null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('21', 'FC-20160903-0001', 'fininfom', '6', '26', '借款单', '管理员', '1', '0', '开发部', '2016-09-03 16:52:52', '1', '管理员', '2,4,5', '0', '1', '2016-09-03', '貂蝉处理通过', '1', '0', null, null, '哈哈哈哈', null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('22', 'FD-20160903-0001', 'fininfom', '7', '27', '还款单', '管理员', '1', '0', '开发部', '2016-09-03 17:17:44', '1', '管理员', '2,1', '0', '1', '2016-09-03', '貂蝉处理通过', '1', '0', null, null, '哈哈哈哈', null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('139', 'KA-20180613-0001', 'kqinfo', '103', '5', '请假条', '小乔', '3', '3', '财务部', '2020-09-27 19:47:18', '3', '小乔', '4,2,5', '0', '2', '2018-06-13', '待貂蝉处理', '0', '1', '2', '貂蝉', '好', '2020-09-27 19:47:18', '2018-06-13 14:48:40', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('24', 'KA-20160908-0001', 'kqinfo', '9', '5', '请假条', '管理员', '1', '0', '开发部', '2016-09-12 14:50:07', '1', '管理员', '4,5', '0', '5', '2016-09-08', '作废', '5', '2', null, null, '作废：太久没处理了，作废吧', '2017-08-02 15:38:14', null, '0', '0');
INSERT INTO `xinhu_flow_bill` VALUES ('25', 'KA-20160925-0001', 'kqinfo', '11', '5', '请假条', '张飞', '6', '0', '开发部', '2016-09-25 16:04:13', '6', '张飞', '1,4,5', '0', '5', '2016-09-25', '作废', '5', '2', null, null, '作废：费了吧', '2019-05-14 23:25:20', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('26', 'FA-20160928-0001', 'fininfom', '8', '11', '费用报销', '管理员', '1', '0', '开发部', '2017-05-14 18:50:58', '1', '管理员', '2,4,5', '0', '1', '2016-09-28', '貂蝉处理通过', '1', '0', null, null, null, '2017-06-22 09:41:59', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('117', 'WB-20171111-0001', 'goodm', '1', '28', '物品采购', '管理员', '1', '0', '开发部', '2017-11-11 15:43:29', '1', '管理员', '4,5', '0', '1', '2017-11-11', '大乔处理同意', '1', '0', null, null, null, '2017-11-11 22:07:42', '2017-11-11 15:43:29', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('28', 'HA-20161006-0001', 'hrpositive', '1', '32', '转正申请', '管理员', '1', '0', '开发部', '2016-10-06 20:58:38', '1', '管理员', '4,5', '0', '1', '2016-10-06', '大乔处理通过', '1', '0', null, null, '好哦奥', null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('29', 'HB-20161007-0001', 'hrredund', '1', '33', '离职申请', '管理员', '1', '2', '开发部', '2021-06-25 15:36:42', '1', '管理员', '3,5,4,2', '0', '1', '2016-10-07', '待大乔处理', '0', '30', '4', '大乔', null, '2021-07-03 18:12:45', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('206', 'GA-20190410-001', 'official', '1', '19', '发文单', '管理员', '1', '2', '开发部', '2020-11-01 08:46:57', '1', '管理员', '1,5', '0', '1', '2019-04-10', '管理员处理流转', '1', '0', null, null, null, '2020-11-01 08:48:02', '2019-04-21 21:27:36', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('188', 'FF-20181022-001', 'fininfom', '12', '65', '开票申请', '管理员', '1', '0', '开发部', '2018-10-22 20:24:32', '1', '管理员', '5,2,3', '0', '1', '2018-10-22', '待貂蝉处理', '0', '65', '2', '貂蝉', null, '2018-11-05 13:42:49', '2018-10-22 20:24:32', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('187', 'HC-20181009-012', 'hrsalary', '20', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 22:33:13', '1', '管理员', '4', '0', '1', '2018-10-09', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:37', '2018-10-09 22:31:45', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('186', 'HC-20181009-011', 'hrsalary', '19', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 22:30:24', '1', '管理员', '4', '1', '0', '2018-10-09', '待大乔处理', '0', '31', '4', '大乔', null, '2018-10-09 22:30:24', '2018-10-09 22:09:16', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('47', 'HE-20161019-0001', 'hrtransfer', '1', '38', '人事调动', '管理员', '1', '0', '开发部', '2016-10-19 22:03:12', '1', '管理员', '4', '0', '1', '2016-10-19', '大乔处理通过', '1', '0', null, null, null, '2017-02-07 10:21:30', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('48', 'HD-20161101-0001', 'hrtrsalary', '1', '37', '调薪申请', '管理员', '1', '0', '开发部', '2016-10-20 22:20:41', '1', '管理员', '5,4', '0', '1', '2016-11-01', '待大乔处理', '0', '34', '4', '大乔', null, '2020-11-13 17:25:54', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('50', 'HF-20161020-0002', 'reward', '2', '39', '奖惩处罚', '管理员', '1', '0', '开发部', '2016-10-20 21:16:24', '1', '管理员', '4,8', '0', '1', '2016-10-20', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:38', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('201', 'FI-20190309-001', 'wordeil', '1', '87', '文件传送', '管理员', '1', '0', '开发部', '2019-03-09 13:13:20', '1', '管理员', '3', '0', '1', '2019-03-09', '小乔处理接收', '1', '0', null, null, null, '2019-03-09 13:14:46', '2019-03-09 13:13:20', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('56', 'CL-20161029-0001', 'bookborrow', '1', '46', '图书借阅', '管理员', '1', '0', '开发部', '2016-10-29 13:08:44', '1', '管理员', '6,3,1', '0', '1', '2016-10-29', '张飞处理确认归还', '1', '0', null, null, '呵呵', null, null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('57', 'CL-20161029-0002', 'bookborrow', '2', '46', '图书借阅', '信呼客服', '8', '0', '开发部', '2016-10-29 13:14:35', '8', '信呼客服', '2,3', '0', '1', '2016-10-29', '貂蝉处理确认归还', '1', '0', null, null, null, '2020-07-11 17:27:39', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('58', 'CL-20161030-0001', 'bookborrow', '3', '46', '图书借阅', '管理员', '1', '0', '开发部', '2016-10-30 11:58:59', '1', '管理员', '1,3', '0', '1', '2016-10-30', '管理员处理确认归还', '1', '0', null, null, null, '2017-03-31 09:36:21', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('59', 'KD-20161108-0001', 'kqout', '2', '23', '外出出差', '信呼客服', '8', '0', '开发部', '2016-11-08 20:52:41', '8', '信呼客服', '1', '0', '1', '2016-11-08', '管理员处理通过', '1', '0', null, null, '好的', '2017-02-28 13:13:38', null, '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('63', 'KA-20161209-0001', 'kqinfo', '14', '5', '请假条', '管理员', '1', '0', '开发部', '2017-07-25 22:09:21', '1', '管理员', '5', '0', '1', '2017-07-25', '磐石处理同意', '1', '0', null, null, '很好啊', '2017-08-04 09:59:14', '2016-12-09 12:23:06', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('64', 'KE-20161216-0001', 'kqerr', '1', '51', '打卡异常', '管理员', '1', '0', '开发部', '2016-12-16 20:54:27', '1', '管理员', '4,5', '0', '1', '2016-12-16', '大乔处理通过', '1', '0', null, null, null, '2016-12-16 21:08:07', '2016-12-16 20:54:27', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('67', 'ZF-20161219-0003', 'tovoid', '3', '52', '单据作废申请', '管理员', '1', '0', '开发部', '2016-12-19 13:49:22', '1', '管理员', '5', '0', '5', '2016-12-19', '作废', '5', '0', null, null, '作废：在删除测试测试', '2016-12-19 14:33:32', '2016-12-19 13:49:22', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('69', 'ZF-20161219-0005', 'tovoid', '5', '52', '单据作废申请', '管理员', '1', '0', '开发部', '2016-12-19 14:39:20', '1', '管理员', '5', '0', '1', '2016-12-19', '磐石处理通过', '1', '0', null, null, null, '2016-12-19 14:39:52', '2016-12-19 14:39:20', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('141', 'HA-20180615-0002', 'hrpositive', '3', '32', '转正申请', '大乔', '4', '0', '行政人事部', '2018-06-15 20:16:18', '1', '管理员', '4,5', '0', '1', '2018-06-15', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:38', '2018-06-15 20:16:18', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('84', 'KD-20170419-0001', 'kqout', '3', '23', '外出出差', '管理员', '1', '0', '开发部', '2017-04-19 12:57:28', '1', '管理员', '5', '0', '1', '2017-04-19', '磐石处理通过', '1', '0', null, null, null, '2017-04-19 12:57:48', '2017-04-19 12:57:28', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('86', 'FA-20160505-0001', 'fininfom', '1', '11', '费用报销', '管理员', '1', '0', '开发部', '2016-05-05 20:19:42', '1', '管理员', null, '0', '1', '2016-05-05', null, '1', '0', null, null, null, '2017-05-02 11:00:16', '2016-05-05 20:19:42', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('88', 'FA-20170508-0001', 'fininfom', '9', '11', '费用报销', '大乔', '4', '4', '行政人事部', '2020-06-15 20:29:43', '4', '大乔', '2,4,5', '0', '1', '2017-05-08', '貂蝉处理同意', '1', '0', null, null, null, '2020-09-27 19:40:56', '2017-05-08 19:35:20', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('89', 'KB-20170518-0001', 'kqinfo', '15', '6', '加班单', '管理员', '1', '0', '开发部', '2017-05-18 12:17:51', '1', '管理员', '5', '0', '1', '2017-05-18', '磐石处理通过', '1', '0', null, null, null, '2017-05-18 12:58:56', '2017-05-18 12:11:37', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('159', 'CJ-20180903-001', 'carmrese', '1', '44', '车辆预定', '管理员', '1', '0', '开发部', '2018-09-03 19:51:16', '1', '管理员', '3,5,6', '0', '1', '2018-09-03', '待张飞处理', '0', '50', '6', '张飞', null, '2018-09-03 19:52:42', '2018-09-03 19:51:16', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('94', 'KB-20170629-0001', 'kqinfo', '16', '6', '加班单', '管理员', '1', '0', '开发部', '2017-06-29 10:14:05', '1', '管理员', '4,5', '0', '1', '2017-06-29', '大乔处理通过', '1', '0', null, null, null, '2017-06-29 10:16:07', '2017-06-29 10:14:05', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('129', 'HH-20180418-0001', 'hrdemint', '1', '78', '人员需求', '赵子龙', '7', '0', '开发部', '2018-04-18 20:39:38', '7', '赵子龙', '4', '0', '1', '2018-04-18', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:38', '2018-04-18 19:49:46', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('97', 'WX-20170707-0001', 'repair', '1', '62', '维修报修', '管理员', '1', '0', '开发部', '2017-07-07 13:12:53', '1', '管理员', '4', '0', '1', '2017-07-07', '大乔处理确认', '1', '0', null, null, null, '2017-07-07 13:16:36', '2017-07-07 13:12:53', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('98', 'WX-20170707-0002', 'repair', '2', '62', '维修报修', '小乔', '3', '0', '财务部', '2017-07-07 13:37:28', '3', '小乔', '4', '0', '1', '2017-07-07', '大乔处理通过', '1', '0', null, null, null, '2017-07-07 13:39:18', '2017-07-07 13:37:28', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('99', 'FE-20170807-0001', 'fininfom', '10', '64', '付款申请', '管理员', '1', '0', '开发部', '2018-10-22 21:46:31', '1', '管理员', '3,5', '0', '1', '2017-08-07', '小乔处理已付款', '1', '0', null, null, null, '2019-07-20 19:09:36', '2017-08-07 20:42:59', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('191', 'CL-20181106-001', 'bookborrow', '4', '46', '图书借阅', '管理员', '1', '0', '开发部', '2018-11-07 19:50:40', '1', '管理员', '1,3', '0', '1', '2018-11-06', '管理员处理确认归还', '1', '0', null, null, null, '2019-09-17 18:32:45', '2018-11-06 20:57:34', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('195', 'KA-20190201-001', 'kqinfo', '142', '5', '请假条', '管理员', '1', '0', '开发部', '2019-02-01 10:28:14', '1', '管理员', '4,5', '0', '1', '2019-02-01', '大乔处理同意', '1', '0', null, null, null, '2019-02-01 10:28:47', '2019-02-01 10:28:14', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('103', 'KD-20170828-0001', 'kqout', '4', '23', '外出出差', '管理员', '1', '0', '开发部', '2017-08-28 11:54:41', '1', '管理员', '5', '0', '1', '2017-08-28', '磐石处理同意', '1', '0', null, null, null, '2018-11-13 18:21:01', '2017-08-28 11:54:41', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('104', 'KE-20170828-0001', 'kqerr', '2', '51', '打卡异常', '貂蝉', '2', '0', '财务部', '2017-08-28 12:19:12', '2', '貂蝉', '4,5', '0', '1', '2017-08-28', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:38', '2017-08-28 12:19:12', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('185', 'HC-20181009-010', 'hrsalary', '15', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 22:48:52', '2', '貂蝉', '4', '0', '1', '2018-10-09', '大乔处理同意', '1', '0', null, null, null, '2018-10-26 11:38:34', '2018-10-09 19:36:57', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('179', 'HC-20181009-005', 'hrsalary', '9', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 18:30:30', '1', '管理员', null, '1', '0', '2018-10-09', null, '0', '0', null, null, null, '2018-10-09 18:30:32', '2018-10-09 18:30:30', '0', '0');
INSERT INTO `xinhu_flow_bill` VALUES ('184', 'HC-20181009-009', 'hrsalary', '18', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 22:06:15', '1', '管理员', '4', '1', '0', '2018-10-09', '待大乔处理', '0', '31', '4', '大乔', null, '2018-10-09 22:06:15', '2018-10-09 19:58:26', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('181', 'HC-20181009-007', 'hrsalary', '12', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 19:21:36', '2', '貂蝉', null, '1', '0', '2018-10-09', null, '0', '0', null, null, null, '2018-10-09 19:23:26', '2018-10-09 19:21:36', '0', '0');
INSERT INTO `xinhu_flow_bill` VALUES ('180', 'HC-20181009-006', 'hrsalary', '11', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 19:21:36', '1', '管理员', null, '1', '0', '2018-10-09', null, '0', '0', null, null, null, '2018-10-09 19:21:39', '2018-10-09 19:21:36', '0', '0');
INSERT INTO `xinhu_flow_bill` VALUES ('173', 'HC-20180928-001', 'hrsalary', '1', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 22:48:53', '2', '貂蝉', '4', '0', '1', '2018-09-28', '大乔处理同意', '1', '0', null, null, null, '2018-10-10 14:56:32', '2018-09-28 22:30:13', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('174', 'HC-20180928-002', 'hrsalary', '2', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 22:48:52', '2', '貂蝉', '4', '0', '1', '2018-09-28', '大乔处理同意', '1', '0', null, null, null, '2018-10-10 14:56:32', '2018-09-28 22:30:33', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('182', 'HC-20181009-008', 'hrsalary', '16', '34', '薪资', '管理员', '1', '0', '开发部', '2018-10-09 22:33:14', '1', '管理员', '4', '0', '1', '2018-10-09', '大乔处理同意', '1', '0', null, null, null, '2018-10-10 14:56:32', '2018-10-09 19:38:14', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('120', 'WB-20171113-0001', 'goodm', '4', '28', '物品采购', '管理员', '1', '0', '开发部', '2017-11-13 14:20:47', '1', '管理员', '4,5', '0', '1', '2017-11-13', '大乔处理同意', '1', '0', null, null, null, '2017-11-13 14:21:23', '2017-11-13 14:20:47', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('121', 'WC-20171228-0001', 'goodm', '5', '76', '调拨单', '管理员', '1', '0', '开发部', '2017-12-28 14:26:46', '1', '管理员', '5', '0', '1', '2017-12-28', '磐石处理同意', '1', '0', null, null, null, '2017-12-28 14:31:01', '2017-12-28 14:24:10', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('122', 'WC-20180127-0001', 'goodm', '6', '76', '调拨单', '管理员', '1', '0', '开发部', '2018-01-27 10:27:28', '1', '管理员', '5', '0', '1', '2018-01-27', '磐石处理同意', '1', '0', null, null, null, '2018-01-27 10:27:51', '2018-01-27 10:27:28', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('124', 'CE-20180306-0001', 'custappy', '1', '77', '客户申请使用', '管理员', '1', '0', '开发部', '2018-03-06 15:57:45', '1', '管理员', '5', '0', '1', '2018-03-06', '磐石处理同意', '1', '0', null, null, null, '2018-03-06 16:24:12', '2018-03-06 15:47:27', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('125', 'CE-20180306-0002', 'custappy', '2', '77', '客户申请使用', '赵子龙', '7', '0', '开发部', '2018-03-06 17:42:35', '7', '赵子龙', '1', '0', '1', '2018-03-06', '管理员处理同意', '1', '0', null, null, null, '2018-03-06 21:14:03', '2018-03-06 17:42:35', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('126', 'CE-20180307-0001', 'custappy', '3', '77', '客户申请使用', '赵子龙', '7', '0', '开发部', '2018-03-07 12:28:31', '7', '赵子龙', '1', '0', '5', '2018-03-07', '作废', '5', '73', null, null, '作废：超时30分钟自动作废', '2018-03-07 12:36:13', '2018-03-07 12:28:31', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('127', 'CE-20180307-0002', 'custappy', '4', '77', '客户申请使用', '赵子龙', '7', '0', '开发部', '2018-03-07 12:41:46', '7', '赵子龙', '1', '0', '5', '2018-03-07', '作废', '5', '73', null, null, '作废：超时10分钟自动作废', '2018-03-07 12:55:05', '2018-03-07 12:41:46', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('130', 'HI-201804180001', 'hrdemint', '2', '79', '面试安排', '赵子龙', '7', '0', '开发部', '2018-04-18 22:01:41', '7', '赵子龙', '7,1', '0', '1', '2018-04-18', '赵子龙处理同意', '1', '0', null, null, null, '2019-07-24 18:32:00', '2018-04-18 22:01:41', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('131', 'HI-201804180002', 'hrdemint', '3', '79', '面试安排', '赵子龙', '7', '0', '开发部', '2018-04-18 22:45:48', '7', '赵子龙', '5,7', '0', '2', '2018-04-18', '待磐石处理', '2', '75', '5', '磐石', '哈哈哈', '2018-04-18 22:46:18', '2018-04-18 22:33:50', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('196', 'KA-20190218-001', 'kqinfo', '145', '5', '请假条', '管理员', '1', '0', '开发部', '2019-02-18 14:23:08', '1', '管理员', null, '0', '1', '2019-02-18', null, '1', '0', null, null, null, '2019-08-15 21:08:55', '2019-02-18 14:23:08', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('226', 'KB-20190826-001', 'kqinfo', '155', '6', '加班单', '管理员', '1', '0', '开发部', '2019-08-26 18:20:43', '1', '管理员', '5', '0', '1', '2019-08-26', '磐石处理同意', '1', '0', null, null, null, '2019-08-26 18:20:57', '2019-08-26 18:20:43', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('140', 'HA-20180615-0001', 'hrpositive', '2', '32', '转正申请', '赵子龙', '7', '0', '开发部', '2018-06-15 20:04:38', '7', '赵子龙', '4,1', '0', '1', '2018-06-15', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:38', '2018-06-15 20:04:38', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('142', 'KD-20180615-0001', 'kqout', '5', '23', '外出出差', '貂蝉', '2', '0', '财务部', '2018-06-15 20:28:19', '1', '管理员', '5', '0', '1', '2018-06-15', '磐石处理同意', '1', '0', null, null, null, '2018-09-06 20:44:40', '2018-06-15 20:28:19', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('143', 'KB-20180615-0001', 'kqinfo', '104', '6', '加班单', '大乔', '4', '0', '行政人事部', '2018-08-20 10:59:14', '1', '管理员', '5', '0', '1', '2018-06-15', '磐石处理同意', '1', '0', null, null, null, '2018-08-21 12:11:52', '2018-06-15 20:31:24', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('204', 'CM-20190419-001', 'sealapl', '1', '49', '印章证照使用', '管理员', '1', '2', '开发部', '2020-11-02 11:57:23', '1', '管理员', '1,5', '0', '1', '2020-11-02', '管理员处理已盖章', '1', '0', null, null, null, '2020-11-02 11:57:42', '2019-04-19 17:23:30', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('155', 'KB-20180807-001', 'kqinfo', '131', '6', '加班单', '管理员', '1', '0', '开发部', '2018-08-07 15:57:02', '1', '管理员', '5', '0', '1', '2018-08-07', '磐石处理同意', '1', '0', null, null, null, '2018-08-07 15:57:19', '2018-08-07 15:57:02', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('147', 'WB-20180706-0001', 'goodm', '8', '28', '物品采购', '管理员', '1', '0', '开发部', '2018-07-06 18:27:39', '1', '管理员', '3,4,5', '0', '1', '2018-07-06', '小乔处理同意', '1', '0', null, null, null, '2018-07-28 22:06:57', '2018-07-06 18:27:39', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('239', 'WA-20200401-001', 'goodm', '21', '24', '物品领用', '管理员', '1', '0', '开发部', '2020-04-01 20:19:36', '1', '管理员', '4', '0', '1', '2020-04-01', '大乔处理发放', '1', '0', null, null, null, '2020-04-01 20:20:07', '2020-04-01 20:19:36', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('153', 'KA-20180728-001', 'kqinfo', '109', '5', '请假条', '大乔', '4', '0', '行政人事部', '2018-07-28 21:52:21', '4', '大乔', '5,2', '0', '1', '2018-07-28', '待貂蝉处理', '0', '2', '2', '貂蝉', null, '2019-05-28 18:49:37', '2018-07-28 21:52:21', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('154', 'KA-20180728-002', 'kqinfo', '110', '5', '请假条', '小乔', '3', '0', '财务部', '2018-07-28 21:55:00', '3', '小乔', '4,5,2', '0', '1', '2018-07-28', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:38', '2018-07-28 21:55:00', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('222', 'WB-20190607-001', 'goodm', '13', '28', '物品采购', '大乔', '4', '0', '行政人事部', '2019-06-07 12:54:28', '4', '大乔', '5', '0', '1', '2019-06-07', '磐石处理同意', '1', '0', null, null, null, '2019-06-07 12:54:58', '2019-06-07 12:54:28', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('221', 'KE-20190606-001', 'kqerr', '4', '51', '打卡异常', '大乔', '4', '0', '行政人事部', '2019-06-06 18:23:56', '4', '大乔', '5,4', '0', '1', '2019-06-06', '待大乔处理', '0', '45', '4', '大乔', null, '2019-08-25 19:48:51', '2019-06-06 18:23:56', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('209', 'GA-20190425-001', 'official', '3', '19', '发文单', '管理员', '1', '0', '开发部', '2019-04-25 20:28:40', '1', '管理员', '1,5', '0', '1', '2019-04-25', '管理员处理流转', '1', '0', null, null, null, '2019-04-25 20:29:23', '2019-04-25 20:28:40', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('210', 'WC-20190429-001', 'goodm', '10', '76', '调拨单', '管理员', '1', '0', '开发部', '2019-05-09 18:11:12', '1', '管理员', '5', '0', '1', '2019-04-29', '磐石处理同意', '1', '0', null, null, '呃呃呃', '2020-07-11 17:29:34', '2019-04-29 11:24:12', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('218', 'GB-20190512-001', 'official', '4', '20', '收文单', '管理员', '1', '0', '开发部', '2019-05-12 23:10:41', '1', '管理员', '1,5', '0', '1', '2019-05-12', '管理员处理流转', '1', '0', null, null, null, '2019-05-12 23:11:10', '2019-05-12 23:10:41', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('212', 'HC-20190507-001', 'hrsalary', '25', '34', '薪资', '管理员', '1', '0', '开发部', '2019-05-07 15:08:33', '1', '管理员', '4', '0', '1', '2019-05-07', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:37', '2019-05-07 15:08:33', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('213', 'HC-20190507-002', 'hrsalary', '26', '34', '薪资', '管理员', '1', '0', '开发部', '2019-05-07 15:10:19', '1', '管理员', '4', '0', '1', '2019-05-07', '大乔处理同意', '1', '0', null, null, null, '2019-06-04 10:59:35', '2019-05-07 15:10:19', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('217', 'KA-20190512-001', 'kqinfo', '147', '5', '请假条', '管理员', '1', '0', '开发部', '2019-05-12 18:41:46', '1', '管理员', null, '0', '1', '2019-05-12', null, '1', '0', null, null, null, '2019-07-03 20:33:17', '2019-05-12 18:41:46', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('216', 'CF-20190512-001', 'goodm', '11', '92', '销售', '管理员', '1', '0', '开发部', '2019-05-12 14:36:03', '1', '管理员', '5', '0', '1', '2019-05-12', '磐石处理同意', '1', '0', null, null, null, '2019-05-12 14:36:24', '2019-05-12 14:36:03', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('224', 'KB-20190701-001', 'kqinfo', '144', '6', '加班单', '管理员', '1', '0', '开发部', '2019-07-01 21:48:33', '1', '管理员', null, '0', '1', null, null, '1', '0', null, null, null, '2019-07-01 21:48:33', '2019-07-01 21:48:33', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('225', 'HI-20190719001', 'hrdemint', '4', '79', '面试安排', '大乔', '4', '0', '行政人事部', '2019-07-19 11:58:06', '4', '大乔', '4', '0', '0', '2019-07-19', '待大乔处理', '0', '75', '4', '大乔', null, '2019-07-19 11:58:06', '2019-07-19 11:58:06', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('238', 'WD-20200331-001', 'goodm', '20', '100', '物品归还', '管理员', '1', '0', '开发部', '2020-03-31 23:06:42', '1', '管理员', '4', '0', '1', '2020-03-31', '大乔处理同意', '1', '0', null, null, null, '2020-04-01 19:48:32', '2020-03-31 23:06:42', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('241', 'HF-20200515-001', 'reward', '3', '39', '奖惩处罚', '管理员', '1', '2', '开发部', '2020-06-24 11:17:56', '1', '管理员', '4,1', '0', '1', '2020-05-15', '大乔处理同意', '1', '0', null, null, null, '2020-06-24 11:18:03', '2020-05-15 11:59:21', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('266', 'XW-20201218-001', 'work', '3', '4', '任务', '管理员', '1', '2', '开发部', '2020-12-18 14:07:33', '1', '管理员', '7,5,1', '0', '3', '2020-12-18', '待磐石处理', '3', '52', '5', '磐石', '转给：磐石', '2021-04-28 21:14:29', '2020-12-18 13:53:35', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('246', 'GA-20200619-001', 'official', '9', '19', '发文单', '管理员', '1', '2', '开发部', '2020-06-19 22:29:41', '1', '管理员', '1,6', '0', '1', '2020-06-19', '管理员处理流转', '1', '0', null, null, null, '2020-06-20 11:13:22', '2020-06-19 17:11:39', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('250', 'HC-20200710-001', 'hrsalary', '27', '34', '薪资', '管理员', '1', '2', '开发部', '2020-07-11 22:53:19', '1', '管理员', '4', '0', '1', '2020-07-10', '大乔处理同意', '1', '0', null, null, null, '2020-07-17 18:38:24', '2020-07-10 20:49:04', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('253', 'WE-20200908-001', 'goodm', '22', '102', '退货单', '管理员', '1', '2', '开发部', '2020-09-08 18:06:35', '1', '管理员', '5', '0', '1', '2020-09-08', '磐石处理同意', '1', '0', null, null, null, '2020-09-08 20:32:02', '2020-09-08 18:06:35', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('254', 'WE-20200908-002', 'goodm', '23', '102', '退货单', '管理员', '1', '2', '开发部', '2020-09-18 11:27:00', '1', '管理员', '5', '0', '1', '2020-09-08', '磐石处理同意', '1', '0', null, null, null, '2020-11-18 15:46:50', '2020-09-08 21:33:18', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('264', 'KB-20201116-001', 'kqinfo', '158', '6', '加班单', '管理员', '1', '2', '开发部', '2020-11-16 12:28:24', '1', '管理员', '5', '0', '1', '2020-11-16', '磐石处理同意', '1', '0', null, null, null, '2020-11-16 12:31:47', '2020-11-16 12:24:24', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('267', 'GB-20210107-001', 'official', '10', '20', '收文单', '管理员', '1', '2', '开发部', '2021-01-07 17:48:20', '1', '管理员', '7,5,4,3', '0', '1', '2021-01-07', '待大乔,小乔处理', '0', '26', '4,3', '大乔,小乔', null, '2021-01-07 18:36:21', '2021-01-07 17:48:20', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('269', 'KA-20210108-001', 'kqinfo', '143', '5', '请假条', '管理员', '1', '2', '开发部', '2021-01-08 21:46:04', '1', '管理员', null, '0', '1', null, null, '1', '0', null, null, null, '2021-01-08 21:46:04', '2021-01-08 21:46:04', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('288', 'GA-20211012-001', 'official', '11', '19', '发文单', '大乔', '4', '4', '行政人事部', '2021-10-12 11:04:36', '4', '大乔', '4,1', '0', '1', '2021-10-12', '大乔处理流转', '1', '0', null, null, null, '2021-10-12 11:05:10', '2021-10-12 11:04:36', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('282', 'XW-20210413-001', 'work', '11', '4', '任务', '管理员', '1', '2', '开发部', '2021-04-13 16:13:43', '1', '管理员', '1,6', '0', '1', '2021-04-13', '管理员处理同意', '1', '0', null, null, null, '2021-06-01 10:27:40', '2021-04-13 16:12:56', '0', '1');
INSERT INTO `xinhu_flow_bill` VALUES ('289', 'WA-20211015-001', 'goodm', '24', '24', '物品领用', '大乔', '4', '4', '行政人事部', '2021-10-15 15:37:29', '4', '大乔', '4', '0', '0', '2021-10-15', '待大乔处理', '0', '47', '4', '大乔', null, '2021-10-15 15:37:29', '2021-10-15 15:37:29', '0', '1');

-- ----------------------------
-- Table structure for `xinhu_flow_chao`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_chao`;
CREATE TABLE `xinhu_flow_chao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modeid` int(11) DEFAULT '0',
  `table` varchar(50) DEFAULT NULL COMMENT '对应表',
  `mid` int(11) DEFAULT '0' COMMENT '对应记录Id',
  `uid` int(11) DEFAULT '0' COMMENT '人员Id',
  `csname` varchar(500) DEFAULT NULL COMMENT '抄送给',
  `csnameid` varchar(50) DEFAULT NULL,
  `type` int(11) DEFAULT '0' COMMENT '0抄送',
  PRIMARY KEY (`id`),
  KEY `modeid` (`modeid`),
  KEY `uid` (`uid`),
  KEY `table` (`table`,`mid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='抄送表';

-- ----------------------------
-- Records of xinhu_flow_chao
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_flow_checks`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_checks`;
CREATE TABLE `xinhu_flow_checks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(30) DEFAULT '0',
  `mid` int(11) DEFAULT NULL,
  `modeid` smallint(6) DEFAULT '0',
  `courseid` int(11) DEFAULT '0',
  `checkid` varchar(100) DEFAULT NULL,
  `checkname` varchar(100) DEFAULT NULL,
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `addlx` tinyint(1) DEFAULT '0' COMMENT '类型1自定义,2撤回添加,3退回添加,4转移添加',
  PRIMARY KEY (`id`),
  KEY `table` (`table`,`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=223 DEFAULT CHARSET=utf8 COMMENT='单据的审核人';

-- ----------------------------
-- Records of xinhu_flow_checks
-- ----------------------------
INSERT INTO `xinhu_flow_checks` VALUES ('161', 'kqinfo', '109', '5', '2', '2', '貂蝉', '5', '磐石', '2019-05-28 18:49:37', '0', '7');
INSERT INTO `xinhu_flow_checks` VALUES ('210', 'official', '10', '20', '25', '7', '赵子龙', '5', '磐石', '2021-01-07 18:34:33', '1', '1');
INSERT INTO `xinhu_flow_checks` VALUES ('211', 'official', '10', '20', '26', '4', '大乔', '7', '赵子龙', '2021-01-07 18:36:21', '0', '1');
INSERT INTO `xinhu_flow_checks` VALUES ('212', 'official', '10', '20', '26', '3', '小乔', '7', '赵子龙', '2021-01-07 18:36:21', '0', '1');
INSERT INTO `xinhu_flow_checks` VALUES ('213', 'work', '3', '4', '52', '5', '磐石', '7', '赵子龙', '2021-04-28 21:14:29', '0', '4');
INSERT INTO `xinhu_flow_checks` VALUES ('215', 'hrredund', '1', '33', '120', '2', '貂蝉', '5', '磐石', '2021-06-25 15:04:54', '1', '3');

-- ----------------------------
-- Table structure for `xinhu_flow_cname`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_cname`;
CREATE TABLE `xinhu_flow_cname` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` varchar(30) DEFAULT NULL COMMENT '编号',
  `name` varchar(50) DEFAULT NULL COMMENT '审核组名',
  `checkid` varchar(50) DEFAULT NULL,
  `checkname` varchar(50) DEFAULT NULL COMMENT '审核人',
  `pid` int(11) DEFAULT '0' COMMENT '上级ID',
  `receid` varchar(300) DEFAULT NULL,
  `recename` varchar(500) DEFAULT NULL COMMENT '适用对象',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  PRIMARY KEY (`id`),
  KEY `num` (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='自定义审核人组';

-- ----------------------------
-- Records of xinhu_flow_cname
-- ----------------------------
INSERT INTO `xinhu_flow_cname` VALUES ('1', 'group1', '审核人员分组1', '1', '管理员', '0', 'd5', '管理层', '0');
INSERT INTO `xinhu_flow_cname` VALUES ('2', 'group', '审核人员分组', '5', '磐石', '1', 'd1', '信呼开发团队', '0');
INSERT INTO `xinhu_flow_cname` VALUES ('4', 'wewwe', null, '3', '小乔', '1', 'd3', '财务部', '22');

-- ----------------------------
-- Table structure for `xinhu_flow_course`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_course`;
CREATE TABLE `xinhu_flow_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '上级ID',
  `nid` int(11) DEFAULT '0' COMMENT '下级步骤ID',
  `setid` smallint(6) DEFAULT '0' COMMENT '模块ID',
  `name` varchar(20) DEFAULT NULL COMMENT '步骤名称',
  `num` varchar(20) DEFAULT NULL,
  `checktype` varchar(20) DEFAULT NULL COMMENT '审核人类型',
  `checktypeid` varchar(200) DEFAULT NULL,
  `checktypename` varchar(200) DEFAULT NULL COMMENT '审核人',
  `sort` smallint(6) DEFAULT '0',
  `whereid` smallint(6) DEFAULT '0' COMMENT '流程模块条件下的Id',
  `where` varchar(500) DEFAULT NULL COMMENT '审核条件',
  `explain` varchar(100) DEFAULT NULL COMMENT '说明',
  `optdt` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `courseact` varchar(50) DEFAULT NULL COMMENT '审核动作',
  `checkshu` tinyint(2) DEFAULT '1' COMMENT '至少几人审核 ,0全部',
  `checkfields` varchar(500) DEFAULT NULL COMMENT '审核处理表单',
  `pid` smallint(6) DEFAULT '0' COMMENT '上级Id(弃用)',
  `optid` smallint(6) DEFAULT '0' COMMENT '操作人id',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人姓名',
  `receid` varchar(1000) DEFAULT NULL COMMENT '适用对象id',
  `recename` varchar(1000) DEFAULT NULL COMMENT '适用对象',
  `iszf` tinyint(1) DEFAULT '0' COMMENT '是否可以转给他人办理',
  `isqm` tinyint(1) DEFAULT '0' COMMENT '手写签名0不用,1都需要,2只需要通过,3只需要不通过',
  `coursetype` tinyint(1) DEFAULT '0' COMMENT '审批方式0顺序,1前置审批,2后置审批',
  `zshtime` int(11) DEFAULT '0' COMMENT '超时时间(分钟)',
  `zshstate` tinyint(2) DEFAULT '1' COMMENT '自动审核类型',
  `zbrangeame` varchar(200) DEFAULT NULL COMMENT '转办人员可选范围',
  `zbrangeid` varchar(200) DEFAULT NULL COMMENT '转办人员可选范围ID',
  `smlx` tinyint(1) DEFAULT '0' COMMENT '处理说明类型',
  `wjlx` tinyint(1) DEFAULT '0' COMMENT '相关文件类型',
  `isxgfj` tinyint(1) DEFAULT '0' COMMENT '审批时是否可直接编辑附件',
  `cslx` tinyint(1) DEFAULT '0' COMMENT '抄送类型',
  `csfwname` varchar(200) DEFAULT NULL COMMENT '抄送范围',
  `csfwid` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `setid` (`setid`)
) ENGINE=MyISAM AUTO_INCREMENT=128 DEFAULT CHARSET=utf8 COMMENT='流程步骤表';

-- ----------------------------
-- Records of xinhu_flow_course
-- ----------------------------
INSERT INTO `xinhu_flow_course` VALUES ('1', '0', '0', '5', '上级审核', null, 'superall', null, null, '0', '0', null, null, '2021-09-01 16:12:07', '1', null, '1', null, '0', '0', null, null, null, '2', '0', null, '0', '4', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('2', '1', '0', '5', '人事审核', null, 'rank', null, '人事主管', '0', '0', null, null, '2018-05-07 09:47:03', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('3', '0', '0', '6', '上级审核', null, 'super', null, null, '0', '0', null, null, '2016-07-02 22:25:29', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('4', '2', '0', '5', '总经理审核', null, 'user', '5', '磐石', '0', '2', null, '超过3天24小时', '2016-09-25 14:53:34', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('5', '0', '0', '23', '上级审核', null, 'super', null, null, '0', '0', null, null, '2016-08-27 08:46:27', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('6', '0', '0', '24', '行政发放', null, 'user', '4', '大乔', '0', '0', null, null, '2017-08-10 11:42:24', '1', '发放,驳回|red', '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('7', '0', '0', '11', '上级审批', null, 'super', null, null, '0', '0', null, null, '2016-09-02 17:36:36', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('8', '7', '0', '11', '人事审核', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-09-02 17:38:15', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('9', '8', '0', '11', '财务审核', null, 'rank', null, '财务总监', '0', '0', null, null, '2016-09-02 17:39:07', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('10', '0', '0', '25', '上级审批', null, 'super', null, null, '0', '0', null, null, '2016-09-03 09:26:32', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('11', '10', '0', '25', '人事审核', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-09-03 09:27:00', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('12', '11', '0', '25', '财务审核', null, 'rank', null, '财务总监', '0', '0', null, null, '2016-09-03 09:27:26', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('13', '0', '0', '26', '上级审核', null, 'super', null, null, '0', '0', null, null, '2016-09-03 09:27:55', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('14', '13', '0', '26', '人事审核', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-09-03 09:28:18', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('15', '14', '0', '26', '财务审核', null, 'rank', null, '财务总监', '0', '0', null, null, '2016-09-03 09:28:35', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('16', '0', '0', '27', '财务确认', null, 'rank', null, '财务总监', '0', '0', null, null, '2016-09-03 15:29:45', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('17', '16', '0', '27', '付款确认', null, 'apply', null, null, '0', '0', null, null, '2016-09-03 15:32:25', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('18', '17', '0', '27', '确认收款', null, 'rank', null, '财务总监', '0', '0', null, null, '2016-09-03 15:33:23', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('19', '0', '0', '28', '上级审批', null, 'super', null, null, '0', '0', null, null, '2017-02-08 14:39:04', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('20', '19', '0', '28', '行政审批', null, 'cname', 'group1', '审核人员分组1', '0', '0', null, null, '2018-07-28 22:05:34', '1', null, '0', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('21', '0', '0', '19', '上级复搞', null, 'super', null, null, '0', '0', null, null, '2020-06-20 10:21:12', '1', null, '1', '|content', '0', '0', null, null, null, '0', '2', null, '0', '1', null, null, '0', '0', '1', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('22', '21', '0', '19', '主要领导会签', null, 'rank', null, '分管领导', '0', '0', null, null, '2019-04-10 20:43:05', '1', null, '0', null, '0', '0', null, null, null, '0', '2', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('23', '22', '0', '19', '领导签发', null, 'change', null, null, '0', '0', null, null, '2020-06-13 23:15:40', '1', null, '0', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '1', '2', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('24', '0', '0', '20', '上级审批', null, 'change', null, null, '0', '0', null, null, '2021-10-15 15:26:50', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('25', '24', '0', '20', '主要领导会签', null, 'change', null, null, '0', '0', null, null, '2019-04-22 19:17:16', '1', null, '0', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('26', '25', '0', '20', '领导签发', null, 'change', null, null, '0', '0', null, null, '2019-04-22 19:17:22', '1', null, '0', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('27', '0', '0', '32', '上级审批', null, 'super', null, null, '0', '0', null, null, '2016-10-06 20:43:27', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('28', '27', '0', '32', '人事审批', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-10-07 10:24:03', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('29', '0', '0', '33', '上级审批', null, 'super', null, null, '0', '0', null, null, '2016-10-07 11:41:01', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('30', '29', '0', '33', '人事审批', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-10-07 11:41:17', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('31', '0', '0', '34', '人事审核', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-10-07 19:24:54', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('32', '0', '0', '38', '人事审批', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-10-19 21:48:34', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('33', '0', '0', '37', '上级审批', null, 'super', null, null, '0', '0', null, null, '2020-01-06 18:21:32', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '10', '5', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('34', '33', '0', '37', '人事审批', null, 'rank', null, '人事主管', '0', '0', null, null, '2019-04-06 22:38:02', '1', null, '1', null, '0', '0', null, null, null, '2', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('35', '0', '0', '39', '当事人确认', 'objectque', 'auto', null, null, '0', '0', null, null, '2016-10-20 20:52:15', '1', '无异议,不认可', '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('36', '35', '0', '39', '人事审批', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-10-20 20:53:10', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('37', '0', '0', '44', '上级审批', null, 'super', null, null, '0', '0', null, null, '2016-10-28 11:29:50', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('38', '37', '0', '44', '行政确认', null, 'user', '3', '小乔', '0', '0', null, null, '2017-08-25 20:03:02', '1', null, '1', 'kmstart,carid,carnum', '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('39', '0', '0', '46', '行政确认', null, 'user', '3', '小乔', '0', '0', null, null, '2020-10-27 18:35:17', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('40', '39', '0', '46', '借阅人归返', null, 'apply', null, null, '0', '0', null, null, '2020-07-11 17:36:26', '1', '确认归还', '1', 'ghtime', '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '3', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('41', '0', '0', '49', '上级审批', null, 'super', null, null, '0', '0', null, null, '2016-12-08 21:32:07', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('42', '41', '0', '49', '保管人确认', 'bgque', 'auto', null, null, '0', '0', null, null, '2019-04-19 18:22:12', '1', null, '0', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('43', '42', '0', '49', '申请人确认', null, 'apply', null, null, '0', '0', null, null, '2016-12-08 21:56:33', '1', '已盖章,放弃盖章', '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('44', '0', '0', '51', '上级审核', null, 'super', null, null, '0', '0', null, null, '2016-12-16 18:02:01', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('45', '44', '0', '51', '人事确认', null, 'rank', null, '人事主管', '0', '0', null, null, '2016-12-16 18:02:21', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('46', '0', '0', '52', '上级审核', null, 'super', null, null, '0', '0', null, null, '2016-12-19 09:58:39', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('47', '0', '0', '24', '行政确认', null, 'rank', null, '人事主管', '1', '0', null, null, '2017-09-26 10:20:04', '1', null, '1', null, '0', '0', null, 'd3,d4', '财务部,行政人事部', '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('48', '47', '0', '24', '行政发放', null, 'user', '3', '小乔', '1', '0', null, null, '2017-08-09 16:58:39', '1', '发放,驳回|red', '1', null, '0', '0', null, 'u3', '小乔', '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('50', '38', '0', '44', '驾驶员归还', 'jias', 'auto', null, null, '0', '0', null, null, '2017-03-01 14:58:11', '1', '已归还', '1', 'kmend,returndt', '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('51', '0', '52', '4', '任务分配', null, 'super', null, null, '0', '4', null, '未分配时', '2018-07-06 16:14:45', '1', '同意分配|green|3,不通过|red', '1', 'dist', '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('52', '0', '0', '4', '执行人执行', 'run', 'auto', null, null, '0', '0', null, '已分配', '2021-04-28 21:26:30', '1', '已完成||6,无法完成|red,执行中||4', '1', null, '0', '0', null, null, null, '2', '0', null, '0', '1', '直属下级(含下级的下级)', 'downall', '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('53', '52', '0', '4', '提交人验证', null, 'opt', null, null, '0', '0', null, null, '2017-08-15 17:46:47', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('55', '0', '0', '59', '行政确认', null, 'rank', null, '人事主管', '0', '0', null, null, '2017-06-26 21:26:30', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('56', '0', '0', '60', '行政确认', null, 'rank', null, '人事主管', '0', '0', null, null, '2017-06-27 13:03:25', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('57', '56', '0', '60', '驾驶员处理', 'jia', 'auto', null, null, '0', '0', null, null, '2017-06-27 13:04:24', '1', null, '1', 'address,bujian,kmshu', '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('58', '57', '0', '60', '驾驶员取车', 'jia', 'auto', null, null, '0', '0', null, null, '2017-06-27 13:05:41', '1', null, '1', 'nextdt,kmnshu,enddt,money', '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('59', '3', '0', '6', '核算加班费', null, 'rank', null, '人事主管', '0', '129', null, null, '2017-06-29 10:05:49', '1', null, '1', 'jiafee', '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('60', '0', '0', '62', '行政人员检查', null, 'user', '4', '大乔', '0', '0', null, null, '2017-07-07 13:16:02', '1', '确认', '1', 'iswx,reasons,money,wxname', '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('61', '60', '0', '62', '外修确认', null, 'user', '4', '大乔', '0', '140', null, null, '2017-07-07 13:36:48', '1', null, '1', 'money,wxname', '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('62', '0', '0', '64', '上级审批', null, 'super', null, null, '0', '0', null, null, '2017-08-07 20:21:47', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('63', '62', '0', '64', '出纳付款', null, 'rank', null, '出纳', '0', '0', null, null, '2017-10-09 15:32:23', '1', '已付款', '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('64', '0', '0', '65', '上级审批', null, 'super', null, null, '0', '0', null, null, '2017-08-07 21:11:40', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('65', '64', '0', '65', '财务审批', null, 'rank', null, '财务总监', '0', '0', null, null, '2017-08-07 21:11:58', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('66', '65', '0', '65', '会计开票', null, 'rank', null, '出纳', '0', '0', null, null, '2021-01-15 12:13:15', '1', '已开票', '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('71', '0', '0', '72', '上级审批', null, 'super', null, null, '0', '0', null, null, '2020-08-12 15:41:30', '1', null, '1', '|upfile1,xuanren', '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('72', '0', '0', '76', '上级审批', null, 'super', null, null, '0', '0', null, null, '2020-07-11 17:29:25', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '3', '赵子龙,张飞,审批人直属上级', 'u7,u6,super');
INSERT INTO `xinhu_flow_course` VALUES ('73', '0', '0', '77', '上级审批', null, 'super', null, null, '0', '0', null, null, '2018-03-06 14:49:42', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('74', '0', '0', '78', '人事审核', null, 'rank', null, '人事主管', '0', '0', null, null, '2018-04-18 19:41:00', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('75', '0', '0', '79', '面试人确认', 'msque', 'field', 'msuserid', '面试人员(msuserid)', '0', '0', null, null, '2018-04-18 22:35:24', '1', '可以面试,不适合', '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('76', '75', '0', '79', '面试结果', null, 'field', 'msuserid', '面试人员(msuserid)', '0', '0', null, null, '2018-04-18 21:34:30', '1', '面试记录', '1', 'state,mscont', '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('77', '76', '0', '79', '安排人确认', null, 'apply', null, null, '0', '0', null, null, '2018-04-18 23:03:09', '1', null, '1', null, '0', '0', null, null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('78', '71', '0', '72', '人事审批', 'rsnum', 'user', '2', '貂蝉', '0', '0', null, null, '2019-04-24 16:14:43', '1', null, '1', null, '0', '0', null, null, null, '1', '1', null, '0', '1', '磐石', 'u5', '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('79', '78', '0', '72', '财务审批', 'cwnum', 'change', 'g3', '部门负责人', '0', '0', null, null, '2019-04-24 19:48:07', '1', null, '1', null, '0', '0', null, null, null, '1', '0', null, '0', '1', '本部门(含下级部门)', 'deptall', '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('82', '79', '0', '72', '最后确认', 'queren', 'apply', null, null, '0', '0', null, null, '2019-02-24 12:17:24', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('83', '0', '0', '87', '接收人接收', null, 'field', 'qiannameid', '签收人(qiannameid)', '0', '0', null, null, '2019-03-09 12:34:20', '1', '接收,拒接', '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '1', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('84', '78', '0', '72', '行政审批', null, 'change', 'd4,d3', '行政人事部,财务部', '0', '0', null, null, '2019-04-25 10:33:38', '1', null, '1', null, '0', '0', null, null, null, '2', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('85', '84', '0', '72', '管理层审批', null, 'change', null, null, '0', '0', null, null, '2019-04-24 23:23:06', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('86', '84', '0', '72', '领导审批', null, 'user', '5', '磐石', '0', '0', null, null, '2019-04-25 20:06:22', '1', null, '0', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('87', '0', '84', '72', '上级处理', null, 'change', 'd2', '开发部', '0', '0', null, null, '2019-04-24 22:11:45', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('88', '0', '0', '92', '上级审批', null, 'super', null, null, '0', '0', null, null, '2019-05-09 17:40:51', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('89', '0', '0', '99', '待办人处理', null, 'field', 'distid', '待办人(distid)', '0', '0', null, null, '2020-01-06 23:22:00', '1', '完成', '1', null, '0', '0', null, null, null, '0', '0', null, '60', '5', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('90', '0', '0', '100', '行政确认', null, 'user', '4', '大乔', '0', '0', null, null, '2020-03-31 18:19:01', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('91', '0', '0', '102', '上级审批', null, 'super', null, null, '0', '0', null, null, '2020-09-08 16:26:48', '1', null, '1', null, '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('109', '0', '0', '116', '审核', null, 'user', '1', '管理员', '0', '0', null, null, '2021-04-29 10:56:47', '1', null, '1', 'rzdept,rzzhiwei', '0', '0', null, null, null, '0', '0', null, '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('127', '-2', '30', '33', '加签', null, 'user', '2', '貂蝉', '0', '1', null, null, '2021-06-25 15:24:05', '1', null, '1', null, '0', '4', '大乔', null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);
INSERT INTO `xinhu_flow_course` VALUES ('126', '-1', '30', '33', '小乔确认', null, 'user', '3', '小乔', '0', '1', null, null, '2021-06-25 15:23:20', '1', null, '1', null, '0', '4', '大乔', null, null, '0', '0', '0', '0', '1', null, null, '0', '0', '0', '0', null, null);

-- ----------------------------
-- Table structure for `xinhu_flow_element`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_element`;
CREATE TABLE `xinhu_flow_element` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0',
  `name` varchar(20) DEFAULT NULL,
  `fields` varchar(50) DEFAULT NULL COMMENT '对应字段',
  `fieldstype` varchar(30) DEFAULT NULL,
  `sort` smallint(6) DEFAULT '0',
  `dev` varchar(100) DEFAULT NULL COMMENT '默认值',
  `isbt` tinyint(1) DEFAULT '0' COMMENT '是否必填',
  `data` varchar(500) DEFAULT NULL COMMENT '数据源',
  `islu` tinyint(1) DEFAULT '1' COMMENT '是否录入元素',
  `iszs` tinyint(1) DEFAULT '1' COMMENT '是否列表展示',
  `attr` varchar(500) DEFAULT NULL COMMENT '属性',
  `iszb` tinyint(1) DEFAULT '0',
  `isss` tinyint(1) DEFAULT '0',
  `lattr` varchar(100) DEFAULT NULL COMMENT '列属性',
  `width` varchar(10) DEFAULT NULL COMMENT '列宽',
  `lens` smallint(6) DEFAULT '0' COMMENT '字段长度',
  `savewhere` varchar(100) DEFAULT NULL COMMENT '保存条件',
  `islb` tinyint(1) DEFAULT '1' COMMENT '是否列表列',
  `ispx` tinyint(1) DEFAULT '0' COMMENT '是否可排序',
  `isalign` tinyint(1) DEFAULT '0' COMMENT '0居中,1居左,2居右',
  `issou` tinyint(1) DEFAULT '0' COMMENT '是否可搜索',
  `istj` tinyint(1) DEFAULT '0' COMMENT '是否可统计',
  `gongsi` varchar(500) DEFAULT NULL COMMENT '计算公式',
  `placeholder` varchar(50) DEFAULT NULL COMMENT '提示内容',
  `isonly` tinyint(1) DEFAULT '0' COMMENT '是否唯一',
  `isdr` tinyint(1) DEFAULT '0' COMMENT '是否导入字段',
  `xiaoshu` smallint(6) DEFAULT '0' COMMENT '小数位数',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`),
  KEY `fields` (`fields`)
) ENGINE=MyISAM AUTO_INCREMENT=1263 DEFAULT CHARSET=utf8 COMMENT='模块元素';

-- ----------------------------
-- Records of xinhu_flow_element
-- ----------------------------
INSERT INTO `xinhu_flow_element` VALUES ('1', '1', '标题', 'title', 'text', '0', null, '1', null, '1', '1', ',font-weight:bold', '0', '0', null, null, '200', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('2', '1', '类型名称', 'typename', 'rockcombo', '2', null, '1', 'gongtype,name,tree', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('3', '1', '内容', 'content', 'htmlediter', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('4', '1', '发送给', 'recename', 'changedeptusercheck', '4', null, '0', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '不选默认为发给全部人员', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('5', '1', '相应地址', 'url', 'text', '5', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('6', '2', '会议室', 'hyname', 'rockcombo', '0', null, '1', 'hyname', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('7', '2', '主题', 'title', 'text', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '100', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('8', '2', '开始时间', 'startdt', 'datetime', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('9', '2', '结束时间', 'enddt', 'datetime', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('10', '2', '参会人', 'joinname', 'changedeptusercheck', '5', null, '1', 'joinid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('11', '2', '说明', 'explain', 'textarea', '8', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('12', '3', '日期', 'dt', 'date', '3', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('13', '3', '日报类型', 'type', 'select', '2', '0', '1', '0|日报,1|周报,2|月报,3|年报', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('14', '3', '截止日期', 'enddt', 'date', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('15', '3', '内容', 'content', 'textarea', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('16', '3', '明日计划', 'plan', 'textarea', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('17', '3', '新增时间', 'adddt', 'hidden', '7', '{now}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('18', '3', '人员', 'optname', 'text', '1', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('19', '2', '发起人', 'optname', 'text', '9', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('20', '4', '类型', 'type', 'rockcombo', '1', null, '1', 'worktype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('21', '4', '等级', 'grade', 'rockcombo', '2', null, '1', 'workgrade', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('22', '4', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('23', '4', '分配给', 'dist', 'changeusercheck', '3', null, '0', 'distid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('24', '4', '说明', 'explain', 'textarea', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('26', '4', '创建人', 'optname', 'text', '11', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('27', '4', '创建时间', 'optdt', 'datetime', '12', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('28', '4', '开始时间', 'startdt', 'datetime', '6', '{now}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('29', '4', '截止时间', 'enddt', 'datetime', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('30', '5', '请假', 'kind', 'fixed', '0', '请假', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('31', '5', '请假类型', 'qjkind', 'rockcombo', '1', null, '1', 'kqqjkind', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('32', '5', '开始时间', 'stime', 'datetime', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('33', '5', '截止时间', 'etime', 'datetime', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('34', '5', '时间(小时)', 'totals', 'number', '4', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('35', '5', '说明', 'explain', 'textarea', '7', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('36', '6', '类型', 'kind', 'fixed', '0', '加班', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('37', '6', '开始时间', 'stime', 'datetime', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('38', '6', '截止时间', 'etime', 'datetime', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('39', '6', '时间(小时)', 'totals', 'number', '4', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('40', '6', '说明', 'explain', 'textarea', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('41', '7', '客户名称', 'name', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '1', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('42', '7', '所属人', 'suoname', 'text', '1', null, '0', null, '0', '1', null, '0', '0', null, null, '50', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('43', '7', '客户类型', 'type', 'rockcombo', '2', null, '1', 'crmtype', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('44', '7', '来源', 'laiyuan', 'rockcombo', '3', null, '1', 'crmlaiyuan', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '1', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('45', '7', '客户单位', 'unitname', 'text', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '如单位全称', '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('47', '7', '联系电话', 'tel', 'text', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('48', '7', '联系手机', 'mobile', 'text', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('49', '7', '邮箱', 'email', 'text', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('50', '7', '说明', 'explain', 'textarea', '21', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('51', '7', '地址', 'address', 'ditumap', '10', null, '0', 'addresslatlng', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('52', '7', '交通路线', 'routeline', 'textarea', '12', null, '0', null, '1', '1', null, '0', '0', null, null, '100', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('53', '8', '客户', 'custid', 'hidden', '0', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('54', '8', '客户', 'custname', 'selectdatafalse', '1', null, '1', 'selectcust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('55', '8', '状态', 'state', 'rockcombo', '8', '0', '1', 'crmstate,value', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('56', '8', '说明', 'explain', 'textarea', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('57', '8', '申请日期', 'applydt', 'date', '2', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('58', '8', '来源', 'laiyuan', 'rockcombo', '3', null, '1', 'custsalelai', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('59', '8', '金额', 'money', 'number', '4', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('60', '8', '添加时间', 'adddt', 'datetime', '5', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('61', '8', '创建人', 'createname', 'text', '6', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('62', '8', '销售人员', 'optname', 'text', '7', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('63', '9', '名称', 'name', 'text', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('64', '9', '对应分类', 'typeid', 'select', '2', null, '1', 'option:getcnumdata,goodstype', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('65', '9', '规格', 'guige', 'text', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('66', '9', '型号', 'xinghao', 'text', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('67', '9', '单价', 'price', 'number', '5', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('68', '9', '单位', 'unit', 'rockcombo', '6', null, '1', 'rockunit', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('69', '9', '说明', 'explain', 'textarea', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('70', '10', '用户Id', 'id', 'number', '23', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('71', '10', '姓名', 'name', 'text', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('72', '10', '部门Id', 'deptid', 'number', '8', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('73', '10', '部门', 'deptname', 'changedept', '9', null, '1', 'deptid', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '1', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('74', '10', '职位', 'ranking', 'text', '11', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '1', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('75', '10', '上级主管', 'superman', 'changeusercheck', '14', null, '0', 'superid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '1', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('76', '10', '上级主管id', 'superid', 'text', '15', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('77', '10', '用户名', 'user', 'text', '2', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '不能包含中文', '1', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('78', '10', '编号', 'num', 'text', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '唯一编号/工号', '1', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('79', '7', '状态', 'status', 'select', '17', '1', '1', '1|启用,0|停用', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('80', '12', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('81', '12', '提醒时间', 'startdt', 'datetime', '1', '{now}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('82', '12', '重复', 'rate', 'select', '2', null, '0', 'd|每天,w|每周,m|每月', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('83', '12', '重复值', 'rateval', 'checkboxall', '3', null, '0', '0|无,1|啊', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('84', '12', '说明', 'explain', 'textarea', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('85', '12', '记事人', 'optname', 'text', '5', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('86', '12', '截止时间', 'enddt', 'datetime', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('87', '12', '提醒', 'txsj', 'select', '7', '0', '1', '0|不提醒,1|提醒', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('88', '21', '打卡时间', 'dkdt', 'datetime', '1', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('89', '21', '打卡类型', 'type', 'text', '2', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('90', '21', '添加时间', 'optdt', 'datetime', '3', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('91', '21', '定位地址', 'address', 'text', '4', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('92', '21', '姓名', 'base_name', 'text', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('93', '22', '项目类型', 'type', 'rockcombo', '2', null, '1', 'projecttype', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('94', '22', '编号', 'num', 'text', '1', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('95', '22', '名称', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('96', '22', '开始时间', 'startdt', 'datetime', '3', '{now}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('97', '22', '预计结束时间', 'enddt', 'datetime', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('98', '22', '负责人', 'fuze', 'changeuser', '5', null, '1', 'fuzeid', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('99', '22', '执行人', 'runuser', 'changedeptusercheck', '6', null, '1', 'runuserid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('100', '22', '进度(%)', 'progress', 'select', '7', '0', '1', 'progressdata', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('101', '22', '内容', 'content', 'htmlediter', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('102', '22', '状态', 'status', 'select', '20', '0', '1', '0|待执行,1|已完成,3|执行中', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('103', '23', '类型', 'atype', 'select', '1', '外出', '1', '外出,出差', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('104', '23', '外出地址', 'address', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('105', '23', '外出时间', 'outtime', 'datetime', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('106', '23', '预计回岗', 'intime', 'datetime', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('107', '23', '外出理由', 'reason', 'text', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('108', '23', '说明', 'explain', 'textarea', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('109', '24', '申请日期', 'applydt', 'text', '0', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('110', '24', '类型', 'type', 'fixed', '0', '0', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('111', '24', '说明', 'explain', 'textarea', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('112', '24', '物品', 'temp_aid', 'selectdatatrue', '0', null, '1', 'getgoodsdata,aid', '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('113', '24', '领用数量', 'count', 'number', '2', '0', '1', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('114', '11', '申请日期', 'applydt', 'text', '0', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('115', '11', '单据数(张)', 'bills', 'number', '0', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('116', '11', '收款人全称', 'fullname', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('117', '11', '付款方式', 'paytype', 'rockcombo', '0', null, '1', 'paytype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('118', '11', '收款帐号', 'cardid', 'text', '0', '', '1', '', '1', '1', '', '0', '0', '', '', '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('119', '11', '开户行', 'openbank', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('120', '11', '报销金额', 'money', 'number', '0', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('121', '11', '大写金额', 'moneycn', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('122', '11', '发生日期', 'sdt', 'date', '0', null, '1', null, '1', '1', '', '1', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('123', '11', '报销项目', 'name', 'rockcombo', '0', null, '1', 'finaitems', '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('124', '11', '金额', 'money', 'number', '0', '0', '1', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('125', '11', '说明', 'sm', 'text', '0', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('126', '24', '单位', 'unit', 'text', '3', null, '0', null, '0', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('127', '25', '申请日期', 'applydt', 'text', '0', '{date}', '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('128', '25', '附单据(张)', 'bills', 'number', '1', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('129', '25', '出差目的', 'purpose', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('130', '25', '出差成果', 'purresult', 'text', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('131', '25', '收款人全称', 'fullname', 'text', '4', '', '1', '', '1', '1', '', '0', '0', '', '', '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('132', '25', '付款方式', 'paytype', 'rockcombo', '5', '', '1', 'paytype', '1', '1', '', '0', '0', '', '', '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('133', '25', '收款帐号', 'cardid', 'text', '6', '', '1', '', '1', '1', '', '0', '0', '', '', '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('134', '25', '开户行', 'openbank', 'text', '7', '', '1', '', '1', '1', '', '0', '0', '', '', '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('135', '25', '报销金额', 'money', 'text', '8', '0', '1', '', '1', '1', '', '0', '0', '', '', '0', null, '1', '0', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('136', '25', '大写金额', 'moneycn', 'text', '9', '', '1', '', '1', '1', '', '0', '0', '', '', '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('137', '25', '发生日期', 'sdt', 'date', '0', '', '1', '', '1', '1', '', '1', '0', '', '', '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('138', '25', '报销项目', 'name', 'rockcombo', '1', '', '1', 'finaitems', '1', '1', '', '1', '0', '', '', '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('139', '25', '金额', 'money', 'number', '2', '0', '1', '', '1', '1', '', '1', '0', '', '', '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('140', '25', '说明', 'sm', 'text', '4', '', '0', '', '1', '1', '', '1', '0', '', '', '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('141', '25', '发生地点', 'didian', 'text', '3', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('142', '25', '说明', 'explain', 'textarea', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('143', '25', '单据类型', 'type', 'fixed', '11', '1', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('144', '26', '单据类型', 'type', 'fixed', '0', '2', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('145', '26', '申请日期', 'applydt', 'text', '1', '{date}', '1', null, '1', '1', 'readonly', '0', '1', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('146', '26', '借款用途', 'purpose', 'text', '2', null, '1', null, '1', '1', null, '0', '1', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('147', '26', '收款人全称', 'fullname', 'text', '3', null, '1', null, '1', '1', null, '0', '1', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('148', '26', '付款方式', 'paytype', 'rockcombo', '4', null, '1', 'paytype', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('149', '26', '收款帐号', 'cardid', 'text', '5', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('150', '26', '开户行', 'openbank', 'text', '6', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('151', '26', '借款金额', 'money', 'text', '7', '0', '1', null, '1', '1', null, '0', '1', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('152', '26', '借款金额大写', 'moneycn', 'text', '8', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('153', '26', '说明', 'explain', 'textarea', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('154', '11', '说明', 'explain', 'textarea', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('155', '27', '类型', 'type', 'fixed', '0', '3', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('156', '27', '申请日期', 'applydt', 'text', '0', '{date}', '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('157', '27', '付款方式', 'paytype', 'rockcombo', '0', null, '1', 'paytype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('158', '27', '收款人全称', 'fullname', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('159', '27', '收款帐号', 'cardid', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('160', '27', '还款金额', 'money', 'number', '0', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('161', '27', '还款金额大写', 'moneycn', 'text', '0', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('162', '27', '说明', 'explain', 'textarea', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('163', '27', '需还款金额', 'temp_money', 'auto', '0', null, '0', 'jiekuantongs', '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('164', '28', '申请日期', 'applydt', 'text', '0', '{date}', '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('165', '28', '说明', 'explain', 'textarea', '1', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('166', '28', '类型', 'type', 'fixed', '2', '1', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('168', '28', '供应商名称', 'custname', 'selectdatafalse', '3', null, '1', 'goods:getgys,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('169', '28', '采购金额', 'money', 'number', '5', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', '[{zb0.count}*{zb0.price}] - [{discount}]', null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('170', '28', '物品', 'temp_aid', 'selectdatatrue', '0', null, '1', 'getgoodsdata,aid', '1', '1', null, '1', '0', '', '', '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('171', '28', '采购数量', 'count', 'number', '2', null, '1', '', '1', '1', '', '1', '0', '', '', '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('172', '28', '单位', 'unit', 'text', '3', null, '0', null, '1', '1', 'readonly', '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('173', '28', '单价', 'price', 'number', '4', '0', '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('174', '7', '供应商', 'isgys', 'checkbox', '19', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('175', '19', '标题', 'title', 'text', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('176', '19', '主题词', 'titles', 'text', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '多个空格分开', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('177', '19', '公文等级', 'grade', 'rockcombo', '6', null, '1', 'officialgrade', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('178', '19', '公文类型', 'class', 'rockcombo', '7', null, '1', 'officialclass', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('179', '19', '公文编号', 'num', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('180', '19', '公文内容', 'content', 'htmlediter', '11', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('181', '19', '拟办意见', 'explain', 'textarea', '13', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('182', '19', '需要查阅', 'recename', 'changedeptusercheck', '16', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '选可查阅人员对象', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('183', '20', '标题', 'title', 'text', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('184', '20', '主题词', 'titles', 'text', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, '多个空格分开', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('185', '20', '公文等级', 'grade', 'rockcombo', '6', null, '1', 'officialgrade', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('186', '20', '公文密级', 'miji', 'rockcombo', '8', null, '1', 'officialmiji', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('187', '20', '公文编号', 'num', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('188', '20', '公文内容', 'content', 'htmlediter', '11', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('189', '20', '说明', 'explain', 'textarea', '12', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('190', '20', '需要查阅', 'recename', 'changedeptusercheck', '13', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('191', '20', '类型', 'type', 'fixed', '2', '1', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('192', '4', '所属项目', 'projectid', 'select', '4', null, '0', 'projectdata', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('193', '30', '类型', 'kind', 'rockcombo', '1', '请假', '1', 'kqkind', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('199', '30', '对应人员', 'uname', 'changeuser', '0', null, '1', 'uid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('195', '30', '开始时间', 'stime', 'datetime', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('196', '30', '截止时间', 'etime', 'datetime', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('197', '30', '时间(小时)', 'totals', 'number', '1', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('198', '30', '说明', 'explain', 'textarea', '2', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('200', '2', '状态', 'state', 'text', '10', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('201', '1', '来源', 'zuozhe', 'text', '6', '{deptname}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('202', '1', '日期', 'indate', 'date', '7', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('203', '29', '电话', 'tel', 'text', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('204', '29', '手机号', 'mobile', 'text', '10', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('205', '29', '邮箱', 'email', 'text', '11', null, '0', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('206', '29', '生日类型', 'birtype', 'select', '12', '0', '1', '0|阳历', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('207', '29', '生日', 'birthday', 'date', '13', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '选择身份证上日期', '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('208', '29', '学历', 'xueli', 'text', '14', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('209', '29', '民族', 'minzu', 'text', '15', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('210', '29', '籍贯', 'jiguan', 'selectdatafalse', '16', null, '1', 'city:alldata', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('211', '29', '婚姻', 'hunyin', 'select', '9', null, '1', '未婚,已婚', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('212', '29', '现住址', 'nowdizhi', 'text', '8', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('213', '29', '家庭住址', 'housedizhi', 'text', '7', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('214', '29', '姓名', 'name', 'text', '0', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('215', '29', '部门', 'deptname', 'text', '1', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('216', '29', '职位', 'ranking', 'text', '2', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('217', '29', '人员状态', 'state', 'rockcombo', '3', null, '0', 'userstate,value', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('218', '31', '合同名称', 'name', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('219', '31', '开始日期', 'startdt', 'date', '6', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('220', '31', '截止日期', 'enddt', 'date', '7', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('221', '31', '合同类型', 'httype', 'rockcombo', '5', null, '1', 'userhttype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('222', '31', '状态', 'state', 'text', '8', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('223', '31', '签署单位', 'company', 'hidden', '3', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('224', '31', '签署人', 'uname', 'changeuser', '0', null, '1', 'uid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('225', '31', '说明', 'explain', 'textarea', '11', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('226', '31', '提前终止日期', 'tqenddt', 'date', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('227', '32', '职位', 'ranking', 'text', '3', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('228', '32', '入职日期', 'entrydt', 'text', '4', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('229', '32', '试用到期日', 'syenddt', 'date', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('230', '32', '转正日期', 'positivedt', 'date', '6', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('231', '32', '申请说明', 'explain', 'textarea', '7', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('232', '33', '职位', 'ranking', 'text', '2', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('233', '33', '入职日期', 'entrydt', 'text', '4', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('234', '33', '离职类型', 'redundtype', 'rockcombo', '5', null, '1', 'redundtype', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('235', '33', '离职日期', 'quitdt', 'date', '6', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('236', '33', '离职原因', 'redundreson', 'textarea', '7', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('237', '33', '说明', 'explain', 'textarea', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('238', '34', '月份', 'month', 'selectdatafalse', '1', '{month-1}', '1', 'selectmonth', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '1', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('239', '34', '对应人', 'uname', 'changeuser', '0', '{admin}|{uid}', '1', 'xuid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('240', '34', '基本工资', 'base', 'number', '1', '1500', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('241', '34', '岗位工资', 'postjt', 'number', '1', '3500', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('242', '34', '技能津贴', 'skilljt', 'number', '1', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('243', '34', '交通补贴', 'travelbt', 'number', '1', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('244', '34', '通信补贴', 'telbt', 'number', '1', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('245', '34', '实发工资', 'money', 'text', '100', '0', '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '1', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('246', '34', '对应人员部门', 'udeptname', 'text', '0', '{deptname}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('247', '34', '职位', 'ranking', 'text', '0', '{ranking}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('248', '34', '奖励', 'reward', 'number', '20', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('249', '34', '处罚', 'punish', 'number', '40', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('250', '34', '说明', 'explain', 'textarea', '101', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('251', '34', '个人社保', 'socials', 'number', '41', '181', '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('252', '34', '个人所得税', 'taxes', 'number', '42', '0', '0', null, '0', '1', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('253', '29', '开户行', 'bankname', 'text', '18', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('254', '29', '工资卡帐号', 'banknum', 'text', '17', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('255', '34', '其它增加', 'otherzj', 'number', '20', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('256', '34', '其它减少', 'otherjs', 'number', '43', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('257', '34', '迟到(次)', 'cidao', 'text', '50', '0', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('258', '34', '迟到处罚', 'cidaos', 'number', '50', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('259', '34', '早退(次)', 'zaotui', 'text', '50', '0', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('260', '34', '早退处罚', 'zaotuis', 'number', '50', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('261', '34', '请假(小时)', 'leave', 'text', '51', '0', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('262', '34', '请假减少', 'leaves', 'number', '51', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('263', '34', '加班(小时)', 'jiaban', 'text', '20', '0', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('264', '34', '加班补贴', 'jiabans', 'number', '20', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('265', '34', '未打卡(次)', 'weidk', 'text', '50', '0', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('266', '34', '未打卡处罚', 'weidks', 'number', '50', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('267', '35', '客户名称', 'custid', 'hidden', '2', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('268', '35', '客户名称', 'custname', 'selectdatafalse', '3', null, '1', 'selectcust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('269', '35', '销售机会', 'saleid', 'select', '5', null, '0', 'selectsale', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('270', '35', '签约日期', 'signdt', 'date', '6', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('271', '35', '合同金额', 'money', 'number', '7', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('272', '35', '生效日期', 'startdt', 'date', '8', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('273', '35', '截止日期', 'enddt', 'date', '9', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('274', '35', '合同内容', 'content', 'textarea', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('275', '35', '说明', 'explain', 'textarea', '13', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('276', '35', '合同编号', 'num', 'num', '0', null, '1', 'HT-', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('277', '35', '合同类型', 'type', 'select', '11', '0', '1', '0|收款合同,1|付款合同', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('278', '36', '合同', 'htid', 'select', '1', null, '0', 'hetongdata', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('279', '36', '合同/销售单', 'htnum', 'hidden', '2', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('280', '36', '客户名称', 'custname', 'selectdatafalse', '4', null, '1', 'selectcust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('281', '36', '类型', 'type', 'select', '6', null, '1', '0|收款单,1|付款单', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('282', '36', '金额', 'money', 'number', '7', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('283', '36', '说明', 'explain', 'textarea', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('284', '35', '待收/付金额', 'moneys', 'text', '12', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('285', '36', '客户名称', 'custid', 'hidden', '5', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('286', '36', '是否付款', 'ispay', 'select', '8', '0', '1', '0|否,1|是', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('287', '36', '收付款时间', 'paydt', 'datetime', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('288', '7', '联系人', 'linkname', 'text', '20', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('289', '36', '所属日期', 'dt', 'date', '3', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('290', '38', '要调动人', 'tranname', 'changeuser', '0', null, '1', 'tranuid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('291', '38', '调动类型', 'trantype', 'rockcombo', '0', null, '1', 'transfertype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('292', '38', '原来部门', 'olddeptname', 'text', '0', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('293', '38', '原来职位', 'oldranking', 'text', '0', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('294', '38', '调动后部门', 'newdeptname', 'changedept', '0', null, '1', 'newdeptid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('295', '38', '调动后职位', 'newranking', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('296', '38', '生效日期', 'effectivedt', 'date', '0', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('297', '38', '说明', 'explain', 'textarea', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('298', '37', '调薪幅度', 'floats', 'number', '4', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('299', '37', '生效日期', 'effectivedt', 'date', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('300', '37', '申请日期', 'applydt', 'date', '3', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('301', '37', '说明', 'explain', 'textarea', '6', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('302', '37', '职位', 'ranking', 'text', '2', '{ranking}', '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('303', '39', '奖惩对象', 'object', 'changeuser', '3', null, '1', 'objectid', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('304', '39', '奖惩类型', 'type', 'select', '6', '0', '1', '0|奖励,1|处罚', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('305', '39', '奖惩结果', 'result', 'rockcombo', '7', null, '1', 'rewardresult', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('306', '39', '奖惩金额', 'money', 'number', '8', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('307', '39', '说明', 'explain', 'textarea', '9', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('308', '39', '申请日期', 'applydt', 'text', '2', '{date}', '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('309', '39', '发生时间', 'happendt', 'datetime', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('310', '39', '发生地点', 'hapaddress', 'text', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('311', '40', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '50', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('312', '40', '对应分类', 'typeid', 'select', '0', null, '1', 'option:getcnumdata,knowledgetype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('313', '40', '排序', 'sort', 'number', '0', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('314', '40', '内容', 'content', 'htmlediter', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('315', '41', '资产分类', 'typeid', 'select', '0', null, '1', 'option:getcnumdata,assetstype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('316', '41', '编号', 'num', 'num', '1', null, '0', 'ZC-', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '1', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('317', '41', '名称', 'title', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('318', '41', '品牌', 'brand', 'rockcombo', '3', null, '0', 'rockbrand', '1', '1', null, '0', '0', null, null, '0', null, '0', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('319', '41', '所在位置', 'address', 'text', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '存放的位置', '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('320', '41', '规格型号', 'model', 'text', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('321', '41', '资产来源', 'laiyuan', 'rockcombo', '6', null, '1', 'wplaiyuan', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('322', '41', '状态', 'state', 'select', '7', '0', '1', '0|闲置,1|在用,2|维修,3|报废,4|丢失', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('323', '41', '购进日期', 'buydt', 'date', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('324', '41', '价格', 'price', 'number', '11', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('325', '41', '说明', 'explain', 'textarea', '30', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('326', '41', '使用者', 'usename', 'changedeptusercheck', '15', null, '0', 'useid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('327', '42', '车牌号', 'carnum', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('328', '42', '车辆类型', 'cartype', 'rockcombo', '1', null, '1', 'cartype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('329', '42', '车辆品牌', 'carbrand', 'rockcombo', '2', null, '1', 'carbrand', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('330', '42', '型号', 'carmode', 'text', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('331', '42', '购买日期', 'buydt', 'date', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('332', '42', '购买价格', 'buyprice', 'number', '6', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('333', '42', '车架号', 'framenum', 'text', '7', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('334', '42', '发动机号', 'enginenb', 'text', '8', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('335', '42', '公开使用', 'ispublic', 'checkbox', '9', '1', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('336', '42', '说明', 'explain', 'textarea', '50', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('337', '42', '状态', 'state', 'select', '11', '1', '1', '0|办理中,1|可用,2|维修中,3|报废', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('516', '58', '状态', 'ztname', 'text', '11', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('515', '42', '登记数', 'djshu', 'text', '51', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('346', '43', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '50', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('347', '43', '对应分类', 'typeid', 'select', '1', null, '1', 'option:getcnumdata,knowtikutype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('348', '43', '类型', 'type', 'select', '2', '0', '1', '0|单选,1|多选,2|判断题', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('349', '43', 'A', 'ana', 'text', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('350', '43', 'B', 'anb', 'text', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('351', '43', 'C', 'anc', 'text', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('352', '43', 'D', 'and', 'text', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('353', '43', '答案', 'answer', 'text', '9', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('354', '43', '状态', 'status', 'select', '10', '1', '1', '0|停用,1|启用', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('355', '43', '说明', 'explain', 'textarea', '11', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('356', '43', '题内容', 'content', 'htmlediter', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('357', '44', '使用者', 'usename', 'changedeptusercheck', '0', null, '1', 'useid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('358', '44', '使用人数', 'useren', 'number', '1', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('359', '44', '开始时间', 'startdt', 'datetime', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('360', '44', '截止时间', 'enddt', 'datetime', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('361', '44', '目的地', 'address', 'text', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('362', '44', '线路', 'xianlines', 'text', '5', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('363', '44', '预定车辆', 'carid', 'hidden', '6', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('364', '44', '车牌号', 'carnum', 'selectdatafalse', '7', null, '1', 'getcardata,carid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('365', '44', '驾驶员', 'jianame', 'changeusercheck', '8', null, '1', 'jiaid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('366', '44', '说明', 'explain', 'textarea', '12', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('367', '45', '书名', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('368', '45', '对应分类', 'typeid', 'select', '0', '0', '1', 'option:getcnumdata,booktype', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('369', '45', '编号', 'num', 'num', '0', null, '1', 'TS-', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '1', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('370', '45', '作者', 'author', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('371', '45', '出版社', 'chuban', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('372', '45', '出版日期', 'cbdt', 'date', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('373', '45', '价格', 'price', 'number', '0', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('374', '45', '数量', 'shul', 'number', '0', '1', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('375', '45', '说明', 'explain', 'textarea', '20', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('376', '45', '存放位置', 'weizhi', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('377', '45', 'ISBN', 'isbn', 'text', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('378', '46', '借阅书名', 'bookid', 'hidden', '0', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('379', '46', '借阅书名', 'bookname', 'selectdatafalse', '0', null, '1', 'getbookdata,bookid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('380', '46', '借阅日期', 'jydt', 'date', '0', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('381', '46', '预计归还', 'yjdt', 'date', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', 'gt|{jydt}|{0}必须大于借阅日期', '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('382', '46', '归还时间', 'ghtime', 'datetime', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', 'gt|{jydt}|{0}必须大于借阅日期,lt|{now}|{0}必须小于当前时间', '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('383', '46', '是否归返', 'isgh', 'select', '0', '0', '0', '0|否,1|是', '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('384', '46', '说明', 'explain', 'textarea', '20', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('385', '4', '督导人', 'ddname', 'changeusercheck', '8', null, '0', 'ddid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('386', '4', '任务分值', 'score', 'number', '9', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('387', '3', '评分分数', 'mark', 'text', '8', '0', '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('388', '47', '主题', 'title', 'text', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('389', '47', '发送给', 'recename', 'changedeptusercheck', '2', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('390', '47', '邮件内容', 'content', 'htmlediter', '10', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('391', '47', '抄送给', 'ccname', 'changeusercheck', '3', null, '0', 'ccid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('392', '47', '立即发送', 'isturn', 'checkbox', '11', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('393', '47', '发送人', 'sendname', 'text', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('395', '48', '名称', 'name', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('396', '48', '类型', 'type', 'rockcombo', '0', null, '1', 'sealtype', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('397', '48', '保管人', 'bgname', 'changeusercheck', '0', null, '1', 'bgid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('398', '49', '对应ID', 'sealid', 'hidden', '1', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('399', '49', '印章/证照', 'sealname', 'selectdatatrue', '2', null, '1', 'getsealdata,sealid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('400', '49', '使用方式', 'isout', 'select', '3', '0', '1', 'usefangshi', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('401', '49', '说明', 'explain', 'textarea', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('402', '5', '剩余考勤统计', 'temp_kqtotal', 'auto', '5', null, '0', 'getshentime', '1', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('403', '41', '封面图片', 'fengmian', 'uploadimg', '16', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('404', '47', '是否外发', 'type', 'select', '1', '0', '1', '0|否,1|是', '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('405', '51', '异常类型', 'errtype', 'rockcombo', '1', null, '1', 'kqerrtype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('406', '51', '异常日期', 'dt', 'date', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', 'elt|{date}|异常日期应小于当前日期', '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('407', '51', '应打卡时间', 'ytime', 'time', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('408', '51', '说明', 'explain', 'textarea', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('409', '52', '流程模块', 'modeid', 'select', '0', '0', '1', 'modebill', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('410', '52', '流程模块', 'modename', 'hidden', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('411', '52', '作废单号', 'tonum', 'select', '3', null, '1', 'gettonum', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('412', '52', '作废类型', 'type', 'select', '4', '0', '1', '0|作废单据,1|删除单据', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('413', '52', '说明', 'explain', 'textarea', '10', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('414', '53', '姓名', 'name', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('415', '53', '手机号', 'mobile', 'text', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('416', '53', '电话', 'tel', 'text', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('417', '53', '邮箱', 'email', 'email', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('418', '53', '所在组', 'gname', 'select', '0', null, '0', 'getgname', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('419', '53', '地址', 'address', 'text', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('420', '53', '单位名称', 'unitname', 'text', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('421', '29', '照片', 'zhaopian', 'uploadimg', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('422', '29', '身份证号', 'idnum', 'text', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('423', '29', '备用联系人', 'spareman', 'text', '19', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('424', '29', '备用联系人电话', 'sparetel', 'text', '20', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('425', '29', '开始日期', 'startdt', 'date', '0', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('426', '29', '截止日期', 'enddt', 'date', '1', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('427', '29', '职位', 'rank', 'text', '2', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('428', '29', '单位名称', 'unitname', 'text', '3', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('429', '29', '开始日期', 'startdt', 'date', '0', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('430', '29', '截止日期', 'enddt', 'date', '1', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('431', '29', '专业', 'rank', 'text', '2', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('432', '29', '学校名称', 'unitname', 'text', '3', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('433', '1', '封面图片', 'fengmian', 'uploadimg', '1', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('434', '19', '正文文件', 'filecontid', 'uploadfile', '10', null, '0', 'doc,docx,pdf,jpg,png,xls,xlsx,ppt,pptx', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('435', '20', '公文文件', 'filecontid', 'uploadfile', '9', null, '0', 'doc,docx,pdf', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('436', '12', '提醒给', 'recename', 'changedeptusercheck', '8', null, '0', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('437', '2', '会议纪要人', 'jyname', 'changeusercheck', '11', null, '0', 'jyid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('438', '2', '会议纪要内容', 'content', 'htmlediter', '12', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('439', '54', '对应会议', 'mid', 'select', '0', null, '1', 'meetdata', '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('440', '54', '会议纪要内容', 'content', 'htmlediter', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('441', '54', '类型', 'type', 'fixed', '3', '2', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('442', '54', '会议主题', 'title', 'hidden', '1', null, '0', null, '0', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('443', '44', '起始公里数', 'kmstart', 'number', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('444', '44', '结束公里数', 'kmend', 'number', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', 'gt|{kmstart}|结束公里数必须大于起始公路数', '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('445', '44', '归还时间', 'returndt', 'datetime', '11', null, '0', null, '0', '1', null, '0', '0', null, null, '0', 'gt|{startdt}|归还时间必须大于开始时间', '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('446', '10', '入职日期', 'workdate', 'date', '5', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('447', '10', '性别', 'sex', 'select', '6', '男', '1', '男,女', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('448', '10', '名字拼音', 'pingyin', 'text', '16', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '拼音全拼(方便人员搜索)', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('449', '10', '手机号', 'mobile', 'text', '7', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '1', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('451', '1', '操作人', 'optname', 'text', '8', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('452', '3', '操作时间', 'optdt', 'datetime', '9', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('453', '3', '部门', 'deptname', 'text', '0', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('454', '22', '任务数', 'workshu', 'number', '9', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('456', '7', '合同数', 'htshu', 'number', '22', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('455', '7', '标★', 'isstat', 'select', '18', '0', '1', '0|否,1|是', '1', '1', null, '0', '0', null, null, '0', null, '0', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('457', '7', '销售总额', 'moneyz', 'number', '23', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('458', '7', '待收金额', 'moneyd', 'number', '24', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('459', '29', '入职日期', 'workdate', 'date', '21', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('460', '29', '转正日期', 'positivedt', 'date', '23', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('461', '28', '物品Id', 'aid', 'hidden', '1', '0', '1', null, '1', '0', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('462', '24', '物品Id', 'aid', 'hidden', '1', '0', '1', null, '1', '0', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('463', '32', '申请日期', 'applydt', 'date', '2', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('467', '33', '申请日期', 'applydt', 'date', '3', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('474', '56', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('475', '56', '考试时间(分钟)', 'kstime', 'select', '2', '10', '1', 'kstimelist', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('484', '56', '状态', 'state', 'select', '16', '0', '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('476', '56', '开始时间', 'startdt', 'datetime', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('477', '56', '截止时间', 'enddt', 'datetime', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('478', '56', '多选题数', 'dxshu', 'number', '6', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('479', '56', '单选题数', 'dsshu', 'number', '5', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('480', '56', '考试对象', 'recename', 'changedeptusercheck', '8', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('481', '56', '培训人数', 'reshu', 'number', '10', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('482', '56', '已答数', 'ydshu', 'number', '11', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('483', '56', '说明', 'explain', 'textarea', '14', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('485', '56', '总分', 'zfenshu', 'number', '12', '100', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('486', '56', '合格分数', 'hgfen', 'number', '13', '60', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('487', '6', '加班兑换', 'jiatype', 'select', '6', '0', '1', '0|调休,1|加班费', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('488', '6', '加班费', 'jiafee', 'number', '7', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('489', '48', '相关图片', 'sealimg', 'uploadimg', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('490', '48', '说明', 'explain', 'textarea', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('491', '56', '培训题库', 'tikuname', 'selectdatatrue', '9', null, '0', 'tikunamedata,tikuid', '1', '1', 'placeholder=\"不选/为空为全部题库\"', '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('492', '56', '题库id', 'tikuid', 'hidden', '1', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('504', '58', '车辆', 'carid', 'select', '0', null, '1', 'getcardata', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('505', '58', '登记类型', 'otype', 'rockcombo', '1', null, '1', 'carotype', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('506', '58', '开始日期', 'startdt', 'date', '2', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('507', '58', '截止日期', 'enddt', 'date', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('508', '58', '驾驶员', 'jianame', 'changeusercheck', '4', null, '0', 'jiaid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('509', '58', '对应名称', 'unitname', 'text', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('510', '58', '金额', 'money', 'number', '6', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('511', '58', '地点', 'address', 'text', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('512', '58', '说明', 'explain', 'textarea', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('513', '58', '登记人', 'optname', 'text', '9', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('514', '58', '登记时间', 'optdt', 'text', '10', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('517', '35', '状态', 'statetext', 'text', '14', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('518', '35', '创建人', 'createname', 'text', '15', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('519', '35', '拥有者', 'optname', 'text', '4', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('520', '36', '创建人', 'createname', 'text', '11', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('521', '36', '所属人', 'optname', 'text', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('522', '59', '维修车辆', 'carid', 'select', '0', '0', '1', 'getcardata', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('523', '59', '维修原因', 'reason', 'textarea', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('524', '59', '维修地点', 'address', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('525', '59', '驾驶员', 'jianame', 'changeusercheck', '3', null, '1', 'jiaid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('526', '59', '更换部件', 'bujian', 'text', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '200', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('527', '59', '维修时间', 'startdt', 'datetime', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('528', '59', '维修截止时间', 'enddt', 'datetime', '6', null, '1', null, '1', '1', null, '0', '0', null, null, '0', 'gt|{startdt}|截止时间必须大于开始时间', '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('529', '59', '维修金额', 'money', 'number', '7', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('530', '59', '说明', 'explain', 'textarea', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('531', '60', '保养车辆', 'carid', 'select', '0', '0', '1', 'getcardata', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('532', '60', '保养原因', 'reason', 'textarea', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('533', '60', '保养地点', 'address', 'text', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('534', '60', '驾驶员', 'jianame', 'changeusercheck', '2', null, '1', 'jiaid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('535', '60', '保养内容', 'bujian', 'text', '5', null, '0', null, '0', '1', null, '0', '0', null, null, '200', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('536', '60', '保养时间', 'startdt', 'datetime', '6', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('537', '60', '取车时间', 'enddt', 'datetime', '7', null, '0', null, '0', '1', null, '0', '0', null, null, '0', 'gt|{startdt}|截止时间必须大于开始时间', '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('538', '60', '保养金额', 'money', 'number', '8', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('539', '60', '说明', 'explain', 'textarea', '12', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('540', '60', '当前保养公里数', 'kmshu', 'text', '9', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('541', '60', '下次保养日期', 'nextdt', 'date', '10', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('542', '60', '下次保养公里数', 'kmnshu', 'text', '11', null, '0', null, '0', '1', null, '0', '0', null, null, '0', 'gt|{kmshu}|下次保养公里数必须大于当前公里数', '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('543', '60', '单据类型', 'type', 'fixed', '1', '1', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('544', '12', '状态', 'status', 'select', '9', '1', '1', '1|启用,0|停用', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('556', '61', '主Id', 'mid', 'text', '1', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('546', '61', '开始时间', 'startdt', 'datetime', '3', '{now}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('547', '61', '重复', 'rate', 'hidden', '5', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('548', '61', '重复值', 'rateval', 'hidden', '7', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('549', '61', '提醒内容', 'explain', 'textarea', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('555', '61', '模块编号', 'modenum', 'text', '0', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('551', '61', '截止时间', 'enddt', 'datetime', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('553', '61', '提醒给', 'recename', 'changedeptusercheck', '6', null, '0', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('554', '61', '状态', 'status', 'select', '10', '1', '1', '1|启用,0|停用', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('557', '61', '频率设置', 'temp_rateval', 'auto', '9', null, '0', 'ratevalset', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('558', '61', '提醒频率', 'ratecont', 'text', '8', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('559', '61', '操作时间', 'optdt', 'datetime', '11', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('560', '61', '操作人', 'optname', 'text', '12', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('561', '4', '得分', 'mark', 'text', '10', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('562', '28', '优惠价格', 'discount', 'number', '4', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('563', '62', '报修资产', 'assetm', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '100', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('564', '62', '保修原因', 'reason', 'textarea', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('565', '62', '实际原因', 'reasons', 'textarea', '3', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('566', '62', '需要外修', 'iswx', 'select', '2', '0', '0', '0|否,1|是', '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('567', '62', '维修金额', 'money', 'number', '5', '0', '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('569', '62', '维修人员', 'wxname', 'text', '6', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('570', '62', '说明', 'explain', 'textarea', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('571', '63', '名称', 'name', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('572', '63', '所属单位', 'pid', 'select', '5', '0', '1', 'companydata', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('573', '63', '对应负责人', 'fuzename', 'changeusercheck', '6', null, '0', 'fuzeid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('574', '63', '所在城市', 'city', 'text', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('575', '63', '地址', 'address', 'text', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('576', '63', '电话', 'tel', 'text', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('577', '63', '传真', 'fax', 'text', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('578', '63', '排序号', 'sort', 'number', '11', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('579', '63', '对应英文名', 'nameen', 'text', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('580', '63', 'ID', 'id', 'text', '12', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('581', '31', '签署单位', 'companyid', 'select', '4', null, '1', 'companydata', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('582', '31', '部门', 'deptname', 'text', '1', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('585', '10', '微信号', 'weixinid', 'text', '19', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '手机号不能作为微信号', '1', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('583', '10', '电话', 'tel', 'text', '17', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('584', '10', '邮箱', 'email', 'email', '18', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '1', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('586', '10', '所属单位', 'companyid', 'select', '20', null, '1', 'companydata', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('588', '10', '启用', 'status', 'select', '21', '1', '0', '1|启用,0|停用', '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('589', '10', '管理员', 'type', 'select', '22', '0', '0', '0|普通,1|管理员', '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('590', '10', '排序号', 'sort', 'number', '24', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('591', '10', '头像', 'face', 'uploadimg', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('592', '10', '所在组', 'groupname', 'checkboxall', '25', null, '0', 'groupdata', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('593', '29', '所属单位', 'companyid', 'select', '25', null, '0', 'companydata', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('594', '29', '试用期到', 'syenddt', 'date', '22', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('595', '29', '离职日期', 'quitdt', 'date', '24', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('596', '10', '密码', 'pass', 'text', '3', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '新增默认123456,不填不修改密码', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('597', '9', '编号', 'num', 'num', '0', '0', '1', 'WP-', '1', '1', 'min=\"0\"', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '1', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('598', '5', '请假天数', 'totday', 'text', '6', '0', '0', null, '1', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('599', '30', '天数', 'totday', 'text', '1', '0', '0', null, '1', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('600', '34', '是否发放', 'ispay', 'text', '102', '0', '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('601', '64', '付款编号', 'num', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '可为合同号/订单号', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('602', '64', '付款金额', 'money', 'number', '1', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('603', '64', '付款日期', 'paydt', 'date', '2', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('604', '64', '收款单位', 'fullname', 'selectdatafalse', '3', null, '1', 'selectcust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '填写全称，可从自己客户选择', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('605', '64', '收款银行', 'openbank', 'text', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('606', '64', '收款帐号', 'cardid', 'text', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('607', '64', '付款方式', 'paytype', 'rockcombo', '6', null, '1', 'paytype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('608', '64', '说明', 'explain', 'textarea', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('609', '64', '类型', 'type', 'fixed', '8', '4', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('611', '65', '开票名称', 'name', 'text', '1', null, '0', null, '1', '1', null, '0', '0', null, null, '50', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('610', '65', '类型', 'type', 'fixed', '0', '5', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('612', '65', '开票金额', 'money', 'number', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('613', '65', '开票日期', 'paydt', 'date', '3', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('614', '65', '公司单位名称', 'fullname', 'selectdatafalse', '4', null, '1', 'selectcust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '从自己客户选择，选了就不要在换', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('615', '65', '纳税识别号', 'shibieid', 'text', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '开企业发票这必须填写', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('616', '65', '开户行', 'openbank', 'text', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('617', '65', '开户帐号', 'cardid', 'text', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('618', '65', '开票类型', 'paytype', 'rockcombo', '10', null, '1', 'openpiaotype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('619', '65', '说明', 'explain', 'textarea', '12', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('620', '65', '公司地址', 'address', 'text', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('621', '65', '联系电话', 'tel', 'text', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('622', '65', '相关单号', 'num', 'text', '11', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '可填写合同号/订单号', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('623', '53', '性别', 'sex', 'select', '0', null, '1', '男,女', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('624', '66', '对应模块', 'setid', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('625', '66', '通知内容摘要', 'summary', 'textarea', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('626', '1', '开始时间', 'startdt', 'datetime', '11', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '投票时需开始时间', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('627', '1', '截止时间', 'enddt', 'datetime', '12', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '投票时需截止时间', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('628', '1', '至少投票', 'mintou', 'number', '9', '0', '0', null, '1', '1', 'min=\"0\"', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '为0不填说明就不用投票', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('629', '1', '最多投票', 'maxtou', 'number', '10', '0', '0', null, '1', '1', 'min=\"0\"', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '0不限制', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('630', '1', '投票选项', 'touitems', 'text', '0', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('631', '19', '发文字号', 'zinum', 'rockcombo', '0', null, '1', 'officiazinum', '1', '1', null, '0', '0', null, null, '30', null, '0', '0', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('632', '19', '主送单位', 'unitname', 'selectdatatrue', '8', null, '1', 'getofficiaunit', '1', '1', null, '0', '0', null, null, '50', null, '1', '0', '0', '1', '0', null, '可输入多个,分开', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('633', '20', '来文日期', 'laidt', 'date', '1', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('634', '20', '来文单位', 'unitsame', 'selectdatafalse', '5', null, '1', 'getofficiaunit', '1', '1', null, '0', '0', null, null, '50', null, '1', '0', '0', '1', '0', null, '可输入多个,分开', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('635', '19', '发文单位', 'unitsame', 'text', '5', '{deptname}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('636', '19', '公文密级', 'miji', 'rockcombo', '9', null, '1', 'officialmiji', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('637', '20', '接收单位', 'unitname', 'text', '7', '{deptname}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('638', '67', '订阅标题', 'title', 'text', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('639', '67', '订阅提醒内容', 'cont', 'textarea', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('640', '67', '订阅说明', 'explain', 'textarea', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('641', '67', '订阅人', 'optname', 'text', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('642', '67', '订阅时间', 'optdt', 'datetime', '4', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('643', '67', '状态', 'status', 'checkbox', '5', '1', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('644', '67', '同时订阅给', 'recename', 'text', '6', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('645', '67', '订阅提醒时间', 'ratecont', 'text', '7', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('646', '67', '订阅地址', 'suburl', 'textarea', '8', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('647', '67', '订阅地址post参数', 'suburlpost', 'textarea', '9', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('648', '67', '最后运行时间', 'lastdt', 'datetime', '10', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('649', '67', 'ID', 'id', 'text', '12', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('650', '68', '订阅标题', 'title', 'text', '0', null, '1', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('651', '68', '订阅内容', 'cont', 'text', '0', null, '1', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('652', '68', '添加时候', 'optdt', 'datetime', '0', null, '1', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('653', '68', '订阅提醒对象', 'recename', 'text', '0', null, '1', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('654', '68', '文件', 'filepath', 'text', '0', null, '1', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('655', '67', '共享给', 'shatename', 'changedeptusercheck', '11', null, '0', 'shateid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '共享给他人让他也可以订阅设置这个订阅', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('656', '34', '是否核算', 'isturn', 'text', '103', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '1', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('657', '34', '是否审核', 'status', 'text', '104', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('658', '69', '标题', 'title', 'text', '1', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('659', '69', '发文单位', 'unitsame', 'text', '3', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('660', '69', '公文编号', 'num', 'text', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('661', '69', '公文密级', 'miji', 'text', '4', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('662', '69', '操作时间', 'optdt', 'datetime', '5', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('663', '10', '多部门', 'deptnames', 'changedeptcheck', '12', null, '0', 'deptids', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('664', '10', '多职位', 'rankings', 'text', '13', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, '多个用,分开', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('665', '70', '设备号', 'num', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '1', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('666', '70', '设备名称', 'name', 'text', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('667', '70', '公司名', 'company', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('668', '70', '状态', 'status', 'text', '3', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('669', '70', '最后请求时间', 'lastdt', 'datetime', '6', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('670', '70', '排序号', 'sort', 'number', '5', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('671', '70', 'ID', 'id', 'number', '7', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('672', '70', 'sd卡剩余空间', 'space', 'number', '21', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('673', '70', '剩余内存', 'memory', 'number', '22', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('674', '70', '人员数', 'usershu', 'number', '10', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('675', '70', '指纹数', 'fingerprintshu', 'number', '11', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('676', '70', '头像数量', 'headpicshu', 'number', '13', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('677', '70', '打卡数', 'clockinshu', 'number', '12', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('678', '70', '现场照片数', 'picshu', 'number', '14', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('679', '70', '系统版本', 'romver', 'text', '15', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('680', '70', '应用版本', 'appver', 'text', '16', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('681', '70', '设备型号', 'model', 'text', '17', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('682', '71', '创建时间', 'createdt', 'datetime', '8', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('683', '71', '考核项目', 'title', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('684', '71', '日期', 'startdt', 'date', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('789', '71', '评分内容', 'itemname', 'text', '0', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('685', '71', '评分截止日期', 'enddt', 'date', '4', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('686', '71', '状态', 'pfrenids', 'text', '5', '0', '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('687', '71', '最后得分', 'fen', 'number', '7', '0', '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('688', '71', '操作人', 'optname', 'text', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('689', '7', '所在省', 'sheng', 'selectdatafalse', '8', null, '0', 'city:citydata', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('690', '7', '所在市', 'shi', 'text', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('691', '72', '省', 'sheng', 'select', '6', null, '0', 'city:shengdata', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('692', '72', '市', 'shi', 'select', '7', null, '0', 'citydata', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('693', '72', '县(区)', 'xian', 'select', '8', null, '0', 'xiandata', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('694', '72', '申请日期', 'applydt', 'date', '0', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('695', '72', '说明', 'explain', 'textarea', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('696', '72', '弹出下 拉单选', 'tanxuan', 'selectdatafalse', '10', null, '0', 'tanxuan,tanxuanid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('697', '72', '弹框下 拉多选', 'tanxuancheck', 'selectdatatrue', '12', null, '0', 'tanxuancheck', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('698', '72', '文件上传1', 'upfile1', 'uploadfile', '13', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('699', '72', '文件上传2', 'upfile2', 'uploadfile', '14', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('700', '69', '正文文件', 'filecontid', 'uploadfile', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('701', '73', '仓库名称', 'depotname', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '50', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('702', '73', '仓库地址', 'depotaddress', 'text', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '100', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('703', '73', '仓库管理员', 'cgname', 'changeusercheck', '1', null, '1', 'cgid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('704', '73', '说明', 'depotexplain', 'textarea', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('705', '73', '排序号', 'sort', 'number', '4', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('706', '73', '仓库编号', 'depotnum', 'text', '2', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('707', '28', '入库状态', 'state', 'select', '6', '0', '0', '0|待入库,1|已入库,2|部分入库', '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('708', '24', '出库状态', 'state', 'select', '3', '0', '0', '0|待出库,1|已出库2|部分出库', '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('709', '28', '供应商ID', 'custid', 'hidden', '3', '0', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('710', '74', '名称', 'name', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('711', '74', '可查看人员', 'recename', 'changedeptusercheck', '1', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('712', '74', '管理人员', 'guanname', 'changedeptusercheck', '3', null, '0', 'guanid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '不选只有管理员可管理', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('713', '74', '排序号', 'sort', 'number', '4', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('714', '74', '操作人', 'optname', 'text', '5', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('715', '76', '申请日期', 'applydt', 'text', '0', '{date}', '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('716', '76', '类型', 'type', 'fixed', '1', '3', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('717', '76', '说明', 'explain', 'textarea', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('718', '76', '物品', 'temp_aid', 'selectdatatrue', '0', null, '1', 'getgoodsdata,aid', '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('719', '76', '调拨数量', 'count', 'number', '2', '0', '1', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('721', '76', '物品Id', 'aid', 'hidden', '1', '0', '1', null, '1', '0', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('722', '76', '出库状态', 'state', 'select', '5', '0', '0', '0|待入库,1|已入库,2|部分入库', '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('723', '76', '调出仓库', 'custid', 'select', '2', null, '1', 'goods:godepotarr', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('724', '74', '限制上传类型', 'uptype', 'text', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '100', null, '1', '0', '0', '0', '0', null, '如:doc,xlsx,不写为全部', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('725', '77', '客户名称', 'custname', 'selectdatafalse', '0', null, '1', 'custghaidata,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('726', '77', '客户ID', 'custid', 'hidden', '0', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('727', '77', '说明', 'explain', 'textarea', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('728', '78', '需求岗位', 'zhiwei', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('729', '78', '薪资', 'xinzi', 'text', '2', '面议', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('730', '78', '需求人数', 'renshu', 'number', '3', '1', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('731', '78', '职位要求', 'content', 'textarea', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('732', '78', '说明', 'explain', 'textarea', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('733', '78', '需求部门', 'bumen', 'text', '1', '{deptname}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('734', '79', '面试者', 'name', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('735', '79', '类型', 'type', 'fixed', '1', '1', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('736', '79', '面试岗位', 'zhiwei', 'text', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('737', '79', '所属部门', 'bumen', 'text', '3', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('738', '79', '面试人员', 'msuser', 'changeusercheck', '5', null, '1', 'msuserid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('739', '79', '面试时间', 'msdt', 'datetime', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('740', '79', '说明', 'explain', 'textarea', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('743', '79', '面试结果', 'state', 'select', '19', '0', '0', '1|录用,2|不适合', '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('741', '79', '面试者简历', 'content', 'htmlediter', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('742', '79', '面试记录', 'mscont', 'textarea', '20', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('744', '7', '放入公海', 'isgh', 'select', '25', null, '0', '0|否,1|是', '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('745', '70', '品牌', 'pinpai', 'select', '4', '1', '1', '0|群英,1|中控', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('746', '70', '分配的ip', 'snip', 'text', '18', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('747', '70', '分配端口号', 'snport', 'text', '20', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('748', '5', '请假人', 'uname', 'changeuser', '0', '{admin}|{uid}', '1', 'uid', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('749', '32', '申请人', 'applyname', 'changeuser', '0', '{admin}|{uid}', '1', 'uid', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('750', '33', '申请人', 'applyname', 'changeuser', '0', '{admin}|{uid}', '1', 'uid', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('751', '37', '申请人', 'applyname', 'changeuser', '0', '{admin}|{uid}', '1', 'uid', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('788', '71', '分数', 'fenshu', 'number', '1', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('787', '71', '考评内容', 'itemname', 'text', '0', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('752', '71', '姓名', 'applyname', 'changeuser', '1', '{admin}|{uid}', '1', 'uid', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('753', '23', '申请人', 'applyname', 'changeuser', '0', '{admin}|{uid}', '1', 'uid', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('754', '6', '申请人', 'uname', 'changeuser', '1', '{admin}|{uid}', '1', 'uid', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('755', '51', '申请人', 'uname', 'changeuser', '0', '{admin}|{uid}', '1', 'uid', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('756', '72', '测试字段', 'testfirs', 'checkboxall', '15', null, '0', '我是,他是,大家都是', '1', '1', null, '0', '0', null, null, '50', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('757', '80', '对应模块编号', 'modenum', 'text', '0', null, '1', null, '1', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('758', '80', '模块名称', 'modename', 'text', '1', null, '1', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('759', '80', '对应主表', 'table', 'hidden', '2', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('760', '80', '主表Id', 'mid', 'text', '3', null, '1', null, '1', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('761', '80', '需回执人', 'recename', 'changedeptusercheck', '4', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('762', '80', '说明', 'explain', 'textarea', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('763', '80', '总人数', 'ushuz', 'number', '6', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('764', '80', '已确认', 'ushuy', 'number', '7', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('765', '80', '操作时间', 'optdt', 'datetime', '8', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('766', '80', '状态', 'status', 'checkbox', '9', '1', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('767', '7', '最后跟进', 'lastdt', 'datetime', '26', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('768', '30', '截止使用时间', 'enddt', 'datetime', '1', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '1', '0', '1', '0', null, '不设置默认长期有效', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('769', '81', '考核名称', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('770', '81', '开始日期', 'startdt', 'date', '1', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('771', '81', '截止日期', 'enddt', 'date', '2', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('772', '81', '考核对象', 'recename', 'changedeptusercheck', '3', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('773', '81', '考核频率', 'pinlv', 'select', '4', 'm', '1', 'm|每月,j|每季,y|每年', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('774', '81', '生成时间', 'sctime', 'date', '5', null, '1', null, '1', '1', null, '0', '0', null, null, '30', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('775', '81', '最高分数', 'maxfen', 'number', '7', '100', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('776', '81', '评分时间(天)', 'pfsj', 'number', '6', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('777', '81', '合格分数', 'hegfen', 'number', '8', '60', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('778', '81', '考评项目', 'itemname', 'text', '0', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('779', '81', '比重(%)', 'weight', 'number', '1', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('780', '81', '考核内容', 'temp_zbcont', 'text', '9', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('781', '81', '评分名称', 'pfname', 'text', '0', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('782', '81', '评分人类型', 'pftype', 'select', '1', null, '1', 'my|自己,super|直属上级,user|选择人', '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('783', '81', '评分人', 'pfren', 'changeuser', '2', null, '0', 'pfrenid', '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('784', '81', '评分权重', 'pfweight', 'number', '3', '0', '1', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('785', '81', '评分人', 'temp_pfren', 'text', '10', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('786', '81', '状态', 'status', 'select', '11', '1', '1', '0|不启用,1|启用', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('790', '71', '评分类型', 'pfname', 'text', '1', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('791', '71', '评分人', 'optname', 'text', '2', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('792', '71', '评分分数', 'fenshu', 'number', '3', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('793', '71', '最后得分', 'defen', 'number', '4', null, '1', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('794', '71', '评分时间', 'optdt', 'datetime', '5', null, '0', null, '0', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('795', '71', '部门', 'deptname', 'text', '0', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('796', '10', '通讯录', 'isvcard', 'checkbox', '26', '1', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('797', '48', '签发日期', 'startdt', 'date', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('798', '48', '截止日期', 'enddt', 'date', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '不选长期有效', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('799', '49', '申请日期', 'applydt', 'date', '0', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('800', '48', '排序号', 'sort', 'number', '0', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('801', '83', '适用对象', 'recename', 'changedeptusercheck', '2', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('802', '83', '模版类型', 'atype', 'select', '1', null, '1', '基本工资,考勤,绩效,个人所得税,社保公积金,补贴,其他', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('803', '83', '模版名称', 'title', 'text', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('804', '83', '开始月份', 'startdt', 'month', '3', '{month}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('805', '83', '截止月份', 'enddt', 'month', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '设置个期限', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('806', '83', '名称', 'name', 'selectdatafalse', '0', null, '1', 'xinzifields,fields', '1', '1', null, '1', '0', null, null, '50', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('807', '83', '类型', 'type', 'select', '1', '0', '1', '0|字段,1|增加,2|减少,3|仅实发增加,4|仅实发减少,5|仅应发增加,6|仅应发减少', '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('808', '83', '默认值', 'devzhi', 'number', '2', '0', '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('809', '83', '公式', 'gongsi', 'text', '3', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('810', '83', '备注', 'beizhu', 'text', '4', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('811', '83', '说明', 'explain', 'textarea', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('812', '34', '绩效基数', 'jxjs', 'number', '60', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('814', '34', '绩效分数', 'jxdf', 'number', '61', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('813', '34', '绩效系数', 'jxxs', 'number', '60', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('815', '34', '绩效收入', 'jtpost', 'number', '61', '0', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('816', '83', '排序号', 'sort', 'number', '7', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('817', '34', '应出勤天数', 'ysbtime', 'number', '49', '0', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('818', '34', '出勤天数', 'zsbtime', 'number', '49', '0', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('819', '83', '对应字段', 'fields', 'hidden', '0', null, '1', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('820', '34', '应发工资', 'mones', 'text', '99', '0', '0', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('821', '34', '个税起征点', 'taxbase', 'number', '42', '3500', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('822', '84', '名称', 'title', 'text', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('823', '84', '适用对象', 'recename', 'changedeptusercheck', '1', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('824', '84', '养老保险基数', 'yljishu', 'number', '3', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('825', '84', '养老个人比例(%)', 'ylgeren', 'number', '4', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('826', '84', '养老单位比例(%)', 'ylunit', 'number', '5', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('827', '84', '失业个人比例(%)', 'sygeren', 'number', '7', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('828', '84', '失业单位比例(%)', 'syunit', 'number', '8', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('829', '84', '工伤个人比例(%)', 'gsgeren', 'number', '10', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('830', '84', '工伤单位比例(%)', 'gsunit', 'number', '11', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('831', '84', '生育个人比例(%)', 'syugeren', 'number', '13', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('832', '84', '生育单位比例(%)', 'syuunit', 'number', '14', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('833', '84', '医疗个人比例(%)', 'yigeren', 'number', '16', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('834', '84', '大病个人(元)', 'dbgeren', 'number', '18', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('835', '84', '个人社保缴费(元)', 'shebaogeren', 'text', '19', '0', '0', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', '({yljishu}*{ylgeren}*0.01)+({syjishu}*{sygeren}*0.01)+({gsjishu}*{gsgeren}*0.01)+({syujishu}*{syugeren}*0.01)+({yijishu}*{yigeren}*0.01)+{dbgeren}', null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('836', '84', '单位社保缴费(元)', 'shebaounit', 'text', '20', '0', '0', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', '({yljishu}*{ylunit}*0.01)+({syjishu}*{syunit}*0.01)+({gsjishu}*{gsunit}*0.01)+({syujishu}*{syuunit}*0.01)+({yijishu}*{yiunit}*0.01)', null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('837', '84', '公积金基数', 'gongjishu', 'number', '21', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('838', '84', '公积金个人比例(%)', 'gjjgeren', 'number', '22', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('839', '84', '公积金单位比例(%)', 'gjjunit', 'number', '23', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('840', '84', '公积金个人(元)', 'gonggeren', 'text', '24', '0', '0', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', '{gongjishu}*{gjjgeren}*0.01', null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('841', '84', '公积金单位(元)', 'gongunit', 'text', '25', '0', '0', null, '1', '1', 'readonly', '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', '{gongjishu}*{gjjunit}*0.01', null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('842', '84', '每月生成时间', 'sctime', 'date', '28', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('843', '84', '说明', 'explian', 'textarea', '29', null, '0', null, '1', '1', null, '0', '0', null, null, '500', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('844', '84', '医疗单位比例(%)', 'yiunit', 'number', '17', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('845', '84', '开始月份', 'startdt', 'month', '26', '{month}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('846', '84', '截止月份', 'enddt', 'month', '27', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('847', '84', '失业保险基数', 'syjishu', 'number', '6', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('848', '84', '工伤保险基数', 'gsjishu', 'number', '9', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('849', '84', '生育保险基数', 'syujishu', 'number', '12', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('850', '84', '医疗保险基数', 'yijishu', 'number', '15', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('851', '1', '发手机短信', 'issms', 'checkbox', '13', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '发短信', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('852', '2', '短信提醒', 'issms', 'checkbox', '13', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '发短信', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('853', '34', '单位社保缴费', 'socialsunit', 'number', '41', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('854', '34', '公积金个人', 'gonggeren', 'number', '41', '0', '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('855', '34', '公积金单位', 'gongunit', 'number', '41', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('856', '84', '状态', 'status', 'checkbox', '2', '1', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '启用', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('857', '83', '状态', 'status', 'checkbox', '8', '1', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '启用', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('858', '24', '领用的物品', 'wupinlist', 'text', '2', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('859', '34', '餐补贴', 'foodbt', 'number', '1', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('860', '34', '高温津贴', 'hotbt', 'number', '1', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('861', '34', '电脑补贴', 'dnbt', 'number', '1', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('862', '34', '其他补贴', 'otherbt', 'number', '1', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('863', '34', '计件收入', 'jiansr', 'number', '71', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('864', '34', '工号', 'gonghao', 'text', '0', '{urs.num}', '0', null, '0', '0', 'readonly', '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('865', '7', '纳税识别号', 'shibieid', 'text', '13', null, '0', null, '1', '0', null, '0', '0', null, null, '30', null, '0', '0', '0', '0', '0', null, '用来开发票', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('866', '7', '开户行', 'openbank', 'text', '14', null, '0', null, '1', '0', null, '0', '0', null, null, '100', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('867', '7', '开户帐号', 'cardid', 'text', '15', null, '0', null, '1', '0', null, '0', '0', null, null, '50', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('868', '65', '对应客户ID', 'custid', 'hidden', '4', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('869', '64', '对应客户ID', 'custid', 'hidden', '3', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('870', '85', '类型', 'type', 'select', '0', '0', '1', '1|收到的发票,0|开出去的发票', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('871', '85', '销售方名称', 'custname', 'selectdatafalse', '3', null, '1', 'crm:custdata,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('872', '85', '销售方名称ID', 'custid', 'hidden', '4', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('873', '85', '购买方名称', 'maicustname', 'selectdatafalse', '6', null, '1', 'crm:custdata,maicustid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('874', '85', '开票日期', 'opendt', 'date', '2', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('875', '85', '开票人', 'kainame', 'text', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('876', '85', '发票类型', 'ptype', 'rockcombo', '1', null, '1', 'openpiaotype', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('877', '85', '金额', 'money', 'number', '7', '0', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('878', '85', '说明', 'explain', 'textarea', '11', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('879', '85', '购买方Id', 'maicustid', 'hidden', '8', '0', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('880', '85', '发票代码', 'daima', 'text', '9', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('881', '85', '发票号码', 'haoma', 'text', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('882', '86', '文档名称', 'name', 'text', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('883', '86', '类型', 'wtype', 'select', '0', null, '1', 'docx|word文档,xlsx|Excel表格,pptx|PPT文档', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('884', '86', '协作人', 'xiename', 'changedeptusercheck', '2', null, '0', 'xienameid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '不选默认自己', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('885', '86', '可查看人', 'recename', 'changedeptusercheck', '3', null, '0', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '不选默认自己', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('886', '86', '创建人', 'optname', 'text', '4', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('887', '86', '操作时间', 'optdt', 'datetime', '5', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('888', '86', '说明', 'explian', 'textarea', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('889', '86', '分类', 'fenlei', 'text', '1', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('890', '86', '操作', 'temp_opt', 'text', '10', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('891', '87', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('892', '87', '相关文件', 'filecontid', 'uploadfile', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('893', '87', '说明', 'explain', 'textarea', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('894', '87', '接收人', 'qianname', 'changeusercheck', '2', null, '1', 'qiannameid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('895', '72', '客户', 'custname', 'selectdatafalse', '3', null, '0', 'getmycust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('896', '72', '客户id', 'custid', 'hidden', '4', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('897', '2', '类型', 'type', 'select', '6', '0', '1', '0|普通会议,1|固定会议', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('898', '2', '会议频率', 'rate', 'select', '7', null, '0', 'getratestore', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('899', '19', '拟稿日期', 'applydt', 'date', '1', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('900', '19', '抄送单位', 'chaoname', 'selectdatatrue', '9', null, '0', 'getofficiaunit', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, '可输入多个,分开', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('901', '19', '正文上称呼', 'zuncheng', 'text', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '200', null, '0', '0', '0', '0', '0', null, '如：尊敬各位领导', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('902', '88', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('903', '88', '类型名称', 'typename', 'rockcombo', '2', null, '1', 'newstype,name,tree', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('904', '88', '内容', 'content', 'htmlediter', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('905', '88', '发送给', 'recename', 'changedeptusercheck', '4', null, '0', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '不选默认为发给全部人员', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('906', '88', '相应地址', 'url', 'text', '5', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('907', '88', '来源', 'zuozhe', 'text', '6', '{deptname}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('908', '88', '日期', 'indate', 'date', '7', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('909', '88', '封面图片', 'fengmian', 'uploadimg', '1', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('910', '88', '发布人', 'optname', 'text', '8', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('911', '88', '展示日期', 'startdt', 'date', '12', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '设置开始展示日期', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('912', '88', '展示截止', 'enddt', 'date', '13', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '不设置长期展示', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('913', '88', '状态', 'status', 'checkbox', '16', '1', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('914', '88', '发手机短信', 'issms', 'checkbox', '14', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '发短信', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('915', '88', '排序号', 'istop', 'checkbox', '15', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('916', '1', '排序号', 'istop', 'checkbox', '14', '0', '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('917', '1', '展示日期', 'zstart', 'date', '16', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '设置开始展示日期', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('918', '1', '展示截止', 'zsend', 'date', '17', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '不设置长期展示', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('932', '19', '是否套红', 'thid', 'select', '14', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('934', '19', '盖章', 'yzid', 'number', '15', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('935', '49', '关联模块单据', 'mknum', 'hidden', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '1', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('947', '20', '正文上称呼', 'zuncheng', 'text', '10', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('948', '69', '正文上称呼', 'zuncheng', 'text', '7', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('949', '69', '公文内容', 'content', 'htmlediter', '8', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('950', '69', '主题词', 'titles', 'text', '2', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('952', '73', '物品数', 'wpshu', 'number', '6', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '1', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('953', '92', '客户名称', 'custname', 'selectdatafalse', '3', null, '1', 'getmycust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('954', '92', '客户id', 'custid', 'hidden', '4', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('955', '92', '优惠价格', 'discount', 'number', '5', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('956', '92', '销售金额', 'money', 'number', '6', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '1', '[{zb0.count}*{zb0.price}] - [{discount}]', null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('957', '92', '销售日期', 'applydt', 'date', '1', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('958', '92', '物品', 'temp_aid', 'selectdatatrue', '0', null, '1', 'getgoodsdata,aid', '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('959', '92', '销售数量', 'count', 'number', '2', '0', '1', '', '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('960', '92', '单位', 'unit', 'text', '3', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('961', '92', '单价', 'price', 'number', '4', '0', '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('962', '92', '物品Id', 'aid', 'hidden', '1', '0', '1', null, '1', '0', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('963', '92', '说明', 'explain', 'textarea', '7', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('964', '92', '关联合同', 'custractid', 'select', '2', '0', '1', 'getmycustract', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('965', '92', '销售单号', 'num', 'text', '0', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('966', '92', '出库状态', 'state', 'select', '8', null, '0', '0|待入库,1|已入库,2|部分入库', '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('967', '92', '收款状态', 'shoukuzt', 'text', '9', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('968', '72', '单选弹出选择id', 'tanxuanid', 'hidden', '11', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('969', '10', '更多单位', 'dwid', 'hidden', '27', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('970', '10', '更多所属单位', 'temp_dwid', 'selectdatatrue', '28', null, '0', 'companydata,dwid', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('971', '63', '对应logo', 'logo', 'uploadimg', '1', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('972', '63', '显示OA名称', 'oaname', 'text', '2', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, 'PC上显示的名称，不设置就默认', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('973', '63', '移动端显示', 'oanemes', 'text', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '手机版OA上的名称，不设置就默认', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('976', '43', 'E', 'ane', 'text', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '1', '0');
INSERT INTO `xinhu_flow_element` VALUES ('975', '63', '单位编号', 'num', 'text', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('977', '56', '判断题数', 'pdshu', 'number', '7', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('978', '56', '操作人', 'optname', 'text', '15', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('979', '72', '封面图片', 'fengmian', 'uploadimg', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '200', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('980', '2', '主持人', 'zcren', 'changeusercheck', '2', null, '0', 'zcrenid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '为空默认发起人主持', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('981', '72', '编号', 'num', 'num', '2', null, '1', 'ABCYmd', '1', '1', 'readonly', '0', '0', null, null, '50', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('982', '69', '需查阅人', 'recename', 'text', '9', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('983', '69', '需查阅(总/未)人', 'chatotal', 'text', '10', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('984', '1', 'APP首页显示', 'appxs', 'checkbox', '15', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('985', '88', 'APP首页显示', 'appxs', 'checkbox', '17', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1032', '35', '合同分类', 'fenlei', 'rockcombo', '1', null, '0', 'crmhtfenlei', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1033', '98', '跟进客户', 'custname', 'selectdatafalse', '0', null, '1', 'getmycust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1034', '98', '客户Id', 'custid', 'hidden', '1', null, '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1035', '98', '跟进方式', 'gentype', 'rockcombo', '2', null, '1', 'custgentype', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1036', '98', '计划时间', 'plandt', 'datetime', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1037', '98', '说明', 'explain', 'textarea', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1038', '98', '状态', 'status', 'select', '3', '0', '1', '0|计划,1|完成,5|取消', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1039', '98', '完成时间', 'findt', 'datetime', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1040', '12', '创建待办', 'isdai', 'checkbox', '10', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1041', '99', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1042', '99', '日程时间', 'startdt', 'datetime', '0', '{now}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1043', '99', '待办人', 'distren', 'changeuser', '0', '{admin}|{uid}', '1', 'distid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1044', '99', '提醒给', 'recename', 'changedeptusercheck', '0', null, '0', 'receid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1045', '99', '说明', 'explain', 'textarea', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1046', '98', '跟进人', 'optname', 'text', '7', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1047', '24', '要归还吗', 'lygh', 'select', '4', '0', '0', '0|不需要,1|需要', '1', '1', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1048', '100', '领用单', 'custname', 'selectdatafalse', '0', null, '1', 'getgoodly,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '读取你申请过的领用单', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1049', '100', '领用单id', 'custid', 'hidden', '1', '0', '1', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1050', '100', '说明', 'explain', 'textarea', '2', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1051', '100', '归还物品', 'temp_aid', 'text', '0', null, '1', null, '1', '1', 'readonly', '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1052', '100', '物品Id', 'aid', 'hidden', '1', null, '1', null, '1', '0', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1053', '100', '归还数量', 'count', 'text', '2', '0', '1', null, '1', '1', 'readonly', '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1054', '100', '入库状态', 'state', 'select', '4', null, '0', '0|待入库,1|已入库,2|部分入库', '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1055', '100', '归还的物品', 'wupinlist', 'text', '3', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1056', '101', '标题', 'title', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '100', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1057', '101', '针对对象', 'recename', 'changedeptusercheck', '0', null, '1', 'receid', '1', '1', null, '0', '0', null, null, '200', null, '1', '0', '0', '1', '0', null, '必须选择', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1058', '101', '开始日期', 'startdt', 'date', '0', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1059', '101', '截止日期', 'enddt', 'date', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1060', '101', '说明', 'explain', 'textarea', '0', null, '0', null, '1', '1', null, '0', '0', null, null, '1000', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1061', '101', '选项标题', 'itemname', 'text', '0', null, '1', null, '1', '1', null, '1', '0', null, null, '100', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1062', '101', '项说明', 'itemcont', 'text', '0', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1063', '101', '项类型', 'itemtype', 'select', '0', '0', '1', '0|单选,1|多选,2|填空', '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1064', '101', '选项A', 'itema', 'text', '0', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1065', '101', '选项B', 'itemb', 'text', '0', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1066', '101', '选项C', 'itemc', 'text', '0', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1067', '101', '选项D', 'itemd', 'text', '0', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1068', '101', '统计(已提交/总人数)', 'temp_total', 'text', '0', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1069', '10', '部门全称', 'deptallname', 'text', '10', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1070', '74', '可上传人员', 'upuser', 'changedeptusercheck', '2', null, '0', 'upuserid', '1', '1', null, '0', '0', null, null, '200', null, '1', '0', '0', '1', '0', null, '不选只有自己可上传', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1071', '72', '名称', 'sheng', 'text', '0', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1072', '72', '相关图片', 'shi', 'uploadimg', '1', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1073', '72', '相应文件', 'xian', 'uploadfile', '2', null, '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1074', '72', '简单名称', 'tanxuan', 'text', '0', null, '0', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1075', '72', '文件上传1', 'upfile1', 'uploadfile', '1', null, '0', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1076', '72', '说明', 'explain', 'textarea', '2', null, '0', null, '1', '1', null, '2', '0', null, null, '0', null, '1', '0', '1', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1077', '72', 'html编辑器', 'htmlcont', 'htmlediter', '16', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1078', '72', '选择人员', 'xuanren', 'changeusercheck', '1', null, '0', 'xuanrenid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', 'd2', null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1079', '7', '地址经纬度', 'addresslatlng', 'hidden', '11', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1080', '102', '退货客户', 'custname', 'selectdatafalse', '0', null, '1', 'getmycust,custid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1094', '102', '关联销售单', 'custractid', 'select', '0', null, '1', 'getxiaoshou', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1081', '102', '客户ID', 'custid', 'hidden', '1', '0', '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1082', '102', '说明', 'explain', 'textarea', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '500', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1083', '102', '退货物品', 'wupinlist', 'text', '3', null, '0', null, '0', '0', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1084', '102', '入库状态', 'state', 'select', '7', null, '0', '0|待入库,1|已入库,2|部分入库', '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1085', '102', '退货物品', 'temp_aid', 'selectdatafalse', '0', null, '1', 'getgoodsdata,aid', '1', '1', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1086', '102', '物品id', 'aid', 'hidden', '1', '0', '0', null, '1', '0', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1087', '102', '退货数量', 'count', 'number', '2', '0', '1', null, '1', '1', null, '1', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1088', '102', '申请日期', 'applydt', 'date', '2', '{date}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1089', '102', '退货类型', 'leixing', 'select', '4', '普通退货', '1', 'tuileixing', '1', '1', null, '0', '0', null, null, '50', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1090', '102', '单位', 'unit', 'text', '3', null, '0', null, '1', '1', 'readonly', '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1091', '102', '退款单价', 'price', 'number', '4', '0', '0', null, '1', '1', null, '1', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1092', '102', '减免价格', 'discount', 'number', '5', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1093', '102', '退款金额', 'money', 'number', '6', '0', '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', '[{zb0.count}*{zb0.price}] - [{discount}]', null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1162', '108', '所属人', 'uname', 'changeuser', '0', '{admin}|{uid}', '1', 'uid', '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1163', '108', '证件名称', 'mingc', 'text', '1', null, '1', null, '1', '1', 'maxhang', '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, '如：毕业证书等', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1164', '108', '证书编号', 'numc', 'text', '2', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1165', '108', '相关图片', 'fengmian', 'uploadimg', '3', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1166', '108', '取得时间', 'sdt', 'date', '4', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1167', '108', '到期时间', 'edt', 'date', '5', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, '不选默认长期', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1168', '108', '说明', 'explain', 'textarea', '6', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1169', '7', '共享给', 'shate', 'changeusercheck', '16', null, '0', 'shateid', '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1170', '86', '模版选择', 'isgk', 'select', '7', '0', '0', '0|不让选择,1|公开做模版选择', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1171', '86', '模版中变量', 'tplvar', 'textarea', '8', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, '模版中有变量就需要写如：title,name', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1172', '31', '合同文件', 'htfid', 'uploadfile', '10', null, '0', 'docx,pdf', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1175', '21', '相关图片', 'imgpath', 'uploadimg', '5', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1176', '63', '使用域名', 'yuming', 'text', '5', '', '0', '', '1', '1', '', '0', '0', '', '', '100', '', '1', '0', '0', '0', '0', '', '', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1177', '35', '合同文件', 'htfileid', 'uploadfile', '16', null, '0', 'pdf,docx', '1', '1', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1249', '72', '多选字段2', 'duoxuanla', 'selectdatatrue', '10', null, '0', ',abcid,admin|name,user,ranking,deptname as subname|id<>{uid}', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1250', '4', '多条任务', 'temp_cjall', 'select', '13', '0', '0', '0|默认不创建,1|分配多人创建多条任务', '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1251', '116', '注册人', 'name', 'text', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1252', '116', '手机号', 'mobile', 'text', '1', null, '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1253', '116', '密码', 'pass', 'text', '2', null, '0', null, '1', '0', null, '0', '0', null, null, '0', null, '0', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1254', '116', '操作时间', 'optdt', 'datetime', '3', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1255', '116', '说明', 'explain', 'textarea', '4', null, '0', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1256', '116', '入职部门', 'rzdept', 'changedept', '5', null, '0', 'rzdeptid', '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1257', '116', '入职职位', 'rzzhiwei', 'text', '6', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1258', '82', '内容', 'content', 'textarea', '0', null, '1', null, '1', '1', null, '0', '0', null, null, '2000', null, '1', '0', '1', '1', '0', null, '写你便笺的内容', '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1259', '82', '状态', 'state', 'select', '0', '0', '1', 'statedata', '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1260', '82', '所属时间', 'suodt', 'datetime', '0', '{now}', '1', null, '1', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1261', '82', '操作时间', 'optdt', 'datetime', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '1', '0', '1', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_flow_element` VALUES ('1262', '82', '操作人', 'optname', 'text', '0', null, '0', null, '0', '1', null, '0', '0', null, null, '0', null, '1', '0', '0', '1', '0', null, null, '0', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_flow_extent`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_extent`;
CREATE TABLE `xinhu_flow_extent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recename` varchar(4000) DEFAULT NULL,
  `receid` varchar(4000) DEFAULT NULL,
  `modeid` smallint(6) DEFAULT '0' COMMENT '模块',
  `type` tinyint(1) DEFAULT '0' COMMENT '0查看,1添加,2编辑,3删除',
  `wherestr` varchar(500) DEFAULT NULL COMMENT '条件',
  `explain` varchar(500) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `whereid` smallint(6) DEFAULT '0' COMMENT '条件Id',
  `fieldstr` varchar(500) DEFAULT NULL COMMENT '相关字段',
  PRIMARY KEY (`id`),
  KEY `modeid` (`modeid`)
) ENGINE=MyISAM AUTO_INCREMENT=265 DEFAULT CHARSET=utf8 COMMENT='模块权限';

-- ----------------------------
-- Records of xinhu_flow_extent
-- ----------------------------
INSERT INTO `xinhu_flow_extent` VALUES ('1', '全体人员', 'all', '1', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('2', '全体人员', 'all', '1', '0', 'e3JlY2VpZH0:', '发布给包含我', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('3', '全体人员', 'all', '3', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('4', '全体人员', 'all', '2', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('5', '全体人员', 'all', '2', '0', 'YWxs', '全部数据', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('6', '全体人员', 'all', '3', '0', 'e2FsbHN1cGVyfQ::', '所有下属人员', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('10', '全体人员', 'all', '1', '2', 'b3B0aWQ9e3VpZH0:', '自己发布的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('11', '全体人员', 'all', '2', '2', 'YHR5cGVgPTAgYW5kIGBvcHRpZGA9e3VpZH0gYW5kIGBzdGF0ZWA9MA::', '我创建的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('12', '全体人员', 'all', '4', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('13', '全体人员', 'all', '4', '0', 'YWxs', '任何人都可以看到任务', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('14', '全体人员', 'all', '5', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('15', '全体人员', 'all', '6', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('16', '管理员', 'u1', '1', '3', 'b3B0aWQ!MA::', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('117', '管理员', 'u1', '5', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('18', '全体人员', 'all', '2', '3', 'YHR5cGVgPTAgYW5kIGBvcHRpZGA9e3VpZH0gYW5kIHN0YXRlIGluKDAsMyk:', '可删除我创建的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('19', '全体人员', 'all', '8', '3', 'dWlkPXt1aWR9IGFuZCBzdGF0ZTw!MQ::', '删除自己未成交的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('20', '全体人员', 'all', '7', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('21', '全体人员', 'all', '8', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('22', '全体人员', 'all', '9', '2', 'b3B0aWQ9e3VpZH0:', '本人添加可编辑', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('24', '全体人员', 'all', '3', '0', 'dWlkPXt1aWR9', '可查看我自己', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('25', '全体人员', 'all', '8', '0', 'dWlkPXt1aWR9', '可查看我自己', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('26', '全体人员', 'all', '7', '0', 'dWlkPXt1aWR9', '可查看我自己的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('27', '全体人员', 'all', '12', '2', 'dWlkPXt1aWR9', '编辑我自己', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('28', '全体人员', 'all', '12', '3', 'dWlkPXt1aWR9', '删除自己', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('29', '全体人员', 'all', '12', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('30', '全体人员', 'all', '12', '0', 'dWlkPXt1aWR9', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('31', '全体人员', 'all', '10', '0', 'MT0x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('32', '全体人员', 'all', '13', '0', 'YWxs', '全体人员都可以查看组织结构', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('33', '管理员', 'u1', '13', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('34', '管理员', 'u1', '10', '3', 'YHR5cGVgPTA:', '只能删除非管理员帐号', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('35', '管理员', 'u1', '14', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('36', '管理员', 'u1', '14', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('37', '管理员', 'u1', '15', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('38', '管理员', 'u1', '15', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('39', '管理员', 'u1', '16', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('40', '管理员', 'u1', '16', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('41', '管理员', 'u1', '17', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('42', '管理员', 'u1', '17', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('43', '管理员', 'u1', '18', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('44', '管理员', 'u1', '18', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('45', '管理员', 'u1', '9', '3', 'YWxs', '管理可删除全部', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('46', '全体人员', 'all', '24', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('47', '全体人员', 'all', '25', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('48', '全体人员', 'all', '26', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('49', '全体人员', 'all', '7', '2', 'dWlkPXt1aWR9', '可编辑我自己的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('50', '全体人员', 'all', '22', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('51', '全体人员', 'all', '22', '2', 'b3B0aWQ9e3VpZH0:', '创建人可修改', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('52', '全体人员', 'all', '23', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('53', '全体人员', 'all', '11', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('54', '全体人员', 'all', '27', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('55', '全体人员', 'all', '8', '2', 'dWlkPXt1aWR9IGFuZCBodGlkPTA:', '可编辑自己未创建合同的单子', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('56', '全体人员', 'all', '3', '2', 'dWlkPXt1aWR9IGFuZCBkdD57ZGF0ZS0zfQ::', '自己可编辑3天前日报', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('57', '全体人员', 'all', '29', '2', 'aWQ9e3VpZH0:', '可编辑自己的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('58', '管理员', 'u1', '29', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('59', '全体人员', 'all', '29', '0', 'aWQ9e3VpZH0:', '可查看自己', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('60', '管理员', 'u1', '31', '0', 'YWxs', '可查看所有人', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('61', '管理员', 'u1', '31', '2', 'YWxs', '管理员可编辑所有人', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('62', '管理员,行政人事部', 'u1,d4', '29', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('63', '全体人员', 'all', '33', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('64', '全体人员', 'all', '32', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('65', '全体人员', 'all', '34', '0', 'YHh1aWRgPXt1aWR9', '可查看自己薪资', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('66', '全体人员', 'all', '34', '0', 'YG9wdGlkYD17dWlkfQ::', '可查看我提交的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('67', '部门负责人', 'g3', '34', '0', null, null, '1', '220', null);
INSERT INTO `xinhu_flow_extent` VALUES ('68', '全体人员', 'all', '35', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('69', '全体人员', 'all', '35', '2', 'dWlkPXt1aWR9IGFuZCBtb25leT1tb25leXM:', '编辑我的合同', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('70', '全体人员', 'all', '36', '2', 'dWlkPXt1aWR9IGFuZCBpc3BheT0w', '未付款前可以编辑', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('71', '管理员', 'u1', '36', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('72', '全体人员', 'all', '37', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('73', '全体人员', 'all', '40', '0', 'YWxs', '任何人都可以查看知识', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('74', '管理员,大乔', 'u1,u4', '40', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('75', '管理员,行政人事部', 'u1,d4', '41', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('76', '行政人事部', 'd4', '41', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('77', '全体人员', 'all', '41', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('78', '管理员,行政人事部', 'u1,d4', '41', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('79', '管理员,行政人事部', 'u1,d4', '42', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('80', '管理员,行政人事部', 'u1,d4', '42', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('81', '管理员,行政人事部', 'u1,d4', '42', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('82', '管理员,行政人事部', 'u1,d4', '42', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('83', '管理员,行政人事部', 'u1,d4', '43', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('84', '信呼开发团队', 'd1', '43', '2', 'b3B0aWQ9e3VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('85', '管理员,行政人事部', 'u1,d4', '43', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('86', '管理员,行政人事部', 'u1,d4', '43', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('87', '全体人员', 'all', '43', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('88', '全体人员', 'all', '44', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('89', '全体人员', 'all', '44', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('90', '全体人员', 'all', '45', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('91', '全体人员', 'all', '45', '2', 'b3B0aWQ9e3VpZH0:', '可编辑自己添加', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('92', '管理员,行政人事部', 'u1,d4', '45', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('93', '管理员,行政人事部', 'u1,d4', '45', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('94', '全体人员', 'all', '46', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('95', '全体人员', 'all', '46', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('96', '全体人员', 'all', '22', '3', 'b3B0aWQ9e3VpZH0:', '创建人可删除自己的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('97', '全体人员', 'all', '47', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('98', '全体人员', 'all', '47', '2', 'c2VuZGlkPXt1aWR9IGFuZCBpc3R1cm49MA::', '可编辑草稿', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('99', '全体人员', 'all', '47', '3', 'c2VuZGlkPXt1aWR9IGFuZCBpc3R1cm49MA::', '可删除草稿', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('100', '管理员', 'u1', '21', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('101', '全体人员', 'all', '48', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('102', '全体人员', 'all', '48', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('103', '全体人员', 'all', '48', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('104', '全体人员', 'all', '49', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('105', '全体人员', 'all', '51', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('106', '全体人员', 'all', '52', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('107', '全体人员', 'all', '53', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('108', '全体人员', 'all', '53', '2', 'dWlkPXt1aWR9', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('109', '全体人员', 'all', '53', '3', 'dWlkPXt1aWR9', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('110', '全体人员', 'all', '36', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('111', '全体人员', 'all', '36', '3', 'dWlkPXt1aWR9IGFuZCBpc3BheT0w', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('112', '行政人事部', 'd4', '28', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('113', '全体人员', 'all', '54', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('114', '全体人员', 'all', '54', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('115', '全体人员', 'all', '54', '2', 'b3B0aWQ9e3VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('116', '全体人员', 'all', '38', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('120', '全体人员', 'all', '10', '0', 'e2RlcHR9', '可以查看本部门人员', '1', '152', null);
INSERT INTO `xinhu_flow_extent` VALUES ('123', '管理员,行政人事部', 'u1,d4', '56', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('124', '全体人员', 'all', '56', '3', 'YG9wdGlkYD17dWlkfSBhbmQgYHN0YXJ0ZHRgPm5vdygp', '时间还未开始就可以删除', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('125', '全体人员', 'all', '56', '2', 'YG9wdGlkYD17dWlkfSBhbmQgYHN0YXJ0ZHRgPm5vdygp', '时间还未开始就可以编辑', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('126', '全体人员', 'all', '56', '0', 'YG9wdGlkYD17dWlkfQ::', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('127', '管理员,行政人事部', 'u1,d4', '56', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('130', '全体人员', 'all', '58', '2', 'b3B0aWQ9e3VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('131', '全体人员', 'all', '58', '3', 'b3B0aWQ9e3VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('132', '全体人员', 'all', '61', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('133', '全体人员', 'all', '61', '2', 'dWlkPXt1aWR9', '可编辑我自己', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('134', '全体人员', 'all', '61', '3', 'dWlkPXt1aWR9', '可删除我自己的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('135', '管理员', 'u1', '61', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('136', '管理员', 'u1', '61', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('137', '全体人员', 'all', '62', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('138', '行政人事部', 'd4', '62', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('139', '全体人员', 'all', '63', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('140', '管理员', 'u1', '63', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('141', '管理员', 'u1', '63', '3', 'YGlkYD4x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('142', '管理员', 'u1', '63', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('143', '管理员', 'u1', '10', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('144', '全体人员', 'all', '7', '3', 'dWlkPXt1aWR9', '可删除我自己的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('145', '全体人员', 'all', '64', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('146', '全体人员', 'all', '65', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('147', '管理员', 'u1', '10', '4', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('148', '全体人员', 'all', '7', '4', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('149', '管理员', 'u1', '67', '3', 'YWxs', '管理员可删除全部人的', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('150', '全体人员', 'all', '67', '3', 'YG9wdGlkYD17dWlkfQ::', '可删除我的订阅', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('151', '全体人员', 'all', '67', '2', 'YG9wdGlkYD17dWlkfQ::', '编辑自己订阅', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('153', '全体人员', 'all', '68', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('154', '全体人员', 'all', '68', '0', 'e3JlY2VpZCx1aWRpbn0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('155', '管理员', 'u1', '68', '3', 'YWxs', '管理员可删除所有', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('156', '管理员', 'u1', '70', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('157', '管理员', 'u1', '70', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('158', '管理员,行政人事部', 'u1,d4', '29', '2', 'YWxs', '编辑所有人', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('159', '全体人员', 'all', '71', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('160', '全体人员', 'all', '72', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('161', '全体人员', 'all', '72', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('162', '全体人员', 'all', '69', '0', 'e3JlY2VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('163', '管理员', 'u1', '73', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('164', '管理员', 'u1', '73', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('165', '管理员', 'u1', '7', '2', 'YGlzZ3lzYD0x', '可编辑供应商', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('166', '全体人员', 'all', '74', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('167', '管理员', 'u1', '74', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('168', '管理员', 'u1', '74', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('169', '全体人员', 'all', '74', '2', 'YHVpZGA9e3VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('170', '全体人员', 'all', '74', '3', 'YHVpZGA9e3VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('172', '管理员', 'u1', '7', '2', 'YGlzZ2hgPTE:', '管理员可编辑公海库', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('173', '全体人员', 'all', '77', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('174', '全体人员', 'all', '7', '0', 'YGlzZ2hgPTE:', '可查看公海库客户信息', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('175', '全体人员', 'all', '78', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('176', '全体人员', 'all', '79', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('177', '全体人员', 'all', '39', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('178', '管理员', 'u1', '10', '5', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('179', '全体人员', 'all', '80', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('180', '全体人员', 'all', '80', '2', 'dWlkPXt1aWR9', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('181', '全体人员', 'all', '80', '3', 'dWlkPXt1aWR9', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('182', '管理员', 'u1', '29', '4', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('183', '全体人员', 'all', '81', '2', 'YG9wdGlkYD17dWlkfQ::', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('184', '全体人员', 'all', '81', '3', 'YG9wdGlkYD17dWlkfQ::', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('185', '行政人事部', 'd4', '81', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('187', '管理员', 'u1', '71', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('188', '管理员', 'u1', '71', '5', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('189', '管理员,行政人事部', 'u1,d4', '83', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('190', '管理员,行政人事部', 'u1,d4', '83', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('191', '行政人事部,管理员', 'd4,u1', '84', '2', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('192', '行政人事部,管理员', 'd4,u1', '84', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('193', '行政人事部', 'd4', '84', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('194', '管理员,行政人事部', 'u1,d4', '83', '0', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('195', '行政人事部', 'd4', '34', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('196', '部门负责人', 'g3', '34', '2', null, null, '1', '220', null);
INSERT INTO `xinhu_flow_extent` VALUES ('197', '管理员', 'u1', '34', '0', 'MT0x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('198', '全体人员', 'all', '85', '2', 'YG9wdGlkYD17dWlkfQ::', '可编辑自己添加', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('199', '全体人员', 'all', '85', '3', 'YG9wdGlkYD17dWlkfQ::', '可删除自己添加', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('200', '管理员', 'u1', '85', '4', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('201', '全体人员', 'all', '86', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('202', '全体人员', 'all', '86', '2', 'YG9wdGlkYD17dWlkfQ::', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('203', '全体人员', 'all', '86', '3', 'YG9wdGlkYD17dWlkfQ::', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('204', '全体人员', 'all', '86', '0', null, null, '1', '228', null);
INSERT INTO `xinhu_flow_extent` VALUES ('205', '全体人员', 'all', '86', '0', null, null, '1', '227', null);
INSERT INTO `xinhu_flow_extent` VALUES ('206', '全体人员', 'all', '86', '5', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('207', '全体人员', 'all', '75', '0', 'YG9wdGlkYD17dWlkfQ::', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('208', '管理员', 'u1', '2', '2', 'YHR5cGVgPTE:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('209', '管理员', 'u1', '2', '3', 'YHR5cGVgPTEg', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('210', '管理员', 'u1', '29', '5', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('211', '全体人员', 'all', '10', '6', 'MT0x', null, '0', '0', 'workdate,mobile,tel,email');
INSERT INTO `xinhu_flow_extent` VALUES ('213', '管理员组', 'g4', '11', '5', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('214', '管理员组', 'g4', '34', '5', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('215', '管理员', 'u1', '88', '2', 'MT0x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('216', '管理员', 'u1', '88', '3', 'MT0x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('217', '全体人员', 'all', '88', '2', 'YG9wdGlkYD17dWlkfQ::', '可以编辑我发布', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('218', '全体人员', 'all', '88', '3', 'YG9wdGlkYD17dWlkfQ::', '可以删除我发布', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('219', '全体人员', 'all', '88', '0', 'MT0x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('225', '大乔', 'u4', '31', '1', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('226', '全体人员', 'all', '5', '7', 'e3N1cGVyfQ::', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('229', '管理员组', 'g4', '72', '7', 'MT0x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('234', '管理员', 'u1', '22', '5', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('239', '全体人员', 'all', '74', '0', 'e3JlY2VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('240', '全体人员', 'all', '75', '0', 'e3NoYXRlaWQscmVjZWFsbH0:', '共享给对应人', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('241', '全体人员', 'all', '98', '3', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPD4x', '可删除未完成', '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('242', '全体人员', 'all', '98', '2', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPD4x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('243', '全体人员', 'all', '92', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('244', '全体人员', 'all', '99', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('245', '全体人员', 'all', '99', '0', 'e3JlY2VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('246', '全体人员', 'all', '98', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('247', '管理员', 'u1', '41', '4', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('248', '全体人员', 'all', '101', '2', 'YHVpZGA9e3VpZH0gYW5kIGBkYXVzZXJpZHNgIGlzIG51bGw:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('249', '全体人员', 'all', '101', '0', 'e3JlY2VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('250', '全体人员', 'all', '101', '3', 'YHVpZGA9e3VpZH0gYW5kIGBkYXVzZXJpZHNgIGlzIG51bGw:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('252', '全体人员', 'all', '102', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('253', '管理员组', 'g4', '30', '3', 'YWxs', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('254', '全体人员', 'all', '87', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('256', '管理员', 'u1', '108', '2', 'MT0x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('260', '管理员', 'u1', '4', '7', 'MT0x', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('261', '全体人员', 'all', '82', '2', 'YHVpZGA9e3VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('262', '全体人员', 'all', '82', '3', 'YHVpZGA9e3VpZH0:', null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('263', '全体人员', 'all', '82', '1', null, null, '1', '0', null);
INSERT INTO `xinhu_flow_extent` VALUES ('264', '大乔', 'u4', '19', '1', null, null, '1', '0', null);

-- ----------------------------
-- Table structure for `xinhu_flow_log`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_log`;
CREATE TABLE `xinhu_flow_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(50) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '1通过',
  `statusname` varchar(20) DEFAULT NULL COMMENT '状态名称',
  `name` varchar(50) DEFAULT NULL COMMENT '进程名称',
  `courseid` int(11) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `ip` varchar(100) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL COMMENT '浏览器',
  `checkname` varchar(20) DEFAULT NULL,
  `checkid` smallint(6) DEFAULT NULL,
  `modeid` smallint(6) DEFAULT NULL COMMENT '@模块Id',
  `color` varchar(10) DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `step` smallint(6) DEFAULT '0' COMMENT '步骤号',
  `qmimg` text COMMENT '签名的图片base64',
  `iszb` tinyint(1) DEFAULT '0' COMMENT '是否转办记录',
  PRIMARY KEY (`id`),
  KEY `table` (`table`,`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=4159 DEFAULT CHARSET=utf8 COMMENT='单据操作记录';

-- ----------------------------
-- Records of xinhu_flow_log
-- ----------------------------
INSERT INTO `xinhu_flow_log` VALUES ('2', 'kqinfo', '1', '2', '不同意', '上级审核', '1', '2016-07-29 10:53:48', '哈哈哈，不给你过，看怎么兜风', '192.168.1.143', 'Chrome', '磐石', '5', '5', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3', 'kqinfo', '1', '1', '同意', '上级审核', '1', '2016-07-29 10:54:12', null, '192.168.1.143', 'Chrome', '磐石', '5', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('5', 'kqinfo', '2', '2', '不同意', '上级审核', '3', '2016-07-29 10:56:03', '哈哈啊', '192.168.1.143', 'Chrome', '磐石', '5', '6', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('7', 'kqinfo', '2', '1', '同意', '上级审核', '3', '2016-07-29 10:58:51', '好的', '127.0.0.1', 'Firefox', '磐石', '5', '6', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('60', 'kqinfo', '5', '1', '同意', '上级审核', '1', '2016-08-25 16:02:15', null, '127.0.0.1', 'Chrome', '磐石', '5', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('61', 'kqinfo', '5', '1', '同意', '人事审核', '2', '2016-08-25 16:03:22', null, '127.0.0.1', 'Chrome', '大乔', '4', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('63', 'kqinfo', '6', '1', '同意', '上级审核', '1', '2016-08-26 23:35:26', null, '127.0.0.1', 'Chrome', '管理员', '1', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('64', 'kqinfo', '3', '1', '同意', '上级审核', '1', '2016-08-26 23:36:28', null, '127.0.0.1', 'Chrome', '管理员', '1', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('65', 'kqinfo', '6', '1', '同意', '人事审核', '2', '2016-08-26 23:36:46', null, '127.0.0.1', 'Chrome', '大乔', '4', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('66', 'kqinfo', '3', '1', '同意', '人事审核', '2', '2016-08-26 23:36:50', null, '127.0.0.1', 'Chrome', '大乔', '4', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('67', 'kqinfo', '1', '1', '同意', '人事审核', '2', '2016-08-26 23:36:55', null, '127.0.0.1', 'Chrome', '大乔', '4', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('71', 'kqinfo', '7', '1', '同意', '上级审核', '3', '2016-08-27 15:14:50', null, '127.0.0.1', 'Chrome', '磐石', '5', '6', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('73', 'kqout', '1', '1', '同意', '上级审核', '5', '2016-08-27 15:23:05', null, '127.0.0.1', 'Chrome', '磐石', '5', '23', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1145', 'goodm', '1', '1', null, '提交', '0', '2017-11-11 15:43:29', null, '127.0.0.1', 'Chrome', '管理员', '1', '28', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1146', 'goodm', '1', '1', '同意', '上级审批', '19', '2017-11-11 21:58:14', null, '::1', 'Chrome', '磐石', '5', '28', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1147', 'goodm', '1', '1', '同意', '行政审批', '20', '2017-11-11 22:07:42', null, '::1', 'Chrome', '大乔', '4', '28', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2916', 'goodm', '20', '1', null, '提交', '0', '2020-03-31 23:06:42', null, '127.0.0.1', 'Chrome', '管理员', '1', '100', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2912', 'goodm', '17', '1', null, '提交', '0', '2020-03-31 22:18:38', null, '127.0.0.1', 'Chrome', '管理员', '1', '24', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('129', 'fininfom', '5', '1', '同意', '上级审批', '7', '2016-09-02 23:03:45', null, '127.0.0.1', 'Chrome', '磐石', '5', '11', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('130', 'kqinfo', '4', '2', '不同意', '上级审核', '1', '2016-09-02 23:06:03', 'as', '127.0.0.1', 'Chrome', '磐石', '5', '5', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('136', 'fininfom', '3', '1', '同意', '上级审批', '10', '2016-09-03 15:39:01', null, '127.0.0.1', 'Chrome', '磐石', '5', '25', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('138', 'fininfom', '4', '1', '同意', '上级审核', '13', '2016-09-03 15:44:02', null, '127.0.0.1', 'Chrome', '磐石', '5', '26', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('139', 'fininfom', '6', '2', '不同意', '上级审核', '13', '2016-09-03 15:46:03', '哈哈', '127.0.0.1', 'Chrome', '磐石', '5', '26', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('140', 'fininfom', '4', '1', '同意', '人事审核', '14', '2016-09-03 15:50:17', null, '127.0.0.1', 'Chrome', '大乔', '4', '26', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('141', 'fininfom', '4', '1', '同意', '财务审核', '15', '2016-09-03 16:51:39', null, '127.0.0.1', 'Chrome', '貂蝉', '2', '26', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('143', 'fininfom', '6', '1', '同意', '上级审核', '13', '2016-09-03 16:53:05', null, '127.0.0.1', 'Chrome', '磐石', '5', '26', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('145', 'fininfom', '6', '1', '同意', '人事审核', '14', '2016-09-03 17:16:16', 'hahah', '127.0.0.1', 'Chrome', '大乔', '4', '26', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('146', 'fininfom', '6', '1', '同意', '财务审核', '15', '2016-09-03 17:16:59', '哈哈哈哈', '127.0.0.1', 'Chrome', '貂蝉', '2', '26', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('148', 'fininfom', '7', '1', '同意', '财务确认', '16', '2016-09-03 17:18:06', null, '127.0.0.1', 'Chrome', '貂蝉', '2', '27', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('149', 'fininfom', '7', '1', '同意', '付款确认', '17', '2016-09-03 17:18:37', '哈哈哈', '127.0.0.1', 'Chrome', '管理员', '1', '27', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('150', 'fininfom', '7', '1', '同意', '确认收款', '18', '2016-09-03 17:19:43', '哈哈哈哈', '127.0.0.1', 'Chrome', '貂蝉', '2', '27', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('156', 'kqinfo', '8', '1', '同意', '上级审核', '1', '2016-09-04 22:06:25', '给你吧，哈哈哈', '127.0.0.1', 'Chrome', '张飞', '6', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('157', 'kqinfo', '8', '1', '同意', '人事审核', '2', '2016-09-05 14:22:32', '好的', '127.0.0.1', 'Chrome', '大乔', '4', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('159', 'fininfom', '3', '1', '同意', '人事审核', '11', '2016-09-05 14:34:49', 'hahah', '127.0.0.1', 'Chrome', '信呼客服', '8', '25', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('160', 'fininfom', '3', '1', '同意', '财务审核', '12', '2016-09-05 14:35:07', null, '127.0.0.1', 'Chrome', '貂蝉', '2', '25', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('188', 'kqinfo', '9', '2', '不同意', '上级审核', '1', '2016-09-08 20:38:05', 'hahaha', '192.168.1.104', 'Chrome', '磐石', '5', '5', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('190', 'kqinfo', '9', '1', '同意', '上级审核', '1', '2016-09-08 21:39:10', null, '192.168.1.100', 'wxbro', '磐石', '5', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('199', 'kqinfo', '9', '2', '不同意', '人事审核', '2', '2016-09-12 14:49:37', '哈哈哈', '127.0.0.1', 'Firefox', '大乔', '4', '5', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('201', 'fininfom', '5', '2', '不同意', '人事审核', '8', '2016-09-12 14:51:29', '12', '127.0.0.1', 'Firefox', '大乔', '4', '11', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('235', 'hrpositive', '1', '1', '同意', '上级审批', '27', '2016-10-07 10:24:33', '表现不错转正吧', '127.0.0.1', 'Chrome', '磐石', '5', '32', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('236', 'hrpositive', '1', '1', '同意', '人事审批', '28', '2016-10-07 10:25:56', '好哦奥', '127.0.0.1', 'Chrome', '大乔', '4', '32', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('238', 'hrredund', '1', '2', '不同意', '上级审批', '29', '2016-10-07 11:55:03', '没事提啥离职啊，我去', '127.0.0.1', 'Chrome', '磐石', '5', '33', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('493', 'hrtransfer', '1', '1', '同意', '人事审批', '32', '2017-02-07 10:21:30', null, '127.0.0.1', 'Chrome', '大乔', '4', '38', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3226', 'goodm', '10', '1', '同意', '上级审批', '72', '2020-07-11 17:29:34', '呃呃呃,抄送给：赵子龙,张飞,磐石', '::1', 'Chrome', '磐石', '5', '76', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3225', 'bookborrow', '2', '1', '确认归还', '借阅人归返', '40', '2020-07-11 17:27:39', '抄送给：磐石', '::1', 'Chrome', '貂蝉', '2', '46', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1571', 'seal', '3', '1', null, '提交', '0', '2018-09-14 22:11:04', null, '127.0.0.1', 'Chrome', '管理员', '1', '48', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1857', 'hrcheck', '1', '1', null, '评分', '0', '2018-12-12 23:18:38', null, '127.0.0.1', 'Chrome', '管理员', '1', '71', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('354', 'bookborrow', '1', '1', '同意', '行政确认', '39', '2016-10-29 15:19:40', '借吧', '127.0.0.1', 'Chrome', '小乔', '3', '46', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('355', 'bookborrow', '1', '1', '确认归还', '借阅人归返', '0', '2016-10-29 16:43:29', '已被张飞拿走了,转给：张飞', '127.0.0.1', 'Chrome', '管理员', '1', '46', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('356', 'bookborrow', '1', '1', '确认归还', '借阅人归返', '40', '2016-10-29 19:43:01', '呵呵', '127.0.0.1', 'Chrome', '张飞', '6', '46', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('357', 'bookborrow', '2', '1', '同意', '行政确认', '39', '2016-10-29 20:21:19', null, '127.0.0.1', 'Chrome', '小乔', '3', '46', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('358', 'bookborrow', '2', '1', '确认归还', '借阅人归返', '0', '2016-10-29 22:33:30', '转给：貂蝉', '127.0.0.1', 'Safari', '信呼客服', '8', '46', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('361', 'bookborrow', '3', '1', '同意', '行政确认', '39', '2016-10-30 11:59:32', null, '127.0.0.1', 'Chrome', '小乔', '3', '46', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('921', 'kqinfo', '9', '1', null, '作废', '0', '2017-08-02 15:38:14', '太久没处理了，作废吧', '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('404', 'seal', '1', '1', null, '提交', '0', '2016-12-08 21:09:03', null, '127.0.0.1', 'Chrome', '管理员', '1', '48', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('412', 'kqinfo', '14', '1', null, '提交', '0', '2016-12-09 12:23:06', null, '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('919', 'kqinfo', '14', '1', '同意', '上级审核', '1', '2017-07-31 16:21:16', null, '::1', 'Chrome', '磐石', '5', '5', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('415', 'customer', '2', '1', '启用', '状态切换', '0', '2016-12-14 10:07:10', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('416', 'customer', '2', '1', '启用', '状态切换', '0', '2016-12-14 10:07:51', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('417', 'customer', '2', '0', '停用', '状态切换', '0', '2016-12-14 10:08:09', '哈哈', '127.0.0.1', 'Chrome', '管理员', '1', '7', 'gray', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('418', 'customer', '2', '1', '标星', '标星', '0', '2016-12-14 10:17:30', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'chocolate', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('419', 'customer', '2', '0', '取消标星', '取消标星', '0', '2016-12-14 10:18:49', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'gray', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('420', 'customer', '1', '1', '标星', '标星', '0', '2016-12-14 10:18:53', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'chocolate', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('421', 'customer', '2', '1', '标星', '状态切换', '0', '2016-12-14 10:19:55', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'chocolate', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('422', 'customer', '1', '1', '拜访记录', '状态切换', '0', '2016-12-14 10:21:44', '哈哈哈哈啊', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('423', 'customer', '1', '1', '拜访记录', '拜访记录', '0', '2016-12-14 10:22:10', '12', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('424', 'customer', '1', '1', '拜访', '拜访记录', '0', '2016-12-14 10:22:47', '112', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('431', 'kqerr', '1', '1', null, '提交', '0', '2016-12-16 20:54:27', null, '127.0.0.1', 'Chrome', '管理员', '1', '51', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('432', 'kqerr', '1', '1', '同意', '上级审核', '44', '2016-12-16 21:01:34', null, '127.0.0.1', 'Chrome', '磐石', '5', '51', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('434', 'kqerr', '1', '1', '同意', '人事确认', '45', '2016-12-16 21:08:07', null, '127.0.0.1', 'Chrome', '大乔', '4', '51', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('451', 'tovoid', '5', '1', '同意', '上级审核', '46', '2016-12-19 14:39:52', null, '127.0.0.1', 'Chrome', '磐石', '5', '52', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('450', 'tovoid', '5', '1', null, '提交', '0', '2016-12-19 14:39:20', null, '127.0.0.1', 'Chrome', '管理员', '1', '52', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('447', 'tovoid', '3', '1', '同意', '上级审核', '46', '2016-12-19 14:32:51', null, '127.0.0.1', 'Chrome', '磐石', '5', '52', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('445', 'tovoid', '3', '1', null, '提交', '0', '2016-12-19 13:49:22', null, '127.0.0.1', 'Chrome', '管理员', '1', '52', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('452', 'vcard', '2', '1', null, '提交', '0', '2016-12-20 10:36:39', null, '127.0.0.1', 'Safari', '管理员', '1', '53', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('453', 'vcard', '3', '1', null, '提交', '0', '2016-12-20 10:37:37', null, '127.0.0.1', 'Safari', '管理员', '1', '53', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('456', 'vcard', '4', '1', null, '提交', '0', '2016-12-20 11:31:21', null, '127.0.0.1', 'Chrome', '信呼客服', '8', '53', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('461', 'vcard', '5', '1', null, '提交', '0', '2016-12-21 14:36:17', null, '127.0.0.1', 'Chrome', '管理员', '1', '53', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('506', 'customer', '1', '1', null, '共享给', '0', '2017-02-15 14:56:50', '共享给:管理员', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('507', 'customer', '1', '1', null, '共享给', '0', '2017-02-15 14:57:01', '共享给:管理员', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('508', 'customer', '1', '1', null, '取消共享', '0', '2017-02-15 15:04:12', '223', '127.0.0.1', 'Chrome', '管理员', '1', '7', 'gray', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('537', 'kqout', '2', '1', '同意', '上级审核', '5', '2017-02-28 13:13:38', '好的', '127.0.0.1', 'Chrome', '管理员', '1', '23', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1594', 'hrsalarm', '4', '1', null, '提交', '0', '2018-09-19 21:56:17', null, '127.0.0.1', 'Chrome', '管理员', '1', '83', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1549', 'kqinfo', '134', '1', null, '提交', '0', '2018-09-07 09:35:54', null, '127.0.0.1', 'Chrome', '管理员', '1', '30', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('561', 'fininfom', '8', '1', '同意', '上级审批', '0', '2017-03-08 09:51:28', null, '192.168.1.63', 'Chrome', '磐石', '5', '11', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('562', 'fininfom', '8', '1', null, '撤回', '0', '2017-03-08 09:51:44', null, '192.168.1.63', 'Chrome', '磐石', '5', '11', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('563', 'fininfom', '8', '2', '不同意', '上级审批', '7', '2017-03-08 09:52:30', 'lalal', '192.168.1.63', 'Chrome', '磐石', '5', '11', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('564', 'fininfom', '8', '2', '不同意', '上级审批', '7', '2017-03-08 09:58:24', 'haha', '192.168.1.63', 'Chrome', '磐石', '5', '11', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('566', 'reward', '2', '1', '无异议', '当事人确认', '35', '2017-03-09 14:33:18', null, '192.168.1.152', 'xinhu', '信呼客服', '8', '39', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('658', 'bookborrow', '3', '1', '确认归还', '借阅人归返', '40', '2017-03-31 09:36:21', null, '127.0.0.1', 'Chrome', '管理员', '1', '46', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('590', 'kqinfo', '11', '2', '不同意', '上级审核', '1', '2017-03-17 13:48:59', '哈哈哈', '127.0.0.1', 'Chrome', '管理员', '1', '5', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('591', 'kqinfo', '11', '2', '不同意', '上级审核', '1', '2017-03-17 13:49:17', '哈哈哈', '127.0.0.1', 'Chrome', '管理员', '1', '5', 'red', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('592', 'kqinfo', '11', '1', '同意', '上级审核', '1', '2017-03-17 14:11:04', null, '127.0.0.1', 'Chrome', '管理员', '1', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1979', 'customer', '15', '0', null, '分配', '0', '2019-04-19 19:16:00', '分配给:管理员', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3990', 'work', '11', '1', null, '提交', '0', '2021-04-13 16:12:56', null, '127.0.0.1', 'Chrome', '管理员', '1', '4', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1978', 'sealapl', '1', '1', '同意', '保管人确认', '42', '2019-04-19 18:11:06', null, '127.0.0.1', 'Chrome', '管理员', '1', '49', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('662', 'meet', '9', '1', null, '提交', '0', '2017-04-11 09:07:15', null, '127.0.0.1', 'Chrome', '管理员', '1', '2', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('663', 'meet', '10', '1', null, '提交', '0', '2017-04-11 09:18:59', null, '127.0.0.1', 'Chrome', '管理员', '1', '2', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('922', 'kqinfo', '14', '1', null, '短信催办', '0', '2017-08-02 16:06:32', 'weew', '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('673', 'schedule', '4', '1', null, '提交', '0', '2017-04-17 08:43:03', null, '127.0.0.1', 'Chrome', '小乔', '3', '12', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('676', 'kqout', '3', '1', null, '提交', '0', '2017-04-19 12:57:28', null, '127.0.0.1', 'Chrome', '管理员', '1', '23', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('677', 'kqout', '3', '1', '同意', '上级审核', '5', '2017-04-19 12:57:48', null, '::1', 'Chrome', '磐石', '5', '23', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('678', 'customer', '2', '0', '取消标星', '状态切换', '0', '2017-04-20 13:03:23', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'gray', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1824', 'bookborrow', '4', '1', null, '追加说明', '0', '2018-11-13 18:21:43', '呃呃呃', '127.0.0.1', 'Chrome', '管理员', '1', '46', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1823', 'kqout', '4', '1', '同意', '上级审核', '5', '2018-11-13 18:21:01', null, '::1', 'Chrome', '磐石', '5', '23', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('685', 'goods', '3', '1', null, '提交', '0', '2017-05-02 13:35:05', null, '127.0.0.1', 'Chrome', '管理员', '1', '9', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('697', 'fininfom', '9', '1', null, '提交', '0', '2017-05-08 19:35:20', null, '127.0.0.1', 'Chrome', '大乔', '4', '11', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('723', 'kqinfo', '15', '1', null, '提交', '0', '2017-05-18 12:11:37', null, '127.0.0.1', 'Safari', '管理员', '1', '6', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('725', 'kqinfo', '15', '1', '同意', '上级审核', '3', '2017-05-18 12:58:56', null, '::1', 'Chrome', '磐石', '5', '6', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('728', 'seal', '2', '1', null, '提交', '0', '2017-05-18 21:04:59', null, '127.0.0.1', 'Chrome', '管理员', '1', '48', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2129', 'knowtraim', '1', '1', null, '提交', '0', '2019-06-05 22:56:49', null, '192.168.1.104', 'Chrome', '大乔', '4', '56', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('739', 'fininfom', '8', '1', '同意', '上级审批', '7', '2017-06-09 20:33:16', null, '127.0.0.1', 'Chrome', '磐石', '5', '11', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('740', 'fininfom', '9', '1', '同意', '上级审批', '7', '2017-06-09 21:35:04', null, '127.0.0.1', 'Chrome', '磐石', '5', '11', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4145', 'official', '11', '1', '流转', '申请人流转', '88909', '2021-10-12 11:05:10', null, 'unknow', 'Chrome', '大乔', '4', '19', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2282', 'kqinfo', '103', '2', '不同意', '上级审核', '100004', '2019-09-23 17:00:01', '额', '192.168.1.100', 'Chrome', '磐石', '5', '5', 'red', '0', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2285', 'kqinfo', '103', '2', '不同意', '上级审核', '1', '2019-09-23 17:10:21', '谔谔', '192.168.1.100', 'Chrome', '貂蝉', '2', '5', 'red', '0', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2294', 'project', '1', '0', null, '更新进度', '0', '2019-11-26 16:40:39', '不出错啊', '127.0.0.1', 'Chrome', '管理员', '1', '22', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2301', 'flow_remind', '11', '1', null, '提交', '0', '2019-12-02 15:44:16', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2316', 'custplan', '1', '1', null, '提交', '0', '2020-01-02 18:55:50', null, '127.0.0.1', 'Chrome', '管理员', '1', '98', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('741', 'fininfom', '9', '1', '同意', '人事审核', '8', '2017-06-09 21:35:52', null, '127.0.0.1', 'Chrome', '大乔', '4', '11', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2230', 'hrdemint', '2', '1', '同意', '安排人确认', '77', '2019-07-24 18:32:00', null, '192.168.1.100', 'Chrome', '赵子龙', '7', '79', 'green', '1', '3', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2288', 'schedule', '5', '1', null, '提交', '0', '2019-11-04 19:21:53', null, '127.0.0.1', 'Chrome', '管理员', '1', '12', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2205', 'customer', '5', '1', null, '放入客户公海库', '0', '2019-07-10 18:40:46', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'deeppink', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2210', 'hrdemint', '4', '1', null, '提交', '0', '2019-07-19 11:58:06', null, '192.168.1.100', 'Chrome', '大乔', '4', '79', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2211', 'fininfom', '10', '1', '已付款', '出纳付款', '63', '2019-07-20 19:09:36', null, '192.168.1.106', 'iPhone', '小乔', '3', '64', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2271', 'bookborrow', '4', '1', '确认归还', '借阅人归返', '40', '2019-09-17 18:32:45', null, '127.0.0.1', 'Chrome', '管理员', '1', '46', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2256', 'kqinfo', '155', '1', '同意', '上级审核', '3', '2019-08-26 18:20:57', null, '::1', 'Chrome', '磐石', '5', '6', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2255', 'kqinfo', '155', '1', null, '提交', '0', '2019-08-26 18:20:43', null, '127.0.0.1', 'Chrome', '管理员', '1', '6', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('778', 'fininfom', '8', '1', '同意', '人事审核', '8', '2017-06-22 09:40:30', null, '::1', 'Chrome', '大乔', '4', '11', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('779', 'fininfom', '8', '1', '同意', '财务审核', '9', '2017-06-22 09:41:59', null, '::1', 'Chrome', '貂蝉', '2', '11', 'green', '1', '3', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('780', 'carms', '1', '1', null, '提交', '0', '2017-06-22 12:47:42', null, '127.0.0.1', 'Chrome', '管理员', '1', '58', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('781', 'carms', '2', '1', null, '提交', '0', '2017-06-22 13:46:07', null, '127.0.0.1', 'Chrome', '管理员', '1', '58', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('782', 'carms', '3', '1', null, '提交', '0', '2017-06-22 13:46:32', null, '127.0.0.1', 'Chrome', '管理员', '1', '58', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('786', 'custfina', '4', '0', '已付款', '标已付款', '0', '2017-06-26 15:54:48', null, '127.0.0.1', 'Chrome', '管理员', '1', '36', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('787', 'custfina', '4', '0', '已付款', '标已付款', '0', '2017-06-26 15:54:55', null, '127.0.0.1', 'Chrome', '管理员', '1', '36', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('788', 'custract', '5', '1', null, '提交', '0', '2017-06-26 17:50:09', null, '127.0.0.1', 'Chrome', '管理员', '1', '35', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1523', 'carmrese', '1', '1', '同意', '行政确认', '38', '2018-09-03 19:52:42', null, '::1', 'Chrome', '小乔', '3', '44', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1522', 'carmrese', '1', '1', '同意', '上级审批', '37', '2018-09-03 19:51:56', null, '::1', 'Chrome', '磐石', '5', '44', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1521', 'carmrese', '1', '1', null, '提交', '0', '2018-09-03 19:51:16', null, '127.0.0.1', 'Chrome', '管理员', '1', '44', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('801', 'kqinfo', '16', '1', '同意', '核算加班费', '59', '2017-06-29 10:16:07', null, '::1', 'Chrome', '大乔', '4', '6', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('800', 'kqinfo', '16', '1', '同意', '上级审核', '3', '2017-06-29 10:15:34', null, '::1', 'Chrome', '磐石', '5', '6', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('799', 'kqinfo', '16', '1', null, '提交', '0', '2017-06-29 10:14:05', null, '127.0.0.1', 'Chrome', '管理员', '1', '6', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4130', 'customer', '17', '1', null, '放入客户公海库', '0', '2021-09-29 18:20:39', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'deeppink', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('809', 'kqinfo', '16', '1', null, '评论', '0', '2017-07-01 15:05:52', '不错', '127.0.0.1', 'Chrome', '管理员', '1', '6', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('854', 'repair', '1', '1', null, '提交', '0', '2017-07-07 13:12:53', null, '127.0.0.1', 'Chrome', '管理员', '1', '62', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('855', 'repair', '1', '1', '确认', '行政人员检查', '60', '2017-07-07 13:16:36', null, '::1', 'Chrome', '大乔', '4', '62', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('856', 'repair', '2', '1', null, '提交', '0', '2017-07-07 13:37:28', null, '::1', 'Chrome', '小乔', '3', '62', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('857', 'repair', '2', '1', '确认', '行政人员检查', '60', '2017-07-07 13:38:35', null, '::1', 'Chrome', '大乔', '4', '62', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('858', 'repair', '2', '1', '同意', '外修确认', '61', '2017-07-07 13:39:18', null, '::1', 'Chrome', '大乔', '4', '62', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('863', 'company', '1', '1', null, '提交', '0', '2017-07-08 14:42:42', null, '127.0.0.1', 'Chrome', '管理员', '1', '63', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('864', 'company', '2', '1', null, '提交', '0', '2017-07-08 14:43:58', null, '127.0.0.1', 'Chrome', '管理员', '1', '63', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('865', 'company', '3', '1', null, '提交', '0', '2017-07-08 14:44:23', null, '127.0.0.1', 'Chrome', '管理员', '1', '63', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('866', 'company', '4', '1', null, '提交', '0', '2017-07-08 14:46:21', null, '127.0.0.1', 'Chrome', '管理员', '1', '63', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('898', 'admin', '2', '0', null, '设置为管理员', '0', '2017-07-08 22:20:34', null, '127.0.0.1', 'Chrome', '管理员', '1', '10', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('900', 'admin', '2', '0', null, '取消管理员', '0', '2017-07-08 22:28:50', null, '127.0.0.1', 'Chrome', '管理员', '1', '10', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('923', 'kqinfo', '14', '1', null, '短信催办', '0', '2017-08-02 16:31:16', 'hahaha', '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('924', 'kqinfo', '14', '1', null, '追加说明', '0', '2017-08-02 16:51:30', '不错啊', '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('925', 'kqinfo', '14', '1', null, '追加说明', '0', '2017-08-02 16:52:26', '哈哈哈', '::1', 'Chrome', '大乔', '4', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('927', 'meet', '13', '1', null, '提交', '0', '2017-08-03 13:17:05', null, '127.0.0.1', 'Chrome', '管理员', '1', '2', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('928', 'meet', '13', '6', '签到', '签到', '0', '2017-08-03 14:14:31', null, '127.0.0.1', 'Chrome', '管理员', '1', '2', 'blue', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('929', 'meet', '13', '6', '签到', '签到', '0', '2017-08-03 14:15:20', '签到', '127.0.0.1', 'Chrome', '管理员', '1', '2', 'blue', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('931', 'kqinfo', '14', '1', '同意', null, null, '2017-08-04 09:42:57', '错误', '127.0.0.1', 'Chrome', '管理员', '1', '5', 'green', '1', null, null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('932', 'kqinfo', '14', '1', '同意', '异常处理', '0', '2017-08-04 09:59:14', '很好啊', '127.0.0.1', 'Chrome', '管理员', '1', '5', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('948', 'fininfom', '10', '1', null, '提交', '0', '2017-08-07 20:42:59', null, '127.0.0.1', 'Chrome', '管理员', '1', '64', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1547', 'hrpositive', '3', '1', '同意', '上级审批', '27', '2018-09-06 21:33:48', null, '::1', 'Safari', '磐石', '5', '32', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1573', 'custract', '6', '1', null, '提交', '0', '2018-09-15 09:17:31', null, '127.0.0.1', 'Chrome', '管理员', '1', '35', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2917', 'goodm', '20', '1', '同意', '行政确认', '90', '2020-04-01 19:48:32', null, '::1', 'Chrome', '大乔', '4', '100', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1531', 'hrkaohem', '1', '1', null, '提交', '0', '2018-09-05 11:02:06', null, '127.0.0.1', 'Chrome', '管理员', '1', '81', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('960', 'meet', '14', '1', null, '提交', '0', '2017-08-13 19:01:21', null, '127.0.0.1', 'Chrome', '管理员', '1', '2', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('974', 'meet', '15', '1', null, '提交', '0', '2017-08-18 11:42:54', null, '127.0.0.1', 'Chrome', '管理员', '1', '2', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('981', 'flow_remind', '4', '1', null, '提交', '0', '2017-08-23 14:25:55', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1002', 'infor', '9', '1', null, '提交', '0', '2017-08-26 12:41:10', null, '127.0.0.1', 'Chrome', '管理员', '1', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1008', 'infor', '9', '1', null, '投票', '0', '2017-08-26 22:22:17', null, '::1', 'Chrome', '张飞', '6', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1009', 'infor', '9', '1', null, '投票', '0', '2017-08-26 22:41:04', null, '192.168.1.101', 'wxbro', '赵子龙', '7', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1131', 'infor', '9', '1', null, '投票', '0', '2017-10-23 17:14:28', '投票项ID(3)', '127.0.0.1', 'Chrome', '管理员', '1', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1013', 'kqout', '4', '1', null, '提交', '0', '2017-08-28 11:54:41', null, '127.0.0.1', 'Chrome', '管理员', '1', '23', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1014', 'kqerr', '2', '1', null, '提交', '0', '2017-08-28 12:19:12', null, '::1', 'Chrome', '貂蝉', '2', '51', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1036', 'flow_remind', '5', '1', null, '提交', '0', '2017-09-02 13:53:03', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1037', 'flow_remind', '6', '1', null, '提交', '0', '2017-09-02 21:06:29', null, '::1', 'Chrome', '信呼客服', '8', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1038', 'flow_remind', '7', '1', null, '提交', '0', '2017-09-02 21:08:52', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1046', 'infor', '9', '1', null, '投票', '0', '2017-09-04 20:32:25', null, '::1', 'Chrome', '大乔', '4', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1045', 'flow_remind', '8', '1', null, '提交', '0', '2017-09-03 20:12:00', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1586', 'hrsalarm', '3', '1', null, '提交', '0', '2018-09-18 22:24:14', null, '127.0.0.1', 'Chrome', '管理员', '1', '83', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1583', 'hrsalarm', '2', '1', null, '提交', '0', '2018-09-18 15:58:46', null, '127.0.0.1', 'Chrome', '管理员', '1', '83', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1141', 'godepot', '1', '1', null, '提交', '0', '2017-11-10 22:21:53', null, '127.0.0.1', 'Chrome', '管理员', '1', '73', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1152', 'goodm', '3', '2', '不同意', '行政审批', '20', '2017-11-12 18:37:39', '哈哈哈', '::1', 'Chrome', '大乔', '4', '28', 'red', '0', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1151', 'goodm', '3', '1', '同意', '上级审批', '19', '2017-11-12 18:37:24', null, '::1', 'Chrome', '磐石', '5', '28', 'green', '0', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1150', 'goodm', '3', '1', null, '提交', '0', '2017-11-12 18:37:02', null, '127.0.0.1', 'Chrome', '管理员', '1', '28', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1155', 'godepot', '2', '1', null, '提交', '0', '2017-11-13 13:24:50', null, '127.0.0.1', 'Chrome', '管理员', '1', '73', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1156', 'goodm', '4', '1', null, '提交', '0', '2017-11-13 14:20:47', null, '127.0.0.1', 'Chrome', '管理员', '1', '28', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1157', 'goodm', '4', '1', '同意', '上级审批', '19', '2017-11-13 14:21:10', null, '::1', 'Chrome', '磐石', '5', '28', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1158', 'goodm', '4', '1', '同意', '行政审批', '20', '2017-11-13 14:21:23', null, '::1', 'Chrome', '大乔', '4', '28', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1161', 'worc', '1', '1', null, '提交', '0', '2017-11-14 16:33:56', null, '127.0.0.1', 'Chrome', '管理员', '1', '74', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1162', 'worc', '2', '1', null, '提交', '0', '2017-11-17 20:25:42', null, '127.0.0.1', 'Chrome', '管理员', '1', '74', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1165', 'worc', '4', '1', null, '提交', '0', '2017-11-20 11:16:44', null, '::1', 'Chrome', '小乔', '3', '74', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1180', 'goodm', '5', '1', null, '提交', '0', '2017-12-28 14:24:10', null, '127.0.0.1', 'Chrome', '管理员', '1', '76', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1182', 'goodm', '5', '1', '同意', '上级审批', '72', '2017-12-28 14:31:01', null, '::1', 'Chrome', '磐石', '5', '76', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1185', 'goodm', '6', '1', null, '提交', '0', '2018-01-27 10:27:28', null, '127.0.0.1', 'Chrome', '管理员', '1', '76', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1186', 'goodm', '6', '1', '同意', '上级审批', '72', '2018-01-27 10:27:51', null, '::1', 'Chrome', '磐石', '5', '76', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1977', 'sealapl', '1', '1', '同意', '上级审批', '41', '2019-04-19 18:10:53', '可以使用', '::1', 'Chrome', '磐石', '5', '49', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1196', 'customer', '16', '0', null, '放入客户公海库', '0', '2018-03-06 13:57:34', '放入', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1197', 'admin', '2', '0', null, '设置为管理员', '0', '2018-03-06 14:06:06', null, '127.0.0.1', 'Chrome', '管理员', '1', '10', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1198', 'admin', '2', '0', null, '取消管理员', '0', '2018-03-06 14:06:08', null, '127.0.0.1', 'Chrome', '管理员', '1', '10', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1199', 'customer', '15', '0', null, '放入客户公海库', '0', '2018-03-06 14:07:45', 'eee', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1209', 'customer', '15', '1', null, '放入客户公海库', '0', '2018-03-07 12:27:59', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'deeppink', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1202', 'custappy', '1', '1', null, '提交', '0', '2018-03-06 15:47:27', null, '127.0.0.1', 'Chrome', '管理员', '1', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1204', 'custappy', '1', '1', '同意', '上级审批', '73', '2018-03-06 16:24:12', null, '::1', 'Chrome', '磐石', '5', '77', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1205', 'customer', '1', '1', null, '放入客户公海库', '0', '2018-03-06 17:42:25', null, '::1', 'Chrome', '赵子龙', '7', '7', 'deeppink', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1206', 'custappy', '2', '1', null, '提交', '0', '2018-03-06 17:42:35', null, '::1', 'Chrome', '赵子龙', '7', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1207', 'custappy', '2', '1', '同意', '上级审批', '73', '2018-03-06 21:14:03', null, '127.0.0.1', 'Chrome', '管理员', '1', '77', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1210', 'customer', '17', '1', null, '放入客户公海库', '0', '2018-03-07 12:28:02', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'deeppink', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1211', 'custappy', '3', '1', null, '提交', '0', '2018-03-07 12:28:31', null, '::1', 'Chrome', '赵子龙', '7', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1212', 'custappy', '3', '1', null, '作废', '0', '2018-03-07 12:31:41', '超时自动作废', '127.0.0.1', 'Firefox', '系统', '1', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1213', 'custappy', '3', '1', null, '作废', '0', '2018-03-07 12:31:42', '超时自动作废', '127.0.0.1', 'Firefox', '系统', '1', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1214', 'custappy', '3', '1', null, '作废', '0', '2018-03-07 12:33:05', '超时自动作废', '127.0.0.1', 'Firefox', '系统', '2', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1215', 'custappy', '3', '1', null, '作废', '0', '2018-03-07 12:33:35', '超时自动作废', '127.0.0.1', 'Chrome', '系统', '0', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1216', 'custappy', '3', '1', null, '作废', '0', '2018-03-07 12:35:03', '超时自动作废', '127.0.0.1', 'IE', '系统', '0', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1217', 'custappy', '3', '1', null, '作废', '0', '2018-03-07 12:36:13', '超时30分钟自动作废', '127.0.0.1', 'Firefox', '系统', '0', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1218', 'custappy', '4', '1', null, '提交', '0', '2018-03-07 12:41:46', null, '::1', 'Chrome', '赵子龙', '7', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1219', 'custappy', '4', '1', null, '作废', '0', '2018-03-07 12:55:05', '超时10分钟自动作废', '127.0.0.1', 'IE', '系统', '0', '77', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1239', 'hrdemint', '1', '1', null, '提交', '0', '2018-04-18 19:49:46', null, '::1', 'Chrome', '赵子龙', '7', '78', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1241', 'hrdemint', '2', '1', null, '提交', '0', '2018-04-18 22:01:41', null, '::1', 'Chrome', '赵子龙', '7', '79', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1242', 'hrdemint', '2', '1', '可以面试', '面试人确认', '75', '2018-04-18 22:26:49', null, '127.0.0.1', 'Chrome', '管理员', '1', '79', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1243', 'hrdemint', '2', '1', '面试记录', '面试结果', '76', '2018-04-18 22:27:20', null, '127.0.0.1', 'Chrome', '管理员', '1', '79', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1244', 'hrdemint', '3', '1', null, '提交', '0', '2018-04-18 22:33:50', null, '::1', 'Chrome', '赵子龙', '7', '79', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1247', 'hrdemint', '3', '2', '不适合', '面试人确认', '75', '2018-04-18 22:46:18', '哈哈哈', '192.168.1.102', 'Chrome', '磐石', '5', '79', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1267', 'admin', '4', '0', null, '设置为管理员', '0', '2018-04-29 15:23:31', null, '127.0.0.1', 'Chrome', '管理员', '1', '10', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1545', 'kqinfo', '109', '1', '同意', '上级审核', '1', '2018-09-06 20:44:05', null, '::1', 'Safari', '磐石', '5', '5', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1273', 'hrredund', '1', '2', '不同意', '上级审批', '29', '2018-05-03 16:49:15', '好好做事啊', '::1', 'Chrome', '磐石', '5', '33', 'red', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1581', 'hrsalarm', '1', '1', null, '提交', '0', '2018-09-18 11:56:02', null, '127.0.0.1', 'Chrome', '管理员', '1', '83', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1458', 'goodm', '8', '1', '同意', '上级审批', '19', '2018-07-28 22:06:08', null, '::1', 'Chrome', '磐石', '5', '28', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1307', 'kqjsn', '2', '1', null, '提交', '0', '2018-05-25 15:35:07', null, '127.0.0.1', 'Chrome', '管理员', '1', '70', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1315', 'infor', '6', '1', null, '评论', '0', '2018-06-02 22:15:13', '很不错的哦', '::1', 'Chrome', '磐石', '5', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1314', 'goodm', '6', '1', null, '评论', '0', '2018-06-02 22:11:46', '呵呵呵很好哦', '127.0.0.1', 'Chrome', '管理员', '1', '76', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1313', 'infor', '6', '1', null, '评论', '0', '2018-06-02 22:10:15', '不错哦', '127.0.0.1', 'Chrome', '管理员', '1', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1326', 'kqinfo', '103', '1', null, '提交', '0', '2018-06-13 14:48:40', null, '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1974', 'sealapl', '1', '1', null, '提交', '0', '2019-04-19 17:23:30', null, '127.0.0.1', 'Chrome', '管理员', '1', '49', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1456', 'kqinfo', '103', '1', '同意', '上级审核', '1', '2018-07-28 21:55:39', null, '::1', 'Chrome', '貂蝉', '2', '5', 'green', '0', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1455', 'kqinfo', '110', '1', '同意', '上级审核', '1', '2018-07-28 21:55:32', null, '::1', 'Chrome', '貂蝉', '2', '5', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1454', 'kqinfo', '110', '1', null, '提交', '0', '2018-07-28 21:55:00', null, '::1', 'Chrome', '小乔', '3', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1453', 'kqinfo', '109', '1', null, '提交', '0', '2018-07-28 21:52:21', null, '::1', 'Chrome', '大乔', '4', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1434', 'flow_remind', '9', '1', null, '提交', '0', '2018-07-20 20:12:41', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1452', 'admin', '4', '0', null, '取消管理员', '0', '2018-07-28 21:51:56', null, '127.0.0.1', 'Chrome', '管理员', '1', '10', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1362', 'kqinfo', '4', '1', '同意', '上级审核', '1', '2018-07-02 09:51:09', null, '127.0.0.1', 'Chrome', '磐石', '5', '5', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1353', 'hrpositive', '2', '1', null, '提交', '0', '2018-06-15 20:04:38', null, '::1', 'Chrome', '赵子龙', '7', '32', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1354', 'hrpositive', '3', '1', null, '提交', '0', '2018-06-15 20:16:18', null, '127.0.0.1', 'Chrome', '管理员', '1', '32', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1355', 'kqout', '5', '1', null, '提交', '0', '2018-06-15 20:28:19', null, '127.0.0.1', 'Chrome', '管理员', '1', '23', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1356', 'kqinfo', '104', '1', null, '提交', '0', '2018-06-15 20:31:24', null, '127.0.0.1', 'Chrome', '管理员', '1', '6', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1866', 'kqinfo', '145', '1', null, '提交', '0', '2019-02-18 14:23:08', null, '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1867', 'customer', '3', '1', null, '放入客户公海库', '0', '2019-02-18 17:06:51', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'deeppink', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1377', 'customer', '16', '1', '跟进', '跟进记录', '0', '2018-07-05 22:45:44', '跟进', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1378', 'custsale', '4', '1', null, '跟进', '0', '2018-07-05 23:12:16', '啊啊', '127.0.0.1', 'Chrome', '管理员', '1', '8', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1382', 'goodm', '8', '1', null, '提交', '0', '2018-07-06 18:27:39', null, '127.0.0.1', 'Chrome', '管理员', '1', '28', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1459', 'goodm', '8', '1', '同意', '行政审批', '20', '2018-07-28 22:06:23', null, '::1', 'Chrome', '大乔', '4', '28', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1460', 'goodm', '8', '1', '同意', '行政审批', '20', '2018-07-28 22:06:57', null, '::1', 'Chrome', '小乔', '3', '28', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1464', 'kqinfo', '131', '1', null, '提交', '0', '2018-08-07 15:57:02', null, '127.0.0.1', 'Chrome', '管理员', '1', '6', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1465', 'kqinfo', '131', '1', '同意', '上级审核', '3', '2018-08-07 15:57:19', null, '::1', 'Chrome', '磐石', '5', '6', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1466', 'kqinfo', '132', '1', null, '提交', '0', '2018-08-07 17:17:17', null, '127.0.0.1', 'Chrome', '管理员', '1', '30', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1488', 'kqinfo', '104', '1', '同意', '上级审核', '3', '2018-08-21 12:11:52', null, '::1', 'Safari', '磐石', '5', '6', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1559', 'hrkaohem', '2', '1', null, '提交', '0', '2018-09-07 22:18:21', null, '127.0.0.1', 'Chrome', '管理员', '1', '81', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1504', 'goodm', '3', '1', '同意', '上级审批', '19', '2018-08-29 16:44:39', null, '::1', 'Chrome', '磐石', '5', '28', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1546', 'kqout', '5', '1', '同意', '上级审核', '5', '2018-09-06 20:44:40', null, '::1', 'Safari', '磐石', '5', '23', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2964', 'assetm', '2', '1', null, '提交', '0', '2020-04-21 11:30:02', null, '127.0.0.1', 'Chrome', '管理员', '1', '41', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1864', 'kqinfo', '142', '1', '同意', '人事审核', '2', '2019-02-01 10:28:47', null, '::1', 'Chrome', '大乔', '4', '5', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1517', 'hrpositive', '2', '1', '同意', '上级审批', '27', '2018-09-01 13:43:52', null, '127.0.0.1', 'Chrome', '管理员', '1', '32', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1520', 'kqinfo', '110', '1', '同意', '上级审核', '100004', '2018-09-02 12:42:25', null, '::1', 'Chrome', '磐石', '5', '5', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1822', 'kqerr', '2', '1', '同意', '上级审核', '44', '2018-11-13 18:15:35', null, '::1', 'Chrome', '磐石', '5', '51', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1614', 'hrshebao', '1', '1', null, '提交', '0', '2018-09-21 12:04:27', null, '127.0.0.1', 'Chrome', '管理员', '1', '84', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1691', 'hrsalary', '1', '1', null, '提交', '0', '2018-09-28 22:30:13', null, '127.0.0.1', 'Chrome', '管理员', '1', '34', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1742', 'hrsalary', '16', '1', null, '发放', '0', '2018-10-10 15:01:27', null, '127.0.0.1', 'Chrome', '管理员', '1', '34', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1862', 'kqinfo', '142', '1', null, '提交', '0', '2019-02-01 10:28:14', null, '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1863', 'kqinfo', '142', '1', '同意', '上级审核', '1', '2019-02-01 10:28:37', null, '::1', 'Chrome', '磐石', '5', '5', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1708', 'hrsalary', '16', '1', null, '提交', '0', '2018-10-09 19:38:14', null, '127.0.0.1', 'Chrome', '管理员', '1', '34', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1754', 'hrsalary', '15', '1', '同意', '人事审核', '31', '2018-10-26 11:38:34', null, '::1', 'Chrome', '大乔', '4', '34', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1672', 'hrsalarm', '14', '1', null, '提交', '0', '2018-09-27 12:08:52', null, '127.0.0.1', 'Chrome', '管理员', '1', '83', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1971', 'news', '1', '1', null, '提交', '0', '2019-04-16 16:42:21', null, '127.0.0.1', 'Chrome', '管理员', '1', '88', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1746', 'fininfom', '12', '1', null, '提交', '0', '2018-10-22 20:24:32', null, '127.0.0.1', 'Chrome', '管理员', '1', '65', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1665', 'hrsalarm', '13', '1', null, '提交', '0', '2018-09-26 17:34:16', null, '127.0.0.1', 'Chrome', '管理员', '1', '83', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1656', 'hrsalarm', '5', '1', null, '提交', '0', '2018-09-23 18:38:07', null, '127.0.0.1', 'Chrome', '管理员', '1', '83', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1740', 'hrsalary', '2', '1', '同意', '人事审核', '31', '2018-10-10 14:56:32', null, '192.168.1.100', 'Chrome', '大乔', '4', '34', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1741', 'hrsalary', '16', '1', '同意', '人事审核', '31', '2018-10-10 14:56:32', null, '192.168.1.100', 'Chrome', '大乔', '4', '34', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1739', 'hrsalary', '1', '1', '同意', '人事审核', '31', '2018-10-10 14:56:32', null, '192.168.1.100', 'Chrome', '大乔', '4', '34', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1807', 'fininfom', '10', '1', '同意', '上级审批', '62', '2018-11-05 13:42:09', null, '::1', 'Chrome', '磐石', '5', '64', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1808', 'fininfom', '12', '1', '同意', '上级审批', '64', '2018-11-05 13:42:49', null, '::1', 'Chrome', '磐石', '5', '65', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1980', 'project', '1', '1', null, '提交', '0', '2019-04-22 10:29:13', null, '127.0.0.1', 'Chrome', '管理员', '1', '22', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1828', 'finpiao', '1', '1', null, '提交', '0', '2018-11-18 22:24:09', null, '127.0.0.1', 'Chrome', '管理员', '1', '85', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1829', 'finpiao', '2', '1', null, '提交', '0', '2018-11-19 10:52:54', null, '127.0.0.1', 'Chrome', '管理员', '1', '85', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1841', 'flow_remind', '10', '1', null, '提交', '0', '2018-12-04 09:24:15', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1948', 'customer', '3', '1', null, '取消共享', '0', '2019-03-25 21:13:30', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'gray', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1926', 'wordxie', '2', '1', null, '提交', '0', '2019-03-09 20:40:42', null, '127.0.0.1', 'Chrome', '管理员', '1', '86', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1923', 'wordxie', '1', '1', null, '提交', '0', '2019-03-09 19:20:41', null, '127.0.0.1', 'Chrome', '管理员', '1', '86', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1930', 'vcard', '8', '1', null, '提交', '0', '2019-03-13 19:48:11', null, '127.0.0.1', 'Chrome', '管理员', '1', '53', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1919', 'wordeil', '1', '1', null, '提交', '0', '2019-03-09 13:13:20', null, '127.0.0.1', 'Chrome', '管理员', '1', '87', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1921', 'wordeil', '1', '1', '接收', '接收人接收', '83', '2019-03-09 13:14:46', null, '::1', 'Chrome', '小乔', '3', '87', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('1947', 'customer', '3', '0', null, '分配', '0', '2019-03-25 21:13:21', '分配给:管理员', '127.0.0.1', 'Chrome', '管理员', '1', '7', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2052', 'hrsalary', '26', '1', null, '提交', '0', '2019-05-07 15:10:19', null, '127.0.0.1', 'Chrome', '管理员', '1', '34', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2116', 'kqinfo', '110', '1', '同意', '人事审核', '2', '2019-06-04 10:59:38', null, '192.168.1.104', 'Chrome', '大乔', '4', '5', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2117', 'hrpositive', '3', '1', '同意', '人事审批', '28', '2019-06-04 10:59:38', null, '192.168.1.104', 'Chrome', '大乔', '4', '32', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2118', 'hrpositive', '2', '1', '同意', '人事审批', '28', '2019-06-04 10:59:38', null, '192.168.1.104', 'Chrome', '大乔', '4', '32', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2119', 'hrdemint', '1', '1', '同意', '人事审核', '74', '2019-06-04 10:59:38', null, '192.168.1.104', 'Chrome', '大乔', '4', '78', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2120', 'kqerr', '2', '1', '同意', '人事确认', '45', '2019-06-04 10:59:38', null, '192.168.1.104', 'Chrome', '大乔', '4', '51', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2121', 'reward', '2', '1', '同意', '人事审批', '36', '2019-06-04 10:59:38', null, '192.168.1.104', 'Chrome', '大乔', '4', '39', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2122', 'kqinfo', '4', '1', '同意', '人事审核', '2', '2019-06-04 10:59:38', null, '192.168.1.104', 'Chrome', '大乔', '4', '5', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2123', 'fininfom', '5', '1', '同意', '人事审核', '8', '2019-06-04 10:59:38', null, '192.168.1.104', 'Chrome', '大乔', '4', '11', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2124', 'worc', '5', '1', null, '提交', '0', '2019-06-05 17:22:44', null, '192.168.1.104', 'Chrome', '大乔', '4', '74', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2126', 'knowledge', '2', '1', null, '提交', '0', '2019-06-05 18:52:43', null, '192.168.1.104', 'Chrome', '大乔', '4', '40', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2141', 'goodm', '13', '1', null, '提交', '0', '2019-06-07 12:54:28', null, '192.168.1.104', 'Chrome', '大乔', '4', '28', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2142', 'goodm', '13', '1', '同意', '上级审批', '19', '2019-06-07 12:54:44', null, '::1', 'Chrome', '磐石', '5', '28', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2143', 'goodm', '13', '1', '同意', '行政审批', '20', '2019-06-07 12:54:58', null, '::1', 'Chrome', '磐石', '5', '28', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3141', 'fininfom', '9', '2', '不同意', '财务审核', '9', '2020-06-15 20:11:39', '不全', '::1', 'Chrome', '貂蝉', '2', '11', 'red', '1', '3', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2115', 'hrsalary', '20', '1', '同意', '人事审核', '31', '2019-06-04 10:59:37', null, '192.168.1.104', 'Chrome', '大乔', '4', '34', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2913', 'goodm', '17', '1', '发放', '行政发放', '6', '2020-03-31 22:18:46', null, '::1', 'Chrome', '大乔', '4', '24', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2113', 'hrsalary', '25', '1', '同意', '人事审核', '31', '2019-06-04 10:59:37', null, '192.168.1.104', 'Chrome', '大乔', '4', '34', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2112', 'hrsalary', '26', '1', '同意', '人事审核', '31', '2019-06-04 10:59:35', null, '192.168.1.104', 'Chrome', '大乔', '4', '34', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2140', 'godepot', '3', '1', null, '提交', '0', '2019-06-07 12:49:00', null, '192.168.1.104', 'Chrome', '大乔', '4', '73', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2254', 'kqerr', '4', '1', '同意', '上级审核', '44', '2019-08-25 19:48:51', null, '192.168.1.106', 'iPhone', '磐石', '5', '51', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2139', 'goods', '9', '1', null, '提交', '0', '2019-06-07 12:44:13', null, '192.168.1.104', 'Chrome', '大乔', '4', '9', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2106', 'admin', '4', '0', null, '设置为管理员', '0', '2019-06-03 17:35:47', null, '127.0.0.1', 'Chrome', '管理员', '1', '10', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2029', 'official', '3', '1', null, '提交', '0', '2019-04-25 20:28:40', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2030', 'official', '3', '1', '同意', '上级复搞', '21', '2019-04-25 20:28:55', null, '::1', 'Chrome', '磐石', '5', '19', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2031', 'official', '3', '1', '流转', '申请人流转', '88909', '2019-04-25 20:29:23', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2051', 'hrsalary', '25', '1', null, '提交', '0', '2019-05-07 15:08:33', null, '127.0.0.1', 'Chrome', '管理员', '1', '34', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2045', 'goodm', '10', '1', null, '提交', '0', '2019-04-29 11:24:12', null, '127.0.0.1', 'Chrome', '管理员', '1', '76', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2050', 'hrsalary', '25', '1', null, '提交', '0', '2019-05-07 15:08:33', null, '127.0.0.1', 'Chrome', '管理员', '1', '34', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2076', 'kqinfo', '147', '1', null, '提交', '0', '2019-05-12 18:41:46', null, '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2077', 'official', '4', '1', null, '提交', '0', '2019-05-12 23:10:41', null, '127.0.0.1', 'Chrome', '管理员', '1', '20', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2057', 'hrsalary', '26', '1', null, '追加说明', '0', '2019-05-09 00:23:39', '呵呵呵', '127.0.0.1', 'Chrome', '管理员', '1', '34', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2078', 'official', '4', '1', '同意', '上级审批', '24', '2019-05-12 23:11:00', null, '::1', 'Chrome', '磐石', '5', '20', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2074', 'custfina', '9', '1', null, '提交', '0', '2019-05-12 14:37:02', null, '127.0.0.1', 'Chrome', '管理员', '1', '36', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2075', 'custfina', '10', '1', null, '提交', '0', '2019-05-12 14:37:23', null, '127.0.0.1', 'Chrome', '管理员', '1', '36', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2066', 'project', '1', '0', null, '更新进度', '0', '2019-05-10 18:46:11', null, '127.0.0.1', 'Chrome', '管理员', '1', '22', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2073', 'goodm', '11', '1', '同意', '上级审批', '88', '2019-05-12 14:36:24', null, '::1', 'Chrome', '磐石', '5', '92', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2072', 'goodm', '11', '1', null, '提交', '0', '2019-05-12 14:36:03', null, '127.0.0.1', 'Chrome', '管理员', '1', '92', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2069', 'custfina', '7', '1', null, '提交', '0', '2019-05-12 00:00:18', null, '127.0.0.1', 'Chrome', '管理员', '1', '36', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2070', 'custfina', '7', '0', '已收款', '标已收款', '0', '2019-05-12 00:00:45', null, '127.0.0.1', 'Chrome', '管理员', '1', '36', 'green', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2071', 'custfina', '8', '1', null, '提交', '0', '2019-05-12 00:02:44', null, '127.0.0.1', 'Chrome', '管理员', '1', '36', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2079', 'official', '4', '1', '流转', '申请人流转', '88912', '2019-05-12 23:11:10', null, '127.0.0.1', 'Chrome', '管理员', '1', '20', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2081', 'kqinfo', '11', '1', null, '追加说明', '0', '2019-05-14 23:09:47', '尽快去处理啊', '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2083', 'kqinfo', '11', '1', null, '作废', '0', '2019-05-14 23:25:20', '费了吧', '127.0.0.1', 'Chrome', '管理员', '1', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2136', 'kqerr', '4', '1', null, '提交', '0', '2019-06-06 18:23:56', null, '192.168.1.104', 'Chrome', '大乔', '4', '51', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2088', 'kqinfo', '109', '1', null, '监控转办', '0', '2019-05-28 18:17:44', '转办给：管理员', '::1', 'Chrome', '磐石', '5', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2089', 'kqinfo', '109', '1', null, '监控转办', '0', '2019-05-28 18:26:34', '将人事审核转办给：张飞', '::1', 'Chrome', '磐石', '5', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2090', 'kqinfo', '109', '1', null, '监控转办', '0', '2019-05-28 18:39:29', '将[人事审核]转办给：小乔', '::1', 'Chrome', '磐石', '5', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2091', 'kqinfo', '109', '1', null, '监控转办', '0', '2019-05-28 18:49:37', '将[人事审核]转办给：貂蝉', '::1', 'Chrome', '磐石', '5', '5', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2173', 'bookborrow', '4', '1', '同意', '行政确认', '39', '2019-07-01 11:45:07', null, '::1', 'Chrome', '小乔', '3', '46', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2918', 'goodm', '21', '1', null, '提交', '0', '2020-04-01 20:19:36', null, '127.0.0.1', 'Chrome', '管理员', '1', '24', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2919', 'goodm', '21', '1', '发放', '行政发放', '6', '2020-04-01 20:20:07', null, '::1', 'Chrome', '大乔', '4', '24', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2962', 'assetm', '1', '1', null, '提交', '0', '2020-04-20 20:45:41', null, '127.0.0.1', 'Chrome', '管理员', '1', '41', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2969', 'official', '3', '0', null, '标无需盖章', '0', '2020-04-25 22:12:33', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', 'blue', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('2971', 'reward', '3', '1', null, '提交', '0', '2020-05-15 11:59:21', null, '127.0.0.1', 'Chrome', '管理员', '1', '39', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3056', 'wenjuan', '1', '1', null, '提交', '0', '2020-05-27 17:59:24', null, '127.0.0.1', 'Chrome', '管理员', '1', '101', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3189', 'reward', '3', '1', '同意', '人事审批', '36', '2020-06-24 11:18:03', null, '::1', 'Chrome', '大乔', '4', '39', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3120', 'admin', '4', '0', null, '取消管理员', '0', '2020-06-10 15:07:56', null, '127.0.0.1', 'Chrome', '管理员', '1', '10', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3192', 'meet', '5', '6', '签到', '签到', '0', '2020-06-30 11:45:39', null, '192.168.0.102', 'Chrome', '管理员', '1', '2', 'blue', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3191', 'meet', '5', '1', null, '提交', '0', '2020-06-30 11:13:57', null, '127.0.0.1', 'Chrome', '管理员', '1', '2', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3156', 'official', '9', '1', null, '提交', '0', '2020-06-19 17:11:39', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3187', 'reward', '3', '2', '不同意', '人事审批', '36', '2020-06-24 11:16:57', '噩噩噩噩', '::1', 'Chrome', '大乔', '4', '39', 'red', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3185', 'reward', '3', '2', '不同意', '人事审批', '36', '2020-06-24 11:16:04', '额为', '::1', 'Chrome', '大乔', '4', '39', 'red', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3164', 'official', '9', '1', '同意', '上级复搞', '21', '2020-06-20 10:21:35', null, '::1', 'Chrome', '张飞', '6', '19', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3401', 'kqinfo', '103', '2', '不同意', '人事审核', '2', '2020-09-27 19:44:31', '好', '::1', 'Chrome', '大乔', '4', '5', 'red', '0', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3400', 'kqinfo', '103', '1', '同意', '上级审核', '1', '2020-09-27 19:41:01', null, '::1', 'Chrome', '貂蝉', '2', '5', 'green', '0', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3399', 'fininfom', '9', '1', '同意', '财务审核', '9', '2020-09-27 19:40:56', null, '::1', 'Chrome', '貂蝉', '2', '11', 'green', '1', '3', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3396', 'carms', '4', '1', null, '提交', '0', '2020-09-26 10:57:42', null, '127.0.0.1', 'Chrome', '管理员', '1', '58', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3165', 'official', '9', '1', '流转', '申请人流转', '88909', '2020-06-20 11:13:22', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3166', 'official', '9', '0', null, '标无需套红', '0', '2020-06-20 11:16:00', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3167', 'official', '9', '0', null, '标无需盖章', '0', '2020-06-20 11:19:24', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', 'blue', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3172', 'reward', '3', '1', '无异议', '当事人确认', '35', '2020-06-20 18:02:43', null, '192.168.0.112', 'Chrome', '管理员', '1', '39', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3909', 'wordxie', '6', '1', null, '提交', '0', '2020-12-24 22:11:35', null, '127.0.0.1', 'Chrome', '管理员', '1', '86', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3908', 'custract', '7', '1', null, '提交', '0', '2020-12-24 21:50:31', null, '127.0.0.1', 'Chrome', '管理员', '1', '35', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3241', 'customer', '5', '1', null, '放入客户公海库', '0', '2020-07-20 10:59:19', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'deeppink', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3233', 'hrsalary', '27', '1', '同意', '人事审核', '31', '2020-07-17 18:38:24', null, '192.168.0.106', 'Chrome', '大乔', '4', '34', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3232', 'kqinfo', '157', '1', null, '提交', '0', '2020-07-16 13:59:49', null, '127.0.0.1', 'Chrome', '管理员', '1', '30', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3223', 'meet', '6', '1', null, '提交', '0', '2020-07-08 13:21:04', null, '127.0.0.1', 'Chrome', '管理员', '1', '2', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3224', 'hrsalary', '27', '1', null, '提交', '0', '2020-07-10 20:49:04', null, '127.0.0.1', 'Chrome', '管理员', '1', '34', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3319', 'goodm', '23', '1', null, '提交', '0', '2020-09-08 21:33:18', null, '127.0.0.1', 'Chrome', '管理员', '1', '102', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3318', 'goodm', '22', '1', '同意', '上级审批', '91', '2020-09-08 20:32:02', null, '::1', 'Chrome', '磐石', '5', '102', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3317', 'goodm', '22', '1', null, '提交', '0', '2020-09-08 18:06:35', null, '127.0.0.1', 'Chrome', '管理员', '1', '102', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3863', 'hrtrsalary', '1', '1', '同意', '上级审批', '33', '2020-11-13 17:25:54', null, '192.168.0.102', 'Chrome', '磐石', '5', '37', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3485', 'userzheng', '1', '1', null, '提交', '0', '2020-10-13 12:00:48', null, '127.0.0.1', 'Chrome', '管理员', '1', '108', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3488', 'flow_remind', '12', '1', null, '提交', '0', '2020-10-13 12:15:16', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3503', 'userzheng', '2', '1', null, '提交', '0', '2020-10-13 15:37:08', null, '127.0.0.1', 'Chrome', '管理员', '1', '108', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3875', 'infor', '6', '1', null, '评论', '0', '2020-11-16 13:01:34', '继续保持', '127.0.0.1', 'Safari', '管理员', '1', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3874', 'infor', '6', '1', null, '评论', '0', '2020-11-16 12:59:32', '很好很不错', '192.168.0.108', 'iPhone', '磐石', '5', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3872', 'kqinfo', '158', '1', '同意', '上级审核', '3', '2020-11-16 12:31:47', null, '192.168.0.108', 'iPhone', '磐石', '5', '6', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3869', 'kqinfo', '158', '1', null, '提交', '0', '2020-11-16 12:24:24', null, '192.168.0.108', 'iPhone', '管理员', '1', '6', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3878', 'goodm', '23', '1', '同意', '上级审批', '91', '2020-11-18 15:46:50', null, '192.168.0.108', 'iPhone', '磐石', '5', '102', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3773', 'sealapl', '1', '1', '已盖章', '申请人确认', '43', '2020-11-02 11:57:42', null, '127.0.0.1', 'Chrome', '管理员', '1', '49', null, '1', '3', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3772', 'sealapl', '1', '1', null, '提交', '0', '2020-11-02 11:57:23', null, '127.0.0.1', 'Chrome', '管理员', '1', '49', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3770', 'official', '1', '1', '流转', '申请人流转', '88909', '2020-11-01 08:48:02', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', null, '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3652', 'customer', '15', '1', null, '取消共享', '0', '2020-10-23 16:22:06', null, '127.0.0.1', 'Chrome', '管理员', '1', '7', 'gray', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3765', 'wordxie', '5', '1', null, '提交', '0', '2020-10-31 22:09:57', null, '127.0.0.1', 'Chrome', '管理员', '1', '86', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3769', 'official', '1', '1', '同意', '上级复搞', '21', '2020-11-01 08:47:49', null, '::1', 'Chrome', '磐石', '5', '19', 'green', '1', '1', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARgAAAB4CAYAAAApDe1iAAALTElEQVR4Xu1dya4dNRCtJAQSgphZMAgWsACB+Ab4cvgFxLiADWJYMEQghkCYVI+uxK9zu2/brrKr7HMlRCDtsuvU8XF56r5C+AEBIAAEjBC4YmQXZoEAEAACBIEBCYAAEDBDAAJjBi0MAwEgAIEBB4AAEDBDAAJjBi0MAwEgAIEBB4AAEDBDAAJjBi0MAwEgAIEBB4AAEDBDAAJjBi0MAwEgAIEBB4AAEDBDAAJjBi0MAwEgAIEBB4AAEDBDAAJjBi0MAwEgAIEBB4AAEDBDAAJjBi0MAwEgAIEBB4AAEDBDAAJjBi0MO0fgXyLif646b2fo5nkQGA6y1Y9t/01E160qgN2wCDA3/iGia2E9CNBwDwKjDdPdhTRr30b0VRu7WewxRx4iwgvXrAM+S6eTLGkWf615E90+Zy7MBfDBOJKzAAyBMSZSMPNYf2kUMAhMI6BRjSsEWGD+wtqcfUwgMPYYowZfCPCiP+8czcL9rujPAjKmSF1p5qpycKFhOGYSGGxJNiSW06o+IKK3l6MLvIuEnzECMwgMdgyMSRTIPLjQOFgzCAx2DBqTynF14ELj4IwuML8T0Q0iukNENxtji+p8IYDspUM8RhcYkKoDqZxWieylQ2BGFxiQap9UfBaE7+KMzgPx82MieqtDP5u2ypGJhfMOx2g9w7btDD4ei3bjp0YWGJDqOJlGzvQ+IqI3sTV9nAyaT44qMFh7yWOJTCFGfD8KuJDHBdWnRxQYmRrhYF0eVaQjMn4jHUIbOTvLi3CHp0cUGBCqnEijjfaj+VMe2U4lRxMYrLvUE2kkDDHY1POhysJIAoPRqooKlwqP0DFlXenD5f6RHjqwdBiBUQQG6y6HQ37owREWfUfKxA4FzeNDowjMCCOuN35EXvT9iYgex9Z0f0qNIDAYqex4FHXaGbXddpHsZDm6wMjUCJcZ7QgUUcDxSRI7PmRZji4wmBplhbv44Ugig+ylOMz6BSMLDIikz4c9i1FEBoNOW17s1hZZYECk9kTyLjK44NqeE0MKDLKXfkTyLDIYdPrx4mTNETMYOaMx2p0ZZ9TYbY5HkRFe/ExET0QCc+S2RhQYj+QemSNbvnmLg7f2zMiJB3yOJjCYGvmirZdOjYN1vnhxrzXRBAZzbH9E8iAyGHj88eKiRZEEBiRySiIiEpHptS6Gg3VOuRFFYOTzI3iJlFMiEZEMAK3fioeBxy8nwmQwHtJwx2F00zQ5h8IN+oyI3mjQskjTZt7punpi5hBloM8OZwTHasRFymYDsyoQAadaH7XKf0pEry/GrDPOCAfrOPt+ZCUq1rhoxbLajveOI+lv6dmGGnEScDVsVAcqoIEWUybP2Yv4n/KIs5epfp4FRgLEaeX1jlHBHL8cfDn8xha0R22PB+vSKSL7zAL4x8yfLfYqMNKptUlZ0lUipOElfrUsk47mPGV4VKHyXpnln8nXME/1H9nRGunLDMXh8igwLVLrHMA+J6JXiegLInotpyCevYTAb8lIXrvTJKLP2cENA5zPiUhapUzTOKPitRb8EgS8CYwQp5aA2kHGOQs9RNNpRCn/NNZeWJw4y+A2nGuH1Mdtf1gPivEtnQO2JQJ3l4B7ExeZS3tsV8v4aNYlsS5ZX8tdE/uSiF48ICQQEc0IL7a8CIzcJVl34nSbuWdbe833DULuxmRpFrJXjoXr2g630xPHPTcO3ATBuiE9O+16Hsv/fao9HhZZc0dN67i1su9NWCUOnJW8dCYrESH6ioheaQUY6rmMgAeBOULi3msgPxLRU0R0m4ienohER2JjDQeyEmuEDe33FpijBD76nCFUF2caZluHaYm7HKPfymQltpzFfE1EL1sGG7Z1EOgpMDnk9TBNymmvTnT6W9H2mU9k3ypYcJWp0bdE9EJ/WNCCowj0EpgS4vaeJs24DlMSJ+be0XMkkhX+unyJcYu3M2aPR/uw6+d6CEwpaUvLaQVgxnWYI5jLGskWl8QGC3TJ6dYZhV2Ls93ttBaYI4TdAsUD0WYZSdd3aoQnR8SEy2ptAX9DRM9PuPbVXRi0GtBSYGrEhf3FOoxW1PftpGeP+M97mYmmmJxqVS1n2iCGWjYRaCEwGkfDZV7PI2OLNu+tBZzb5YhMt/T2c+oHd3RrMVnjJm0pOe0bOQZDtd26s2pfXGSic5re6z4IRtR29J9lOtoO0Q41WQlMmrVovnKh904SBKYNST2st7XxdPBaLARGO2tZp+o9D7tBYNp0CGQvbXA2r0VbYNItSbl0pulE7w7eu35NLL3aQvbiNTIF7dIUmBadrzf5WvhYEMZhitxZXtqkOa0eBpyIjmgJjHQ8rdchbmEpOwta7c6NGQQmF7G854FvHl7un9boqNLpW62NcD080t3sgC46gB3osjHA1wzSV09+knxfifH/5cy1ArsWwnI2AhoCk11pZYGeO0kQmMrg7RTfW9hl8Vm/2lKet1jrs/NyMstRBaZVtrSmAwTGpoPkrK3xjezHVgcuI/LYBklnViMGJoeM2nBDYLQR/d9ezbY0YmITExWrEQVG404Sv1Ferh3kfDUSZFah3SUjtQPG90T0DC5E6gdGw2JEgeGdKv4WTknbaz/nCYHRYN19G/Ky99ptaV4Y5gGj19RZF5WBrJV0Ug/uM5E4kznyfpE0W5F0nO8zlXwkCwKjG31NPFvvZuoiMai1yAJzbrSqzVZOhVyzQwxKqcNuWdyW9vrhvsOgjPZgZIHhWJx7ixoLQmm2AoGxZXvNwu5eyzx919wWwQDWowrM3sKgZZZhaTsAXdSaWLuwe64hYv/oNPqcPfx9IQKjCYwIwF52UwjVRSbEaz54AVIpgvfLWWUvactEZHq+P6geqeAWogrM1p0kOeV7dYmLpn/IXnTIbp29nBKZH4joWZ3mw0oOApodMKfe2mffI6J3iOh9Inp3MZaej9EWA2QvtRFrm72krdXmgh4SE1iKKjAcmvVWdUokjcN4IKl+B2iZvSB++vHLthhdYNKt6vUlSK1Lkchesml1soDg2GvhFZmMThyzrIwiMKcyFi1CadnJCsyAD3vA0UMbBgzttkvRBYY9Yx+2iCP/n++rPFcQWWQvBaCdKCIDQM69L52aH7QCkbFC9oTdyAKTzuf3pkNCqJLUHGSsI2O6q3fu5HVdTXmlEdc8vIqfjiwwMiqy0PC29J4vJac7ey1IFgfTWcH06xLrl0V5aCpEpkEUIguM3KAVmM75kvM5lVS88Ma0fCLeJqInl6mrTGHlbFK+NbsSEBk7bC8sn+uUxtVXmxeCHL3uf/QyXIuTptXOOzYgceF/e8xeUuggMoZEGkVgcvxIv7+8LpfewM6xaRiicKYFQ84w+RO/R8W/p6MQGSP0o3eiUmJ8t3N03NNipFHYzcyup0aRsuRSLpmBOYLhWQVmhNh59CHtpFoHHVv6CZFRRhsCowzo5OYirb1shQoio0ji6AKjCAVMKSAgnZNNRZ5qih94NUclKSAwlQCi+CUEUoHp9fVNrZDkHGvQqnM4OxCY4ULa1aFUYEbgFnYVK+k0AgkqIUBxRQTSNRiPB+tKXOVvYd9aCkbYci/x0awMBMYM2ikNj7xAiilTAaUhMAWgocgmAiMLDDstJ8H5z78u38gGHXYQgMCAHpoIjC4wgtWIU0FNHtyzBYExgXVao7MIDAcYU6YDNIfAHAAJjwCBDQTkhWT81+hLJ0ACKOg7QKAegZkytyy0IDBZcOFhIDDtAndR6CEwRbChEBA4iQAymRUsEBj0FCCgiwBEJsETAqNLLlgDAowARGbhAQQGHQII2CAAkcHWmg2zYBUILAiwyJR8LmcYAJHBDBNKOAIE/CEAgfEXE7QICAyDAARmmFDCESDgD4H/AN+fNZc5+KLgAAAAAElFTkSuQmCC', '0');
INSERT INTO `xinhu_flow_log` VALUES ('3767', 'wordxie', '5', '1', null, '上传文件', '0', '2020-10-31 22:16:16', null, '127.0.0.1', 'Chrome', '管理员', '1', '86', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3705', 'wordxie', '4', '1', null, '提交', '0', '2020-10-28 19:35:43', null, '127.0.0.1', 'Chrome', '管理员', '1', '86', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3918', 'official', '10', '1', '同意', '主要领导会签', '25', '2021-01-07 18:36:21', null, '192.168.0.102', 'Chrome', '赵子龙', '7', '20', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3917', 'official', '10', '1', '同意', '上级审批', '24', '2021-01-07 18:34:33', null, '::1', 'Chrome', '磐石', '5', '20', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3907', 'work', '3', '1', null, '评论', '0', '2020-12-24 15:54:40', '44', '127.0.0.1', 'Chrome', '管理员', '1', '4', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3893', 'infor', '6', '1', null, '评论', '0', '2020-12-10 16:22:57', '很好，和好', '127.0.0.1', 'Chrome', '管理员', '1', '1', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3914', 'official', '10', '1', null, '提交', '0', '2021-01-07 17:48:19', null, '127.0.0.1', 'Chrome', '管理员', '1', '20', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3915', 'official', '10', '1', '同意', '上级审批', '0', '2021-01-07 18:03:50', null, '192.168.0.102', 'Chrome', '磐石', '5', '20', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3903', 'work', '3', '1', null, '提交', '0', '2020-12-18 13:53:35', null, '127.0.0.1', 'Chrome', '管理员', '1', '4', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('3916', 'official', '10', '1', null, '撤回', '0', '2021-01-07 18:33:34', null, '::1', 'Chrome', '磐石', '5', '20', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4083', 'daily', '38', '1', null, '提交', '0', '2021-08-21 13:15:20', null, '127.0.0.1', 'Chrome', '管理员', '1', '3', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4000', 'work', '3', '1', '已完成', '执行人执行', '0', '2021-04-28 21:14:29', '转给：磐石', '::1', 'Chrome', '赵子龙', '7', '4', null, '1', '1', null, '1');
INSERT INTO `xinhu_flow_log` VALUES ('4147', 'official', '11', '0', null, '标无需盖章', '0', '2021-10-12 11:30:42', null, 'unknow', 'Chrome', '大乔', '4', '19', 'blue', '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4149', 'flow_remind', '15', '1', null, '提交', '0', '2021-10-12 18:34:26', null, '127.0.0.1', 'Chrome', '管理员', '1', '61', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4008', 'hrcheck', '2', '1', null, '评分', '0', '2021-04-30 09:56:02', null, '127.0.0.1', 'Chrome', '管理员', '1', '71', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4012', 'work', '11', '1', null, '监控转办', '0', '2021-06-01 10:26:32', '将[执行人执行]转办给：张飞', '127.0.0.1', 'Chrome', '管理员', '1', '4', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4013', 'work', '11', '1', '已完成', '执行人执行', '52', '2021-06-01 10:27:27', '很好', '::1', 'Chrome', '张飞', '6', '4', null, '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4014', 'work', '11', '1', '同意', '提交人验证', '53', '2021-06-01 10:27:40', null, '127.0.0.1', 'Chrome', '管理员', '1', '4', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4075', 'hrredund', '1', '1', '同意', '小乔确认', '126', '2021-07-03 18:12:45', null, '::1', 'Chrome', '小乔', '3', '33', 'green', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4076', 'project', '1', '0', null, '更新进度', '0', '2021-07-16 16:54:51', null, '127.0.0.1', 'Chrome', '管理员', '1', '22', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4141', 'project', '1', '0', null, '更新进度', '0', '2021-10-12 10:18:37', '完美', '127.0.0.1', 'Chrome', '管理员', '1', '22', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4142', 'project', '1', '0', null, '更新进度', '0', '2021-10-12 10:20:48', null, '127.0.0.1', 'Chrome', '管理员', '1', '22', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4143', 'official', '11', '1', null, '提交', '0', '2021-10-12 11:04:36', null, 'unknow', 'Chrome', '大乔', '4', '19', null, '1', '0', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4144', 'official', '11', '1', '同意', '上级复搞', '21', '2021-10-12 11:05:01', null, '127.0.0.1', 'Chrome', '管理员', '1', '19', 'green', '1', '1', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARgAAAB4CAYAAAApDe1iAAAAAXNSR0IArs4c6QAADCxJREFUeF7tXcnub0MQLq7hmhaEINiJYOcJ2LAxRryCd7CwsBEv4RmIIRZseAlDWIi4SAgL8yx1/ZtO3/M7PVZXVZ/vl8i97umurv6+6u/0fC4j/IAAEAACQghcJmQXZoEAEAACBIFBEAABICCGAARGDFoYBgJAAAKDGAACQEAMAQiMGLQwDASAAAQGMQAEgIAYAhAYMWhhGAgAAQgMYgAIAAExBCAwYtDCMBAAAhAYxAAQAAJiCEBgxKCFYSAABCAwiAEgAATEEIDAiEF70fBfRBePYwBnWZxh3SgCCHyjxMAtILACAhCYFVhEHYCAUQQgMEaJgVtAYAUEIDArsIg6AAGjCEBgjBIDt4DACghAYFZgEXUAAkYRWE1geFn4cqNYw625CHAsxL+/iejcXBdQ2ioC80cUPKvUCdHZhwALytaP/x0voT5si3N7b4yxsPAbC2+oYuoPmfBXIroqqvlvRHT1IZGYVGmvAgNhmRQgCxcTdllzFb22A/P0eAMWwmI+pNw5+OfZkMlbW3ABtBdQISwuwsm1kzw346U9uAHaMqBhFSD4mE7afUhE97tBGo56QAAiM5glqwITj49zVeag+ImIrs8lxHMgUIAARKYApNIkFgUm7qnk/Avj51Bfzvt7tDKAfTGlkTAu3QoNdIU6jGO0w1KuAXeYrs4aei1BYGrvUYnnadLCLdWzGhiHGbw3UNzjMyjoLDS8uBcS/OH9CVd2TLpxL4b3xMT143KuGIQbzOwj4L2BevffTHxqCgzPm1xzhsQv0d/j4c53RHRTJ1q8uSoVK2zK6wQ1k917A/Xuvyy7Fda1BCb0WvZ6FdzNHi0EbDPtvvP/c4/pfAVuSIoeDGKgAAENgSl9OwQxGH1uJAjM+0R078YwDIJTEDiL92A4Bn7EymR/IMwWmHgCN+e91A7LMBmc1n1rKMU+QnByTF36vPQlUm9ZPodEz1nea6MlzBSYWuK+JaIbOyZ69yAv6R1BcNqD1qvAlMRFOyoHzDlLYJi4n4no2kqMw76W+ARspYnN5C3iBcEpR96jwHj0uZwRpZQzBKZnT4TkG6U3oCA4p4O2F9vZzeHUsHm2H8uVJy0wvYHWmz9HWGvPassuBOd/VKR5y/Fa+py3R3DvuHZTZ6n9w6ezLjCfE9EdQvMwTL7URDLbPrLgWBWYWFDixi/dDg4rNNLAptv/W4BmH09df9hiL80jbX+rvNWD24rAnBKU9MzaiDiCjQ0EpAVGUhhWIVSag1qc0suya/OH9LOFe0+0ISitLHbmsxbcW9XhtxDfmyrpq+RkcidF07Ov8FKAoEwPm+0CJRvtyCpywPBMP58pkvi9RkRPCIuYhN+aNrdOr4ejHVaGSJr4oGxHDWpGDwONor1J8FkuPqmevrC8vMDaa46cuwh4CQC+fmErgEfTG44F4FMW7cjyVab3nIkN4zn6LFm7Z8g5HQEvAsPAcLDy0q/kqWfJZevp5CoWmF55yqJzn6I/KFoJAW8CM+ONWHtmSok608XGw80g2jO4Mw3KEZ3zJDCzehfh63+esLEWu1vzWTUn6a3VB/40IuCtEXGQXiCiOxvrW5ptxqRyqS8e052aMA//PvoiMY8YHcJnjwIzq6vN5bxORE8eIhLGVnJvRe6Ds4u+uERv8TcWpQNY80bwzKXkmWWtFmol2JWkWQ2Xw9UHArNPufQGv1UDrlQ8er8esSp+y9TLm8DMmugNBM8ub5XAKhWYUN+eO4NWwWzJengTGI03Hpat60O/VmC4BIhMPc7mc3gTmK+J6ObJk4Ph+01bWLU0JPNBMcDBVlwg5gPAt2TCm8CEN903RHTLRCBPLVu3NqSJrqsU1YNLfJrbY3yqAG61UI8ESl0EnuOIy02/NNnTkHLlHf05sF0gArwKjMZ3prcCHo1ArhEAWzlsp1n2KjCzNtulRHC5sbihEciFKrCVw3aaZQhMHdTp7XpoBHX41aQGtjVoGU3rUWC0Ay8tf/S5pXBRdS5kVr5nRZvjHPZ4XogABKYQqCRZKio9J4X5Mq1zjUvvHvnLId6DZc42nk9GwGOAWtldm65mhbduyaXZW7jXXlQ965a/mSGZYspc52J05Z7cTOxFysqRJ1Jop1Er3ecfiOi6pAGEu2RyVQyTxb2XmI8enuX8lnwedvLGV26Wlucxjkvr5jqdN2Liqxgt+G6hN7XCFnuuwydEdHfUmj6KrnXYa2RWXjiuhUDKeQuNtKRu8Scy2GcOyNm7eU/5qd2L8N7AwvEAHuq0XkSlzUFJDB8yjXWBCT0EJicOPq3dvHsiI/ndplxwer1WIggDx2FvLLKtj8++aJDDC88nIdBLqoSb6Qe9tt5q6YY3CT9qbH5JRLcNaCQ1ZcZpLQzVan2XOHO0wnCxFkfT6S0JTCwsua6yxS6xdiP31IsJ4pLjuaXxQGRaUBPKY0VgagPOosAwRZp+eZmLCVzzMvtVQnENkRECttasBYEJk3y82az0Z7kxaQ7fuGy+v4aXzy3+4r1C0vtXIDIGIkBbYMKwqNYPjZvtaujSCm7LwhvOcTGOtXzXYB+nZR7eIaJHWg0gXx8Cs4je8zJ0mWt94Xzp/Sx9aIzL3SqcIzzQErec74Hnl4no2Vzigc81e5QDq+HTVG2jlqply5u3ZWgl5f+W3ZY6jfBPcx7olP9BXPhP6aHRlg+tL7ERfBzahhWBYRJqG0Zteg2iNUTwRyK6duIwJIerld3XWoKfw2fp55YEJogMz69cXYC69rJwgYsXk7DIfEVEt5dmGJDOivhqzLvswReGrpqbIgfQ68eENYFJL3TaQ/JbIrrR0Jv6lK8ap541ytwbmkjsd2ltZW8T0cNR5jeJ6PFWY8i3j4A1gWFva7qy/KaW3E8xKn5q6jSqTI3hWex76EWNOAYwCpPYzhtE9Gj0gkKvRgBliwJTMx9jZShQQs3sBq8hagEHrivPBaXXWZTgpJEm9PhC2RCbQSxYFZjS+Rgv8zBpw7t+EH97ZrQEJiyTW10uz0Gfik1YgQr5NFbBcj6bfW5ZYErnYzwF8swNghria2XFaFSDS8Um2LXcbkbVfYgd60CVvIVnDz16gS+pU28ZnF9js9+suo3Ap8eGp5daTz2781oXmJL5GI9BPWPuaGZvqTsQHRqAyBSQ5kFgYpHhbn96j61HgQl14jt8zxfw1JLkeyLiuR4vHLfUUTsPRCbDgKfgSyfbQiPlP60uhe7B/zUR3SwsAGgAshL0GBHxcrendiSLSGLdKzDpt4S81kN6GMMCw18/uGFqVB2rMBYYFhqvMSjKFkARhbfIuORqj5eNiEVAGU70GRHdBZG5lCEIjI2olZpHwlUF8/jli774DF3NxWnzvFMqCQKjBPxGsRIi420J3w4bbZ5wb5SHvde0ZV8vFwTGFqejl69H27OFlk1vGPMLRHSnTffmegWBmYt3SWkjRWGkrRLfkeZfBBj3t84OUx4aEwiMTfpHzZ1IDLtsImbPK+bwaSJ61Z5r8zyCwMzDurakEStAEJha1Mel/4KIbj36pC8EZlxASVhiken5BjcERoKVMpvPEdFLR1+6hsCUBYtWqt7dvhAYLeb+n4t5nohe1HVDr3QIjB72pSX37PaFwJSiLJOOl635vBlfwn7IHwTGB+2tu31b8/lAxYeXYSXvFSJ65oTLDxLRez6qU+clBKYOL83Urb0RHHjUZO3fsnkl6amz5etPiYj/C79wUPchfTfHewCBGY+ppMUWkcFuXklG6myz0DyQCMwLRPRunRk/qSEwfrgKntZunsMwyR/Hy3gMgfFJZa3IYJjkk2f3XkNg/FJYs9sXwyS/PLv2HALjmr6LZ15KPjyHYZJvnt16D4FxS91/jpfu9sUwyT/X7moAgXFH2abDJeKBYdIaXLuqBQTGFV27zuZEBsOkdbh2UxMIjBuqihzNiUzueVEhSAQEShGAwJQi5SfdnohgmOSHxyU8hcAsQeMllTglMi07gddECLWaggAEZgrMKoVsiQwERoWK4xYKgVmb+1RkIDBr822udhAYc5QMdygWGQjMcHhhcA8BCMwx4iOIDATmGHybqSUExgwV4o6EA5Lh/hHxAlEAEIDAHCsGWGT4B96PxbtabRFoatCjYCCwPgIQmPU5Rg2BgBoCEBg16FEwEFgfAQjM+hyjhkBADYF/ANUfOpfN32LEAAAAAElFTkSuQmCC', '0');
INSERT INTO `xinhu_flow_log` VALUES ('4072', 'hrredund', '1', '2', '不同意', '小乔确认', '126', '2021-06-25 15:36:26', '啊', 'fe80::406c:d051:f915:9c2f', 'Chrome', '小乔', '3', '33', 'red', '1', '2', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4071', 'hrredund', '1', '1', '同意', '上级审批', '29', '2021-06-25 15:35:28', null, 'fe80::406c:d051:f915:9c2f', 'Chrome', '磐石', '5', '33', 'green', '1', '1', null, '0');
INSERT INTO `xinhu_flow_log` VALUES ('4154', 'goodm', '24', '1', null, '提交', '0', '2021-10-15 15:37:29', null, '192.168.1.4', 'iPhone', '大乔', '4', '24', null, '1', '0', null, '0');

-- ----------------------------
-- Table structure for `xinhu_flow_menu`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_menu`;
CREATE TABLE `xinhu_flow_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '显示名称',
  `num` varchar(20) DEFAULT NULL COMMENT '编号',
  `sort` tinyint(4) DEFAULT '0',
  `statusname` varchar(20) DEFAULT NULL,
  `statuscolor` varchar(20) DEFAULT NULL,
  `statusvalue` tinyint(1) DEFAULT '1' COMMENT '状态值',
  `actname` varchar(20) DEFAULT NULL COMMENT '动作名称',
  `setid` smallint(6) DEFAULT '0' COMMENT '对应模块',
  `wherestr` varchar(300) DEFAULT NULL COMMENT '显示条件',
  `explain` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `islog` tinyint(1) DEFAULT '1',
  `issm` tinyint(1) DEFAULT '1',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型',
  `changeaction` varchar(20) DEFAULT NULL COMMENT '触发的方法',
  `fields` varchar(50) DEFAULT NULL,
  `upgcont` varchar(500) DEFAULT NULL COMMENT '更新内容',
  `iszs` tinyint(1) DEFAULT '0' COMMENT '是否在详情页面显示',
  PRIMARY KEY (`id`),
  KEY `setid` (`setid`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COMMENT='单据操作菜单';

-- ----------------------------
-- Records of xinhu_flow_menu
-- ----------------------------
INSERT INTO `xinhu_flow_menu` VALUES ('1', '标为已读', 'yd', '0', '已读', 'green', '1', '标识', '1', null, null, '1', '0', '0', '0', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('2', '全部标识已读', 'allyd', '0', null, null, '1', null, '1', null, null, '1', '0', '0', '0', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('3', '标为已读', 'yd', '0', '已读', 'green', '1', '标识', '3', null, null, '1', '0', '0', '0', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('4', '全部标识已读', 'allyd', '0', null, null, '1', null, '3', null, null, '1', '0', '0', '0', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('40', '创建项目任务', null, '0', null, null, '0', null, '22', 'c3RhdHVzIG5vdCBpbigxLDUp', null, '1', '0', '0', '5', null, null, 'YWRkfHdvcmt8ZGVmX3Byb2plY3RpZD17aWR9', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('6', '取消会议', null, '0', '取消', 'gray', '3', null, '2', 'dHlwZT0wIGFuZCBvcHRpZD17dWlkfSBhbmQgc3RhcnRkdD57bm93fSAgYW5kIHN0YXRlPTA:', '我发起，未开始会议可取消', '1', '1', '1', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('7', '结束会议', null, '0', '结束', 'orange', '2', null, '2', 'YHR5cGVgPTAgYW5kIG9wdGlkPXt1aWR9IGFuZCBzdGFydGR0PHtub3d9IGFuZCBlbmRkdD57bm93fSAgYW5kIHN0YXRlPTE:', '我发起，开会中可提前结束', '1', '1', '1', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('12', '＋添加跟进计划', null, '0', '跟进', null, '1', '跟进记录', '7', 'KHVpZD17dWlkfSBvciB7c2hhdGVpZCx1aWRpbn0p', null, '1', '1', '1', '5', null, null, 'YWRkfGN1c3RwbGFufGRlZl9jdXN0aWQ9e2lkfSZkZWZfY3VzdG5hbWU9e25hbWV9', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('13', '启用', 'ztqh', '0', '启用', 'green', '1', '状态切换', '7', 'dWlkPXt1aWR9IGFuZCBzdGF0dXM9MA::', null, '1', '1', '0', '1', null, null, 'c3RhdHVzPTE:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('14', '停用', 'ztqh', '0', '停用', 'gray', '0', '状态切换', '7', 'dWlkPXt1aWR9IGFuZCBzdGF0dXM9MQ::', null, '1', '1', '1', '1', null, null, 'c3RhdHVzPTA:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('15', '共享给...', 'shate', '5', null, null, '1', null, '7', 'dWlkPXt1aWR9IGFuZCBzdGF0dXM9MQ::', null, '1', '1', '0', '3', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('16', '＋添加跟进', 'genjin', '0', null, null, '1', '跟进', '8', 'dWlkPXt1aWR9', null, '1', '1', '1', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('17', '标为跟进中', 'ztqh', '0', '跟进中', 'blue', '0', '状态切换', '8', 'dWlkPXt1aWR9IGFuZCBzdGF0ZTw!MCBhbmQgaHRpZD0w', null, '1', '1', '0', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('18', '标为已成交', 'ztqh', '0', '已成交', 'green', '1', '状态切换', '8', 'dWlkPXt1aWR9IGFuZCBzdGF0ZTw!MQ::', null, '1', '1', '1', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('19', '标为已丢失', 'ztqh', '0', '已丢失', 'gray', '2', '状态切换', '8', 'dWlkPXt1aWR9IGFuZCBzdGF0ZTw!MiBhbmQgaHRpZD0w', null, '1', '1', '1', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('20', '转移给...', 'zhuan', '0', null, null, '1', null, '8', 'dWlkPXt1aWR9IGFuZCBzdGF0ZT0w', null, '1', '1', '1', '2', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('21', '标为执行中', 'state2', '1', '执行中', 'orange', '3', '进度报告', '22', 'YHN0YXR1c2Agbm90IGluKDEsNSk:', null, '1', '1', '0', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('22', '更新进度', 'upprogress', '0', null, null, '0', null, '22', 'cHJvZ3Jlc3M8PjEwMCBhbmQgYHN0YXR1c2A8PjUgYW5kIHtpc21vYmlsZX09MA::', null, '1', '1', '0', '4', null, 'progress', null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('23', '标为已完成', 'state1', '1', '已完成', 'green', '1', '进度报告', '22', 'c3RhdHVzIG5vdCBpbigxLDUp', null, '1', '1', '0', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('24', '终止项目', 'state3', '1', '终止', 'gray', '5', '进度报告', '22', 'YHN0YXR1c2Agbm90IGluKDEsNSk:', null, '1', '1', '1', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('25', '提前终止', null, '0', '终止', 'gray', '2', '提前终止', '31', 'c3RhdGU9MQ::', null, '1', '1', '1', '4', null, 'tqenddt', null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('26', '创建收款单', 'cjdaishou', '0', null, 'chocolate', '1', null, '35', 'dWlkPXt1aWR9IGFuZCBpc292ZXI9MCBhbmQgdHlwZT0w', null, '1', '0', '0', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('27', '标已收款', 'pay', '0', '已收款', 'green', '0', null, '36', 'dWlkPXt1aWR9IGFuZCB0eXBlPTAgYW5kIGlzcGF5PTA:', null, '1', '1', '1', '4', null, 'paydt', null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('28', '标已付款', 'pay', '0', '已付款', 'green', '0', null, '36', 'dWlkPXt1aWR9IGFuZCB0eXBlPTEgYW5kIGlzcGF5PTA:', null, '1', '1', '1', '4', null, 'paydt', null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('29', '创建付款单', 'cjdaishou', '0', null, 'chocolate', '1', null, '35', 'dWlkPXt1aWR9IGFuZCBpc292ZXI9MCBhbmQgdHlwZT0x', null, '1', '0', '0', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('30', '领用登记', null, '0', '领用', null, '1', null, '41', 'YHN0YXRlYD0w', null, '1', '1', '1', '4', null, 'usename', 'YHN0YXRlYD0x', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('31', '维修登记', null, '0', '维修', 'gray', '1', null, '41', null, null, '1', '1', '1', '1', null, null, 'YHN0YXRlYD0y', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('32', '标☆', null, '0', '标星', 'chocolate', '1', '状态切换', '7', 'dWlkPXt1aWR9IGFuZCBpc3N0YXQ9MA::', null, '1', '1', '0', '1', null, null, 'aXNzdGF0PTE:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('33', '取消标☆', null, '0', '取消标星', 'gray', '0', '状态切换', '7', 'dWlkPXt1aWR9IGFuZCBpc3N0YXQ9MQ::', null, '1', '1', '0', '1', null, null, 'aXNzdGF0PTA:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('34', '取消共享', 'unshate', '6', null, 'gray', '1', null, '7', 'dWlkPXt1aWR9IGFuZCBzdGF0dXM9MSBhbmQgc2hhdGUgaXMgbm90IG51bGw:', null, '1', '1', '0', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('35', '会议签到', 'qdnoup', '0', '签到', 'blue', '6', '签到', '2', 'YHR5cGVgPTAgYW5kIChgc3RhdGVgPTEgb3IgKDYwMD4odW5peF90aW1lc3RhbXAoYHN0YXJ0ZHRgKS11bml4X3RpbWVzdGFtcCgpKT4wIGFuZCBgc3RhdGVgPTApKQ::', '只能手机端上签到, 可提前10分钟签到', '1', '1', '0', '1', null, null, null, '1');
INSERT INTO `xinhu_flow_menu` VALUES ('36', '设置为管理员', null, '0', null, null, '0', null, '10', 'YGlkYD4xICBhbmQge3VpZH09MSBhbmQgYHR5cGVgPTA:', null, '1', '1', '1', '0', null, null, 'YHR5cGVgPTE:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('37', '取消管理员', null, '0', null, null, '0', null, '10', 'YGlkYD4xICBhbmQge3VpZH09MSBhbmQgYHR5cGVgPTE:', null, '1', '1', '1', '0', null, null, 'YHR5cGVgPTA:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('38', '放入客户公海库', 'ghnoup', '7', null, 'deeppink', '1', null, '7', 'KGB1aWRgPjAgb3IgYGlzZ2hgPTAp', null, '1', '1', '0', '1', null, null, null, '1');
INSERT INTO `xinhu_flow_menu` VALUES ('39', '进度+5%', null, '0', null, null, '0', null, '22', 'cHJvZ3Jlc3M8PjEwMCBhbmQgYHN0YXR1c2A8PjU:', null, '1', '1', '0', '1', null, null, 'cHJvZ3Jlc3M9cHJvZ3Jlc3MrNQ::', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('41', '相关项目信息', null, '0', null, null, '0', null, '4', 'cHJvamVjdGlkPjA:', null, '1', '0', '0', '5', null, null, 'eGlhbmd8cHJvamVjdHxtaWQ9e3Byb2plY3RpZH0:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('42', '归还登记', null, '0', '归还', null, '1', null, '41', null, null, '1', '1', '0', '1', null, null, 'YHN0YXRlYD0wLGB1c2VpZGA9JzAnLGB1c2VuYW1lYD1udWxs', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('43', '停用会议', 'tynoup', '0', null, 'gray', '0', null, '2', 'YHR5cGVgPTEgYW5kIGBzdGF0dXNgPTE:', '固定会议停用的', '1', '1', '0', '1', null, null, 'YHN0YXR1c2A9MA::', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('44', '启用会议', 'qynoup', '0', null, 'green', '0', null, '2', 'YHR5cGVgPTEgYW5kIGBzdGF0dXNgPTA:', '固定会议启用', '1', '1', '0', '1', null, null, 'YHN0YXR1c2A9MQ::', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('45', '分配给...', null, '10', null, null, '0', '分配', '7', 'YHVpZGA9MA::', null, '1', '1', '0', '2', null, null, 'YHVpZGA9e2NuYW1laWR9LGBpc2doYD0w', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('54', '创建收款单', null, '0', null, 'chocolate', '0', null, '92', 'YHN0YXR1c2A9MSBhbmQgYGlzcGF5YD0wIGFuZCBgY3VzdHJhY3RpZGA9MA::', '未关联合同需要创建收款单', '1', '0', '0', '5', null, null, 'YWRkfGN1c3RmaW5hfGRlZl9odGlkPS17aWR9JmRlZl90eXBlPTA:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('56', '标识已完成', 'bywc', '0', '已完成', 'green', '1', null, '98', 'YHVpZGA9e3VpZH0gYW5kICBgc3RhdHVzYD0w', null, '1', '1', '0', '1', null, null, 'c3RhdHVzPTEsZmluZHQ9bm93KCk:', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('57', '查看汇总', null, '0', null, 'blue', '0', null, '101', 'YHVpZGA9e3VpZH0:', null, '1', '0', '0', '5', null, null, 'eGlhbmd8d2VuanVhbnxtaWQ9e2lkfSZobHg9dHJ1ZQ::', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('60', '撤回邮件', 'chemail', '0', null, 'red', '0', '撤回', '47', 'YHVpZGA9e3VpZH0:', null, '1', '1', '0', '1', null, null, null, '1');
INSERT INTO `xinhu_flow_menu` VALUES ('61', '编辑文档', 'editfile', '0', null, null, '0', null, '86', null, null, '1', '0', '0', '7', null, null, 'b3BlbnhpZWVkaXRmaWxl', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('62', '查看销售单', null, '0', null, null, '0', null, '102', 'YGN1c3RyYWN0aWRgPjA:', null, '1', '1', '1', '5', null, null, 'eGlhbmd8Y3VzdHhpYW98bWlkPXtjdXN0cmFjdGlkfQ::', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('64', '发邮件给客户', null, '0', null, null, '0', null, '85', 'YHR5cGVgPTA:', null, '1', '0', '0', '7', null, null, 'c2VuZHBpYW9lbWFpbA::', '0');
INSERT INTO `xinhu_flow_menu` VALUES ('67', '客户确认', 'openqren', '0', null, null, '0', null, '35', 'MT0yIGFuZCBgaXNxcmA9MA::', null, '1', '1', '0', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('68', '客户评论', 'openpl', '0', null, null, '0', null, '35', 'MT0yIGFuZCAxPTE:', null, '1', '1', '1', '1', null, null, null, '0');
INSERT INTO `xinhu_flow_menu` VALUES ('69', '标识已完成', 'noupno1', '0', null, 'green', '1', null, '82', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0ZWA9Mg::', null, '1', '1', '0', '1', null, null, 'YHN0YXRlYD0xLGBvcHRkdGA9J3tub3d9Jw::', '0');

-- ----------------------------
-- Table structure for `xinhu_flow_modetpl`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_modetpl`;
CREATE TABLE `xinhu_flow_modetpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setid` smallint(6) DEFAULT '0' COMMENT '模块Id',
  `tplname` varchar(200) DEFAULT NULL COMMENT '名称',
  `tplnum` varchar(20) NOT NULL DEFAULT '0' COMMENT '多模版编号',
  `fieldsluru` varchar(2000) DEFAULT NULL COMMENT '录入字段',
  `fieldsbitian` varchar(2000) DEFAULT NULL COMMENT '必填字段',
  `explain` varchar(200) DEFAULT NULL COMMENT '说明',
  `recename` varchar(200) DEFAULT NULL COMMENT '适用对象',
  `receid` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `sort` int(11) DEFAULT '0' COMMENT '排序号',
  PRIMARY KEY (`id`,`tplnum`),
  KEY `modeid` (`setid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='模块多模版设置表';

-- ----------------------------
-- Records of xinhu_flow_modetpl
-- ----------------------------
INSERT INTO `xinhu_flow_modetpl` VALUES ('3', '3', '月报', 'ybao', 'type,dt,enddt,content,plan,adddt', 'type,dt,content,adddt', null, '全体人员', null, '1', '0');

-- ----------------------------
-- Table structure for `xinhu_flow_remind`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_remind`;
CREATE TABLE `xinhu_flow_remind` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startdt` datetime DEFAULT NULL COMMENT '开始时间',
  `enddt` datetime DEFAULT NULL COMMENT '截止时间',
  `uid` smallint(6) DEFAULT NULL COMMENT '用户Id',
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `modenum` varchar(30) DEFAULT NULL COMMENT '对应模块编号',
  `table` varchar(30) DEFAULT NULL,
  `mid` int(11) DEFAULT '0' COMMENT '主Id',
  `ratecont` varchar(500) DEFAULT NULL COMMENT '频率',
  `explain` varchar(500) DEFAULT NULL COMMENT '提醒内容',
  `rate` varchar(50) DEFAULT NULL COMMENT '提醒频率o仅一次,d天,w周,m月',
  `rateval` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `receid` varchar(500) DEFAULT NULL,
  `recename` varchar(500) DEFAULT NULL COMMENT '提醒给',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table` (`uid`,`table`,`mid`) USING BTREE,
  UNIQUE KEY `uid` (`uid`,`modenum`,`mid`),
  KEY `table_2` (`table`,`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='单据提醒设置表';

-- ----------------------------
-- Records of xinhu_flow_remind
-- ----------------------------
INSERT INTO `xinhu_flow_remind` VALUES ('15', '2021-10-12 18:34:15', null, '1', '2021-10-12 19:10:53', '管理员', 'flowtodo', 'flow_todo', '22', '每天 08:30:00', '人员{uname}的“{httype}管理员”将再{enddt}到期', 'd', '08:30:00', '1', null, null, '1');
INSERT INTO `xinhu_flow_remind` VALUES ('4', '2017-08-23 14:25:17', null, '1', '2017-08-23 20:48:29', '管理员', 'flowtodo', 'flow_todo', '1', '每小时 52:24 每天从14:25:17至23:27:06提醒', '车辆[{carid}]的{otype}将在{enddt}到期。', 'h', '52:24|23:27:06', '0', null, null, '0');
INSERT INTO `xinhu_flow_remind` VALUES ('8', '2017-09-03 20:11:29', null, '1', '2019-08-16 22:55:51', '管理员', 'subscribe', 'subscribe', '1', '每月 02 02:05:00', '订阅管理：每月份人员考勤统计简表', 'm', '02 02:05:00', '1', 'd4,g4', '行政人事部,管理员组', '1');
INSERT INTO `xinhu_flow_remind` VALUES ('10', '2018-12-04 09:23:47', null, '1', '2020-09-01 17:57:41', '管理员', 'flowtodo', 'flow_todo', '11', '每天 08:00:00', '物品库存预警小于10提醒。', 'd', '08:00:00', '0', null, null, '1');
INSERT INTO `xinhu_flow_remind` VALUES ('11', '2019-12-02 15:43:47', null, '1', '2021-04-13 19:37:54', '管理员', 'flowtodo', 'flow_todo', '16', '每天 19:40:00', '客户“{custname}”待收金额：{money}', 'd', '19:40:00', '1', null, null, '1');
INSERT INTO `xinhu_flow_remind` VALUES ('12', '2020-10-01 12:14:50', null, '1', '2020-10-13 15:42:49', '管理员', 'flowtodo', 'flow_todo', '20', '每天 15:45:45', '{mingc}将在{etime}到期', 'd', '15:45:45', '1', null, null, '1');

-- ----------------------------
-- Table structure for `xinhu_flow_set`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_set`;
CREATE TABLE `xinhu_flow_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '模块名称',
  `num` varchar(30) NOT NULL DEFAULT '' COMMENT '模块编号',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序',
  `table` varchar(50) DEFAULT NULL COMMENT '对应的表',
  `where` varchar(500) DEFAULT NULL COMMENT '相关条件',
  `summary` varchar(500) DEFAULT NULL COMMENT '摘要',
  `summarx` varchar(500) DEFAULT NULL COMMENT '应用摘要',
  `type` varchar(20) DEFAULT NULL COMMENT '分类',
  `pctx` tinyint(1) DEFAULT '0' COMMENT 'pc端提醒',
  `mctx` tinyint(1) DEFAULT '0' COMMENT 'app提醒',
  `wxtx` tinyint(1) DEFAULT '0' COMMENT '微信提醒',
  `emtx` tinyint(1) DEFAULT '0' COMMENT '是否邮件提醒',
  `sericnum` varchar(50) DEFAULT NULL COMMENT '编号规则',
  `isflow` tinyint(1) DEFAULT '1' COMMENT '是否有流程',
  `receid` varchar(255) DEFAULT NULL,
  `recename` varchar(255) DEFAULT NULL COMMENT '针对对象',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `status` tinyint(1) DEFAULT '1',
  `islu` tinyint(1) DEFAULT '1' COMMENT '是否可录入',
  `tables` varchar(500) DEFAULT NULL COMMENT '多行子表',
  `names` varchar(500) DEFAULT NULL COMMENT '多行子表名称',
  `statusstr` varchar(500) DEFAULT NULL COMMENT '状态值设置',
  `isgbjl` tinyint(1) DEFAULT '0' COMMENT '是否关闭操作记录',
  `isgbcy` tinyint(1) DEFAULT '0' COMMENT '是否不显示查阅记录',
  `isscl` tinyint(1) DEFAULT '0' COMMENT '是否生成列表页',
  `isup` tinyint(1) DEFAULT '1' COMMENT '更新时是否同步',
  `ddtx` tinyint(1) DEFAULT '0' COMMENT '是否钉钉提醒',
  `isbxs` tinyint(1) DEFAULT '0' COMMENT '录入页面是否不显示流程图',
  `lbztxs` tinyint(1) DEFAULT '0' COMMENT '列表页状态搜索显示0默认,1必须显示,2显示',
  `isflowlx` tinyint(1) DEFAULT '0' COMMENT '从新提交时0默认，1从新走流程',
  `iscs` tinyint(1) DEFAULT '0' COMMENT '是否自定义抄送',
  `zfeitime` int(11) DEFAULT '0' COMMENT '超过分钟自动作废',
  `ispl` tinyint(1) DEFAULT '1' COMMENT '是否开启评论',
  `istxset` tinyint(1) DEFAULT '1' COMMENT '是否开启单据提醒设置',
  `ishz` tinyint(1) DEFAULT '0' COMMENT '回执确认0不开启,1必须选择,2可选',
  `istpl` tinyint(1) DEFAULT '0' COMMENT '是否支持多模版',
  `sortdir` varchar(50) DEFAULT NULL COMMENT '默认排序',
  `isys` tinyint(1) DEFAULT '0' COMMENT '提请预审',
  PRIMARY KEY (`id`,`num`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COMMENT='流程模块';

-- ----------------------------
-- Records of xinhu_flow_set
-- ----------------------------
INSERT INTO `xinhu_flow_set` VALUES ('1', '通知公告', 'gong', '1', 'infor', null, '[{typename}]{title}', 'title:[{typename}]{title}\noptdt:{optdt}', '行政', '1', '0', '0', '0', 'XA-Ymd-', '0', 'all', '全体人员', '2021-10-09 14:50:50', '1', '1', 'infors', '投票选项', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '1', '1', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('2', '会议', 'meet', '2', 'meet', '`type` in(0,1)', '[{hyname}]{title},{startdt}→{enddt}', 'title:{title}\ncont:会议室：{hyname}[br]发起人：{optname}[br]参会人：{joinname}\noptdt:{startdt}→{enddt}', '行政', '1', '0', '0', '0', 'XB-Ymd-', '0', 'all', '全体人员', '2019-06-03 13:54:09', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('3', '工作日报', 'daily', '21', 'daily', null, '[{optname}]{content}', 'title:[{optname}]{dt}的{type}\noptdt:{optdt}\ncont:{content}', '基础', '1', '0', '0', '0', 'XC-Ymd-', '0', 'all', '全体人员', '2021-05-08 09:23:14', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '1', '0', '1', '1', '0', '1', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('4', '任务', 'work', '23', 'work', null, '[{type}]{title},分配给:{dist}', 'title:{title}\noptdt:{optdt}\ncont:类型：{type}[br]等级：{grade}[br]分配给：{dist}[br]创建者：{optname}[br]截止时间：{enddt}', '基础', '1', '0', '0', '0', 'XW-Ymd-', '1', 'all', '全体人员', '2021-04-01 10:18:34', '1', '1', null, null, '待?分配,已完成,无法完成,待?执行,?执行中,已作废,待?验证|blue', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('5', '请假条', 'leave', '30', 'kqinfo', 'kind=[F]请假[F]', '[{qjkind}]{stime}→{etime}共{totals}小时,{explain}', 'title:[{qjkind}]共{totals}小时\ncont:时间：{stime}→{etime}[br]说明：{explain}\noptdt:{optdt}', '考勤', '1', '0', '0', '0', 'KA-Ymd-', '1', 'all', '全体人员', '2019-09-23 17:02:28', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '1', '0', '0', '1', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('6', '加班单', 'jiaban', '31', 'kqinfo', 'kind=[F]加班[F]', '{stime}→{etime}共加班{totals}小时,{explain}', 'title:加班共{totals}小时\ncont:时间：{stime}→{etime}[br]说明：{explain}\noptdt:{optdt}', '考勤', '1', '0', '0', '0', 'KB-Ymd-', '1', 'all', '全体人员', '2020-06-08 15:15:30', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('7', '客户管理', 'customer', '50', 'customer', null, '{name}', 'title:{name}\noptdt:{optdt}\ncont:来源：{laiyuan}[br]操作人：{optname}[br]共享给：{shate}', '客户', '1', '0', '0', '0', 'CA-Ymd-', '0', 'all', '全体人员', '2019-06-07 19:13:47', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('8', '销售机会', 'custsale', '52', 'custsale', null, null, 'title:{custname}\noptdt:{optdt}\ncont:来源：{laiyuan}[br]销售人：{optname}[br]申请日期：{applydt}[br]预计金额：{money}', '客户', '1', '0', '0', '0', 'CB-Ymd-', '0', 'all', '全体人员', '2019-06-07 19:13:49', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('9', '物品', 'goods', '80', 'goods', null, '名称：{name}，编号：{num}', 'title:{name}\ncont:编号：{num}[br]规格：{guige}[br]型号：{xinghao}[br]库存：{stock}{unit}', '物品', '1', '0', '0', '0', 'CC-Ymd-', '0', 'all', '全体人员', '2020-05-30 18:50:17', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('11', '费用报销', 'finfybx', '90', 'fininfom', 'type=0', '报销：{money},收款人：{fullname}', null, '财务', '1', '0', '0', '0', 'FA-Ymd-', '1', 'all', '全体人员', '2019-06-07 14:16:05', '1', '1', 'fininfos', '报销明细', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('10', '用户', 'user', '901', 'admin', null, '{deptallname}，{name}', 'title:{name}\ncont:部门：{deptallname}[br]职位：{ranking}[br]电话：{tel}[br]手机：{mobile}[br]邮箱：{email}', '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2019-06-17 09:34:13', '1', '0', null, null, null, '0', '0', '1', '1', '0', '0', '2', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('12', '日程', 'schedule', '24', 'schedule', null, '{title}', 'title:{title}\noptdt:{optdt}\ncont:时间：{startdt}[br]截止：{enddt}[br]说明：{explain}[br]重复：{rate}{rateval}[br]提醒：{txsj}', '基础', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-03 17:12:10', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('13', '部门', 'dept', '902', 'dept', null, '{name}', null, '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2019-06-03 17:25:41', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('14', '系统组', 'group', '903', 'group', null, null, null, '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2016-08-13 17:47:58', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('15', '计划任务', 'task', '904', 'task', null, null, null, '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2016-08-13 17:23:01', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('16', '会话管理', 'huihua', '905', 'im_group', 'type<>2', null, null, '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2016-08-13 17:26:59', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('17', '应用管理', 'yingyong', '906', 'im_group', 'type=2', null, null, '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2016-08-13 17:26:25', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('18', '应用菜单', 'yymenu', '907', 'im_menu', null, null, null, '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2016-08-13 17:27:41', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('19', '发文单', 'officia', '40', 'official', 'type=0', '{num}[{class}]{title}', 'title:{title}\noptdt:{optdt}\ncont:编号：{num}[br]接收单位：{unitname}', '公文', '1', '0', '0', '0', 'GA-Ymd-', '1', 'all', '全体人员', '2020-04-25 20:23:09', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('20', '收文单', 'officib', '41', 'official', 'type=1', '{num}{title}，来文单位：{unitsame}', 'title:{title}\noptdt:{optdt}\ncont:编号：{num}[br]来文单位：{unitname}[br]来文日期：{laidt}', '公文', '1', '0', '0', '0', 'GB-Ymd-', '1', 'all', '全体人员', '2021-01-07 17:50:25', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('21', '打卡记录', 'kqdkjl', '32', 'kqdkjl', null, null, 'title:{dkdt}(星期{week})\ncont:姓名：{name}[br]打卡类型：{type}[br]打卡IP：{ip}[br]说明：{explain}', '考勤', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-06 18:00:18', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('22', '项目', 'project', '22', 'project', null, '项目 “{title}”,执行人：{runuser}', 'title:{title}\noptdt:{startdt}\ncont:类型：{type}[br]创建者：{optname}[br]预计结束：{enddt}[br]进度：{progress}%', '基础', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-06 16:33:47', '1', '1', null, null, '待执行|blue,已完成|green,结束|#888888,执行中|#ff6600', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('23', '外出出差', 'waichu', '33', 'kqout', null, '[{atype}]{address}{outtime}', 'title:{optname}[{atype}]{reason}\noptdt:{optdt}\ncont:外出时间：{outtime}[br]预计回岗：{intime}[br]地点：{address}', '考勤', '1', '0', '0', '0', 'KD-Ymd-', '1', 'all', '全体人员', '2019-06-06 18:00:21', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('24', '物品领用', 'goodly', '82', 'goodm', 'type=0', '领用物品：{wupinlist}', 'cont:日期：{applydt}[br]领用物品：{wupinlist}', '物品', '1', '0', '0', '0', 'WA-Ymd-', '1', 'all', '全体人员', '2019-06-07 09:38:17', '1', '1', 'goodn', '领用物品', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('25', '出差报销', 'finccbx', '91', 'fininfom', 'type=1', '报销金额:{money},目的:{purpose},成果:{purresult}', null, '财务', '1', '0', '0', '0', 'FB-Ymd-', '1', 'all', '全体人员', '2017-05-06 21:29:35', '1', '1', 'fininfos', '报销明细', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('26', '借款单', 'finjkd', '92', 'fininfom', 'type=2', '借款金额:{money},用途:{purpose}', null, '财务', '1', '0', '0', '0', 'FC-Ymd-', '1', 'all', '全体人员', '2017-05-06 21:45:31', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('27', '还款单', 'finhkd', '93', 'fininfom', 'type=3', '还款金额：{money}，还款方式:{paytype}，还款到：{fullname}', null, '财务', '1', '0', '0', '0', 'FD-Ymd-', '1', 'all', '全体人员', '2017-05-06 21:45:35', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('28', '物品采购', 'caigou', '83', 'goodm', 'type=1', '从[{custname}]采购金额:{money}元，采购物品：{wupinlist}', 'cont:供应商：{custname}[br]采购物品：{wupinlist}[br]金额：{money}元', '物品', '1', '0', '0', '0', 'WB-Ymd-', '1', 'all', '全体人员', '2019-04-29 11:16:52', '1', '1', 'goodn', '采购物品', null, '0', '0', '1', '1', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('29', '人员档案', 'userinfo', '60', 'userinfo', null, null, null, '人事', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-03 17:07:09', '1', '0', 'userinfos,userinfos', '工作经历,教育经历', null, '0', '0', '0', '1', '0', '0', '1', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('30', '考勤信息', 'leavehr', '34', 'kqinfo', null, null, null, '考勤', '1', '0', '0', '0', null, '0', 'd4', '行政人事部', '2016-09-13 13:46:55', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('31', '员工合同', 'userract', '61', 'userract', null, null, null, '人事', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-06 17:57:19', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('32', '转正申请', 'hrpositive', '62', 'hrpositive', null, '申请[{positivedt}]转正', null, '人事', '1', '0', '0', '0', 'HA-Ymd-', '1', 'all', '全体人员', '2019-06-06 17:03:11', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('33', '离职申请', 'hrredund', '63', 'hrredund', null, '[{redundtype}],在{quitdt}离职', null, '人事', '1', '0', '0', '0', 'HB-Ymd-', '1', 'all', '全体人员', '2021-06-24 16:13:03', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '1');
INSERT INTO `xinhu_flow_set` VALUES ('34', '薪资', 'hrsalary', '65', 'hrsalary', null, '{uname}[{month}]月份的薪资', 'title:{month}薪资', '人事', '1', '0', '0', '0', 'HC-Ymd-', '1', 'all', '全体人员', '2019-06-06 17:03:28', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('35', '客户合同', 'custract', '53', 'custract', null, '编号[{num}],客户:{custname},{enddt}到期', 'title:{custname}\ncont:合同编号：{num}[br]日期：{startdt}→{enddt}[br]状态：{moneys}', '客户', '0', '0', '0', '0', 'CC-Ymd-', '0', 'all', '全体人员', '2019-06-07 19:13:52', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('36', '收付款单', 'custfina', '54', 'custfina', null, '{type}：日期[{dt}],{custname},{ispay}', 'title:{custname}\noptdt:{optdt}\ncont:金额：{money}[br]对应合同：{htnum}', '客户', '0', '0', '0', '0', 'CD-Ymd-', '0', 'all', '全体人员', '2019-06-07 19:13:55', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('37', '调薪申请', 'hrtrsalary', '66', 'hrtrsalary', null, '{optname}申请调薪', null, '人事', '1', '0', '0', '0', 'HD-Ymd-', '1', 'all', '全体人员', '2019-06-06 17:03:31', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('38', '人事调动', 'hrtransfer', '67', 'hrtransfer', null, '[{tranname}]【{trantype}】:{olddeptname}→{newdeptname},{oldranking}→{newranking}', null, '人事', '1', '0', '0', '0', 'HE-Ymd-', '1', 'all', '全体人员', '2019-06-06 17:03:36', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('39', '奖惩处罚', 'reward', '68', 'reward', null, '对【{object}】的{atype},奖惩结果:{result},金额:{money}', null, '人事', '1', '0', '0', '0', 'HF-Ymd-', '1', 'all', '全体人员', '2019-06-06 17:03:40', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('40', '知识', 'knowledge', '75', 'knowledge', null, '{title}', 'title:{title}\noptdt:{optdt}', '资源', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-05 18:11:27', '1', '1', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('41', '固定资产', 'assetm', '7', 'assetm', null, null, 'title:{title}\ncont:所在位置：{address}[br]编号：{num}[br]状态：{state}[br]使用者：{usename}', '行政', '0', '0', '0', '0', 'CF-Ymd-', '0', 'all', '全体人员', '2020-05-30 18:56:52', '1', '0', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('42', '车辆管理', 'carm', '121', 'carm', null, null, null, '车辆', '0', '0', '0', '0', 'CH-Ymd-', '0', 'all', '全体人员', '2019-06-07 13:32:06', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('43', '题库', 'knowtiku', '76', 'knowtiku', null, null, 'title:[{typename}]{title}\ncont:A：{ana}[br]B：{anb}[br]C：{anc}[br]D：{and}[br]E：{ane}', '资源', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-22 20:21:52', '1', '1', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('44', '车辆预定', 'carmrese', '123', 'carmrese', null, '{usename}预定车辆【{carnum}】在{startdt}前往{address}', null, '车辆', '1', '0', '0', '0', 'CJ-Ymd-', '1', 'all', '全体人员', '2019-06-07 13:32:15', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('45', '图书管理', 'book', '8', 'book', null, '{title}', 'title:{title}', '行政', '0', '0', '0', '0', 'CK-Ymd-', '0', 'all', '全体人员', '2021-06-29 19:29:25', '1', '0', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('46', '图书借阅', 'bookborrow', '9', 'bookborrow', null, '{optname}借阅[{bookname}]', null, '行政', '1', '0', '0', '0', 'CL-Ymd-', '1', 'all', '全体人员', '2019-06-03 13:55:41', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('47', '邮件', 'emailm', '25', 'emailm', null, '{title},发件人:{sendname}', 'title:{title}\noptdt:{senddt}\ncont:发件人：{sendname}[br]收件人：{recename}', '基础', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-03 17:12:14', '1', '1', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('48', '印章证照', 'seal', '10', 'seal', null, null, null, '行政', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-07 13:43:19', '1', '1', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('49', '印章证照使用', 'sealapl', '11', 'sealapl', null, '{optname}申请使用[{sealname}]', null, '行政', '1', '0', '0', '0', 'CM-Ymd-', '1', 'all', '全体人员', '2019-06-07 13:43:21', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('50', '待办处理', 'daiban', '908', 'flow_bill', null, null, null, '系统', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2016-12-15 17:48:55', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('51', '打卡异常', 'kqerr', '35', 'kqerr', null, '{optname}[{errtype}]申请', null, '考勤', '1', '0', '0', '0', 'KE-Ymd-', '1', 'all', '全体人员', '2019-06-06 18:00:26', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('52', '单据作废申请', 'tovoid', '27', 'tovoid', null, '{optname}申请单号:{tonum}作废,模块:{modename}', null, '基础', '1', '0', '0', '0', 'ZF-Ymd-', '1', 'all', '全体人员', '2019-07-04 13:37:44', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('53', '个人通讯录', 'vcard', '26', 'vcard', null, null, 'title:{name}\ncont:电话：{tel}[br]手机：{mobile}[br]邮箱：{email}[br]所在组：{gname}', '基础', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-03 17:12:17', '1', '1', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('54', '会议纪要', 'meetjy', '3', 'meet', 'type=2', null, null, '行政', '1', '0', '0', '0', 'XC-Ymd-', '0', 'all', '全体人员', '2019-06-03 13:54:23', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('55', '定位记录', 'kqdw', '36', 'location', null, null, 'title:{name}\noptdt:{optdt}\ncont:星期：{week}[br]地址：{label}[br]说明：{explain}', '考勤', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-06 18:00:29', '1', '0', null, null, null, '1', '0', '0', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('56', '考试培训', 'knowtraim', '77', 'knowtraim', null, '{title},时间{startdt}→{enddt}', 'title:{title}\noptdt:{start}', '资源', '1', '0', '0', '0', 'ZA-Ymd-', '0', 'all', '全体人员', '2019-06-05 18:11:42', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('58', '车辆信息登记', 'carms', '122', 'carms', null, null, null, '车辆', '1', '0', '0', '0', 'CN-Ymd', '0', 'all', '全体人员', '2019-06-07 13:32:09', '1', '1', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('59', '车辆维修', 'carmwx', '124', 'carmang', 'type=0', null, null, '车辆', '1', '0', '0', '0', 'CX-Ymd-', '1', 'all', '全体人员', '2019-06-07 13:32:18', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('60', '车辆保养', 'carmby', '125', 'carmang', 'type=1', '车辆保养', null, '车辆', '1', '0', '0', '0', 'CY-Ymd-', '1', 'all', '全体人员', '2019-06-07 13:32:21', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('61', '单据提醒设置', 'remind', '28', 'flow_remind', null, null, null, '基础', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-03 17:14:33', '1', '0', null, null, null, '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('62', '维修报修', 'repair', '12', 'repair', null, '{assetm}:{reason}', null, '行政', '1', '0', '0', '0', 'WX-Ymd-', '1', 'all', '全体人员', '2019-06-03 17:11:49', '1', '1', null, null, '待?处理,已完成', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('63', '公司单位', 'company', '900', 'company', null, '{name}', null, '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2019-06-17 09:28:55', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('64', '付款申请', 'finpay', '94', 'fininfom', 'type=4', '申请付款{money}元', null, '财务', '1', '0', '0', '0', 'FE-Ymd-', '1', 'all', '全体人员', '2017-08-07 20:46:42', '1', '1', null, null, '待?处理,已付款', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('65', '开票申请', 'finkai', '95', 'fininfom', 'type=5', '[{fullname}]要开票{money}元', null, '财务', '1', '0', '0', '0', 'FF-Ymd-', '1', 'all', '全体人员', '2018-08-31 16:27:44', '1', '1', null, null, '待?处理,已开票,不同意', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('66', '单据通知设置', 'flowtodo', '909', 'flow_todo', null, '{summary}', null, '系统', '0', '0', '0', '0', null, '0', 'u1', '管理员', '2017-08-23 13:51:23', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('67', '订阅管理', 'subscribe', '910', 'subscribe', null, '{cont}', null, '系统', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-07 19:22:20', '1', '0', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('68', '订阅报表', 'subscribeinfo', '911', 'subscribeinfo', null, '{title}', 'title:{title}\noptdt:{optdt}\ncont:{cont}', '系统', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-07 19:22:09', '1', '0', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('69', '公文查阅', 'officic', '42', 'official', 'status=1', '{title}', 'title:{title}\noptdt:{optdt}\ncont:编号：{num}[br]来文单位：{unitsame}', '公文', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-07-12 14:52:36', '1', '0', null, null, null, '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('70', '考勤机设备', 'kqjsn', '37', 'kqjsn', null, null, null, '考勤', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-06 17:48:03', '1', '1', null, null, null, '0', '0', '0', '1', '0', '0', '2', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('71', '考核评分', 'hrcheck', '72', 'hrcheck', null, '{startdt}“{title}”考核', 'title:{title}\noptdt:{startdt}', '人事', '1', '0', '0', '0', 'HG-Ymd-', '0', 'all', '全体人员', '2019-06-06 17:03:53', '1', '0', 'hrchecks,hrcheckn', '考核内容,评分记录', null, '1', '1', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('72', '演示测试', 'demo', '930', 'demo', 'mid=0', null, null, '系统', '1', '0', '0', '0', 'YS-Ymd-', '1', 'all', '全体人员', '2020-07-29 12:39:45', '1', '0', 'demo,demo', '多行子表1,多行子表2', null, '0', '0', '1', '1', '0', '0', '0', '1', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('73', '仓库管理', 'godepot', '81', 'godepot', null, null, null, '物品', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-07 09:38:14', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('74', '文档分区', 'worc', '130', 'worc', null, null, null, '文档', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-05 13:28:30', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('75', '文档', 'word', '131', 'word', null, null, null, '文档', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-05 13:28:38', '1', '0', null, null, null, '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('76', '调拨单', 'diaobo', '84', 'goodm', 'type=3', '从仓库“{custname}”调拨物品：{wupinlist}', 'cont:日期：{applydt}[br]调拨仓库：{custname}[br]调拨物品：{wupinlist}', '物品', '1', '0', '0', '0', 'WC-Ymd-', '1', 'all', '全体人员', '2019-04-29 11:36:10', '1', '1', 'goodn', '调拨物品', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('77', '客户申请使用', 'custappy', '55', 'custappy', null, '{optname}申请客户[{custname}]使用', null, '客户', '1', '0', '0', '0', 'CE-Ymd-', '1', 'all', '全体人员', '2019-06-07 19:13:59', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', null, '0', '0', '1440', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('78', '人员需求', 'hrdemand', '69', 'hrdemint', 'type=0', '{bumen}，岗位“{zhiwei}”需{renshu}人', null, '人事', '1', '0', '0', '0', 'HH-Ymd-', '1', 'all', '全体人员', '2019-06-06 17:03:44', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('79', '面试安排', 'hrmanshi', '70', 'hrdemint', 'type=1', '{name}在{msdt}面试{zhiwei}，面试人{msuser}', null, '人事', '1', '0', '0', '0', 'HI-Ymd', '1', 'all', '全体人员', '2019-06-06 17:03:47', '1', '1', null, null, '待?处理,已完成,不适合', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '1', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('80', '回执确认', 'receipt', '912', 'receipt', null, null, 'cont:模块：{modename}[br]发送人：{optname}[br]内容：{explain}', '系统', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-07 19:22:45', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('83', '薪资模版', 'hrsalarm', '64', 'hrsalarm', null, null, null, '人事', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-06 17:03:23', '1', '0', 'hrsalars', '字段内容项目', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('81', '考核项目', 'hrkaohem', '71', 'hrkaohem', null, null, null, '人事', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-06 17:03:50', '1', '0', 'hrkaohes,hrkaohen', '考核项目内容,评分人', null, '0', '0', '0', '1', '0', '0', '2', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('84', '社保公积金', 'hrshebao', '63', 'hrshebao', null, null, null, '人事', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-06 17:03:19', '1', '0', null, null, null, '0', '0', '1', '1', '0', '0', '2', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('85', '发票管理', 'finpiao', '99', 'finpiao', null, null, null, '财务', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-07 14:16:10', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('86', '文档协作', 'wordxie', '132', 'wordxie', null, '{name}', 'title:{name}.{wtype}\ncont:创建人：{optname}[br]协作人：{xiename}[br]可查看人：{recename}\noptdt:{optdt}', '文档', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2019-06-05 13:28:41', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('87', '文件传送', 'wordeil', '133', 'wordeil', null, '{title}，接收人：{qianname}', null, '文档', '1', '0', '0', '0', 'FI-Ymd-', '1', 'all', '全体人员', '2019-06-05 13:28:44', '1', '1', null, null, '待?接收,已接收,拒收', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('88', '新闻资讯', 'news', '13', 'news', null, '[{typename}]{title}', 'title:[{typename}]{title}\noptdt:{optdt}', '行政', '1', '0', '0', '0', 'XA-Ymd-', '0', 'all', '全体人员', '2019-06-03 17:11:53', '1', '1', null, null, '停用,启用', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('92', '销售', 'custxiao', '56', 'goodm', 'type=2', '从[{custname}]销售金额:{money}元，销售物品：{wupinlist}', 'cont:客户：{custname}[br]销售物品：{wupinlist}[br]销售金额：{money}元', '客户', '1', '0', '0', '0', 'CF-Ymd-', '1', 'all', '全体人员', '2019-06-07 19:14:02', '1', '1', 'goodn', '销售物品', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('98', '跟进计划', 'custplan', '51', 'custplan', null, '{custname}，跟进方式：{gentype}', 'title:{custname}\ncont:跟进方式：{gentype}[br]跟进人：{optname}[br]说明：{explain}[br]计划时间：{plandt}[br]完成时间：{findt}', '客户', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2020-01-16 19:08:01', '1', '1', null, null, '计划|blue,已完成|green', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('99', '日程待办', 'scheduld', '24', 'scheduld', null, '{title}', 'title:{title}\noptdt:{startdt}\ncont:发起人：{optname}[br]说明：{explain}', '基础', '1', '0', '0', '0', 'XH-Ymd-', '1', 'all', '全体人员', '2020-01-08 19:33:57', '1', '1', null, null, '待?处理,已完成', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('100', '物品归还', 'goodgh', '85', 'goodm', 'type=4', '归还物品：{wupinlist}', 'cont:日期：{applydt}[br]归还物品：{wupinlist}', '物品', '1', '0', '0', '0', 'WD-Ymd-', '1', 'all', '全体人员', '2020-09-08 15:48:33', '1', '1', 'goodn', '归还物品', '待?确认,已归还', '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('101', '调查问卷', 'wenjuan', '14', 'wenjuan', null, '{title}', 'title:{title}\noptdt:{startdt}→{enddt}', '行政', '1', '0', '0', '0', null, '0', 'all', '全体人员', '2020-05-27 19:43:42', '1', '1', 'wenjuat', '问卷项', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('102', '退货单', 'tuihuo', '86', 'goodm', 'type=5', '退货物品：{wupinlist}', 'cont:日期：{applydt}[br]退货物品：{wupinlist}', '物品', '1', '0', '0', '0', 'WE-Ymd-', '1', 'all', '全体人员', '2020-09-08 15:48:50', '1', '1', 'goodn', '退货物品', null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('108', '人员证件', 'userzheng', '62', 'userzheng', null, null, null, '人事', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2020-10-13 15:38:01', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'id asc', '0');
INSERT INTO `xinhu_flow_set` VALUES ('116', '注册审核', 'regcheck', '913', 'regcheck', null, '注册人：“{name}”，注册手机：{mobile}', 'title:{name}注册\ncont:手机号：{mobile}', '系统', '1', '0', '0', '0', 'REG-Ymd-', '1', 'all', '全体人员', '2021-04-29 10:52:04', '1', '0', null, null, null, '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '0');
INSERT INTO `xinhu_flow_set` VALUES ('82', '便笺', 'bianjian', '29', 'bianjian', null, '{content}', 'optdt:{suodt}\ncont:{content}', '基础', '0', '0', '0', '0', null, '0', 'all', '全体人员', '2021-08-31 18:45:45', '1', '1', null, null, null, '0', '0', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', null, '0');

-- ----------------------------
-- Table structure for `xinhu_flow_todo`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_todo`;
CREATE TABLE `xinhu_flow_todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '名称标题',
  `num` varchar(30) DEFAULT NULL COMMENT '编号',
  `setid` smallint(6) DEFAULT '0' COMMENT '对应模块',
  `explain` varchar(100) DEFAULT NULL COMMENT '说明',
  `whereid` smallint(6) DEFAULT '0' COMMENT '对应条件id',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `receid` varchar(500) DEFAULT NULL,
  `recename` varchar(500) DEFAULT NULL COMMENT '通知给',
  `changefields` varchar(200) DEFAULT NULL COMMENT '改变字段',
  `changecourse` varchar(30) DEFAULT NULL COMMENT '对应步做id',
  `boturn` tinyint(1) DEFAULT '0' COMMENT '提交时',
  `boedit` tinyint(1) DEFAULT '0' COMMENT '编辑时',
  `bochang` tinyint(1) DEFAULT '0' COMMENT '字段改变时',
  `bodel` tinyint(1) DEFAULT '0' COMMENT '删除时',
  `bozuofei` tinyint(1) DEFAULT '0' COMMENT '作废时',
  `botong` tinyint(1) DEFAULT '0' COMMENT '步骤处理通过时',
  `bobutong` tinyint(1) DEFAULT '0' COMMENT '步骤处理不通过时',
  `bofinish` tinyint(1) DEFAULT '0' COMMENT '处理完成时',
  `bozhui` tinyint(1) DEFAULT '0' COMMENT '追加说明时',
  `bozhuan` tinyint(1) DEFAULT '0' COMMENT '流程转办时',
  `toturn` tinyint(1) DEFAULT '0' COMMENT '是否通知给提交人',
  `tocourse` tinyint(1) DEFAULT '0' COMMENT '是否通知给流程所有参与人',
  `todofields` varchar(500) DEFAULT NULL COMMENT '通知给主表上字段',
  `summary` varchar(500) DEFAULT NULL COMMENT '通知内容摘要',
  `botask` tinyint(1) DEFAULT '0' COMMENT '计划任务',
  `boping` tinyint(1) DEFAULT '0' COMMENT '评论时',
  `bohuiz` tinyint(1) DEFAULT '0' COMMENT '回执时',
  `tosuper` tinyint(1) DEFAULT '0' COMMENT '直属上级',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='单据通知设置表';

-- ----------------------------
-- Records of xinhu_flow_todo
-- ----------------------------
INSERT INTO `xinhu_flow_todo` VALUES ('1', '车辆信息到期提醒', null, '58', '这只是个事例', '96', '0', 'u1', '管理员', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', null, '车辆[{carid}]的{otype}将在{enddt}到期。', '1', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('4', '通知仓库管理员出库操作', null, '24', null, '0', '1', 'u1', '管理员', null, null, '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', null, '有人申请物品领用，请及时处理出库操作。', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('7', '新增项目提醒', null, '22', null, '0', '1', null, null, null, null, '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'runuserid,fuzeid', '{optname}新增项目“{title}”，负责人{fuze}', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('6', '项目信息修改提醒', null, '22', null, '0', '1', null, null, null, null, '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'runuserid,fuzeid', '项目“{title}”被{optname}修改了', '0', '1', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('9', '考核评分提醒', 'pftodo', '71', null, '0', '1', null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'pfrenids', '你有“{applyname}”的考核评分需要你评分', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('11', '物品库存预警', null, '9', null, '225', '1', 'u1', '管理员', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '物品“{num}.{name}”库存为{stock}已不足10，请知晓。', '1', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('12', null, null, '86', null, '0', '1', null, null, null, null, '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'receid', '{optname}添加协作文档“{name}.{wtype}”需要你查看', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('13', null, null, '86', null, '0', '1', null, null, null, null, '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'xienameid', '{optname}修改协作文档“{name}.{wtype}”的信息', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('14', null, null, '86', null, '0', '1', null, null, null, null, '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'xienameid', '{optname}添加协作文档“{name}.{wtype}”需要你协作完成', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('16', '待收款提醒', null, '36', null, '113', '0', null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'uid', '客户“{custname}”待收金额：{money}', '1', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('17', '{title}', null, '99', null, '0', '1', null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', 'receid', '日程“{title}”已完成', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('18', null, null, '101', null, '0', '1', null, null, null, null, '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'receid', '{optname}发布“{title}”调查问卷，有效日期{startdt}→{enddt}。', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('19', null, 'wmsubmit', '101', null, '0', '1', null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', null, '{now_adminname}提交“{title}”的调查问卷。', '0', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('20', '证件到期提醒', null, '108', null, '297', '1', null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'uid,optid', '{uname}的“{mingc}”将在“{edt}”到期', '1', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('21', '事项逾期提醒', null, '115', null, '319', '1', null, null, null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'runrenid', '未完成的“{title}”事项即将逾期。', '1', '0', '0', '0');
INSERT INTO `xinhu_flow_todo` VALUES ('22', '员工合同到期提醒', null, '31', null, '144', '0', 'u4', '大乔', null, null, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', null, '人员{uname}的“{httype}{name}”将再{enddt}到期', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_flow_todos`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_todos`;
CREATE TABLE `xinhu_flow_todos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modenum` varchar(30) DEFAULT '0' COMMENT '对应模块编号',
  `modename` varchar(30) DEFAULT NULL COMMENT '模块名称',
  `table` varchar(30) DEFAULT NULL COMMENT '对应主表',
  `mid` int(11) DEFAULT '0' COMMENT '主表Id',
  `uid` int(11) DEFAULT '0' COMMENT '对应人员',
  `adddt` datetime DEFAULT NULL COMMENT '添加时间',
  `readdt` datetime DEFAULT NULL COMMENT '已读时间',
  `isread` tinyint(1) DEFAULT '0' COMMENT '是否已读',
  PRIMARY KEY (`id`),
  KEY `table` (`table`,`mid`),
  KEY `modenum` (`modenum`,`mid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单据通知给对应人员';

-- ----------------------------
-- Records of xinhu_flow_todos
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_flow_where`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_flow_where`;
CREATE TABLE `xinhu_flow_where` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `setid` smallint(6) DEFAULT '0' COMMENT '对应模块',
  `num` varchar(30) DEFAULT NULL COMMENT '编号',
  `pnum` varchar(30) DEFAULT NULL COMMENT '编号分组',
  `name` varchar(50) DEFAULT NULL COMMENT '条件名称',
  `wheresstr` varchar(500) DEFAULT NULL COMMENT '对应表条件',
  `whereustr` varchar(500) DEFAULT NULL COMMENT '用户条件',
  `wheredstr` varchar(500) DEFAULT NULL COMMENT '对应部门条件',
  `sort` smallint(6) DEFAULT NULL,
  `explain` varchar(200) DEFAULT NULL,
  `receid` varchar(200) DEFAULT NULL,
  `recename` varchar(200) DEFAULT NULL COMMENT '包含用户',
  `nreceid` varchar(200) DEFAULT NULL,
  `nrecename` varchar(200) DEFAULT NULL COMMENT '不包含用户',
  `islb` tinyint(1) DEFAULT '0' COMMENT '是否在生成列表页面上显示',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `syrid` varchar(200) DEFAULT NULL,
  `syrname` varchar(200) DEFAULT NULL COMMENT '此条件可适用对象',
  PRIMARY KEY (`id`),
  KEY `setid` (`setid`)
) ENGINE=MyISAM AUTO_INCREMENT=327 DEFAULT CHARSET=utf8 COMMENT='单据条件';

-- ----------------------------
-- Records of xinhu_flow_where
-- ----------------------------
INSERT INTO `xinhu_flow_where` VALUES ('1', '1', 'my', null, '与我相关', 'YHN0YXR1c2A9MSBhbmQge3JlY2VpZH0:', null, null, '0', 'receid包含用户', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('2', '5', null, null, '大于3天', 'YHRvdGFsc2AgPiAyNA::', null, null, '0', '时间(小时) 大于 24', null, null, null, null, '0', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('3', '34', null, null, '管理员对应开发部', null, null, null, '0', null, 'd2', '开发部', null, null, '0', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('4', '4', null, null, '未分配的记录', 'YGRpc3RgIGlzIG51bGw:', null, null, '20', '分配给 为空 ', null, null, null, null, '0', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('5', '1', 'wfb', null, '我发布', 'YG9wdGlkYD17dWlkfQ::', null, null, '1', 'optid字段等于当前用户', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('7', '3', 'my', null, '我的日报', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('8', '3', 'undall', null, '下属全部日报', 'YHVpZGAgaW4oe2FsbHN1cGVyfSk:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('9', '3', 'undwd', null, '全部下属未读', 'e3VpZCxkb3duYWxsfSBhbmQge3VucmVhZH0:', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('10', '1', 'wexx', null, '我未读', 'YHN0YXR1c2A9MSBhbmQge3JlY2VpZH0gYW5kIHt1bnJlYWR9', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('11', '2', 'today', null, '今日会议', 'YHR5cGVgPTAgYW5kIGBzdGF0dXNgPTEgYW5kIGBzdGFydGR0YCBsaWtlICdbZGF0ZV0lJw::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('12', '2', 'bzhy', null, '本周会议', 'YHR5cGVgPTAgYW5kIGBzdGF0dXNgPTEgYW5kIGBzdGFydGR0YCA!PSAne3dlZWtmaXJzdH0n', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('13', '2', 'all', null, '全部会议', 'YHR5cGVgPTAgYW5kIGBzdGF0dXNgPTE:', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('14', '2', 'myfq', null, '我发起会议', 'YHR5cGVgPTAgYW5kIGBzdGF0dXNgPTEgYW5kIGBvcHRpZGA9e3VpZH0:', null, null, '4', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('15', '13', null, null, '大乔查看权限', null, null, null, '0', null, 'd3,d4', '财务部,行政人事部', null, null, '0', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('16', '4', 'my', null, '我的任务', 'e2Rpc3RpZCx1aWRpbn0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('17', '4', 'down', 'down', '我下属任务', 'e2Rpc3RpZCxkb3dufQ::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('18', '4', 'wcj', null, '我创建任务', 'YG9wdGlkYD17dWlkfQ::', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('19', '4', 'dd', null, '我督导任务', 'e2RkaWQsdWlkaW59', null, null, '4', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('20', '4', 'wwc', null, '我未完成任务', 'YHN0YXR1c2AgaW4oMyw0KSBhbmQge2Rpc3RpZCx1aWRpbn0:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('21', '4', 'all', 'all', '所有任务', 'MT0x', null, null, '5', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('22', '22', 'my', null, '我执行项目', 'e3J1bnVzZXJpZCxyZWNlbm90fQ::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('23', '22', 'wwc', null, '我未完成项目', 'e3J1bnVzZXJpZCxyZWNlbm90fSBhbmQgYHN0YXR1c2AgaW4oMCwzKQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('24', '22', 'myfz', null, '我负责项目', 'e2Z1emVpZCx1aWRpbn0:', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('25', '22', 'mycj', null, '我创建项目', 'YG9wdGlkYD17dWlkfQ::', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('26', '22', 'all', 'all', '所有项目', 'MT0x', null, null, '5', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('27', '12', 'my', null, '我的日程', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('28', '12', 'rece', null, '提醒给我的', 'e3JlY2VpZCxyZWNlbm90fQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('29', '4', 'ywc', null, '已完成任务', 'e2Rpc3RpZCx1aWRpbn0gYW5kIGBzdGF0dXNgPTE:', null, null, '0', null, null, null, null, null, '0', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('30', '7', 'my', null, '我的客户', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPTE:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('31', '7', 'gxgw', 'shate', '共享给我的', 'e3NoYXRlaWQsdWlkaW59', null, null, '21', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('32', '7', 'myall', null, '我全部客户', 'YHVpZGA9e3VpZH0:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('33', '7', 'my_ting', null, '我停用客户', 'dWlkPXt1aWR9IGFuZCBgc3RhdHVzYD0w', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('34', '7', 'downall', 'down', '下属全部客户', 'YHVpZGAgaW4oe3N1cGVyfSk:', null, null, '9', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('35', '7', 'downty', 'down', '下属停用', 'YHVpZGAgaW4oe3N1cGVyfSkgYW5kIGBzdGF0dXNgPTA:', null, null, '10', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('36', '7', 'downstat', 'down', '下属标★', 'YHVpZGAgaW4oe3N1cGVyfSkgYW5kIGBpc3N0YXRgPTE:', null, null, '11', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('37', '7', 'mystat', null, '我标★客户', 'YHVpZGA9e3VpZH0gYW5kIGBpc3N0YXRgPTE:', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('38', '7', 'distdfp', 'dist', '待分配客户', 'KGB1aWRgPXt1aWR9IG9yIGBmemlkYD17dWlkfSBvciBgY3JlYXRlaWRgPXt1aWR9IG9yIGB1aWRgIGluKHtzdXBlcn0pKSBhbmQgYHVpZGA9MA::', null, null, '31', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('39', '7', 'distyfp', 'dist', '已分配的', 'KGB1aWRgPXt1aWR9IG9yIGBmemlkYD17dWlkfSBvciBgY3JlYXRlaWRgPXt1aWR9ICBvciBgdWlkYCBpbih7c3VwZXJ9KSkgYW5kIGB1aWRgPjA:', null, null, '32', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('40', '7', 'distall', 'dist', '全部分配状态', 'KGB1aWRgPXt1aWR9IG9yIGBmemlkYD17dWlkfSBvciBgY3JlYXRlaWRgPXt1aWR9ICBvciBgdWlkYCBpbih7c3VwZXJ9KSk:', null, null, '33', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('41', '7', 'mycj', null, '我创建', 'YGNyZWF0ZWlkYD17dWlkfQ::', null, null, '4', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('42', '7', 'all', 'all', '所有客户', 'MT0x', null, null, '50', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('43', '7', 'allty', 'all', '所有停用客户', 'YHN0YXR1c2A9MA::', null, null, '51', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('44', '29', 'my', null, '我的档案', 'W0FdYGlkYD17dWlkfQ::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('45', '34', 'my', null, '我的薪资', 'YHh1aWRgPXt1aWR9', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('46', '8', 'mygx', null, '我需跟进', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0ZWA9MA::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('47', '8', 'mycj', null, '已成交', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0ZWA9MQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('48', '8', 'myds', null, '已丢失', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0ZWA9Mg::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('49', '8', 'myall', null, '我所有销售机会', 'YHVpZGA9e3VpZH0g', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('50', '8', 'downgj', 'down', '下属需跟进', 'YHVpZGAgaW4oe3N1cGVyfSkgYW5kIGBzdGF0ZWA9MA::', null, null, '11', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('51', '8', 'downcj', 'down', '下属已成交', 'YHVpZGAgaW4oe3N1cGVyfSkgYW5kIGBzdGF0ZWA9MQ::', null, null, '12', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('52', '8', 'downds', 'down', '下属已丢失', 'YHVpZGAgaW4oe3N1cGVyfSkgYW5kIGBzdGF0ZWA9Mg::', null, null, '13', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('53', '8', 'downall', 'down', '下属所有销售机会', 'YHVpZGAgaW4oe3N1cGVyfSkg', null, null, '14', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('54', '8', 'all', 'all', '所有销售机会', 'MT0x', null, null, '22', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('55', '8', 'allgj', 'all', '所有需跟进', 'YHN0YXRlYD0w', null, null, '21', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('56', '8', 'allcj', 'all', '所有已成交', 'YHN0YXRlYD0x', null, null, '23', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('57', '8', 'allds', 'all', '所有已丢失', 'YHN0YXRlYD0y', null, null, '24', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('58', '38', 'my', null, '我的调动', 'W0FdYHRyYW51aWRgPXt1aWR9', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('59', '38', 'mysq', null, '我申请调动', 'YHVpZGA9e3VpZH0:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('60', '38', 'all', 'all', '所有调动', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('61', '32', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('62', '32', 'all', 'all', '所有申请', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('63', '33', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('64', '33', 'all', 'all', '所有申请', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('65', '37', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('66', '37', 'all', 'all', '所有申请', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('67', '39', 'objmy', null, '奖惩对象是我', 'YG9iamVjdGlkYD17dWlkfQ::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('68', '39', 'my', null, '我申请的', 'YHVpZGA9e3VpZH0:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('69', '39', 'all', 'all', '所有奖惩', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('70', '1', 'all', 'all', '所有公告', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('71', '11', 'all', 'all', '所有费用报销', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('72', '11', 'my', null, '我的报销', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('73', '25', 'all', 'all', '所有出差报销', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('74', '25', 'my', null, '我的出差报销', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('75', '26', 'all', 'all', '所有借款单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('76', '26', 'my', null, '我的借款单', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('77', '27', 'all', 'all', '所有还款单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('78', '27', 'my', null, '我的还款单', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('79', '49', 'all', 'all', '所有印章申请', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('80', '49', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('81', '44', 'all', 'all', '所有车辆预定', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('82', '44', 'my', null, '我的预定', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('83', '44', 'myjia', null, '驾驶员是我', 'e2ppYWlkLHVpZGlufQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('84', '44', 'myuse', null, '使用人包含我', 'e3VzZWlkLHJlY2VhbGx9', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('85', '46', 'all', 'all', '所有图书借阅', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('86', '46', 'my', null, '我的借阅', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('87', '29', 'all', 'all', '所有档案', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('88', '3', 'all', 'all', '所有日报', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('89', '56', 'all', 'all', '所有培训', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('90', '43', 'all', 'all', '所有题库', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('91', '43', 'weidu', null, '我未读', 'e3VucmVhZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('167', '20', 'all', 'all', '所有收文单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('93', '58', 'all', 'all', '所有登记', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('94', '42', 'all', 'all', '所有车辆', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('95', '42', 'public', 'all', '可预定车辆', 'YGlzcHVibGljYD0x', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('96', '58', 'kydq', 'all', '7天内到期', 'YGVuZGR0YCBpcyBub3QgbnVsbCBhbmQgYGVuZGR0YDx7ZGF0ZSs3fSBhbmQgYGVuZGR0YD49e2RhdGV9', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('97', '35', 'my', null, '我所有合同', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('98', '35', 'ygq', null, '已过期', 'YHVpZGA9e3VpZH0gYW5kIGBlbmRkdGA8e2RhdGV9', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('99', '35', 'daisfk', null, '待收/付款', 'YHVpZGA9e3VpZH0gYW5kIGBpc3BheWA9MA::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('100', '35', 'qbsfk', null, '已全部收/付款', 'YHVpZGA9e3VpZH0gYW5kIGBpc3BheWA9MQ::', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('101', '35', 'bfsfk', null, '部分收/付款', 'YHVpZGA9e3VpZH0gYW5kIGBpc3BheWA9Mg::', null, null, '4', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('102', '35', 'downall', 'down', '下属所有合同', 'IHt1aWQsZG93bn0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('103', '35', 'downygq', 'down', '已过期', 'IHt1aWQsZG93bn0gYW5kIGBlbmRkdGA8e2RhdGV9', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('104', '35', 'all', 'all', '所有合同', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('105', '36', 'myskd', null, '我收款单', 'YHVpZGA9e3VpZH0gYW5kIGB0eXBlYD0w', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('106', '36', 'myskdys', null, '我已收款', 'YHVpZGA9e3VpZH0gYW5kIGB0eXBlYD0wIGFuZCBgaXNwYXlgPTE:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('107', '36', 'myskdws', null, '我未收款', 'YHVpZGA9e3VpZH0gYW5kIGB0eXBlYD0wIGFuZCBgaXNwYXlgPTA:', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('108', '36', 'downskd', 'downskd', '下属收款单', 'IHt1aWQsZG93bn0gYW5kIGB0eXBlYD0w', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('109', '36', 'downskdys', 'downskd', '下属已收款', 'e3VpZCxkb3dufSBhbmQgYHR5cGVgPTAgYW5kIGBpc3BheWA9MQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('110', '36', 'downskdwsk', 'downskd', '下属未收款', 'e3VpZCxkb3dufSBhbmQgYHR5cGVgPTAgYW5kIGBpc3BheWA9MA::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('111', '36', 'allskd', 'allskd', '所有收款单', 'YHR5cGVgPTA:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('112', '36', 'allskdys', 'allskd', '所有已收款', 'YHR5cGVgPTAgYW5kIGBpc3BheWA9MQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('113', '36', 'allskdwsk', 'allskd', '所有未收款', 'YHR5cGVgPTAgYW5kIGBpc3BheWA9MA::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('114', '36', 'myfkd', 'fkd', '我的付款单', 'YHVpZGA9e3VpZH0gYW5kIGB0eXBlYD0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('115', '36', 'myfkdyf', 'fkd', '我已付款', 'YHVpZGA9e3VpZH0gYW5kIGB0eXBlYD0xIGFuZCBgaXNwYXlgPTE:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('116', '36', 'myfkdwf', 'fkd', '我未付款', 'YHVpZGA9e3VpZH0gYW5kIGB0eXBlYD0xIGFuZCBgaXNwYXlgPTA:', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('117', '36', 'downfkd', 'downfkd', '下属付款单', 'e3VpZCxkb3dufSBhbmQgYHR5cGVgPTE:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('118', '36', 'downfkdyf', 'downfkd', '下属已付款', 'e3VpZCxkb3dufSBhbmQgYHR5cGVgPTEgYW5kIGBpc3BheWA9MQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('119', '36', 'downfkdwf', 'downfkd', '下属未付款', 'e3VpZCxkb3dufSBhbmQgYHR5cGVgPTEgYW5kIGBpc3BheWA9MA::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('120', '36', 'allfkd', 'allfkd', '所有付款单', 'YHR5cGVgPTE:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('121', '36', 'allfkdyf', 'allfkd', '所有已付款', 'YHR5cGVgPTEgYW5kIGBpc3BheWA9MQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('122', '36', 'allfkdwf', 'allfkd', '所有未付款', 'YHR5cGVgPTEgYW5kIGBpc3BheWA9MA::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('123', '59', 'all', 'all', '所有维修', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('124', '60', 'all', 'all', '所有保养记录', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('125', '59', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('126', '59', 'myjia', null, '驾驶员包含我', 'e2ppYWlkLHVpZGlufQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('127', '60', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('128', '60', 'myjia', null, '驾驶员包含我', 'IHtqaWFpZCx1aWRpbn0:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('129', '6', 'allfee', 'all', '加班费单据', 'YGppYXR5cGVgPTE:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('130', '6', 'all', 'all', '所有加班单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('131', '6', 'my', null, '我的加班单', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('132', '5', 'my', null, '我的请假条', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('133', '5', 'all', 'all', '所有请假条', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('136', '42', 'my', null, '可预定车辆', 'YGlzcHVibGljYD0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('137', '61', 'my', null, '我的设置', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('138', '62', 'my', null, '我的报修', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('139', '62', 'all', 'all', '所有报修', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('140', '62', 'allsx', 'all', '需要外修', 'YGlzd3hgPTE:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('141', '63', 'all', 'all', '所有公司单位', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('142', '31', 'my', null, '我的合同', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('143', '31', 'all', 'all', '所有合同', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('144', '31', 'all30', 'all', '30天内过期', 'YGVuZGR0YDw9e2RhdGUrMzB9IGFuZCBgc3RhdGVgPTE:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('145', '31', 'allsxz', 'all', '生效中', 'YHN0YXRlYD0x', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('146', '31', 'allyzz', 'all', '已终止', 'YHN0YXRlYD0y', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('147', '10', 'all', 'all', '所有用户', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('148', '10', 'allty', 'all', '已停用', 'YHN0YXR1c2A9MA::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('149', '10', 'allgly', 'all', '管理员用户', 'YHR5cGVgPTE:', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('150', '10', 'txlmy', 'txl', '通讯录', 'YHN0YXR1c2A9MSBhbmQgYGlzdmNhcmRgPTE:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('151', '10', 'txldown', 'txl', '我直属下级', 'YHN0YXR1c2A9MSBhbmQgYGlzdmNhcmRgPTEgYW5kIHtpZCxkb3dufQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('152', '10', 'txldownall', 'txl', '我全部下属', 'YHN0YXR1c2A9MSBhbmQgYGlzdmNhcmRgPTEgYW5kIHtpZCxkb3duYWxsfQ::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('153', '64', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('154', '64', 'all', 'all', '所有申请', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('155', '65', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('156', '65', 'all', 'all', '所有申请', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('157', '23', 'my', null, '我的外出出差', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('158', '23', 'all', 'all', '所有外出出差', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('159', '23', 'mytoday', null, '我今日外出', 'YHVpZGA9e3VpZH0gYW5kIGBpbnRpbWVgID4gJ1tkYXRlXScgYW5kIGBvdXR0aW1lYDwgJ1tkYXRlXSAyMzo1OTo1OSc:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('160', '23', 'alltoday', 'all', '所有今日外出', 'YGludGltZWAgPiAnW2RhdGVdJyBhbmQgYG91dHRpbWVgPCAnW2RhdGVdIDIzOjU5OjU5Jw::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('161', '23', 'downtoday', 'down', '下属今日外出', 'e3VpZCxkb3dufSBhbmQgYGludGltZWAgPiAnW2RhdGVdJyBhbmQgYG91dHRpbWVgPCAnW2RhdGVdIDIzOjU5OjU5Jw::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('162', '23', 'downall', 'down', '下属所有外出', 'e3VpZCxkb3dufQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('163', '51', 'my', null, '我的打卡异常', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('164', '51', 'all', 'all', '所有打卡异常', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('165', '19', 'all', 'all', '所有发文', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('166', '19', 'my', null, '我的发文', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('168', '20', 'my', null, '我的收文单', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('169', '67', 'my', null, '我的订阅管理', 'YG9wdGlkYD17dWlkfQ::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('170', '67', 'all', 'all', '所有订阅管理', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('171', '68', 'my', null, '给我的订阅', 'e3JlY2VpZCx1aWRpbn0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('172', '68', 'all', 'all', '所有订阅报表', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('173', '67', 'shate', null, '共享给我订阅', 'e3NoYXRlaWQscmVjZW5vdH0:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('174', '69', 'my', null, '需我查阅公文', 'KHtyZWNlaWQscmVjZW5vdH0p', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('175', '69', 'mywcy', null, '未查阅公文', 'e3JlY2VpZCxyZWNlbm90fSBhbmQge3VucmVhZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('176', '70', 'all', 'all', '所有考勤机设备', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('177', '70', 'offline', 'all', '不在线设备', 'KGBsYXN0ZHRgIGlzIG51bGwgb3IgYGxhc3RkdGA8e3NlY29uZC0zMDB9KQ::', null, null, '1', '最后请求在5分钟之前就不在线', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('178', '29', 'nowbirty', 'all', '当月人员生日', 'TUlEKGBiaXJ0aGRheWAsNiwyKT1kYXRlX2Zvcm1hdChub3coKSwnJW0nKQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('179', '71', 'all', 'all', '所有考核评分', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('180', '71', 'my', null, '我的考核评分', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('181', '72', 'all', 'all', '所有演示数据', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('182', '73', 'all', 'all', '仓库管理', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('183', '73', 'my', null, '我管理的仓库', 'e2NnaWQsdWlkaW59', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('184', '7', 'gys', 'gys', '供应商管理', 'YGlzZ3lzYD0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('185', '28', 'all', 'all', '所有采购单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('186', '24', 'all', 'all', '所有领用单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('187', '24', 'my', null, '我的领用单', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('188', '28', 'my', null, '我的采购单', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('189', '24', 'alldck', 'all', '待领用出库', 'YHN0YXR1c2A9MSBhbmQgYHN0YXRlYDw!MQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('190', '28', 'alldrk', 'all', '待采购入库', 'YHN0YXR1c2A9MSBhbmQgYHN0YXRlYDw!MQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('191', '74', 'all', 'all', '所有文档分区', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('192', '74', 'my', null, '我的文档分区', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('193', '76', 'my', null, '我的调拨单', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('194', '76', 'all', 'all', '所有调拨单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('195', '76', 'alldck', 'all', '待调拨入库', 'YHN0YXR1c2A9MSBhbmQgYHN0YXRlYDw!MQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('196', '7', 'allgh', 'ghai', '所有公海库', 'YGlzZ2hgPTE:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('197', '77', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('198', '77', 'all', 'all', '所有申请', 'MT0x', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('199', '77', 'down', null, '我下级申请', 'e3VpZCxkb3duYWxsfQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('200', '29', 'lizhi', 'all', '离职人员', 'YHF1aXRkdGAgaXMgbm90IG51bGw:', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('201', '78', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('202', '78', 'all', 'all', '所有需求', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('203', '79', 'my', null, '我的安排', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('204', '79', 'myms', null, '我的面试', 'e21zdXNlcmlkLHVpZGlufQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('205', '79', 'all', 'all', '所有面试', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('206', '80', 'my', null, '我未回执确认', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('207', '80', 'myall', null, '我全部回执确认', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('208', '80', 'my创建', null, '我创建的', 'YHVpZGA9e3VpZH0:', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('209', '80', 'all', 'all', '所有回执确认', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('210', '81', 'all', 'all', '所有考核项目', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('211', '81', 'my', null, '我的考核项目', 'e3JlY2VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('212', '71', 'wopf', null, '需我评分', 'IHtwZnJlbmlkLHVpZGlufQ::', null, null, '2', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('213', '71', 'wowpf', null, '需我未评分', 'e3BmcmVuaWRzLHVpZGlufQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('214', '71', 'tjall', 'tjall', '考核评分详情', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('215', '10', 'notall', 'all', '未通讯录显示', 'YGlzdmNhcmRgPTA:', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('216', '48', 'all', 'all', '所有', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('217', '83', 'all', 'all', '所有模版', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('218', '84', 'all', 'all', '所有记录', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('220', '34', null, null, '本部门人员', 'e3h1aWQsZGVwdGFsbH0:', null, null, '0', null, null, null, null, null, '0', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('221', '34', 'all', 'all', '所有人薪资', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('222', '85', 'shou', 'all', '收到的发票', 'YHR5cGVgPTE:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('223', '85', 'kai', 'all', '开出去的发票', 'YHR5cGVgPTA:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('224', '85', 'my', null, '我添加的发票', 'YG9wdGlkYD17dWlkfQ::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('225', '9', 'qie', 'all', '物品库存小于10', 'YHN0b2NrYDw9MTA:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('226', '29', 'zaizhi', 'all', '在职人员', 'YHF1aXRkdGAgaXMgbnVsbA::', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('227', '86', 'myxie', null, '需我协作', 'KGBvcHRpZGA9e3VpZH0gb3Ige3hpZW5hbWVpZCxyZWNlbm90fSk:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('228', '86', 'myview', null, '我可查看', 'KGBvcHRpZGA9e3VpZH0gb3Ige3JlY2VpZCxyZWNlbm90fSk:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('229', '86', 'my', null, '我创建', 'YG9wdGlkYD17dWlkfQ::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('230', '87', 'my', null, '我的传送', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('231', '87', 'tomy', null, '接收人是我', 'e3FpYW5uYW1laWQsdWlkaW59', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('232', '2', 'all', 'all', '所有会议', 'YHR5cGVgPTA:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('233', '2', 'allgd', 'all', '固定会议', 'YHR5cGVgPTE:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('234', '88', 'all', 'all', '所有记录', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('235', '88', 'my', null, '发给我的', 'YHN0YXR1c2A9MSBhbmQge3JlY2VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('236', '88', 'wfb', null, '我发布的', 'YG9wdGlkYD17dWlkfQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('239', '19', 'mymb', 'hong', '所有需套红公文', 'YHN0YXR1c2A9MQ::', null, null, '1', '必须已审核的', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('240', '19', 'xuth', 'hong', '需套红公文', 'YHN0YXR1c2A9MSBhbmQgYHRoaWRgPTA:', null, null, '0', '必须已审核的', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('241', '19', 'yuth', 'hong', '已套红公文', 'YHN0YXR1c2A9MSBhbmQgYHRoaWRgPjA:', null, null, '2', '必须已审核的', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('242', '19', 'wgz', 'gaiz', '未申请盖章', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPTEgYW5kIGB0aGlkYD4wIGFuZCBgeXppZGA9MA::', null, null, '0', '需套红后才可以盖章', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('243', '19', 'bygz', 'gaiz', '不需要盖章', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPTEgYW5kIGB0aGlkYD4wIGFuZCBgeXppZGA9LTE:', null, null, '1', '需套红后才可以盖章', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('244', '19', 'ygz', 'gaiz', '已申请盖章的', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPTEgYW5kIGB0aGlkYD4wIGFuZCBgeXppZGA!MA::', null, null, '2', '需套红后才可以盖章', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('245', '19', 'allgz', 'gaiz', '所有需盖章', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPTEgYW5kIGB0aGlkYD4w', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('246', '19', 'ffall', 'ffa', '公文分发', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPTEgYW5kIGB0aGlkYD4wIGFuZCAoYHl6aWRgPjAgb3IgYHl6aWRgPS0xKQ::', null, null, '0', '需已套红和盖章的', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('247', '19', 'dfefa', 'ffa', '待分发公文', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPTEgYW5kIGB0aGlkYD4wIGFuZCAoYHl6aWRgPjAgb3IgYHl6aWRgPS0xKSBhbmQgaWZudWxsKGByZWNlaWRgLCcnKT0nJyA:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('249', '92', 'my', null, '我的销售', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('250', '92', 'downall', 'down', '下属的销售', 'YHVpZGAgaW4oe3N1cGVyfSk:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('251', '92', 'all', 'all', '所有销售', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('252', '52', 'my', null, '我的申请', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('253', '7', 'alllizi', 'all', '离职人员客户', 'YHVpZGA!MCBhbmQgYHVpZGAgbm90IGluKHNlbGVjdCBpZCBmcm9tIFtRXWFkbWluIHdoZXJlIHN0YXR1cz0xKQ::', null, null, '52', '无主客户', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('254', '69', 'all', 'all', '所有查阅', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('260', '98', 'my', null, '我的跟进', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('261', '98', 'all', 'all', '所有跟进', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('262', '99', 'my', null, '我的日程待办', 'KGB1aWRgPXt1aWR9IG9yIGBkaXN0aWRgPXt1aWR9KQ::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('263', '99', 'receid', null, '提醒给我', 'e3JlY2VpZCxyZWNlbm90fQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('264', '98', 'down', null, '下属跟进', 'YHVpZGAgaW4oe3N1cGVyfSk:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('265', '98', 'mywwc', null, '未完成', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0dXNgPTA:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('266', '100', 'all', 'all', '所有归还单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('267', '41', 'all', 'all', '所有资产', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('268', '41', 'my', null, '我可用资产', 'e3VzZWlkLHJlY2Vub3R9', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('269', '41', 'mycj', null, '我创建', 'b3B0aWQ9e3VpZH0:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('270', '9', 'all', 'all', '所有物品', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('271', '101', 'all', 'all', '所有问卷', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('272', '101', 'my', null, '我的问卷', 'e3JlY2VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('273', '9', 'my', null, '我创建的物品', 'YG9wdGlkYD17dWlkfQ::', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('274', '101', 'mcj', null, '我创建的', 'YHVpZGA9e3VpZH0:', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('275', '19', 'bxyyd', 'hong', '不需要套红', 'YHN0YXR1c2A9MSBhbmQgYHRoaWRgPS0x', null, null, '3', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('276', '9', 'pan', null, '盘点条件', 'MT0x', null, null, '0', null, null, null, null, null, '0', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('277', '4', 'downwwc', 'down', '下属未完成', 'e2Rpc3RpZCxkb3dufSBhbmQgYHN0YXR1c2AgaW4oMCwzLDQp', null, null, '5', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('278', '4', 'downwc', 'down', '下属已完成', 'e2Rpc3RpZCxkb3dufSBhbmQgYHN0YXR1c2AgPTE:', null, null, '6', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('279', '102', 'my', null, '我的退货单', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('280', '102', 'all', 'all', '所有退货单', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('295', '108', 'all', 'all', '所有证件', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('296', '108', 'my', null, '我的证件', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('297', '108', 'daoqi', 'all', '7天内到期', 'YGVkdGAgaXMgbm90IG51bGwgYW5kIGBlZHRgPD17ZGF0ZSs3fSBhbmQgYGVkdGA!PXtkYXRlfQ::', null, null, '1', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('320', '116', 'all', 'all', '所有注册', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('322', '45', 'all', 'all', '所有图书', 'MT0x', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('323', '82', 'my', null, '我的便笺', 'YHVpZGA9e3VpZH0:', null, null, '0', null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_flow_where` VALUES ('324', '82', 'mywfc', null, '待完成便笺', 'YHVpZGA9e3VpZH0gYW5kIGBzdGF0ZWA9Mg::', null, null, '1', null, null, null, null, null, '1', '1', null, null);

-- ----------------------------
-- Table structure for `xinhu_godepot`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_godepot`;
CREATE TABLE `xinhu_godepot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `depotname` varchar(50) DEFAULT NULL COMMENT '仓库名称',
  `depotaddress` varchar(100) DEFAULT NULL COMMENT '仓库地址',
  `cgname` varchar(50) DEFAULT NULL COMMENT '仓库管理员',
  `cgid` varchar(50) DEFAULT NULL COMMENT '仓库管理员的ID',
  `depotexplain` varchar(500) DEFAULT NULL COMMENT '说明',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `depotnum` varchar(50) DEFAULT NULL COMMENT '仓库编号',
  `wpshu` int(11) DEFAULT '0' COMMENT '物品数',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='物品仓库表';

-- ----------------------------
-- Records of xinhu_godepot
-- ----------------------------
INSERT INTO `xinhu_godepot` VALUES ('1', '默认仓库', null, '赵子龙', '7', null, '0', 'depot', '3', '0');
INSERT INTO `xinhu_godepot` VALUES ('2', '仓库2', null, '貂蝉', '2', null, '1', 'cknu', '4', '0');
INSERT INTO `xinhu_godepot` VALUES ('3', '泉州仓库', null, '大乔', '4', null, '0', 'qzbh', '0', '2');

-- ----------------------------
-- Table structure for `xinhu_goodm`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_goodm`;
CREATE TABLE `xinhu_goodm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `num` varchar(50) DEFAULT NULL COMMENT '编号如销售号',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` int(11) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `type` tinyint(1) DEFAULT '0' COMMENT '0领用,1采购申请',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '费用',
  `custid` smallint(6) DEFAULT '0',
  `custname` varchar(50) DEFAULT NULL COMMENT '供应商名称',
  `discount` decimal(8,2) DEFAULT '0.00' COMMENT '优惠价格',
  `state` tinyint(1) DEFAULT '0' COMMENT '0待出入库,2部分出入库,1已全部出入库',
  `custractid` int(11) DEFAULT '0' COMMENT '关联客户合同custract.id',
  `ispay` tinyint(1) DEFAULT '0' COMMENT '是否全部创建收款单',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `leixing` varchar(50) DEFAULT NULL COMMENT '退货类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='物品领用采购申请主表';

-- ----------------------------
-- Records of xinhu_goodm
-- ----------------------------
INSERT INTO `xinhu_goodm` VALUES ('1', '1', null, '2017-11-11 15:43:29', '1', '管理员', '2017-11-11', null, '1', '1', '1', '26456.00', '0', null, '10.00', '1', '0', '0', '0', null);
INSERT INTO `xinhu_goodm` VALUES ('17', '1', null, '2020-03-31 22:18:38', '1', '管理员', '2020-03-31', null, '1', '1', '0', '0.00', '0', null, '0.00', '1', '0', '0', '1', null);
INSERT INTO `xinhu_goodm` VALUES ('3', '1', null, '2018-02-07 23:05:49', '1', '管理员', '2017-11-12', '这只是测试而已拉。', '0', '1', '1', '14400.00', '3', '微软公司', '0.00', '0', '0', '0', '0', null);
INSERT INTO `xinhu_goodm` VALUES ('4', '1', null, '2017-11-13 14:20:47', '1', '管理员', '2017-11-13', null, '1', '1', '1', '24.00', '0', null, '0.00', '1', '0', '0', '0', null);
INSERT INTO `xinhu_goodm` VALUES ('5', '1', null, '2017-12-28 14:26:46', '1', '管理员', '2017-12-28', '嗯嗯嗯额', '1', '1', '3', '0.00', '1', '默认仓库', '0.00', '1', '0', '0', '0', null);
INSERT INTO `xinhu_goodm` VALUES ('6', '1', null, '2018-01-27 10:27:28', '1', '管理员', '2018-01-27', null, '1', '1', '3', '0.00', '1', '默认仓库', '0.00', '1', '0', '0', '0', null);
INSERT INTO `xinhu_goodm` VALUES ('8', '1', null, '2018-07-06 18:27:39', '1', '管理员', '2018-07-06', null, '1', '1', '1', '2400.00', '4', '谷歌公司', '0.00', '1', '0', '0', '0', null);
INSERT INTO `xinhu_goodm` VALUES ('20', '1', null, '2020-03-31 23:06:42', '1', '管理员', '2020-03-31', null, '1', '1', '4', '0.00', '17', 'WA-20200331-001', '0.00', '1', '0', '0', '1', null);
INSERT INTO `xinhu_goodm` VALUES ('10', '1', null, '2019-05-09 18:11:12', '1', '管理员', '2019-04-29', '调拨', '1', '1', '3', '0.00', '1', '默认仓库', '0.00', '0', '0', '0', '0', null);
INSERT INTO `xinhu_goodm` VALUES ('11', '1', 'CF-20190512-001', '2019-05-12 14:36:03', '1', '管理员', '2019-05-12', null, '1', '1', '2', '58.00', '1', '信呼办公软件', '2.00', '1', '6', '0', '0', null);
INSERT INTO `xinhu_goodm` VALUES ('13', '4', null, '2019-06-07 12:54:28', '4', '大乔', '2019-06-07', null, '1', '1', '1', '1101.00', '3', '微软公司', '0.00', '2', '0', '0', '2', null);
INSERT INTO `xinhu_goodm` VALUES ('21', '1', null, '2020-04-01 20:19:36', '1', '管理员', '2020-04-01', null, '1', '1', '0', '0.00', '0', null, '0.00', '1', '0', '0', '1', null);
INSERT INTO `xinhu_goodm` VALUES ('22', '1', null, '2020-09-08 18:06:35', '1', '管理员', '2020-09-08', null, '1', '1', '5', '0.00', '0', null, '0.00', '1', '0', '0', '1', '普通退货');
INSERT INTO `xinhu_goodm` VALUES ('23', '1', null, '2020-09-18 11:27:00', '1', '管理员', '2020-09-08', null, '1', '1', '5', '58.00', '1', '信呼办公软件', '2.00', '0', '11', '0', '1', '无理由退货');
INSERT INTO `xinhu_goodm` VALUES ('24', '4', null, '2021-10-15 15:37:29', '4', '大乔', '2021-10-15', '不错的选择', '0', '1', '0', '0.00', '0', null, '0.00', '0', '0', '0', '2', null);

-- ----------------------------
-- Table structure for `xinhu_goodn`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_goodn`;
CREATE TABLE `xinhu_goodn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表',
  `aid` smallint(6) DEFAULT '0' COMMENT '物品Id',
  `count` int(11) DEFAULT '0' COMMENT '数量',
  `couns` int(11) DEFAULT '0' COMMENT '已出库入库数跟count相等时就全部了',
  `sort` smallint(6) DEFAULT '0',
  `unit` varchar(5) DEFAULT NULL COMMENT '单位',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `lygh` tinyint(1) DEFAULT '0' COMMENT '是否需要归还',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='物品库存详细表';

-- ----------------------------
-- Records of xinhu_goodn
-- ----------------------------
INSERT INTO `xinhu_goodn` VALUES ('1', '1', '3', '22', '22', '0', '台', '1200.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('2', '1', '5', '33', '33', '1', '支', '2.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('24', '17', '3', '2', '2', '0', null, '0.00', '1', '2');
INSERT INTO `xinhu_goodn` VALUES ('4', '3', '3', '12', '0', '0', '台', '1200.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('5', '4', '5', '12', '12', '0', '支', '2.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('6', '5', '3', '2', '2', '0', null, '0.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('7', '5', '6', '33', '33', '1', null, '0.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('8', '6', '5', '22', '22', '0', null, '0.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('10', '8', '3', '2', '2', '0', '台', '1200.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('25', '17', '5', '3', '3', '1', null, '0.00', '1', '2');
INSERT INTO `xinhu_goodn` VALUES ('13', '10', '1', '1', '0', '0', null, '0.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('14', '10', '3', '1', '0', '1', null, '0.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('15', '11', '5', '5', '5', '0', '个', '12.00', '0', '0');
INSERT INTO `xinhu_goodn` VALUES ('17', '13', '8', '10', '10', '0', '盒', '0.10', '2', '0');
INSERT INTO `xinhu_goodn` VALUES ('18', '13', '9', '22', '0', '1', '支', '50.00', '2', '0');
INSERT INTO `xinhu_goodn` VALUES ('31', '20', '5', '3', '3', '1', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodn` VALUES ('30', '20', '3', '2', '2', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodn` VALUES ('32', '21', '3', '1', '1', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodn` VALUES ('33', '21', '5', '1', '1', '1', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodn` VALUES ('34', '21', '8', '1', '1', '2', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodn` VALUES ('35', '21', '9', '1', '1', '3', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodn` VALUES ('36', '22', '1', '1', '1', '0', '月', '0.00', '1', '0');
INSERT INTO `xinhu_goodn` VALUES ('37', '23', '5', '5', '0', '0', '个', '12.00', '1', '0');
INSERT INTO `xinhu_goodn` VALUES ('38', '24', '1', '1', '0', '0', null, '0.00', '2', '0');
INSERT INTO `xinhu_goodn` VALUES ('39', '24', '3', '10', '0', '1', null, '0.00', '2', '0');

-- ----------------------------
-- Table structure for `xinhu_goods`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_goods`;
CREATE TABLE `xinhu_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` smallint(6) DEFAULT '0' COMMENT '对应分类',
  `num` varchar(30) DEFAULT NULL COMMENT '物品编号',
  `name` varchar(30) DEFAULT NULL,
  `guige` varchar(50) DEFAULT NULL COMMENT '规格',
  `xinghao` varchar(50) DEFAULT NULL COMMENT '型号',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `unit` varchar(5) DEFAULT NULL COMMENT '单位',
  `adddt` datetime DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL,
  `stock` int(11) DEFAULT '0' COMMENT '库存',
  `stockcs` int(11) DEFAULT '0' COMMENT '初始库存',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `typeid` (`typeid`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='物品表';

-- ----------------------------
-- Records of xinhu_goods
-- ----------------------------
INSERT INTO `xinhu_goods` VALUES ('1', '64', 'WP-001', '信呼实施维护', null, null, '实施维护包括如下内容\n1、系统bug修改。\n2、需求添加修改等。\n3、数据服务器稳定性维护。\n4、数据分析等。', '3000.00', '月', '2016-07-19 17:53:28', '2016-07-20 12:38:55', '1', '管理员', '21', '0', '0');
INSERT INTO `xinhu_goods` VALUES ('7', '356', 'WP-829', '红色粉笔', '红色', '4厘米', null, '0.10', '盒', '2019-06-04 15:26:46', '2019-06-04 15:26:46', '1', '管理员', '0', '0', '0');
INSERT INTO `xinhu_goods` VALUES ('3', '66', 'WP-003', '联想显示器', '21寸', 'abc', null, '1200.00', '台', '2017-05-02 13:35:05', '2017-10-14 22:56:25', '1', '管理员', '23', '0', '0');
INSERT INTO `xinhu_goods` VALUES ('4', '356', 'WP-004', '2b铅笔', '0.5mm', '25505-2B', null, '1.50', '支', '2017-07-25 19:59:55', '2017-08-20 12:06:14', '1', '管理员', '0', '0', '0');
INSERT INTO `xinhu_goods` VALUES ('5', '356', 'WP-005', '3b铅笔', '0.8mm', '25505-3B', null, '2.00', '支', '2017-07-25 19:59:55', '2017-08-20 12:06:09', '1', '管理员', '44', '0', '0');
INSERT INTO `xinhu_goods` VALUES ('6', '356', 'WP-006', '红色粉笔', '红色', '5厘米', null, '0.20', '支', '2017-08-20 11:52:00', '2019-05-05 17:04:03', '1', '管理员', '0', '0', '0');
INSERT INTO `xinhu_goods` VALUES ('8', '460', 'WP-009', '红色粉笔', '红色', '5厘米', null, '0.10', '盒', '2019-06-07 09:41:51', '2019-06-07 09:41:51', '4', '大乔', '11', '0', '2');
INSERT INTO `xinhu_goods` VALUES ('9', '460', 'WP-830', '毛笔', '1毫米', null, null, '50.00', '支', '2019-06-07 12:44:13', '2019-06-07 12:44:13', '4', '大乔', '1', '0', '2');

-- ----------------------------
-- Table structure for `xinhu_goodss`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_goodss`;
CREATE TABLE `xinhu_goodss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` smallint(6) DEFAULT '0' COMMENT '物品Id',
  `count` int(11) DEFAULT '0' COMMENT '数量',
  `uid` smallint(6) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '0入库,1出库',
  `kind` tinyint(2) DEFAULT '0' COMMENT '出入库类型',
  `optname` varchar(20) DEFAULT NULL,
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `optdt` datetime DEFAULT NULL,
  `explain` varchar(500) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `optid` int(11) DEFAULT '0',
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表',
  `sort` smallint(6) DEFAULT '0',
  `unit` varchar(5) DEFAULT NULL COMMENT '单位',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `depotid` smallint(6) DEFAULT '1' COMMENT '存放仓库ID',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `aiddepotid` (`aid`,`depotid`) USING BTREE,
  KEY `applydt` (`applydt`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='物品库存详细表';

-- ----------------------------
-- Records of xinhu_goodss
-- ----------------------------
INSERT INTO `xinhu_goodss` VALUES ('1', '1', '20', '1', '0', '2', '管理员', '2017-11-11', '2017-11-11 11:29:11', null, '1', '1', '0', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('3', '3', '2', '1', '0', '0', '管理员', '2017-11-12', '2017-11-12 14:31:00', null, '1', '1', '0', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('4', '3', '2', '1', '0', '0', '管理员', '2017-11-12', '2017-11-12 14:59:48', null, '1', '1', '0', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('5', '5', '2', '1', '0', '0', '管理员', '2017-11-12', '2017-11-12 15:01:06', null, '1', '1', '0', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('6', '5', '2', '1', '0', '0', '管理员', '2017-11-12', '2017-11-12 15:01:40', null, '1', '1', '0', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('7', '3', '18', '1', '0', '0', '管理员', '2017-11-12', '2017-11-12 15:01:56', null, '1', '1', '0', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('8', '5', '29', '1', '0', '0', '管理员', '2017-11-12', '2017-11-12 15:01:56', null, '1', '1', '0', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('11', '5', '8', '1', '0', '0', '管理员', '2017-11-13', '2017-11-13 14:29:02', null, '1', '1', '4', '0', null, '0.00', '2', '0');
INSERT INTO `xinhu_goodss` VALUES ('12', '3', '1', '1', '0', '3', '管理员', '2017-12-28', '2017-12-28 14:58:38', null, '1', '1', '5', '0', null, '0.00', '2', '0');
INSERT INTO `xinhu_goodss` VALUES ('13', '3', '-1', '1', '1', '3', '管理员', '2017-12-28', '2017-12-28 14:58:38', null, '1', '1', '5', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('14', '3', '1', '1', '0', '3', '管理员', '2017-12-28', '2017-12-28 15:00:21', null, '1', '1', '5', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('15', '3', '-1', '1', '1', '3', '管理员', '2017-12-28', '2017-12-28 15:00:21', null, '1', '1', '5', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('16', '6', '33', '1', '0', '3', '管理员', '2017-12-28', '2017-12-28 15:04:12', null, '1', '1', '5', '0', null, '0.00', '2', '0');
INSERT INTO `xinhu_goodss` VALUES ('17', '6', '-33', '1', '1', '3', '管理员', '2017-12-28', '2017-12-28 15:04:12', null, '1', '1', '5', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('20', '5', '4', '1', '0', '0', '管理员', '2018-01-27', '2018-01-27 10:28:39', null, '1', '1', '4', '0', null, '0.00', '1', '0');
INSERT INTO `xinhu_goodss` VALUES ('22', '3', '2', '1', '0', '0', '管理员', '2019-05-12', '2019-05-12 14:32:15', null, '1', '1', '8', '0', null, '0.00', '2', '0');
INSERT INTO `xinhu_goodss` VALUES ('27', '8', '2', '4', '0', '2', '大乔', '2019-06-07', '2019-06-07 12:50:06', null, '1', '4', '0', '0', null, '0.00', '3', '2');
INSERT INTO `xinhu_goodss` VALUES ('28', '9', '2', '4', '0', '2', '大乔', '2019-06-07', '2019-06-07 12:50:06', null, '1', '4', '0', '0', null, '0.00', '3', '2');
INSERT INTO `xinhu_goodss` VALUES ('29', '8', '10', '4', '0', '0', '大乔', '2019-06-07', '2019-06-07 12:55:24', null, '1', '4', '13', '0', null, '0.00', '3', '2');
INSERT INTO `xinhu_goodss` VALUES ('30', '5', '3', '1', '0', '1', '管理员', '2020-04-01', '2020-04-01 19:56:26', null, '1', '1', '20', '0', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodss` VALUES ('31', '3', '2', '1', '0', '1', '管理员', '2020-04-01', '2020-04-01 19:56:51', null, '1', '1', '20', '0', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodss` VALUES ('32', '3', '-2', '1', '1', '0', '管理员', '2020-04-01', '2020-04-01 19:57:13', null, '1', '1', '17', '0', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodss` VALUES ('33', '5', '-3', '1', '1', '0', '管理员', '2020-04-01', '2020-04-01 19:57:13', null, '1', '1', '17', '0', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodss` VALUES ('34', '3', '-1', '1', '1', '0', '管理员', '2020-04-01', '2020-04-01 20:52:02', null, '1', '1', '21', '0', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodss` VALUES ('35', '5', '-1', '1', '1', '0', '管理员', '2020-04-01', '2020-04-01 20:52:02', null, '1', '1', '21', '0', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodss` VALUES ('36', '8', '-1', '1', '1', '0', '管理员', '2020-04-01', '2020-04-01 20:52:02', null, '1', '1', '21', '0', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodss` VALUES ('37', '9', '-1', '1', '1', '0', '管理员', '2020-04-01', '2020-04-01 20:52:02', null, '1', '1', '21', '0', null, '0.00', '1', '1');
INSERT INTO `xinhu_goodss` VALUES ('38', '1', '1', '1', '0', '4', '管理员', '2020-09-08', '2020-09-08 20:36:04', null, '1', '1', '22', '0', null, '0.00', '3', '1');

-- ----------------------------
-- Table structure for `xinhu_group`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_group`;
CREATE TABLE `xinhu_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '组名',
  `sort` int(11) DEFAULT '0',
  `ispir` tinyint(4) DEFAULT '1' COMMENT '是否权限验证',
  `indate` datetime DEFAULT NULL,
  `companyid` smallint(6) DEFAULT '0' COMMENT '所属单位Id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='系统组';

-- ----------------------------
-- Records of xinhu_group
-- ----------------------------
INSERT INTO `xinhu_group` VALUES ('1', '外地职工', '1', '1', '2014-09-04 17:33:47', '0');
INSERT INTO `xinhu_group` VALUES ('2', '本地职工', '2', '1', '2014-09-04 17:33:56', '0');
INSERT INTO `xinhu_group` VALUES ('3', '部门负责人', '0', '1', '2018-09-26 11:51:56', '1');
INSERT INTO `xinhu_group` VALUES ('4', '管理员组', '0', '1', '2019-04-01 16:12:45', '0');

-- ----------------------------
-- Table structure for `xinhu_homeitems`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_homeitems`;
CREATE TABLE `xinhu_homeitems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `num` varchar(30) NOT NULL COMMENT '编号',
  `receid` varchar(300) DEFAULT NULL,
  `recename` varchar(500) DEFAULT NULL,
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `row` tinyint(1) DEFAULT '0' COMMENT '所在位置',
  PRIMARY KEY (`id`,`num`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='桌面项目表';

-- ----------------------------
-- Records of xinhu_homeitems
-- ----------------------------
INSERT INTO `xinhu_homeitems` VALUES ('1', '快捷入口', 'kjrk', null, null, '0', '1', '0');
INSERT INTO `xinhu_homeitems` VALUES ('2', '通知公告', 'gong', null, null, '0', '1', '0');
INSERT INTO `xinhu_homeitems` VALUES ('3', '考勤打卡', 'kqdk', null, null, '4', '1', '0');
INSERT INTO `xinhu_homeitems` VALUES ('4', '微信办公', 'gwwx', 'u1', '管理员', '10', '1', '0');
INSERT INTO `xinhu_homeitems` VALUES ('5', '我的申请', 'apply', null, null, '0', '1', '1');
INSERT INTO `xinhu_homeitems` VALUES ('6', '今日会议', 'meet', null, null, '2', '1', '1');
INSERT INTO `xinhu_homeitems` VALUES ('7', '系统日志', 'syslog', 'u1', '管理员', '4', '1', '1');
INSERT INTO `xinhu_homeitems` VALUES ('8', '关于信呼', 'about', 'u1', '管理员', '10', '1', '1');
INSERT INTO `xinhu_homeitems` VALUES ('9', '公文查阅', 'officic', null, null, '3', '1', '1');
INSERT INTO `xinhu_homeitems` VALUES ('10', '快捷入口(大图标)', 'kjrko', null, null, '0', '1', '0');
INSERT INTO `xinhu_homeitems` VALUES ('11', '新闻资讯', 'news', null, null, '3', '0', '0');
INSERT INTO `xinhu_homeitems` VALUES ('12', '今日出勤情况', 'kqtotal', 'd4', '行政人事部', '2', '1', '0');
INSERT INTO `xinhu_homeitems` VALUES ('13', '登录统计', 'tjlogin', 'u1', '管理员', '5', '0', '0');
INSERT INTO `xinhu_homeitems` VALUES ('14', '便笺', 'bianjian', null, null, '1', '1', '0');

-- ----------------------------
-- Table structure for `xinhu_hrcheck`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrcheck`;
CREATE TABLE `xinhu_hrcheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `title` varchar(100) DEFAULT NULL COMMENT '考核名称',
  `applyname` varchar(30) DEFAULT NULL COMMENT '申请人姓名',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `uname` varchar(20) DEFAULT NULL COMMENT '对应人',
  `month` varchar(50) DEFAULT '{month}' COMMENT '月份',
  `content` varchar(2000) DEFAULT NULL COMMENT '考核内容',
  `fenzp` decimal(6,1) DEFAULT '0.0' COMMENT '自评分数',
  `fensj` decimal(6,1) DEFAULT '0.0' COMMENT '上级评分',
  `fenrs` decimal(6,1) DEFAULT '0.0' COMMENT '人事评分',
  `fen` decimal(6,1) DEFAULT '0.0' COMMENT '最后得分',
  `khid` int(11) DEFAULT '0' COMMENT '关联考核项目hrkaohem.id',
  `createdt` datetime DEFAULT NULL COMMENT '创建时间',
  `startdt` date DEFAULT NULL COMMENT '评分开始时间',
  `enddt` date DEFAULT NULL COMMENT '评分截止时间',
  `pfren` varchar(500) DEFAULT NULL COMMENT '评分人',
  `pfrenid` varchar(500) DEFAULT NULL,
  `pfrenids` varchar(500) DEFAULT NULL COMMENT '未评分人Id',
  `pfrens` varchar(500) DEFAULT NULL COMMENT '未评分人',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `startdt` (`startdt`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='考核评分表';

-- ----------------------------
-- Records of xinhu_hrcheck
-- ----------------------------
INSERT INTO `xinhu_hrcheck` VALUES ('1', '6', '招聘考核的', '张飞', '2018-12-12 22:52:37', '1', '管理员', '2018-12-12', null, '1', '1', null, '2018-12', null, '0.0', '0.0', '0.0', '124.0', '2', '2018-12-12 22:52:37', '2018-12-12', '2018-12-17', '管理员', '1', null, null, '0');
INSERT INTO `xinhu_hrcheck` VALUES ('2', '6', '招聘考核的', '张飞', '2019-05-12 00:05:09', '1', '管理员', '2019-05-12', null, '1', '1', null, '2019-05', null, '0.0', '0.0', '0.0', '156.0', '2', '2019-05-12 00:05:09', '2019-05-12', '2019-05-17', '管理员', '1', null, null, '0');
INSERT INTO `xinhu_hrcheck` VALUES ('3', '6', '招聘考核的', '张飞', '2021-05-01 10:20:58', '1', '管理员', '2021-05-01', null, '1', '1', null, '2021-05', null, '0.0', '0.0', '0.0', '0.0', '2', '2021-05-01 10:20:58', '2021-05-01', '2021-05-06', '管理员,赵子龙', '1,7', '1,7', '管理员,赵子龙', '0');

-- ----------------------------
-- Table structure for `xinhu_hrcheckn`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrcheckn`;
CREATE TABLE `xinhu_hrcheckn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemname` varchar(100) DEFAULT NULL COMMENT '评分项目',
  `pfname` varchar(100) DEFAULT NULL COMMENT '评分名称',
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表hrcheck.id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `sid` int(11) DEFAULT '0' COMMENT '对应hrchecks.id',
  `fenshu` smallint(6) DEFAULT '0' COMMENT '评分分数',
  `weight` decimal(6,1) DEFAULT '0.0' COMMENT '权重',
  `optid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '评分人',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `defen` decimal(6,1) DEFAULT '0.0' COMMENT '最后得分',
  `pfid` int(11) DEFAULT '0' COMMENT '关联评分hrkaohen.Id',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='考核评分记录';

-- ----------------------------
-- Records of xinhu_hrcheckn
-- ----------------------------
INSERT INTO `xinhu_hrcheckn` VALUES ('1', '打电话面试', '董事长评分', '1', '0', '1', '100', '100.0', '1', '管理员', '2018-12-12 23:18:38', '12.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('2', '现场招聘面试', '董事长评分', '1', '0', '2', '100', '100.0', '1', '管理员', '2018-12-12 23:18:38', '100.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('3', '网站招聘', '董事长评分', '1', '0', '3', '100', '100.0', '1', '管理员', '2018-12-12 23:18:38', '12.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('4', '打电话面试', '董事长评分', '2', '0', '4', '100', '100.0', '1', '管理员', '2021-04-30 09:56:02', '51.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('5', '现场招聘面试', '董事长评分', '2', '0', '5', '100', '100.0', '1', '管理员', '2021-04-30 09:56:02', '52.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('6', '网站招聘', '董事长评分', '2', '0', '6', '100', '100.0', '1', '管理员', '2021-04-30 09:56:02', '53.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('7', '打电话面试', '董事长评分', '3', '0', '7', '100', '50.0', '1', '管理员', null, '0.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('8', '打电话面试', '主管评分', '3', '1', '7', '100', '50.0', '7', '赵子龙', null, '0.0', '10', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('9', '现场招聘面试', '董事长评分', '3', '0', '8', '100', '50.0', '1', '管理员', null, '0.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('10', '现场招聘面试', '主管评分', '3', '1', '8', '100', '50.0', '7', '赵子龙', null, '0.0', '10', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('11', '网站招聘', '董事长评分', '3', '0', '9', '100', '50.0', '1', '管理员', null, '0.0', '5', '0');
INSERT INTO `xinhu_hrcheckn` VALUES ('12', '网站招聘', '主管评分', '3', '1', '9', '100', '50.0', '7', '赵子龙', null, '0.0', '10', '0');

-- ----------------------------
-- Table structure for `xinhu_hrchecks`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrchecks`;
CREATE TABLE `xinhu_hrchecks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表hrkaohem.id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `itemname` varchar(200) DEFAULT NULL COMMENT '考评项目',
  `weight` decimal(6,1) DEFAULT '0.0' COMMENT '权重(%)',
  `fenshu` decimal(6,1) DEFAULT '0.0' COMMENT '占用分数',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='考评内容';

-- ----------------------------
-- Records of xinhu_hrchecks
-- ----------------------------
INSERT INTO `xinhu_hrchecks` VALUES ('1', '1', '0', '打电话面试', '100.0', '100.0', '0');
INSERT INTO `xinhu_hrchecks` VALUES ('2', '1', '1', '现场招聘面试', '100.0', '100.0', '0');
INSERT INTO `xinhu_hrchecks` VALUES ('3', '1', '2', '网站招聘', '100.0', '100.0', '0');
INSERT INTO `xinhu_hrchecks` VALUES ('4', '2', '0', '打电话面试', '100.0', '100.0', '0');
INSERT INTO `xinhu_hrchecks` VALUES ('5', '2', '1', '现场招聘面试', '100.0', '100.0', '0');
INSERT INTO `xinhu_hrchecks` VALUES ('6', '2', '2', '网站招聘', '100.0', '100.0', '0');
INSERT INTO `xinhu_hrchecks` VALUES ('7', '3', '0', '打电话面试', '100.0', '100.0', '0');
INSERT INTO `xinhu_hrchecks` VALUES ('8', '3', '1', '现场招聘面试', '100.0', '100.0', '0');
INSERT INTO `xinhu_hrchecks` VALUES ('9', '3', '2', '网站招聘', '100.0', '100.0', '0');

-- ----------------------------
-- Table structure for `xinhu_hrdemint`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrdemint`;
CREATE TABLE `xinhu_hrdemint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `type` tinyint(1) DEFAULT '0' COMMENT '0需求,1面试',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `zhiwei` varchar(50) DEFAULT NULL COMMENT '需求职位',
  `xinzi` varchar(50) DEFAULT NULL COMMENT '薪资',
  `renshu` smallint(6) DEFAULT '1' COMMENT '需求人数',
  `content` varchar(2000) DEFAULT NULL COMMENT '职位要求/面试者简历',
  `bumen` varchar(50) DEFAULT NULL COMMENT '需求部门',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `msuser` varchar(50) DEFAULT NULL COMMENT '面试人员',
  `msuserid` varchar(50) DEFAULT NULL COMMENT '面试人员的ID',
  `mscont` varchar(2000) DEFAULT NULL COMMENT '面试记录',
  `state` tinyint(1) DEFAULT '0' COMMENT '面试结果0待面试,1录用,2不适合',
  `msdt` datetime DEFAULT NULL COMMENT '面试时间',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='面试和人员需求表';

-- ----------------------------
-- Records of xinhu_hrdemint
-- ----------------------------
INSERT INTO `xinhu_hrdemint` VALUES ('1', '7', '2018-04-18 20:39:38', '7', '赵子龙', '0', '2018-04-18', null, '1', '1', '初级php程序员', '3k-8k', '1', '1、至少要一个php框架，如tp，lararvl，ci，yii等。\n2、知道啥是面向对象。', '技术部', '20', null, null, null, '0', null, '0');
INSERT INTO `xinhu_hrdemint` VALUES ('2', '7', '2018-04-18 22:01:41', '7', '赵子龙', '1', '2018-04-18', null, '1', '1', '程序员', null, '1', null, '技术部', '夏侯惇', '管理员', '1', '符合要求', '1', '2018-04-18 22:00:00', '0');
INSERT INTO `xinhu_hrdemint` VALUES ('3', '7', '2018-04-18 22:45:48', '7', '赵子龙', '1', '2018-04-18', null, '2', '1', '丞相', null, '1', '诸葛亮，字孔明，号卧龙（也作伏龙），徐州琅琊阳都（今山东临沂市沂南县）人，三国时期蜀汉丞相，杰出的政治家、军事家、外交家、文学家、书法家、发明家。', '技术部', '诸葛亮', '磐石', '5', null, '2', '2018-04-19 08:31:00', '0');
INSERT INTO `xinhu_hrdemint` VALUES ('4', '4', '2019-07-19 11:58:06', '4', '大乔', '1', '2019-07-19', null, '0', '1', '喔喔', null, '1', null, '技术部', '未知', '大乔', '4', null, '0', '2019-07-19 11:58:00', '2');

-- ----------------------------
-- Table structure for `xinhu_hrkaohem`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrkaohem`;
CREATE TABLE `xinhu_hrkaohem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '考核名称',
  `startdt` date DEFAULT NULL COMMENT '开始日期',
  `enddt` date DEFAULT NULL COMMENT '截止日期',
  `receid` varchar(1000) DEFAULT NULL,
  `recename` varchar(1000) DEFAULT NULL COMMENT '对应考核人',
  `optid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `hegfen` smallint(6) DEFAULT '0' COMMENT '合格分数',
  `maxfen` smallint(6) DEFAULT '100' COMMENT '最高分数',
  `pinlv` varchar(50) DEFAULT 'm' COMMENT '考核频率',
  `sctime` varchar(30) DEFAULT NULL COMMENT '生成时间',
  `pfsj` smallint(6) DEFAULT '3' COMMENT '评分时间(天)',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否启用',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='考核项目';

-- ----------------------------
-- Records of xinhu_hrkaohem
-- ----------------------------
INSERT INTO `xinhu_hrkaohem` VALUES ('1', '系统开发维护考核', '2018-09-05', '2023-10-31', 'd2,d3', '开发部,财务部', '1', '管理员', '2018-12-02 13:47:03', '60', '100', 'm', '2018-12-02', '5', '1', '0');
INSERT INTO `xinhu_hrkaohem` VALUES ('2', '招聘考核的', '2018-09-01', '2023-09-30', 'u6', '张飞', '1', '管理员', '2021-05-01 10:20:55', '60', '100', 'm', '2018-12-01', '5', '1', '1');

-- ----------------------------
-- Table structure for `xinhu_hrkaohen`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrkaohen`;
CREATE TABLE `xinhu_hrkaohen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表hrkaohem.id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `pfname` varchar(50) DEFAULT NULL COMMENT '评分名称',
  `pftype` varchar(20) DEFAULT NULL COMMENT '评分人类型',
  `pfren` varchar(20) DEFAULT NULL COMMENT '评分人',
  `pfrenid` varchar(20) DEFAULT NULL,
  `pfweight` decimal(6,1) DEFAULT '0.0' COMMENT '评分权重',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='考核项目人员';

-- ----------------------------
-- Records of xinhu_hrkaohen
-- ----------------------------
INSERT INTO `xinhu_hrkaohen` VALUES ('1', '1', '0', '自评', 'my', null, null, '20.0', '0');
INSERT INTO `xinhu_hrkaohen` VALUES ('2', '1', '1', '上级评分', 'super', null, null, '70.0', '0');
INSERT INTO `xinhu_hrkaohen` VALUES ('3', '1', '2', '人事评分', 'user', '大乔', '4', '10.0', '0');
INSERT INTO `xinhu_hrkaohen` VALUES ('5', '2', '0', '董事长评分', 'user', '管理员', '1', '50.0', '1');
INSERT INTO `xinhu_hrkaohen` VALUES ('10', '2', '1', '主管评分', 'super', null, '1', '50.0', '1');

-- ----------------------------
-- Table structure for `xinhu_hrkaohes`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrkaohes`;
CREATE TABLE `xinhu_hrkaohes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表hrkaohem.id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `itemname` varchar(200) DEFAULT NULL COMMENT '考评项目',
  `weight` decimal(6,1) DEFAULT '0.0' COMMENT '权重(%)',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='考核项目的内容';

-- ----------------------------
-- Records of xinhu_hrkaohes
-- ----------------------------
INSERT INTO `xinhu_hrkaohes` VALUES ('1', '1', '0', '开发', '50.0', '0');
INSERT INTO `xinhu_hrkaohes` VALUES ('2', '1', '1', '数据库设计', '30.0', '0');
INSERT INTO `xinhu_hrkaohes` VALUES ('3', '1', '2', '文档撰写', '20.0', '0');
INSERT INTO `xinhu_hrkaohes` VALUES ('4', '2', '0', '打电话面试', '100.0', '1');
INSERT INTO `xinhu_hrkaohes` VALUES ('5', '2', '1', '现场招聘面试', '100.0', '1');
INSERT INTO `xinhu_hrkaohes` VALUES ('9', '2', '2', '网站招聘', '100.0', '1');

-- ----------------------------
-- Table structure for `xinhu_hrpositive`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrpositive`;
CREATE TABLE `xinhu_hrpositive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `applyname` varchar(30) DEFAULT NULL COMMENT '申请人姓名',
  `ranking` varchar(30) DEFAULT NULL COMMENT '职位',
  `entrydt` date DEFAULT NULL COMMENT '入职日期',
  `syenddt` date DEFAULT NULL COMMENT '试用到期日',
  `positivedt` date DEFAULT NULL COMMENT '转正日期',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '0' COMMENT '@0|待审核,1|审核通过,2|审核不通过',
  `isturn` tinyint(1) DEFAULT '0' COMMENT '@0|未提交,1|提交',
  `isover` tinyint(1) DEFAULT '0',
  `optid` int(11) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='HR转正';

-- ----------------------------
-- Records of xinhu_hrpositive
-- ----------------------------
INSERT INTO `xinhu_hrpositive` VALUES ('1', '1', null, '项目主管', '2016-07-01', '2016-10-01', '2016-10-02', '2016-10-06 20:58:38', '工作认真。', '1', '1', '1', '1', '管理员', '2016-10-06', '0');
INSERT INTO `xinhu_hrpositive` VALUES ('2', '7', '赵子龙', '高级程序员', '2016-07-01', '2016-10-01', '2018-06-16', '2018-06-15 20:04:38', '脸红啊', '1', '1', '1', '7', '赵子龙', '2018-06-15', '0');
INSERT INTO `xinhu_hrpositive` VALUES ('3', '4', '大乔', '人事主管', '2017-07-01', '2018-06-01', '2018-06-01', '2018-06-15 20:16:18', '不错的哦', '1', '1', '1', '1', '管理员', '2018-06-15', '0');

-- ----------------------------
-- Table structure for `xinhu_hrredund`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrredund`;
CREATE TABLE `xinhu_hrredund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `applyname` varchar(30) DEFAULT NULL COMMENT '申请人姓名',
  `ranking` varchar(30) DEFAULT NULL COMMENT '职位',
  `entrydt` date DEFAULT NULL COMMENT '入职日期',
  `quitdt` date DEFAULT NULL COMMENT '离职日期',
  `redundtype` varchar(20) DEFAULT NULL COMMENT '离职类型',
  `redundreson` varchar(100) DEFAULT NULL COMMENT '离职原因',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '0' COMMENT '@0|待审核,1|审核通过,2|审核不通过',
  `isturn` tinyint(1) DEFAULT '0' COMMENT '@0|未提交,1|提交',
  `isover` tinyint(1) DEFAULT '0',
  `optid` int(11) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='离职申请';

-- ----------------------------
-- Records of xinhu_hrredund
-- ----------------------------
INSERT INTO `xinhu_hrredund` VALUES ('1', '1', '管理员', '项目主管', '2016-06-01', '2016-10-31', '自动离职', '不想做了，呵呵呵', '2021-06-25 15:36:42', null, '0', '1', '0', '1', '管理员', '2016-10-07', '1');

-- ----------------------------
-- Table structure for `xinhu_hrsalarm`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrsalarm`;
CREATE TABLE `xinhu_hrsalarm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '模版名称',
  `receid` varchar(500) DEFAULT NULL,
  `recename` varchar(500) DEFAULT NULL COMMENT '适用对象',
  `explain` varchar(500) DEFAULT NULL,
  `optid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `atype` varchar(20) DEFAULT NULL COMMENT '模版类型',
  `startdt` varchar(20) DEFAULT NULL COMMENT '开始月份',
  `enddt` varchar(20) DEFAULT NULL COMMENT '截止月份',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否启用',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='薪资模版';

-- ----------------------------
-- Records of xinhu_hrsalarm
-- ----------------------------
INSERT INTO `xinhu_hrsalarm` VALUES ('1', null, 'd1', '信呼开发团队', null, '1', '管理员', '2018-09-30 13:26:47', '基本工资', '2013-12', '2050-12', '0', '1', '0');
INSERT INTO `xinhu_hrsalarm` VALUES ('2', '绩效考核', 'd1', '信呼开发团队', null, '1', '管理员', '2018-09-30 13:26:47', '绩效', '2013-12', '2050-12', '10', '1', '0');
INSERT INTO `xinhu_hrsalarm` VALUES ('3', '人事考勤', 'd1', '信呼开发团队', null, '1', '管理员', '2018-09-30 13:26:47', '考勤', '2013-01', '2050-12', '20', '1', '0');
INSERT INTO `xinhu_hrsalarm` VALUES ('4', null, 'd1', '信呼开发团队', '起征点3500', '1', '管理员', '2018-10-09 19:37:48', '个人所得税', '2013-01', '2018-09', '70', '1', '0');
INSERT INTO `xinhu_hrsalarm` VALUES ('5', null, 'd1', '信呼开发团队', null, '1', '管理员', '2018-09-30 13:26:47', '社保公积金', '2013-01', '2050-12', '50', '1', '0');
INSERT INTO `xinhu_hrsalarm` VALUES ('6', '人事考勤', 'd1', '信呼开发团队', null, '1', '管理员', '2018-09-30 13:26:47', '考勤', '2013-01', '2050-12', '20', '0', '0');
INSERT INTO `xinhu_hrsalarm` VALUES ('7', null, 'd1', '信呼开发团队', null, '1', '管理员', '2018-10-09 19:39:10', '补贴', '2013-01', '2050-12', '30', '1', '0');
INSERT INTO `xinhu_hrsalarm` VALUES ('8', null, 'd1', '信呼开发团队', null, '1', '管理员', '2018-09-30 13:26:47', '其他', '2013-01', '2050-12', '40', '1', '0');
INSERT INTO `xinhu_hrsalarm` VALUES ('9', null, 'd1', '信呼开发团队', '起征点5000，2018年10月起', '1', '管理员', '2018-09-30 13:26:47', '个人所得税', '2018-10', '2050-12', '70', '1', '0');

-- ----------------------------
-- Table structure for `xinhu_hrsalars`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrsalars`;
CREATE TABLE `xinhu_hrsalars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表hrsalarm.id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `fields` varchar(50) DEFAULT NULL COMMENT '对应字段',
  `gongsi` varchar(1000) DEFAULT NULL COMMENT '公式',
  `type` tinyint(1) DEFAULT '0' COMMENT '0字段,1增加,2减少',
  `beizhu` varchar(200) DEFAULT NULL COMMENT '备注',
  `devzhi` varchar(20) DEFAULT NULL COMMENT '默认值',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='薪资模版子表';

-- ----------------------------
-- Records of xinhu_hrsalars
-- ----------------------------
INSERT INTO `xinhu_hrsalars` VALUES ('1', '1', '0', '基本工资', 'base', null, '1', '厦门市最低工资', '1700', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('2', '8', '0', '其它增加', 'otherzj', null, '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('3', '2', '0', '绩效基数', 'jxjs', null, '0', null, '2000', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('4', '2', '1', '绩效系数', 'jxxs', null, '0', null, '1.5', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('5', '2', '2', '绩效分数', 'jxdf', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('6', '2', '3', '绩效收入', 'jtpost', '{jxjs}*{jxxs}*{jxdf}*0.01', '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('7', '3', '0', '应出勤天数', 'ysbtime', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('8', '3', '1', '出勤天数', 'zsbtime', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('9', '9', '0', '个税起征点', 'taxbase', null, '0', null, '5000', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('10', '9', '1', '个人所得税', 'taxes', 'faxgerenn({mones}-{taxbase})', '4', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('11', '7', '0', '交通补贴', 'travelbt', 'last', '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('12', '4', '1', '个人所得税', 'taxes', 'faxgeren({mones}-{taxbase})', '4', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('13', '4', '0', '个税起征点', 'taxbase', null, '0', null, '3500', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('14', '3', '2', '奖励', 'reward', null, '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('15', '3', '3', '处罚', 'punish', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('16', '3', '4', '加班(小时)', 'jiaban', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('17', '3', '5', '加班补贴', 'jiabans', null, '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('18', '5', '0', '个人社保', 'socials', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('19', '5', '1', '单位社保缴费', 'socialsunit', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('20', '5', '2', '公积金个人', 'gonggeren', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('21', '5', '3', '公积金单位', 'gongunit', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('22', '6', '4', '早退(次)', 'zaotui', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('23', '6', '2', '迟到(次)', 'cidao', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('24', '6', '3', '迟到处罚', 'cidaos', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('25', '8', '2', '计件收入', 'jiansr', null, '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('26', '8', '1', '其它减少', 'otherjs', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('27', '7', '6', '其他补贴', 'otherbt', null, '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('28', '7', '5', '电脑补贴', 'dnbt', 'last', '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('29', '7', '4', '高温津贴', 'hotbt', 'last', '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('30', '7', '3', '餐补贴', 'foodbt', 'last', '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('31', '7', '1', '通信补贴', 'telbt', 'last', '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('32', '7', '2', '技能津贴', 'skilljt', 'last', '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('33', '6', '5', '早退处罚', 'zaotuis', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('34', '6', '6', '未打卡(次)', 'weidk', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('35', '6', '7', '未打卡处罚', 'weidks', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('36', '6', '8', '请假(小时)', 'leave', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('37', '6', '9', '请假减少', 'leaves', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('38', '6', '0', '应出勤天数', 'ysbtime', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('39', '6', '1', '出勤天数', 'zsbtime', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('40', '6', '10', '奖励', 'reward', null, '1', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('41', '6', '11', '处罚', 'punish', null, '2', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('42', '6', '12', '加班(小时)', 'jiaban', null, '0', null, '0', '0');
INSERT INTO `xinhu_hrsalars` VALUES ('43', '6', '13', '加班补贴', 'jiabans', null, '1', null, '0', '0');

-- ----------------------------
-- Table structure for `xinhu_hrsalary`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrsalary`;
CREATE TABLE `xinhu_hrsalary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `xuid` smallint(6) DEFAULT '0',
  `uname` varchar(20) DEFAULT NULL COMMENT '对应人',
  `udeptname` varchar(20) DEFAULT NULL COMMENT '对应人员部门',
  `ranking` varchar(20) DEFAULT NULL COMMENT '职位',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `month` varchar(10) DEFAULT NULL COMMENT '月份',
  `base` decimal(10,2) DEFAULT '0.00' COMMENT '基本工资',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '实发',
  `mones` decimal(10,2) DEFAULT '0.00' COMMENT '应发工资',
  `postjt` decimal(10,2) DEFAULT '0.00' COMMENT '职务津贴',
  `skilljt` decimal(10,2) DEFAULT '0.00' COMMENT '技能津贴',
  `travelbt` decimal(10,2) DEFAULT '0.00' COMMENT '交通补贴',
  `telbt` decimal(10,2) DEFAULT '0.00' COMMENT '通信补贴',
  `reward` decimal(10,2) DEFAULT '0.00' COMMENT '奖励',
  `punish` decimal(10,2) DEFAULT '0.00' COMMENT '处罚',
  `socials` decimal(10,2) DEFAULT '0.00' COMMENT '个人社保',
  `socialsunit` decimal(10,2) DEFAULT '0.00' COMMENT '单位社保缴费',
  `taxes` decimal(10,2) DEFAULT '0.00' COMMENT '个人所得税',
  `taxbase` decimal(10,2) DEFAULT '0.00' COMMENT '个税起征点',
  `ispay` tinyint(1) DEFAULT '0' COMMENT '是否发放',
  `otherzj` decimal(10,2) DEFAULT '0.00' COMMENT '其它增加',
  `otherjs` decimal(10,2) DEFAULT '0.00' COMMENT '其它减少',
  `cidao` smallint(6) DEFAULT '0' COMMENT '迟到(次)',
  `cidaos` decimal(10,2) DEFAULT '0.00' COMMENT '迟到处罚',
  `zaotui` smallint(6) DEFAULT '0' COMMENT '早退(次)',
  `zaotuis` decimal(10,2) DEFAULT '0.00' COMMENT '早退处罚',
  `leave` smallint(6) DEFAULT '0' COMMENT '请假(小时)',
  `leaves` decimal(10,2) DEFAULT '0.00' COMMENT '请假减少',
  `jiaban` smallint(6) DEFAULT '0' COMMENT '加班(小时)',
  `jiabans` decimal(10,2) DEFAULT '0.00' COMMENT '加班补贴',
  `weidk` smallint(6) DEFAULT '0' COMMENT '未打卡(次)',
  `weidks` decimal(10,2) DEFAULT '0.00' COMMENT '未打卡减少',
  `jxjs` decimal(10,2) DEFAULT '0.00' COMMENT '绩效基数',
  `jxxs` decimal(10,2) DEFAULT '0.00' COMMENT '绩效系数',
  `jxdf` decimal(10,2) DEFAULT '0.00' COMMENT '绩效打分',
  `jtpost` decimal(10,2) DEFAULT '0.00' COMMENT '绩效收入',
  `ysbtime` decimal(10,1) DEFAULT '0.0' COMMENT '应上班天数',
  `zsbtime` decimal(10,1) DEFAULT '0.0' COMMENT '已上班天数',
  `gonggeren` decimal(10,2) DEFAULT '0.00' COMMENT '公积金个人',
  `gongunit` decimal(10,2) DEFAULT '0.00' COMMENT '公积金单位',
  `foodbt` decimal(10,2) DEFAULT '0.00' COMMENT '餐补贴',
  `hotbt` decimal(10,2) DEFAULT '0.00' COMMENT '高温津贴',
  `dnbt` decimal(10,2) DEFAULT '0.00' COMMENT '电脑补贴',
  `jiansr` decimal(10,2) DEFAULT '0.00' COMMENT '计件收入',
  `otherbt` decimal(10,2) DEFAULT '0.00' COMMENT '其他补贴',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `xuid` (`xuid`),
  KEY `month` (`month`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='工资表';

-- ----------------------------
-- Records of xinhu_hrsalary
-- ----------------------------
INSERT INTO `xinhu_hrsalary` VALUES ('1', '1', '3', '小乔', '财务部', '出纳', '2018-10-09 22:48:53', '2', '貂蝉', '2018-09-28', null, '1', '1', '2018-07', '1700.00', '4027.23', '4043.54', '2000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '221.46', '355.49', '16.31', '3500.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '2000.00', '1.50', '20.00', '600.00', '0.0', '0.0', '85.00', '170.00', '0.00', '0.00', '0.00', '50.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('2', '1', '3', '小乔', '财务部', '出纳', '2018-10-09 22:48:52', '2', '貂蝉', '2018-09-28', '一键生成复制2018-07月份的工资', '1', '1', '2018-08', '1700.00', '3998.13', '4013.54', '2000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '221.46', '355.49', '15.41', '3500.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '2000.00', '1.50', '20.00', '600.00', '0.0', '0.0', '85.00', '170.00', '0.00', '0.00', '0.00', '20.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('15', '1', '3', '小乔', '财务部', '出纳', '2018-10-09 22:48:52', '2', '貂蝉', '2018-10-09', '一键生成参考了2018-08月份的工资', '1', '1', '2018-09', '1700.00', '3981.73', '3993.54', '2000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '221.46', '355.49', '11.81', '3600.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '2000.00', '1.50', '20.00', '600.00', '0.0', '0.0', '85.00', '170.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('16', '1', '6', '张飞', '开发部', '程序员', '2018-10-09 22:33:14', '1', '管理员', '2018-10-09', null, '1', '1', '2018-08', '1700.00', '6398.19', '6603.54', '3500.00', '0.00', '100.00', '200.00', '0.00', '0.00', '221.46', '355.49', '205.35', '3500.00', '1', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '2000.00', '1.50', '33.00', '990.00', '0.0', '0.0', '85.00', '170.00', '200.00', '100.00', '120.00', '0.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('20', '1', '6', '张飞', '开发部', '程序员', '2018-10-09 22:33:13', '1', '管理员', '2018-10-09', '一键生成参考了2018-08月份的工资', '1', '1', '2018-09', '1700.00', '6047.19', '6213.54', '3500.00', '0.00', '100.00', '200.00', '0.00', '0.00', '221.46', '355.49', '166.35', '3500.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '2000.00', '1.50', '20.00', '600.00', '0.0', '0.0', '85.00', '170.00', '200.00', '100.00', '120.00', '0.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('23', '1', '2', '貂蝉', '财务部', '财务总监', '2019-05-07 15:08:33', '1', '管理员', '2019-05-07', '本月薪资', '1', '1', '2017-08', '1700.00', '5700.00', '0.00', '500.00', '3500.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0', '0.0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('24', '1', '4', '大乔', '行政人事部', '人事主管', '2019-05-07 15:08:33', '1', '管理员', '2019-05-07', '本月薪资', '1', '1', '2017-08', '1700.00', '5200.00', '0.00', '500.00', '3000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0', '0.0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('25', '1', '3', '小乔', '财务部', '出纳', '2019-05-07 15:08:33', '1', '管理员', '2019-05-07', '导入', '1', '1', '2017-08', '1700.00', '4695.00', '0.00', '500.00', '2500.00', '0.00', '0.00', '0.00', '5.00', '0.00', '0.00', '0.00', '0.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0', '0.0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('26', '1', '3', '小乔', '财务部', '出纳', '2019-05-07 15:10:19', '1', '管理员', '2019-05-07', '导入', '1', '1', '2018-02', '1700.00', '4695.00', '0.00', '500.00', '2500.00', '0.00', '0.00', '0.00', '5.00', '0.00', '0.00', '0.00', '0.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.0', '0.0', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0');
INSERT INTO `xinhu_hrsalary` VALUES ('27', '1', '1', '管理员', '开发部', '项目主管', '2020-07-11 22:53:19', '1', '管理员', '2020-07-10', null, '1', '1', '2020-05', '1700.00', '6400.23', '6443.54', '5000.00', '0.00', '0.00', '0.00', '50.00', '0.00', '221.46', '355.49', '43.31', '5000.00', '0', '0.00', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '0', '0.00', '2000.00', '1.50', '0.00', '0.00', '0.0', '0.0', '85.00', '170.00', '0.00', '0.00', '0.00', '0.00', '0.00', '1');

-- ----------------------------
-- Table structure for `xinhu_hrshebao`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrshebao`;
CREATE TABLE `xinhu_hrshebao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL COMMENT '名称',
  `recename` varchar(500) DEFAULT NULL COMMENT '适用对象',
  `receid` varchar(500) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '是否开启',
  `gongjishu` decimal(10,2) DEFAULT '0.00' COMMENT '公积金基数',
  `yljishu` decimal(10,2) DEFAULT '0.00' COMMENT '养老保险基数',
  `ylgeren` decimal(10,2) DEFAULT '0.00' COMMENT '养老个人比例(%)',
  `ylunit` decimal(10,2) DEFAULT '0.00' COMMENT '养老单位比例(%)',
  `syjishu` decimal(10,2) DEFAULT '0.00' COMMENT '失业报销基数',
  `sygeren` decimal(10,2) DEFAULT '0.00' COMMENT '失业个人比例(%)',
  `syunit` decimal(10,2) DEFAULT '0.00' COMMENT '失业单位比例(%)',
  `gsjishu` decimal(10,2) DEFAULT '0.00' COMMENT '工伤报销基数',
  `gsgeren` decimal(10,2) DEFAULT '0.00' COMMENT '工伤个人比例(%)',
  `gsunit` decimal(10,2) DEFAULT '0.00' COMMENT '工伤单位比例(%)',
  `syujishu` decimal(10,2) DEFAULT '0.00' COMMENT '生育保险基数',
  `syugeren` decimal(10,2) DEFAULT '0.00' COMMENT '生育个人比例(%)',
  `syuunit` decimal(10,2) DEFAULT '0.00' COMMENT '生育单位比例(%)',
  `yijishu` decimal(10,2) DEFAULT '0.00' COMMENT '医疗报销基数',
  `yigeren` decimal(10,2) DEFAULT '0.00' COMMENT '医疗个人比例(%)',
  `yiunit` decimal(10,2) DEFAULT '0.00' COMMENT '医疗单位比例(%)',
  `dbgeren` decimal(10,2) DEFAULT '0.00' COMMENT '大病个人',
  `gjjgeren` decimal(10,2) DEFAULT '0.00' COMMENT '公积金个人比例(%)',
  `gjjunit` decimal(10,2) DEFAULT '0.00' COMMENT '公积金单位比例(%)',
  `shebaogeren` decimal(10,2) DEFAULT '0.00' COMMENT '个人社保缴费',
  `shebaounit` decimal(10,2) DEFAULT '0.00' COMMENT '单位社保缴费',
  `sctime` varchar(20) DEFAULT NULL COMMENT '每月生成时间',
  `optdt` datetime DEFAULT NULL,
  `gonggeren` decimal(10,2) DEFAULT '0.00' COMMENT '公积金个人',
  `gongunit` decimal(10,2) DEFAULT '0.00' COMMENT '公积金单位',
  `explian` varchar(500) DEFAULT NULL COMMENT '说明',
  `startdt` varchar(20) DEFAULT NULL COMMENT '开始月份',
  `enddt` varchar(20) DEFAULT NULL COMMENT '截止月份',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='社保公积金';

-- ----------------------------
-- Records of xinhu_hrshebao
-- ----------------------------
INSERT INTO `xinhu_hrshebao` VALUES ('1', '厦门本地社保', '本地职工', 'g2', '1', '1700.00', '1700.00', '8.00', '12.00', '1700.00', '0.00', '0.50', '1700.00', '0.00', '0.20', '3772.80', '0.00', '0.70', '3772.80', '2.00', '3.00', '10.00', '5.00', '10.00', '221.46', '355.49', '2018-09-21', '2018-09-23 19:30:39', '85.00', '170.00', null, '2014-12', '2024-12', '0');
INSERT INTO `xinhu_hrshebao` VALUES ('2', '厦门外来社保', '外地职工', 'g1', '0', '1700.00', '1700.00', '8.00', '12.00', '1700.00', '0.00', '0.50', '1700.00', '0.00', '0.20', '3772.80', '0.00', '0.70', '3772.80', '2.00', '3.00', '10.00', '5.00', '10.00', '221.46', '355.49', '2018-09-21', '2018-09-23 19:30:16', '85.00', '170.00', null, '2014-12', '2024-12', '0');

-- ----------------------------
-- Table structure for `xinhu_hrtransfer`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrtransfer`;
CREATE TABLE `xinhu_hrtransfer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `tranuid` smallint(6) DEFAULT '0',
  `tranname` varchar(20) DEFAULT NULL COMMENT '要调动人',
  `trantype` varchar(20) DEFAULT NULL COMMENT '调动类型',
  `olddeptname` varchar(50) DEFAULT NULL COMMENT '原来部门',
  `oldranking` varchar(20) DEFAULT NULL COMMENT '原来职位',
  `effectivedt` date DEFAULT NULL COMMENT '生效日期',
  `newdeptname` varchar(50) DEFAULT NULL COMMENT '调动后部门',
  `newdeptid` smallint(6) DEFAULT NULL,
  `newranking` varchar(20) DEFAULT NULL COMMENT '调动后职位',
  `isover` tinyint(1) DEFAULT '0' COMMENT '是否已完成',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='职位调动';

-- ----------------------------
-- Records of xinhu_hrtransfer
-- ----------------------------
INSERT INTO `xinhu_hrtransfer` VALUES ('1', '1', '2016-10-19 22:03:12', '1', '管理员', '2016-10-19', null, '1', '1', '5', '磐石', '平调', '管理层', '董事长', '2016-10-19', '财务部', '3', 'CEO', '1', '0');

-- ----------------------------
-- Table structure for `xinhu_hrtrsalary`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_hrtrsalary`;
CREATE TABLE `xinhu_hrtrsalary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `applyname` varchar(30) DEFAULT NULL COMMENT '申请人姓名',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `effectivedt` date DEFAULT NULL COMMENT '生效日期',
  `floats` smallint(6) DEFAULT '0' COMMENT '调薪幅度',
  `ranking` varchar(20) DEFAULT NULL COMMENT '职位',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='调薪申请';

-- ----------------------------
-- Records of xinhu_hrtrsalary
-- ----------------------------
INSERT INTO `xinhu_hrtrsalary` VALUES ('1', '1', null, '2016-10-20 22:20:41', '1', '管理员', '2016-11-01', '122121', '0', '1', '2016-10-19', '500', '项目主管', '0');

-- ----------------------------
-- Table structure for `xinhu_im_group`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_im_group`;
CREATE TABLE `xinhu_im_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `pid` smallint(6) DEFAULT '0' COMMENT '对应上级',
  `types` varchar(10) DEFAULT NULL COMMENT '应用分类',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型@0|群,1|讨论组,2|应用',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `createid` int(11) DEFAULT NULL,
  `createname` varchar(20) DEFAULT NULL COMMENT '创建人',
  `createdt` datetime DEFAULT NULL COMMENT '创建时间',
  `face` varchar(50) DEFAULT NULL COMMENT '头像',
  `num` varchar(20) DEFAULT NULL,
  `receid` varchar(200) DEFAULT NULL,
  `recename` varchar(200) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `explain` varchar(200) DEFAULT NULL,
  `iconfont` varchar(30) DEFAULT NULL COMMENT '对应字体图标',
  `iconcolor` varchar(7) DEFAULT NULL COMMENT '字体图标颜色',
  `yylx` tinyint(1) DEFAULT '0' COMMENT '应用类型0全部,1pc,2手机',
  `urlpc` varchar(200) DEFAULT NULL COMMENT '应用上PC地址',
  `urlm` varchar(200) DEFAULT NULL COMMENT '应用上手机端地址',
  `deptid` varchar(100) DEFAULT NULL COMMENT '对应部门id',
  PRIMARY KEY (`id`),
  KEY `num` (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='IM会话表';

-- ----------------------------
-- Records of xinhu_im_group
-- ----------------------------
INSERT INTO `xinhu_im_group` VALUES ('1', '信呼团队', '0', '官网', '2', '0', null, null, null, 'images/logo.png', 'xinhu', null, null, 'link', '1', null, 'cf-c90', '#1ABC9C', '0', 'http://www.rockoa.com/', null, null);
INSERT INTO `xinhu_im_group` VALUES ('2', '全体人员', '0', null, '0', '0', '1', '管理员', '2021-09-10 10:15:22', 'images/logo.png', null, null, null, null, '1', null, null, null, '0', null, null, '1');
INSERT INTO `xinhu_im_group` VALUES ('3', '通知公告', '0', '基础', '2', '28', null, null, null, 'images/gong.png', 'gong', null, null, 'link', '1', null, 'xiaolaba', '#f25e94', '0', 'gong', null, null);
INSERT INTO `xinhu_im_group` VALUES ('4', '会议', '0', '基础', '2', '27', null, null, null, 'images/meet.png', 'meet', null, null, 'auto', '1', null, 'huiyi-copy', '#fc6419', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('7', '工作日报', '0', '基础', '2', '26', null, null, '2015-06-25 16:03:34', 'images/daily.png', 'daily', null, null, 'auto', '1', null, 'ribaojindu', '#578dc4', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('12', '任务', '0', '任务', '2', '13', '1', '管理员', '2015-09-10 13:38:07', 'images/work.png', 'work', null, null, 'auto', '1', null, 'renwu', '#91cd4a', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('13', '万年历', '0', '基础', '2', '25', null, null, null, 'images/calendar.png', 'calendar', null, null, 'link', '1', null, 'rili', '#e0815c', '0', 'http://www.rockoa.com/rili.html', null, null);
INSERT INTO `xinhu_im_group` VALUES ('14', '高管群', '0', null, '0', '0', '1', '管理员', '2021-09-10 10:15:18', null, null, null, null, null, '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('15', '流程申请', '0', '流程', '2', '10', null, null, null, 'images/flow.png', 'flow', null, null, 'buin', '1', null, 'shenqingliuchengicon', '#3fbfc2', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('16', '流程待办', '0', '流程', '2', '11', null, null, null, 'images/daiban.png', 'daiban', null, null, 'link', '1', null, 'daiban', '#33b5e5', '0', 'daiban', null, null);
INSERT INTO `xinhu_im_group` VALUES ('17', '客户管理', '0', '客户', '2', '51', null, null, null, 'images/crm.png', 'customer', null, null, 'auto', '1', null, 'kehu', '#2e98f1', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('18', '客户主页', '0', '客户', '2', '50', null, null, null, 'images/crm.png', 'crmindex', null, null, 'link', '1', null, 'kehu', '#2e98f1', '1', 'crmindex', null, null);
INSERT INTO `xinhu_im_group` VALUES ('19', '销售机会', '0', '客户', '2', '53', null, null, null, 'images/sale.png', 'custsale', null, null, 'auto', '1', null, 'xiaoshou', '#5c8fba', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('20', '文档', '0', '资源', '2', '31', null, null, null, 'images/folder.png', 'word', null, null, 'link', '1', null, 'wenjian-copy', '#f5d95a', '0', 'word', null, null);
INSERT INTO `xinhu_im_group` VALUES ('21', '日程', '0', '基础', '2', '23', null, null, null, 'images/bwl2.png', 'schedule', null, null, 'link', '1', null, 'richeng', '#158aad', '0', 'schedule', null, null);
INSERT INTO `xinhu_im_group` VALUES ('24', '外出出差', '0', '考勤', '2', '7', null, null, null, 'images/waichu.png', 'waichu', null, null, 'auto', '1', null, 'waichu', '#cc9999', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('26', '邮件', '0', '基础', '2', '22', null, null, null, 'images/email.png', 'emailm', null, null, 'link', '1', null, 'youjian', '#7CD3B0', '0', 'email', null, null);
INSERT INTO `xinhu_im_group` VALUES ('27', '知识信息', '0', '资源', '2', '33', null, null, null, 'images/zhishi.png', 'knowledge', null, null, 'auto', '1', null, 'knowledge', '#F28794', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('5', '通讯录', '0', '基础', '2', '21', null, null, null, 'images/tongxunlu.png', 'user', null, null, 'link', '1', null, 'tongxunlu', '#B28649', '0', 'reimtxl', null, null);
INSERT INTO `xinhu_im_group` VALUES ('28', '考勤信息', '0', '考勤', '2', '5', null, null, null, 'images/kaoqin.png', 'kqdkjl', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('29', '考勤打卡', '0', '考勤', '2', '3', null, null, null, 'images/adddk.png', 'kqdaka', null, null, 'link', '1', null, null, null, '0', null, '?d=we&m=ying&a=daka', null);
INSERT INTO `xinhu_im_group` VALUES ('30', '考勤统计', '0', '考勤', '2', '6', null, null, null, 'images/kaoqin.png', 'kqtotal', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('31', '开发部', '0', null, '0', '1', '1', '管理员', '2021-09-10 10:10:13', '', null, null, null, null, '1', null, null, null, '0', null, null, '2');
INSERT INTO `xinhu_im_group` VALUES ('32', '题库', '0', '资源', '2', '34', null, null, null, 'images/tiku.png', 'knowtiku', null, null, 'auto', '1', null, 'tiku', '#fb2b16', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('33', '培训考试', '0', '资源', '2', '35', null, null, null, 'images/kaoshi.png', 'kaoshi', null, null, 'auto', '1', null, 'kaoshi', '#4f68b0', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('34', '客户合同', '0', '客户', '2', '54', null, null, null, 'images/hetong.png', 'custract', null, null, 'auto', '1', null, 'kehu-kehuhetong', '#4ca2fa', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('35', '收款单', '0', '客户', '2', '55', null, null, null, 'images/skuan.png', 'custfina', null, null, 'auto', '1', null, 'achargeaudit', '#339966', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('36', '付款单', '0', '客户', '2', '56', null, null, null, 'images/fkuan.png', 'custfkd', null, null, 'auto', '1', null, 'apayaudit', '#cf9d31', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('38', '工资条', '0', '基础', '2', '29', null, null, null, 'images/gzt.png', 'hrsalary', null, null, 'auto', '1', null, null, '#FF6666', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('39', '申请流程', '0', '流程', '2', '12', null, null, null, 'images/apply.png', 'flowapply', null, null, 'link', '1', null, null, null, '0', 'flowapply', '?d=we&m=flow&a=apply', null);
INSERT INTO `xinhu_im_group` VALUES ('40', '订阅报表', '0', '基础', '2', '30', null, null, null, 'images/work.png', 'subscribeinfo', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('41', '公文查阅', '0', '公文', '2', '15', null, null, null, 'images/gw.png', 'officic', null, null, 'auto', '1', null, null, '#ff6600', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('42', '发文单', '0', '公文', '2', '16', null, null, null, 'images/fawen.png', 'officia', null, null, 'auto', '1', null, null, '#f85252', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('43', '收文单', '0', '公文', '2', '17', null, null, null, 'images/shouwen.png', 'officib', null, null, 'auto', '1', null, null, '#ff0000', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('44', '项目', '0', '任务', '2', '14', null, null, null, 'images/project.png', 'project', null, null, 'auto', '1', null, null, '#30c7b0', '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('45', '排班查看', '0', '考勤', '2', '4', null, null, null, 'images/bwl2.png', 'kqpai', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('46', '回执确认', '0', '基础', '2', '30', null, null, null, 'images/jwcl.png', 'receipt', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('49', '提醒消息', '0', '基础', '2', '20', null, null, null, 'images/todo.png', 'todo', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('50', '文档协作', '0', '资源', '2', '32', null, null, null, 'images/wjj.png', 'wordxie', null, null, 'link', '1', null, null, null, '0', 'wordxie', null, null);
INSERT INTO `xinhu_im_group` VALUES ('52', '新闻资讯', '0', '基础', '2', '30', null, null, null, 'images/news.png', 'news', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('53', '流程监控', '0', '流程', '2', '11', null, null, null, 'images/jiank.png', 'jiankong', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('56', '跟进计划', '0', '客户', '2', '52', null, null, null, 'images/work.png', 'custplan', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('57', '单据查看', '0', '流程', '2', '12', null, null, null, 'images/project.png', 'danju', null, null, 'link', '1', null, null, null, '0', 'flowsearch', '?d=we&m=flow&a=view', null);
INSERT INTO `xinhu_im_group` VALUES ('58', '考勤表', '0', '考勤', '2', '8', null, null, null, 'images/bwl2.png', 'kqbiao', null, null, 'auto', '1', null, null, null, '0', null, null, null);
INSERT INTO `xinhu_im_group` VALUES ('59', '便笺', '0', '基础', '2', '30', null, null, null, 'images/daily.png', 'bianjian', null, null, 'auto', '1', null, null, null, '0', null, null, null);

-- ----------------------------
-- Table structure for `xinhu_im_groupuser`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_im_groupuser`;
CREATE TABLE `xinhu_im_groupuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` smallint(6) NOT NULL DEFAULT '0',
  `uid` smallint(6) NOT NULL DEFAULT '0',
  `istx` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8 COMMENT='IM会话人员';

-- ----------------------------
-- Records of xinhu_im_groupuser
-- ----------------------------
INSERT INTO `xinhu_im_groupuser` VALUES ('19', '0', '0', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('120', '14', '4', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('45', '26', '9', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('124', '14', '1', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('44', '26', '8', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('43', '26', '1', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('74', '2', '1', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('144', '14', '2', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('104', '31', '1', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('135', '2', '4', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('134', '2', '3', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('133', '2', '2', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('100', '2', '5', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('101', '2', '6', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('102', '2', '7', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('103', '2', '8', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('105', '31', '6', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('106', '31', '7', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('107', '31', '8', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('111', '47', '6', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('117', '51', '3', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('112', '47', '5', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('116', '51', '1', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('143', '14', '5', '1');
INSERT INTO `xinhu_im_groupuser` VALUES ('145', '2', '12', '1');

-- ----------------------------
-- Table structure for `xinhu_im_history`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_im_history`;
CREATE TABLE `xinhu_im_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) DEFAULT NULL,
  `receid` smallint(6) DEFAULT '0',
  `uid` smallint(6) DEFAULT NULL,
  `sendid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL,
  `cont` varchar(200) DEFAULT NULL,
  `stotal` smallint(6) DEFAULT '0',
  `title` varchar(50) DEFAULT NULL COMMENT '推送时标题',
  `xgurl` varchar(200) DEFAULT NULL COMMENT '相关地址',
  `messid` int(11) DEFAULT '0' COMMENT '最后一条消息id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`receid`,`uid`),
  KEY `optdt` (`optdt`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='IM会话记录历史表';

-- ----------------------------
-- Records of xinhu_im_history
-- ----------------------------
INSERT INTO `xinhu_im_history` VALUES ('1', 'group', '2', '2', '8', '2021-10-29 13:43:00', '5aSn5a625pyJ5ZWl6Zeu6aKY6L!Z6YeM6K!05ZWK77yB', '1', null, null, '0');
INSERT INTO `xinhu_im_history` VALUES ('2', 'group', '2', '7', '8', '2021-10-29 13:43:00', '5aSn5a625pyJ5ZWl6Zeu6aKY6L!Z6YeM6K!05ZWK77yB', '1', null, null, '0');
INSERT INTO `xinhu_im_history` VALUES ('3', 'group', '2', '6', '8', '2021-10-29 13:43:00', '5aSn5a625pyJ5ZWl6Zeu6aKY6L!Z6YeM6K!05ZWK77yB', '1', null, null, '0');
INSERT INTO `xinhu_im_history` VALUES ('4', 'group', '2', '8', '8', '2021-10-29 13:43:00', '5aSn5a625pyJ5ZWl6Zeu6aKY6L!Z6YeM6K!05ZWK77yB', '1', null, null, '0');
INSERT INTO `xinhu_im_history` VALUES ('5', 'group', '2', '5', '8', '2021-10-29 13:43:00', '5aSn5a625pyJ5ZWl6Zeu6aKY6L!Z6YeM6K!05ZWK77yB', '1', null, null, '0');
INSERT INTO `xinhu_im_history` VALUES ('6', 'group', '2', '1', '8', '2021-10-29 13:43:00', '5aSn5a625pyJ5ZWl6Zeu6aKY6L!Z6YeM6K!05ZWK77yB', '1', null, null, '0');
INSERT INTO `xinhu_im_history` VALUES ('7', 'group', '2', '4', '8', '2021-10-29 13:43:00', '5aSn5a625pyJ5ZWl6Zeu6aKY6L!Z6YeM6K!05ZWK77yB', '1', null, null, '0');

-- ----------------------------
-- Table structure for `xinhu_im_menu`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_im_menu`;
CREATE TABLE `xinhu_im_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) NOT NULL DEFAULT '0',
  `pid` smallint(6) NOT NULL DEFAULT '0',
  `name` varchar(10) DEFAULT NULL COMMENT '名称',
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `type` tinyint(4) DEFAULT '0' COMMENT '1url,0事件',
  `url` varchar(300) DEFAULT NULL COMMENT '对应地址',
  `num` varchar(20) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL COMMENT '颜色',
  `receid` varchar(300) DEFAULT NULL,
  `recename` varchar(300) DEFAULT NULL COMMENT '可使用人员',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 COMMENT='IM下应用菜单';

-- ----------------------------
-- Records of xinhu_im_menu
-- ----------------------------
INSERT INTO `xinhu_im_menu` VALUES ('1', '13', '0', '上月', '0', '0', 'prevmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('2', '13', '0', '当月', '0', '0', 'nowmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('3', '13', '0', '下月', '0', '0', 'nextmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('4', '3', '0', '全部信息', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('5', '3', '0', '未读信息', '0', '0', 'wexx', 'weidu', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('6', '12', '0', '我的任务', '0', '0', null, 'myrw', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('7', '12', '0', '下属任务', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('8', '12', '0', '＋创建任务', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('9', '3', '0', '新增公告', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('10', '4', '0', '今日会议', '0', '0', 'today', 'today', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('11', '4', '0', '本周会议', '0', '0', 'week', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('12', '4', '0', '＋新增会议', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('13', '7', '0', '我的日报', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('14', '7', '0', '下属日报', '0', '0', 'under', 'under', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('15', '7', '0', '＋写日报', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('16', '7', '14', '全部', '0', '0', 'undall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('17', '7', '14', '未读', '1', '0', 'undwd', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('18', '1', '0', '最新信息', '0', '0', 'new', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('19', '1', '0', '＋建议反馈', '1', '1', 'http://www.rockoa.com/fankui.html', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('22', '12', '6', '我创建的', '2', '0', 'wcj', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('20', '12', '6', '未完成', '0', '0', 'wwc', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('21', '12', '6', '已完成', '1', '0', 'ywc', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('23', '15', '0', '流程申请', '3', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('24', '15', '23', '＋请假条', '0', '1', 'add_leave', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('25', '15', '23', '＋加班单', '0', '1', 'add_jiaban', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('26', '15', '0', '我的申请', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('27', '15', '0', '处理未通过', '0', '0', 'wtg', 'mywtg', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('28', '15', '26', '我所有申请', '0', '0', 'apply', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('29', '15', '26', '未通过', '0', '0', 'wtg', null, 'red', null, null);
INSERT INTO `xinhu_im_menu` VALUES ('30', '15', '26', '已完成', '0', '0', 'ywc', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('31', '16', '0', '所有待办', '0', '0', 'daib', 'daiban', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('32', '16', '0', '单据查看', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('34', '16', '32', '直属下级申请', '1', '0', 'myxia', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('33', '16', '32', '经我处理', '0', '0', 'jwcl', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('35', '12', '6', '我所有任务', '3', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('36', '19', '0', '我的销售机会', '0', '0', null, 'gen', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('37', '17', '40', '我的客户', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('41', '17', '0', '新增客户', '2', '1', 'add_customer', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('39', '17', '40', '我全部客户', '0', '0', 'myall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('40', '17', '0', '我的客户', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('42', '19', '0', '新增销售', '2', '1', 'add_custsale', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('43', '19', '36', '需跟进', '0', '0', 'mygx', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('44', '19', '36', '已成交', '1', '0', 'mycj', null, 'green', null, null);
INSERT INTO `xinhu_im_menu` VALUES ('45', '19', '36', '已丢失', '2', '0', 'myds', null, '', null, null);
INSERT INTO `xinhu_im_menu` VALUES ('46', '19', '36', '我所有销售', '3', '0', 'myall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('47', '17', '40', '我标★客户', '0', '0', 'mystat', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('48', '17', '40', '我停用客户', '0', '0', 'my_ting', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('49', '15', '26', '待处理', '0', '0', 'dcl', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('50', '20', '0', '新建文件夹', '2', '0', 'create', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('51', '20', '0', '上传文件', '0', '0', 'up', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('52', '20', '0', '共享的', '1', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('53', '21', '0', '日程', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('54', '21', '53', '当月', '0', '0', 'nowmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('55', '21', '53', '下月', '0', '0', 'nextmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('56', '21', '0', '日程管理', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('57', '21', '0', '＋新增', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('58', '24', '87', '我今日外出', '0', '0', 'mytoday', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('59', '24', '0', '＋新增', '2', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('60', '24', '87', '我所有外出', '1', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('61', '20', '52', '我共享的', '0', '0', 'shate', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('62', '20', '52', '共享给我的', '0', '0', 'fxgw', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('63', '15', '23', '更多&gt;&gt;', '0', '0', 'moreapply', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('64', '26', '0', '收件箱', '0', '0', null, 'sjx', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('65', '26', '0', '＋写信', '2', '1', 'add_emailm', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('66', '27', '0', '知识信息', '0', '0', 'def', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('67', '26', '0', '草稿箱', '1', '0', 'cgx', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('68', '26', '64', '未读邮件', '0', '0', 'wdyj', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('69', '26', '64', '所有邮件', '0', '0', 'sjx', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('70', '26', '64', '已发送', '0', '0', 'yfs', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('71', '5', '0', '内部通讯录', '0', '0', 'txlmy|user', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('72', '5', '0', '个人通讯录', '0', '0', 'def|vcard', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('73', '5', '0', '＋新增', '0', '1', 'add_vcard', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('74', '28', '0', '我的记录', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('75', '28', '0', '考勤信息', '2', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('80', '28', '74', '我的打卡', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('77', '28', '75', '＋请假条', '0', '1', 'add_leave', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('78', '28', '75', '我的请假条', '3', '0', 'my|leave', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('79', '17', '0', '下属客户', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('81', '28', '82', '下属打卡', '0', '0', 'down', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('82', '28', '0', '下属记录', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('83', '28', '74', '我的定位', '0', '0', 'my|kqdw', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('84', '28', '82', '下属定位', '0', '0', 'down|kqdw', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('92', '19', '0', '下属销售机会', '1', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('85', '28', '75', '我的加班单', '4', '0', 'my|jiaban', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('86', '28', '75', '＋加班单', '1', '1', 'add_jiaban', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('87', '24', '0', '我的外出', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('88', '24', '91', '下属所有外出', '3', '0', 'downall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('89', '1', '0', '打开官网', '0', '1', 'http://www.rockoa.com/', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('90', '24', '91', '下属今日外出', '2', '0', 'downtoday', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('91', '24', '0', '下属外出', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('93', '30', '0', '我的统计', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('94', '30', '0', '下属统计', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('95', '30', '93', '当月统计', '0', '0', 'mynow', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('96', '30', '93', '上月统计', '0', '0', 'mylast', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('97', '30', '94', '当月统计', '0', '0', 'downnow', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('98', '30', '94', '上月统计', '0', '0', 'downlast', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('99', '21', '56', '我的日程', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('100', '21', '56', '提醒给我的', '1', '0', 'rece', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('101', '17', '40', '我创建', '0', '0', 'mycj', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('102', '17', '40', '共享给我的', '0', '0', 'gxgw', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('103', '17', '79', '下属全部客户', '0', '0', 'downall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('104', '17', '79', '下属停用', '0', '0', 'downty', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('105', '17', '79', '下属标★', '0', '0', 'downstat', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('106', '19', '92', '下属需跟进', '0', '0', 'downgj', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('107', '19', '92', '下属已成交', '1', '0', 'downcj', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('108', '19', '92', '下属已丢失', '2', '0', 'downds', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('109', '19', '92', '所有销售机会', '3', '0', 'downall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('110', '32', '0', '所有题库', '0', '0', 'all', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('111', '32', '0', '未读题库', '1', '0', 'weidu', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('112', '33', '0', '我未考试', '0', '0', 'weiks', 'weiks', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('113', '33', '0', '所有考试', '1', '0', 'myall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('114', '34', '0', '我的合同', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('115', '34', '114', '我所有合同', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('116', '34', '114', '我的已过期', '1', '0', 'ygq', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('117', '34', '0', '下属合同', '1', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('118', '34', '117', '下属所有合同', '0', '0', 'downall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('119', '34', '117', '下属已过期', '1', '0', 'downygq', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('120', '34', '0', '＋新增', '1', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('121', '35', '0', '我的收款单', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('122', '35', '121', '我未收款', '0', '0', 'myskdws', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('123', '35', '121', '我已收款', '1', '0', 'myskdys', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('124', '35', '121', '我所有的', '2', '0', 'myskd', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('125', '35', '0', '下属收款单', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('126', '35', '125', '下属未收款', '0', '0', 'downskdwsk', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('127', '35', '125', '下属已收款', '0', '0', 'downskdys', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('128', '35', '125', '下属所有', '0', '0', 'downskd', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('129', '35', '0', '＋新增', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('130', '36', '0', '我的付款单', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('131', '36', '130', '我未付款', '0', '0', 'myfkdwf|custfina', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('132', '36', '130', '我已付款', '1', '0', 'myfkdyf|custfina', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('133', '36', '130', '我所有的', '2', '0', 'myfkd|custfina', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('134', '36', '0', '下属付款单', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('135', '36', '134', '下属未付款', '0', '0', 'downfkdwf|custfina', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('136', '36', '134', '下属已付款', '0', '0', 'downfkdyf|custfina', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('137', '36', '134', '下属所有', '0', '0', 'downfkd|custfina', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('138', '36', '0', '＋新增', '0', '1', 'add_custfina', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('139', '38', '0', '我的工资', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('140', '40', '0', '给我的订阅', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('141', '41', '0', '未查阅', '0', '0', 'mywcy', 'mywcy', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('142', '41', '0', '所有需查阅', '1', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('143', '42', '0', '我的发文', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('144', '42', '0', '＋拟办', '2', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('145', '42', '0', '发文查看', '1', '0', 'grant', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('146', '43', '0', '我的收文', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('147', '43', '0', '收文查看', '1', '0', 'grant', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('148', '43', '0', '＋收文登记', '3', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('149', '44', '0', '我未完成', '0', '0', 'wwc', 'wwc', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('150', '44', '0', '项目查看', '0', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('151', '44', '150', '我负责的', '1', '0', 'myfz', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('152', '44', '150', '我创建的', '2', '0', 'mycj', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('153', '44', '0', '创建项目', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('154', '44', '150', '我执行项目', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('155', '7', '14', '抄送给我', '2', '0', 'chaos', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('156', '15', '26', '抄送给我', '0', '0', 'chaos', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('157', '45', '0', '上月', '0', '0', 'prevmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('158', '45', '0', '当月', '0', '0', 'nowmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('159', '45', '0', '下月', '0', '0', 'nextmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('160', '46', '0', '我未回执确认', '0', '0', 'my', 'mywei', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('161', '46', '0', '我全部回执确认', '0', '0', 'myall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('162', '49', '0', '未读消息', '1', '0', 'wdtodo', 'wdtodo', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('163', '49', '0', '所有消息', '0', '0', 'myall', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('164', '49', '0', '全部标已读', '2', '0', 'allydu', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('165', '50', '0', '需我协作', '0', '0', 'myxie', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('166', '50', '0', '我可查看', '0', '0', 'myview', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('167', '50', '0', '新增', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('168', '52', '0', '发给我的', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('169', '52', '0', '我发布的', '0', '0', 'wfb', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('170', '52', '0', '新增', '0', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('171', '53', '0', '流程监控记录', '0', '0', 'jiankong', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('172', '53', '0', '我关注单据', '0', '0', 'follow', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('173', '56', '0', '计划跟进', '0', '0', 'mywwc', 'mywwc', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('174', '56', '0', '＋新增', '2', '1', 'add', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('175', '56', '0', '跟进查看', '1', '0', null, null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('176', '56', '175', '下属跟进', '0', '0', 'down', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('177', '56', '175', '我所有跟进', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('178', '12', '7', '下属任务', '0', '0', 'down', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('179', '12', '7', '下属未完成', '1', '0', 'downwwc', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('180', '12', '7', '下属已完成', '2', '0', 'downwc', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('181', '58', '0', '本月考勤', '0', '0', 'nowmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('182', '58', '0', '上月考勤表', '0', '0', 'prevmonth', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('183', '58', '0', '重新分析', '0', '0', 'refenxi', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('184', '59', '0', '我所有便笺', '0', '0', 'my', null, null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('185', '59', '0', '待完成的', '0', '0', 'mywfc', 'weiwc', null, null, null);
INSERT INTO `xinhu_im_menu` VALUES ('186', '59', '0', '新增', '0', '1', 'add', null, null, null, null);

-- ----------------------------
-- Table structure for `xinhu_im_mess`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_im_mess`;
CREATE TABLE `xinhu_im_mess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `optdt` datetime DEFAULT NULL,
  `zt` tinyint(1) DEFAULT '0' COMMENT '状态',
  `cont` varchar(4000) DEFAULT NULL COMMENT '内容',
  `sendid` smallint(6) DEFAULT NULL,
  `receid` smallint(6) DEFAULT '0' COMMENT '接收',
  `receuid` varchar(4000) DEFAULT NULL COMMENT '接收用户id',
  `type` varchar(20) DEFAULT NULL COMMENT '信息类型',
  `url` varchar(1000) DEFAULT NULL COMMENT '相关地址',
  `fileid` int(11) NOT NULL DEFAULT '0' COMMENT '对应文件Id',
  `msgid` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `optdt` (`optdt`,`receid`),
  KEY `msgid` (`msgid`),
  KEY `type` (`type`,`receid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='IM聊天记录表';

-- ----------------------------
-- Records of xinhu_im_mess
-- ----------------------------
INSERT INTO `xinhu_im_mess` VALUES ('1', '2021-10-29 13:43:00', '1', '5aSn5a625pyJ5ZWl6Zeu6aKY6L!Z6YeM6K!05ZWK77yB', '8', '2', '2,7,6,8,5,1,4', 'group', null, '0', null);

-- ----------------------------
-- Table structure for `xinhu_im_messzt`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_im_messzt`;
CREATE TABLE `xinhu_im_messzt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT NULL COMMENT '信息id',
  `uid` int(11) DEFAULT NULL COMMENT '人员id',
  `gid` smallint(6) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='IM聊天消息状态表';

-- ----------------------------
-- Records of xinhu_im_messzt
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_im_tonghua`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_im_tonghua`;
CREATE TABLE `xinhu_im_tonghua` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '官网用户id',
  `faid` int(11) DEFAULT '0' COMMENT '发起人id',
  `channel` varchar(50) NOT NULL DEFAULT '' COMMENT '房间号',
  `adddt` datetime DEFAULT NULL COMMENT '发起时间',
  `jiedt` datetime DEFAULT NULL COMMENT '接通时间',
  `enddt` datetime DEFAULT NULL COMMENT '结束时间',
  `type` tinyint(4) DEFAULT '0' COMMENT '0语音,1视频,2会议视频',
  `joinids` varchar(255) DEFAULT NULL COMMENT '参加人id',
  `plat` tinyint(4) DEFAULT '0' COMMENT '发起平台0声网,1腾讯云',
  `state` tinyint(4) DEFAULT '0' COMMENT '状态1接通,2拒接',
  `jieid` int(11) DEFAULT NULL COMMENT '结束人',
  PRIMARY KEY (`id`,`channel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='语音视频通话表';

-- ----------------------------
-- Records of xinhu_im_tonghua
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_infor`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_infor`;
CREATE TABLE `xinhu_infor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `optdt` datetime DEFAULT NULL,
  `typename` varchar(20) DEFAULT NULL,
  `content` text,
  `url` varchar(200) DEFAULT NULL,
  `receid` varchar(2000) DEFAULT NULL COMMENT '接收人Id',
  `recename` varchar(4000) DEFAULT NULL,
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL,
  `enddt` datetime DEFAULT NULL COMMENT '截止时间',
  `startdt` datetime DEFAULT NULL COMMENT '开始时间',
  `zuozhe` varchar(30) DEFAULT NULL COMMENT '发布者',
  `indate` date DEFAULT NULL COMMENT '日期',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `fengmian` varchar(100) DEFAULT NULL COMMENT '封面图片',
  `mintou` smallint(6) DEFAULT '0' COMMENT '至少投票',
  `maxtou` smallint(6) DEFAULT '0' COMMENT '最多投投票0不限制',
  `issms` tinyint(1) DEFAULT '0' COMMENT '是否发短信',
  `istop` tinyint(1) DEFAULT '0' COMMENT '排序号越大越靠前',
  `zstart` date DEFAULT NULL COMMENT '展示开始日期',
  `zsend` date DEFAULT NULL COMMENT '展示截止日期',
  `comid` smallint(6) DEFAULT '0' COMMENT '所在单位Id',
  `appxs` tinyint(1) DEFAULT '0' COMMENT 'app首页显示',
  `mtplid` int(11) DEFAULT '0' COMMENT '对应多模版flow_modetpl.id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='通知公告';

-- ----------------------------
-- Records of xinhu_infor
-- ----------------------------
INSERT INTO `xinhu_infor` VALUES ('1', '欢迎使用信呼工作台', '2018-04-26 17:27:10', '通知公告', '<p>\n	欢迎使用，有任何问题可以随时联系我们，帮你解答哦。&nbsp;\n</p>\n<p>\n	信呼是一款开源免费的工作台软件，可添加应用，管理，pc上，app都可以使用，我们的代码全部开放，可自由使用。\n</p>', null, null, null, '1', '管理员', null, null, '信呼开发团队', '2016-08-01', '1', null, '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_infor` VALUES ('2', '关于写日报制度说明', '2019-04-13 19:54:43', '规则制度', '<p>\n	1、每个人工作日在下班后必须写工作日报，全天请假可不用写，只要有上班就需要写，即使你上班1分钟。\n</p>\n<p>\n	2、也可以隔天写。\n</p>\n<p>\n	<strong>未写处罚</strong> \n</p>\n<p>\n	1、未写一次扣五块，累计加倍。\n</p>', null, 'u1,u2,u3,u4,u5,u6,u7,u8,u9', null, '1', '管理员', null, null, '人力行政部', '2016-08-01', '1', 'images/kqbanner1.jpg', '0', '0', '0', '0', null, null, '0', '0', '0');
INSERT INTO `xinhu_infor` VALUES ('6', '信呼更新发布V2.3.1版本', '2021-10-29 13:40:06', '通知公告', '<p>\n	版本添加了发布如下功能啊。\n</p>\n<p>\n	1、系统功能完善。\n</p>\n<p>\n	2、更新了很多，自己看<a href=\"http://www.rockoa.com/view_core.html\" target=\"_blank\">升级日志</a>。\n</p>', 'http://www.rockoa.com/view_down.html', 'd1', '信呼开发团队', '1', '管理员', null, null, '信呼开发团队', '2021-10-29', '1', 'images/logo.png', '0', '0', '0', '1', null, null, '1', '1', '0');
INSERT INTO `xinhu_infor` VALUES ('9', '你们觉得这个系统如何？', '2019-06-03 13:17:05', '通知公告', '开始投票拉。', null, 'd1', '信呼开发团队', '1', '管理员', '2018-08-31 12:42:00', '2017-08-26 12:42:00', '开发部', '2017-08-26', '1', null, '1', '1', '0', '0', '2019-04-15', null, '1', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_infors`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_infors`;
CREATE TABLE `xinhu_infors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表infor.id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `touitems` varchar(200) DEFAULT NULL COMMENT '投票选项',
  `touci` int(11) DEFAULT '0' COMMENT '得到票数',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='信息子表投票项';

-- ----------------------------
-- Records of xinhu_infors
-- ----------------------------
INSERT INTO `xinhu_infors` VALUES ('1', '9', '0', '好', '1', '0');
INSERT INTO `xinhu_infors` VALUES ('2', '9', '1', '很好', '3', '0');
INSERT INTO `xinhu_infors` VALUES ('3', '9', '2', '非常好', '4', '0');

-- ----------------------------
-- Table structure for `xinhu_knowledge`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_knowledge`;
CREATE TABLE `xinhu_knowledge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `typeid` smallint(6) DEFAULT '0' COMMENT '对应分类',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序',
  `content` text,
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL,
  `adddt` datetime DEFAULT NULL,
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='知识信息';

-- ----------------------------
-- Records of xinhu_knowledge
-- ----------------------------
INSERT INTO `xinhu_knowledge` VALUES ('1', '如果提供工作效率！', '199', '0', '<p>\n	1、加班。\n</p>\n<p>\n	2、加班。\n</p>\n<p>\n	3、在加班。\n</p>', '2018-09-10 11:22:42', '管理员', '2016-10-24 22:05:24', '0');
INSERT INTO `xinhu_knowledge` VALUES ('2', '公司的介绍', '453', '0', '介绍', '2019-06-05 18:52:43', '大乔', '2019-06-05 18:52:43', '2');

-- ----------------------------
-- Table structure for `xinhu_knowtiku`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_knowtiku`;
CREATE TABLE `xinhu_knowtiku` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL COMMENT '标题',
  `typeid` smallint(6) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '0单选,1多选,2判断题',
  `ana` varchar(300) DEFAULT NULL COMMENT '答案A',
  `anb` varchar(300) DEFAULT NULL COMMENT '答案B',
  `anc` varchar(300) DEFAULT NULL COMMENT '答案C',
  `and` varchar(300) DEFAULT NULL COMMENT '答案D',
  `ane` varchar(300) DEFAULT NULL COMMENT '答案E',
  `answer` varchar(10) DEFAULT NULL,
  `sort` smallint(6) DEFAULT '0',
  `adddt` datetime DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `explain` varchar(500) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `content` varchar(1000) DEFAULT NULL,
  `optid` smallint(6) DEFAULT '0',
  `imgurl` varchar(100) DEFAULT NULL COMMENT '相关图片地址',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='知识题库';

-- ----------------------------
-- Records of xinhu_knowtiku
-- ----------------------------
INSERT INTO `xinhu_knowtiku` VALUES ('1', '信呼要求PHP最低版本', '227', '0', '5.2', '5.3', '5.4', '5.5', null, 'B', '0', '2019-06-05 22:17:56', '2019-06-05 22:17:56', '详见官网说明使用', '1', null, '1', null, '1');
INSERT INTO `xinhu_knowtiku` VALUES ('2', '信呼要求MySql最低版本', '227', '0', '5', '5.3', '5.4', '5.5', null, 'D', '0', '2019-06-05 22:17:56', '2019-06-05 23:22:40', '详见官网说明使用', '1', '我们知道mysql有很多版本，选系统适用是最好的。', '1', null, '1');
INSERT INTO `xinhu_knowtiku` VALUES ('3', 'PHP如何连接数据库方式', '295', '1', 'mysql', 'mysqli', 'pdo', 'mssql', 'com', 'ABC', '0', '2019-06-05 22:17:56', '2019-06-05 22:17:56', 'D选项为别的数据库', '1', null, '1', null, '1');
INSERT INTO `xinhu_knowtiku` VALUES ('4', '信呼官网域名是rockoa.com', '227', '2', '正确', '错误', null, null, null, 'A', '0', '2019-06-05 22:17:56', '2019-06-05 22:17:56', null, '1', null, '1', null, '1');
INSERT INTO `xinhu_knowtiku` VALUES ('5', '信呼要求PHP最低版本', '454', '0', '5.2', '5.3', '5.4', '5.5', null, 'B', '0', '2019-06-05 22:18:58', '2019-06-05 22:18:58', '详见官网说明使用', '1', null, '4', null, '2');
INSERT INTO `xinhu_knowtiku` VALUES ('6', '信呼要求MySql最低版本', '454', '0', '5', '5.3', '5.4', '5.5', null, 'D', '0', '2019-06-05 22:18:58', '2019-06-05 22:18:58', '详见官网说明使用', '1', null, '4', null, '2');
INSERT INTO `xinhu_knowtiku` VALUES ('7', 'PHP如何连接数据库方式', '456', '1', 'mysql', 'mysqli', 'pdo', 'mssql', 'com', 'ABC', '0', '2019-06-05 22:18:58', '2019-06-05 22:18:58', 'D选项为别的数据库', '1', null, '4', null, '2');
INSERT INTO `xinhu_knowtiku` VALUES ('8', '信呼官网域名是rockoa.com', '454', '2', '正确', '错误', null, null, null, 'A', '0', '2019-06-05 22:18:58', '2019-06-05 22:18:58', null, '1', null, '4', null, '2');

-- ----------------------------
-- Table structure for `xinhu_knowtraim`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_knowtraim`;
CREATE TABLE `xinhu_knowtraim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `optid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `optdt` datetime DEFAULT NULL,
  `dxshu` smallint(6) DEFAULT '0' COMMENT '多选题目数量',
  `dsshu` smallint(6) DEFAULT '0' COMMENT '单选题目数量',
  `pdshu` smallint(6) DEFAULT '0' COMMENT '判断题数',
  `reshu` smallint(6) DEFAULT '0' COMMENT '培训人数',
  `startdt` datetime DEFAULT NULL COMMENT '开始时间',
  `enddt` datetime DEFAULT NULL COMMENT '截止时间',
  `kstime` smallint(6) DEFAULT '0' COMMENT '考试时间(分钟)',
  `ydshu` smallint(6) DEFAULT '0' COMMENT '已答题人数',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `receid` varchar(200) DEFAULT NULL,
  `recename` varchar(200) DEFAULT NULL COMMENT '发给谁培训',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `state` tinyint(1) DEFAULT '0' COMMENT '0还没开始,1考试中,2已结束',
  `zfenshu` int(11) DEFAULT '0' COMMENT '总分',
  `hgfen` int(11) DEFAULT '0' COMMENT '合格分数',
  `tikuid` varchar(200) DEFAULT NULL,
  `tikuname` varchar(200) DEFAULT NULL COMMENT '对应题库',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='培训表出题考试';

-- ----------------------------
-- Records of xinhu_knowtraim
-- ----------------------------
INSERT INTO `xinhu_knowtraim` VALUES ('1', '培训开始了', '4', '大乔', '2019-06-05 23:43:36', '1', '2', '1', '3', '2019-06-05 23:43:35', '2019-06-05 23:56:00', '10', '1', '1', 'd3', '财务部', null, '2', '100', '60', null, null, '2');

-- ----------------------------
-- Table structure for `xinhu_knowtrais`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_knowtrais`;
CREATE TABLE `xinhu_knowtrais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `kssdt` datetime DEFAULT NULL COMMENT '考试时间',
  `ksedt` datetime DEFAULT NULL COMMENT '考试结束时间',
  `fenshu` smallint(6) DEFAULT '0' COMMENT '得分',
  `kstime` int(11) DEFAULT '0' COMMENT '考试时间(秒数)',
  `isks` tinyint(1) DEFAULT '0' COMMENT '是否已考试',
  `tkids` varchar(2000) DEFAULT NULL COMMENT '考试题目id',
  `dyids` varchar(1000) DEFAULT NULL COMMENT '我做题得到答案',
  `dyjgs` varchar(1000) DEFAULT NULL COMMENT '答题结果0未答,1正确,2错误',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mid` (`mid`,`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='考试培训子表';

-- ----------------------------
-- Records of xinhu_knowtrais
-- ----------------------------
INSERT INTO `xinhu_knowtrais` VALUES ('1', '1', '1', null, null, '0', '0', '0', null, null, null, '2');
INSERT INTO `xinhu_knowtrais` VALUES ('2', '1', '2', null, null, '0', '0', '0', null, null, null, '2');
INSERT INTO `xinhu_knowtrais` VALUES ('3', '1', '3', null, null, '0', '0', '0', null, null, null, '2');

-- ----------------------------
-- Table structure for `xinhu_kqanay`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqanay`;
CREATE TABLE `xinhu_kqanay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` date DEFAULT NULL COMMENT '日期',
  `uid` smallint(6) DEFAULT NULL COMMENT '人员id',
  `ztname` varchar(20) DEFAULT NULL COMMENT '状态名称',
  `time` datetime DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL COMMENT '状态名称',
  `states` varchar(20) DEFAULT NULL COMMENT '其他状态,如请假',
  `sort` smallint(6) DEFAULT '0',
  `iswork` tinyint(4) DEFAULT '1' COMMENT '是否工作日',
  `emiao` int(11) DEFAULT '0' COMMENT '秒数',
  `optdt` datetime DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `timesb` decimal(6,1) DEFAULT '0.0' COMMENT '应上班时间(小时)',
  `timeys` decimal(6,1) DEFAULT '0.0' COMMENT '已上班时间(小时)',
  PRIMARY KEY (`id`),
  KEY `uiddt` (`uid`,`dt`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考勤分析';

-- ----------------------------
-- Records of xinhu_kqanay
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_kqdist`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqdist`;
CREATE TABLE `xinhu_kqdist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recename` varchar(1000) DEFAULT NULL COMMENT '适用对象',
  `receid` varchar(1000) DEFAULT NULL,
  `mid` smallint(6) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '0考勤时间,1休息,2定位的',
  `startdt` date DEFAULT NULL,
  `enddt` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `sort` int(11) DEFAULT '0' COMMENT '排序号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='考勤分配表';

-- ----------------------------
-- Records of xinhu_kqdist
-- ----------------------------
INSERT INTO `xinhu_kqdist` VALUES ('1', '信呼开发团队', 'd1', '1', '0', '2010-01-01', '2021-09-30', '1', '1');
INSERT INTO `xinhu_kqdist` VALUES ('4', '信呼开发团队', 'd1', '1', '1', '2014-08-01', '2021-08-31', '1', '0');
INSERT INTO `xinhu_kqdist` VALUES ('5', '信呼开发团队', 'd1', '1', '2', '2016-11-01', '2050-12-31', '1', '0');
INSERT INTO `xinhu_kqdist` VALUES ('6', '信呼客服', 'u8', '33', '0', '2017-05-01', '2017-05-16', '1', '0');
INSERT INTO `xinhu_kqdist` VALUES ('7', '管理员组', 'g4', '21', '0', '2019-04-01', '2019-04-01', '1', '0');
INSERT INTO `xinhu_kqdist` VALUES ('8', '开发部', 'd2', '2', '2', '2017-05-01', '2019-05-01', '0', '0');
INSERT INTO `xinhu_kqdist` VALUES ('9', '管理员', 'u1', '30', '0', '2021-08-01', '2022-07-31', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_kqdisv`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqdisv`;
CREATE TABLE `xinhu_kqdisv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plx` tinyint(1) DEFAULT '1' COMMENT '1组排班,2人员',
  `receid` int(11) DEFAULT '0' COMMENT '组id,人员Id',
  `dt` date DEFAULT NULL COMMENT '日期',
  `type` tinyint(1) DEFAULT '0' COMMENT '0考勤时间,1休息,2工作日',
  `mid` int(11) DEFAULT '0' COMMENT '对应主ID',
  PRIMARY KEY (`id`),
  KEY `receid` (`plx`,`receid`,`dt`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考勤排班';

-- ----------------------------
-- Records of xinhu_kqdisv
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_kqdkjl`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqdkjl`;
CREATE TABLE `xinhu_kqdkjl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `dkdt` datetime DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '0在线打卡,1考勤机,2手机定位,3手动添加,4异常添加,5数据导入,6接口导入',
  `address` varchar(200) DEFAULT NULL COMMENT '定位地址',
  `lat` varchar(20) DEFAULT NULL COMMENT '纬度',
  `lng` varchar(20) DEFAULT NULL COMMENT '经度',
  `accuracy` smallint(6) DEFAULT '0' COMMENT '精确范围',
  `ip` varchar(100) DEFAULT NULL,
  `mac` varchar(100) DEFAULT NULL,
  `explain` varchar(200) DEFAULT NULL,
  `imgpath` varchar(100) DEFAULT NULL COMMENT '相关图片',
  `snid` int(11) DEFAULT '0' COMMENT '考勤机设备id',
  `sntype` tinyint(4) DEFAULT '0' COMMENT '考勤机打卡方式0密码,1指纹,2刷卡',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`dkdt`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='打卡记录';

-- ----------------------------
-- Records of xinhu_kqdkjl
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_kqdw`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqdw`;
CREATE TABLE `xinhu_kqdw` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '名称',
  `location_x` varchar(20) DEFAULT NULL,
  `location_y` varchar(20) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL COMMENT '位置名称',
  `precision` int(11) DEFAULT '0' COMMENT '允许误差米',
  `scale` smallint(6) DEFAULT '0',
  `wifiname` varchar(100) DEFAULT NULL COMMENT '打卡wifi名',
  `iswgd` tinyint(1) DEFAULT '0' COMMENT '是否为无固定地点',
  `dwids` varchar(50) DEFAULT NULL COMMENT '关联对应ID,实现多点定位',
  `ispz` tinyint(1) DEFAULT '0' COMMENT '是否需要拍照才能打卡',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='考勤定位规则';

-- ----------------------------
-- Records of xinhu_kqdw
-- ----------------------------
INSERT INTO `xinhu_kqdw` VALUES ('1', '厦门默认位置', '24.528153', '118.167806', '厦门', '0', '12', null, '1', null, '1');

-- ----------------------------
-- Table structure for `xinhu_kqerr`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqerr`;
CREATE TABLE `xinhu_kqerr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `uname` varchar(30) DEFAULT NULL COMMENT '申请人',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `errtype` varchar(10) DEFAULT NULL COMMENT '异常类型',
  `dt` date DEFAULT NULL COMMENT '异常日期',
  `ytime` varchar(10) DEFAULT NULL COMMENT '应打卡时间',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='打卡异常申请';

-- ----------------------------
-- Records of xinhu_kqerr
-- ----------------------------
INSERT INTO `xinhu_kqerr` VALUES ('1', '1', null, '2016-12-16 20:54:27', '1', '管理员', '2016-12-16', '忘记了', '1', '1', '忘记打卡', '2016-12-16', '18:00:28', '0');
INSERT INTO `xinhu_kqerr` VALUES ('2', '2', null, '2017-08-28 12:19:12', '2', '貂蝉', '2017-08-28', 'hehehe', '1', '1', '忘记打卡', '2017-08-24', '18:19:04', '0');
INSERT INTO `xinhu_kqerr` VALUES ('4', '4', '大乔', '2019-06-06 18:23:56', '4', '大乔', '2019-06-06', '我', '0', '1', '忘记打卡', '2019-06-04', '18:23:50', '2');

-- ----------------------------
-- Table structure for `xinhu_kqinfo`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqinfo`;
CREATE TABLE `xinhu_kqinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `uname` varchar(20) DEFAULT NULL,
  `stime` datetime DEFAULT NULL,
  `etime` datetime DEFAULT NULL,
  `kind` varchar(10) DEFAULT NULL COMMENT '类型',
  `qjkind` varchar(20) DEFAULT NULL COMMENT '请假类型',
  `explain` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `totals` decimal(6,1) DEFAULT '0.0' COMMENT '时间',
  `optdt` datetime DEFAULT NULL,
  `isturn` tinyint(1) DEFAULT '0' COMMENT '是否提交',
  `optname` varchar(20) DEFAULT NULL,
  `optid` smallint(6) DEFAULT '0',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `jiafee` decimal(6,2) DEFAULT '0.00' COMMENT '加班费',
  `jiatype` tinyint(1) DEFAULT '0' COMMENT '加班方式0换调休,1给加班费',
  `totday` decimal(8,2) DEFAULT '0.00' COMMENT '请假天数',
  `enddt` datetime DEFAULT NULL COMMENT '截止使用时间',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `kind` (`kind`)
) ENGINE=MyISAM AUTO_INCREMENT=160 DEFAULT CHARSET=utf8 COMMENT='请假条加班单';

-- ----------------------------
-- Records of xinhu_kqinfo
-- ----------------------------
INSERT INTO `xinhu_kqinfo` VALUES ('1', '1', null, '2016-07-29 10:52:51', '2016-07-29 18:52:54', '请假', '事假', '下午请假去骑车兜风啊！', '1', '6.0', '2016-07-29 10:53:11', '1', '管理员', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('2', '1', null, '2016-07-27 19:00:00', '2016-07-27 23:00:00', '加班', null, '老板说我能做事就多加班，呵呵呵！', '1', '4.0', '2016-07-29 10:56:58', '1', '管理员', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('3', '6', null, '2016-08-08 09:00:00', '2016-08-08 18:36:40', '请假', '年假', '辛辛苦苦一年终于有一天年假啦，请一下吧！', '1', '8.0', '2016-08-07 18:37:02', '1', '张飞', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('4', '1', null, '2016-08-30 09:00:00', '2016-08-31 18:00:00', '请假', '事假', '年底年假休息！', '1', '16.0', '2016-09-13 16:05:38', '1', '管理员', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('5', '1', null, '2016-08-25 08:00:00', '2016-08-25 18:01:35', '请假', '事假', '请假1条', '1', '8.0', '2016-08-25 16:01:52', '1', '管理员', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('6', '8', null, '2016-08-01 08:44:45', '2016-08-02 22:44:54', '请假', '事假', '请假2天去玩喽', '1', '24.0', '2016-08-26 22:45:35', '1', '信呼客服', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('7', '1', null, '2016-08-28 14:06:25', '2016-08-28 16:06:33', '加班', null, '人傻要多加班！', '1', '2.0', '2016-08-27 14:06:56', '1', '管理员', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('8', '6', null, '2016-08-02 08:02:21', '2016-08-02 20:02:23', '请假', '事假', '请假啦', '1', '8.0', '2016-08-30 20:12:33', '1', '张飞', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('9', '1', null, '2016-09-08 17:45:57', '2016-09-09 17:46:00', '请假', '事假', '嗯嗯嗯。', '5', '9.0', '2016-09-08 21:14:14', '1', '管理员', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('122', '1', '管理员', '2017-06-01 00:00:00', '2017-06-01 23:59:59', '增加年假', null, '2016-06-01入职年限满1年添加年假5天', '1', '40.0', '2018-08-06 22:24:53', '1', '系统', '0', '2018-08-06', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('11', '6', null, '2016-09-27 08:36:19', '2016-09-30 23:36:27', '请假', '事假', '国庆请假啦', '5', '32.0', '2016-09-25 16:04:13', '1', '张飞', '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('14', '1', null, '2016-12-13 14:00:00', '2016-12-13 18:00:00', '请假', '调休', '调休啦', '1', '4.0', '2017-07-25 22:09:21', '1', '管理员', '1', null, '0.00', '0', '0.25', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('15', '1', null, '2017-04-17 18:00:00', '2017-04-18 01:00:00', '加班', null, '加班很晚，很辛苦的，我要很多很多钱。', '1', '7.0', '2017-05-18 12:17:51', '1', '管理员', '1', '2017-05-18', '501.20', '1', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('16', '1', null, '2017-06-28 18:00:00', '2017-06-29 00:00:00', '加班', null, '加班加班', '1', '6.0', '2017-06-29 10:14:05', '1', '管理员', '1', '2017-06-29', '354.00', '1', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('141', '5', '磐石', '2018-11-01 00:00:00', '2018-11-01 23:59:59', '增加年假', null, '2015-11-01入职年限满3年添加年假5天', '1', '40.0', '2018-12-01 02:30:02', '1', '系统', '0', '2018-12-01', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('126', '3', '小乔', '2018-05-02 00:00:00', '2018-05-02 23:59:59', '增加年假', null, '2017-05-02入职年限满1年添加年假5天', '1', '40.0', '2018-08-06 22:25:26', '1', '系统', '0', '2018-08-06', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('127', '4', '大乔', '2018-07-01 00:00:00', '2018-07-01 23:59:59', '增加年假', null, '2017-07-01入职年限满1年添加年假5天', '1', '40.0', '2018-08-06 22:25:26', '1', '系统', '0', '2018-08-06', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('128', '6', '张飞', '2018-07-01 00:00:00', '2018-07-01 23:59:59', '增加年假', null, '2017-07-01入职年限满1年添加年假5天', '1', '40.0', '2018-08-06 22:25:26', '1', '系统', '0', '2018-08-06', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('129', '7', '赵子龙', '2018-07-01 00:00:00', '2018-07-01 23:59:59', '增加年假', null, '2016-07-01入职年限满2年添加年假5天', '1', '40.0', '2018-08-06 22:25:26', '1', '系统', '0', '2018-08-06', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('123', '7', '赵子龙', '2017-07-01 00:00:00', '2017-07-01 23:59:59', '增加年假', null, '2016-07-01入职年限满1年添加年假5天', '1', '40.0', '2018-08-06 22:24:53', '1', '系统', '0', '2018-08-06', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('103', '3', '小乔', '2018-06-14 08:00:00', '2018-06-14 18:47:00', '请假', '事假', '外出的有时哦', '0', '8.0', '2020-09-27 19:47:18', '1', '小乔', '3', '2018-06-13', '0.00', '0', '1.00', null, '2');
INSERT INTO `xinhu_kqinfo` VALUES ('104', '4', '大乔', '2018-06-13 20:30:00', '2018-06-13 21:31:00', '加班', null, '加班招人面试。', '1', '1.0', '2018-08-20 10:59:14', '1', '管理员', '1', '2018-06-15', '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('140', '8', '信呼客服', '2018-07-01 00:00:00', '2018-07-01 23:59:59', '增加年假', null, '2016-07-01入职年限满2年添加年假5天', '1', '40.0', '2018-09-21 21:58:36', '1', '系统', '0', '2018-09-21', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('131', '1', '管理员', '2018-08-06 15:56:00', '2018-08-06 23:56:00', '加班', null, '哈哈哈哈', '1', '8.0', '2018-08-07 15:57:02', '1', '管理员', '1', '2018-08-07', '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('139', '2', '貂蝉', '2018-08-09 00:00:00', '2018-08-09 23:59:59', '增加年假', null, '2016-08-09入职年限满2年添加年假5天', '1', '40.0', '2018-09-21 21:58:36', '1', '系统', '0', '2018-09-21', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('138', '1', '管理员', '2018-06-01 00:00:00', '2018-06-01 23:59:59', '增加年假', null, '2016-06-01入职年限满2年添加年假5天', '1', '40.0', '2018-09-21 21:58:36', '1', '系统', '0', '2018-09-21', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('109', '4', '大乔', '2018-07-28 21:52:00', '2018-07-29 21:52:00', '请假', '事假', '请假喽', '0', '8.0', '2018-07-28 21:52:21', '1', '大乔', '4', '2018-07-28', '0.00', '0', '1.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('110', '3', '小乔', '2018-07-28 21:54:52', '2018-07-29 21:54:00', '请假', '事假', '额', '1', '8.0', '2018-07-28 21:55:00', '1', '小乔', '3', '2018-07-28', '0.00', '0', '1.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('132', '5', '磐石', '2018-08-07 17:17:00', '2018-08-15 17:17:00', '增加年假', null, null, '1', '1.0', '2018-08-07 17:17:17', '1', '管理员', '1', '2018-08-07', '0.00', '0', '0.13', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('134', '3', '小乔', '2018-09-01 09:00:00', '2018-09-30 09:34:00', '增加哺乳假', null, null, '1', '8.0', '2018-09-07 09:35:54', '1', '管理员', '1', '2018-09-07', '0.00', '0', '1.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('142', '1', '管理员', '2019-01-31 09:00:00', '2019-01-31 12:00:00', '请假', '事假', '请假', '1', '3.0', '2019-02-01 10:28:14', '1', '管理员', '1', '2019-02-01', '0.00', '0', '0.38', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('143', '1', null, '2019-05-09 14:30:00', '2019-05-09 18:00:00', '请假', '事假', null, '1', '7.0', null, '1', null, '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('144', '1', null, '2019-01-02 14:00:00', '2019-02-02 18:00:00', '加班', null, null, '1', '8.0', null, '1', null, '0', null, '0.00', '0', '0.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('145', '1', '管理员', '2019-05-09 08:00:00', '2019-05-11 18:00:00', '请假', '调休', '23', '1', '3.0', '2019-02-18 14:23:08', '1', '管理员', '1', '2019-02-18', '0.00', '0', '0.06', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('146', '3', '小乔', '2019-05-02 00:00:00', '2019-05-02 23:59:59', '增加年假', null, '2017-05-02入职年限满2年添加年假5天', '1', '40.0', '2019-05-07 15:52:08', '1', '系统', '0', '2019-05-07', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('147', '1', '管理员', '2019-05-10 08:41:00', '2019-05-10 12:41:00', '请假', '事假', '请假', '1', '3.0', '2019-05-12 18:41:46', '1', '管理员', '1', '2019-05-12', '0.00', '0', '0.38', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('148', '6', '张飞', '2019-07-01 00:00:00', '2020-07-01 00:00:00', '增加年假', null, '奖励2天年假', '1', '16.0', '2019-07-01 23:31:45', '1', '管理员', '1', '2019-07-01', '0.00', '0', '2.00', null, '1');
INSERT INTO `xinhu_kqinfo` VALUES ('149', '7', '赵子龙', '2019-07-01 08:02:00', '2020-07-01 18:10:10', '增加陪产假', null, '陪产假男性，一般15天是要一次休完', '1', '120.0', '2019-07-01 23:31:45', '1', '管理员', '1', '2019-07-01', '0.00', '0', '15.00', null, '1');
INSERT INTO `xinhu_kqinfo` VALUES ('150', '1', '管理员', '2019-06-01 00:00:00', '2019-06-01 23:59:59', '增加年假', null, '2016-06-01入职年限满3年添加年假5天', '1', '40.0', '2019-07-04 02:30:06', '1', '系统', '0', '2019-07-04', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('151', '4', '大乔', '2019-07-01 00:00:00', '2019-07-01 23:59:59', '增加年假', null, '2017-07-01入职年限满2年添加年假5天', '1', '40.0', '2019-07-04 02:30:06', '1', '系统', '0', '2019-07-04', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('152', '6', '张飞', '2019-07-01 00:00:00', '2019-07-01 23:59:59', '增加年假', null, '2017-07-01入职年限满2年添加年假5天', '1', '40.0', '2019-07-04 02:30:06', '1', '系统', '0', '2019-07-04', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('153', '7', '赵子龙', '2019-07-01 00:00:00', '2019-07-01 23:59:59', '增加年假', null, '2016-07-01入职年限满3年添加年假5天', '1', '40.0', '2019-07-04 02:30:06', '1', '系统', '0', '2019-07-04', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('154', '2', '貂蝉', '2019-08-09 00:00:00', '2019-08-09 23:59:59', '增加年假', null, '2016-08-09入职年限满3年添加年假5天', '1', '40.0', '2019-08-15 02:30:03', '1', '系统', '0', '2019-08-15', '0.00', '0', '5.00', null, '0');
INSERT INTO `xinhu_kqinfo` VALUES ('155', '1', '管理员', '2019-08-26 18:20:00', '2019-08-26 22:20:00', '加班', null, '好的', '1', '4.0', '2019-08-26 18:20:43', '1', '管理员', '1', '2019-08-26', '0.00', '0', '0.00', null, '1');
INSERT INTO `xinhu_kqinfo` VALUES ('157', '2', '貂蝉', '2020-07-01 13:59:00', '2020-07-31 13:59:00', '增加调休', null, null, '1', '8.0', '2020-07-16 13:59:49', '1', '管理员', '1', '2020-07-16', '0.00', '0', '1.00', null, '1');
INSERT INTO `xinhu_kqinfo` VALUES ('158', '1', '管理员', '2020-11-16 12:24:00', '2020-11-17 04:24:00', '加班', null, '啦啦啦', '1', '16.0', '2020-11-16 12:28:24', '1', '管理员', '1', '2020-11-16', '0.00', '0', '0.00', null, '1');

-- ----------------------------
-- Table structure for `xinhu_kqjcmd`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqjcmd`;
CREATE TABLE `xinhu_kqjcmd` (
  `id` int(11) NOT NULL,
  `snid` int(11) DEFAULT '0' COMMENT '设备ID',
  `cmd` text COMMENT '发送命令内容',
  `status` tinyint(1) DEFAULT '0' COMMENT '运行状态0待运行,1已运行,2请求中',
  `qjtime` datetime DEFAULT NULL COMMENT '请求时间',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `cjtime` datetime DEFAULT NULL COMMENT '处理时间',
  `atype` varchar(30) DEFAULT NULL COMMENT '类型',
  `others` varchar(500) DEFAULT NULL COMMENT '推送主键id',
  PRIMARY KEY (`id`),
  KEY `snid` (`snid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考勤机命令发送表';

-- ----------------------------
-- Records of xinhu_kqjcmd
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_kqjsn`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqjsn`;
CREATE TABLE `xinhu_kqjsn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` varchar(50) NOT NULL DEFAULT '' COMMENT '设备号',
  `name` varchar(50) DEFAULT NULL COMMENT '设备名称',
  `company` varchar(50) DEFAULT NULL COMMENT '公司名',
  `sort` int(11) DEFAULT '0' COMMENT '排序号',
  `optdt` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `deptids` varchar(4000) DEFAULT NULL COMMENT '部门ID聚合',
  `userids` text COMMENT '人员ID聚合',
  `lastdt` datetime DEFAULT NULL COMMENT '最后请求时间',
  `space` int(11) DEFAULT '0' COMMENT 'sd卡剩余空间',
  `memory` int(11) DEFAULT '0' COMMENT '剩余内存',
  `usershu` int(11) DEFAULT '0' COMMENT '人员数',
  `fingerprintshu` int(11) DEFAULT '0' COMMENT '指纹数',
  `headpicshu` int(11) DEFAULT '0' COMMENT '头像数量',
  `clockinshu` int(11) DEFAULT '0' COMMENT '打卡数',
  `picshu` int(11) DEFAULT '0' COMMENT '现场照片数',
  `romver` varchar(30) DEFAULT NULL COMMENT '系统版本',
  `appver` varchar(30) DEFAULT NULL COMMENT '应用版本',
  `model` varchar(30) DEFAULT NULL COMMENT '设备型号',
  `pinpai` tinyint(4) DEFAULT '0' COMMENT '品牌0群英,1中控',
  `snip` varchar(30) DEFAULT NULL COMMENT '分配的ip',
  `snport` varchar(10) DEFAULT NULL COMMENT '分配端口号',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`,`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考勤机设备表';

-- ----------------------------
-- Records of xinhu_kqjsn
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_kqjuser`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqjuser`;
CREATE TABLE `xinhu_kqjuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `snid` int(11) DEFAULT '0' COMMENT '设备ID',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `fingerprint1` text COMMENT '指纹1',
  `fingerprint2` text COMMENT '指纹2',
  `headpic` varchar(100) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`),
  UNIQUE KEY `snid_uid` (`snid`,`uid`) USING BTREE,
  KEY `snid` (`snid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考勤机上人员';

-- ----------------------------
-- Records of xinhu_kqjuser
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_kqout`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqout`;
CREATE TABLE `xinhu_kqout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT NULL,
  `applyname` varchar(30) DEFAULT NULL COMMENT '申请人姓名',
  `outtime` datetime DEFAULT NULL COMMENT '外出时间',
  `intime` datetime DEFAULT NULL COMMENT '回岗时间',
  `address` varchar(50) DEFAULT NULL COMMENT '外出地址',
  `reason` varchar(500) DEFAULT NULL COMMENT '外出事由',
  `atype` varchar(20) DEFAULT NULL COMMENT '外出类型@外出,出差',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '@0|待审核,1|审核通过,2|审核不通过',
  `isturn` tinyint(1) DEFAULT '0' COMMENT '@0|未提交,1|提交',
  `optid` int(11) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `isxj` tinyint(1) DEFAULT '0' COMMENT '是否销假@0|否,1|是',
  `sicksm` varchar(500) DEFAULT NULL COMMENT '销假说明',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='外出出差';

-- ----------------------------
-- Records of xinhu_kqout
-- ----------------------------
INSERT INTO `xinhu_kqout` VALUES ('1', '1', null, '2016-09-27 08:00:00', '2016-09-27 15:22:42', '火车站', '接人啊', '外出', null, '2016-08-27 15:22:51', '1', '1', '1', '管理员', '2016-08-27', '0', null, '0');
INSERT INTO `xinhu_kqout` VALUES ('2', '8', null, '2016-11-08 18:52:28', '2016-11-08 21:52:29', '湖里税务局', '税务局办税', '外出', null, '2016-11-08 20:52:41', '1', '1', '8', '信呼客服', '2016-11-08', '0', null, '0');
INSERT INTO `xinhu_kqout` VALUES ('3', '1', null, '2017-04-19 12:56:00', '2017-04-19 19:56:00', '高崎机场', '接信呼作者[磐石]来厦喝茶。', '外出', null, '2017-04-19 12:57:28', '1', '1', '1', '管理员', '2017-04-19', '0', null, '0');
INSERT INTO `xinhu_kqout` VALUES ('4', '1', null, '2017-08-28 11:54:25', '2017-08-28 12:54:00', '出去办事', '嗯嗯嗯额', '外出', null, '2017-08-28 11:54:41', '1', '1', '1', '管理员', '2017-08-28', '0', null, '0');
INSERT INTO `xinhu_kqout` VALUES ('5', '2', '貂蝉', '2018-06-19 08:00:00', '2018-06-19 12:00:00', '税务局', '信呼团队报税。', '外出', null, '2018-06-15 20:28:19', '1', '1', '1', '管理员', '2018-06-15', '0', null, '0');

-- ----------------------------
-- Table structure for `xinhu_kqsjgz`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqsjgz`;
CREATE TABLE `xinhu_kqsjgz` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `pid` smallint(6) DEFAULT '0' COMMENT '主',
  `stime` varchar(20) DEFAULT NULL COMMENT '开始时间',
  `etime` varchar(20) DEFAULT NULL COMMENT '结束时间',
  `qtype` tinyint(1) DEFAULT '0' COMMENT '取值类型@0|最小值,1|最大值',
  `iskt` tinyint(1) DEFAULT '0' COMMENT '是否跨天',
  `iskq` tinyint(1) DEFAULT '1' COMMENT '是否需要考勤',
  `isxx` tinyint(1) DEFAULT '0' COMMENT '是否休息断',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='考勤时间规则';

-- ----------------------------
-- Records of xinhu_kqsjgz
-- ----------------------------
INSERT INTO `xinhu_kqsjgz` VALUES ('1', '全体人员考勤', '1', '0', null, null, '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('2', '下班', '3', '1', '13:00:00', '18:00:00', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('5', '正常', '0', '12', '06:00:00', '09:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('6', '迟到', '1', '12', '09:00:01', '12:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('7', '正常', '0', '2', '18:00:00', '23:59:59', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('8', '早退', '1', '2', '13:00:00', '17:59:59', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('12', '上班', '0', '1', '09:00:00', '12:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('21', '中班(8点-16点)', '3', '0', null, null, '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('22', '上班', '0', '21', '08:00:00', '12:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('23', '下班', '1', '21', '12:00:00', '16:00:00', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('24', '正常', '0', '22', '06:00:00', '08:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('25', '迟到', '1', '22', '08:00:01', '12:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('26', '早退', '1', '23', '12:00:01', '15:59:59', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('27', '正常', '0', '23', '16:00:00', '18:00:00', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('30', '晚班(16点-24点)', '4', '0', null, null, '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('31', '上班', '0', '30', '16:00:00', '20:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('32', '下班', '1', '30', '20:00:00', '23:59:59', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('33', '早班(0点-8点)', '2', '0', null, null, '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('34', '上班', '0', '33', '00:00:00', '04:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('35', '下班', '0', '33', '04:00:00', '08:00:00', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('36', '正常', '0', '34', '22:00:00', '00:00:00', '0', '2', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('37', '迟到', '0', '34', '00:00:01', '02:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('38', '正常', '0', '35', '08:00:00', '10:00:00', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('39', '早退', '0', '35', '04:00:00', '07:59:59', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('40', '正常', '0', '31', '14:00:00', '16:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('41', '迟到', '0', '31', '16:00:01', '20:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('42', '正常', '0', '32', '00:00:00', '02:00:00', '1', '1', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('43', '早退', '0', '32', '20:00:00', '23:59:59', '1', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('44', '全体人员4状态考勤', '5', '0', null, null, '0', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('45', '上午上班', '0', '44', '08:00:00', '12:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('46', '上午下班', '1', '44', '12:00:00', '14:30:00', '0', '0', '1', '1');
INSERT INTO `xinhu_kqsjgz` VALUES ('47', '下午上班', '2', '44', '14:30:00', '18:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('48', '下午下班', '3', '44', '18:00:00', '23:59:59', '0', '0', '1', '1');
INSERT INTO `xinhu_kqsjgz` VALUES ('49', '正常', '0', '45', '06:00:00', '08:00:00', '0', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('50', '迟到', '1', '45', '08:00:01', '12:00:00', '0', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('51', '正常', '0', '46', '12:00:00', '14:30:00', '0', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('52', '早退', '0', '46', '08:00:01', '11:59:59', '1', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('53', '正常', '0', '47', '12:00:01', '14:30:00', '0', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('54', '迟到', '0', '47', '14:30:00', '18:00:00', '0', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('55', '正常', '0', '48', '18:00:00', '23:59:59', '1', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('56', '早退', '0', '48', '14:30:01', '17:59:59', '1', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('57', '半天班', '6', '0', null, null, '0', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('58', '上班', '0', '57', '09:00:00', '12:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('59', '下班', '1', '57', '12:00:00', '23:59:59', '0', '0', '1', '1');
INSERT INTO `xinhu_kqsjgz` VALUES ('60', '正常', '0', '58', '06:00:00', '09:00:00', '0', '0', '1', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('61', '迟到', '0', '58', '09:00:01', '12:00:00', '0', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('62', '正常', '0', '59', '12:00:00', '23:59:59', '1', '0', '0', '0');
INSERT INTO `xinhu_kqsjgz` VALUES ('63', '早退', '0', '59', '09:00:01', '12:00:00', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_kqxxsj`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_kqxxsj`;
CREATE TABLE `xinhu_kqxxsj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `dt` date DEFAULT NULL,
  `pid` smallint(6) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '0休息日,1工作日',
  `uid` int(11) DEFAULT '0' COMMENT '对应用户/部门Id',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`,`dt`)
) ENGINE=MyISAM AUTO_INCREMENT=336 DEFAULT CHARSET=utf8 COMMENT='休息时间规则';

-- ----------------------------
-- Records of xinhu_kqxxsj
-- ----------------------------
INSERT INTO `xinhu_kqxxsj` VALUES ('1', '全体人员休息日', null, '0', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_location`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_location`;
CREATE TABLE `xinhu_location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(30) DEFAULT NULL,
  `uid` int(11) DEFAULT '0',
  `agentid` varchar(20) DEFAULT NULL COMMENT '应用Id',
  `optdt` datetime DEFAULT NULL,
  `location_x` varchar(30) DEFAULT NULL COMMENT '地理位置纬度',
  `location_y` varchar(30) DEFAULT NULL COMMENT '地理位置经度 ',
  `scale` smallint(6) DEFAULT '0' COMMENT '地图缩放大小',
  `label` varchar(200) DEFAULT NULL COMMENT '地理位置',
  `msgid` varchar(50) DEFAULT NULL,
  `precision` smallint(6) DEFAULT '0' COMMENT '地理位置精度',
  `type` tinyint(4) DEFAULT '0' COMMENT '0普通,1事件,2企业微信定位',
  `explain` varchar(50) DEFAULT NULL COMMENT '说明',
  `imgpath` varchar(100) DEFAULT NULL COMMENT '相关图片',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='外勤定位';

-- ----------------------------
-- Records of xinhu_location
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_log`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_log`;
CREATE TABLE `xinhu_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL COMMENT '类型',
  `optid` int(11) DEFAULT NULL COMMENT '操作人id',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `remark` varchar(500) DEFAULT NULL COMMENT '备注',
  `optdt` datetime DEFAULT NULL COMMENT '添加时间',
  `ip` varchar(100) DEFAULT NULL COMMENT 'IP地址',
  `web` varchar(100) DEFAULT NULL COMMENT '浏览器',
  `device` varchar(50) DEFAULT NULL,
  `level` tinyint(1) DEFAULT '0' COMMENT '日志级别0普通,1提示,2错误',
  `url` varchar(500) DEFAULT NULL,
  `result` varchar(1000) DEFAULT NULL COMMENT '运行结果',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统日志';

-- ----------------------------
-- Records of xinhu_log
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_logintoken`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_logintoken`;
CREATE TABLE `xinhu_logintoken` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(20) DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `adddt` datetime DEFAULT NULL,
  `moddt` datetime DEFAULT NULL,
  `cfrom` varchar(10) DEFAULT NULL,
  `device` varchar(50) DEFAULT NULL,
  `ip` varchar(500) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `online` tinyint(1) DEFAULT '1',
  `ispush` tinyint(1) DEFAULT '0' COMMENT '是否可推送',
  `pushtoken` varchar(200) DEFAULT NULL COMMENT '推送token',
  PRIMARY KEY (`id`),
  KEY `token` (`token`) USING BTREE,
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统登录token';

-- ----------------------------
-- Records of xinhu_logintoken
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_meet`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_meet`;
CREATE TABLE `xinhu_meet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hyname` varchar(20) DEFAULT NULL COMMENT '会议室名称',
  `title` varchar(100) DEFAULT NULL COMMENT '主题',
  `startdt` varchar(50) DEFAULT NULL COMMENT '开始时间',
  `enddt` varchar(50) DEFAULT NULL COMMENT '结束时间',
  `state` tinyint(1) DEFAULT '0' COMMENT '会议状态@0|正常,1|会议中,2|结束,3|取消',
  `status` tinyint(1) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型@0|普通,1|固定,2|纪要',
  `joinid` varchar(200) DEFAULT NULL,
  `joinname` varchar(500) DEFAULT NULL COMMENT '参加人员',
  `mid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '发起人',
  `rate` varchar(100) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` int(11) DEFAULT '0',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `cancelreason` varchar(200) DEFAULT NULL,
  `jyid` varchar(100) DEFAULT NULL COMMENT '会议纪要人id',
  `jyname` varchar(100) DEFAULT NULL COMMENT '会议纪要人',
  `content` varchar(4000) DEFAULT NULL COMMENT '会议纪要内容',
  `issms` tinyint(1) DEFAULT '0' COMMENT '是否短信通知',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `zcrenid` varchar(50) DEFAULT NULL,
  `zcren` varchar(50) DEFAULT NULL COMMENT '主持人',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='会议';

-- ----------------------------
-- Records of xinhu_meet
-- ----------------------------
INSERT INTO `xinhu_meet` VALUES ('7', null, null, null, null, '2', '0', '0', null, null, '0', null, null, null, null, '0', null, null, null, null, null, '0', '0', null, null);
INSERT INTO `xinhu_meet` VALUES ('6', '会客室', '主题开会', '2020-07-08 13:30:00', '2020-07-08 15:20:00', '2', '1', '0', 'd1', '信呼开发团队', '0', '管理员', null, '1', '2020-07-08 13:21:04', '1', null, null, null, null, null, '0', '1', null, null);
INSERT INTO `xinhu_meet` VALUES ('5', '会客室', '关于国庆放假通知', '2020-07-07 11:15:00', '2020-07-07 16:15:00', '2', '1', '0', 'd1', '信呼开发团队', '0', '管理员', null, '1', '2020-06-30 11:13:57', '1', null, null, null, null, null, '0', '1', null, null);

-- ----------------------------
-- Table structure for `xinhu_menu`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_menu`;
CREATE TABLE `xinhu_menu` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '菜单名',
  `pid` smallint(6) DEFAULT '0' COMMENT '上级id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序',
  `url` varchar(500) DEFAULT NULL,
  `icons` varchar(50) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `num` varchar(50) DEFAULT NULL COMMENT '编号',
  `ispir` tinyint(1) DEFAULT '1' COMMENT '1验证',
  `status` tinyint(1) DEFAULT '1' COMMENT '1启用',
  `color` varchar(10) DEFAULT NULL,
  `ishs` tinyint(1) DEFAULT '0' COMMENT '是否在首页显示',
  `iszm` tinyint(1) DEFAULT '0' COMMENT '是否为桌面版图标',
  `type` smallint(6) DEFAULT '0' COMMENT '菜单类型',
  `types` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=373 DEFAULT CHARSET=utf8 COMMENT='系统菜单';

-- ----------------------------
-- Records of xinhu_menu
-- ----------------------------
INSERT INTO `xinhu_menu` VALUES ('1', '系统', '0', '20', null, 'cog', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('2', '菜单管理', '220', '4', 'system,menu', 'list-ul', null, 'menu', '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('3', '用户管理', '220', '2', 'flow,page,user,atype=all,pnum=all', 'user', null, 'user', '1', '1', '#5AAAE2', '1', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('4', '计划任务', '46', '7', 'system,task', 'time', null, 'taskrun', '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('5', '组管理', '220', '3', 'system,group', 'group', null, 'group', '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('6', '组织结构', '220', '1', 'system,dept', 'sitemap', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('7', '流程模块', '0', '10', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('8', '权限管理', '1', '6', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('9', '人员→菜单', '8', '0', 'system,extent,type=um', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('10', '菜单→人员', '8', '1', 'system,extent,type=mu', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('11', '组→菜单', '8', '2', 'system,extent,type=gm', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('12', '菜单→组', '8', '3', 'system,extent,type=mg', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('13', '人员权限查看', '8', '4', 'system,extent,type=view', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('14', '即时通信管理', '1', '5', null, 'comment', null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('15', '应用管理', '14', '4', 'main,yingyong', 'th-large', null, 'yingyong', '1', '1', '#1ABC9C', '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('16', '会话管理', '14', '3', 'main,imgroup', 'comments', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('17', '流程模块列表', '7', '2', 'main,flow,set', 'th-list', null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('18', '服务器设置', '14', '0', 'main,xinhu,cog', 'tasks', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('20', '表单元素管理', '7', '5', 'main,flow,element', 'check', null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('21', '流程模块权限', '7', '7', 'main,view', 'lock', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('22', '流程单据查看', '7', '8', 'main,flow,view', 'search', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('23', '系统升级', '46', '10', 'system,upgrade', 'arrow-up', null, 'upgrade', '1', '1', '#ff0000', '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('24', '数据选项', '7', '1', 'system,option,num=xinhu', 'cog', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('25', '初始化备份', '46', '5', 'system,beifen', null, null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('26', '流程审核步骤', '7', '4', 'main,flow,course', 'resize-vertical', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('27', '人事考勤', '0', '6', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('28', '物品基础管理', '140', '1', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('29', '单据异常监控', '7', '13', 'main,flow,error', 'briefcase', null, 'danerror', '1', '1', '#800000', '1', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('30', '物品列表', '28', '3', 'main,goods', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('31', '出入库详情', '249', '1', 'main,goods,xiang', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('32', '考勤设置', '27', '10', null, 'time', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('33', '打卡记录', '57', '0', 'main,kaoqin,dkjl,atype=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('34', '单据操作菜单', '7', '9', 'main,flow,menu', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('36', '考勤信息', '57', '4', 'main,kaoqin,info', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('37', '客户统计', '63', '10', null, null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('38', '个人办公', '0', '0', null, 'desktop', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('39', '个人中心', '38', '0', null, null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('40', '流程', '0', '1', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('41', '我的申请', '40', '2', 'main,fwork,bill,atype=my', 'align-left', null, 'applymy', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('42', '流程申请', '40', '1', 'main,fwork,apply', 'plus', null, 'flowapply', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('43', '待办/处理', '40', '0', 'main,fwork,bill,atype=daib', 'time', null, 'daiban', '0', '1', '#3BBDDB', '1', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('44', '我的下属申请', '40', '5', 'main,fwork,bill,atype=xia', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('45', '日程', '38', '2', 'system,schedule,list', 'calendar', null, 'schedule', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('46', '系统工具', '1', '10', null, 'wrench', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('53', '考勤时间规则', '32', '1', 'main,kaoqin,sjgz', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('54', '考勤时间分配', '32', '2', 'main,kaoqin,sjfp,type=0', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('55', '休息时间规则', '32', '4', 'main,kaoqin,xxgz', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('56', '休息时间分配', '32', '5', 'main,kaoqin,sjfp,type=1', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('57', '考勤信息', '27', '11', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('58', '考勤分析', '57', '6', 'main,kaoqin,anay', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('59', '我的考勤', '38', '12', null, 'time', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('60', '考勤统计简表', '57', '7', 'main,kaoqin,total,atype=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('61', '人员考勤设置', '32', '0', 'main,kaoqin,kqcog', 'cog', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('62', '经我处理', '40', '6', 'main,fwork,bill,atype=jmy', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('63', '客户', '0', '5', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('64', '客户', '63', '2', 'main,customer,list,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('65', '我的任务', '66', '1', 'flow,page,work,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('66', '任务', '19', '1', null, 'book', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('67', '我未完成任务', '66', '2', 'flow,page,work,atype=wwc', 'book', null, 'workwwc', '0', '1', '#BFC462', '1', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('68', '我创建任务', '66', '3', 'flow,page,work,atype=wcj', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('69', '我执行的项目', '79', '2', 'flow,page,project,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('70', '我未完成项目', '79', '2', 'flow,page,project,atype=wwc', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('71', '我创建的项目', '79', '3', 'flow,page,project,atype=mycj', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('72', '我负责的项目', '79', '2', 'flow,page,project,atype=myfz', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('73', '销售机会', '63', '3', null, null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('74', '我申请未通过', '40', '4', 'main,fwork,bill,atype=mywtg', 'info-sign', null, 'applywtg', '0', '1', '#B67FF4', '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('75', '共享给我的', '64', '1', 'flow,page,customer,atype=gxgw,pnum=shate', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('76', '工作日报', '38', '13', null, 'edit', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('77', '我的日报', '76', '6', 'flow,page,daily,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('78', '我下属日报', '76', '8', 'flow,page,daily,atype=undall', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('79', '项目', '19', '0', null, 'circle-arrow-right', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('80', '任务督导', '66', '4', 'flow,page,work,atype=dd', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('81', '所有任务一览', '66', '10', 'flow,page,work,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('82', '所有项目一览', '79', '10', 'flow,page,project,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('83', '我下属任务', '66', '2', 'flow,page,work,atype=down,pnum=down', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('84', '人事档案', '27', '0', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('85', '人员档案', '84', '0', 'flow,page,userinfo,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('86', '我的考勤表', '59', '0', 'main,kaoqin,geren', null, null, 'mykqbiao', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('87', '我的考勤信息', '59', '4', 'main,kaoqin,kqinfo,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('88', '我的考勤统计', '59', '5', 'main,kaoqin,total,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('89', '会议', '38', '11', null, null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('90', '通知公告', '38', '1', 'flow,page,gong,atype=my', 'volume-up', null, 'gong', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('91', '流程模块条件', '7', '10', 'main,flow,where', 'asterisk', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('92', '我的打卡记录', '59', '1', 'main,kaoqin,dkjl,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('93', '我的定位记录', '59', '6', 'main,kaoqin,location,atype=my', 'map-marker', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('94', '定位记录', '57', '1', 'main,kaoqin,location,atype=all', 'map-marker', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('95', '员工合同', '84', '0', 'flow,page,userract,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('96', '转正申请', '127', '3', 'flow,page,hrpositive,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('97', '离职申请', '127', '4', 'flow,page,hrredund,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('98', '薪资管理', '27', '3', null, 'money', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('99', '薪资核算', '98', '2', 'main,salary,list', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('100', '提醒信息', '39', '1', 'system,geren,todo', 'bell', null, 'todo', '0', '1', '#EFBB62', '1', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('101', '个人资料', '39', '2', 'main,userinfo,geren', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('102', '薪资发放', '98', '4', 'main,salary,fafang', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('103', '系统信息', '46', '2', 'system,cog,sysinfo', null, null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('104', '合同', '63', '4', null, null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('105', '收款单', '63', '6', null, 'money', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('106', '我的收款单', '105', '0', 'flow,page,custfina,atype=myskd', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('107', '我的付款单', '118', '0', 'flow,page,custfina,atype=myfkd,pnum=fkd', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('108', '我的合同', '104', '0', 'flow,page,custract,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('109', '我下属的合同', '104', '1', 'flow,page,custract,atype=downall,pnum=down', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('110', '我的销售机会', '73', '0', 'flow,page,custsale,atype=mygx', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('111', '我下属销售机会', '73', '1', 'flow,page,custsale,atype=downgj,pnum=down', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('112', '我的客户', '64', '0', 'flow,page,customer,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('113', '我下属客户', '64', '2', 'flow,page,customer,atype=downall,pnum=down', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('114', '金额统计', '63', '20', null, 'bar-chart', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('115', '我的财务统计', '114', '0', 'main,customer,total,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('116', '我下属财务统计', '114', '0', 'main,customer,total,atype=down', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('117', '我年度统计', '114', '0', 'main,customer,totalge', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('118', '付款单', '63', '7', null, null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('119', '我下属收款单', '105', '0', 'flow,page,custfina,atype=downskd,pnum=downskd', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('120', '我下属付款单', '118', '0', 'flow,page,custfina,atype=downfkd,pnum=downfkd', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('121', '所有收款单', '126', '5', 'flow,page,custfina,atype=allskd,pnum=allskd', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('122', '所有付款单', '126', '6', 'flow,page,custfina,atype=allfkd,pnum=allfkd', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('123', '客户分配', '64', '6', 'flow,page,customer,atype=distdfp,pnum=dist', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('19', '任务资源', '0', '2', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('124', '文档中心', '19', '2', 'flow,page,worcword', 'folder-close-alt', null, 'word', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('125', '共享的文档', '19', '4', 'system,word,shate', 'folder-open-alt', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('126', '客户查看', '63', '30', null, 'eye-open', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('127', '人事管理', '27', '1', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('128', '人事调动', '127', '5', 'flow,page,hrtransfer,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('129', '调薪申请', '127', '6', 'flow,page,hrtrsalary,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('130', '奖惩处罚', '127', '7', 'flow,page,reward,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('131', '我的客户统计', '37', '0', 'main,customer,alllist,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('132', '下属客户统计', '37', '0', 'main,customer,alllist,atype=downall', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('133', '我上传过的文件', '39', '6', 'system,geren,file', 'folder-close-alt', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('134', '知识信息管理', '19', '21', 'system,infor,zhishi', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('135', '知识题库管理', '19', '22', 'system,infor,tiku', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('136', '知识题库学习', '19', '24', 'system,infor,tikuxx', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('137', '固定资产', '140', '11', 'flow,page,assetm,atype=all,pnum=all', 'laptop', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('138', '车辆管理', '140', '21', null, 'truck', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('139', '图书管理', '140', '41', null, 'book', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('140', '行政', '0', '7', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('141', '图书列表', '139', '0', 'flow,page,book,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('142', '信呼官网', '46', '88', 'http://www.rockoa.com', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('143', '车辆列表', '138', '0', 'flow,page,carm,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('144', '车辆预定查询', '138', '2', 'flow,page,carmrese,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('145', '图书借阅', '139', '5', 'flow,page,bookborrow,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('146', '车辆预定情况', '138', '3', 'main,carm,carmqing', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('147', '数据库表', '46', '3', 'system,table,list', 'table', null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('148', '内部通讯录', '39', '4', 'system,admin,txl', 'phone', null, 'reimtxl', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('149', '人员分析', '84', '3', 'main,userinfo,anay', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('150', '今日会议', '89', '0', 'flow,page,meet,atype=today', null, null, 'meet', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('151', '会议室情况', '89', '0', 'main,fwork,meethy', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('152', '定位打卡位置', '32', '6', 'main,kaoqin,kqdw', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('153', '定位打卡位置分配', '32', '7', 'main,kaoqin,sjfp,type=2', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('154', '邮件管理', '1', '3', null, 'envelope', null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('156', '系统设置', '46', '0', 'system,cog,set', null, null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('157', '邮件', '38', '0', 'system,email', 'envelope', null, 'email', '0', '1', '#0099CC', '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('158', '知识信息查看', '19', '23', 'system,infor,zhishixx', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('159', '外出记录', '57', '2', 'flow,page,waichu,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('160', '我的外出记录', '59', '2', 'flow,page,waichu,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('161', '上传文件管理', '46', '15', 'system,geren,file,atype=all', 'folder-close-alt', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('162', '日志查看', '46', '16', 'system,cog,log', null, null, 'syslog', '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('163', '邮件收发设置', '154', '1', 'system,email,cogsfa', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('164', '用户邮箱设置', '154', '2', 'system,email,coguser', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('165', '印章证照', '140', '51', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('166', '印章证照列表', '165', '0', 'flow,page,seal,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('167', '申请使用查询', '165', '0', 'flow,page,sealapl,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('168', '个人通讯录', '39', '5', 'system,geren,vcard', 'credit-card', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('169', '打卡异常', '57', '3', 'flow,page,kqerr,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('170', '我的打卡异常', '59', '3', 'flow,page,kqerr,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('171', '企业微信', '1', '30', null, 'comments-alt', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('172', '企业微信设置', '171', '0', 'system,weixinqy,cog', 'cog', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('155', '企业微信部门', '171', '1', 'system,weixinqy,dept', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('173', '企业微信用户', '171', '2', 'system,weixinqy,user', 'user', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('174', '企业微信应用', '171', '3', 'system,weixinqy,ying', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('179', '客户主页', '63', '0', 'main,customer,index', 'user-md', null, 'crmindex', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('175', '客户一览表', '126', '0', 'flow,page,customer,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('176', '所有客户合同', '126', '3', 'flow,page,custract,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('177', '所有销售机会', '126', '2', 'flow,page,custsale,atype=allgj,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('178', '所有财务统计', '126', '7', 'main,customer,total,atype=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('35', '单据通知设置', '7', '11', 'main,flow,todo', 'bell', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('180', '微信公众号', '1', '19', null, 'comments-alt', null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('181', '公众号设置', '180', '0', 'system,wxgzh,cog', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('182', '授权单据查看', '40', '8', 'main,fwork,bill,atype=grantview', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('183', '单据查看', '38', '3', 'main,fwork,search', 'search', null, 'flowsearch', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('184', '我的薪资', '39', '3', 'main,salary,my', 'money', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('185', '流程模块统计', '7', '12', 'main,flowtotal', 'bar-chart', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('186', '报销借还款', '313', '2', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('187', '费用报销', '186', '0', 'flow,page,finfybx,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('188', '出差报销', '186', '3', 'flow,page,finccbx,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('189', '借款单', '186', '5', 'flow,page,finjkd,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('190', '还款单', '186', '7', 'flow,page,finhkd,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('191', '费用报销报表', '186', '1', 'flow,page,finccbxchart', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('192', '工作日报', '27', '2', null, 'edit', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('193', '所有日报', '192', '1', 'flow,page,daily,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('194', '日报统计', '192', '2', 'flow,page,dailytotal', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('195', '我的日报统计', '76', '7', 'flow,page,dailytotal,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('196', '日报统计分析', '192', '3', 'flow,page,dailychart', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('197', '考勤时间排班', '32', '3', 'main,kaoqin,sjpb', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('198', '我下属日报统计', '76', '9', 'flow,page,dailytotal,atype=down', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('199', '考试培训', '27', '12', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('200', '考试培训列表', '199', '0', 'flow,page,knowtraim,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('201', '考试培训人员', '199', '1', 'flow,page,knowtraimuser,atype=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('202', '我的培训考试', '39', '7', 'flow,page,knowtraimuser,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('203', '文档分区管理', '19', '10', 'flow,page,worc,atype=all,pnum=all', 'folder-close-alt', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('204', '公文', '0', '4', null, null, null, null, '1', '1', null, '0', '0', '0', '1');
INSERT INTO `xinhu_menu` VALUES ('205', '收文单', '204', '3', null, 'hand-down', null, null, '1', '1', null, '0', '0', '0', '1');
INSERT INTO `xinhu_menu` VALUES ('206', '收文管理', '255', '1', 'flow,page,officib,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('207', '单据提醒', '38', '4', 'main,fwork,todo', 'star-empty', null, 'flowtodo', '0', '1', '#ADCC61', '1', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('208', '钉钉管理', '1', '40', null, 'bolt', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('209', '钉钉设置', '208', '0', 'system,wding,cog', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('210', '钉钉部门', '208', '1', 'system,wding,dept', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('211', '钉钉用户', '208', '2', 'system,wding,user', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('212', '钉钉微应用', '208', '3', 'system,wding,ying', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('213', '审核人员分组', '7', '3', 'main,flow,cname', 'group', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('214', '车辆信息登记', '138', '1', 'flow,page,carms,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('215', '车辆维修', '138', '4', 'flow,page,carmwx,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('216', '车辆保养', '138', '5', 'flow,page,carmby,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('217', '加班单核算', '57', '5', 'flow,page,jiaban,atype=allfee,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('218', '借款单统计', '186', '6', 'flow,page,finjkdtotal,atype=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('219', '公司单位', '220', '0', 'flow,page,company,atype=all,pnum=all', 'road', null, 'company', '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('220', '基础管理', '1', '0', null, 'asterisk', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('221', '首页项管理', '220', '5', 'system,home', null, null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('222', '短信管理', '1', '4', null, 'envelope-alt', null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('223', '短信设置', '222', '0', 'system,sms,cog', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('224', '短信签名', '222', '1', 'system,sms,qian', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('225', '短信模版', '222', '2', 'system,sms,tpl', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('226', '短信发送记录', '222', '3', 'system,sms,record', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('227', '我的会话记录', '39', '8', 'reim,record,atype=my', 'file-alt', null, 'reimrecord', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('228', '会话记录', '14', '5', 'reim,record,atype=all', 'file-alt', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('229', '付款申请', '315', '8', 'flow,page,finpay,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('230', '开票申请', '315', '9', 'flow,page,finkai,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('231', '日报授权查看', '76', '10', 'flow,page,daily,atype=grant', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('232', '发文单', '204', '2', null, 'hand-up', null, null, '1', '1', null, '0', '0', '0', '1');
INSERT INTO `xinhu_menu` VALUES ('233', '发文管理', '255', '0', 'flow,page,officia,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('234', '考勤统计详表', '57', '8', 'main,kaoqin,total,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('235', '订阅管理', '237', '0', 'flow,page,subscribe,atype=all,pnum=all', null, null, 'rssglall', '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('236', '订阅报表', '270', '5', 'flow,page,subscribeinfo,atype=my', 'rss', null, 'subscribeinfo', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('237', '订阅回执管理', '1', '5', null, 'rss', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('238', '订阅报表', '237', '1', 'flow,page,subscribeinfo,atype=all,pnum=all', null, null, 'rssinfoall', '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('239', '公文查阅', '204', '0', 'flow,page,officic,atype=my', 'suitcase', null, 'officic', '0', '1', null, '0', '0', '0', '1');
INSERT INTO `xinhu_menu` VALUES ('240', '考勤机管理', '27', '10', null, 'hdd', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('241', '考勤机设备', '240', '0', 'flow,page,kqjsn,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('242', '考勤机命令查看', '240', '2', 'main,kaoqinj,cmd', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('243', '考勤机上部门人员', '240', '1', 'main,kaoqinj,dept', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('244', '考核评分', '272', '1', 'flow,page,hrcheck,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('245', '城市管理', '46', '17', 'system,city', null, null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('246', '演示模块数据', '46', '89', 'flow,page,demo,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('247', '仓库管理', '28', '0', 'flow,page,godepot,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('251', '物品采购', '249', '2', 'flow,page,caigou,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('248', '供应商管理', '28', '1', 'flow,page,customer,atype=gys,pnum=gys', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('249', '物品管理', '140', '2', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('250', '物品领用', '249', '3', 'flow,page,goodly,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('252', '物品盘点', '249', '10', 'main,goods,pandian', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('253', '出入库操作', '249', '0', 'main,goods,cropt', 'plus-sign', null, 'cropt', '1', '1', '#00b6ff', '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('254', '个人设置', '39', '0', 'system,geren,cog', 'cog', null, 'grcog', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('255', '公文管理', '204', '10', null, null, null, null, '1', '1', null, '0', '0', '0', '1');
INSERT INTO `xinhu_menu` VALUES ('256', '拟办发文', '232', '0', 'add,officia', 'edit', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('257', '收文登记', '205', '0', 'add,officib', 'plus', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('258', '我的发文', '232', '1', 'flow,page,officia,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('259', '我的收文', '205', '1', 'flow,page,officib,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('260', '剩余假期统计', '57', '4', 'main,kaoqin,totaljia', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('261', '物品调拨', '249', '5', 'flow,page,diaobo,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('262', '客户公海库', '63', '1', 'flow,page,customer,atype=allgh,pnum=ghai', 'beaker', null, 'crmghai', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('263', '客户申请使用', '64', '7', 'flow,page,custappy,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('264', '所有客户申请使用', '126', '8', 'flow,page,custappy,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('265', '人员需求', '127', '1', 'flow,page,hrdemand,atype=all,pnum=all ', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('266', '面试安排', '127', '2', 'flow,page,hrmanshi,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('267', '抄送给我的单据', '40', '7', 'main,fwork,bill,atype=chaosview', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('268', '单据回执确认', '270', '3', 'flow,page,receipt,atype=my', 'exchange', null, 'receiptmy', '0', '1', '#ed8b8b', '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('269', '回执确认管理', '237', '2', 'flow,page,receipt,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('270', '订阅和回执', '38', '5', null, null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('271', '草稿待提交', '40', '3', 'main,fwork,bill,atype=daiturn', 'edit', null, 'daiturn', '0', '1', '#ff6600', '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('272', '考核管理', '27', '2', null, 'ok-circle', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('273', '考核项目', '272', '0', 'flow,page,hrkaohem,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('274', '我的考核', '39', '9', 'flow,page,hrcheck,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('275', '考核评分详情', '272', '2', 'flow,page,hrcheck,atype=tjall,pnum=tjall', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('276', '薪资模版', '98', '1', 'flow,page,hrsalarm,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('277', '社保公积金', '98', '0', 'flow,page,hrshebao,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('278', '部门薪资核算', '98', '3', 'main,salary,bumen', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('279', '发票管理', '315', '10', 'flow,page,finpiao,atype=kai,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('280', '发票管理统计', '315', '11', 'flow,page,finpiaototal', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('281', '文档协作', '19', '5', 'flow,page,wordxie,atype=myxie', 'file', null, 'wordxie', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('282', '文件传送', '19', '6', 'flow,page,wordeil,atype=my', 'signout', null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('283', '会议管理', '140', '12', 'flow,page,meet,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('284', '模版消息', '180', '1', 'system,wxgzh,tpl', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('285', '授权微信人', '180', '2', 'system,wxgzh,user', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('286', '信息中心', '140', '0', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('287', '通知公告', '286', '1', 'flow,page,gong,atype=all,pnum=all', 'volume-up', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('288', '新闻资讯', '286', '2', 'flow,page,news,atype=all,pnum=all', 'globe', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('367', '第三方模块插件', '46', '11', 'system,upgrade,other', 'cogs', null, 'otherpluge', '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('299', '销售', '63', '5', null, null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('300', '我的销售', '299', '0', 'flow,page,custxiao,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('301', '我下属的销售', '299', '0', 'flow,page,custxiao,atype=downall,pnum=down', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('302', '所有销售', '126', '4', 'flow,page,custxiao,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('303', '流程监控', '40', '9', 'main,fwork,bill,atype=jiankong', 'retweet', null, 'jiankong', '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('306', '我的公文统计', '232', '5', 'flow,page,officiatotal,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('313', '财务', '0', '8', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('315', '发票付款', '313', '3', null, null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('317', '跟进计划', '64', '0', 'flow,page,custplan,atype=my', null, null, null, '0', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('318', '所有跟进计划', '126', '1', 'flow,page,custplan,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('319', '物品归还', '249', '4', 'flow,page,goodgh,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('320', '系统签授', '46', '1', 'system,cog,auther', 'key', null, 'auther', '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('321', '调查问卷', '286', '3', 'flow,page,wenjuan,atype=all,pnum=all', 'question-sign', null, 'wenjuanall', '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('323', '物品退货单', '249', '6', 'flow,page,tuihuo,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('324', '系统程序文件', '46', '6', 'system,sysfile', null, null, null, '1', '1', null, '0', '0', '1', '0');
INSERT INTO `xinhu_menu` VALUES ('339', '人员证书', '84', '1', 'flow,page,userzheng,atype=all,pnum=all', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('340', 'REIM通信平台', '1', '43', null, 'comment-alt', null, null, '1', '0', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('341', 'REIM平台设置', '340', '0', 'system,reimplat,cog', null, null, null, '1', '0', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('342', 'REIM平台部门', '340', '0', 'system,reimplat,dept', null, null, null, '1', '0', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('343', 'REIM平台用户', '340', '0', 'system,reimplat,user', null, null, null, '1', '0', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('344', 'REIM平台应用', '340', '0', 'system,reimplat,ying', null, null, null, '1', '0', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('366', '微信小程序用户', '180', '3', 'system,wxgzh,xcyuser', null, null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('370', '腾讯企业邮箱应用', '171', '4', 'system,weixinqy,txemail', 'envelope', null, null, '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('371', '音视频通话记录', '14', '6', 'reim,record,tonghua,atype=all', 'facetime-video', null, 'tonghua', '1', '1', null, '0', '0', '0', '0');
INSERT INTO `xinhu_menu` VALUES ('372', '可视化模块设计', '7', '2', 'open:?d=main&m=flowview', 'desktop', null, 'keshihau', '1', '1', null, '0', '0', '1', '0');

-- ----------------------------
-- Table structure for `xinhu_news`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_news`;
CREATE TABLE `xinhu_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `typename` varchar(20) DEFAULT NULL,
  `content` text,
  `url` varchar(200) DEFAULT NULL,
  `receid` varchar(2000) DEFAULT NULL COMMENT '接收人Id',
  `recename` varchar(4000) DEFAULT NULL,
  `optid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL,
  `enddt` date DEFAULT NULL COMMENT '截止时间',
  `startdt` date DEFAULT NULL COMMENT '开始时间',
  `zuozhe` varchar(30) DEFAULT NULL COMMENT '发布者',
  `indate` date DEFAULT NULL COMMENT '日期',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `fengmian` varchar(100) DEFAULT NULL COMMENT '封面图片',
  `mintou` smallint(6) DEFAULT '0' COMMENT '至少投票',
  `maxtou` smallint(6) DEFAULT '0' COMMENT '最多投投票0不限制',
  `issms` tinyint(1) DEFAULT '0' COMMENT '是否发短信',
  `istop` tinyint(1) DEFAULT '0' COMMENT '排序号越大越靠前',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `appxs` tinyint(1) DEFAULT '0' COMMENT 'app首页显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='新闻资讯';

-- ----------------------------
-- Records of xinhu_news
-- ----------------------------
INSERT INTO `xinhu_news` VALUES ('1', '欢迎使用信呼OA系统', '2020-06-30 21:29:36', '资讯', '<p>\n	信呼是免费开源的一款办公系统，跨平台的系统，支持APP，pc网页版，pc客户端，REIM即时通信等。\n</p>\n<p>\n	相关图片1\n</p>\n<img src=\"http://www.rockoa.com/images/web/banner02.jpg\" alt=\"\" /> \n<p>\n	相关图片2\n</p>\n<img src=\"http://www.rockoa.com/images/web/banner06.jpg\" alt=\"\" />', 'http://www.rockoa.com/', null, null, '1', '管理员', null, null, '信呼开发团队', '2020-06-30', '1', 'http://www.rockoa.com/images/web/bannertp.jpg', '0', '0', '0', '0', '1', '1');

-- ----------------------------
-- Table structure for `xinhu_official`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_official`;
CREATE TABLE `xinhu_official` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `titles` varchar(255) DEFAULT NULL COMMENT '副标题',
  `class` varchar(10) DEFAULT NULL COMMENT '类型',
  `type` tinyint(1) DEFAULT '0' COMMENT '1收文单,0发文单',
  `grade` varchar(10) DEFAULT NULL COMMENT '等级',
  `optname` varchar(20) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `content` text COMMENT '内容',
  `receid` varchar(200) DEFAULT NULL COMMENT '来源',
  `recename` varchar(200) DEFAULT NULL COMMENT '发给',
  `applydt` date DEFAULT NULL COMMENT '日期',
  `num` varchar(30) DEFAULT NULL COMMENT '编号',
  `optid` smallint(6) DEFAULT '0',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `filecontid` varchar(30) DEFAULT NULL COMMENT '正文文件Id',
  `zinum` varchar(30) DEFAULT NULL COMMENT '发文字号',
  `unitname` varchar(200) DEFAULT NULL COMMENT '主送单位',
  `unitsame` varchar(200) DEFAULT NULL COMMENT '发文单位',
  `miji` varchar(50) DEFAULT NULL COMMENT '公文密级',
  `laidt` date DEFAULT NULL COMMENT '来文日期',
  `chaoname` varchar(200) DEFAULT NULL COMMENT '抄送单位',
  `zuncheng` varchar(200) DEFAULT NULL COMMENT '正文上称呼',
  `thid` int(11) DEFAULT '0' COMMENT '对应officialhong里id',
  `yzid` int(11) DEFAULT '0' COMMENT '对应印章sealapl里Id',
  `ffid` int(11) DEFAULT '0' COMMENT '对应分发表officialfa的Id',
  `enddt` date DEFAULT NULL COMMENT '截止日期',
  `startdt` date DEFAULT NULL COMMENT '查阅日期',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `ffdt` date DEFAULT NULL COMMENT '分发日期',
  `pdfid` int(11) DEFAULT '0' COMMENT '生成pdf文件id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='公文';

-- ----------------------------
-- Records of xinhu_official
-- ----------------------------
INSERT INTO `xinhu_official` VALUES ('1', '1', '关于任命磐石为董事长通知', '任命 通知', '决定', '0', '平急', '管理员', '2020-11-01 08:46:57', '1', '<p>\n	由于磐石认真工作，工作勤勤恳恳，任劳任怨积极配合各部门工作协调，不辞辛苦坚持以9127的工作时间工作，把工作做到最好，按时完成了。\n</p>\n<p>\n	为了让更好的发展，经各位领导研究决定任命为董事长。\n</p>\n<p>\n	<span>以上任命决定自本通知发布之日起开始执行。<br />\n</span> \n</p>\n<p>\n	<span>特此决定。</span> \n</p>', 'd1', '信呼开发团队', '2019-04-10', '信呼〔2019〕1号', '1', null, '1', '1', '信呼', '信呼开发团队', '开发部', '公开', null, null, '各位同事', '3', '1', '1', null, null, '1', '2019-04-21', '0');
INSERT INTO `xinhu_official` VALUES ('4', '1', '关于调整社保基数的通知', '社保基数', null, '1', '平急', '管理员', '2019-05-12 23:10:41', '1', '<p>\n	由于系统已升级，基数原来的最低工资1700调整为1800。\n</p>\n<p>\n	请各个企业单位务必遵守。\n</p>', 'd1', '信呼开发团队', '2019-05-12', '厦门【2019】1号', '1', null, '1', null, null, '信呼开发团队', '厦门市人民政府', '私密', '2019-05-12', null, '各位企业单位：', '0', '0', '2', null, null, '0', null, '0');
INSERT INTO `xinhu_official` VALUES ('3', '1', '关于决定使用信呼OA系统通知', null, '决议', '0', '平急', '管理员', '2019-04-25 20:28:40', '1', '<p>\n	在已一开始使用体验信呼OA系统。很好我们决定启用该系统来办公。\n</p>\n<p>\n	<br />\n</p>', null, null, '2019-04-25', '信呼〔2019〕2号', '1', null, '1', null, '信呼', '信呼开发团队', '开发部', '私密', null, null, '各位同事：', '2', '-1', '0', null, null, '0', null, '0');
INSERT INTO `xinhu_official` VALUES ('9', '1', '关于国庆放假通知', '国庆 放假', '决议', '0', '平急', '管理员', '2020-06-19 22:29:41', '1', null, 'd1', '信呼开发团队', '2020-06-19', '信呼〔2020〕1号', '1', null, '1', '13', '信呼', '信呼开发团队', '开发部', '公开', null, null, '各位同事', '-1', '-1', '4', null, null, '1', '2020-06-20', '0');
INSERT INTO `xinhu_official` VALUES ('10', '1', '今年2021年建议留厦过年', '过年 留厦', null, '1', '平急', '管理员', '2021-01-07 17:48:20', '0', '<p>\n	新年就要到了，由于今年新冠疫情还未消除，我们建议过年留厦。\n</p>\n<p>\n	<br />\n</p>', null, null, '2021-01-07', '厦门【2021】1号	', '1', null, '1', null, null, '信呼开发团队', '厦门市人民政府', '私密', '2021-01-07', null, '各位外来务工人员', '0', '0', '0', null, null, '1', null, '0');
INSERT INTO `xinhu_official` VALUES ('11', '4', '关于台风来公告', '台风', '决议', '0', '平急', '大乔', '2021-10-12 11:04:36', '1', '今年第8好台风“<span>圆规</span>”来了，沿海区域风好大，注意防患。', 'd1', '信呼开发团队', '2021-10-12', '信呼〔2021〕1号', '4', null, '1', null, '信呼', '信呼开发团队', '行政人事部', '公开', null, null, null, '4', '-1', '5', null, null, '1', '2021-10-12', '0');

-- ----------------------------
-- Table structure for `xinhu_option`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_option`;
CREATE TABLE `xinhu_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `type` int(11) DEFAULT '0' COMMENT '选项类型',
  `pid` int(11) DEFAULT NULL,
  `num` varchar(50) DEFAULT NULL COMMENT '编号',
  `value` varchar(2000) DEFAULT NULL COMMENT '对应值',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `values` varchar(50) DEFAULT NULL,
  `valid` tinyint(1) DEFAULT '1',
  `optdt` datetime DEFAULT NULL,
  `optid` smallint(6) DEFAULT '0',
  `receid` varchar(300) DEFAULT NULL,
  `recename` varchar(300) DEFAULT NULL,
  `explain` varchar(200) DEFAULT NULL COMMENT '说明',
  `comid` smallint(6) DEFAULT '0' COMMENT '所属单位id',
  PRIMARY KEY (`id`),
  KEY `num` (`num`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=706 DEFAULT CHARSET=utf8 COMMENT='系统选项';

-- ----------------------------
-- Records of xinhu_option
-- ----------------------------
INSERT INTO `xinhu_option` VALUES ('4', '流程分类', '0', '1', 'flowfenlei', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('5', '考勤', '0', '4', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('6', '表单类型', '0', '1', 'flowinputtype', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('7', '文本框', '0', '6', null, 'text', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('8', '日期', '0', '6', null, 'date', '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('9', '日期时间', '0', '6', null, 'datetime', '2', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('10', '时间', '0', '6', null, 'time', '3', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('11', '隐藏文本框', '0', '6', null, 'hidden', '4', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('12', '选择人员(单选)', '0', '6', null, 'changeuser', '7', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('13', '选择人员(多选)', '0', '6', null, 'changeusercheck', '8', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('14', '自定义', '0', '6', null, 'auto', '30', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('15', '数字', '0', '6', null, 'number', '5', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('16', '文本域', '0', '6', null, 'textarea', '6', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('17', '固定值', '0', '6', null, 'fixed', '11', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('18', '系统选项下拉框', '0', '6', null, 'rockcombo', '12', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('19', '下拉框', '0', '6', null, 'select', '13', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('20', 'html编辑器', '0', '6', null, 'htmlediter', '14', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('21', '选择部门人员组', '0', '6', null, 'changedeptusercheck', '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('22', '单个复选框', '0', '6', null, 'checkbox', '15', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('23', '多个复选框', '0', '6', null, 'checkboxall', '16', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('24', '选择部门', '0', '6', null, 'changedept', '9', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('25', '行政', '0', '4', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('26', '通知公告类型', '0', '1', 'gongtype', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('27', '通知公告', '0', '26', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('28', '规则制度', '0', '26', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('29', '会议室', '0', '1', 'hyname', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('30', '会客室', '0', '29', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('1', '信呼', '0', '0', 'xinhu', null, '0', null, '1', null, '0', null, null, '此记录禁止删除修改否则出问题', '0');
INSERT INTO `xinhu_option` VALUES ('32', '任务类型', '0', '151', 'worktype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('33', '任务等级', '0', '151', 'workgrade', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('34', 'bug', '0', '32', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('35', '改进', '0', '32', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('36', '设计', '0', '32', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('37', '低', '0', '33', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('38', '中', '0', '33', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('39', '高', '0', '33', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('40', '紧急', '0', '33', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('41', '任务状态', '0', '151', 'workstate', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('42', '待执行', '0', '41', null, '0', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('43', '执行中', '0', '41', null, '2', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('44', '已完成', '0', '41', null, '1', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('45', '中止', '0', '41', null, '3', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('46', '请假类型', '0', '144', 'kqqjkind', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('47', '事假', '0', '46', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('48', '病假', '0', '46', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('49', '年假', '0', '46', null, 'd', '0', null, '1', null, '0', null, null, 'd按天,w工作日,h按小时,z自然日 ', '0');
INSERT INTO `xinhu_option` VALUES ('50', '客户关系', '0', '1', 'crm', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('51', 'CRM', '0', '4', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('52', '客户来源', '0', '50', 'crmlaiyuan', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('53', '销售状态', '0', '50', 'crmstate', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('54', '网上开拓', '0', '52', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('55', '电话开拓', '0', '52', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('56', '跟进中', '0', '53', null, '0', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('57', '已成交', '0', '53', null, '1', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('58', '销售来源', '0', '50', 'custsalelai', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('59', '主动来访', '0', '58', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('60', '网上联系', '0', '58', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('61', '电话销售', '0', '58', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('62', '行政选项', '0', '1', 'goods', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('63', '互联网', '0', '83', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('64', '信呼', '0', '63', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('65', '办公耗材', '0', '83', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('66', '显示器', '0', '65', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('67', '鼠标', '0', '65', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('68', '主机', '0', '65', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('357', '编程', '0', '232', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('70', '资产品牌', '0', '62', 'rockbrand', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('71', '单位', '0', '62', 'rockunit', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('72', '台', '0', '71', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('73', '个', '0', '71', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('74', '件', '0', '71', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('75', '箱', '0', '71', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('76', '年', '0', '71', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('77', '月', '0', '71', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('78', '入库类型', '0', '62', 'kutype0', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('79', '出库类型', '0', '62', 'kutype1', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('80', '采购入库', '0', '78', null, '0', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('81', '领用出库', '0', '79', null, '0', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('82', '归返入库', '0', '78', null, '1', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('83', '物品分类', '0', '62', 'goodstype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('84', '销售出库', '0', '79', null, '1', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('85', '知识信息', '0', '26', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('86', '管理员文件夹目录', '0', null, 'folder1', 'success', '0', null, '1', '2016-10-26 20:52:17', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('87', '技术文档', '0', '86', null, null, '20', null, '1', '2016-08-08 22:07:41', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('88', '需求文档', '0', '86', null, null, '0', null, '1', '2016-08-08 22:08:27', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('89', '图片', '0', '86', null, null, '0', null, '1', '2016-08-08 22:44:10', '1', 'd1', '信呼开发团队', null, '0');
INSERT INTO `xinhu_option` VALUES ('92', '文件夹目录', '0', null, 'folder3', 'success', '0', null, '1', '2016-10-19 19:30:59', '3', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('93', '个人文件', '0', '92', null, null, '0', null, '1', '2016-08-09 13:57:06', '3', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('95', 'js文档', '0', '89', null, null, '0', null, '1', '2016-08-09 22:20:23', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('94', 'php文档', '0', '89', null, null, '0', null, '1', '2016-08-09 22:20:17', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('98', '文件夹目录', '0', null, 'folder4', 'success', '0', null, '1', '2016-10-19 19:31:27', '4', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('99', '公文选项', '0', '1', 'official', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('100', '公文类型', '0', '99', 'officialclass', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('101', '决议', '0', '100', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('102', '决定', '0', '100', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('103', '请示', '0', '100', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('104', '规定', '0', '100', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('105', '公文等级', '0', '99', 'officialgrade', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('106', '平急', '0', '105', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('107', '加急', '0', '105', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('108', '项目选项', '0', '1', 'project', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('109', '项目类型', '0', '108', 'projecttype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('110', '一般项目', '0', '109', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('111', '重要项目', '0', '109', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('112', '关键项目', '0', '109', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('113', '项目状态', '0', '108', 'projectstate', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('114', '待执行', '0', '113', null, '0', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('115', '执行中', '0', '113', null, '2', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('116', '已取消', '0', '113', null, '3', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('117', '已完成', '0', '113', null, '1', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('609', '管理公文人员', '0', '99', 'officialguan', '1', '3', null, '1', null, '0', null, null, '对应值设置人员id多个,分开', '0');
INSERT INTO `xinhu_option` VALUES ('128', '文件夹目录', '0', null, 'folder8', 'success', '0', null, '1', '2016-10-01 17:57:12', '8', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('130', '财务选项', '0', '1', 'finance', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('131', '付款方式', '0', '130', 'paytype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('132', '现金', '0', '131', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('133', '支付宝', '0', '131', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('134', '微信', '0', '131', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('135', '银行转账', '0', '131', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('136', '报销项目', '0', '130', 'finaitems', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('137', '市内交通费', '0', '136', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('138', '电话费', '0', '136', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('139', '快递费', '0', '136', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('140', '停车费', '0', '136', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('141', '文件夹目录', '0', null, 'folder5', 'success', '0', null, '1', '2016-09-08 21:13:56', '5', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('143', '服务文档', '0', '128', null, null, '0', null, '1', '2016-09-13 11:32:22', '8', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('144', '考勤选项', '0', '1', 'kaoqin', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('145', '人事考勤类型', '0', '144', 'kqkind', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('146', '增加年假', '0', '145', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('147', '增加哺乳假', '0', '145', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('148', '增加陪产假', '0', '145', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('149', '奖惩', '0', '26', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('150', '文件夹目录', '0', null, 'folder9', null, '0', null, '1', '2016-10-01 18:38:06', '9', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('151', '任务选项', '0', '1', 'work', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('154', '人事选项', '0', '1', 'usertype', null, '10', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('155', '学历', '0', '154', 'xueli', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('156', '小学', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('157', '初中', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('158', '高中', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('159', '中专', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('160', '大专', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('161', '本科', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('162', '自考本科', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('163', '研究生', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('164', '博士', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('165', '其它', '0', '155', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('166', '合同类型', '0', '154', 'userhttype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('167', '劳动合同', '0', '166', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('168', '离职类型', '0', '154', 'redundtype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('169', '自动离职', '0', '168', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('170', '退休', '0', '168', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('171', '病辞', '0', '168', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('172', '辞退', '0', '168', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('173', '辞职', '0', '168', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('174', '月份', '0', '6', null, 'month', '17', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('175', '编号', '0', '6', null, 'num', '21', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('176', '客户类型', '0', '50', 'crmtype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('177', '互联网', '0', '176', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('178', '软件', '0', '176', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('179', '个体经营', '0', '176', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('180', '个人', '0', '176', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('181', '其它', '0', '176', null, null, '20', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('182', '政府机构', '0', '176', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('183', '人事调动类型', '0', '154', 'transfertype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('184', '平调', '0', '183', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('185', '晋升', '0', '183', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('186', '降职', '0', '183', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('190', '奖惩结果', '0', '154', 'rewardresult', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('191', '奖励', '0', '190', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('192', '警告', '0', '190', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('193', '辞退', '0', '190', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('194', '降职', '0', '190', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('195', '张飞文件夹目录', '0', null, 'folder6', 'success', '0', null, '1', '2016-10-24 21:27:22', '6', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('196', '个人经历', '0', '195', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('197', '知识分类', '0', null, 'knowledgetype', null, '0', null, '1', '2016-10-27 21:10:24', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('198', '工作指南', '0', '197', null, 'title,typename,recename,zuozhe,indate,optname,caozuo', '0', null, '1', '2017-04-21 09:26:50', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('199', '工作效率', '0', '198', null, null, '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('200', '工作目标', '0', '198', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('201', '规章制度', '0', '197', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('202', '工作规范', '0', '197', null, null, '0', null, '1', '2016-10-26 20:54:54', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('203', '资产分类', '0', '62', 'assetstype', null, '0', null, '1', '2016-10-27 21:14:45', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('204', '打印机', '0', '203', null, '', '0', null, '1', '2017-04-21 09:26:50', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('205', '固定资产来源', '0', '62', 'wplaiyuan', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('206', '电脑', '0', '203', null, null, '2', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('207', '台式电脑', '0', '206', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('208', '笔记本', '0', '206', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('209', '平板', '0', '206', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('210', '一体机', '0', '206', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('211', '仓库', '0', '62', 'warehouse', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('212', '购入', '0', '205', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('213', '自建', '0', '205', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('214', '仓库1', '0', '211', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('215', '仓库2', '0', '211', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('216', '联想', '0', '70', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('217', '惠普', '0', '70', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('218', '车辆类型', '0', '327', 'cartype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('219', '小型车', '0', '218', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('220', '轿车', '0', '218', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('221', '车辆品牌', '0', '327', 'carbrand', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('222', '奔驰', '0', '221', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('223', '奥迪', '0', '221', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('224', '夏利', '0', '221', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('226', '题库分类', '0', null, 'knowtikutype', null, '0', null, '1', '2016-10-27 21:17:22', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('227', '官网知识', '0', '226', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('228', 'OA题库', '0', '226', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('229', '会议室1', '0', '29', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('230', '会议室2', '0', '29', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('231', '图书分类', '0', null, 'booktype', null, '0', null, '1', '2016-10-28 23:15:11', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('232', '计算机', '0', '231', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('233', '管理书籍', '0', '231', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('244', '印章证照类型', '0', '62', 'sealtype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('245', '合同章', '0', '244', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('246', '法人章', '0', '244', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('247', '财务章', '0', '244', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('248', '公章', '0', '244', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('249', '调休', '0', '46', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('250', '单图片上传', '0', '6', null, 'uploadimg', '19', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('251', '打卡异常类型', '0', '144', 'kqerrtype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('252', '忘记打卡', '0', '251', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('253', '打卡不成功', '0', '251', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('254', '邮箱文本框', '0', '6', null, 'email', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('255', 'gerenvcard_1选项', '0', null, 'gerenvcard_1', null, '0', null, '1', '2016-12-20 10:52:49', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('256', '同事', '0', '255', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('257', '盟友', '0', '255', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('258', '敌人', '0', '255', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('259', 'gerenvcard_8选项', '0', null, 'gerenvcard_8', null, '0', null, '1', '2016-12-20 11:23:21', '8', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('260', '同事', '0', '259', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('261', 'gerenvcard_5选项', '0', null, 'gerenvcard_5', null, '0', null, '1', '2016-12-20 11:30:38', '5', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('262', '弹框下拉选择(单选)', '0', '6', null, 'selectdatafalse', '22', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('263', '弹框下拉选择(多选)', '0', '6', null, 'selectdatatrue', '23', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('365', '默认1天上班小时', '0', '144', 'kqsbtime', '0', '0', null, '1', null, '0', null, null, '设置0默认读取考勤规则下的', '0');
INSERT INTO `xinhu_option` VALUES ('277', '用户bug1', '0', null, 'adminbug1', '2017-02-15 10:18:19', '0', null, '1', '2017-02-15 10:18:19', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('273', '文件上传(可多选)', '0', '6', null, 'uploadfile', '20', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('524', '查看全部用户数据', '0', '401', 'sysalluview', null, '0', null, '1', null, '0', null, null, '对应值设置人员id，多个,分开，多单位模式时可查看全部用户', '0');
INSERT INTO `xinhu_option` VALUES ('286', '模块列定义', '0', '-1007', 'columns_customer_shate', 'name,suoname,type,laiyuan,unitname,isstat,caozuo', '0', null, '1', '2017-07-13 19:02:20', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('287', '模块列定义', '0', '-1007', 'columns_customer_dist', 'name,suoname,type,laiyuan,unitname,isgys,linkname', '0', null, '1', '2017-04-21 11:55:50', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('288', '模块列定义', '0', '-1007', 'columns_customer_', 'name,suoname,type,unitname,mobile,shate,htshu,moneyz,moneyd,lastdt,caozuo', '0', null, '1', '2021-06-07 15:03:28', '1', null, null, null, '1');
INSERT INTO `xinhu_option` VALUES ('294', '技术姿势', '0', '226', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('295', 'PHP知识', '0', '294', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('296', 'JS知识', '0', '294', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('298', '模块列定义', '0', '-1004', 'columns_work_', 'title,type,grade,dist,startdt,enddt,ddname,score,optname,optdt,caozuo', '0', null, '1', '2017-05-11 09:55:28', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('299', '模块列定义', '0', '-1004', 'columns_work_all', 'title,type,grade,dist,startdt,enddt,ddname,score,mark,optname,optdt,caozuo', '0', null, '1', '2018-04-15 16:10:26', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('300', '收款章', '0', '244', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('302', '开发部文件夹目录', '0', null, 'deptfolderdev', null, '0', null, '1', '2017-05-27 17:36:02', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('303', '技术文档', '0', '302', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('304', '赵子龙文件夹目录', '0', null, 'folder7', 'success', '0', null, '1', '2017-05-27 17:49:46', '7', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('305', '公文密级', '0', '99', 'officialmiji', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('306', '私密', '0', '305', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('307', '机密', '0', '305', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('308', '绝密', '0', '305', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('309', '特急', '0', '105', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('310', '特提', '0', '105', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('311', '公文文件类别', '0', '99', 'officialfiletype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('312', '中央省委', '0', '311', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('313', '单选框', '0', '6', null, 'radio', '24', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('320', '车辆登记类型', '0', '327', 'carotype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('321', '加油', '0', '320', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('322', '强险', '0', '320', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('323', '商业险', '0', '320', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('324', '行驶证', '0', '320', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('325', '违章', '0', '320', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('326', '事故', '0', '320', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('327', '车辆管理选项', '0', '62', 'carm', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('328', '车辆几天内提醒(多个,分开)', '0', '327', 'cartodo', '0,3,7,10,30,45', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('331', '过桥费', '0', '320', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('332', '高速费', '0', '320', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('333', '客户合同到期几天内提醒', '0', '50', 'crmtodo', '0,3,7,10,30,45', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('334', '可直接申请请假类型', '0', '144', 'kqsqtype', '病假,事假,陪产假', '0', null, '1', null, '0', null, null, '多个,分开，没有设置的请假类型时，申请请假条需要有剩余假期才可以申请。', '0');
INSERT INTO `xinhu_option` VALUES ('335', '人员状态', '0', '154', 'userstate', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('336', '试用期', '0', '335', null, '0', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('337', '正式', '0', '335', null, '1', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('338', '实习生', '0', '335', null, '2', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('339', '兼职', '0', '335', null, '3', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('340', '临时工', '0', '335', null, '4', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('341', '离职', '0', '335', null, '5', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('342', 'gerenvcar选项', '0', null, 'gerenvcar', null, '0', null, '1', '2017-07-21 13:37:48', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('344', '支', '0', '71', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('351', '发票类型', '0', '130', 'openpiaotype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('352', '增值税普通发票', '0', '351', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('353', '增值税专用发票', '0', '351', null, null, '2', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('354', '定额发票', '0', '351', null, null, '3', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('355', '复印机', '0', '203', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('356', '笔', '0', '65', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('359', '发文字号', '0', '99', 'officiazinum', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('360', '信呼', '0', '359', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('400', '定时清理数据', '0', '144', 'kqcleartime', '6', '0', null, '1', null, '0', null, null, '0不清理,1一个月前,2个月,以此类推，清理打卡记录', '0');
INSERT INTO `xinhu_option` VALUES ('363', '选择部门(多选)', '0', '6', null, 'changedeptcheck', '9', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('367', '初始入库', '0', '78', null, '2', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('368', '模块列定义', '0', '-1007', 'columns_customer_gys', 'name,suoname,type,unitname,tel,address,status,linkname,explain,caozuo', '0', null, '1', '2017-11-11 15:04:39', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('369', '行政人事部文件夹目录', '0', null, 'deptfolder4', null, '0', null, '1', '2017-11-13 15:38:38', '4', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('381', '调拨入库', '0', '78', null, '3', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('382', '调拨出库', '0', '79', null, '3', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('383', '模块列定义', '0', '-1007', 'columns_customer_ghai', 'name,suoname,type,unitname,sheng,shi,status,caozuo', '0', null, '1', '2018-03-06 14:22:28', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('387', '生日短信提醒', '0', '154', 'smsbirthday', null, '0', null, '1', null, '0', null, null, '对应值设置：短信模版编号为空不提醒', '0');
INSERT INTO `xinhu_option` VALUES ('418', '模块列定义', '0', '-1071', 'columns_hrcheck_tjall', 'deptname,applyname,title,startdt,pfrenids,fen', '0', null, '1', '2018-09-07 22:23:50', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('417', '陪产假', '0', '46', null, 'z', '0', null, '1', null, '0', null, null, '男士专用', '0');
INSERT INTO `xinhu_option` VALUES ('401', '系统选项', '0', '1', 'sysoption', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('402', '用户初始化', '0', '401', 'sysuserinit', '否', '0', null, '1', null, '0', null, null, '对应值写:是，就可以多次初始化用户。', '0');
INSERT INTO `xinhu_option` VALUES ('403', '短信平台', '0', '401', 'syssmsplat', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('537', '增加调休', '0', '145', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('405', '阿里云(短信服务)', '0', '403', null, 'alisms', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('408', '自动放入公海', '0', '50', 'crmaddghai', '0', '1', null, '1', null, '0', null, null, '对应值0不限制,7天未根据自动放入公海', '0');
INSERT INTO `xinhu_option` VALUES ('409', '模块列定义', '0', '-1007', 'columns_customer_all', 'name,suoname,type,unitname,isstat,isgys,htshu,moneyz,moneyd,lastdt,caozuo', '0', null, '1', '2018-07-07 10:21:00', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('410', '我短信服务', '0', '403', null, 'mysms', '2', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('413', '每月可申请打卡异常次数', '0', '144', 'kqerrapply', '0', '0', null, '1', null, '0', null, null, '对应值设置0不限制', '0');
INSERT INTO `xinhu_option` VALUES ('411', '加班可调休', '0', '144', 'kqjiabanuse', '0', '0', null, '1', null, '0', null, null, '加班换调休有效时间0不限制3个月内', '0');
INSERT INTO `xinhu_option` VALUES ('412', '年假可使用', '0', '144', 'kqnianjiause', '0', '0', null, '1', null, '0', null, null, '年假可使用时间0不限制,1一个月,12一年', '0');
INSERT INTO `xinhu_option` VALUES ('419', '营业执照', '0', '244', null, null, '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('420', '组织机构代码证', '0', '244', null, null, '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('421', '税务登记证', '0', '244', null, null, '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('422', '机构信用代码证', '0', '244', null, null, '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('423', '发票专用章', '0', '244', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('424', '云片网', '0', '403', null, 'yunpsms', '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('425', '增值税普通电子发票', '0', '351', null, null, '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('426', '自己企业', '0', '52', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('427', '公文单位', '0', '99', 'officiaunit', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('428', '信呼开发团队', '0', '427', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('429', '厦门市人民政府', '0', '427', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('430', '厦门市税务局', '0', '427', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('431', '福建省人民政府', '0', '427', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('433', '新闻分类', '0', '62', 'newstype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('434', '安全信息', '0', '433', null, 'home', '1', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('435', '资讯', '0', '433', null, 'home', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('436', '红头标题', '0', '99', 'officiatitle', '	信呼开发团队文件', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('437', '红头模版文件分类', '0', '99', 'officialmbfenlei', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('438', '通用', '0', '437', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('439', '厦门人民政府文件', '0', '436', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('440', '福建省人民政府文件', '0', '436', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('441', '信呼开发团队文件', '0', '436', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('449', 'gerenvcard_4选项', '0', null, 'gerenvcard_4', null, '0', null, '1', '2019-06-04 16:22:19', '4', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('451', 'knowledgetype_qz选项', '0', null, 'knowledgetype_qz', null, '0', null, '1', '2019-06-05 18:10:38', '4', null, null, null, '2');
INSERT INTO `xinhu_option` VALUES ('452', 'knowtikutype_qz选项', '0', null, 'knowtikutype_qz', null, '0', null, '1', '2019-06-05 18:10:46', '4', null, null, null, '2');
INSERT INTO `xinhu_option` VALUES ('453', '泉州公司', '0', '451', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('454', '官网知识', '0', '452', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('455', '技术姿势', '0', '452', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('456', 'PHP知识', '0', '455', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('457', 'goodstype_qz选项', '0', null, 'goodstype_qz', null, '0', null, '1', '2019-06-06 18:50:51', '4', null, null, null, '2');
INSERT INTO `xinhu_option` VALUES ('458', 'assetstype_qz选项', '0', null, 'assetstype_qz', null, '0', null, '1', '2019-06-06 18:51:03', '4', null, null, null, '2');
INSERT INTO `xinhu_option` VALUES ('459', '办公耗材', '0', '457', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('460', '笔', '0', '459', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('461', '泉州资产', '0', '458', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('462', 'booktype_qz选项', '0', null, 'booktype_qz', null, '0', null, '1', '2019-06-07 13:26:40', '4', null, null, null, '2');
INSERT INTO `xinhu_option` VALUES ('463', '计算机', '0', '462', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('464', '编程', '0', '463', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('465', 'PC首页自动刷新(秒)', '0', '401', 'syshometime', '200', '0', null, '1', null, '0', null, null, '自动刷新秒数最低5秒', '0');
INSERT INTO `xinhu_option` VALUES ('466', '页面大小设置', '0', '401', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('467', '弹框默认大小', '0', '466', 'tanwidth', '900x800', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('468', '录入页最大宽', '0', '466', 'inputwidth', '750', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('469', '详情页最大宽', '0', '466', 'xiangwidth', '700', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('471', '模块列定义', '0', '-1069', 'columns_officic_', 'num,title,unitsame,optdt,caozuo', '0', null, '1', '2019-07-12 14:05:28', '1', null, null, null, '1');
INSERT INTO `xinhu_option` VALUES ('475', 'APP选项设置', '0', '401', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('476', '首页是否显示快捷应用', '0', '475', 'appsy_yyshow', '是', '0', null, '1', null, '0', null, null, '不显示对应值改成“否”', '0');
INSERT INTO `xinhu_option` VALUES ('477', '首页是否显示公告滚动图片', '0', '475', 'appsy_ggshow', '否', '0', null, '1', null, '0', null, null, '不显示对应值改成“否”', '0');
INSERT INTO `xinhu_option` VALUES ('478', '首页是否显示新闻滚动图片', '0', '475', 'appsy_xwshow', '否', '0', null, '1', null, '0', null, null, '显示对应值改成“是”', '0');
INSERT INTO `xinhu_option` VALUES ('483', '记账收入类型', '0', '130', 'finjishoutype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('484', '记账支出类型', '0', '130', 'finjizhitype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('485', '转账', '0', '483', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('486', '产品收入', '0', '483', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('487', '转账', '0', '484', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('488', '工资', '0', '484', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('489', '报销', '0', '484', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('490', '客户合同分类', '0', '50', 'crmhtfenlei', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('491', '普通合同', '0', '490', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('492', '客户跟进方式', '0', '50', 'custgentype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('493', '电话联系', '0', '492', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('494', '上门拜访', '0', '492', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('495', '微信联系', '0', '492', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('496', '其他..', '0', '492', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('501', '通知公告短信模版编号', '0', '62', 'gongsmstpl', null, '0', null, '1', null, '0', null, null, '如使用阿里短信这个就要设置', '0');
INSERT INTO `xinhu_option` VALUES ('596', '厦府', '0', '359', null, null, '0', null, '1', '2021-06-23 21:38:37', '1', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('599', '其它..', '0', '26', null, null, '999', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('574', '工程选项', '0', '1', 'gongcheng', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('575', '项目类型', '0', '574', 'xiangmutype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('576', '房建', '0', '575', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('577', '桥梁', '0', '575', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('525', '公开', '0', '305', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('547', '选地图位置', '0', '6', null, 'ditumap', '25', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('548', '退货入库', '0', '78', null, '4', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('549', '退货类型', '0', '62', 'tuileixing', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('550', '普通退货', '0', '549', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('551', '无理由退货', '0', '549', '', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('554', 'dangantype选项', '0', null, 'dangantype', null, '0', null, '1', '2020-09-27 18:02:05', '1', null, null, null, '1');
INSERT INTO `xinhu_option` VALUES ('555', '人员资料', '0', '554', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('556', '技术档案', '0', '554', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('590', '督查督办类型', '0', '99', 'officidutype', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('573', '清除会话记录', '0', '401', 'chatrecorddt', '180', '0', null, '1', null, '0', null, null, '清理长旧记录对应值设置天数入50天前写50', '0');
INSERT INTO `xinhu_option` VALUES ('578', '公路', '0', '575', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('579', '园林景观', '0', '575', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('580', '铁路', '0', '575', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('581', '其他', '0', '575', null, null, '22', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('582', '项目状态', '0', '574', 'xiangmustate', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('583', '竞标中', '0', '582', null, '0', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('584', '中标', '0', '582', null, '1', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('585', '落标', '0', '582', null, '2', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('586', '施工中', '0', '582', null, '3', '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('587', '系统集成', '0', '575', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('588', '项目动态类型', '0', '574', 'xiangmudtlx', null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('589', '会议纪要', '0', '588', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('591', '类型一', '0', '590', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('592', '类型二', '0', '590', null, null, '0', null, '1', null, '0', null, null, null, '0');
INSERT INTO `xinhu_option` VALUES ('594', '会议短信模版编号', '0', '62', 'meetsmstpl', null, '0', null, '1', null, '0', null, null, '如使用阿里短信这个就要设置', '0');
INSERT INTO `xinhu_option` VALUES ('595', '会议取消短信模版编号', '0', '62', 'meetsmstplqx', null, '0', null, '1', null, '0', null, null, '如使用阿里短信这个就要设置', '0');

-- ----------------------------
-- Table structure for `xinhu_project`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_project`;
CREATE TABLE `xinhu_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) DEFAULT '0',
  `type` varchar(20) DEFAULT NULL COMMENT '项目类型',
  `num` varchar(20) DEFAULT NULL COMMENT '编号',
  `status` tinyint(1) DEFAULT '0',
  `title` varchar(100) DEFAULT NULL COMMENT '项目名称',
  `startdt` datetime DEFAULT NULL COMMENT '开始时间',
  `enddt` datetime DEFAULT NULL COMMENT '预计结束时间',
  `fuze` varchar(20) DEFAULT NULL COMMENT '负责人',
  `fuzeid` varchar(50) DEFAULT NULL,
  `runuser` varchar(100) DEFAULT NULL COMMENT '执行人员',
  `runuserid` varchar(100) DEFAULT NULL,
  `progress` smallint(6) DEFAULT '0' COMMENT '进度',
  `viewuser` varchar(100) DEFAULT NULL COMMENT '授权查看',
  `viewuserid` varchar(100) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL COMMENT '说明备注',
  `optid` smallint(6) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `optdt` datetime DEFAULT NULL,
  `adddt` datetime DEFAULT NULL COMMENT '添加时间',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='项目表';

-- ----------------------------
-- Records of xinhu_project
-- ----------------------------
INSERT INTO `xinhu_project` VALUES ('1', '0', '关键项目', 'xinhuoa', '3', '信呼OA办公系统维护开发', '2016-08-01 10:28:11', null, '管理员', '1', '开发部', 'd2', '26', null, null, '系统开发', '1', '管理员', '2019-04-22 10:29:13', '2019-04-22 10:29:13', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_reads`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_reads`;
CREATE TABLE `xinhu_reads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(30) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  `optid` smallint(6) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `adddt` datetime DEFAULT NULL COMMENT '第一次浏览',
  `stotal` smallint(6) DEFAULT '1' COMMENT '流程次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_2` (`table`,`mid`,`optid`),
  KEY `table` (`table`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单据浏览记录表';

-- ----------------------------
-- Records of xinhu_reads
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_receipt`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_receipt`;
CREATE TABLE `xinhu_receipt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modenum` varchar(30) DEFAULT '0' COMMENT '对应模块编号',
  `modename` varchar(30) DEFAULT NULL COMMENT '模块名称',
  `table` varchar(30) DEFAULT NULL COMMENT '对应主表',
  `mid` int(11) DEFAULT '0' COMMENT '主表Id',
  `uid` int(11) DEFAULT '0' COMMENT '对应人员',
  `receid` varchar(4000) DEFAULT NULL COMMENT '发送给',
  `recename` varchar(200) DEFAULT NULL,
  `receids` varchar(4000) DEFAULT NULL COMMENT '已确认人员Id',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `explain` varchar(500) DEFAULT NULL COMMENT '内容',
  `ushuz` int(11) DEFAULT '0' COMMENT '总人数',
  `ushuy` int(11) DEFAULT '0' COMMENT '已确认',
  `status` tinyint(1) DEFAULT '1',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `modenum` (`modenum`,`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='回执确认表';

-- ----------------------------
-- Records of xinhu_receipt
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_regcheck`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_regcheck`;
CREATE TABLE `xinhu_regcheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `pass` varchar(50) DEFAULT NULL COMMENT '密码',
  `rzdept` varchar(50) DEFAULT NULL COMMENT '入职部门',
  `rzdeptid` int(11) DEFAULT '0' COMMENT '入职部门',
  `rzzhiwei` varchar(50) DEFAULT NULL COMMENT '职位',
  `rzid` int(11) DEFAULT '0',
  `sex` varchar(10) DEFAULT NULL COMMENT '性别',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xinhu_regcheck
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_repair`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_repair`;
CREATE TABLE `xinhu_repair` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `assetm` varchar(100) DEFAULT NULL COMMENT '报修资产',
  `reason` varchar(500) DEFAULT NULL COMMENT '保修原因',
  `reasons` varchar(500) DEFAULT NULL COMMENT '实际原因',
  `iswx` tinyint(1) DEFAULT '0' COMMENT '需要外修',
  `money` decimal(8,2) DEFAULT '0.00' COMMENT '维修金额',
  `wxname` varchar(50) DEFAULT NULL COMMENT '维修人员',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='维修报修';

-- ----------------------------
-- Records of xinhu_repair
-- ----------------------------
INSERT INTO `xinhu_repair` VALUES ('1', '1', '2017-07-07 13:12:53', '1', '管理员', '2017-07-07', null, '1', '1', '打印机', '打印机突然打印不了字了', '没碳粉了，换一下就好了', '0', '0.00', '大乔', '0');
INSERT INTO `xinhu_repair` VALUES ('2', '3', '2017-07-07 13:37:28', '3', '小乔', '2017-07-07', null, '1', '1', '我电脑坏了', '我电脑坏了开不了，一按下键盘就重启。', '键盘坏了，需要外修', '1', '30.00', '科技人员', '0');

-- ----------------------------
-- Table structure for `xinhu_reward`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_reward`;
CREATE TABLE `xinhu_reward` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `object` varchar(30) DEFAULT NULL COMMENT '奖惩对象',
  `objectid` smallint(6) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '奖惩类型0奖励,1处罚',
  `result` varchar(50) DEFAULT NULL COMMENT '奖惩结果',
  `money` mediumint(6) DEFAULT NULL COMMENT '奖惩金额',
  `happendt` datetime DEFAULT NULL COMMENT '发生时间',
  `hapaddress` varchar(50) DEFAULT NULL COMMENT '发生地点',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='奖惩';

-- ----------------------------
-- Records of xinhu_reward
-- ----------------------------
INSERT INTO `xinhu_reward` VALUES ('2', '1', '2016-10-20 21:16:24', '1', '管理员', '2016-10-20', '对客户言语不确当被投诉了，哈哈。', '1', '1', '信呼客服', '8', '1', '警告', '20', '2016-10-06 21:15:27', '公司', '0');
INSERT INTO `xinhu_reward` VALUES ('3', '1', '2020-06-24 11:17:56', '1', '管理员', '2020-05-15', '发给我自己才能测试', '1', '1', '管理员', '1', '0', '奖励', '50', '2020-05-15 11:58:00', '办公室', '1');

-- ----------------------------
-- Table structure for `xinhu_scheduld`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_scheduld`;
CREATE TABLE `xinhu_scheduld` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `uid` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `schid` int(11) DEFAULT '0' COMMENT '日程的id',
  `receid` varchar(500) DEFAULT NULL,
  `recename` varchar(500) DEFAULT NULL COMMENT '提醒给',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `startdt` datetime DEFAULT NULL COMMENT '日程时间',
  `distid` int(11) DEFAULT '0' COMMENT '分配给人',
  `distren` varchar(50) DEFAULT NULL COMMENT '待办人',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='日程待办';

-- ----------------------------
-- Records of xinhu_scheduld
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_schedule`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_schedule`;
CREATE TABLE `xinhu_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `startdt` datetime DEFAULT NULL,
  `enddt` datetime DEFAULT NULL,
  `uid` smallint(6) DEFAULT NULL COMMENT '用户Id',
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `mid` int(11) DEFAULT '0' COMMENT '主Id',
  `ratecont` varchar(500) DEFAULT NULL COMMENT '频率',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `rate` varchar(5) DEFAULT NULL,
  `rateval` varchar(50) DEFAULT NULL,
  `txsj` tinyint(1) DEFAULT '0' COMMENT '是否提醒',
  `status` tinyint(1) DEFAULT '1',
  `receid` varchar(100) DEFAULT NULL,
  `recename` varchar(100) DEFAULT NULL COMMENT '提醒给',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `isdai` tinyint(1) DEFAULT '0' COMMENT '是否创建日程待办',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='日程';

-- ----------------------------
-- Records of xinhu_schedule
-- ----------------------------
INSERT INTO `xinhu_schedule` VALUES ('1', '每月初提醒考勤是否正常', '2016-08-01 08:00:00', null, '1', '2017-02-27 10:54:57', '管理员', '0', null, null, 'm', '1', '1', '1', 'd1', '信呼开发团队', '0', '0');
INSERT INTO `xinhu_schedule` VALUES ('2', '每周五下班前提交这周报告给主管', '2016-08-01 17:00:00', null, '1', '2016-08-30 22:38:48', '管理员', '0', null, null, 'w', '5', '1', '1', null, null, '0', '0');
INSERT INTO `xinhu_schedule` VALUES ('5', '检查当月是否缴社保了', '2019-11-01 08:10:00', null, '1', '2020-01-06 13:57:14', '管理员', '0', null, null, 'm', '4,5,6', '1', '1', null, null, '1', '0');
INSERT INTO `xinhu_schedule` VALUES ('4', '该起床拉', '2017-04-17 08:00:00', null, '3', '2017-04-17 08:43:03', '小乔', '0', null, null, 'd', null, '1', '1', null, null, '0', '0');

-- ----------------------------
-- Table structure for `xinhu_seal`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_seal`;
CREATE TABLE `xinhu_seal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '印章名称',
  `type` varchar(10) DEFAULT NULL COMMENT '印章类型',
  `bgname` varchar(50) DEFAULT NULL COMMENT '保管人',
  `bgid` varchar(50) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `sealimg` varchar(100) DEFAULT NULL COMMENT '对应印章图片',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `startdt` date DEFAULT NULL COMMENT '签发日期',
  `enddt` date DEFAULT NULL COMMENT '截止日期',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='印章表';

-- ----------------------------
-- Records of xinhu_seal
-- ----------------------------
INSERT INTO `xinhu_seal` VALUES ('1', '信呼团队收款章', '收款章', '张飞', '6', '2018-07-12 17:18:34', '0', 'http://www.rockoa.com/images/zhang_ys.png', null, null, null, '0');
INSERT INTO `xinhu_seal` VALUES ('2', '信呼团队公章', '公章', '管理员', '1', '2017-05-18 21:04:59', '1', 'http://www.rockoa.com/images/gz.png', null, null, null, '0');
INSERT INTO `xinhu_seal` VALUES ('3', '信呼开发团队营业执照正本', '营业执照', '管理员', '1', '2019-06-16 11:59:21', '0', '{PLATURL}upload/xinhuoa/2019-06/16_11591424.jpg', null, '2018-09-01', null, '1');

-- ----------------------------
-- Table structure for `xinhu_sealapl`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_sealapl`;
CREATE TABLE `xinhu_sealapl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `sealid` varchar(100) DEFAULT '' COMMENT '印章id',
  `sealname` varchar(200) DEFAULT NULL COMMENT '印章名称',
  `isout` tinyint(1) DEFAULT '0' COMMENT '使用方式',
  `mknum` varchar(50) DEFAULT NULL COMMENT '关联模块单据',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='印章申请使用表';

-- ----------------------------
-- Records of xinhu_sealapl
-- ----------------------------
INSERT INTO `xinhu_sealapl` VALUES ('1', '1', '2020-11-02 11:57:23', '1', '管理员', '2020-11-02', '信呼〔2019〕1号[决定]关于任命磐石为董事长通知，申请使用印章', '1', '1', '2', '信呼团队公章', '2', 'officia,1', '1');

-- ----------------------------
-- Table structure for `xinhu_sjoin`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_sjoin`;
CREATE TABLE `xinhu_sjoin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(10) DEFAULT NULL COMMENT '类型',
  `mid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  `indate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_mid` (`type`,`mid`) USING BTREE,
  KEY `type_sid` (`type`,`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COMMENT='后台菜单权限';

-- ----------------------------
-- Records of xinhu_sjoin
-- ----------------------------
INSERT INTO `xinhu_sjoin` VALUES ('120', 'ug', '5', '3', '2020-07-29 13:23:57');
INSERT INTO `xinhu_sjoin` VALUES ('23', 'gu', '1', '7', null);
INSERT INTO `xinhu_sjoin` VALUES ('89', 'ug', '8', '4', '2019-06-03 19:29:32');
INSERT INTO `xinhu_sjoin` VALUES ('123', 'ug', '1', '4', '2020-11-02 18:02:31');
INSERT INTO `xinhu_sjoin` VALUES ('15', 'ug', '6', '1', '2017-07-08 18:51:03');
INSERT INTO `xinhu_sjoin` VALUES ('122', 'ug', '1', '3', '2020-11-02 18:02:31');
INSERT INTO `xinhu_sjoin` VALUES ('129', 'ug', '2', '3', '2021-01-15 12:13:01');
INSERT INTO `xinhu_sjoin` VALUES ('131', 'gu', '1', '3', null);
INSERT INTO `xinhu_sjoin` VALUES ('113', 'ug', '4', '3', '2020-01-08 20:22:42');
INSERT INTO `xinhu_sjoin` VALUES ('88', 'ug', '8', '1', '2019-06-03 19:29:32');
INSERT INTO `xinhu_sjoin` VALUES ('130', 'gu', '1', '1', null);
INSERT INTO `xinhu_sjoin` VALUES ('128', 'ug', '2', '1', '2021-01-15 12:13:01');
INSERT INTO `xinhu_sjoin` VALUES ('112', 'ug', '4', '1', '2020-01-08 20:22:42');
INSERT INTO `xinhu_sjoin` VALUES ('119', 'ug', '5', '2', '2020-07-29 13:23:57');

-- ----------------------------
-- Table structure for `xinhu_subscribe`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_subscribe`;
CREATE TABLE `xinhu_subscribe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL COMMENT '订阅标题',
  `cont` varchar(200) DEFAULT NULL COMMENT '订阅时提醒内容',
  `explain` varchar(100) DEFAULT NULL COMMENT '订阅说明',
  `optid` int(11) DEFAULT '0' COMMENT '操作人ID',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `optdt` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `suburl` varchar(1000) DEFAULT NULL COMMENT '订阅地址',
  `suburlpost` varchar(4000) DEFAULT NULL COMMENT '订阅地址post参数',
  `lastdt` datetime DEFAULT NULL COMMENT '最后运行时间',
  `shateid` varchar(300) DEFAULT NULL,
  `shatename` varchar(300) DEFAULT NULL COMMENT '共享给',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订阅管理表';

-- ----------------------------
-- Records of xinhu_subscribe
-- ----------------------------
INSERT INTO `xinhu_subscribe` VALUES ('1', '考勤统计简表({month-1})', '{month-1}月份人员考勤统计简表', '订阅上月考勤统计表', '1', '管理员', '2017-09-03 20:11:24', '1', 'aW5kZXgucGhwP2E9cHVibGljc3RvcmUmbT1rYW9xaW4mZD1tYWluJmFqYXhib29sPXRydWUmcm5kPTY0NjM0OQ::', 'dGFibGVuYW1lX2FiYz11c2VyaW5mbyZsb2FkY2k9MSZzdG9yZWJlZm9yZWFjdGlvbj1rcXRvdGFsYmVmb3Jlc2hvdyZzdG9yZWFmdGVyYWN0aW9uPWtxdG90YWxhZnRlcnNob3cmc3RhcnQ9MCZsaW1pdD0xMDAwMCZhdHlwZT1hbGwmZXhlY2xkb3duPXRydWUmZXhjZWx0aXRsZT3ogIPli6Tnu5.orqHnroDooagmZXhjZWxmaWVsZHM9ZGVwdG5hbWUsbmFtZSxyYW5raW5nLHN0YXRlLHN0YXRlMCxzdGF0ZTEsc3RhdGUyLGppYWJhbixvdXRjaSxlcnJjaSxzYmRheSx5c2JkYXksd2VpZGsmZXhjZWxoZWFkZXI96YOo6ZeoLOWnk!WQjSzogYzkvY0s5Lq65ZGY54q25oCBLOato!W4uCzov5.liLAs5pep6YCALOWKoOePrSjml7YpLOWkluWHuijmrKEpLOW8guW4uCjmrKEpLOW6lOS4iuePrSjlpKkpLOW3suS4iuePrSjlpKkpLOacquaJk!WNoSZtb250aD17bW9udGgtMX0:', '2020-10-01 21:26:32', null, null, '0');

-- ----------------------------
-- Table structure for `xinhu_subscribeinfo`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_subscribeinfo`;
CREATE TABLE `xinhu_subscribeinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '对应定ID',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `cont` varchar(200) DEFAULT NULL COMMENT '内容',
  `optdt` datetime DEFAULT NULL,
  `filepath` varchar(200) DEFAULT NULL COMMENT '文件路径',
  `receid` varchar(4000) DEFAULT NULL COMMENT '发送给',
  `recename` varchar(200) DEFAULT NULL COMMENT '对应人',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='订阅内容信息';

-- ----------------------------
-- Records of xinhu_subscribeinfo
-- ----------------------------
INSERT INTO `xinhu_subscribeinfo` VALUES ('5', '1', '考勤统计简表(2020-09)', '2020-09月份人员考勤统计简表', '2020-10-01 21:26:32', 'upload/logs/2020-10/考勤统计简表(2020-09)_01_212632.html', '1', '管理员', '0');

-- ----------------------------
-- Table structure for `xinhu_task`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_task`;
CREATE TABLE `xinhu_task` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `fenlei` varchar(10) DEFAULT NULL COMMENT '类型分类',
  `url` varchar(100) DEFAULT NULL COMMENT '运行地址',
  `type` varchar(100) DEFAULT NULL,
  `time` varchar(200) DEFAULT NULL,
  `ratecont` varchar(500) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '是否启用',
  `state` tinyint(1) DEFAULT '0' COMMENT '最后状态',
  `lastdt` datetime DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `sort` smallint(6) DEFAULT '0',
  `startdt` datetime DEFAULT NULL COMMENT '开始时间',
  `lastcont` varchar(500) DEFAULT NULL,
  `explain` varchar(200) DEFAULT NULL COMMENT '说明',
  `todoid` varchar(200) DEFAULT NULL,
  `todoname` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='计划任务';

-- ----------------------------
-- Records of xinhu_task
-- ----------------------------
INSERT INTO `xinhu_task` VALUES ('1', '流程从新匹配', '系统', 'flow,pipei', 'd,d', '00:10:00,12:10:00', '每天0,12点', '1', '0', null, null, '0', null, null, null, null, null);
INSERT INTO `xinhu_task` VALUES ('2', '数据备份', '系统', 'sys,beifen', 'd', '00:20:00', '每天凌晨00:20', '1', '0', null, null, '0', null, null, null, null, null);
INSERT INTO `xinhu_task` VALUES ('3', '系统升级提醒', '系统', 'sys,upgtx', 'd', '00:25:00', '每天凌晨00:25', '1', '0', null, null, '0', null, null, null, '1,8', '管理员,信呼客服');
INSERT INTO `xinhu_task` VALUES ('4', '5分钟运行1次', '系统', 'minute5,run', 'i5', '00', '每5分钟运行一次', '1', '0', null, null, '0', null, null, '这个是必须的，不可删除停用', null, null);
INSERT INTO `xinhu_task` VALUES ('5', '考勤信息提醒', '考勤', 'kaoqin,todo', 'd', '09:25:00', '每天9点', '1', '0', null, null, '21', null, null, '在模块【考勤信息】下设置提醒到哪些平台。', null, null);
INSERT INTO `xinhu_task` VALUES ('6', '用户数据更新', '用户', 'sys,dataup', 'd', '00:10:00', '每天', '1', '0', null, null, '1', null, null, null, null, null);
INSERT INTO `xinhu_task` VALUES ('7', '考勤分析', '考勤', 'kaoqin,anay', 'd', '02:15:00', '每天2点', '1', '0', null, null, '22', null, null, '分析全体人员昨天的考勤', null, null);
INSERT INTO `xinhu_task` VALUES ('8', '员工合同提醒', '人事', 'hr,httodo', 'd', '00:05:00', null, '1', '0', null, null, '30', null, null, '员工合同到期提醒，hr调用转正离职运行', '4', '大乔');
INSERT INTO `xinhu_task` VALUES ('9', '每天运行一次', '系统', 'day,run', 'd', '07:50:00', '每天07:50', '1', '0', null, null, '0', null, null, '任务到期,固定会议,待办提醒', null, null);
INSERT INTO `xinhu_task` VALUES ('10', '每月1号分析上月考勤', '考勤', 'kaoqin,lmanay', 'm', '01 05:00:00', '每月1号5点', '1', '0', null, null, '23', null, null, '分析上月全体人员考勤', null, null);
INSERT INTO `xinhu_task` VALUES ('11', '日报统计分析', '考勤', 'kaoqin,dailyfx', 'd', '02:20:00', null, '1', '0', null, null, '24', null, null, null, null, null);
INSERT INTO `xinhu_task` VALUES ('12', '车辆信息提醒', '车辆', 'carm,run', 'd', '11:35:00', null, '1', '0', null, null, '40', null, null, null, '4', '大乔');
INSERT INTO `xinhu_task` VALUES ('13', '客户提醒', 'CRM', 'crm,run', 'd', '08:15:00', null, '1', '0', null, null, '50', null, null, null, null, null);
INSERT INTO `xinhu_task` VALUES ('14', '考勤每天运行', '考勤', 'kaoqin,day', 'd', '02:30:00', null, '1', '0', null, null, '20', null, null, '年假自动添加等', null, null);
INSERT INTO `xinhu_task` VALUES ('15', '读取打卡记录', '考勤', 'kaoqin,getdk', 'h', '15:00', '每小时', '0', '0', null, null, '25', null, null, '每小时从企业微信和钉钉上读取打卡记录', null, null);
INSERT INTO `xinhu_task` VALUES ('16', '定时清理', '系统', 'sys,clear', 'w7', '23:55:00', '每周日23点', '1', '0', null, null, '0', null, null, '每周清理日志考勤分析等', null, null);

-- ----------------------------
-- Table structure for `xinhu_todo`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_todo`;
CREATE TABLE `xinhu_todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL COMMENT '类型',
  `mess` varchar(500) DEFAULT NULL COMMENT '信息内容',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态@0|未读,1|已读',
  `optdt` datetime DEFAULT NULL COMMENT '时间',
  `table` varchar(50) DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  `readdt` datetime DEFAULT NULL COMMENT '已读时间',
  `tododt` datetime DEFAULT NULL COMMENT '提醒时间',
  `modenum` varchar(20) DEFAULT NULL COMMENT '对应模块编号',
  `url` varchar(500) DEFAULT NULL COMMENT '相关url',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='提醒消息表';

-- ----------------------------
-- Records of xinhu_todo
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_tovoid`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_tovoid`;
CREATE TABLE `xinhu_tovoid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `type` tinyint(1) DEFAULT '0' COMMENT '0|作废,1|删除',
  `modename` varchar(20) DEFAULT NULL COMMENT '模块',
  `modeid` smallint(6) DEFAULT '0' COMMENT '对应模块id',
  `tonum` varchar(200) DEFAULT NULL COMMENT '作废单号',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='单据申请作废表';

-- ----------------------------
-- Records of xinhu_tovoid
-- ----------------------------
INSERT INTO `xinhu_tovoid` VALUES ('3', '1', '2016-12-19 13:49:22', '1', '管理员', '2016-12-19', '在删', '5', '1', '1', '单据作废申请', '52', 'ZF-20161219-0002', '0');
INSERT INTO `xinhu_tovoid` VALUES ('5', '1', '2016-12-19 14:39:20', '1', '管理员', '2016-12-19', 'hhahhhhaaa', '1', '1', '1', '单据作废申请', '52', 'ZF-20161219-0004', '0');

-- ----------------------------
-- Table structure for `xinhu_userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_userinfo`;
CREATE TABLE `xinhu_userinfo` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `num` varchar(20) DEFAULT NULL COMMENT '编号',
  `deptname` varchar(30) DEFAULT NULL,
  `deptnames` varchar(100) DEFAULT NULL COMMENT '多部门',
  `deptallname` varchar(200) DEFAULT NULL,
  `ranking` varchar(20) DEFAULT NULL,
  `rankings` varchar(100) DEFAULT NULL COMMENT '多职位',
  `dkip` varchar(200) DEFAULT NULL,
  `dkmac` varchar(200) DEFAULT NULL,
  `state` tinyint(1) DEFAULT '0' COMMENT '状态来自userstate',
  `sex` varchar(10) DEFAULT NULL COMMENT '性别',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话',
  `mobile` varchar(100) DEFAULT NULL COMMENT '手机号',
  `workdate` date DEFAULT NULL COMMENT '入职时间',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `quitdt` date DEFAULT NULL COMMENT '离职日期',
  `iskq` tinyint(1) DEFAULT '1' COMMENT '是否考勤',
  `isdwdk` tinyint(1) DEFAULT '0' COMMENT '是否定位打卡',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `xueli` varchar(20) DEFAULT NULL,
  `birtype` tinyint(1) DEFAULT '0' COMMENT '0阳历1农历',
  `minzu` varchar(20) DEFAULT NULL COMMENT '民族',
  `hunyin` varchar(10) DEFAULT NULL COMMENT '婚姻',
  `jiguan` varchar(20) DEFAULT NULL COMMENT '籍贯',
  `nowdizhi` varchar(50) DEFAULT NULL COMMENT '现住址',
  `housedizhi` varchar(50) DEFAULT NULL COMMENT '家庭地址',
  `syenddt` date DEFAULT NULL COMMENT '试用期到',
  `positivedt` date DEFAULT NULL COMMENT '转正日期',
  `bankname` varchar(50) DEFAULT NULL COMMENT '开户行',
  `banknum` varchar(30) DEFAULT NULL COMMENT '工资卡帐号',
  `zhaopian` varchar(100) DEFAULT NULL COMMENT '照片',
  `idnum` varchar(30) DEFAULT NULL COMMENT '身份证号',
  `spareman` varchar(30) DEFAULT NULL COMMENT '备用联系人',
  `sparetel` varchar(50) DEFAULT NULL COMMENT '备用联系人电话',
  `isdaily` tinyint(4) DEFAULT '1' COMMENT '是否需要写日报',
  `companyid` int(11) DEFAULT '0' COMMENT '所在公司单位Id',
  `finger` varchar(20) DEFAULT NULL COMMENT '关联考勤机人员编号',
  `dwid` varchar(50) DEFAULT NULL COMMENT '加入单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户档案表';

-- ----------------------------
-- Records of xinhu_userinfo
-- ----------------------------
INSERT INTO `xinhu_userinfo` VALUES ('1', '管理员', 'A001', '开发部', null, '信呼开发团队/开发部', '项目主管', null, '*', null, '1', '男', '0592-1234569', '15800000023', '2016-06-01', 'admin@rockoa.com', null, '1', '0', '1987-08-01', '博士后', '0', '汉', '未婚', '福建', '厦门', '厦门', '2016-10-01', '2016-10-02', '厦门银行', '6225****', 'images/face.jpg', '350202199901013526', '磐石', '10086', '1', '1', null, null);
INSERT INTO `xinhu_userinfo` VALUES ('2', '貂蝉', null, '财务部', null, '信呼开发团队/财务部', '财务总监', null, null, null, '1', '女', '0592-123456', '15800000007', '2016-08-09', null, null, '1', '0', null, null, '0', null, null, null, null, null, null, null, null, null, 'images/diaochan.jpg', null, null, null, '1', '2', null, null);
INSERT INTO `xinhu_userinfo` VALUES ('3', '小乔', null, '财务部', null, '信呼开发团队/财务部', '出纳', null, null, null, '1', '女', null, '15800000001', '2017-05-02', null, null, '1', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '2', null, null);
INSERT INTO `xinhu_userinfo` VALUES ('4', '大乔', null, '行政人事部', '财务部', '信呼开发团队/行政人事部', '人事主管', '会计', null, null, '1', '女', null, '15800000002', '2017-07-01', null, null, '1', '0', '1990-06-11', '本科', '0', '汉族', '未婚', '厦门', '厦门', '厦门', '2018-06-01', '2018-06-01', null, null, null, null, null, null, '1', '2', null, null);
INSERT INTO `xinhu_userinfo` VALUES ('5', '磐石', null, '管理层', null, '信呼开发团队/管理层', '董事长', null, null, null, '1', '男', null, '15800000003', '2015-11-01', null, null, '0', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, '0', '1', null, '1,2,3,4');
INSERT INTO `xinhu_userinfo` VALUES ('6', '张飞', null, '开发部', null, '信呼开发团队/开发部', '程序员', null, null, null, '1', '男', null, '15800000004', '2017-07-01', null, null, '1', '0', null, null, '0', null, null, null, null, null, null, null, '招商银行', '6228123456', null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_userinfo` VALUES ('7', '赵子龙', null, '开发部', null, '信呼开发团队/开发部', '高级程序员', null, null, null, '1', '男', null, '15800000005', '2016-07-01', null, null, '1', '0', null, null, '0', null, null, null, null, null, '2016-10-01', '2018-06-16', null, null, null, null, null, null, '1', '1', null, null);
INSERT INTO `xinhu_userinfo` VALUES ('8', '信呼客服', null, '开发部', null, '信呼开发团队/开发部', '客服', null, '192.168.1.63', '64:00:6a:49:20:4b', '0', '女', null, '15800000006', '2018-12-12', 'wx001@rockoa.com', null, '1', '0', null, null, '0', null, null, null, null, null, null, null, null, null, null, null, null, null, '1', '1', null, '2');

-- ----------------------------
-- Table structure for `xinhu_userinfos`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_userinfos`;
CREATE TABLE `xinhu_userinfos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` smallint(6) DEFAULT '0' COMMENT '对应主表userinfo.id',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `startdt` date DEFAULT NULL COMMENT '开始日期',
  `enddt` date DEFAULT NULL COMMENT '截止日期',
  `rank` varchar(50) DEFAULT NULL COMMENT '职位',
  `unitname` varchar(50) DEFAULT NULL COMMENT '单位名称',
  `sslx` tinyint(1) DEFAULT '0' COMMENT '0工作经历,1教育经历',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='人员档案子表';

-- ----------------------------
-- Records of xinhu_userinfos
-- ----------------------------
INSERT INTO `xinhu_userinfos` VALUES ('1', '1', '0', '2017-01-05', '2017-01-12', '程序员', '微信', '0', '1');
INSERT INTO `xinhu_userinfos` VALUES ('2', '1', '0', '2017-01-05', '2017-01-27', '研究生', '哈弗', '1', '1');
INSERT INTO `xinhu_userinfos` VALUES ('3', '1', '1', '2017-01-13', '2017-01-20', '架构师', '微软', '0', '1');
INSERT INTO `xinhu_userinfos` VALUES ('4', '1', '1', '2017-01-25', '2017-01-31', '博士', '剑桥', '1', '1');
INSERT INTO `xinhu_userinfos` VALUES ('5', '1', '2', '2017-01-11', '2017-01-31', '博士后', '清华', '1', '1');
INSERT INTO `xinhu_userinfos` VALUES ('6', '1', '2', '2017-05-01', '2017-05-31', '架构师', '微软', '0', '1');
INSERT INTO `xinhu_userinfos` VALUES ('7', '4', '0', '2019-06-01', '2019-06-05', '无', '无', '0', '2');
INSERT INTO `xinhu_userinfos` VALUES ('8', '4', '0', '2019-06-01', '2019-06-26', '我', '我', '1', '2');

-- ----------------------------
-- Table structure for `xinhu_userract`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_userract`;
CREATE TABLE `xinhu_userract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `startdt` date DEFAULT NULL COMMENT '开始日期',
  `enddt` date DEFAULT NULL COMMENT '截止日期',
  `uid` smallint(6) DEFAULT '0',
  `sort` smallint(6) DEFAULT '0',
  `optdt` datetime DEFAULT NULL,
  `explain` varchar(500) DEFAULT NULL,
  `httype` varchar(30) DEFAULT NULL COMMENT '合同类型',
  `state` tinyint(1) DEFAULT '0' COMMENT '0|待执行,1|生效中,2|已终止,3|已过期',
  `tqenddt` date DEFAULT NULL COMMENT '提前终止',
  `company` varchar(50) DEFAULT NULL COMMENT '签署公司',
  `uname` varchar(20) DEFAULT NULL COMMENT '签署人',
  `companyid` smallint(6) DEFAULT '0' COMMENT '签署公司Id',
  `htfid` int(11) DEFAULT '0' COMMENT '合同文件',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户合同表';

-- ----------------------------
-- Records of xinhu_userract
-- ----------------------------
INSERT INTO `xinhu_userract` VALUES ('1', '第一次签劳动合同', '2015-10-01', '2018-07-31', '1', '0', '2018-07-11 12:11:51', null, '劳动合同', '2', '2018-07-10', '信呼开发团队', '管理员', '1', '0');
INSERT INTO `xinhu_userract` VALUES ('2', '合同', '2015-10-01', '2016-09-30', '6', '0', '2017-07-08 15:40:20', '嗯嗯嗯', '劳动合同', '3', null, '信呼开发团队', '张飞', '1', '0');
INSERT INTO `xinhu_userract` VALUES ('3', '合同', '2021-01-20', '2021-11-10', '3', '0', '2021-10-12 18:35:41', '合同文件', '劳动合同', '1', null, '信呼开发团队', '小乔', '1', null);

-- ----------------------------
-- Table structure for `xinhu_userzheng`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_userzheng`;
CREATE TABLE `xinhu_userzheng` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `uid` int(11) DEFAULT '0',
  `uname` varchar(50) DEFAULT NULL COMMENT '所属人',
  `mingc` varchar(50) DEFAULT NULL COMMENT '证件名称',
  `numc` varchar(50) DEFAULT NULL COMMENT '证书编号',
  `sdt` date DEFAULT NULL COMMENT '取得时间',
  `edt` date DEFAULT NULL COMMENT '到期时间',
  `fengmian` varchar(100) DEFAULT NULL COMMENT '相关图标',
  `optid` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL,
  `optname` varchar(50) DEFAULT NULL,
  `explain` varchar(2000) DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xinhu_userzheng
-- ----------------------------
INSERT INTO `xinhu_userzheng` VALUES ('1', '1', '1', '管理员', '毕业证书', '1233554', '2020-10-01', '2020-10-13', 'images/logo.png', '1', '2020-10-13 14:45:07', '管理员', null);
INSERT INTO `xinhu_userzheng` VALUES ('2', '1', '1', '管理员', '高级工程师', '001', '2020-10-02', null, null, '1', '2020-10-13 15:37:08', '管理员', null);

-- ----------------------------
-- Table structure for `xinhu_vcard`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_vcard`;
CREATE TABLE `xinhu_vcard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(100) DEFAULT NULL COMMENT '手机号',
  `optdt` datetime DEFAULT NULL,
  `uid` smallint(6) DEFAULT '0',
  `tel` varchar(50) DEFAULT NULL COMMENT '电话',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `gname` varchar(20) DEFAULT NULL COMMENT '所在组名',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `address` varchar(100) DEFAULT NULL COMMENT '地址',
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `unitname` varchar(50) DEFAULT NULL COMMENT '单位名称',
  `sex` varchar(5) DEFAULT NULL COMMENT '性别',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='个人通讯录';

-- ----------------------------
-- Records of xinhu_vcard
-- ----------------------------
INSERT INTO `xinhu_vcard` VALUES ('2', '曹操', null, '2017-08-22 16:17:49', '1', null, 'caocao@rockoa.com', '敌人', '管理员', null, '0', '魏国', '男', '0');
INSERT INTO `xinhu_vcard` VALUES ('3', '诸葛亮', null, '2016-12-20 11:27:08', '1', null, 'zgl@rockoa.com', '同事', '管理员', null, '0', '蜀国', null, '0');
INSERT INTO `xinhu_vcard` VALUES ('4', '管理员', null, '2016-12-20 11:31:21', '8', null, 'admin@rockoa.com', '同事', '信呼客服', null, '0', '信呼开发团队', null, '0');
INSERT INTO `xinhu_vcard` VALUES ('5', '管理员', null, '2016-12-21 14:36:17', '1', null, 'admin@rockoa.com', null, '管理员', null, '0', null, null, '0');
INSERT INTO `xinhu_vcard` VALUES ('7', '关羽', '15812345678', '2017-08-22 16:19:20', '1', '0592-123456', 'guanyu@rockoa.com', '同事', '管理员', '荆州市', '0', '蜀国荆州守将', '男', '0');
INSERT INTO `xinhu_vcard` VALUES ('8', '我自己', null, '2020-09-21 18:22:12', '1', null, '290802026@qq.com', '同事', '管理员', null, '0', null, '男', '1');
INSERT INTO `xinhu_vcard` VALUES ('10', '许正骢', null, '2020-09-24 13:32:09', '1', null, 'xuzhengcong@bz.cn', '未分组', null, null, '0', null, null, '0');

-- ----------------------------
-- Table structure for `xinhu_wenjuan`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wenjuan`;
CREATE TABLE `xinhu_wenjuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `recename` varchar(1000) DEFAULT NULL COMMENT '针对对象',
  `receid` varchar(1000) DEFAULT NULL COMMENT '针对对象的ID',
  `startdt` date DEFAULT NULL COMMENT '开始日期',
  `enddt` date DEFAULT NULL COMMENT '截止日期',
  `uid` int(11) DEFAULT '0',
  `optid` int(11) DEFAULT '0' COMMENT '创建人id',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optname` varchar(50) DEFAULT NULL COMMENT '操作人',
  `explain` varchar(2000) DEFAULT NULL COMMENT '说明',
  `dauserids` varchar(4000) DEFAULT NULL COMMENT '已提交人id',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='问卷表';

-- ----------------------------
-- Records of xinhu_wenjuan
-- ----------------------------
INSERT INTO `xinhu_wenjuan` VALUES ('1', '1', '信呼OA系统使用调查', '信呼开发团队', 'd1', '2020-05-28', '2020-05-31', '1', '1', '2020-05-27 19:29:03', '管理员', null, '1,7,6', '1');

-- ----------------------------
-- Table structure for `xinhu_wenjuat`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wenjuat`;
CREATE TABLE `xinhu_wenjuat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '对应主表wenjuan.id',
  `sort` int(11) DEFAULT '0' COMMENT '排序号',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `itemname` varchar(100) DEFAULT NULL,
  `itemcont` varchar(200) DEFAULT NULL COMMENT '项说明',
  `itemtype` smallint(6) DEFAULT '0' COMMENT '项类型',
  `itema` varchar(100) DEFAULT NULL COMMENT '选项A',
  `itemb` varchar(100) DEFAULT NULL COMMENT '选项B',
  `itemc` varchar(100) DEFAULT NULL COMMENT '选项C',
  `itemd` varchar(100) DEFAULT NULL COMMENT '选项D',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='问卷表子表';

-- ----------------------------
-- Records of xinhu_wenjuat
-- ----------------------------
INSERT INTO `xinhu_wenjuat` VALUES ('1', '1', '0', '1', '觉得系统好用吗', '选几个多好啊', '1', '好用', '非常好用', null, null);
INSERT INTO `xinhu_wenjuat` VALUES ('2', '1', '1', '1', '对系统的建议', '都可以选', '2', null, null, null, null);
INSERT INTO `xinhu_wenjuat` VALUES ('3', '1', '2', '1', '系统官网地址', null, '0', 'www.rockoa.com', 'rockoa.com', '不知道', null);

-- ----------------------------
-- Table structure for `xinhu_wenjuau`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wenjuau`;
CREATE TABLE `xinhu_wenjuau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0' COMMENT '对应主表wenjuan.id',
  `sid` int(11) DEFAULT '0' COMMENT '子表id',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `conts` varchar(500) DEFAULT NULL,
  `optid` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL,
  `rand` int(11) DEFAULT '0' COMMENT '批次',
  `optname` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='问卷表子表';

-- ----------------------------
-- Records of xinhu_wenjuau
-- ----------------------------
INSERT INTO `xinhu_wenjuau` VALUES ('1', '1', '1', '1', 'a', '1', '2020-05-27 22:49:58', '584963', '管理员');
INSERT INTO `xinhu_wenjuau` VALUES ('2', '1', '2', '1', '不错的哦', '1', '2020-05-27 22:49:58', '584963', '管理员');
INSERT INTO `xinhu_wenjuau` VALUES ('3', '1', '3', '1', 'b', '1', '2020-05-27 22:49:58', '584963', '管理员');
INSERT INTO `xinhu_wenjuau` VALUES ('4', '1', '1', '1', 'a,b', '7', '2020-05-27 22:50:36', '674667', '赵子龙');
INSERT INTO `xinhu_wenjuau` VALUES ('5', '1', '2', '1', '很好的哦，继续保持', '7', '2020-05-27 22:50:36', '674667', '赵子龙');
INSERT INTO `xinhu_wenjuau` VALUES ('6', '1', '3', '1', 'c', '7', '2020-05-27 22:50:36', '674667', '赵子龙');
INSERT INTO `xinhu_wenjuau` VALUES ('7', '1', '1', '1', 'a,b', '6', '2020-05-29 11:21:00', '803729', '张飞');
INSERT INTO `xinhu_wenjuau` VALUES ('8', '1', '2', '1', '很好的哦，继续保持系统要求。', '6', '2020-05-29 11:21:00', '803729', '张飞');
INSERT INTO `xinhu_wenjuau` VALUES ('9', '1', '3', '1', 'a', '6', '2020-05-29 11:21:00', '803729', '张飞');

-- ----------------------------
-- Table structure for `xinhu_worc`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_worc`;
CREATE TABLE `xinhu_worc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '文档分区名称',
  `recename` varchar(500) DEFAULT NULL COMMENT '可查看人员',
  `receid` varchar(500) DEFAULT NULL,
  `sort` smallint(6) DEFAULT '0' COMMENT '排序号',
  `guanname` varchar(500) DEFAULT NULL COMMENT '管理人员',
  `guanid` varchar(500) DEFAULT NULL,
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `uid` int(11) DEFAULT '0' COMMENT '所属用户',
  `optname` varchar(30) DEFAULT NULL COMMENT '创建人',
  `uptype` varchar(100) DEFAULT NULL COMMENT '限制上传类型',
  `upuserid` varchar(500) DEFAULT '' COMMENT '可上传人员ID',
  `upuser` varchar(500) DEFAULT '' COMMENT '可上传人员',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='文档分区表';

-- ----------------------------
-- Records of xinhu_worc
-- ----------------------------
INSERT INTO `xinhu_worc` VALUES ('1', '公共文档区', '信呼开发团队', 'd1', '0', null, null, '2020-06-10 14:34:36', '1', '管理员', null, 'd1', '信呼开发团队', '1');
INSERT INTO `xinhu_worc` VALUES ('2', '技术文档', '开发部', 'd2', '1', '开发部', 'd2', '2020-06-10 14:35:18', '1', '管理员', null, 'd2', '开发部', '1');
INSERT INTO `xinhu_worc` VALUES ('4', '行政规章制度', '信呼开发团队', 'd1', '0', '行政人事部', 'd4', '2020-06-10 15:04:43', '3', '管理员', null, 'd4', '行政人事部', '1');
INSERT INTO `xinhu_worc` VALUES ('5', '大乔分区1', '大乔', 'u4', '0', null, null, '2019-06-05 17:23:42', '4', '大乔', null, '', '', '2');

-- ----------------------------
-- Table structure for `xinhu_word`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_word`;
CREATE TABLE `xinhu_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `optid` int(11) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL,
  `fileid` int(11) DEFAULT '0',
  `shateid` varchar(200) DEFAULT NULL,
  `shate` varchar(200) DEFAULT NULL COMMENT '分享给我的',
  `optdt` datetime DEFAULT NULL,
  `typeid` int(11) DEFAULT '0' COMMENT '对应类型',
  `cid` int(11) DEFAULT '0' COMMENT '文档分区ID',
  `type` tinyint(1) DEFAULT '0' COMMENT '0文件,1文件夹',
  `sort` int(11) DEFAULT '0' COMMENT '排序号',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `type` (`type`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='文档表';

-- ----------------------------
-- Records of xinhu_word
-- ----------------------------
INSERT INTO `xinhu_word` VALUES ('1', null, '1', '管理员', '1', 'd1', '信呼开发团队', '2016-11-23 22:26:22', '0', '1', '0', '0', '0');
INSERT INTO `xinhu_word` VALUES ('2', null, '1', '管理员', '2', 'd1', '信呼开发团队', '2016-11-23 22:26:27', '0', '1', '0', '0', '0');
INSERT INTO `xinhu_word` VALUES ('3', '员工资料', '1', '管理员', '0', 'd1', '信呼开发团队', null, '0', '1', '1', '0', '0');

-- ----------------------------
-- Table structure for `xinhu_wordeil`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wordeil`;
CREATE TABLE `xinhu_wordeil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` smallint(6) DEFAULT '0',
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `explain` varchar(500) DEFAULT NULL COMMENT '说明',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `filecontid` varchar(100) DEFAULT NULL COMMENT '相关文件',
  `qianname` varchar(50) DEFAULT NULL COMMENT '签收人',
  `qiannameid` varchar(50) DEFAULT NULL COMMENT '签收人的ID',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文件传送';

-- ----------------------------
-- Records of xinhu_wordeil
-- ----------------------------
INSERT INTO `xinhu_wordeil` VALUES ('1', '1', '2019-03-09 13:13:20', '1', '管理员', '2019-03-09', null, '1', '1', '进出口商品网上交易会申请表', '52', '小乔', '3', '0');

-- ----------------------------
-- Table structure for `xinhu_wordxie`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wordxie`;
CREATE TABLE `xinhu_wordxie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '文档名称',
  `fenlei` varchar(50) DEFAULT NULL COMMENT '分类',
  `wtype` varchar(20) DEFAULT NULL COMMENT '文档类型',
  `optname` varchar(20) DEFAULT NULL,
  `optid` int(11) DEFAULT '0',
  `optdt` datetime DEFAULT NULL,
  `xiename` varchar(500) DEFAULT NULL COMMENT '协作人',
  `xienameid` varchar(500) DEFAULT NULL,
  `recename` varchar(500) DEFAULT NULL COMMENT '可查看人',
  `receid` varchar(500) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `fileid` int(11) DEFAULT '0',
  `explian` varchar(2000) DEFAULT NULL COMMENT '说明',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `isgk` tinyint(4) DEFAULT '0' COMMENT '是否可让他人选择',
  `tplvar` varchar(500) DEFAULT NULL COMMENT '模版中变量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='文档协作';

-- ----------------------------
-- Records of xinhu_wordxie
-- ----------------------------
INSERT INTO `xinhu_wordxie` VALUES ('1', '信呼开发团队介绍', null, 'pptx', '管理员', '1', '2019-03-09 20:35:22', '行政人事部,开发部', 'd4,d2', '信呼开发团队', 'd1', '1', '3', null, '0', '0', null);
INSERT INTO `xinhu_wordxie` VALUES ('2', '信呼使用文档', null, 'docx', '管理员', '1', '2019-08-18 19:16:51', '开发部', 'd2', '信呼开发团队', 'd1', '1', '4', null, '1', '0', null);
INSERT INTO `xinhu_wordxie` VALUES ('4', '员工劳动合同', '员工合同', 'docx', '管理员', '1', '2020-10-29 10:08:17', null, null, '信呼开发团队', 'd1', '1', '5', '可供员工合同使用', '1', '1', 'company,name,idnum,mobile');
INSERT INTO `xinhu_wordxie` VALUES ('5', '标准红头文件模版', '红头文件', 'docx', '管理员', '1', '2020-10-31 22:10:30', null, null, '信呼开发团队', 'd1', '1', '6', '发文单套红使用', '1', '1', 'officiatitle,num,title,zuncheng,content,applydt,keyword,unitname,chaoname,unitsame');
INSERT INTO `xinhu_wordxie` VALUES ('6', '客户合同模版', '客户合同', 'docx', '管理员', '1', '2020-12-24 22:11:35', null, null, '信呼开发团队', 'd1', '1', '7', null, '1', '1', 'custname,startdt,enddt,money');

-- ----------------------------
-- Table structure for `xinhu_work`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_work`;
CREATE TABLE `xinhu_work` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `num` varchar(30) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `type` varchar(20) DEFAULT NULL COMMENT '任务类型',
  `grade` varchar(10) DEFAULT NULL COMMENT '任务等级',
  `distid` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL COMMENT '分配给',
  `explain` varchar(4000) DEFAULT NULL COMMENT '说明',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `optid` int(11) DEFAULT NULL,
  `optname` varchar(20) DEFAULT NULL COMMENT '操作人',
  `startdt` datetime DEFAULT NULL COMMENT '开始时间',
  `enddt` datetime DEFAULT NULL COMMENT '结束时间',
  `state` tinyint(1) DEFAULT '0' COMMENT '状态',
  `txdt` datetime DEFAULT NULL,
  `fen` smallint(6) DEFAULT '0' COMMENT '分值',
  `status` tinyint(1) DEFAULT '1',
  `projectid` smallint(6) DEFAULT '0' COMMENT '对应项目Id',
  `ddid` varchar(50) DEFAULT NULL,
  `ddname` varchar(50) DEFAULT NULL COMMENT '督导人员，不填默认上级',
  `score` smallint(6) DEFAULT '0' COMMENT '任务分数',
  `mark` smallint(6) DEFAULT '0' COMMENT '得分',
  `uid` smallint(6) DEFAULT '0',
  `applydt` date DEFAULT NULL COMMENT '申请日期',
  `isturn` tinyint(1) DEFAULT '1' COMMENT '是否提交',
  `comid` smallint(6) DEFAULT '0' COMMENT '对应单位id',
  `zhuid` int(11) DEFAULT '0' COMMENT '关联id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='任务';

-- ----------------------------
-- Records of xinhu_work
-- ----------------------------
INSERT INTO `xinhu_work` VALUES ('3', null, '系统开发使用', 'bug', '低', '5', '磐石', null, '2020-12-18 14:07:33', '1', '管理员', '2020-12-18 13:53:21', null, '0', null, '0', '3', '1', null, null, '0', '0', '1', '2020-12-18', '1', '1', '0');
INSERT INTO `xinhu_work` VALUES ('11', null, '测试任务', 'bug', '低', '8', '信呼客服', null, '2021-04-13 16:13:43', '1', '管理员', '2021-04-13 16:12:47', null, '0', null, '0', '1', '1', null, null, '0', '0', '1', '2021-04-13', '1', '1', '0');

-- ----------------------------
-- Table structure for `xinhu_wotpl`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wotpl`;
CREATE TABLE `xinhu_wotpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` varchar(100) DEFAULT NULL COMMENT '模版消息id',
  `title` varchar(50) DEFAULT NULL COMMENT '模板标题',
  `primary_industry` varchar(50) DEFAULT NULL COMMENT '模板所属行业的一级行业',
  `deputy_industry` varchar(50) DEFAULT NULL COMMENT '模板所属行业的二级行业',
  `content` varchar(1000) DEFAULT NULL COMMENT '模板内容',
  `example` varchar(500) DEFAULT NULL COMMENT '模板示例',
  `modename` varchar(50) DEFAULT NULL COMMENT '使用名称',
  `modeparams` varchar(500) DEFAULT NULL COMMENT '参数设置',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信公众号消息模版';

-- ----------------------------
-- Records of xinhu_wotpl
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_wouser`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wouser`;
CREATE TABLE `xinhu_wouser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '绑定用户Id',
  `openid` varchar(100) DEFAULT '微信openid',
  `nickname` varchar(30) DEFAULT NULL COMMENT '微信昵称',
  `sex` tinyint(1) DEFAULT '1' COMMENT '性别1男2女0未知',
  `province` varchar(30) DEFAULT NULL COMMENT '省份',
  `city` varchar(30) DEFAULT NULL COMMENT '城市',
  `country` varchar(30) DEFAULT NULL COMMENT '国家',
  `headimgurl` varchar(300) DEFAULT NULL COMMENT '微信头像',
  `adddt` datetime DEFAULT NULL COMMENT '添加时间',
  `optdt` datetime DEFAULT NULL COMMENT '操作时间',
  `ip` varchar(100) DEFAULT NULL COMMENT 'IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信用户信息表';

-- ----------------------------
-- Records of xinhu_wouser
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_wxtx_renlian`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wxtx_renlian`;
CREATE TABLE `xinhu_wxtx_renlian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personname` varchar(50) DEFAULT NULL,
  `personid` varchar(100) NOT NULL DEFAULT '' COMMENT '人员id',
  `gender` tinyint(1) DEFAULT NULL COMMENT '性别',
  `faceids` varchar(500) DEFAULT NULL COMMENT '包含的人脸照片列表',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否启用',
  `uid` int(11) DEFAULT '0' COMMENT '关联用户ID',
  `imgurl` varchar(500) DEFAULT NULL COMMENT '人脸图片',
  PRIMARY KEY (`id`,`personid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of xinhu_wxtx_renlian
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_wxxcyus`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_wxxcyus`;
CREATE TABLE `xinhu_wxxcyus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickName` varchar(50) DEFAULT NULL COMMENT '昵称',
  `openid` varchar(50) DEFAULT NULL,
  `xcytype` varchar(20) DEFAULT NULL COMMENT '小程序类型',
  `adddt` datetime DEFAULT NULL,
  `optdt` datetime DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `mobile1` varchar(50) DEFAULT NULL,
  `avatarUrl` varchar(200) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dingyue` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信小程序用户';

-- ----------------------------
-- Records of xinhu_wxxcyus
-- ----------------------------

-- ----------------------------
-- Table structure for `xinhu_zreim_user`
-- ----------------------------
DROP TABLE IF EXISTS `xinhu_zreim_user`;
CREATE TABLE `xinhu_zreim_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(20) DEFAULT NULL,
  `position` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态@1|已关注,2|已冻结,0|未关注',
  `deptid` int(11) DEFAULT '0' COMMENT '部门id',
  `face` varchar(200) DEFAULT NULL COMMENT '头像',
  `tel` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='REIM即时通讯平台用户';

-- ----------------------------
-- Records of xinhu_zreim_user
-- ----------------------------

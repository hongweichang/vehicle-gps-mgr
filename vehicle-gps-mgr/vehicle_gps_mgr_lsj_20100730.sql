-- phpMyAdmin SQL Dump
-- version 2.6.1-pl3
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2010 年 07 月 30 日 01:53
-- 服务器版本: 5.0.18
-- PHP 版本: 5.2.5
-- 
-- 数据库: `vehicle_gps_mgr`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `alert_info`
-- 

CREATE TABLE `alert_info` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `alert_time` datetime default NULL COMMENT '告警时间',
  `alert_type` tinyint(1) NOT NULL COMMENT '告警类型',
  `vehicle_id` int(11) NOT NULL COMMENT '车辆id',
  `dispose_id` int(11) default NULL COMMENT '处理人',
  `dispose_opinion` varchar(500) default NULL COMMENT '处理意见',
  `description` varchar(100) default NULL COMMENT '描述',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `alert_info`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `area`
-- 

CREATE TABLE `area` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `top_parentid` int(11) NOT NULL COMMENT '父级',
  `name` varchar(6) NOT NULL COMMENT '地名',
  `display_order` char(3) default NULL COMMENT '显示排列顺序',
  `description` varchar(6) default NULL COMMENT '描述',
  `avaliable` tinyint(1) NOT NULL COMMENT '是否可用',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `area`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `area_info`
-- 

CREATE TABLE `area_info` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `info_id` int(11) NOT NULL COMMENT '信息id',
  `type` tinyint(1) NOT NULL COMMENT '类型',
  `log` float default NULL COMMENT '经度',
  `lat` float default NULL COMMENT '纬度',
  `radius` float default NULL COMMENT '半径',
  `next_id` int(11) default NULL COMMENT '下一个数据点的id',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `area_info`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `common_setting`
-- 

CREATE TABLE `common_setting` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `company_id` int(11) NOT NULL COMMENT '所属公司id',
  `page_refresh_time` int(11) NOT NULL COMMENT '页面刷新时间',
  `default_color` char(10) default NULL COMMENT '默认车辆/轨迹颜色',
  `speed_astrict` float default NULL COMMENT '超速限制(例120公里/h)',
  `fatigue_remind_time` float default NULL COMMENT '疲劳驾驶提醒时间',
  `backup1` varchar(100) default NULL COMMENT '备用字段1',
  `backup2` varchar(100) default NULL COMMENT '备用字段2',
  `backup3` varchar(100) default NULL COMMENT '备用字段3',
  `backup4` varchar(100) default NULL COMMENT '备用字段4',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `common_setting`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `company`
-- 

CREATE TABLE `company` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `login_id` varchar(20) default NULL COMMENT '登录ID',
  `name` varchar(20) NOT NULL COMMENT '公司名',
  `register_num` char(20) NOT NULL COMMENT '公司注册号',
  `area1` varchar(6) default NULL COMMENT '地区1(省)',
  `area2` varchar(6) default NULL COMMENT '地区2(市)',
  `area3` varchar(6) default NULL COMMENT '地区3(区、县)',
  `description` varchar(100) default NULL COMMENT '描述',
  `contact` varchar(20) NOT NULL COMMENT '联系人名称',
  `address` varchar(100) default NULL COMMENT '地址',
  `zipcode` char(6) default NULL COMMENT '邮编',
  `tel` char(13) NOT NULL COMMENT '电话',
  `fax` char(13) default NULL COMMENT '传真',
  `mobile` char(11) default NULL COMMENT '移动电话',
  `email` varchar(30) NOT NULL COMMENT '邮箱',
  `site_url` varchar(50) default NULL COMMENT '网址',
  `state` tinyint(1) NOT NULL COMMENT '状态',
  `service_start_time` datetime default NULL COMMENT '服务开始时间',
  `service_end_time` datetime default NULL COMMENT '服务结束时间',
  `charge_standard` varchar(30) default NULL COMMENT '收费标准',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- 导出表中的数据 `company`
-- 

INSERT INTO `company` VALUES (1, '1', '公司', '1', '北京市', '北京市', '北京市', '北京市公司', '苏元元', '北京市', '10000', '15001095653', '010-11111111', '15001095653', 'suyuanyuan.87@163.com', 'www.baidu.com', 1, '2010-07-27 11:23:54', '2010-07-27 11:23:54', '100元/小时', 1, '2010-07-27 11:23:54', 1, '2010-07-27 11:23:54');
INSERT INTO `company` VALUES (2, '2', '公司', '1', '北京市', '北京市', '北京市', '北京市公司', '苏元元', '北京市', '10000', '15001095653', '010-11111111', '15001095653', 'suyuanyuan.87@163.com', 'www.baidu.com', 1, '2010-07-27 11:23:54', '2010-07-27 11:23:54', '100元/小时', 1, '2010-07-27 11:23:54', 1, '2010-07-27 11:23:54');

-- --------------------------------------------------------

-- 
-- 表的结构 `continue_drive_statistic`
-- 

CREATE TABLE `continue_drive_statistic` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `vehicle_id` int(11) NOT NULL COMMENT '车辆id',
  `driver_id` int(11) NOT NULL COMMENT '驾驶员id',
  `distance` float default NULL COMMENT '里程',
  `start_time` datetime default NULL COMMENT '开始时间',
  `end_time` datetime default NULL COMMENT '结束时间',
  `drive_time` float default NULL COMMENT '驾驶时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `continue_drive_statistic`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `driver_manage`
-- 

CREATE TABLE `driver_manage` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `name` varchar(20) NOT NULL COMMENT '姓名',
  `driving_licence_id` varchar(20) NOT NULL COMMENT '驾驶证号',
  `sex` tinyint(1) default NULL COMMENT '性别',
  `birthday` date default NULL COMMENT '出生日期',
  `company_id` int(11) default NULL COMMENT '所属公司id',
  `career_time` date default NULL COMMENT '参加工作时间',
  `job_number` char(20) default NULL COMMENT '工号',
  `driving_type` tinyint(2) NOT NULL COMMENT '驾照类型',
  `mobile` char(11) NOT NULL COMMENT '手机',
  `driving_state` tinyint(1) default NULL COMMENT '驾驶状态',
  `phone_email` varchar(30) NOT NULL COMMENT '手机邮箱',
  `address` varchar(100) default NULL COMMENT '家庭住址',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- 导出表中的数据 `driver_manage`
-- 

INSERT INTO `driver_manage` VALUES (1, 'yuanyuan', '1111111111111', 0, '2010-07-26', 1, '2010-07-26', '100', 1, '15001095653', 1, 'suyuanyuan.87@163.com', 'beijing', 1, '2010-07-26 21:23:04', 1, '2010-07-26 21:23:04');
INSERT INTO `driver_manage` VALUES (2, 'suyuanyuan', '1111111111111', 0, '2010-07-26', 1, '2010-07-26', '100', 1, '15001095653', 1, 'suyuanyuan.87@163.com', 'beijing', 1, '2010-07-26 00:00:00', 1, '2010-07-26 22:49:53');
INSERT INTO `driver_manage` VALUES (3, '苏元元', '1111111111111', 0, '2010-07-26', 1, '2010-07-26', '100', 1, '15001095653', 1, 'suyuanyuan.87@163.com', '北京市', 1, '2010-07-26 23:25:05', 1, '2010-07-26 23:25:05');

-- --------------------------------------------------------

-- 
-- 表的结构 `driver_vehicle`
-- 

CREATE TABLE `driver_vehicle` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `driver_id` int(11) NOT NULL COMMENT '驾驶员id',
  `vehicle_id` int(11) NOT NULL COMMENT '车辆id',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `driver_vehicle`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `info_issue`
-- 

CREATE TABLE `info_issue` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `is_area_info` tinyint(1) NOT NULL COMMENT '是否是区域信息',
  `issuer_id` int(11) NOT NULL COMMENT '信息发布人',
  `type` tinyint(1) NOT NULL COMMENT '信息类型',
  `issue_time` datetime default NULL COMMENT '发布时间',
  `begin_time` datetime default NULL COMMENT '生效时间',
  `end_time` datetime default NULL COMMENT '失效时间',
  `content` text COMMENT '信息内容',
  `backup1` varchar(100) default NULL COMMENT '备用字段1',
  `backup2` varchar(100) default NULL COMMENT '备用字段2',
  `backup3` varchar(100) default NULL COMMENT '备用字段3',
  `backup4` varchar(100) default NULL COMMENT '备用字段4',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `info_issue`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `info_receive_driver`
-- 

CREATE TABLE `info_receive_driver` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `info_id` int(11) NOT NULL COMMENT '信息id',
  `receiver_type` tinyint(1) NOT NULL COMMENT '接收人员类型',
  `receiver_id` int(11) NOT NULL COMMENT '接收人员id',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `info_receive_driver`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `login_log`
-- 

CREATE TABLE `login_log` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `company_id` int(11) NOT NULL COMMENT '所属公司id',
  `ip` varchar(15) default NULL COMMENT 'IP',
  `login_time` datetime default NULL COMMENT '登录时间',
  `logout_time` datetime default NULL COMMENT '退出时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `login_log`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `module`
-- 

CREATE TABLE `module` (
  `id` int(10) NOT NULL auto_increment COMMENT '模块ID',
  `abbreviation` varchar(20) NOT NULL COMMENT '简称',
  `directory` varchar(50) NOT NULL COMMENT '目录',
  `name` varchar(15) NOT NULL COMMENT '名称',
  `display_order` tinyint(4) NOT NULL default '0' COMMENT '显示顺序',
  `effective_flag` tinyint(1) default NULL,
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `abbreviation` (`abbreviation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5002 ;

-- 
-- 导出表中的数据 `module`
-- 

INSERT INTO `module` VALUES (2, 'company', 'admin/company', '公司管理', 1, 1, 1, '2010-07-27 10:46:35', 1, '2010-07-27 10:46:35');
INSERT INTO `module` VALUES (3, 'log', 'admin/log', '日志管理', 3, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module` VALUES (1001, 'user', 'admin/user', '用户', 1, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1002, 'error', 'admin/error', '系统', 3, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `module` VALUES (1003, 'vehicle', 'admin/vehicle', '车辆管理', 4, NULL, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1004, 'vehicle_group', 'admin/vehicle_group', '车辆组管理', 4, NULL, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (5001, 'driver', 'admin/driver', '人员管理', 1, 0, 1, '2010-07-26 20:39:06', 1, '2010-07-26 20:39:06');

-- --------------------------------------------------------

-- 
-- 表的结构 `module_function`
-- 

CREATE TABLE `module_function` (
  `id` int(10) NOT NULL auto_increment COMMENT '编号',
  `description` varchar(100) default NULL COMMENT '描述',
  `principal` int(11) default NULL COMMENT '负责人',
  `complete_time` datetime default NULL COMMENT '完成时间',
  `module_abbreviation` varchar(20) NOT NULL COMMENT '模块简称',
  `file_name` varchar(30) default NULL COMMENT '文件名',
  `operate` varchar(20) default NULL COMMENT '操作',
  `subjoin_parameter` varchar(100) default NULL COMMENT '附加参数',
  `is_login` tinyint(1) NOT NULL COMMENT '是否需要登录',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5008 ;

-- 
-- 导出表中的数据 `module_function`
-- 

INSERT INTO `module_function` VALUES (1001, '输出用户管理html', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'user_manage', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1002, '显示用户管理数据列表', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'list_data', NULL, 0, 1, '2010-07-26 10:41:08', 1, '2010-07-26 10:41:08');
INSERT INTO `module_function` VALUES (1003, '登录成功页面', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'login_success', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1004, '管理', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'manage_list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1005, '退出', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'logout', NULL, 0, 1, '2010-07-26 10:37:50', 1, '2010-07-26 10:37:50');
INSERT INTO `module_function` VALUES (1006, '系统设置', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'setup', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1007, '用户增删该操作', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1008, '下拉列表内容', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'select', NULL, 0, 1, '2010-07-26 10:41:08', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1010, '车辆html页面输出', 1, '2010-07-26 10:37:50', 'vehicle', 'vehicle.php', 'list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1011, '车辆数据输出', 1, '2010-07-26 10:37:50', 'vehicle', 'vehicle.php', 'list_data', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1012, '车辆的增删改操作', 1, '2010-07-26 10:37:50', 'vehicle', 'vehicle.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1013, '下拉列表内容', 1, '2010-07-26 10:37:50', 'vehicle', 'vehicle.php', 'select', NULL, 0, 1, '2010-07-26 10:41:08', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1020, '车辆组html页面输出', 1, '2010-07-26 10:37:50', 'vehicle_group', 'vehicle_group.php', 'list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1021, '车辆组数据输出', 1021, '2010-07-26 10:37:50', 'vehicle_group', 'vehicle_group.php', 'list_data', NULL, 0, 1, '2010-07-26 10:37:50', 1, '2010-07-26 10:37:50');
INSERT INTO `module_function` VALUES (1022, '车辆组的增删改操作', 1, '2010-07-26 10:37:50', 'vehicle_group', 'vehicle_group.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1023, '下拉列表内容', 1, '2010-07-26 10:37:50', 'vehicle_group', 'vehicle_group.php', 'select', NULL, 0, 1, '2010-07-26 10:41:08', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (5001, '人员管理：列表', 1, '2010-07-26 20:41:21', 'driver', 'driver.php', 'list', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5002, '人员管理：列表数据', 1, '2010-07-26 20:41:21', 'driver', 'driver.php', 'list_data', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5003, '公司管理：列表', 1, '2010-07-26 21:26:28', 'company', 'company.php', 'list', NULL, 0, 1, '2010-07-26 21:26:28', 1, '2010-07-26 21:26:28');
INSERT INTO `module_function` VALUES (5004, '公司管理：列表数据', 1, '2010-07-26 20:41:21', 'company', 'company.php', 'list_data', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5005, '日志管理：列表', 1, '2010-07-27 15:33:20', 'log', 'log.php', 'list', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module_function` VALUES (5006, '日志管理：列表数据', 1, '2010-07-27 15:33:20', 'log', 'log.php', 'list_data', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module_function` VALUES (5007, '日志管理：操作日志接口', 1, '2010-07-27 15:52:50', 'log', 'log.php', 'add_log', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');

-- --------------------------------------------------------

-- 
-- 表的结构 `operation_log_manage`
-- 

CREATE TABLE `operation_log_manage` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `company_id` int(11) NOT NULL COMMENT '所属公司id',
  `time` datetime default NULL COMMENT '操作时间',
  `description` varchar(100) default NULL COMMENT '操作描述',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- 
-- 导出表中的数据 `operation_log_manage`
-- 

INSERT INTO `operation_log_manage` VALUES (1, 1, 1, '2010-07-27 15:45:15', '测试测试');
INSERT INTO `operation_log_manage` VALUES (2, 1, 1, '2010-07-27 15:45:15', '测试测试2');
INSERT INTO `operation_log_manage` VALUES (3, 0, 0, '2010-07-27 16:07:18', '');
INSERT INTO `operation_log_manage` VALUES (4, 0, 0, '2010-07-27 16:07:35', '我是5006');
INSERT INTO `operation_log_manage` VALUES (5, 0, 0, '2010-07-27 16:10:25', '我是5006');
INSERT INTO `operation_log_manage` VALUES (6, 0, 0, '2010-07-27 16:16:20', '我是5006');
INSERT INTO `operation_log_manage` VALUES (7, 0, 0, '2010-07-27 16:25:29', '人员管理列表');
INSERT INTO `operation_log_manage` VALUES (8, 0, 0, '2010-07-27 16:25:53', '人员管理列表');
INSERT INTO `operation_log_manage` VALUES (9, 0, 0, '2010-07-27 16:27:42', '公司管理列表');
INSERT INTO `operation_log_manage` VALUES (10, 0, 0, '2010-07-27 16:28:00', '公司管理列表数据');
INSERT INTO `operation_log_manage` VALUES (11, 0, 0, '2010-07-27 22:50:04', '日志接口');
INSERT INTO `operation_log_manage` VALUES (12, 0, 0, '2010-07-27 22:50:14', '日志接口');
INSERT INTO `operation_log_manage` VALUES (13, 0, 0, '2010-07-27 23:36:27', '日志接口');
INSERT INTO `operation_log_manage` VALUES (14, 0, 0, '2010-07-27 23:42:04', '日志接口');
INSERT INTO `operation_log_manage` VALUES (15, 0, 0, '2010-07-27 23:50:13', '日志接口');
INSERT INTO `operation_log_manage` VALUES (16, 0, 0, '2010-07-27 23:53:07', '日志接口');
INSERT INTO `operation_log_manage` VALUES (17, 0, 0, '2010-07-27 23:53:10', '日志接口');
INSERT INTO `operation_log_manage` VALUES (18, 0, 0, '2010-07-28 10:12:00', '日志接口');
INSERT INTO `operation_log_manage` VALUES (19, 0, 0, '2010-07-28 10:18:14', '日志接口');
INSERT INTO `operation_log_manage` VALUES (20, 0, 0, '2010-07-28 14:22:04', '日志接口');

-- --------------------------------------------------------

-- 
-- 表的结构 `permission`
-- 

CREATE TABLE `permission` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `authority_code` varchar(50) NOT NULL COMMENT '权限码',
  `authority_name` varchar(50) NOT NULL COMMENT '名称',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `permission`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `position_info`
-- 

CREATE TABLE `position_info` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `gps_id` char(11) NOT NULL COMMENT 'GPS编号',
  `file_index` bigint(20) NOT NULL COMMENT '文件索引',
  `receive_time` char(10) NOT NULL COMMENT '接收时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `position_info`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `role`
-- 

CREATE TABLE `role` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `name` varchar(20) NOT NULL COMMENT '角色名',
  `description` text COMMENT '描述',
  `company_id` int(11) NOT NULL COMMENT '所属公司id',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `role`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `speed_color`
-- 

CREATE TABLE `speed_color` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `company_id` int(11) NOT NULL COMMENT '所属公司id',
  `min` int(11) NOT NULL COMMENT '本段最小值',
  `max` int(11) default NULL COMMENT '本段最大值',
  `color` char(6) default NULL COMMENT '颜色',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `speed_color`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `stop_statistic`
-- 

CREATE TABLE `stop_statistic` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `vehicle_id` int(11) NOT NULL COMMENT '车辆id',
  `driver_id` int(11) NOT NULL COMMENT '驾驶员id',
  `start_time` datetime default NULL COMMENT '开始时间',
  `end_time` datetime default NULL COMMENT '结束时间',
  `stop_time` float default NULL COMMENT '停车时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `stop_statistic`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `user`
-- 

CREATE TABLE `user` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `login_name` varchar(20) NOT NULL COMMENT '登录用户名',
  `password` varchar(15) NOT NULL COMMENT '密码',
  `name` varchar(20) default NULL COMMENT '用户名',
  `company_id` int(11) NOT NULL COMMENT '所属公司id',
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `email` varchar(30) default NULL COMMENT '邮箱',
  `state` tinyint(1) NOT NULL COMMENT '状态',
  `backup1` varchar(100) default NULL COMMENT '备用字段1',
  `backup2` varchar(100) default NULL COMMENT '备用字段2',
  `backup3` varchar(100) default NULL COMMENT '备用字段3',
  `backup4` varchar(100) default NULL COMMENT '备用字段4',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- 导出表中的数据 `user`
-- 

INSERT INTO `user` VALUES (1, 'root', 'root', 'hello', 1, 1, 'lishaojie@17uxi.cn', 1, '6', '', '', '', 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `user` VALUES (2, 'admin', 'admin', 'world', 1, 1, 'lishaojie@17uxi.cn', 1, NULL, NULL, NULL, NULL, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');

-- --------------------------------------------------------

-- 
-- 表的结构 `user_authority`
-- 

CREATE TABLE `user_authority` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `authority_id` int(11) NOT NULL COMMENT '权限id',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `user_authority`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `vehicle_group`
-- 

CREATE TABLE `vehicle_group` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `name` varchar(50) NOT NULL COMMENT '组名',
  `company_id` int(11) NOT NULL COMMENT '所属公司id',
  `description` int(11) default NULL COMMENT '描述',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=103 ;

-- 
-- 导出表中的数据 `vehicle_group`
-- 

INSERT INTO `vehicle_group` VALUES (101, 'bbv', 1, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `vehicle_group` VALUES (102, 'lsjlsfhdw', 2, 3, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- 表的结构 `vehicle_manage`
-- 

CREATE TABLE `vehicle_manage` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `number_plate` char(12) NOT NULL COMMENT '车牌号(ID)',
  `gps_id` char(11) NOT NULL COMMENT 'GPS设备编号',
  `vehicle_group_id` int(11) NOT NULL COMMENT '车辆组id',
  `driver_id` int(11) NOT NULL COMMENT '驾驶员id',
  `type_id` int(11) NOT NULL COMMENT '车型id',
  `cur_longitude` float(9,6) default NULL COMMENT '当前经度',
  `cur_latitude` float(8,6) default NULL COMMENT '当前纬度',
  `cur_speed` float(3,3) default NULL COMMENT '当前速度',
  `cur_direction` tinyint(1) default NULL COMMENT '当前方向',
  `alert_state` tinyint(1) default NULL COMMENT '告警状态',
  `color` char(2) default NULL COMMENT '颜色',
  `running_time` float default NULL COMMENT '当前累计的行驶时间',
  `backup1` varchar(100) default NULL COMMENT '备用字段1',
  `backup2` varchar(100) default NULL COMMENT '备用字段2',
  `backup3` varchar(100) default NULL COMMENT '备用字段3',
  `backup4` varchar(100) default NULL COMMENT '备用字段4',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- 导出表中的数据 `vehicle_manage`
-- 

INSERT INTO `vehicle_manage` VALUES (1, '10034', '1001', 101, 1, 101, 0.000000, 0.000000, 0.000, 0, 2, '', 2, 'a', 'b', 'c', 'd', 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `vehicle_manage` VALUES (2, '13245', '233', 102, 2, 101, 2.000000, 0.000000, 0.000, 0, 2, '', 0, 'er', 'we', NULL, NULL, 3, NULL, NULL, NULL);
INSERT INTO `vehicle_manage` VALUES (3, '3', '5545', 103, 2, 0, 0.000000, 0.000000, 0.000, 0, 1, '4', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `vehicle_manage` VALUES (4, '45', '223', 102, 2, 101, 0.000000, 0.000000, 0.000, 0, 1, '', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

-- 
-- 表的结构 `vehicle_type_manage`
-- 

CREATE TABLE `vehicle_type_manage` (
  `id` int(11) NOT NULL auto_increment COMMENT '编号',
  `company_id` int(11) NOT NULL COMMENT '所属公司id',
  `name` varchar(10) NOT NULL COMMENT '类型名称',
  `fuel_consumption` float default NULL COMMENT '耗油指标',
  `load_capacity` float default NULL COMMENT '载重',
  `description` varchar(50) default NULL COMMENT '描述',
  `create_id` int(11) default NULL COMMENT '创建人',
  `create_time` datetime default NULL COMMENT '创建时间',
  `update_id` int(11) default NULL COMMENT '更新人',
  `update_time` datetime default NULL COMMENT '更新时间',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

-- 
-- 导出表中的数据 `vehicle_type_manage`
-- 

INSERT INTO `vehicle_type_manage` VALUES (101, 1, 'aaa', NULL, NULL, NULL, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');

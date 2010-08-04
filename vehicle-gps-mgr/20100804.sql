-- moudle 表

INSERT INTO `module` VALUES (5001, 'driver', 'admin/driver', '人员管理', 1, 1, 1, '2010-07-26 20:39:06', 1, '2010-07-26 20:39:06');
INSERT INTO `module` VALUES (5002, 'company', 'admin/company', '公司管理', 1, 1, 1, '2010-07-27 10:46:35', 1, '2010-07-27 10:46:35');
INSERT INTO `module` VALUES (5003, 'log', 'admin/log', '日志管理', 3, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module` VALUES (5004, 'message', 'admin/message', '信息管理', 4, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module` VALUES (5005, 'xml', 'readxml', '读取xml信息', 4, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module` VALUES (5006, 'setting', 'setting', '系统参数设置', 4, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');


 -- lsj moudle
INSERT INTO `module` VALUES (1001, 'user', 'admin/user', '用户', 1, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1003, 'vehicle', 'admin/vehicle', '车辆管理', 4, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1004, 'vehicle_group', 'admin/vehicle_group', '车辆组管理', 4, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1005, 'vehicle_type', 'admin/vehicle_type', '车辆类型管理', 5, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1008, 'login_log', 'admin/login_log', '登录日志管理', 3, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');


-- `module_function` 表
INSERT INTO `module_function` VALUES (5001, '人员管理：列表', 1, '2010-07-26 20:41:21', 'driver', 'driver.php', 'list', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5002, '人员管理：列表数据', 1, '2010-07-26 20:41:21', 'driver', 'driver.php', 'list_data', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5003, '公司管理：列表', 1, '2010-07-26 21:26:28', 'company', 'company.php', 'list', NULL, 0, 1, '2010-07-26 21:26:28', 1, '2010-07-26 21:26:28');
INSERT INTO `module_function` VALUES (5004, '公司管理：列表数据', 1, '2010-07-26 20:41:21', 'company', 'company.php', 'list_data', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5005, '日志管理：列表', 1, '2010-07-27 15:33:20', 'log', 'log.php', 'list', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module_function` VALUES (5006, '日志管理：列表数据', 1, '2010-07-27 15:33:20', 'log', 'log.php', 'list_data', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module_function` VALUES (5007, '日志管理：操作日志接口', 1, '2010-07-27 15:52:50', 'log', 'log.php', 'add_log', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5008, '信息管理：信息列表', 1, '2010-07-27 15:52:50', 'message', 'message.php', 'list', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5009, '信息管理：信息列表数据', 1, '2010-07-27 15:52:50', 'message', 'message.php', 'list_data', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5010, '人员管理：', 1, '2010-07-27 15:52:50', 'driver', 'driver.php', 'edit_data', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5011, '公司管理：添加，修改，删除', 1, '2010-07-27 15:52:50', 'company', 'company.php', 'edit_data', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5012, '读取XML', 1, '2010-07-27 15:52:50', 'xml', 'get_xml.php', 'read_xml', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5013, '查询接受信息的人员', 1, '2010-07-27 15:52:50', 'message', 'message.php', 're_driver', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5014, '查询影响区域', 1, '2010-07-27 15:52:50', 'message', 'message.php', 're_area', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5015, '人员管理：车辆授权', 1, '2010-07-27 15:52:50', 'driver', 'driver.php', 'driver_pri', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5016, '人员管理：执行车辆授权', 1, '2010-07-27 15:52:50', 'driver', 'driver.php', 'submit_pri', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5017, '参数读取', 1, '2010-07-27 15:52:50', 'setting', 'setting.php', 'get_setting', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5018, '参数设置', 1, '2010-07-27 15:52:50', 'setting', 'setting.php', 'speed_color', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5019, '参数设置第一部分', 1, '2010-07-27 15:52:50', 'setting', 'setting.php', 'set_setting', '', 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5020, '刷新速度颜色映射', 1, '2010-07-27 15:52:50', 'setting', 'setting.php', 're_speed_color', '', 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');

  -- lsj moudle_func
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
INSERT INTO `module_function` VALUES (1030, '车辆类型html页面输出', 1, '2010-07-26 10:37:50', 'vehicle_type', 'vehicle_type.php', 'list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1031, '车辆类型数据列表', 1, '2010-07-26 10:37:50', 'vehicle_type', 'vehicle_type.php', 'list_data', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1032, '车辆类型数据增删改操作', 1, '2010-07-26 10:37:50', 'vehicle_type', 'vehicle_type.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1040, '系统设置html页面输出', 1, '2010-07-26 10:37:50', 'system_set', 'sys_set.php', 'list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1041, '系统设置数据输出', 1, '2010-07-26 10:37:50', 'system_set', 'sys_set.php', 'list_data', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1042, '系统设置数据增删改操作', 1, '2010-07-26 10:37:50', 'system_set', 'sys_set.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1050, '登录日志管理：列表', 1, '2010-07-27 15:33:20', 'login_log', 'login_log.php', 'list', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module_function` VALUES (1051, '日志管理：列表数据', 1, '2010-07-27 15:33:20', 'login_log', 'login_log.php', 'list_data', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
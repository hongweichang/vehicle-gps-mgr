-- moudle ��

INSERT INTO `module` VALUES (5001, 'driver', 'admin/driver', '��Ա����', 1, 1, 1, '2010-07-26 20:39:06', 1, '2010-07-26 20:39:06');
INSERT INTO `module` VALUES (5002, 'company', 'admin/company', '��˾����', 1, 1, 1, '2010-07-27 10:46:35', 1, '2010-07-27 10:46:35');
INSERT INTO `module` VALUES (5003, 'log', 'admin/log', '��־����', 3, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module` VALUES (5004, 'message', 'admin/message', '��Ϣ����', 4, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module` VALUES (5005, 'xml', 'readxml', '��ȡxml��Ϣ', 4, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module` VALUES (5006, 'setting', 'setting', 'ϵͳ��������', 4, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');


 -- lsj moudle
INSERT INTO `module` VALUES (1001, 'user', 'admin/user', '�û�', 1, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1003, 'vehicle', 'admin/vehicle', '��������', 4, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1004, 'vehicle_group', 'admin/vehicle_group', '���������', 4, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1005, 'vehicle_type', 'admin/vehicle_type', '�������͹���', 5, 1, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module` VALUES (1008, 'login_log', 'admin/login_log', '��¼��־����', 3, 1, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');


-- `module_function` ��
INSERT INTO `module_function` VALUES (5001, '��Ա�����б�', 1, '2010-07-26 20:41:21', 'driver', 'driver.php', 'list', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5002, '��Ա�����б�����', 1, '2010-07-26 20:41:21', 'driver', 'driver.php', 'list_data', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5003, '��˾�����б�', 1, '2010-07-26 21:26:28', 'company', 'company.php', 'list', NULL, 0, 1, '2010-07-26 21:26:28', 1, '2010-07-26 21:26:28');
INSERT INTO `module_function` VALUES (5004, '��˾�����б�����', 1, '2010-07-26 20:41:21', 'company', 'company.php', 'list_data', NULL, 0, 1, '2010-07-26 20:42:16', 1, '2010-07-26 20:42:22');
INSERT INTO `module_function` VALUES (5005, '��־�����б�', 1, '2010-07-27 15:33:20', 'log', 'log.php', 'list', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module_function` VALUES (5006, '��־�����б�����', 1, '2010-07-27 15:33:20', 'log', 'log.php', 'list_data', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module_function` VALUES (5007, '��־����������־�ӿ�', 1, '2010-07-27 15:52:50', 'log', 'log.php', 'add_log', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5008, '��Ϣ������Ϣ�б�', 1, '2010-07-27 15:52:50', 'message', 'message.php', 'list', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5009, '��Ϣ������Ϣ�б�����', 1, '2010-07-27 15:52:50', 'message', 'message.php', 'list_data', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5010, '��Ա����', 1, '2010-07-27 15:52:50', 'driver', 'driver.php', 'edit_data', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5011, '��˾������ӣ��޸ģ�ɾ��', 1, '2010-07-27 15:52:50', 'company', 'company.php', 'edit_data', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5012, '��ȡXML', 1, '2010-07-27 15:52:50', 'xml', 'get_xml.php', 'read_xml', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5013, '��ѯ������Ϣ����Ա', 1, '2010-07-27 15:52:50', 'message', 'message.php', 're_driver', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5014, '��ѯӰ������', 1, '2010-07-27 15:52:50', 'message', 'message.php', 're_area', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5015, '��Ա����������Ȩ', 1, '2010-07-27 15:52:50', 'driver', 'driver.php', 'driver_pri', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5016, '��Ա����ִ�г�����Ȩ', 1, '2010-07-27 15:52:50', 'driver', 'driver.php', 'submit_pri', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5017, '������ȡ', 1, '2010-07-27 15:52:50', 'setting', 'setting.php', 'get_setting', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5018, '��������', 1, '2010-07-27 15:52:50', 'setting', 'setting.php', 'speed_color', NULL, 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5019, '�������õ�һ����', 1, '2010-07-27 15:52:50', 'setting', 'setting.php', 'set_setting', '', 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');
INSERT INTO `module_function` VALUES (5020, 'ˢ���ٶ���ɫӳ��', 1, '2010-07-27 15:52:50', 'setting', 'setting.php', 're_speed_color', '', 0, 1, '2010-07-27 15:52:50', 1, '2010-07-27 15:52:50');

  -- lsj moudle_func
INSERT INTO `module_function` VALUES (1001, '����û�����html', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'user_manage', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1002, '��ʾ�û����������б�', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'list_data', NULL, 0, 1, '2010-07-26 10:41:08', 1, '2010-07-26 10:41:08');
INSERT INTO `module_function` VALUES (1003, '��¼�ɹ�ҳ��', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'login_success', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1004, '����', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'manage_list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1005, '�˳�', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'logout', NULL, 0, 1, '2010-07-26 10:37:50', 1, '2010-07-26 10:37:50');
INSERT INTO `module_function` VALUES (1006, 'ϵͳ����', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'setup', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1007, '�û���ɾ�ò���', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1008, '�����б�����', 1, '2010-07-26 10:37:50', 'user', 'user.php', 'select', NULL, 0, 1, '2010-07-26 10:41:08', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1010, '����htmlҳ�����', 1, '2010-07-26 10:37:50', 'vehicle', 'vehicle.php', 'list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1011, '�����������', 1, '2010-07-26 10:37:50', 'vehicle', 'vehicle.php', 'list_data', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1012, '��������ɾ�Ĳ���', 1, '2010-07-26 10:37:50', 'vehicle', 'vehicle.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1013, '�����б�����', 1, '2010-07-26 10:37:50', 'vehicle', 'vehicle.php', 'select', NULL, 0, 1, '2010-07-26 10:41:08', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1020, '������htmlҳ�����', 1, '2010-07-26 10:37:50', 'vehicle_group', 'vehicle_group.php', 'list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1021, '�������������', 1021, '2010-07-26 10:37:50', 'vehicle_group', 'vehicle_group.php', 'list_data', NULL, 0, 1, '2010-07-26 10:37:50', 1, '2010-07-26 10:37:50');
INSERT INTO `module_function` VALUES (1022, '���������ɾ�Ĳ���', 1, '2010-07-26 10:37:50', 'vehicle_group', 'vehicle_group.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1023, '�����б�����', 1, '2010-07-26 10:37:50', 'vehicle_group', 'vehicle_group.php', 'select', NULL, 0, 1, '2010-07-26 10:41:08', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1030, '��������htmlҳ�����', 1, '2010-07-26 10:37:50', 'vehicle_type', 'vehicle_type.php', 'list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1031, '�������������б�', 1, '2010-07-26 10:37:50', 'vehicle_type', 'vehicle_type.php', 'list_data', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1032, '��������������ɾ�Ĳ���', 1, '2010-07-26 10:37:50', 'vehicle_type', 'vehicle_type.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1040, 'ϵͳ����htmlҳ�����', 1, '2010-07-26 10:37:50', 'system_set', 'sys_set.php', 'list', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1041, 'ϵͳ�����������', 1, '2010-07-26 10:37:50', 'system_set', 'sys_set.php', 'list_data', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1042, 'ϵͳ����������ɾ�Ĳ���', 1, '2010-07-26 10:37:50', 'system_set', 'sys_set.php', 'operate', NULL, 0, 1, '2010-07-26 10:36:23', 1, '2010-07-26 10:36:36');
INSERT INTO `module_function` VALUES (1050, '��¼��־�����б�', 1, '2010-07-27 15:33:20', 'login_log', 'login_log.php', 'list', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
INSERT INTO `module_function` VALUES (1051, '��־�����б�����', 1, '2010-07-27 15:33:20', 'login_log', 'login_log.php', 'list_data', NULL, 0, 1, '2010-07-27 15:33:20', 1, '2010-07-27 15:33:20');
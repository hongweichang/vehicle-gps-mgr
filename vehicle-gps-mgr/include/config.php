<?php
//		数据库配置
$db_config = array(
	'HOST'			=> '220.194.47.152',
	'USERNAME'			=> 'qinyh',
	'PASSWORD'			=> '608',
	'DB'		=> 'vehicle_gps_mgr');
	
//		memcached配置
$memcache_config = array(
	'ENABLED' => false,
	'SERVER' => array(
			0 => array('HOST'=>'devmem','PORT'=>'9999')
			)
	);

$mail_config = array(
		"Host"		=> "smtp.gmail.com",		// 发送邮件的服务器
		"Port"		=> 465,						// 设置SMTP的端口号
		"SMTPAuth"	=> true,					// 打开SMTP	
		"SMTPSecure"=> "ssl",					// 设置连接的前缀。
		"Username"	=> "LiuHuanQYH@gmail.com",	// SMTP账户   
		"Password"	=> "LIUHUAN528xuke",		// SMTP密码   
		"From"		=> "LiuHuanQYH@gmail.com",  // 发件人E-mail地址	
		"FromName"	=> "peng",             		// 发件人称呼
		"WordWrap"	=> 50,						// 每行 50 个字  
		"CharSet"	=> "UTF-8"					// 设置字符集编码   
	);

$server_path_config = array(
		"gps_info_path"		=> "/usr/local/joycomm/sos_file", //gpsx信息文件在服务器上的路径
		"mail_save_path"	=> "email_data", //gpsx信息文件在服务器上的路径
	);

?>

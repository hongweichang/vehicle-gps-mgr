<?php
//===================配置文件======================

//数据库配置
$db_config = array(
	'HOST'			=> '220.194.47.152',
	'USERNAME'			=> 'qinyh',
	'PASSWORD'			=> '608',
	'DB'		=> 'vehicle_gps_mgr');
	
//memcached配置
$memcache_config = array(
	'ENABLED' => false,
	'SERVER' => array(
			0 => array('HOST'=>'devmem','PORT'=>'9999')
			)
	);

//Email配置
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

//服务路径
$server_path_config = array(
		"gps_info_path"		=> "/usr/local/joycomm/sos_file", 			//gpsx信息文件在服务器上的路径
		"mail_save_path"	=> "/usr/local/joycomm/email_src/waiting",  //gpsx信息文件在服务器上的路径
		"photo_assign_path"	=> "images/photo/",			//下发照相指令照片存入路径
		"subfolder"			=>"" 										//当系统布置到网站子目录的情况下使用
	);
	
//初	始化速度颜色
$init_speed_color=array(
		1=>array( //速度阶段1
			"min"=>"0",        //最小速度
			"max"=>"40",  	   //最大速度
			"color"=>"54C8EA", //速度颜色
		),
		2=>array( //速度阶段2
			"min"=>"40", 	   //最小速度
			"max"=>"80", 	   //最大速度
			"color"=>"FFF200", //速度颜色
		),
		3=>array( //速度阶段3
			"min"=>"80",       //最小速度
			"max"=>"120", 	   //最大速度
			"color"=>"9D1E23", //速度颜色
		)
);	

//默认设置
$default_setting=array(
	"page_refresh_time"=>"5", //刷新页面时间 
	"default_color"=>"392768",//默认颜色
	"speed_astrict"=>"100",	  //超速限制
	"fatigue_remind_time"=>"5"//疲劳驾驶限制
);

?>

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

?>

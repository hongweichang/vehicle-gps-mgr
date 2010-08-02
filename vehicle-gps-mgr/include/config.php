<?php
//		数据库配置
$db_config = array(
	'HOST'			=> '192.168.1.120',
	'USERNAME'			=> 'root',
	'PASSWORD'			=> 'root',
	'DB'		=> 'vehicle_gps_mgr');
	
//		memcached配置
$memcache_config = array(
	'ENABLED' => false,
	'SERVER' => array(
			0 => array('HOST'=>'devmem','PORT'=>'9999')
			)
	);

?>

<?php
//		���ݿ�����
$db_config = array(
	'HOST'			=> 'localhost',
	'USERNAME'			=> 'root',
	'PASSWORD'			=> 'root',
	'DB'		=> 'vehicle_gps_mgr');
	
//		memcached����
$memcache_config = array(
	'ENABLED' => false,
	'SERVER' => array(
			0 => array('HOST'=>'devmem','PORT'=>'9999')
			)
	);

?>

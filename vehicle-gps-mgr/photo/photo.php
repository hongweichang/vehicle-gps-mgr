<?php
/** 
 * 照片相关
 * @copyright		秦运恒, 2011
 * @author			赵将伟
 * @create date		2011.03.30
 * @modify			修改人
 * @modify date		修改日期
 * @modify describe	修改内容
 */
$act = $GLOBALS ["all"] ["operate"];

$photo = new Photo ();

switch ($act) {
	case "assign_photo" :
		$deal_time = get_sysdate ();
		
		$param ['gps_id'] = $GLOBALS ['db']->prepare_value ( $_REQUEST ['gps_id'], "CHAR" );
		$param ['specialname'] = $GLOBALS ['db']->prepare_value ( $_REQUEST ['name'], "CHAR" );
		$param ['cmd_type'] = $GLOBALS ['db']->prepare_value ( "P1", "CHAR" );
		$param ['deal_time'] = $GLOBALS ['db']->prepare_value ( get_sysdate (), "VARCHAR" );
		
		$result = $photo->assign_photo ( $param );
		if (false === $result) {
			echo "fail";
		} else {
			$filetime = explode ( " ", $deal_time );
			$photofilename = $_REQUEST ['gps_id'] . "_" . str_replace ( "-", "", $filetime [0] ) . "_" . $_REQUEST ['name'] . ".jpg";
			
			echo $photofilename;
		}
		
		break;
	
	case "recent_photo" :
		$photofilename = $_REQUEST ['name'];
		$photopath = $server_path_config ['photo_assign_path'] . $photofilename;
		//$photopath = "images/13823358278_20110323_test1.jpg";
		

		if (file_exists ( $photopath )) {
			echo $photopath;
		} else {
			echo "fail";
		}
		
		break;
	
	case "history_photo" :
		$gps_id = $_REQUEST ['gps_id'];
		
		$allfiles = glob ( $server_path_config ['photo_assign_path'] . $gps_id . "*", GLOB_NOSORT );
		//$allfiles = glob ( "images/13823358278*", GLOB_NOSORT );
		

		if (empty ( $allfiles )) {
			echo "fail";
		} else {
			echo json_encode ( $allfiles );
		}
		
		break;
}
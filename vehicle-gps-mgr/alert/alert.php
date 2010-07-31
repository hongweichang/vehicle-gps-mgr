<?php
/**
 * 告警处理
@copyright  秦运恒, 2010
 * @author 　　段贵山
 * @create date 　 2010.07.30
 */
require_once ("include/data_mapping_handler.php");


$act = $GLOBALS ["all"] ["operate"];

$page = $_REQUEST ['page']; // get the requested page
$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST ['sord']; // get the direction
$searchfil = $_REQUEST ['searchField']; // get the direction
$searchstr = $_REQUEST ['searchString']; // get the direction


$path = $_SERVER ["DOCUMENT_ROOT"]."/vehicle-gps-mgr/include/comm_setting.xml"; //定义xml文件局对路径
$tableName = "alert_info";
$colName = "alert_type";

if (! $sidx)
	$sidx = 1;

switch ($act) {
	case "list" : //模拟测试
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	
	case "list_data" :
		
		$alert = new Alert ();
		$count = $alert->get_all_count ();
		
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages)
			$page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		
		$wh=" where dispose_opinion is null ";
		$dataList = $alert->get_all_alerts ( $wh, $sidx, $sord, $start, $limit );
		
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		
		foreach ( $dataList as $key => $value ) {
			$dataMapping = new Data_mapping_handler ( $path );
			
			$alert_type_display = $dataMapping->getMappingText ( $tableName, $colName, $value ['alert_type'] );
			$vehicle_number = $alert->get_vehicle_manage_number ( $value ['vehicle_id'] );//onclick="jQuery('#dialog').dialog('open')"
			$user_name = $alert->get_user_name ( $value ['dispose_id'] );
			
			$responce->rows [$key] ['id'] = $value['id'];
			$responce->rows [$key] ['cell'] = array ($value['id'],$value['alert_time'], $alert_type_display, $vehicle_number, $user_name, 
			                                         $value['description'],"<a href='javascript:void(0)' onclick='adviceDialog();' style='text-decoration:none;color:#0099FF'>未处理</a>" );
		}
		
		//打印json格式的数据
		echo json_encode ($responce);
		
		break;

}
?>
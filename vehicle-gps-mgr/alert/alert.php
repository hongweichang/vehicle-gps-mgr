<?php
/**
 * 告警处理
@copyright  秦运恒, 2010
 * @author 　　段贵山
 * @create date 　 2010.07.30
 */
require_once ("include/data_mapping_handler.php");

$act = $GLOBALS ["all"] ["operate"];//取得功能名称

$page = $_REQUEST ['page']; // get the requested page
$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST ['sord']; // get the direction


$advice = $_REQUEST ["advice"]; //接收到的告警处理意见
$id = $_REQUEST ["id"]; //要增加处理意见的数据id

//定义xml映射文件局对路径
$comm_setting_path = $_SERVER ["DOCUMENT_ROOT"] . "/vehicle-gps-mgr/xml/comm_setting.xml"; 
//定义xml映射文件局对路径
$treatment_advice = $_SERVER ["DOCUMENT_ROOT"] . "/vehicle-gps-mgr/xml/treatment_advice.xml"; 


$tableName = "alert_info";//解析xml文件中对应的表明
$colName = "alert_type";//解析xml文件中对应的列名

$lableName="advice";//解析xml文件中标签名称

if (! $sidx)
	$sidx = 1;

switch ($act) {
	case "list" : //模拟测试
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	
	case "list_data" ://向jqgrid填充数据
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
		
		$wh = " where dispose_opinion is null ";//取得dispose_opinion为null的数据
		$dataList = $alert->get_all_alerts ( $wh, $sidx, $sord, $start, $limit );
		
		$responce->page = $page;//分别赋值当前页,总页数，总数据条数
		$responce->total = $total_pages;
		$responce->records = $count;

		
		$dataMapping = new Data_mapping_handler ( $treatment_advice ); //从xml配置信息中读取告警处理意见   
	    $data_list_advicer=$dataMapping->getTextDataList($lableName);
     
	    
	    foreach($data_list_advicer as $key => $value ){//追加xml文件字符串
	    	$option=$option.$key.",".$value."|";
	    }
	    
	    
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值
	
			$dataMapping = new Data_mapping_handler ( $comm_setting_path );	
			$alert_type_display = $dataMapping->getMappingText ( $tableName, 
																 $colName, 
																 $value ['alert_type'] );
																 
		
			$vehicle_number = $alert->get_vehicle_manage_number ( $value ['vehicle_id'] ); 
			$user_name = $alert->get_user_name ( $value ['dispose_id'] );
			
			$responce->rows [$key] ['id'] = $value ['id'];
			$responce->rows [$key] ['cell'] = array ($value ['id'], 
													 $value ['alert_time'], 
													 $alert_type_display, 
													 $vehicle_number, $user_name, 
													 $value ['description'],
			                                         "<a href='javascript:void(0)' onclick=adviceDialog(".$value ['id'].",'".count($data_list_advicer)."','".$option."'); style='text-decoration:none;color:#0099FF'>未处理</a>");
		}
		echo json_encode ( $responce );	//打印json格式的数据
		break;
	case "edit" : //给指定的数据添加处理意见
		$alert = new Alert ($id);
		$arr["dispose_opinion"]=$advice;
		$boolean = $alert->edit_alert_advice($arr);
		if ($boolean) {
			echo "success";
		} else {
			echo "fail";
		}
		break;

}
?>
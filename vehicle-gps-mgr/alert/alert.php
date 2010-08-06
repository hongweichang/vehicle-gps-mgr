<?php
/**
 * 告警处理
@copyright  秦运恒, 2010
 * @author 　　段贵山
 * @create date 　 2010.07.30
 */
require_once ("include/data_mapping_handler.php");
//require_once ("include/commmon.php");
$act = $GLOBALS ["all"] ["operate"]; //取得功能名称


$page = $_REQUEST ['page']; // 得到当前页数
$limit = $_REQUEST ['rows']; // 得到一页的行数
$sidx = $_REQUEST ['sidx']; // 得第一列
$sord = $_REQUEST ['sord']; // 得到排序


$id = $_REQUEST ["id"]; //要增加处理意见的数据id


//定义xml映射文件局对路径
$comm_setting_path = $all ["BASE"] . "xml/comm_setting.xml";
//定义xml映射文件局对路径
$treatment_advice = $all ["BASE"] . "xml/treatment_advice.xml";

$tableName = "alert_info"; //解析xml文件中对应的表明
$colName = "alert_type"; //解析xml文件中对应的列名


$lableName = "advice"; //解析xml文件中标签名称


$vehicle_id = $_REQUEST ['vehicle_id']; //车辆组id


$deal = $_REQUEST ['deal']; //是否是已处理


if (! $sidx)
	$sidx = 1;
if (! $sord)
	$sord = "asc";
if (! $page)
	$page = 1;
if (! $limit)
	$limit = 10;

if (! empty ( $deal )) { //判断是否选中处理意见	
	$wh = " where 1=1 ";
} else {
	$wh = " where dispose_opinion is null ";
}

if (! empty ( $vehicle_id )) { //判断是否选中全部车辆
	$count = strlen ( $vehicle_id ) - 1;
	$str = substr ( $vehicle_id, 1, $count );
	if (substr ( $vehicle_id, 0, 1 ) == "@") {
		$wh = $wh . " and vehicle_id in(select vm.id  from vehicle_manage as vm inner join  vehicle_group as vg" . " on vg.id=vm.vehicle_group_id and vm.vehicle_group_id=" . $str . ")";
	} else {
		$wh = $wh . " and vehicle_id=" . $vehicle_id; //取得dispose_opinion为null的数据
	}
}

switch ($act) {
	case "list" : //模拟测试
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	
	case "list_data" : //向jqgrid填充数据
		

		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$alert = new Alert ();
		
		$count = $alert->get_all_count ( $wh );
		
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
		
		$dataList = $alert->get_all_alerts ( $wh, $sidx, $sord, $start, $limit );
		
		$responce->page = $page; //分别赋值当前页,总页数，总数据条数
		$responce->total = $total_pages;
		$responce->records = $count;
		
		$dataMapping = new Data_mapping_handler ( $treatment_advice ); //从xml配置信息中读取告警处理意见   
		$data_list_advicer = $dataMapping->getTextDataList ( $lableName );
		
		foreach ( $data_list_advicer as $key => $value ) { //追加xml文件字符串
			$option = $option . $key . "," . $value . "|";
		}
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值
			

			$dataMapping = new Data_mapping_handler ( $comm_setting_path );
			$alert_type_display = $dataMapping->getMappingText ( $tableName, $colName, $value ['alert_type'] );
			
			$vehicle_number = $alert->get_vehicle_manage_number ( $value ['vehicle_id'] );
			$user_name = $alert->get_user_name ( $value ['dispose_id'] );
			
			$responce->rows [$key] ['id'] = $value ['id'];
			if (! empty ( $value ['dispose_opinion'] )) {
				
				if (strlen($value ['dispose_opinion']) > $limit_length) {
					$char = characteString( $value ['dispose_opinion'], $limit_length );
				}else{
					$char=$value ['dispose_opinion'];
				}
				$responce->rows [$key] ['cell'] = array ($value ['id'], $value ['alert_time'], $alert_type_display, $vehicle_number, $user_name, $value ['description'], $char );
			} else {
				$responce->rows [$key] ['cell'] = array ($value ['id'], $value ['alert_time'], $alert_type_display, $vehicle_number, $user_name, $value ['description'], "<a href='javascript:void(0)' onclick=adviceDialog(" . $value ['id'] . ",'" . count ( $data_list_advicer ) . "','" . $option . "'); style='text-decoration:none;color:#0099FF'>未处理</a>" );
			}
		
		}
		echo json_encode ( $responce ); //打印json格式的数据
		break;
	case "edit" : //给指定的数据添加处理意见
		

		$alert = new Alert ( $_REQUEST ['id'] );
		
		$arr ["dispose_opinion"] = $db->prepare_value ( $_REQUEST ['advice'], "VARCHAR" );
		
		$boolean = $alert->edit_alert_advice ( $arr );
		if ($boolean) {
			echo "success";
		} else {
			echo "fail";
		}
		break;
	case "list_select_data" : //查询所有车辆组名称
		$vehicle_group = "";
		$vehicle = "";
		
		$alert = new Alert ();
		
		$vehicle_group_data = $alert->get_vehicle_group ();
		foreach ( $vehicle_group_data as $key => $value ) { //追加xml文件字符串
			$vehicle_group = $vehicle_group . $value ["id"] . "," . $value ["name"] . "|";
		}
		
		$vehicle_data = $alert->get_vehicle ();
		foreach ( $vehicle_data as $key => $value ) { //追加xml文件字符串
			$vehicle = $vehicle . $value ["id"] . "," . $value ["number_plate"] . "|";
		}
		
		echo $vehicle_group . "@" . $vehicle;
		break;
	
	case "list_relevance_data" : //根据车辆组查询相关车辆
		$vehicle_data = "";
		$alert = new Alert ();
		
		$vehicle_list_count = $alert->get_count_vehicle ( $vehicle_id );
		$vehicle_list = $alert->get_linkage_vehicle ( $vehicle_id );
		if ($vehicle_list_count > 0) {
			foreach ( $vehicle_list as $key => $value ) { //追加xml文件字符串
				$vehicle_data = $vehicle_data . $value ["id"] . "," . $value ["number_plate"] . "|";
			}
		} else {
			$vehicle_data = 0;
		}
		echo $vehicle_data;
		break;
	case "newest_alert" : //查询24小时内的最新告警	
		$alert = new Alert ();
		$record = $alert->get_newest_alert ();
		
		if ($record != null) {
			echo "在" . $record [0] . "时间点，车牌号为" . $record [1] . "，产生了告警：" . $record [2];
		} else {
			echo "没有最新告警";
		}
}
?>
<?php
/**
 * 告警处理
@copyright  秦运恒, 2010
 * @author 　　段贵山
 * @create date 　 2010.07.30
 */

session_start();//开启session 

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

/**
 * 
 * @var unknown_type
 */
 

$group_id=$_REQUEST ['group_id'];//车辆组id
$vehicle_id=$_REQUEST ['vehicle_id'];//车辆id
$deal=$_REQUEST ['deal'];//是否处理

if (! $sidx)
	$sidx = 1;
if (! $sord)
	$sord = "asc";
if (! $page)
	$page = 1;
if (! $limit)
	$limit = 10;

if ($deal=='true') { //1为显示全部数据 0为显示为处理数据
	$wh = " where 1=1 ";
} else {
	$wh = " where (dispose_opinion is null or \"\" = dispose_opinion) ";
}



switch ($act) {
	case "list" : //模拟测试
$vehicle_group = "";

		$vehicle_group_options = "<option value=-1 selected>全部车辆组</option>";
		$alert = new Alert ();
		$vehicle_group_data = $alert->get_vehicle_group ();
		foreach ( $vehicle_group_data as $key => $value ) {
			$vehicle_group_options = $vehicle_group_options."<option value=\"".$value['id']."\" >".$value['name']."</option>"; 
		}
		
		$param["GROUP_OPTION"] = $vehicle_group_options;
		$param["VEHICLE_OPTION"] = "<option value=-1 selected>全部车辆</option>";
		
		echo $GLOBALS ['db']->display ( $param, $act );
		break;
	
	case "list_data" : //向jqgrid填充数据

		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$alert = new Alert ();
		
		$count = $alert->get_all_count ($group_id,$vehicle_id, $wh );
		
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
		
		$dataList = $alert->get_all_alerts ( $group_id,$vehicle_id,$wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { 
			$dataMapping = new Data_mapping_handler ( $comm_setting_path );//从xml文件中映射相应的数据库字段值
			$alert_type_display = $dataMapping->getMappingText ( $tableName, $colName, $value ['alert_type'] );
			
			$vehicle_number = $alert->get_vehicle_manage_number ( $value ['vehicle_id'] );
			$user_name = $alert->get_user_name ( $value ['dispose_id'] );
			
			$response->rows [$key] ['id'] = $value ['id'];
			

			$length=strlen(trim($value['dispose_opinion']));
			
			if (strlen(trim($value['dispose_opinion']))!=0)
			 {
				
				if (strlen($value ['dispose_opinion']) > $limit_length) {
					$shortString = convertOverlongString( $value ['dispose_opinion'], $limit_length );
				}else{
					$shortString=$value ['dispose_opinion'];
				}
			   $response->rows [$key] ['cell'] = array ($value ['id'], $value ['alert_time'], $alert_type_display, $vehicle_number, $user_name, $value ['description'], $shortString );
			} else {
				$response->rows [$key] ['cell'] = array ($value ['id'], $value ['alert_time'], $alert_type_display, $vehicle_number, $user_name, $value ['description'], "<a href='#' onclick=\"showOpinion(".$value ['id'].")\" style='text-decoration:none;color:#0099FF'>未处理</a>");
			}
		
		}
		echo json_encode ( $response ); //打印json格式的数据
		break;
		
	case "write_opinion":
		$dataMapping = new Data_mapping_handler ( $treatment_advice ); //从xml配置信息中读取告警处理意见   
		$data_list_advicer = $dataMapping->getTextDataList ( $lableName );
		$options_str = "";
		foreach($data_list_advicer as $key => $value)
		{
			$options_str = $options_str."<option value=\"".$key."\">".$value."</option>";
		}
		
		$options["option"] = $options_str;
		$options["id"] = $_REQUEST ['id'];
		echo $GLOBALS ['db']->display ( $options, $act );
		break;
		
	case "add_opinion" : //给指定的数据添加处理意见
		$alert = new Alert ( $_REQUEST ['id'] );
		$arr ["dispose_opinion"] = $db->prepare_value ( $_REQUEST ['advice'], "VARCHAR" );
		
		$boolean = $alert->edit_alert_advice ( $arr );
		if ($boolean) {
			echo "意见添加成功";
		} else {
			echo "意见添加失败";
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
	case "newest_alert" : //查询没有处理的最新告警	
		$alert = new Alert ();
		$record = $alert->get_newest_alert ();
		if($record!=null){	
			$dataMapping = new Data_mapping_handler ( $comm_setting_path );
			$alert_type_display = $dataMapping->getMappingText ( $tableName, $colName, $record[3]);
			
			echo $record[0]."|".$record[1]."|".$record[2]."|".$alert_type_display;
		}
		else{
			echo "-1";
		}
		break;
}	
?>
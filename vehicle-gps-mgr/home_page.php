<?php
/** 
 * 首页处理
 * @copyright		秦运恒, 2010
 * @author			叶稳
 * @create date		2010.08.07
 * @modify			修改人 
 * @modify date		修改日期
 * @modify describe	修改内容
 */
$act = $GLOBALS ["all"] ["operate"];
$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
$company_id = get_session ( "company_id" ); //获取当前公司ID
$identify_id = get_session ( "identify_id" ); //获取角色ID
$arr ['host'] = "http://" . $_SERVER ['HTTP_HOST'] . $server_path_config ["subfolder"]; //主机


if (! $sidx)
	$sidx = 1;

$vehicle_console = new vehicle_console ();
switch ($act) {
	case "list" : //首页加载
		

		$common_setting = new Setting ( $company_id );
		$arr ['url_manage'] = URL ( 'user', 'user.php', 'manage_list' ); //管理中心链接
		

		$arr ['url_logout'] = URL ( 'user', 'user.php', 'logout' ); //退出链接
		

		$page_refresh_time = $common_setting->data ["page_refresh_time"];
		if (! isset ( $page_refresh_time )) {
			$page_refresh_time = 30;
		}
		$arr ['page_refresh_time'] = "var page_refresh_time=" . $page_refresh_time . ";"; //页面刷新时间
		

		echo $db->display ( $arr, "list" );
		break;
	
	case "vehicle_position" : //车辆定位加载	
		

		//获取当前公司所有车辆信息
		$vehicle = $vehicle_console->company_all_vehicle ( $company_id );
		
		//导入数据映射文件解析类
		require_once ("include/data_mapping_handler.php");
		
		//创建XML解析对象
		$xml_handler = new Data_mapping_handler ( $GLOBALS ["all"] ["BASE"] . "xml/color.xml" );
		$arr_vehicle = array (); //车辆数据数组
		

		$index = 0; //下标索引
		

		$ve_status = new Vehicle_status ();
		
		//获取车辆定位信息
		foreach ( $vehicle as $value ) {
			
			$lon = $ve_status->around ( $value ['cur_longitude'], 0 );
			$lat = $ve_status->around ( $value ['cur_latitude'], 0 );
			
			if (! isset ( $_REQUEST ['map_type'] ) || (isset ( $_REQUEST ['map_type'] ) && ($google_map_config ['is_exact'] === $_REQUEST ['map_type']))) {
				$ve_status->exact_lon_lat ( $lon, $lat );
			}
			
			$arr_vehicle [$index] ['id'] = $value ['id']; //车辆id
			$arr_vehicle [$index] ['number_plate'] = $value ['number_plate']; //车牌号			
			$arr_vehicle [$index] ['cur_longitude'] = $lon; //经度
			$arr_vehicle [$index] ['cur_latitude'] = $lat; //纬度	 
			$arr_vehicle [$index] ['alert_state'] = $value ['alert_state']; //告警状态		
			$arr_vehicle [$index] ['cur_direction'] = resolvingDirection ( $value ['cur_direction'] ); //方向 
			

			$arr_vehicle [$index] ['xMin'] = empty ( $value ['xMin'] ) ? '' : $value ['xMin'];
			$arr_vehicle [$index] ['yMin'] = empty ( $value ['yMin'] ) ? '' : $value ['yMin'];
			$arr_vehicle [$index] ['xMax'] = empty ( $value ['xMax'] ) ? '' : $value ['xMax'];
			$arr_vehicle [$index] ['yMax'] = empty ( $value ['yMax'] ) ? '' : $value ['yMax'];
			
			//图片路径
			if (! isset ( $value ['color'] ))
				$arr_vehicle [$index] ['file_path'] = "images/vehicle/gray"; //未设置、设置  默认车辆
			else
				$arr_vehicle [$index] ['file_path'] = str_ireplace ( "/west.png", "", $xml_handler->getTextData ( "color", "#" . $value ['color'] ) );
			
			$index ++;
		}
		echo json_encode ( $arr_vehicle );
		break;
	
	case "vehicle_issue" : //动态加载车辆定位信息
		$ve_status = new Vehicle_status ();
		$vehicle_id = $_REQUEST ['vehicle_id']; //获取车辆ID
		$vehicle = $vehicle_console->get_vehicle ( $vehicle_id ); //根据ID查询车辆信息
		

		if (! isset ( $_REQUEST ['map_type'] ) || (isset ( $_REQUEST ['map_type'] ) && ($google_map_config ['is_exact'] === $_REQUEST ['map_type']))) {
			$lon = $ve_status->exact_lon ( $ve_status->around ( $vehicle [0] ['cur_longitude'], 0 ) ); //经度
			$lat = $ve_status->exact_lat ( $ve_status->around ( $vehicle [0] ['cur_latitude'], 0 ) ); //纬度
		} else {
			$lon = $ve_status->around ( $vehicle [0] ['cur_longitude'], 0 ); //经度
			$lat = $ve_status->around ( $vehicle [0] ['cur_latitude'], 0 ); //纬度
		}
		
		$vehicle[0]['cur_longitude'] = $lon;
		$vehicle[0]['cur_latitude'] = $lat;
		
//		$address = $ve_status->get_location_desc ( $lon / 100000, $lat / 100000 ); //地址
//		if ($address != false) {
//			$vehicle [0] ['location_desc'] = $address;
//		} else {
//			$vehicle [0] ['location_desc'] = "经纬度信息错误";
//		}
		
		if ($vehicle [0] ['group_name'] == null) {
			$vehicle [0] ['group_name'] = "未设置";
		}
		
		if ($vehicle [0] ['driver_name'] == null) {
			$vehicle [0] ['driver_name'] = "未指定";
		}
		
		echo json_encode ( ($vehicle [0]) );
		break;
		
	case "getAdressByLngLat" :
		$ve_status = new Vehicle_status ();
		$lon = $_REQUEST['lng'];
		$lat = $_REQUEST['lat'];
		
		$address = $ve_status->get_location_desc ( $lon / 100000, $lat / 100000 ); //地址
		if ($address != false) {
			echo $address;
		} else {
			echo "位置服务暂时不可用，请稍候再尝试";
		}
		break;
	
	case "as_date" : //年检时间
		$vehicles = $vehicle_console->get_as_date ();
		
		echo json_encode ( $vehicles );
		break;
	
	case "update_as_date" : //修改年检时间
		$vehicle_id = $_REQUEST ['vehicle_id'];
		
		$new_date = $_REQUEST ['new_date'];
		$r_date = str_replace ( "/", "-", $new_date );
		$date_array = explode ( " ", $r_date, 2 );
		$date = $date_array [0];
		
		$result = $vehicle_console->modify_as_date ( $vehicle_id, $GLOBALS ['db']->prepare_value ( $date, "VARCHAR" ) );
		
		if ($result) {
			echo "修改成功";
		} else {
			echo "fail";
		}
		
		break;
	
	case "company_position" : //公司定位
		require_once ("home_page.class.php");
		
		$position_data ['company_id'] = get_session ( "company_id" ); //公司编号 
		$position_data ['name'] = $_REQUEST ['name']; //公司标注点名称 
		$position_data ['longitude'] = $_REQUEST ['longitude']; //经度
		$position_data ['latitude'] = $_REQUEST ['latitude']; //纬度
		

		//实例首页对象
		$home_page = new home_page ();
		//添点标注点记录行
		if ($home_page->company_position ( $position_data ))
			echo "1"; //标注成功
		else
			echo "0"; //标注失败
		break;
	
	case "get_company_position" : //查询公司定位 
		require_once ("home_page.class.php");
		$home_page = new home_page ();
		$companies = $home_page->get_company_position ();
		if ($companies) {
			echo json_encode ( $companies );
		} else {
			echo "fail";
		}
		break;
	
	case "delete_position" : //删除公司标注
		require_once ("home_page.class.php");
		$home_page = new home_page ();
		$position_id = $_REQUEST ['position_id'];
		
		$result = $home_page->delete_company_position ( $position_id );
		if ($result) {
			echo "ok";
		} else {
			echo "fail";
		}
		break;
	
	case "find_position" : //根据经纬度查询公司标注信息
		require_once ("home_page.class.php");
		$home_page = new home_page ();
		$result = $home_page->find_company_position ( $_REQUEST ['longitude'], $_REQUEST ['latitude'] );
		foreach ( $result as $key => $value ) {
			if ($value == null || $value = "") {
				$result [$key] = "未设置";
			}
		}
		
		echo json_encode ( $result );
		break;
	
	case "update_position" : //更新公司标注信息
		require_once ("home_page.class.php");
		$home_page = new home_page ();
		$company_position = $home_page->find_company_position_id ( $_REQUEST ['position_id'] );
		
		$position ['id'] = $company_position ['id'];
		$position ['company_id'] = get_session ( "company_id" );
		$position ['name'] = $GLOBALS ['db']->prepare_value ( $_REQUEST ['name'], "VARCHAR" );
		$position ['longitude'] = $company_position ['longitude'];
		$position ['latitude'] = $company_position ['latitude'];
		
		$result = $home_page->update_company_position ( $position );
		if ($result) {
			echo "ok";
		} else {
			echo "fail";
		}
		
		break;
}

?>
<?php
/** 
 * 车辆当前状态
 * @copyright		秦运恒, 2010
 * @author			叶稳
 * @create date		2010.07.30
 * @modify			修改人
 * @modify date		修改日期
 * @modify describe	修改内容
 */
$act = $GLOBALS ["all"] ["operate"];

$page = $_REQUEST ['page']; // get the requested page
$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST ['sord']; // get the direction
$vehicleIds = $_REQUEST ['vehicleIds']; //获取车辆ID集合  


$company_id = get_session ( "company_id" ); //获取公司ID
if (! $sidx)
	$sidx = 1;

$vehicle_console = new vehicle_console ();

switch ($act) {
	case "select" : //选择车辆		
		$str = "";
		$arrayID = $_REQUEST ['array_ID'];
		$vehicles_request = explode ( ",", $vehicleIds ); //获取首页地图上显示的所有车辆ID,生成数组
		

		/**获取所有车辆组*/
		$vehicle_group = $vehicle_console->get_all_vehicle_group ( $company_id );
		
		/**
		 * 手动配置未分组的车辆组信息
		 */
		$vehicle_group_noset [0] = - 1;
		$vehicle_group_noset [1] = "未分组车辆";
		
		$vehicle_group [count ( $vehicle_group )] = $vehicle_group_noset; //将未分组的车辆组加到所有车辆组里面
		

		/**遍历车辆组，生成车辆组标题*/
		
		$str = "<link type='text/css' href='/css/vehicle_console.css'  media='screen' rel='stylesheet' /><div class='vehicle_status_font'><ul>";
		
		foreach ( $vehicle_group as $value ) {
			
			$str = $str . "<li class='vehicle_status_font'><a href='#tabs" . $value [0] . "'><span>" . $value [1] . "</span></a></li>";
		
		}
		$str = $str . "</ul></div>";
		
		$vehicle_list = explode ( ",", $arrayID );
		$is_selected = "";
		
		/*遍历车辆组，显示车辆*/
		foreach ( $vehicle_group as $values ) {
			$vehicles = $vehicle_console->get_group_vehicle ( "where vehicle_group_id=" . $values [0], $company_id ); //查询该组的所有车辆
			$str = $str . "<div class='vehicle_status_font' id='tabs" . $values [0] . "'>" . "<input type='checkbox'  value=" . $values [0] . " name='selectall' class='selectall' id='selectall" . $values [0] . 

			"'/><span class='table_select_title'>选择本组车辆</span>
							<div class='table_div'><table class='table vehicle_status_font' >";
			
			$count = count ( $vehicles ); //获取该组车辆总数
			$rows = $count / 6; //每行显示六辆车辆
			$exat_rows = round ( $rows ); //总共显示多少行
			

			/*精确行数*/
			if ($exat_rows < $rows) {
				$exat_rows = $exat_rows + 1;
			}
			
			/*遍历生成车辆显示*/
			for($j = 0; $j < $exat_rows; $j ++) {
				$str = $str . "<tr>";
				/*判断是否是最后一行*/
				if ($j == $exat_rows - 1) {
					for($m = $j * 6; $m < $count; $m ++) {
						
						(in_array ( $vehicles [$m] [0], $vehicle_list ) ? $is_selected = "checked=true" : $is_selected = "");
						/*判断GPRS是否在线*/
						if ($vehicles [$m] ['gprs_status'] == 1) {
							$is_ok = 0;
							for($k = 0; $k < count ( $vehicles_request ); $k ++) {
								if ($vehicles [$m] [0] == $vehicles_request [$k]) {
									$str = $str . "<td style='width:120px;height:28px;'><input type='checkbox'" . $is_selected . 

									" checked='checked' class='vehicle' name='" . $values [0] . "' 
											value='" . $vehicles [$m] [0] . "'/>" . $vehicles [$m] [1] . "</td>";
									$is_ok = 1;
								}
							}
							if ($is_ok == 0) {
								$str = $str . "<td style='width:120px;height:28px;'><input type='checkbox'" . $is_selected . 

								" class='vehicle' name='" . $values [0] . "' 
											value='" . $vehicles [$m] [0] . "'/>" . $vehicles [$m] [1] . "</td>";
							}
						} else {
							
							$str = $str . "<td style='width:120px;height:28px;'><input type='checkbox' disabled />" . 

							$vehicles [$m] [1] . "</td>";
						}
					}
					$str = $str . "</tr>";
				} else {
					for($m = $j * 6; $m < ($j + 1) * 6; $m ++) {
						
						(in_array ( $vehicles [$m] [0], $vehicle_list ) ? $is_selected = "checked=true" : $is_selected = "");
						/*判断GPRS是否在线*/
						if ($vehicles [$m] ['gprs_status'] == 1) {
							$is_right = 0;
							for($k = 0; $k < count ( $vehicles_request ); $k ++) {
								if ($vehicles [$m] [0] == $vehicles_request [$k]) {
									$str = $str . "<td style='width:120px;height:28px;'><input type='checkbox'" . $is_selected . 

									" checked='checked' class='vehicle' name='" . $values [0] . "' 
											value='" . $vehicles [$m] [0] . "'/>" . $vehicles [$m] [1] . "</td>";
									$is_right = 1;
								}
							}
							if ($is_right == 0) {
								$str = $str . "<td style='width:120px;height:28px;'><input type='checkbox' " . $is_selected . 

								"  class='vehicle' name='" . $values [0] . "' 
			
							value='" . $vehicles [$m] [0] . "'/>" . $vehicles [$m] [1] . "</td>";
							}
						} else {
							
							$str = $str . "<td style='width:120px;height:28px;'><input type='checkbox' disabled />" . 

							$vehicles [$m] [1] . "</td>";
						}
					}
					$str = $str . "</tr>";
				}
			}
			$str = $str . "</table></div></div>";
		}
		
		$arr ['vehicle_group_data'] = $str;
		echo $db->display ( $arr, "select" ); //输出数据到页面
		

		break;
	case "get_vehicle_position" : //获取请求车辆定位信息
		

		//导入数据映射文件解析类
		require_once ("include/data_mapping_handler.php");
		
		//查询车辆集合定位信息、速度颜色返回数据
		$vehicle = $vehicle_console->get_vehicles ( $vehicleIds, $company_id );
		//创建XML解析对象
		$xml_handler = new Data_mapping_handler ( $GLOBALS ["all"] ["BASE"] . "xml/color.xml" );
		
		//数组长度
		$length = count ( $vehicle );
		$ve_status = new Vehicle_status ();
		
		$arr_vehicle = array (); //车辆数据数组
		

		$index = 0; //下标索引
		

		foreach ( $vehicle as $value ) {
			
			//获取车图标路径
			if (! isset ( $value ['color'] )) {
				$value ['color'] = "images/vehicle/gray";
			} else {
				$value ['color'] = str_ireplace ( "/west.png", "", $xml_handler->getTextData ( "color", "#" . $value ['color'] ) );
			}
			
			//求精准经纬度
			$lon = $ve_status->around ( $value ['cur_longitude'], 0 );
			$lat = $ve_status->around ( $value ['cur_latitude'], 0 );
			
			if (! isset ( $_REQUEST ['map_type'] ) || (isset ( $_REQUEST ['map_type'] ) && ($google_map_config['is_exact'] === $_REQUEST ['map_type']))) {
				$ve_status->exact_lon_lat ( $lon, $lat );
			}
			
			//分解度数换为方向
			$cur_direction = resolvingDirection ( $value ['cur_direction'] );
			
			//赋值
			$arr_vehicle [$index] ['id'] = $value ['id']; //车辆ID
			$arr_vehicle [$index] ['number_plate'] = $value ['number_plate']; //车辆号
			$arr_vehicle [$index] ['alert_state'] = $value ['alert_state']; //告警状态
			$arr_vehicle [$index] ['file_path'] = $value ['color']; //路径
			$arr_vehicle [$index] ['cur_longitude'] = $lon; //经度
			$arr_vehicle [$index] ['cur_latitude'] = $lat; //纬度
			$arr_vehicle [$index] ['cur_direction'] = $cur_direction; //方向
			$arr_vehicle [$index] ['xMin'] = empty ( $value ['xMin'] ) ? '' : $value ['xMin']; //方向
			$arr_vehicle [$index] ['yMin'] = empty ( $value ['yMin'] ) ? '' : $value ['yMin']; //方向
			$arr_vehicle [$index] ['xMax'] = empty ( $value ['xMax'] ) ? '' : $value ['xMax']; //方向
			$arr_vehicle [$index] ['yMax'] = empty ( $value ['yMax'] ) ? '' : $value ['yMax']; //方向
			

			$index ++;
		}
		echo json_encode ( $arr_vehicle );
		break;
}

?>
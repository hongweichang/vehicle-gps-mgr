<?php
/** 
 * 用户处理
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
$vehicleIds = $_REQUEST['vehicleIds'];  //获取车辆ID集合  
//$vehicleIds="1,2,3,4";
if (! $sidx)
	$sidx = 1;

	$vehicle_console = new vehicle_console ();
	
switch ($act) {
	case "select" ://选择车辆		
		 
        /**获取所有车辆组*/
		$vehicle_group = $vehicle_console->get_all_vehicle_group ();
		
		/**生成车辆组标题*/
		$str = "<div style='font-size:13px;'><ul>";
		foreach ( $vehicle_group as $value ) {
			$str = $str . "<li><a href='#tabs" . $value [0] . "'>" . $value [1] . "</a></li>";
		}
		$str = $str."</ul></div>";
		
		/**在车辆组下显示所有的车辆*/
		foreach ( $vehicle_group as $values ) {
			$vehicles = $vehicle_console->get_group_vehicle ( "where vehicle_group_id=" . $values [0] );
			$str = $str . "<div id='tabs" . $values [0] . "'>".
							"&nbsp<input type='checkbox' value=" . $values [0] . " name='selectall' class='selectall' id='selectall" . $values [0] . 
							"'>全选</input><div class='scroll'>";
			
			/**遍历每个车辆组，得到每组的所有车辆*/
			foreach ( $vehicles as $vehicle ) {
				$str = $str . "<input type='checkbox' class='vehicle' name='" . $values [0] . "' value='".$vehicle[0]."'>" . $vehicle [1] . "</input>";
			}
			$str = $str . "</div></div>";
		}
		$arr ['vehicle_group_data'] = $str;
		echo $db->display ( $arr, "select" );

		break;
	case  "get_vehicle_position":  //获取请求车辆定位信息
		
		//导入数据映射文件解析类
		require_once("include/data_mapping_handler.php");
		
		//查询车辆集合定位信息、速度颜色返回数据
		$vehicle = $vehicle_console->get_vehicles($vehicleIds);
		//创建XML解析对象
		$xml_handler =  new Data_mapping_handler($GLOBALS["all"]["BASE"]."xml/color.xml");
		
		//方向数组(八个方向:东、东南、南、西南、西、西北、北、东北)
		$arr_direction =array("east","southeast","south","southwest","west","northwest","north","northeast");
		
		//数组长度
		$length = count($vehicle);

		for($row=0;$row<$length;$row++){   
			   //获取车图标路径
			   $vehicle[$row][4] = str_ireplace("west.png","",$xml_handler->getTextData("color","#".$vehicle[$row][4])); 
			   //获取当前车方向
			   $cur_direction = $vehicle[$row][3];
			   //求车方向
			   $vehicle[$row][3] = $arr_direction[intval($cur_direction/45)+((($cur_direction%45)-(45/2))>=0?1:0)]; 
		}
	    echo json_encode($vehicle); 
		break;
}

?>
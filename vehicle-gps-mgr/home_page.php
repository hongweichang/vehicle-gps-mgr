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
$act = $GLOBALS["all"]["operate"];
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$company_id = get_session("company_id"); //获取当前公司ID 
$arr['host']= "http://".$_SERVER ['HTTP_HOST']; //主机

if(!$sidx) $sidx =1;

$vehicle_console = new vehicle_console ();
switch($act)
{
	case "list":	//首页加载
		
		$common_setting =  new Setting ($company_id);
		
		$arr['url_manage'] = URL('user','user.php','manage_list'); //管理中心链接
		$arr['url_logout'] = URL('user','user.php','logout'); //退出链接
		
		$arr['page_refresh_time'] = "var page_refresh_time=". $common_setting->data["page_refresh_time"].";"; //页面刷新时间
		
		echo $db->display($arr,"list");
		break;
	case "vehicle_position": //车辆定位加载	
	 
		//获取当前公司所有车辆信息
		$vehicle = $vehicle_console->company_all_vehicle($company_id); 
		
		//导入数据映射文件解析类
		require_once("include/data_mapping_handler.php");
		 
		//创建XML解析对象
		$xml_handler =  new Data_mapping_handler($GLOBALS["all"]["BASE"]."xml/color.xml");
		$arr_vehicle = array();
		
		$length = count($vehicle);
		$index = 0;
		
		$ve_status = new Vehicle_status(); 
		
		foreach($vehicle as $value){
		  
			$arr_vehicle[$index]['number_plate']= $value['number_plate'];
			$arr_vehicle[$index]['gps_id']	= $value['gps_id'];
			$arr_vehicle[$index]['location_time']	= $value['location_time'];
			$arr_vehicle[$index]['cur_longitude']	= $value['cur_longitude'];
			$arr_vehicle[$index]['cur_latitude']	= $value['cur_latitude'];
			$arr_vehicle[$index]['cur_speed']	= $value['cur_speed'];
			$arr_vehicle[$index]['cur_direction']	= resolvingDirection($value['cur_direction']);
			$arr_vehicle[$index]['group_name']	= $value['group_name'];
			$arr_vehicle[$index]['driver_name']	= $value['driver_name'];
			$arr_vehicle[$index]['file_path']	= str_ireplace("west.png","",$xml_handler->getTextData("color","#".$value['color'])); //图片路径  
			$arr_vehicle[$index]['location_desc'] = $ve_status->get_location_desc($value['cur_longitude'],$value['cur_latitude']);
			
			$index++;
		  
		} 
		echo json_encode($arr_vehicle); 
		break;
}


?>
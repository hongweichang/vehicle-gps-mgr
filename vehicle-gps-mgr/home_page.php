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
		
		$page_refresh_time = $common_setting->data["page_refresh_time"];
		if(!isset($page_refresh_time)){
				$page_refresh_time = 30;
		}
		$arr['page_refresh_time'] = "var page_refresh_time=".$page_refresh_time.";"; //页面刷新时间
		
		echo $db->display($arr,"list");
		break;
		
		case "vehicle_position": //车辆定位加载	
	 
			//获取当前公司所有车辆信息
			$vehicle = $vehicle_console->company_all_vehicle($company_id); 
			
			//导入数据映射文件解析类
			require_once("include/data_mapping_handler.php");
			 
			//创建XML解析对象
			$xml_handler =  new Data_mapping_handler($GLOBALS["all"]["BASE"]."xml/color.xml");
			$arr_vehicle = array(); //车辆数据数组
			
			$index = 0; //下标索引
			
			$ve_status = new Vehicle_status(); 
			
			//获取车辆定位信息
			foreach($vehicle as $value){
				
				$lon = $ve_status->exact_lon($ve_status->around($value['cur_longitude'],0)); //经度
				$lat = $ve_status->exact_lat($ve_status->around($value['cur_latitude'],0));//纬度
				
				$arr_vehicle[$index]['id']= $value['id'];//车id
				$arr_vehicle[$index]['number_plate']= $value['number_plate'];//车牌号			
				$arr_vehicle[$index]['cur_longitude']	= $lon;
				$arr_vehicle[$index]['cur_latitude']	= $lat;			
				$arr_vehicle[$index]['cur_direction']	= resolvingDirection($value['cur_direction']); //方向 
								
				//图片路径
				if(!isset($value['color'])) 
					$arr_vehicle[$index]['file_path']	= "images/vehicle/gray"; //未设置、设置  默认车辆
				 else 
					$arr_vehicle[$index]['file_path']	= str_ireplace("/west.png","",$xml_handler->getTextData("color","#".$value['color'])); 	
					
				$index++; 
			} 
			echo json_encode($arr_vehicle); 
			break;
		
		case "vehicle_issue": //动态加载车辆定位信息
			$ve_status = new Vehicle_status(); 
			$vehicle_id = $_REQUEST['vehicle_id']; //获取车辆ID
			$vehicle = $vehicle_console->get_vehicle($vehicle_id); //根据ID查询车辆信息
			
			$lon = $ve_status->exact_lon($ve_status->around($vehicle[0]['cur_longitude'],0)); //经度
			$lat = $ve_status->exact_lat($ve_status->around($vehicle[0]['cur_latitude'],0));//纬度
			//$vehicle[0]['location_desc']=$ve_status->get_location_desc($lon/100000,$lat/100000); //地址
			$address = $ve_status->get_location_desc($lon/100000,$lat/100000); //地址
			if($address!=false){
				$vehicle[0]['location_desc'] = $address;
			}else{
				$vehicle[0]['location_desc'] = "经纬度信息错误";
			}
			
			if($vehicle[0]['group_name']==null){
				$vehicle[0]['group_name']="未设置";
			}
			
			echo json_encode(($vehicle[0]));
			break;
}


?>
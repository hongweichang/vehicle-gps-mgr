<?php
/** 
* 人员管理
* @copyright		秦运恒, 2010
* @author			郭英涛
* @create date		2010.07.31
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/
$act = $GLOBALS["all"]["operate"];

$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
$searchfil = $_REQUEST['searchField']; // get the direction
$searchstr = $_REQUEST['searchString']; // get the direction

if(!$sidx) $sidx =1;

switch($act)
{
	case "list":		//模拟测试
		//数据页面
		echo $db->display(null,$act);
		break;

	case "list_data":
		
		$encoding = mb_detect_encoding($_REQUEST['number_plate']);
		if($encoding=="UTF-8"){
			$number_plate = $_REQUEST['number_plate'];
		}else{
		    $number_plate = iconv("gb2312", "utf-8",$_REQUEST['number_plate']);	
		}
		$vehicle_status	= new Vehicle_status();
		if($number_plate==null or $number_plate==""){
		    $count = $vehicle_status->get_vehicle_count();
		}else{
			$count = $vehicle_status->get_vehicle_count_plate($number_plate);
		}

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;
		
		$company_id=get_session('company_id');

		//得到字段类型
		if(empty($searchfil) or empty($searchstr))
			$wh = 'where company_id='.$company_id;
		else
		{
			$type = $vehicle_status->get_type($searchfil);
			$searchstr = $db->prepare_value($searchstr,$type);
			$wh = "where ".$searchfil." = ".$searchstr." and company_id=".$company_id;
		}
		
		if($number_plate==null or $number_plate==""){
		//得到所有车辆
		    $result = $vehicle_status->get_all_vehicles($wh,$sidx,$sord,$start,$limit);
		}else{
			$result = $vehicle_status->get_all_vehicles_number($number_plate);
		}
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;

		foreach($result as	$key => $val)
		{ 
			//对指定字段进行翻译
			$vehicle = new Vehicle_status($val['id']);
			$cur_location = $vehicle->get_cur_location($val['cur_longitude'],$val['cur_latitude']);
			
			$driver = $vehicle->get_driver($val['driver_id']);
            
			$response->rows[$key]['id']=$val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['number_plate'],
												$vehicle->gps_status_boolean($val['gprs_status']),"定位时间",$val['cur_speed'],"限速",
												$driver[0]['name'],$vehicle->alert_status($val['alert_state']),
												"轨迹","统计信息","信息发布","定位",$cur_location);
		}

		//打印json格式的数据
		echo json_encode($response);
}
?>
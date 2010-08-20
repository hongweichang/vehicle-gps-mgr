<?php
/** 
* 车辆状态
* @copyright		秦运恒, 2010
* @author			郭英涛
* @create date		2010.07.31
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/
$act = $GLOBALS["all"]["operate"];

require_once ("include/data_mapping_handler.php");
$comm_setting_path = $all ["BASE"] . "xml/comm_setting.xml";

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
		$vehicle_status = new Vehicle_status();
		$lon_lat = $vehicle_status->get_lon_lat();
		foreach($lon_lat as $value){
			$str = $str."<input type='hidden' name='lonlat' title=".$vehicle_status->around($value[0])." value=".$vehicle_status->around($value[1])." />";
		}
		$arr['lon_lat'] = $str;
		echo $db->display($arr,"list");
		break;

	case "list_data":
		$vehicle_status	= new Vehicle_status();
		if($_REQUEST['number_plate']!=null or $_REQUEST['number_plate']!=""){
			$encoding = mb_detect_encoding($_REQUEST['number_plate']);
			if($encoding=="UTF-8"){
			 $number_plate = $_REQUEST['number_plate'];
			}else{
		   	 $number_plate = iconv("gb2312", "utf-8",$_REQUEST['number_plate']);	
			}
		}
		
		if($_REQUEST['lonMin']!=null or $_REQUEST['lonMin']!=""){
			$lonMin = $vehicle_status->e_around($_REQUEST['lonMin']);
			$lonMax = $vehicle_status->e_around($_REQUEST['lonMax']);
			$latMin = $vehicle_status->e_around($_REQUEST['latMin']);
			$latMax = $vehicle_status->e_around($_REQUEST['latMax']);
		}
		
		if(($number_plate!=null or $number_plate!="") and ($lonMin==null or $lonMin=="")){
			$count = $vehicle_status->get_vehicle_count_plate($number_plate);
		}else if(($lonMin!=null or $number_plate!="") and ($number_plate==null or $number_plate=="")){
		    $count = $vehicle_status->get_lon_lat_count($lonMin,$lonMax,
		             $latMin,$latMax);
		}
		else if(($lonMin==null or $number_plate=="") and ($number_plate==null or $number_plate=="")){
		    $count = $vehicle_status->get_vehicle_count();
		}else {
			$count = $vehicle_status->get_lon_lat_plate_count($lonMin,$lonMax,
		             $latMin,$latMax,$number_plate);
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
		
       if(($number_plate!=null or $number_plate!="") and ($lonMin==null or $lonMin=="")){
			$result = $vehicle_status->get_all_vehicles_number($number_plate);
		}else if(($lonMin!=null or $number_plate!="") and ($number_plate==null or $number_plate=="")){
		    $result = $vehicle_status->get_lon_lat_vehicle($lonMin,$lonMax,$latMin,$latMax);
		}
		else if(($lonMin==null or $number_plate=="") and ($number_plate==null or $number_plate=="")){
		   $result = $vehicle_status->get_all_vehicles($wh,$sidx,$sord,$start,$limit);
		}else {
		   $result = $vehicle_status->get_lon_lat_plate_vehicle($lonMin,$lonMax, 
		             $latMin,$latMax,$number_plate);
		}
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;

		$dataMapping = new Data_mapping_handler ( $comm_setting_path );//从xml文件中映射相应的数据库字段值
		
		foreach($result as	$key => $val)
		{ 
			//对指定字段进行翻译
			$vehicle = new Vehicle_status($val['id']);
			$cur_location = $vehicle->get_location_desc($val['cur_longitude'],$val['cur_latitude']);
			
			$driver = $vehicle->get_driver($val['driver_id']);
			$alert_state = $dataMapping->getMappingText ( "vehicle_manage", "alert_state", $val ['alert_state'] );
            
			$response->rows[$key]['id']=$val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['number_plate'],
												$vehicle->gps_status_boolean($val['gprs_status']),$val['location_time'],$cur_location,$val['cur_speed'],
												$driver[0]['name'],$alert_state,
												"<a href='#' title='查询历史轨迹' onclick='showOperationDialog(this,\"index.php?a=352&vehicle_id=".$val['id']."\")'>查看>></a>","统计>>",
												"<a href='#' title='发布信息' onclick='showOperationDialog(this,\"index.php?a=201&vehicle_id=".$val['id']."\")'>发布>></a>",
												"<a href='#' title='车辆定位' onclick='vehicle_position(".$val['id'].")' name=".$val['id'].">定位>></a>");
		}

		//打印json格式的数据
		echo json_encode($response);
		
		break;

	case "address":
		$vehicle_status	= new Vehicle_status();
		$location = $vehicle_status->get_location($_REQUEST['longitude'],$_REQUEST['latitude']);
		echo $location;
		
	case "gps_plate":
		$vehicle_status = new Vehicle_status($_REQUEST['vehicle_id']);
		$attr[0]=$vehicle_status->data['number_plate'];
		$attr[1]=$vehicle_status->data['gps_id'];
		echo json_encode($attr);
}
?>
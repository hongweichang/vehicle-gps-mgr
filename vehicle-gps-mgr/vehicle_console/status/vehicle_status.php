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
	case "list":  //模拟测试
		//数据页面
		$vehicle_status = new Vehicle_status();
		$lon_lat = $vehicle_status->get_lon_lat(); //获取本公司所有车辆的经纬度
		
		/*遍历经纬度传递经纬度到前台*/
		foreach($lon_lat as $value){
			$str = $str."<input type='hidden' name='lonlat' title=".$vehicle_status->around($value[0])." value=".$vehicle_status->around($value[1])." />";
		}
		$arr['lon_lat'] = $str;
		echo $db->display($arr,"list");//发送数据到页面
		break;

	case "list_data":  //查询车辆当前状态
		$vehicle_status	= new Vehicle_status();
		
		if($_REQUEST['number_plate']!=null or $_REQUEST['number_plate']!=""){
			$encoding = mb_detect_encoding($_REQUEST['number_plate']); //获取请求参数的编码方式
			if($encoding=="UTF-8"){
			 $number_plate = $_REQUEST['number_plate'];
			}else{
		   	 $number_plate = iconv("gb2312", "utf-8",$_REQUEST['number_plate']); //转换gb2312为utf-8编码	
			}
		}
		
		if($_REQUEST['lonMin']!=null or $_REQUEST['lonMin']!=""){
			/*转换经纬度为数据库中保存的值*/
			$lonMin = $vehicle_status->e_around($_REQUEST['lonMin']);
			$lonMax = $vehicle_status->e_around($_REQUEST['lonMax']);
			$latMin = $vehicle_status->e_around($_REQUEST['latMin']);
			$latMax = $vehicle_status->e_around($_REQUEST['latMax']);
		}
		
		/*获取车辆总数*/
		if(($number_plate!=null or $number_plate!="") and ($lonMin==null or $lonMin=="")){
			$count = $vehicle_status->get_vehicle_count_plate($number_plate);//根据车牌号模糊查询车辆总数
		}else if(($lonMin!=null or $number_plate!="") and ($number_plate==null or $number_plate=="")){
		    $count = $vehicle_status->get_lon_lat_count($lonMin,$lonMax,
		             $latMin,$latMax); //根据经纬度范围查询该范围内车辆总数
		}else if(($lonMin==null or $number_plate=="") and ($number_plate==null or $number_plate=="")){
		    $count = $vehicle_status->get_vehicle_count();//查询本公司所有车辆总数
		}else {
			$count = $vehicle_status->get_lon_lat_plate_count($lonMin,$lonMax,
		             $latMin,$latMax,$number_plate);//模糊查询指定经纬度范围内包含该车牌的车辆总数
		}

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;
		
		$company_id=get_session('company_id'); //获取公司ID

		//得到字段类型
		if(empty($searchfil) or empty($searchstr))
			$wh = 'where company_id='.$company_id;
		else
		{
			$type = $vehicle_status->get_type($searchfil);
			$searchstr = $db->prepare_value($searchstr,$type);
			$wh = "where ".$searchfil." = ".$searchstr." and company_id=".$company_id;
		}
		
		/*查询车辆*/
       if(($number_plate!=null or $number_plate!="") and ($lonMin==null or $lonMin=="")){
			$result = $vehicle_status->get_all_vehicles_number($number_plate);//根据车牌号模糊查询
		}else if(($lonMin!=null or $number_plate!="") and ($number_plate==null or $number_plate=="")){
		    $result = $vehicle_status->get_lon_lat_vehicle($lonMin,$lonMax,$latMin,$latMax);//根据经纬度范围查询
		}
		else if(($lonMin==null or $number_plate=="") and ($number_plate==null or $number_plate=="")){
		    $result = $vehicle_status->get_all_vehicles($wh,$sidx,$sord,$start,$limit);//查询本公司所有车辆
		}else {
		    $result = $vehicle_status->get_lon_lat_plate_vehicle($lonMin,$lonMax, 
		             $latMin,$latMax,$number_plate);//根据经纬度范围和车牌号查询
		}
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;
		
		$dataMapping = new Data_mapping_handler ( $comm_setting_path );//从xml文件中映射相应的数据库字段值
		
		/*遍历查询得到的车辆*/
		foreach($result as	$key => $val)
		{ 
			$vehicle_position_str = null; //当前车辆定位链接
			$cur_location = null; //当前位置
			
			//对指定字段进行翻译
			$vehicle = new Vehicle_status($val['id']);
			
			//获取驾驶员对象
			$driver = $vehicle->get_driver($val['driver_id']);
			//获取状态映射文字说明
			$alert_state = $dataMapping->getMappingText ( "vehicle_manage", "alert_state", $val ['alert_state'] );
            
			//gprs正常状态操作
			if($val['gprs_status'] == 1){
				$vehicle_position_str = "<a href='#' title='车辆定位' onclick='vehicle_position(".$val['id'].")' name=".$val['id'].">定位>></a>";
				$cur_location = $vehicle->get_location_desc($val['cur_longitude'],$val['cur_latitude']);//根据经纬度得到地址信息
			} 
			
			//设置显示列表
			$response->rows[$key]['id']=$val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['gps_id'],$val['number_plate'],
												$vehicle->gps_status_boolean($val['gprs_status']),$val['location_time'],$cur_location,$val['cur_speed'],
												$driver[0]['name'],$alert_state,
												"<a href='#' title='查询历史轨迹' onclick='showOperationDialog(this,\"index.php?a=352&vehicle_id=".$val['id']."\",\"inquire_modal_win\")'>查看>></a>",
												
												"<a href='#'  showWidth='850' showHeight='320' title='统计分析' onclick='showOperationDialog(this,\"index.php?a=402&vehicle_id=".$val['id']."\", \"inquire_modal_win\")'>统计>></a>",
												
												"<a href='#' title='发布信息' showHeight='350' showWidth='300' onclick='showOperationDialog(this,\"index.php?a=201&hidden=1&vehicle_ids=".$val['id']."\",\"inquire_modal_win\")'>发布>></a>",
												$vehicle_position_str);
		}

		//打印json格式的数据
		echo json_encode($response);
		
		break;

	case "address":
		$vehicle_status	= new Vehicle_status();
		$location = $vehicle_status->get_location($_REQUEST['longitude'],$_REQUEST['latitude']);//根据经纬度得到地址信息
		echo $location;
		
	case "gps_plate":
		$vehicle_status = new Vehicle_status($_REQUEST['vehicle_id']);//根据车辆ID查询出车辆具体信息
		
		/*将车牌号和gps编号放入数组*/
		$attr[0]=$vehicle_status->data['number_plate'];
		$attr[1]=$vehicle_status->data['gps_number'];
		
		//打印数据
		echo json_encode($attr);
}
?>
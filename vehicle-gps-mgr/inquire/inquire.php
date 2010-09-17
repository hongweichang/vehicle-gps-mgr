<?php
/** 
* 信息查询
* @copyright		秦运恒, 2010
* @author			郭英涛
* @create date		2010.08.02
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/

$act = $GLOBALS["all"]["operate"];
require_once ("include/data_mapping_handler.php");
$comm_setting_path = $all ["BASE"] . "xml/comm_setting.xml";

$page = $_REQUEST['page'];
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
$searchfil = $_REQUEST['searchField']; // get the direction
$searchstr = $_REQUEST['searchString']; // get the direction

switch($act)
{
	case "main":	//填写信息内容页面
		echo $GLOBALS['db']->display(null,$act);
		break;
	case "trace":
		$options = ""; //车辆下拉框
		$function_operate = ""; //功能操作
		$position_vehicle = ""; //定位车辆脚本 
		
		$id = $_REQUEST['vehicle_id']; //车辆ID
		$logic = $_REQUEST['logic']; //逻辑判断   1是查看信息 0是历史轨迹
		
		
		$inquire_info = new Inquire();
		$vehicle_list = $inquire_info->get_all_vehicles(); //查询所有车辆
		
		$inherit_str = "";
		$inherit = $_REQUEST['inherit']; //在iframe调用时，是否从父页面继承设定值
		if("1" == $inherit){
			$inherit_str = "<input id='inherit' name='inherit' type='hidden' value='1'/>";
		}
		
		//填充车辆列表
		foreach($vehicle_list as $value){
			if($id && $value["id"] == $id)
				$options = $options."<option name='have' value=".$value["id"]." selected>".$value["number_plate"]."</option>";
			else
				$options = $options."<option value=".$value["id"].">".$value["number_plate"]."</option>";
		}
			
		if($logic == 1){  //查看信息状态下
			$function_operate="<select id='select_mode'>".
									"<option value='vehicle_mode'>选择车辆</option>".
									"<option value='area_mode'>选择区域</option>".
							  "</select>";	
		}else if($logic == 0){ //首页地图查看历史轨迹状态下
			$function_operate ="<div style='margin-top:5px'>选择车辆: </div>";
			$position_vehicle  =  "history_track_frame.vehiclePosition(".$id.");";
		}
		
		$have_header = $_REQUEST['have_header'];
		$header_str = "";
		$foot_str = "";
		if("1" == $have_header){
			$header_str = 
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
			.'<html xmlns="http://www.w3.org/1999/xhtml">'
			.'<head>'
			.'<meta http-equiv="content-type" content="text/html; charset=utf-8"/>'
			.'<link type="text/css" href="css/style.css" rel="stylesheet" />'
			.'<link type="text/css" href="css/cupertino/jquery-ui-1.8.4.custom.css" rel="stylesheet" />'
			.'<link type="text/css" href="css/jquery.loadmask.small.css"  media="screen" rel="stylesheet" />'
			.'<script language="javascript" src="js/jquery-1.4.2.js" ></script>'
			.'<script language="javascript" src="js/jquery-ui-1.8.1.custom.min.js" ></script>'
			.'<script language="javascript" src="js/jquery.loadmask.min.js" ></script>'
			.'<script type="text/javascript" src="js/jquery-ui-timepicker-addon-0.5.js"></script>'
			//.'<script language="javascript" src="js/jquery.selectboxes.min.js" ></script>'
			.'</head><body style="BACKGROUND-COLOR: transparent">';
			$foot_str ='</body></html>';
		}
		
		$data['HEADER'] = $header_str;
		$data['FOOTER'] = $foot_str;
		$data['INHERIT'] = $inherit_str;
		$data['FUNCTION_OPERATE'] = $function_operate;
		$data["VEHICLE_LIST"] = $options;
		$data['POSITION_VEHICLE']=$position_vehicle;
		$data['HOST']= "http://".$_SERVER ['HTTP_HOST']; //主机
		
		echo $GLOBALS['db']->display($data,$act);
		break;
	case "get_trace_data":
		require_once 'traceInfo.php';
		require_once 'color_mapper.php';
		
		$id = $_REQUEST['vehicle_id']; //车辆ID
		$company_id = get_session("company_id"); //获取当前公司ID  
		$time = $_REQUEST['time'];
		
		$gps_info_path = $server_path_config["gps_info_path"]."/".$time.".log";
		//$gps_info_path = $GLOBALS["all"]["BASE"]."/log/".$time.".log";
		if(!file_exists($gps_info_path)){
			echo json_encode(0);
			break;
		}
			
		$parser = new Position_parser($company_id,$gps_info_path,$id,$time);
		$datalist = $parser->getDataList();
		
		$point_info = array();
		$trace_info = array();
		
		$ve_status = new Vehicle_status(); 
		
		foreach($datalist as $k=>$v)
		{
			$long = $ve_status->around($v->longitude,0);
			$lat = $ve_status->around($v->latitude,0);
			$ve_status->exact_lon_lat($long, $lat);
			
			$point_info[0]= $long; //经度
			$point_info[1]= $lat;  //纬度
			$point_info[2]= resolvingDirection($v->direction); //方向 
			$point_info[3]= $v->speed; //速度
			//$point_info[4]= $v->location_desc; //地址
			$point_info[4]= $v->color; //颜色
			$point_info[5]= $v->img_path; //图片路径
			$point_info[6]= $v->location_time; //定位时间
			
			//速度大于1 列入队列中
			if($point_info[3]>=1){
				array_push($trace_info,$point_info);
			}	
		}
		
		echo json_encode(array_reverse($trace_info));
		break;
		
	case "get_history_info": //查询历史发布信息
		$inquire = new Inquire();
		
		$count = $inquire->get_history_info_count($_REQUEST['begin_date'],$_REQUEST['end_date']); //获取历史信息总数
		if( $count >0 ) {
			$total_pages = ceil($count/$limit); //获取总页数
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;
		
		if(empty($searchfil) or empty($searchstr))
			$wh = "";
		else
		{
			$type = $inquire->get_type($searchfil);
			$searchstr = $db->prepare_value($searchstr,$type);
			$wh = "where ".$searchfil." = ".$searchstr;
		}

		//查询指定时间内的历史 发布信息
		$infoes = $inquire->get_history_info($wh,$sidx,$sord,$start,$limit,$_REQUEST['begin_date'],$_REQUEST['end_date']); 
		
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;
		
		$dataMapping = new Data_mapping_handler ( $comm_setting_path );//从xml文件中映射相应的数据库字段值
		
		//遍历历史发布信息
		foreach($infoes as	$key => $val)
		{ 
			
			$info_type= $dataMapping->getMappingText ( "info_issue", "type", $val ['type'] ); //从XML中获取信息类型
			$response->rows[$key]['id']=$val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['login_name'],
												$info_type,$val['issue_time'],$val['begin_time'],$val['end_time'],
												$val['content']);
		}

		//打印json格式的数据
		echo json_encode($response);
		
		break;
		
	case "get_area_history": 
		require_once 'areaInfo.php';
		$areaInfo = new AreaInfo();
		
		$position1 = new Position($_REQUEST["lonMin"], $_REQUEST["latMin"]);
		$position2 = new Position($_REQUEST["lonMax"], $_REQUEST["latMax"]);
		
		array_push($areaInfo->positionList, $position1);
		array_push($areaInfo->positionList, $position2);
		
		$vehile_list = explode(",", $_REQUEST["vehicle_list"]);//将字符串转换成数组，以","为分割符
		$hour = $_REQUEST["hour"];
		
		//$gps_info_path = $server_path_config["gps_info_path"]."/".$hour.".log";
		$gps_info_path = $GLOBALS["all"]["BASE"]."/log/".$hour.".log";
		
		$inquire = new Inquire();
		$vehicle_in_area = $inquire->check_in_area($vehile_list, $areaInfo, $hour, $gps_info_path);
		echo json_encode($vehicle_in_area);
		break;
		
	case "show_area_history":  //区域查询历史轨迹时显示该区域内的车辆
		$inquire = new Inquire();
		
		if($_REQUEST['id_list']==""){
			$id_list = null;
		}else{		
			$id_list = explode(",",$_REQUEST['id_list']); //获取车辆ID集合
		}
		$begin_time = $_REQUEST['begin_time']; //获取开始时间
		$end_time = $_REQUEST['end_time']; //获取结束时间
		
		
		$count = count($id_list); //获取车辆总数
		
		/*获取总页数*/
		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;
		
		/*查询出所有车辆的信息保存在数组中*/
		for($i = $start;$i<$count;$i++){
			if($i >= ($start + $limit)){
				break;
			}
			$infoes[$i-$start] = $inquire->get_vehicle($id_list[$i]);
		}
	
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;
		
		/*遍历所有车辆*/
		foreach($infoes as	$key => $val)
		{ 
			$driver = $inquire->get_driver($val['driver_id']); //查询驾驶员信息
			$response->rows[$key]['id']=$val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['number_plate'],$driver[0]['name'],
												"<a href='#' onclick='show_trace_area(".$val['id'].")'>查看历史轨迹</a>");
		}

		//打印json格式的数据
		echo json_encode($response);
		break;
		
	break;
}
?>
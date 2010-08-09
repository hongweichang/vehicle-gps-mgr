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

switch($act)
{
	case "main":	//填写信息内容页面
		echo $GLOBALS['db']->display(null,$act);
		break;
	case "trace":
		$inquire_info = new Inquire();
		$vehicle_list = $inquire_info->get_all_vehicles();
		
		$options = "";
		
		foreach($vehicle_list as $value)
		{
			$options = $options."<option value=".$value["id"].">".$value["number_plate"]."</option>";
		}
		
		$data["VEHICLE_LIST"] = $options;
		
		echo $GLOBALS['db']->display($data,$act);
		break;
	case "get_trace_data":
		require_once 'traceInfo.php';
		require_once 'color_mapper.php';
		
		$id = $_REQUEST['id'];
		$company_id = $_REQUEST['companyId'];
		$startTime = $_REQUEST['startTime'];
		$endTime = $_REQUEST['endTime'];
		$time = $_REQUEST['time'];
		
		$gps_info_path = $server_path_config["gps_info_path"].$time.".log";
	
		$parser = new Position_parser($company_id,$gps_info_path,$id);
		//$parser = new Position_parser("1","tracedata/2010080312.log","3"); //测试数据
		$datalist = $parser->getDataList();
		
		$point_info = array();
		$trace_info = array();
		
		foreach($datalist as $k=>$v)
		{
			$point_info[0]= $v->longitude;
			$point_info[1]= $v->latitude;
			$point_info[2]= resolvingDirection($v->direction); 
			$point_info[3]= $v->speed;

			array_push($trace_info,$point_info);
		}
		
		echo json_encode($trace_info);
		break;
		
	break;
}
?>
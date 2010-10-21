<?php
/** 
* 添加GPS设备
* @copyright		gps, 2010
* @author			赵将伟
* @create date		2010.10.19
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

$par = $_REQUEST["par"];
$child = $_REQUEST["child"];

$identify_id = get_session('identify_id');
$user_id = get_session("user_id");

if(!$sidx) $sidx =1;

switch($act)
{
	case "list": //添加GPS主页面
		echo $db->display(null,"list");
		break;
		
	case "add_gps"://添加GPS设备号
		$add_gps = new add_gps();
		
		$gps_number = $_REQUEST['gps_number'];
		$gps['gps_number'] =  $db->prepare_value($gps_number,"VARCHAR");	
		$gps['company_id'] =  $db->prepare_value(get_session("company_id"),"INT");	
		$gps['user_id'] =  $db->prepare_value(get_session("user_id"),"INT");	
		$gps['state'] = $db->prepare_value(0,"TINYINT");
		
		if($add_gps->add_gps_number($gps)){
			echo "add success";
		}else{
			echo "add fail";
		}
		break;
}


?>
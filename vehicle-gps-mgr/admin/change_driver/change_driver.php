<?php
/** 
* 更换驾驶员
* @copyright		driver, 2010
* @author			赵将伟
* @create date		2010.10.14
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
		$vehicle_id = $_REQUEST['vehicle_id'];
		require_once("admin/vehicle/vehicle.class.php");
		$vehicle = new vehicle();
		$drivers = $vehicle->get_select_driver("driver_manage","name",$vehicle_id);
		//$arr['drivers'] = $drivers;
		//echo $GLOBALS['db']->display($arr,"list");
		
		echo json_encode($drivers);
		break;
		
}
?>
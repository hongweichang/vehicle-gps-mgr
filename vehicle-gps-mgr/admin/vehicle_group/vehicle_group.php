<?php
/** 
* 车辆处理
* @copyright		vehicle_group, 2010
* @author			李少杰
* @create date		2010.07.24
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

if(!$sidx) $sidx =1;

switch($act)
{
	case "list":			//加载车辆管理的html页面
		echo $db->display(null,"list");
		break;
	case "list_data":		//车辆组管理html中，js文件会加载这个case，取得并输出数据
		$user	= new Vehicle_group();
		$count = $user->get_vehicle_group_count();

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;

		//得到字段类型
		if(empty($searchfil) or empty($searchstr))
			$wh = '';
		else
		{
			$type = $user->get_type($searchfil);
			switch($searchfil)
			{
			}
			$searchstr = $db->prepare_value($searchstr,$type);
			if($type == 'INT')
			{
				$wh = "where ".$searchfil." = ".$searchstr;
			}
			else
			{
				$searchstr = str_replace("'","",$searchstr);
				$wh = "where ".$searchfil." like '%".$searchstr."%'";
			}
		}
		//得到所有车辆组
		$result = $user->get_all_vehicle_groups($wh,$sidx,$sord,$start,$limit);
		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		foreach($result as	$key => $val)
		{
			//对指定字段进行翻译
			$vehicle2	= new Vehicle_group($val['id']);
			$company_name = $vehicle2->get_data("company_name");
			$responce->rows[$key]['id']=$val['id'];
			$responce->rows[$key]['cell']=array($val['id'],$val['name'],$company_name,
																					$val['description'],
																					//$val['create_id'],$val['create_time'],
																					//$val['update_id'],$val['update_time']
																					);
		}

		//打印json格式的数据
		echo json_encode($responce);
		break;
	case "operate":		//车辆组修改、添加、删除
		$oper = $_REQUEST['oper'];
		$arr["name"] = $db->prepare_value($_REQUEST['name'],"VARCHAR");
		$arr["company_id"] = $db->prepare_value($_REQUEST['company_id'],"INT");
		$arr["description"] = $db->prepare_value($_REQUEST['description'],"VARCHAR");
//		$arr["create_id"] = $db->prepare_value($_REQUEST['create_id'],"INT");
//		$arr["create_time"] = $db->prepare_value($_REQUEST['create_time'],"DATETIME");
//		$arr["update_id"] = $db->prepare_value($_REQUEST['update_id'],"INT");
//		$arr["update_time"] = $db->prepare_value($_REQUEST['update_time'],"DATETIME");
		$group = new Vehicle_group($_REQUEST['id']);
		switch($oper)
		{
			case "add":		//增加
				$group->add_vehicle_group($arr);
				break;
			case "edit":		//修改
				$group->edit_vehicle_group($arr);
				break;
			case "del":		//删除
				$group->del_vehicle_group($arr);
				break;
		}
		break;
	case "select":		//下拉列表
		$p = $_REQUEST["p"];		//获得需要翻译的字段
		$vehicle = new Vehicle_group();
		switch($p)
		{
			case "company_id":
				$html = $vehicle->get_select("company","name");
				break;
//			case "driver_id":
//				$html = $vehicle->get_select("driver_manage","name");
//				break;
//			case "type_id":
//				$html = $vehicle->get_select("vehicle_type_manage","name");
//				break;
//			case "alert_state":
//				if(!$par or !$child)
//				{
//					$par = "vehicle_manage";
//					$child = "alert_state";
//				}
//				$xml = new Xml($par,$child);
//				$html = $xml->get_html_xml();
				break;
		}
		echo $html;
		break;
}


?>
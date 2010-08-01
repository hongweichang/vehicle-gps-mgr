<?php
/** 
* 车辆类型处理
* @copyright		vehicle, 2010
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
	case "list_data":		//车辆管理html中，js文件会加载这个case，取得并输出数据
		$vehicle	= new Vehicle_type();
		$count = $vehicle->get_vehicle_type_count();

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;

		//得到查询条件
		if(empty($searchfil) or $searchstr == '')
			$wh = 'where 1=1 ';
		else
		{
			$type = $vehicle->get_type($searchfil);
			$wh = "where 1=1 ";
			//翻译serchstr
			switch($searchfil)
			{
			}
			$searchstr = $db->prepare_value($searchstr,$type);
			if($type == 'INT')	//----用=号
			{
				$wh .= "and ".$searchfil." = ".$searchstr;
			}
			else if($type == 'RAW')	//----用in
			{
				$wh .= $searchstr;
			}
			else	//----用like
			{
				$searchstr = str_replace("'","",$searchstr);
				$wh .= "and ".$searchfil." like '%".$searchstr."%'";
			}
		}
		//file_put_contents("a.txt",$wh);
		//得到所有车辆
		$result = $vehicle->get_all_vehicle_types($wh,$sidx,$sord,$start,$limit);
		//file_put_contents("a.txt",$db->sql);
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;

		foreach($result as	$key => $val)
		{
			$response->rows[$key]['id']=$val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['company_id'],$val['name'],
																					$val['fuel_consumption'],$val['load_capacity'],
																					$val['description']
																					);
		}
		//打印json格式的数据
		echo json_encode($response);
		break;
		
	case "operate":		//车辆修改、添加、删除
		$oper = $_REQUEST['oper'];
		//file_put_contents("a.txt",implode(',',array_keys($_REQUEST)).'--'.implode(',',$_REQUEST));exit;
		$arr["company_id"] = $db->prepare_value(get_session("company_id"),"VARCHAR");
		$arr["name"] = $db->prepare_value($_REQUEST['name'],"VARCHAR");
		$arr["fuel_consumption"] = $db->prepare_value($_REQUEST['fuel_consumption'],"INT");
		$arr["load_capacity"] = $db->prepare_value($_REQUEST['load_capacity'],"INT");
		$arr["description"] = $db->prepare_value($_REQUEST['description'],"VARCHAR");
//		$arr["create_id"] = $db->prepare_value($_REQUEST['create_id'],"INT");
//		$arr["create_time"] = $db->prepare_value($_REQUEST['create_time'],"DATETIME");
//		$arr["update_id"] = $db->prepare_value($_REQUEST['update_id'],"INT");
//		$arr["update_time"] = $db->prepare_value($_REQUEST['update_time'],"DATETIME");
		$vehicle = new Vehicle_type($_REQUEST['id']);//file_put_contents("a.txt",implode(",",array_keys($_REQUEST)));
		switch($oper)
		{
			case "add":		//增加
				$vehicle->add_vehicle_type($arr);
				break;
			case "edit":		//修改
				$vehicle->edit_vehicle_type($arr);//file_put_contents("a.txt",$db->sql);
				break;
			case "del":		//删除
				$vehicle->del_vehicle_type($arr);
				break;
		}
		break;
//	case "select":		//下拉列表
//		$p = $_REQUEST["p"];		//获得需要翻译的字段
//		$vehicle = new Vehicle_type();
//		switch($p)
//		{
//		}
//		echo $html;
//		break;
}


?>
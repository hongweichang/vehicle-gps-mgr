<?php
/** 
* 系统设置
* @copyright		system_set, 2010
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
	case "list_data":		//
		$set	= new System_set();
		$count = $set->get_system_set_count();
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
			$type = $set->get_type($searchfil);
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
		
		$result = $set->get_all_system_sets($wh,$sidx,$sord,$start,$limit);
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;

		foreach($result as	$key => $val)
		{
			//对指定字段进行翻译
			$response->rows[$key]['id']=$val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['company_id'],
																					$val['page_refresh_time'],$val['default_color'],
																					$val['speed_limit'],$val['fatigue_remind_time']
																					//$val['backup1'],$val['backup2'],
																					//$val['backup3'],$val['backup4'],$val['create_id'],
																					//$val['create_time'],$val['update_id'],$val['update_time']
																					);
		}

		//打印json格式的数据
		echo json_encode($response);
		break;
		
	case "operate":		//车辆修改、添加、删除
		$oper = $_REQUEST['oper'];
		//file_put_contents("a.txt",implode(',',array_keys($_REQUEST)).'--'.implode(',',$_REQUEST));exit;
		$arr["company_id"] = $db->prepare_value(get_session("company_id"),"INT");
		$arr["page_refresh_time"] = $db->prepare_value($_REQUEST['page_refresh_time'],"INT");
		$arr["default_color"] = $db->prepare_value($_REQUEST['default_color'],"VARCHAR");
		$arr["speed_limit"] = $db->prepare_value($_REQUEST['speed_limit'],"INT");
		$arr["fatigue_remind_time"] = $db->prepare_value($_REQUEST['fatigue_remind_time'],"INT");
//		$arr["backup1"] = $db->prepare_value($_REQUEST['backup1'],"VARCHAR");
//		$arr["backup2"] = $db->prepare_value($_REQUEST['backup2'],"VARCHAR");
//		$arr["backup3"] = $db->prepare_value($_REQUEST['backup3'],"VARCHAR");
//		$arr["backup4"] = $db->prepare_value($_REQUEST['backup4'],"VARCHAR");
//		$arr["create_id"] = $db->prepare_value($_REQUEST['create_id'],"INT");
//		$arr["create_time"] = $db->prepare_value($_REQUEST['create_time'],"DATETIME");
//		$arr["update_id"] = $db->prepare_value($_REQUEST['update_id'],"INT");
//		$arr["update_time"] = $db->prepare_value($_REQUEST['update_time'],"DATETIME");
		$set = new System_set($_REQUEST['id']);
		switch($oper)
		{
			case "add":		//增加
				$set->add_system_set($arr);
				break;
			case "edit":		//修改
				$set->edit_system_set($arr);
				break;
			case "del":		//删除
				$set->del_system_set($arr);
				break;
		}
		break;
//	case "select":		//下拉列表
//		$p = $_REQUEST["p"];		//获得需要翻译的字段
//		$set = new System_set();
//		switch($p)
//		{
//		}
//		echo $html;
//		break;
}


?>
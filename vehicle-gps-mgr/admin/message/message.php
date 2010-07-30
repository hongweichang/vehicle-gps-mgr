<?php
/** 
* 信息管理
* @copyright		company, 2010
* @author			苏元元
* @create date		2010.07.26
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
		include("include/templates.php");
		echo $GLOBALS['db']->display(null,$act);
		break;

	case "list_data":

			$message	= new Message();	//模拟打印润色后的字符串值
			$count = $message->get_all_count();

			if( $count >0 ) {
				$total_pages = ceil($count/$limit);
			} else {
				$total_pages = 0;
			}
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit;
			if ($start<0) $start = 0;

			//翻译代码
			//是否是区域信息
			$xml_is_area_info  = new Xml("info_issue","is_area_info");
			$is_area_info = $xml_is_area_info->get_array_xml();

			//信息类型
			$xml_type  = new Xml("info_issue","type");
			$type = $xml_type->get_array_xml();

			//得到字段类型
			if(empty($searchfil) or empty($searchstr))
				$wh = '';
			else
			{
//				$type = $driver->get_type($searchfil);
//				$searchstr = $db->prepare_value($searchstr,$type);

				if($searchfil == "is_area_info")
				{
					$is_area_info_wh = array_flip($is_area_info);
					$searchstr = $is_area_info_wh[$searchstr];
				}

				if($searchfil == "type")
				{
					$type_wh = array_flip($type);
					$searchstr = $type_wh[$searchstr];
				}

				$wh = "where ".$searchfil." LIKE '%".$searchstr."%'";
			}

			$rtn = $message->get_all_messages($wh,$sidx,$sord,$start,$limit);

			$responce->page	= $page;
			$responce->total = $total_pages;
			$responce->records = $count;

			foreach($rtn as	$key=>$rtn_message)
			{
				$responce->rows[$key]['id']=$rtn_message['id'];
				$responce->rows[$key]['cell']=array($is_area_info[$rtn_message['is_area_info']],$rtn_message['issuer_id'],$type[$rtn_message['type']],$rtn_message['issue_time'],$rtn_message['begin_time'],$rtn_message['end_time'],$rtn_message['title'],$rtn_message['content']);
			}

			//打印json格式的数据
			echo json_encode($responce);

		break;

	//查询接受到信息的人员
	case "re_driver":

		//取得信息ID
		$id = $_REQUEST["id"];

		$message	= new Message();	//模拟打印润色后的字符串值
		$count = $message->get_all_receiver_count($id);

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;

		//翻译代码
		//信息类型
		$xml_receiver_type  = new Xml("info_receive_driver","receiver_type");
		$receiver_type = $xml_receiver_type->get_array_xml();

		$rtn = $message->get_all_receivers($id);

		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		foreach($rtn as	$key=>$rtn_message)
		{
			$responce->rows[$key]['id']=$rtn_message['id'];
			$responce->rows[$key]['cell']=array($rtn_message['id'],$rtn_message['info_id'],$receiver_type[$rtn_message['receiver_type']],$rtn_message['receiver_id']);
		}

		//打印json格式的数据
		echo json_encode($responce);
//		file_put_contents("d:\a.txt",$GLOBALS['db']->sql);
		break;

	// 查询影响区域
	case "re_area":

//取得信息ID
		$id = $_REQUEST["id"];

		$message	= new Message();	//模拟打印润色后的字符串值
		$count = $message->get_all_area_count($id);

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;

		//翻译代码
		//信息类型
		$xml_type  = new Xml("area_info","type");
		$type = $xml_type->get_array_xml();

		$rtn = $message->get_all_areas($id);

		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		foreach($rtn as	$key=>$rtn_message)
		{
			$responce->rows[$key]['id']=$rtn_message['id'];
			$responce->rows[$key]['cell']=array($rtn_message['id'],$rtn_message['info_id'],$type[$rtn_message['type']],$rtn_message['log'],$rtn_message['lat'],$rtn_message['radius'],$rtn_message['next_id']);
		}

		//打印json格式的数据
		echo json_encode($responce);
		break;
}
?>
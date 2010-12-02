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
		
	case "list_gps"://添加GPS设备号
		$add_gps = new add_gps();
		
		/*$gps_number = $_REQUEST['gps_number'];
		$gps['gps_number'] =  $db->prepare_value($gps_number,"VARCHAR");	
		$gps['company_id'] =  $db->prepare_value(get_session("company_id"),"INT");	
		$gps['user_id'] =  $db->prepare_value(get_session("user_id"),"INT");	
		$gps['state'] = $db->prepare_value(0,"TINYINT");
		
		if($add_gps->add_gps_number($gps)){
			echo "add success";
		}else{
			echo "add fail";
		}*/
		
		$count = $add_gps->get_count_gps();//查询登录公司所有GPS设备总数

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
			$wh = 'where 1=1 ';
		else
		{
			$type = $add_gps->get_type($searchfil);
			$wh = "where 1=1 ";
			switch($searchfil)
			{
			}
			$searchstr = $db->prepare_value($searchstr,$type);
			if($type == 'INT')
			{
				$wh .= "and ".$searchfil." = ".$searchstr;
			}
			else
			{
				$searchstr = str_replace("'","",$searchstr);
				$wh .= "and ".$searchfil." like '%".$searchstr."%'";
			}
		}
		//得到所有车辆组
		$result = $add_gps->get_all_gps($wh,$sidx,$sord,$start,$limit);
		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		foreach($result as	$key => $val)
		{
			//对指定字段进行翻译
			$gps = new add_gps($val['id']);
			
			//翻译GPS状态字段
			if($val['state']==0){
				$is_use = "未使用";
			}else{
				$is_use = "使用中";
			}
			$responce->rows[$key]['id']=$val['id'];
			$responce->rows[$key]['cell']=array($val['id'],$val['gps_number'],$is_use);
		}

		//打印json格式的数据
		echo json_encode($responce);
		break;
		
	case "operator":
		$add_gps = new add_gps();
		$oper = $_REQUEST['oper'];
		
		$arr["gps_number"] = $db->prepare_value($_REQUEST['gps_number'],"VARCHAR");
		$arr['company_id'] =  $db->prepare_value(get_session("company_id"),"INT");			
		
		switch($oper)
		{
			case "add":		//增加
				if(strlen($_REQUEST['gps_number']."")!=11 || !is_numeric($_REQUEST['gps_number']."")){
					exit(json_encode(array('success'=>false,'errors'=>'gps必须为11位数字,请重新输入!')));
				}//校验GPS号格式与长度
				
				$arr['user_id'] =  $db->prepare_value(get_session("user_id"),"INT");	
				$arr["state"] = $db->prepare_value(0,"TINYINT");
				$add_gps->add_gps_number($arr);
				echo json_encode(array('success'=>true,'errors'=>'添加成功!'));
				break;
			case "edit":	//修改
				if(strlen($_REQUEST['gps_number']."")!=11 || !is_numeric($_REQUEST['gps_number']."")){
					exit(json_encode(array('success'=>false,'errors'=>'gps必须为11位数字,请重新输入!')));
				}//校验GPS号格式与长度
				
				$add_gps->edit_gps($_REQUEST['gps_number'],$_REQUEST['id']);
				echo json_encode(array('success'=>true,'errors'=>'修改成功!'));
				break;
			case "del":		//删除
				$add_gps->delete_gps($_REQUEST['id']);
				echo json_encode(array('success'=>true,'errors'=>'删除成功!'));
				break;
		}
		break;
		
	break;
}


?>
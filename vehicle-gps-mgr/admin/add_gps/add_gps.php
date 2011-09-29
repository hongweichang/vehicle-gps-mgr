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
$add_gps = new add_gps();

if(!$sidx) $sidx =1;

switch($act)
{
	case "list": //GPS主页面
		echo $db->display(null,$act);
		break;
		
	case "child": //子业务员管辖GPS主页面
		$explorer_id = get_session("user_id");
		$comp = new Company();
		$child_ids = $comp->get_child_ids($explorer_id);//获取子业务员ID
		$rtn = $comp->get_all_companys($explorer_id);
		while($child_ids!=false && count($child_ids) > 0){
			$rtn = array_merge($rtn,$comp->get_all_companys($child_ids));
			$child_ids = $comp->get_child_ids($child_ids);
		}
		$arr['company_list'] = "<option id='all'>全部</option>";
		//将所有公司传到前台页面下拉列表显示
		foreach($rtn as $key=>$value){
			$arr['company_list'] = $arr['company_list']."<option id=".$value['id'].">".$value['name']."</option>";
		}
		
		echo $db->display($arr,$act);
		break;
		
	case "company": //具体公司GPS主页面
		echo $db->display(null,$act);
		break;
		
	case "list_gps"://显示GPS设备号列表
		$action = $_REQUEST['action'];

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
		
		switch ($action){
			case "company":
				$company_id = get_session("company_id");
				$count = $add_gps->get_count_gps($company_id);//查询登录公司所有GPS设备总数
				
				if( $count >0 ) {
					$total_pages = ceil($count/$limit);
				} else {
					$total_pages = 0;
				}
				if ($page > $total_pages) $page=$total_pages;
				$start = $limit*$page - $limit;
				if ($start<0) $start = 0;
				
				$result = $add_gps->get_all_gps($company_id,$wh,$sidx,$sord,$start,$limit);
				break;
			case "child":
				if($_REQUEST['company_id'] == "all"){											
					$count = $add_gps->get_offer_gps_count($user_id);
					
					if( $count >0 ) {
						$total_pages = ceil($count/$limit);
					} else {
						$total_pages = 0;
					}
					if ($page > $total_pages) $page=$total_pages;
					$start = $limit*$page - $limit;
					if ($start<0) $start = 0;
					
					$result = $add_gps->get_offer_gps($user_id,$wh,$sidx,$sord,$start,$limit);
				}else{
					$company_id = $_REQUEST['company_id'];
					$count = $add_gps->get_count_gps($company_id);//查询登录公司所有GPS设备总数
					
					if( $count >0 ) {
						$total_pages = ceil($count/$limit);
					} else {
						$total_pages = 0;
					}
					if ($page > $total_pages) $page=$total_pages;
					$start = $limit*$page - $limit;
					if ($start<0) $start = 0;
					
					$result = $add_gps->get_all_gps($company_id,$wh,$sidx,$sord,$start,$limit);
				}
				break;
			case "direct":
				$count = $add_gps->get_offer_gps_count($user_id);
				
				if( $count >0 ) {
						$total_pages = ceil($count/$limit);
				} else {
					$total_pages = 0;
				}
				if ($page > $total_pages) $page=$total_pages;
				$start = $limit*$page - $limit;
				if ($start<0) $start = 0;
				
				$result = $add_gps->get_offer_gps($user_id,$wh,$sidx,$sord,$start,$limit);
				break;
		}
		
		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		foreach($result as	$key => $val)
		{
			//翻译GPS状态字段
			if($val['state']==0){
				$is_use = "未使用";
			}else{
				$is_use = "使用中";
			}
			$responce->rows[$key]['id']=$val['id'];
			if("direct" == $action){
				$responce->rows[$key]['cell']=array($val['id'],$val['gps_number'],$is_use,$val['company_name']);
			}else{
				$responce->rows[$key]['cell']=array($val['id'],$val['gps_number'],$is_use,$val['company_id']);	
			}
		}
		//打印json格式的数据
		echo json_encode($responce);
		break;
		
	case "operator":
		$oper = $_REQUEST['oper'];
		if($_REQUEST['company_id'] == false || $_REQUEST['company_id'] == "undefined"){
			$company_id = get_session("company_id");
		}else{
			$company_id = $_REQUEST['company_id'];
			set_session("gps_company",$company_id);
		}
		
		$arr["gps_number"] = $db->prepare_value($_REQUEST['gps_number'],"VARCHAR");
		$arr['company_id'] =  $db->prepare_value($company_id,"INT");			
		
		switch($oper)
		{
			case "add":		//增加
				if(!is_numeric($_REQUEST['gps_number'].""))
				{
					//校验GPS号格式与长度
					exit(json_encode(array('success'=>false,'errors'=>'gps必须为数字,请重新输入!')));
				}else if($add_gps->get_gps_number_count($arr["gps_number"])>0)
				{
					exit(json_encode(array('success'=>false,'errors'=>'该gps设备号已经存在，请使用其它设备号')));
				}
				
				$arr['user_id'] =  $db->prepare_value(get_session("user_id"),"INT");	
				$arr["state"] = $db->prepare_value(0,"TINYINT");
				$add_gps->add_gps_number($arr);
				echo json_encode(array('success'=>true,'errors'=>'添加成功!','company_id'=>$company_id));
				break;
			case "edit":	//修改
				if(!is_numeric($_REQUEST['gps_number']."")){
					exit(json_encode(array('success'=>false,'errors'=>'gps必须为数字,请重新输入!')));
				}//校验GPS号格式与长度
				
				$add_gps->edit_gps($_REQUEST['gps_number'],$_REQUEST['id']);
				echo json_encode(array('success'=>true,'errors'=>'修改成功!'));
				break;
			case "del":		//删除
				$add_gps->remove_gps($_REQUEST['id']);
				$add_gps->delete_gps($_REQUEST['id']);
				echo json_encode(array('success'=>true,'errors'=>'删除成功!'));
				break;
		}
		break;
		
	case "select": //下拉列表	
		$oper = $_REQUEST['p'];
		switch ($oper){
			case "company_name"://公司名下拉列表
				$companies = $add_gps->get_companies($user_id);
				$result = "<select id='company_names' name=".get_session('gps_company').">";
				foreach($companies as $key=>$value){
					$result = $result."<option value=".$value['id'].">".$value['name']."</option>";
				}
				$result = $result."</select>";
				break;
		}
		echo $result;
	break;
}


?>
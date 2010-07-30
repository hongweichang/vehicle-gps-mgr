<?php
/** 
* 公司管理
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

		include("include/templates.php");
		echo $GLOBALS['db']->display(null,$act);
		break;

	case "list_data":

		$comp	= new Company();	//模拟打印润色后的字符串值

		$count = $comp->get_all_count();

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
//				$type = $driver->get_type($searchfil);
//				$searchstr = $db->prepare_value($searchstr,$type);
			$wh = "where ".$searchfil." LIKE '%".$searchstr."%'";
		}

		//得到所有公司
		$rtn = $comp->get_all_companys($wh,$sidx,$sord,$start,$limit);

		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		foreach($rtn as	$key=>$rtn_company)
		{
			$responce->rows[$key]['id']=$rtn_company['id'];
			$responce->rows[$key]['cell']=array($rtn_company['id'],$rtn_company['login_id'],$rtn_company['name'],$rtn_company['register_num'],$rtn_company['description'],$rtn_company['contact'],$rtn_company['address'],$rtn_company['zipcode'],$rtn_company['tel'],$rtn_company['fax'],$rtn_company['mobile'],$rtn_company['email'],$rtn_company['site_url'],$rtn_company['state'],$rtn_company['service_start_time'],$rtn_company['service_end_time '],$rtn_company['charge_standard'],$rtn_company['create_id'],$rtn_company['create_time'],$rtn_company['update_id'],$rtn_company['update_time']);
		}

		//打印json格式的数据
		echo json_encode($responce);

		break;

	case "edit_data":

		// 取到当前的操作
		$oper = $_REQUEST["oper"];

		switch($oper)
		{
			// 添加数据
			case "add":

				//获取各种数据
				$parms["id"]				= $GLOBALS['db']->prepare_value($_REQUEST["id"],"INT"); 
				$parms["login_id"]			= $GLOBALS['db']->prepare_value($_REQUEST["name"],"INT"); 
				$parms["name"]				= $GLOBALS['db']->prepare_value($_REQUEST["name"],"VARCHAR");
				$parms["register_num"]		= $GLOBALS['db']->prepare_value($_REQUEST["register_num"],"VARCHAR");
				$parms["area1"]				= $GLOBALS['db']->prepare_value($_REQUEST["area1"],"VARCHAR");
				$parms["area2"]				= $GLOBALS['db']->prepare_value($_REQUEST["area2"],"VARCHAR");
				$parms["area3"]				= $GLOBALS['db']->prepare_value($_REQUEST["area3"],"VARCHAR");
				$parms["description"]		= $GLOBALS['db']->prepare_value($_REQUEST["description"],"VARCHAR");
				$parms["contact"]			= $GLOBALS['db']->prepare_value($_REQUEST["contact"],"VARCHAR");
				$parms["address"]			= $GLOBALS['db']->prepare_value($_REQUEST["address"],"VARCHAR");
				$parms["zipcode"]			= $GLOBALS['db']->prepare_value($_REQUEST["zipcode"],"VARCHAR");
				$parms["tel"]				= $GLOBALS['db']->prepare_value($_REQUEST["tel"],"VARCHAR");
				$parms["fax"]				= $GLOBALS['db']->prepare_value($_REQUEST["fax"],"VARCHAR");
				$parms["mobile"]			= $GLOBALS['db']->prepare_value($_REQUEST["mobile"],"VARCHAR");
				$parms["email"]				= $GLOBALS['db']->prepare_value($_REQUEST["email"],"VARCHAR");
				$parms["site_url"]			= $GLOBALS['db']->prepare_value($_REQUEST["site_url"],"VARCHAR");
				$parms["state"]				= $GLOBALS['db']->prepare_value($_REQUEST["state"],"INT");
				$parms["service_start_time"]= $GLOBALS['db']->prepare_value($_REQUEST["service_start_time"],"VARCHAR");
				$parms["service_end_time "]	= $GLOBALS['db']->prepare_value($_REQUEST["service_end_time "],"VARCHAR");
				$parms["charge_standard"]	= $GLOBALS['db']->prepare_value($_REQUEST["charge_standard"],"VARCHAR");
				$parms["create_id"]			= $GLOBALS['db']->prepare_value($_REQUEST["create_id"],"INT");
				$parms["create_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["create_time"],"VARCHAR");
				$parms["update_id"]			= $GLOBALS['db']->prepare_value($_REQUEST["update_id"],"INT");
				$parms["update_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["update_time"],"VARCHAR");
				$comp	= new Company();

				//执行更新
				$comp->add_data($parms,"id");

				//查一下是否已经车辆组，如果没有，则添加一个默认的
				break;

			// 修改数据
			case "edit":

				//获取各种数据
				$parms["id"]				= $GLOBALS['db']->prepare_value($_REQUEST["id"],"INT"); 
				$parms["login_id"]			= $GLOBALS['db']->prepare_value($_REQUEST["login_id"],"INT"); 
				$parms["name"]				= $GLOBALS['db']->prepare_value($_REQUEST["name"],"VARCHAR");
				$parms["register_num"]		= $GLOBALS['db']->prepare_value($_REQUEST["register_num"],"VARCHAR");
				$parms["area1"]				= $GLOBALS['db']->prepare_value($_REQUEST["area1"],"VARCHAR");
				$parms["area2"]				= $GLOBALS['db']->prepare_value($_REQUEST["area2"],"VARCHAR");
				$parms["area3"]				= $GLOBALS['db']->prepare_value($_REQUEST["area3"],"VARCHAR");
				$parms["description"]		= $GLOBALS['db']->prepare_value($_REQUEST["description"],"VARCHAR");
				$parms["contact"]			= $GLOBALS['db']->prepare_value($_REQUEST["contact"],"VARCHAR");
				$parms["address"]			= $GLOBALS['db']->prepare_value($_REQUEST["address"],"VARCHAR");
				$parms["zipcode"]			= $GLOBALS['db']->prepare_value($_REQUEST["zipcode"],"VARCHAR");
				$parms["tel"]				= $GLOBALS['db']->prepare_value($_REQUEST["tel"],"VARCHAR");
				$parms["fax"]				= $GLOBALS['db']->prepare_value($_REQUEST["fax"],"VARCHAR");
				$parms["mobile"]			= $GLOBALS['db']->prepare_value($_REQUEST["mobile"],"VARCHAR");
				$parms["email"]				= $GLOBALS['db']->prepare_value($_REQUEST["email"],"VARCHAR");
				$parms["site_url"]			= $GLOBALS['db']->prepare_value($_REQUEST["site_url"],"VARCHAR");
				$parms["state"]				= $GLOBALS['db']->prepare_value($_REQUEST["state"],"INT");
				$parms["service_start_time"]= $GLOBALS['db']->prepare_value($_REQUEST["service_start_time"],"VARCHAR");
				$parms["service_end_time"]	= $GLOBALS['db']->prepare_value($_REQUEST["service_end_time"],"VARCHAR");
				$parms["charge_standard"]	= $GLOBALS['db']->prepare_value($_REQUEST["charge_standard"],"VARCHAR");
				$parms["create_id"]			= $GLOBALS['db']->prepare_value($_REQUEST["create_id"],"INT");
				$parms["create_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["create_time"],"VARCHAR");
				$parms["update_id"]			= $GLOBALS['db']->prepare_value($_REQUEST["update_id"],"INT");
				$parms["update_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["update_time"],"VARCHAR");
				$comp	= new Company();

				//执行更新
				$comp->edit_data($parms,"id");

				break;

			// 删除数据
			case "del":
				$comp	= new Company();
				$parms["id"] = $GLOBALS['db']->prepare_value($_REQUEST["id"],"INT"); 
				$comp->delete_data($parms);
				break;
		}
		file_put_contents("d:\a.txt",$GLOBALS['db']->get_last_sql());
		break;
}
?>
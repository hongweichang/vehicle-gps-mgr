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

		//公司状态
		$xml_state  = new Xml("company","state");
		$state = $xml_state->get_array_xml();

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
			$responce->rows[$key]['cell']=array($rtn_company['id'],$rtn_company['login_id'],$rtn_company['name'],$rtn_company['register_num'],$rtn_company['area1'],$rtn_company['area2'],$rtn_company['area3'],$rtn_company['description'],$rtn_company['contact'],$rtn_company['address'],$rtn_company['zipcode'],$rtn_company['tel'],$rtn_company['fax'],$rtn_company['mobile'],$rtn_company['email'],$rtn_company['site_url'],$state[$rtn_company['state']],$rtn_company['service_start_time'],$rtn_company['service_end_time'],$rtn_company['charge_standard'],$rtn_company['create_id'],$rtn_company['create_time'],$rtn_company['update_id'],$rtn_company['update_time']);
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
				$parms["login_id"]			= $GLOBALS['db']->prepare_value($_REQUEST["login_id"],"INT"); 
				$parms["name"]				= $GLOBALS['db']->prepare_value($_REQUEST["name"],"VARCHAR");
				$parms["register_num"]		= $GLOBALS['db']->prepare_value($_REQUEST["register_num"],"VARCHAR");
				/******************************* 暂时都是 0 */
				$parms["area1"]				= 0;
				$parms["area2"]				= 0;
				$parms["area3"]				= 0;
				/******************************* 暂时都是 0 */
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
				if(!empty($_REQUEST["service_start_time"]) && !empty($_REQUEST["service_end_time"]))
				{	
					$parms["service_start_time"]= $GLOBALS['db']->prepare_value($_REQUEST["service_start_time"],"VARCHAR");
					$parms["service_end_time "]	= $GLOBALS['db']->prepare_value($_REQUEST["service_end_time"],"VARCHAR");
				}
				$parms["charge_standard"]	= $GLOBALS['db']->prepare_value($_REQUEST["charge_standard"],"VARCHAR");

				$parms["create_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["create_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				$parms["update_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["update_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				$comp	= new Company();

				//检查 login_id 是否重复
				$login = $comp->checkLoignid($_REQUEST["login_id"]);
				if($login)
				{
					exit(json_encode(array('success'=>false,'errors'=>'重复的登录ID，请重试!')));
				}
				
				//执行更新
				$rtn = $comp->add_data($parms,"id");

				if($rtn > 1)
				{
					//查一下是否已经车辆组，如果没有，则添加一个默认的
					//添加默认车辆组
					$vehicle = new Vehicle_group();

					$result = $vehicle->add_vehicle_group_by_company($rtn,$_REQUEST["name"]);
	
					if($result)
					{
						//成功
						echo json_encode(array('success'=>true,'errors'=>'添加成功!'));
					}
					else
					{
						//删掉添加成功的公司
						$parms["id"] = $GLOBALS['db']->prepare_value($rtn,"INT"); 
						$comp->delete_data($parms);
						echo json_encode(array('success'=>false,'errors'=>'添加失败，请重试!'));
					}

				}
				else
				{
					
					echo json_encode(array('success'=>false,'errors'=>'添加失败，请重试!'));
				}

				break;

			// 修改数据
			case "edit":

				//获取各种数据
				$parms["id"]				= $GLOBALS['db']->prepare_value($_REQUEST["id"],"INT"); 
//				$parms["login_id"]			= $GLOBALS['db']->prepare_value($_REQUEST["login_id"],"INT"); 
				$parms["name"]				= $GLOBALS['db']->prepare_value($_REQUEST["name"],"VARCHAR");
				$parms["register_num"]		= $GLOBALS['db']->prepare_value($_REQUEST["register_num"],"VARCHAR");
				/******************************* 暂时都是 0 */
				$parms["area1"]				= 0;
				$parms["area2"]				= 0;
				$parms["area3"]				= 0;
				/******************************* 暂时都是 0 */
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

				if(!empty($_REQUEST["service_start_time"]) && !empty($_REQUEST["service_end_time"]))
				{	
					$parms["service_start_time"]= $GLOBALS['db']->prepare_value($_REQUEST["service_start_time"],"VARCHAR");
					$parms["service_end_time "]	= $GLOBALS['db']->prepare_value($_REQUEST["service_end_time"],"VARCHAR");
				}
				$parms["charge_standard"]	= $GLOBALS['db']->prepare_value($_REQUEST["charge_standard"],"VARCHAR");

				// session 值
				$parms["create_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["create_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				$parms["update_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["update_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				$comp	= new Company();

				//执行更新
				$rtn = $comp->edit_data($parms,"id");

				if(!$rtn)
					echo json_encode(array('success'=>false,'errors'=>'更新失败，请重试!'));
				else
					echo json_encode(array('success'=>true,'errors'=>'更新成功!'));

				break;

			// 删除数据
			case "del":
				$comp	= new Company();
				$parms["id"] = $GLOBALS['db']->prepare_value($_REQUEST["id"],"INT"); 
				$comp->delete_data($parms);
				break;
		}
//		file_put_contents("d:\a.txt",$GLOBALS['db']->sql);
		break;
}
?>
<?php
/** 
* 人员管理
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

			$driver	= new Driver();	//模拟打印润色后的字符串值
			$count = $driver->get_all_count();

			if( $count >0 ) {
				$total_pages = ceil($count/$limit);
			} else {
				$total_pages = 0;
			}
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit;
			if ($start<0) $start = 0;


			//翻译xml里面的数据
			//性别
			$xml_sex  = new Xml("comm","sex");
			$sex = $xml_sex->get_array_xml();

			//驾照类型
			$xml_driving_type  = new Xml("driver_manage","driving_type");
			$driving_type = $xml_driving_type->get_array_xml();

			//驾驶状态
//			$xml_driving_state  = new Xml("driver_manage","driving_state");
//			$driving_state = $xml_driving_state->get_array_xml();

			//得到字段类型
			if(empty($searchfil) or empty($searchstr))
				$wh = '';
			else
			{
//				$type = $driver->get_type($searchfil);
//				$searchstr = $db->prepare_value($searchstr,$type);

				//查询的时候需要翻译
				if($searchfil == "sex")
				{
					$sex_wh = array_flip($sex);
					$searchstr = $sex_wh[$searchstr];
				}
				if($searchfil == "driving_type")
				{
					$driving_type_wh = array_flip($driving_type);
					$searchstr = $driving_type_wh[$searchstr];
				}
//				if($searchfil == "driving_state")
//				{
//					$driving_state_wh = array_flip($driving_state);
//					$searchstr = $driving_state_wh[$searchstr];
//				}

				$wh = " where ".$searchfil." LIKE '%".$searchstr."%'";

			}

			//得到所有人员
			$rtn = $driver->get_all_drivers($wh,$sidx,$sord,$start,$limit);

			$responce->page	= $page;
			$responce->total = $total_pages;
			$responce->records = $count;

			foreach($rtn as	$key=>$rtn_driver)
			{
				$responce->rows[$key]['id']=$rtn_driver['id'];
				$responce->rows[$key]['cell']=array($rtn_driver['name'],$rtn_driver['driving_licence_id'],$sex[$rtn_driver['sex']],$rtn_driver['birthday'],$rtn_driver['career_time'],$rtn_driver['job_number'],$driving_type[$rtn_driver['driving_type']],$rtn_driver['mobile'],$rtn_driver['phone_email'],$rtn_driver['address'],"<a href='javascript:void(0)' onclick='adviceDialog(".$rtn_driver['id'].",".$rtn_driver['company_id'].");' style='text-decoration:none;color:#0099FF'>车辆授权</a>");
			}

			//打印json格式的数据
			echo json_encode($responce);

		break;

	case "edit_data":

		$oper = $_REQUEST["oper"];

		switch($oper)
		{
			case "add":
								//获取各种数据
				$parms["name"]					= $GLOBALS['db']->prepare_value($_REQUEST["name"],"VARCHAR"); 
				$parms["driving_licence_id"]	= $GLOBALS['db']->prepare_value($_REQUEST["driving_licence_id"],"VARCHAR");
				$parms["sex"]					= $_REQUEST["sex"];
				$parms["birthday"]				= $GLOBALS['db']->prepare_value($_REQUEST["birthday"],"VARCHAR");
				//取的 session
				$parms["company_id"]			= $GLOBALS['db']->prepare_value(get_session("company_id"),"INT");
				$parms["career_time"]			= $GLOBALS['db']->prepare_value($_REQUEST["career_time"],"VARCHAR");
				$parms["job_number"]			= $GLOBALS['db']->prepare_value($_REQUEST["job_number"],"VARCHAR");
				$parms["driving_type"]			= $_REQUEST["driving_type"];
				$parms["mobile"]				= $GLOBALS['db']->prepare_value($_REQUEST["mobile"],"VARCHAR");
//				$parms["driving_state"]			= $_REQUEST["driving_state"];
				$parms["job_number"]			= $GLOBALS['db']->prepare_value($_REQUEST["job_number"],"VARCHAR");
				$parms["phone_email"]			= $GLOBALS['db']->prepare_value($_REQUEST["phone_email"],"VARCHAR");
				//取的 session
				$parms["create_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["create_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				$parms["update_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["update_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");

				$driver = new Driver();

				//检查同一公司的人员是否重复
				$result = $driver->checkName($_REQUEST["name"],get_session("company_id"));
				if($result)
				{
					exit(json_encode(array('success'=>false,'errors'=>'重复的姓名!')));
				}

				//执行插入
				$rtn = $driver->add_data($parms);
				if($rtn > 1)
					echo json_encode(array('success'=>true,'errors'=>'添加成功!'));
				else
					echo json_encode(array('success'=>true,'errors'=>'添加失败，请重试!'));
				break;

			case "edit":

				//获取各种数据
				$parms["id"]					= $GLOBALS['db']->prepare_value($_REQUEST["id"],"INT"); 
				$parms["name"]					= $GLOBALS['db']->prepare_value($_REQUEST["name"],"VARCHAR"); 
				$parms["driving_licence_id"]	= $GLOBALS['db']->prepare_value($_REQUEST["driving_licence_id"],"VARCHAR");
				$parms["sex"]					= $_REQUEST["sex"];
				$parms["birthday"]				= $GLOBALS['db']->prepare_value($_REQUEST["birthday"],"VARCHAR");
				//取的 session
				$parms["company_id"]			= $GLOBALS['db']->prepare_value(get_session("company_id"),"INT");
				$parms["career_time"]			= $GLOBALS['db']->prepare_value($_REQUEST["career_time"],"VARCHAR");
				$parms["job_number"]			= $GLOBALS['db']->prepare_value($_REQUEST["job_number"],"VARCHAR");
				$parms["driving_type"]			= $_REQUEST["driving_type"];
				$parms["mobile"]				= $GLOBALS['db']->prepare_value($_REQUEST["mobile"],"VARCHAR");
//				$parms["driving_state"]			= $_REQUEST["driving_state"];
				$parms["job_number"]			= $GLOBALS['db']->prepare_value($_REQUEST["job_number"],"VARCHAR");
				$parms["phone_email"]			= $GLOBALS['db']->prepare_value($_REQUEST["phone_email"],"VARCHAR");
				//取的 session
				$parms["create_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["create_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				$parms["update_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["update_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				
				$driver = new Driver();

				//检查同一公司的人员是否重复
				$result = $driver->checkName($_REQUEST["name"],get_session("company_id"),$_REQUEST["id"]);
				if($result)
				{
					exit(json_encode(array('success'=>false,'errors'=>'重复的姓名!')));
				}

				//执行更新
				$rtn = $driver->edit_data($parms,"id");
				if(!$rtn)
					echo json_encode(array('success'=>false,'errors'=>'更新失败，请重试!'));
				else
					echo json_encode(array('success'=>true,'errors'=>'更新成功!'));
				break;

			case "del":
				$driver = new Driver();
				$parms["id"] = $GLOBALS['db']->prepare_value($_REQUEST["id"],"INT"); 
				$driver->delete_data($parms);
				break;
		}
//		file_put_contents("d:\a.txt",$GLOBALS['db']->sql);
		break;

	// 车辆授权
	case "driver_pri":

		//人员ID
		$driver_id = $_REQUEST["driver_id"];

		//公司ID
		$company_id = $_REQUEST["company_id"];

		//取出该公司所有的车辆组
		$group = new Vehicle_group;
		$rtn = $group->get_all_group($company_id);

		//查出已经授权的车辆
		$driver = new Driver();
		$pri = $driver->get_driver_vehicle($driver_id);

		$pri_driver = array();
		foreach($pri as $pri_temp)
		{
			$pri_driver[] = $pri_temp["vehicle_id"];
		}

		//拼一个组的页面
		$html = '';
		foreach($rtn as $key=>$group)
		{
			//得到每个组的车辆
			$vehicle = new Vehicle();
			$rtn_vehicle = $vehicle->get_all_vehicle($group["id"]);

			$html .= "<br>".$group["name"]."<br>";

			$i = 0;
			foreach($rtn_vehicle as $key_v=>$rtn_vehicle)
			{
				$i++;
//echo $i."<br>";
				//如果已经则表示选中
				if(in_array($rtn_vehicle["id"],$pri_driver))
				{
					$check = " checked ";
				}
				else
				{
					$check = " ";
				}

				if($i%5 == 0)
				{
					$b = " <br> ";
				}
				else
				{
					$b = "&nbsp;&nbsp;&nbsp;";
				}

				$html .= "<input type='checkbox' name='vehicle[]' ".$check." value='".$rtn_vehicle["id"]."'> ".$rtn_vehicle["number_plate"].$b;
			}

		}
		echo $html;
		break;

	case "submit_pri":

		//得到选中的车辆ID
		$driver_ids = $_REQUEST["temp"];

		$driver = new Driver();

		//先删除已经授权的车辆
		$driver->del_driver_vehicle($_REQUEST["driver_id"]);

		if(!empty($driver_ids))
		{

			$driver_ids = substr($driver_ids, 0, -1);  

			//分隔得到的车辆ID
			$ids = explode(",", $driver_ids);

			foreach($ids as $v_id)
			{
				$parms["driver_id"]				= $GLOBALS['db']->prepare_value($_REQUEST["driver_id"],"INT");
				$parms["vehicle_id"]			= $GLOBALS['db']->prepare_value($v_id,"INT");
				$parms["create_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["create_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				$parms["update_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
				$parms["update_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
				$rtn = $driver->driver_vehicle($parms);			
			}
		}
//		file_put_contents("d:\a.txt",$driver_ids);
		break;
}
?>
<?php
/** 
* 用户处理
* @copyright		user, 2010
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
$foper = $_REQUEST['searchOper'];
$par = $_REQUEST["par"];
$child = $_REQUEST["child"];

if(!$sidx) $sidx =1;

switch($act)
{
	case "user_manage":			//加载用户管理的html页面
		echo $db->display(null,"manage");
		break;
	case "list_data":		//用户管理html中，js文件会加载这个case，取得并输出数据
		$user	= new User();
		$count = $user->get_user_count();

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
			$searchstr = $db->prepare_value($searchstr,$type);
			$wh = "where ".$searchfil." = ".$searchstr;
		}
		
		//得到所有用户
		$result = $user->get_all_users($wh,$sidx,$sord,$start,$limit);
		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		foreach($result as	$key => $val)
		{
			$responce->rows[$key]['id']=$val['id'];
			$responce->rows[$key]['cell']=array($val['id'],$val['login_name'],$val['password'],$val['name'],$val['company_id'],$val['role_id'],$val['email'],$val['state'],$val['backup1'],$val['backup2'],$val['backup3'],$val['backup4'],$val['create_id'],$val['create_time'],$val['update_id'],$val['update_time']);
		}

		//打印json格式的数据
		echo json_encode($responce);
		break;
	case "manage_list":			//模拟管理页面
		echo $db->display(null,"manage_list");
		break;
	case "login_success":		
		$arr['url_manage'] = URL('user','user.php','manage_list');
		$arr['url_setup'] = URL('user','user.php','setup');
		$arr['url_logout'] = URL('user','user.php','logout');
		echo $db->display($arr,"login_success");
		break;
	case "logout":	//模拟退出
		session_start();
		session_unset();
		session_destroy();
		Header("Location: index.php");
		break;
	case "setup":		//你进入到了系统设置页面
		msg('你进入到了系统设置页面.');
		//echo '<select><option>hello</option><option>world</option></select>';
		break;
	case "operate":		//用户修改、添加、删除
		$oper = $_REQUEST['oper'];
		//file_put_contents("a.txt",$oper);exit;
		$arr["login_name"] = $db->prepare_value($_REQUEST['login_name'],"VARCHAR");
		$arr["password"] = $db->prepare_value("1","VARCHAR");//$_REQUEST['password']
		$arr["name"] = $db->prepare_value($_REQUEST['name'],"VARCHAR");
		$arr["company_id"] = $db->prepare_value(1,"INT");//$_REQUEST['company_id']
		$arr["role_id"] = $db->prepare_value(1,"INT");//$_REQUEST['role_id']
		$arr["email"] = $db->prepare_value($_REQUEST['email'],"VARCHAR");
		$arr["state"] = $db->prepare_value($_REQUEST['state'],"INT");
//		$arr["backup1"] = $db->prepare_value($_REQUEST['backup1'],"VARCHAR");
//		$arr["backup2"] = $db->prepare_value($_REQUEST['backup2'],"VARCHAR");
//		$arr["backup3"] = $db->prepare_value($_REQUEST['backup3'],"VARCHAR");
//		$arr["backup4"] = $db->prepare_value($_REQUEST['backup4'],"VARCHAR");
//		$arr["create_id"] = $db->prepare_value($_REQUEST['create_id'],"INT");
//		$arr["create_time"] = $db->prepare_value($_REQUEST['create_time'],"DATETIME");
//		$arr["update_id"] = $db->prepare_value($_REQUEST['update_id'],"INT");
//		$arr["update_time"] = $db->prepare_value($_REQUEST['update_time'],"DATETIME");
		$user = new User($_REQUEST['id']);
		switch($oper)
		{
			case "add":		//增加
				$user->add_user($arr);
				break;
			case "edit":		//修改
				$user->edit_user($arr);
//				file_put_contents("a.txt",$db->sql);
				break;
			case "del":		//删除
				$user->del_user($arr);
				break;
		}
		break;
	case "select":		//下拉列表
		$p = $_REQUEST["p"];		//获得需要翻译的字段
		$vehicle = new User();
		switch($p)
		{
//			case "vehicle_group_id":
//				$html = $vehicle->get_select("vehicle_group","name");
//				break;
//			case "driver_id":
//				$html = $vehicle->get_select("driver_manage","name");
//				break;
//			case "type_id":
//				$html = $vehicle->get_select("vehicle_type_manage","name");
//				break;
			case "state":
				if(!$par or !$child)
				{
					$par = "user";
					$child = "state";
				}
				$xml = new Xml($par,$child);
				$html = $xml->get_html_xml();
				break;
		}
		echo $html;
}


?>
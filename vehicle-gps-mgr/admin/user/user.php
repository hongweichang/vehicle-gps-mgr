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
$comm_setting_path = $all ["BASE"] . "xml/tree.xml";

$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
$searchfil = $_REQUEST['searchField']; // get the direction
$searchstr = $_REQUEST['searchString']; // get the direction
$foper = $_REQUEST['searchOper'];
$par = $_REQUEST["par"];
$child = $_REQUEST["child"];
$identify_id = get_session("identify_id");//获取用户角色ID

if(!$sidx) $sidx =1;

switch($act)
{
	case "user_manage":			//加载用户管理的html页面
		echo $db->display(null,"manage");
		break;
		
	case "user_system":
		echo $db->display(null,"system");	
		break;
		
	case "list_data":		//用户管理html中，js文件会加载这个case，取得并输出数据
		$user	= new User();
		$count = $user->get_user_count();//查询登录公司所有用户总数

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
			$type = $user->get_type($searchfil);
			$wh = "where 1=1 ";
			switch($searchfil)
			{
				case "state":
					$xml = new Xml("user","state");
					$xmldata = $xml->get_array_xml();
					$data = array_flip($xmldata);
					$searchstr = $data[$searchstr];
					break;
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
			//file_put_contents("a.txt",$wh);
		}
		
		//得到所有用户
		if($_REQUEST['role']){
			$result = $user->get_sys_users($wh,$sidx,$sord,$start,$limit); //查询所有公司平台管理员和系统管理员
		}else{
			$result = $user->get_all_users($wh,$sidx,$sord,$start,$limit); //查询所有公司管理员权限及其以下用户
		}
//		file_put_contents("a.txt",$db->sql);
		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		foreach($result as	$key => $val)
		{
			$user = new User($val['id']);
			$state = $user->get_data("v_state");
			$responce->rows[$key]['id']=$val['id'];
			$responce->rows[$key]['cell']=array($val['id'],$val['login_name'],$val['name'],
																					$val['company_id'],$val['company_name'],$val['role_id'],$val['email'],$state,$val['role_name']
//																					$val['backup1'],$val['backup2'],
//																					$val['backup3'],$val['backup4'],$val['create_id'],
//																					$val['create_time'],$val['update_id'],$val['update_time']
																					);
		}

		//打印json格式的数据
		echo json_encode($responce);
		break;
		
	case "manage_list":			//模拟管理页面
		$data["user_name"] = get_session("user_name");
		$data['identify_id'] = $identify_id;
		echo $db->display($data,"manage_list");
		break;
	case "login_success":	 
		echo $db->display(null,"login_success");
		break; 
	case "logout":	//模拟退出
		$user = new User();
		if(!$user->logout())
			msg("退出失败！");
		else
			Header("Location: index.php");
		break;
	case "setup":		//你进入到了系统设置页面
		goto_url(URL("system_set","sys_set.php","list"));
		//echo '<select><option>hello</option><option>world</option></select>';
		break;
	case "operate":		//用户修改、添加、删除
		$oper = $_REQUEST['oper'];
		//file_put_contents("a.txt",$oper);exit;
		$arr["login_name"] = $db->prepare_value($_REQUEST['login_name'],"VARCHAR");
		$arr["name"] = $db->prepare_value($_REQUEST['name'],"VARCHAR");
		$arr["company_id"] = $db->prepare_value(get_session("company_id"),"INT");
		$arr["role_id"] = $db->prepare_value($_REQUEST['role'],"INT");	
		$arr["email"] = $db->prepare_value($_REQUEST['email'],"VARCHAR");
		$arr["state"] = $db->prepare_value($_REQUEST['state'],"INT");
		
		$user = new User($_REQUEST['id']);
		switch($oper)
		{
			case "add":		//增加						
				if($user->check_login_name($_REQUEST['login_name'])){
					exit(json_encode(array('success'=>false,'errors'=>'重复的登录ID，请重试!')));
				}
				
				if($user->add_user($arr)){
					echo json_encode(array('success'=>true,'errors'=>'添加成功!'));
				}
				break;
			case "edit":		//修改
				$result = $user->edit_user($arr);
//				file_put_contents("a.txt",$db->sql);
				if($result){
					echo json_encode(array('success'=>true,'errors'=>'添加成功!'));
				}else{
					exit(json_encode(array('success'=>false,'errors'=>'编辑失败!')));
				}
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
			case "state"://用户状态(激活与未激活)
				if(!$par or !$child)
				{
					$par = "user";
					$child = "state";
				}
				
				//读取xml
				$xml = new Xml($par,$child);
				$html = $xml->get_html_xml();
				break;
				
			case "role"://用户角色(公司内部管理员与一般使用人员)
				if(!$par or !$child)
				{
					$par = "role";
					$child = "admin";
				}
				
				//读取xml
				$xml = new Xml($par,$child);
				$html = $xml->get_html_xml();
				break;
				
			case "sys_role"://用户角色(系统管理员与公司平台管理员)
				if(!$par or !$child)
				{
					$par = "role";
					$child = "sysadmin";
				}
				
				//读取xml
				$xml = new Xml($par,$child);
				$html = $xml->get_html_xml();
				break;
		}
		echo $html;
		break;
}


?>
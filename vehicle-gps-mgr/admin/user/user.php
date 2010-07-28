<?php
/** 
* 用户处理
* @copyright		company, 2010
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
		break;
	case "add":			//模拟添加用户
		$arr['url_submit'] = URL('us','user','add_submit');
		echo $GLOBALS['db']->display($arr,$act);
		break;
	case "add_submit":		//模拟添加用户提交
		msg($_REQUEST['user_name']);
		msg($_REQUEST['user_pass']);
		break;
	
	case "edit":		//用户修改
		msg('hello');
		break;
	case "edit_submit":		//用户修改
		
		break;
}


?>
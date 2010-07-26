<?
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

switch($act)
{
	case "list":		//模拟测试【登录首页】
	default:
		break;
	case "login_success":		
		$arr['url_manage'] = URL('user','user.php','manage_list');
		$arr['url_logout'] = URL('user','user.php','logout');
		echo $db->display($arr,"login_success");
		break;
	case "manage_list":			//模拟管理页面
		echo $db->display($arr,"manage");
		break;
	case "user_manage":			//模拟用户管理
		msg('hello world!');
		break;
	case "logout":	//模拟退出
		session_start();
		session_unset();
		session_destroy();
		Header("Location: index.php");
		break;
	case "add":			//模拟添加用户
		$arr['url_submit'] = URL('us','user','add_submit');
		echo $GLOBALS['db']->display($arr,$act);
		break;
	case "add_submit":		//模拟添加用户提交
		msg($_REQUEST['user_name']);
		msg($_REQUEST['user_pass']);
		break;
}


?>
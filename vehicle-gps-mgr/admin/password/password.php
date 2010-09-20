<?php
/** 
* 密码处理
* @copyright		vehicle, 2010
* @author			赵将伟
* @create date		2010.09.19
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

$role_id = get_session('role_id');
$user_id = get_session("user_id");

if(!$sidx) $sidx =1;

switch($act)
{
	case "list": //修改密码主页面
		echo $db->display(null,"list");
		break;
		
	case "update_password": //修改密码
		$password = new Password();
		$old = $_REQUEST['old_pass'];
		$new = $_REQUEST['new_pass'];
		
		if(!$password->check_old($old)){
			echo "old_wrong";
			break;
		}
		
		$old_user = $password->get_user($user_id);
		
		$user['id'] = $GLOBALS['db']->prepare_value($user_id,"INT");
		$user['login_name'] = $GLOBALS['db']->prepare_value($old_user['login_name'],"VARCHAR");
		$user['password'] = $GLOBALS['db']->prepare_value($new,"VARCHAR");
		$user['name'] = $GLOBALS['db']->prepare_value($old_user['name'],"VARCHAR");
		$user['state'] = $GLOBALS['db']->prepare_value($old_user['state'],"TINYINT");
		$user['company_id'] = $GLOBALS['db']->prepare_value($old_user['company_id'],"INT");
		$user['role_id'] = $GLOBALS['db']->prepare_value($old_user['role_id'],"INT");
		$user['email'] = $GLOBALS['db']->prepare_value($old_user['email'],"VARCHAR");
		
		$result = $password->update_password($user);
		if($result){
			echo "ok";
		}else{
			echo "fail";
		}
		break;
}


?>
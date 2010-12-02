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

$identify_id = get_session('identify_id');
$user_id = get_session("user_id");

if(!$sidx) $sidx =1;

switch($act)
{
	case "list": //修改密码主页面
		echo $db->display(null,"list");
		break;
		
	case "update_password": //修改密码
		$password = new Password();
		$old = $_REQUEST['old_pass']; //原密码
		$new = $_REQUEST['new_pass']; //新密码
		
		//检查旧密码是否正确
		if(!$password->check_old($old)){
			echo "old_wrong";
			break;
		}
		
		$result = $password->update_password($new,$user_id); //更新密码
		if($result){
			echo "ok";
		}else{
			echo "fail";
		}
		break;
}


?>
<?php
/** 
* 首页处理
* @copyright		home_page, 2010
* @author			叶稳
* @create date		2010.08.07
* @modify			修改人 
* @modify date		修改日期
* @modify describe	修改内容
*/
$act = $GLOBALS["all"]["operate"];
 
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
 
if(!$sidx) $sidx =1;

switch($act)
{
	case "list":		
		$arr['url_manage'] = URL('user','user.php','manage_list'); 
		$arr['url_logout'] = URL('user','user.php','logout');
		$arr['host']= "http://".$_SERVER ['HTTP_HOST'];
		
		echo $db->display($arr,"list");
		break;
}


?>
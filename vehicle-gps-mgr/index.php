<?
	require_once('include/inc_all.php');
	//此处添加判断，比如用户是否注册，是否已经登录等等，若不符合条件，则跳转到其他页面
	//完全符合条件的，可以设置一些系统常用的数据，比如用户对象，用户ID等等，方便之后的使用
	$admin_id = get_session("admin_id");
	//session_destroy();exit;
	if(!$admin_id)
	{
		//模拟跳转到登录或注册页面
		goto_url("login.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>车辆管理系统</title>
<script type='text/javascript' src='js/jquery.js'></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<link href="css/main.css" rel="stylesheet" type="text/css" />

	<!--主页布局内容-->
	<?
	decode_func_url();
	$func_file = trim($all["BASE"].$all["gong_neng"]["directory"]."/".$all["gong_neng"]["file_name"]);
	//msg($func_file);exit;
	if (is_file($func_file))
  {
  	require($func_file);
  }
  else
  {
    echo "操作无效，请重新登录！";
    exit;
  }
?>
<!--// JQuery test>
<script>
	$(document).ready(
		function(){
			alert('hello world!');
		}
	);
</script-->

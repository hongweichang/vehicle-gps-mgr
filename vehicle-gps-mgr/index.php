<?
	require_once('include/inc_all.php');

	$admin_id = get_session("admin_id");
	//session_destroy();exit;
	if(!$admin_id)
	{
		//模拟跳转到登录或注册页面
		goto_url("login.php");
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>车辆管理系统</title>

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
</html>
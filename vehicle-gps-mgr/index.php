<?php
	require_once('include/inc_all.php');

	$user_id = get_session("user_id");
	if(!$user_id)
	{
		//模拟跳转到登录或注册页面
		goto_url("login.php");
	}

	decode_func_url();
	$func_file = trim($all["BASE"].$all["gong_neng"]["directory"]."/".$all["gong_neng"]["file_name"]);
	if (is_file($func_file))
  {
  	require($func_file);
  } 
  else
  {
  	//用户已经成功登录，如果访问功能（文件）不存在，则直接跳到首页
  	goto_url(URL("home_page","home_page.php","list"));
  }
?>
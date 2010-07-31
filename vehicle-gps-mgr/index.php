<?php
	require_once('include/inc_all.php');

	$user_id = get_session("user_id");
	//session_destroy();exit;
	if(!$user_id)
	{
		//模拟跳转到登录或注册页面
		goto_url("login.php");
	}

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
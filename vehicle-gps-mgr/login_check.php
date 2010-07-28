<?php
	/**
	*		
	*		用于登录校验
	*
	*/
	require_once('include/inc_all.php');
	$user_name = $_REQUEST['username'];
	$user_pass = $_REQUEST['password'];
	$companyloginid = $_REQUEST['companyloginid'];
	$user = new User();
	if(!$user->login($user_name,$user_pass,$companyloginid))
	{
		$errormsg = $user->message;//"用户名或密码错误，登录失败！";
		echo $db->result_display_alert(0,false,$errormsg);
		die();
	}
	goto_url(URL('user',"user.php","login_success"));
	//Header("Location: index.php?a=1003");
	exit;




?>
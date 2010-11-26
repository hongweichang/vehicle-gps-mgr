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
		echo(-1);
	}
	if($companyloginid=="system"){
		echo(2);
	}else{
		echo(1);
	}
	exit;




?>
<?php
	/**
	*		
	*		���ڵ�¼У��
	*
	*/
	require_once('include/inc_all.php');
	$user_name = $_REQUEST['username'];
	$user_pass = $_REQUEST['password'];
	$user = new User();
	if(!$user->login($user_name,$user_pass))
	{
		$errormsg = $user->message;//"�û�����������󣬵�¼ʧ�ܣ�";
		echo $db->result_display_alert(0,false,$errormsg);
		die();
	}
	goto_url(URL('user',"user.php","login_success"));
	//Header("Location: index.php?a=1003");
	exit;




?>
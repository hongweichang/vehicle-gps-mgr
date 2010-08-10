<?php
/** 
 * 人员管理
 * @copyright		秦运恒, 2010
 * @author			郭英涛
 * @create date		2010.08.02
 * @modify			修改人
 * @modify date		修改日期
 * @modify describe	修改内容
 */

$act = $GLOBALS ["all"] ["operate"];

switch ($act) {
	case "main" : //填写信息内容页面
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	
	case "get_mail" :
		$str = $_REQUEST ["character"];
		$info = new info ();
		$address = $info->get_phone_email ( $str );
		$email = "";
		for($i = 0; $i < count ( $address ); $i ++) {
			$email = $email . $address [$i] [0] . "|";
		}
		echo $email;
		break;
	
	case "sendmail" :
		$path=$server_path_config["mail_save_path"];
		$company_id=get_session("company_id");
		$str = mb_convert_encoding ( $_REQUEST ['email_data'], "UTF-8", "gb2312" );	
		file_put_contents($path."/".$company_id.date( 'YmdHis').'.eml' , $str);
		echo "success";

}
?>
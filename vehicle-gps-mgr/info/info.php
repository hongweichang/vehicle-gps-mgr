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
		$vehicle_id = $_REQUEST ["vehicle_id"];
		if($vehicle_id)
		{
			$info = new info();
			$address = $info->get_phone_email ( $vehicle_id );
			$param["MAIL_LIST"] = "<option>--驾驶员手机邮箱列表--</option><option selected>".$address[0][0]."</option>";
		}
		else
		{
			$param["MAIL_LIST"] = "<option>--驾驶员手机邮箱列表--</option>";
		}	
		echo $GLOBALS ['db']->display ( $param, $act );
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
		
		$eamil_data=iconv("UTF-8","GBK", $_REQUEST ['email_data']);

		file_put_contents($path."/".$company_id.date( 'YmdHis').'.eml' ,$eamil_data);
		echo "success";
		
	case "save_email":
		$info = new info();
		
		$parms["is_area_info"]			= $GLOBALS['db']->prepare_value(0,"TINYINT");
		$parms["issuer_id"]		= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
		$parms["type"]		= $GLOBALS['db']->prepare_value(0,"TINYINT");
		$parms["issue_time"]		= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
		$parms["content"]		= $GLOBALS['db']->prepare_value($_REQUEST["content"],"TEXT");
		
        $result = $info->save_info($parms);
        if($result>1){
        	echo "ok";
        }else{
        	echo "fail";
        }
        
        break;
}
?>
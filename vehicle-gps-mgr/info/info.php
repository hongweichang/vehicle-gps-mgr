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
		
		break;
		
	case "save_info":
		$info = new info();
		
		$parms["is_area_info"]			= $GLOBALS['db']->prepare_value($_REQUEST["is_area_info"],"TINYINT");
		$parms["issuer_id"]		= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
		$parms["type"]		= $GLOBALS['db']->prepare_value(0,"TINYINT");
		$parms["issue_time"]		= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
		$parms["content"]		= $GLOBALS['db']->prepare_value($_REQUEST["content"],"TEXT");
		if($_REQUEST['begin_time']!="" or $_REQUEST['begin_time']!=null){
			$parms["begin_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["begin_time"],"VARCHAR");
			$parms["end_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["end_time"],"VARCHAR");
		}
        $result = $info->save_info($parms);
        if($result>1){
        	echo $result;
        }else{
        	echo "fial";
        }
        break;
        
	case "save_area_info_one":
        $info = new info();
        
        $params["info_id"] = $GLOBALS['db']->prepare_value($_REQUEST['info_id'],"INT");
        $params["type"] = $GLOBALS['db']->prepare_value(0,"TINYINT");
        $params["log"] = $GLOBALS['db']->prepare_value($info->arroud($_REQUEST['lon']),"INT");
        $params["lat"] = $GLOBALS['db']->prepare_value($info->arroud($_REQUEST['lat']),"INT");
        $params["radius"] = $GLOBALS['db']->prepare_value(null,"INT");
        $params["next_id"] = $GLOBALS['db']->prepare_value(null,"INT");
        
        $result = $info->save_area_info_one($params);
 		if($result>1){
        	echo $result;
        }else{
        	echo "fail";
        }
        
    	break;
        
	case "save_area_info_two":
		$info = new info();
        
        $params["info_id"] = $GLOBALS['db']->prepare_value($_REQUEST['info_id'],"INT");
        $params["type"] = $GLOBALS['db']->prepare_value(0,"TINYINT");
        $params["log"] = $GLOBALS['db']->prepare_value($info->arroud($_REQUEST['lon']),"INT");
        $params["lat"] = $GLOBALS['db']->prepare_value($info->arroud($_REQUEST['lat']),"INT");
        $params["radius"] = $GLOBALS['db']->prepare_value(null,"INT");
        $params["next_id"] = $GLOBALS['db']->prepare_value($_REQUEST['next_id'],"INT");
        
        $result = $info->save_area_info_two($params);
 		if($result>1){
        	echo "ok";
        }else{
        	echo "fail";
        }
    
        break;
        
     break;
}
?>
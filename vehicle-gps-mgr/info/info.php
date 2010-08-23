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

require_once ("include/data_mapping_handler.php");
$comm_setting_path = $all ["BASE"] . "xml/comm_setting.xml";

switch ($act) {
	case "main" : //填写信息内容页面
		$vehicle_id = $_REQUEST ["vehicle_id"];
		$vehicle_ids = $_REQUEST["vehicle_ids"];
		$info = new info();
		if($vehicle_id)
		{		
			$address = $info->get_phone_email ( $vehicle_id );
			$param["MAIL_LIST"] = "<option>--驾驶员手机邮箱列表--</option><option selected>".$address[0][0]."</option>";
		}else if($vehicle_ids){
			$param["MAIL_LIST"] = "<option>--驾驶员手机邮箱列表--</option>";
			$ids = explode(",",$vehicle_ids);
			for($i = 0;$i<count($ids);$i++){
				$address = $info->get_phone_email($ids[$i]);
				$param["MAIL_LIST"] = $param["MAIL_LIST"]."<option selected>".$address[0][0]."</option>";
			}
		}else{
			$param["MAIL_LIST"] = "<option>--驾驶员手机邮箱列表--</option>";
		}	
		
		$dataMapping = new Data_mapping_handler ( $comm_setting_path );//从xml文件中映射相应的数据库字段值
		$day_list= $dataMapping->getMappingDataList ( "end_time", "day" );
		
		foreach($day_list as $val=>$dis){
			$attr = $attr."<option value=".$val.">".$dis."</option>";
		}
		$param['day_list']=$attr;
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
        
	case "save_area_info":
        $info = new info();
        
        $params["info_id"] = $GLOBALS['db']->prepare_value($_REQUEST['info_id'],"INT");
        $params["type"] = $GLOBALS['db']->prepare_value(0,"TINYINT");
        $params["log"] = $GLOBALS['db']->prepare_value($info->arroud($_REQUEST['lon']),"INT");
        $params["lat"] = $GLOBALS['db']->prepare_value($info->arroud($_REQUEST['lat']),"INT");
        $params["radius"] = $GLOBALS['db']->prepare_value(null,"INT");
        $params["next_id"] = $GLOBALS['db']->prepare_value(null,"INT");
        
        $result = $info->save_area_info($params);
 		if($result>1){
        	echo $result;
        }else{
        	echo "fail";
        }      
    	break;
   
    case "update_area_info":
		$info = new info();
		
		$first_info = $info->get_area_info($_REQUEST['first_id']);
		$second_info = $info->get_area_info($_REQUEST['second_id']);
		
		$first_info[0]['next_id']=$_REQUEST['second_id'];
		$second_info[0]['next_id']=-1;
		
		$info_one['id']=$first_info[0]['id'];
		$info_one['info_id']=$first_info[0]['info_id'];
		$info_one['type']=$first_info[0]['type'];
		$info_one['log']=$first_info[0]['log'];
		$info_one['lat']=$first_info[0]['lat'];
		$info_one['radius']=$first_info[0]['radius'];
		$info_one['next_id']=$first_info[0]['next_id'];
		
		$info_two['id']=$second_info[0]['id'];
		$info_two['info_id']=$second_info[0]['info_id'];
		$info_two['type']=$second_info[0]['type'];
		$info_two['log']=$second_info[0]['log'];
		$info_two['lat']=$second_info[0]['lat'];
		$info_two['radius']=$second_info[0]['radius'];
		$info_two['next_id']=$second_info[0]['next_id'];
		
		$first = $info->update_next_id($info_one);
		$second = $info->update_next_id($info_two);
		
		if($first && $second){
			echo "ok";
		}else{
			echo "fail";
		}		
		break;

        
     break;
}
?>
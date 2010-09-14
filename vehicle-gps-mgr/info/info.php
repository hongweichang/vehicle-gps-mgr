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
		$vehicle_ids = $_REQUEST["vehicle_ids"];//获取驾驶员ID集合
		$info = new info();
		if($vehicle_ids){
			$param["MAIL_LIST"] = "<option>--驾驶员手机邮箱列表--</option>";
			$ids = explode(",",$vehicle_ids);//将驾驶员ID集转换成数组
			
			//遍历驾驶员ID获取驾驶员邮箱
			for($i = 0;$i<count($ids);$i++){
				$address = $info->get_phone_email($ids[$i]); //获取驾驶员邮箱
				$param["MAIL_LIST"] = $param["MAIL_LIST"]."<option selected>".$address[0][0]."</option>"; //将驾驶员邮箱放入数组中
			}
		}else{
			$param["MAIL_LIST"] = "<option>--驾驶员手机邮箱列表--</option>";
		}	
		
		$dataMapping = new Data_mapping_handler ( $comm_setting_path );//从xml文件中映射相应的数据库字段值
		$day_list= $dataMapping->getMappingDataList ( "end_time", "day" ); //从XML文件中读出发布信息的失效天数
		
		//遍历信息失效天数显示在下拉列表中
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
		
	case "save_info": //保存发布信息到数据库中
		$info = new info();
		
		//配置发布信息参数以备保存
		$parms["is_area_info"] = $GLOBALS['db']->prepare_value($_REQUEST["is_area_info"],"TINYINT");
		$parms["issuer_id"]		= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
		$parms["type"]		= $GLOBALS['db']->prepare_value(0,"TINYINT");
		$parms["issue_time"]		= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
		$parms["content"]		= $GLOBALS['db']->prepare_value($_REQUEST["content"],"TEXT");
		$parms["company_id"] = $GLOBALS['db']->prepare_value(get_session("company_id"),"INT");
		
		//通过url中的begin_time参数确定是否是区域信息
		if($_REQUEST['begin_time']!="" or $_REQUEST['begin_time']!=null){
			$parms["begin_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["begin_time"],"VARCHAR");
			$parms["end_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["end_time"],"VARCHAR");
		}
        $result = $info->save_info($parms); //将信息保存到数据库中，返回信息ID
        if($result>1){
        	echo $result;
        }else{
        	echo "fial";
        }
        break;
        
	case "save_area_info": //保存区域发布信息
        $info = new info();
        
        //配置发布信息以备保存
        $params["info_id"] = $GLOBALS['db']->prepare_value($_REQUEST['info_id'],"INT");
        $params["type"] = $GLOBALS['db']->prepare_value(0,"TINYINT");
        $params["log"] = $GLOBALS['db']->prepare_value($info->arroud($_REQUEST['lon']),"INT");
        $params["lat"] = $GLOBALS['db']->prepare_value($info->arroud($_REQUEST['lat']),"INT");
        $params["radius"] = $GLOBALS['db']->prepare_value(null,"INT");
        $params["next_id"] = $GLOBALS['db']->prepare_value(null,"INT");
        
        $result = $info->save_area_info($params); //将信息保存在数据库中，返回信息ID
 		if($result>1){
        	echo $result;
        }else{
        	echo "fail";
        }      
    	break;
   
    case "update_area_info": //更新区域信息表，添加next_id值
		$info = new info();
		
		$first_info = $info->get_area_info($_REQUEST['first_id']); //获取信息
		$second_info = $info->get_area_info($_REQUEST['second_id']); //获取信息
		
		$first_info[0]['next_id']=$_REQUEST['second_id']; //设置第一条信息next_id为第二条信息ID
		$second_info[0]['next_id']=-1; //设置第二条信息ID为-1
		
		/**手动书写发布信息，以符合更新发布信息方法要求的格式**/
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
		
		$first = $info->update_next_id($info_one); //更新next_id返回boolean值
		$second = $info->update_next_id($info_two); //更新next_id返回boolean值
		
		if($first && $second){
			echo "ok";
		}else{
			echo "fail";
		}		
		break;

        
     break;
}
?>
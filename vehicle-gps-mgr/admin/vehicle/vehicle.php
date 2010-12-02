<?php
/** 
* 车辆处理
* @copyright		vehicle, 2010
* @author			李少杰
* @create date		2010.07.24
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/
$act = $GLOBALS["all"]["operate"];

$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
$searchfil = $_REQUEST['searchField']; // get the direction
$searchstr = $_REQUEST['searchString']; // get the direction

$par = $_REQUEST["par"];
$child = $_REQUEST["child"];

$identify_id = get_session('identify_id');//用户角色ID

if(!$sidx) $sidx =1;

switch($act)
{
	case "list":			//加载车辆管理的html页面
		echo $db->display(null,"list");
		break;
	case "list_data":		//车辆管理html中，js文件会加载这个case，取得并输出数据
		$vehicle = new Vehicle();
		$count = $vehicle->get_vehicle_count();//本公司车辆总数

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;

		//得到查询条件
		if(empty($searchfil) or $searchstr == '')
			$wh = 'where 1=1 ';
		else
		{
			$type = $vehicle->get_type($searchfil);
			$wh = "where 1=1 ";
			//翻译serchstr
			switch($searchfil)
			{
				case "vehicle_group_id":
					$tra = new Translate("vehicle_group","id");
					$vgroup = $tra->get_all_data();
					foreach($vgroup as $key=>$gro)
					{
						if($gro['name'] == $searchstr)
						{
							$searcharr[] = $key;		//有可能在车辆组表中存在两个相同的name，所以用数组存储
						}
					}
					if(is_array($searcharr))
					{
						$searchstr = " and ".$searchfil." in (".implode(",",$searcharr).")";
						$type = "RAW";
						//file_put_contents("a.txt",$searchstr);
					}
					break;
				case "driver_id":
					$tra = new Translate("driver_manage","id");
					$driver = $tra->get_all_data();
					foreach($driver as $key=>$dri)
					{
						if($dri['name'] == $searchstr)
						{
							$searcharr[] = $key;
						}
					}
					if(is_array($searcharr))
					{
						$searchstr = " and ".$searchfil." in (".implode(",",$searcharr).")";
						$type = "RAW";
					}
					break;
				case "type_id":
					$tra = new Translate("vehicle_type_manage","id");
					$type = $tra->get_all_data();
					foreach($type as $key=>$typ)
					{
						if($typ['name'] == $searchstr)
						{
							$searcharr[] = $key;
						}
					}
					if(is_array($searcharr))
					{
						$searchstr = " and ".$searchfil." in (".implode(",",$searcharr).")";
						$type = "RAW";
					}
					break;
				case "alert_state":
					$xml = new Xml("vehicle_manage","alert_state");
					$xmldata = $xml->get_array_xml();
					$data = array_flip($xmldata);
					$searchstr = $data[$searchstr];
					break;
			}
			$searchstr = $db->prepare_value($searchstr,$type);
			if($type == 'INT')	//----用=号
			{
				$wh .= "and ".$searchfil." = ".$searchstr;
			}
			else if($type == 'RAW')	//----用in
			{
				$wh .= $searchstr;
			}
			else	//----用like
			{
				$searchstr = str_replace("'","",$searchstr);
				$wh .= "and ".$searchfil." like '%".$searchstr."%'";
			}
		}
		//file_put_contents("a.txt",$wh);
		//得到所有车辆
		$result = $vehicle->get_all_vehicles($wh,$sidx,$sord,$start,$limit);

		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;

		foreach($result as	$key => $val)
		{
			//对指定字段进行翻译
			$vehicle2	= new Vehicle($val['id']);
			$vehicle_group_name = $vehicle2->get_data("vehicle_group_name");
			$driver_name = $vehicle2->get_data("driver_name");
			$type_name = $vehicle2->get_data("type_name");
			$alert_state = $vehicle2->get_data("v_alert_state");
			$response->rows[$key]['id']=$val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['number_plate'],
																					$val['gps_number'],$vehicle_group_name,
																					$driver_name,$type_name,$val['color'],
																					$val['next_AS_date']/*,"<a href='#' style='color:#0099FF;text-decoration:none;' id=".$val['id']."  onclick='change_driver(".$val['id'].")'>更改>></a>"*/
																					//$val['backup1'],$val['backup2'],
																					//$val['backup3'],$val['backup4'],$val['create_id'],
																					//$val['create_time'],$val['update_id'],$val['update_time']
																					,$val['gps_index_id'],$val['vehicle_group_id'],$val['type_id'],$val['driver_id']
																					);
		}

		//打印json格式的数据
		echo json_encode($response);
		break;
		
	case "operate":		//车辆修改、添加、删除
		$oper = $_REQUEST['oper'];
		$vehicle = new Vehicle();
			
		$type_id = $_REQUEST['type_id'];
		$vehicle_group_id = $_REQUEST['vehicle_group_id'];
		$driver_id = $_REQUEST['driver_id'];
		$gps_index_id = $_REQUEST['gps_index_id'];
		$gps_id = $_REQUEST['gps_id'];
			
		$arr["number_plate"] = $db->prepare_value($_REQUEST['number_plate'],"VARCHAR");	
		$arr['gps_index_id'] = $db->prepare_value($gps_index_id,"INT");
		$arr['gps_id'] = $db->prepare_value($gps_id,"CHAR");
		$arr["company_id"] = $db->prepare_value(get_session("company_id"),"INT");
		$arr["vehicle_group_id"] = $db->prepare_value($vehicle_group_id,"INT");
		$arr["driver_id"] = $db->prepare_value($driver_id,"INT");
		$arr["type_id"] = $db->prepare_value($type_id,"INT");
		$arr["color"] = $db->prepare_value($_REQUEST['color'],"VARCHAR");

		if($_REQUEST['next_AS_date']=="" || $_REQUEST['next_AS_date']==null){
			$next_as_date = 'null'; //如果年检时间为空则添加默认的
			$arr["next_AS_date"] = $next_as_date;
		}else{
			//去除时间后面的时分秒,只保留年月日
			$next_date = explode(" ",$_REQUEST['next_AS_date'],2);
			$next_as_date = $next_date[0];
			$arr["next_AS_date"] = $db->prepare_value($next_as_date,"VARCHAR");
		}
		
		$vehicle = new Vehicle($_REQUEST['id']);
		switch($oper)
		{
			case "add":		//增加
				$new_vehicle_id = $vehicle->add_vehicle($arr);
				if($new_vehicle_id){
					$vehicle->change_gps_state($_REQUEST['gps_index_id']);//设置GPS设备号为使用中
					if($driver_id!="" && $driver_id!=false){
						$parms["driver_id"]	= $GLOBALS['db']->prepare_value($driver_id,"INT");
						$parms["vehicle_id"]= $GLOBALS['db']->prepare_value($new_vehicle_id,"INT");
						$vehicle->set_authority($parms); //添加车辆时给车辆授权驾驶员
					}
					echo "success";
				}else{
					echo "fail";
				}
				break;
			case "edit":		//修改
				$vehicle->edit_vehicle($arr); //修改车辆
				$gps_state = $vehicle->get_gps_state($gps_index_id); //查询gps设备号是否被使用
				
				//如果GPS状态为0,则设置为1即使用中
				if($gps_state==0){
					$vehicle->change_gps_state($gps_index_id);
				}
				
				$old_gps_id = $_REQUEST['old_gps_id'];//获取以前使用的GPS设备号
				if($old_gps_id!=false && $old_gps_id!=""){
					$vehicle->remove_gps_state($old_gps_id);//解除该GPS设备号
				}
				echo "success";
				break;
			case "del":		//删除
				if($vehicle->del_vehicle($arr)){
					$crrute_gps_id = $vehicle->data['gps_index_id'];//获取该 车辆的GPS设备号
					$vehicle->remove_gps_state($crrute_gps_id);//设置设备号为未使用
					$crrute_driver_id = $vehicle->data['driver_id'];//获取该车辆的驾驶员ID
					$vehicle->remove_vehicle_driver($_REQUEST['id'],$crrute_driver_id);//解除车辆与驾驶员的关系 
					echo json_encode(array('success'=>true,'errors'=>'删除成功!'));
				}else{
					exit(json_encode(array('success'=>false,'errors'=>'删除失败!')));
				}
				break;
		}
		break;
	case "select":		//下拉列表
		$p = $_REQUEST["p"];		//获得需要翻译的字段
		$vehicle = new Vehicle();
		switch($p)
		{
			case "vehicle_group_id"://车辆组
				$html = $vehicle->get_select("vehicle_group","name");
				break;
			case "driver_id"://驾驶员
				//$html = $vehicle->get_select("driver_manage","name");
				$html = $vehicle->get_select_driver("driver_manage","name",$_REQUEST['vehicle_id']);
				break;
			case "type_id"://类型
				$html = $vehicle->get_select("vehicle_type_manage","name");
				break;
			case "gps_number"://gps设备号
				$html = $vehicle->get_select_gps("gps_equipment","gps_number");
				break;
			case "alert_state"://告警状态
				if(!$par or !$child)
				{
					$par = "vehicle_manage";
					$child = "alert_state";
				}
				$xml = new Xml($par,$child);
				$html = $xml->get_html_xml();
				break;
		}
		echo $html;
		break;
		
	case "change_driver": //更改驾驶员
		$vehicle_id = $_REQUEST['vehicle_id'];//车辆ID
		$driver_id = $_REQUEST['driver_id'];//驾驶员ID
		$vehicle = new Vehicle();
		$result = $vehicle->change_driver($vehicle_id,$driver_id);//更换驾驶员
		if($result){
			echo "ok";
		}else{
			echo "fail";
		}
		break;
}


?>
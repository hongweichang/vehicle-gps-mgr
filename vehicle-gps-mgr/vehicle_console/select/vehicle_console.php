<?php
/** 
 * 用户处理
 * @copyright		秦运恒, 2010
 * @author			叶稳
 * @create date		2010.07.30
 * @modify			修改人
 * @modify date		修改日期
 * @modify describe	修改内容
 */
$act = $GLOBALS ["all"] ["operate"];

$page = $_REQUEST ['page']; // get the requested page
$limit = $_REQUEST ['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST ['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST ['sord']; // get the direction
$vehicleIds = $_REQUEST['vehicleIds'];  //获取车辆ID集合  

$company_id = get_session("company_id");  //获取公司ID
if (! $sidx)
	$sidx = 1;

	$vehicle_console = new vehicle_console ();
	
switch ($act) {
	case "select" ://选择车辆		
		$str="";		
		$arrayID=$_REQUEST ['array_ID'];
		$vehicles_request = explode(",",$vehicleIds); //获取首页地图上显示的所有车辆ID,生成数组
		
        /**获取所有车辆组*/
		$vehicle_group = $vehicle_console->get_all_vehicle_group ($company_id);
		
		/**
		 * 手动配置未分组的车辆组信息
		 */
		$vehicle_group_noset[0]=-1;
		$vehicle_group_noset[1]="未设置";
		
		
		$vehicle_group[count($vehicle_group)-1] = $vehicle_group_noset;//将未分组的车辆组加到所有车辆组里面
		
		/**遍历车辆组，生成车辆组标题*/

		$str = "<link type='text/css' href='/css/vehicle_console.css'  media='screen' rel='stylesheet' /><div class='vehicle_status_font'><ul>";

		foreach ( $vehicle_group as $value ) {

			$str = $str . "<li class='vehicle_status_font'><a href='#tabs" . $value [0] . "'><span>" . $value [1] . "</span></a></li>";

		}
		$str = $str."</ul></div>";
		
		$vehicle_list = explode(",",$arrayID);
		$is_selected = "";
		
		/*遍历车辆组，显示车辆*/
		foreach ( $vehicle_group as $values ) {
			$vehicles = $vehicle_console->get_group_vehicle ( "where vehicle_group_id=" . $values [0],$company_id);//查询该组的所有车辆
			$str = $str . "<div class='vehicle_status_font' id='tabs" . $values [0] . "'>".
							"<input type='checkbox'  value=" . $values [0] . " name='selectall' class='selectall' id='selectall" . $values [0] . 

							"'/><span class='table_select_title'>选择本组车辆</span>
							<div class='table_div'><table border='1' width='600' height='25' bordercolor='#CCCCCC' cellpadding='0' cellspacing='0' 
								   class='table vehicle_status_font' >";

			
			$count = count($vehicles);  //获取该组车辆总数
			$rows = $count/6;  //每行显示六辆车辆
			$exat_rows = round($rows);//总共显示多少行

			/*精确行数*/
			if($exat_rows<$rows){
    			$exat_rows = $exat_rows+1; 
			}
			
			/*遍历生成车辆显示*/
			for($j = 0;$j<$exat_rows;$j++){
		   		 $str = $str . "<tr>";
		   		 /*判断是否是最后一行*/
		   		 if($j==$exat_rows-1){
		   		 	for($m = $j*6;$m<$count;$m++){
		   		 		
		   		 	(in_array($vehicles[$m][0],$vehicle_list)? $is_selected = "checked=true" : $is_selected = "");
		   		 	/*判断GPRS是否在线*/
		   		 	if($vehicles[$m]['gprs_status']==1){
		   		 		$is_ok=0;
						for($k = 0;$k<count($vehicles_request);$k++){
							if($vehicles[$m][0]==$vehicles_request[$k]){
								$str = $str . "<td class='table_td'><input type='checkbox'".$is_selected.

		   		 							" checked='checked' class='vehicle' name='" . $values [0] . "' 
											value='".$vehicles[$m][0]."'/>" . $vehicles [$m][1]."</td>" ;
								$is_ok=1;
							}
						}
						if($is_ok==0){
		   		 			$str = $str . "<td class='table_td'><input type='checkbox'".$is_selected.

		   		 							" class='vehicle' name='" . $values [0] . "' 
											value='".$vehicles[$m][0]."'/>" . $vehicles [$m][1]."</td>" ;
						}
		   		 	}else{

		   		 		$str = $str . "<td class='table_td'><input type='checkbox' disabled />" .

		   		 							 $vehicles [$m][1]."</td>" ;
		   		 		}
		  	 		}
		    			$str = $str."</tr>";
		   		 }else{		   		 
		   		 for($m = $j*6; $m<($j+1)*6;$m++){
		   		 	
		   		 	(in_array($vehicles[$m][0],$vehicle_list)? $is_selected = "checked=true" : $is_selected = "");	 		
		   		 	/*判断GPRS是否在线*/
		   		 	if($vehicles[$m]['gprs_status']==1){
		   		 		$is_right=0;
						for($k = 0;$k<count($vehicles_request);$k++){
							if($vehicles[$m][0]==$vehicles_request[$k]){
								$str = $str . "<td class='table_td'><input type='checkbox'".$is_selected.

		   		 							" checked='checked' class='vehicle' name='" . $values [0] . "' 
											value='".$vehicles[$m][0]."'/>" . $vehicles [$m][1]."</td>" ;
								$is_right=1;
							}
						}
		   		 	if($is_right==0){
						$str = $str . "<td class='table_td'><input type='checkbox' ".$is_selected.
	
											"  class='vehicle' name='" . $values [0] . "' 
			
							value='".$vehicles[$m][0]."'/>" . $vehicles [$m][1]."</td>" ;
		   		 		}
		   		 	}else{

		   		 		$str = $str . "<td class='table_td'><input type='checkbox' disabled />" .

		   		 							 $vehicles [$m][1]."</td>" ;
		   		 		}
		  	 		} 
		    		$str = $str."</tr>";
				}
			}
			$str = $str . "</table></div></div>";
		}

		$arr ['vehicle_group_data'] = $str;
		echo $db->display ( $arr, "select" );//输出数据到页面

		break;
	case  "get_vehicle_position":  //获取请求车辆定位信息
		
		//导入数据映射文件解析类
		require_once("include/data_mapping_handler.php");
		
		//查询车辆集合定位信息、速度颜色返回数据
		$vehicle = $vehicle_console->get_vehicles($vehicleIds,$company_id);
		//创建XML解析对象
		$xml_handler =  new Data_mapping_handler($GLOBALS["all"]["BASE"]."xml/color.xml");
	
		//数组长度
		$length = count($vehicle);
		$ve_status = new Vehicle_status(); 
		
		for($row=0;$row<$length;$row++){   
				
				//获取车图标路径
				if(!isset($vehicle[$row][4])){
					$vehicle[$row][4] = "images/vehicle/gray";
				}else{
					$vehicle[$row][4] = str_ireplace("/west.png","",$xml_handler->getTextData("color","#".$vehicle[$row][4]));	
				} 
				$lon = $ve_status->exact_lon($ve_status->around($vehicle[$row][1],0)); //经度
				$lat = $ve_status->exact_lat($ve_status->around($vehicle[$row][2],0));//纬度
				
				$vehicle[$row][1] = $lon; 
				$vehicle[$row][2] = $lat;
				 
				$vehicle[$row][11] = $ve_status->get_location_desc($lon/100000,$lat/100000); //地址
			   //获取当前车方向
			   $cur_direction = $vehicle[$row][3];
			   //分解度数换为方向
			   $vehicle[$row][3] = resolvingDirection($cur_direction); 
		}
	    echo json_encode($vehicle); 
		break;
}

?>
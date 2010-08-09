<?php
/** 
* 首页处理
* @copyright		秦运恒, 2010
* @author			叶稳
* @create date		2010.08.07
* @modify			修改人 
* @modify date		修改日期
* @modify describe	修改内容
*/
$act = $GLOBALS["all"]["operate"];
 
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$company_id = get_session("company_id"); //获取当前公司ID 
if(!$sidx) $sidx =1;

switch($act)
{
	case "list":		
		$vehicle_console = new vehicle_console ();
		
		$arr['url_manage'] = URL('user','user.php','manage_list'); 
		$arr['url setup']=URL('setting','setting.php','get_setting');
		$arr['url_logout'] = URL('user','user.php','logout');
		$arr['host']= "http://".$_SERVER ['HTTP_HOST'];
		$arr['map_point']= null; //地图点
		$scope = "var points = new Array();";
		
		$vehicle = $vehicle_console->company_all_vehicle($company_id);

		//导入数据映射文件解析类
		require_once("include/data_mapping_handler.php");
		 
		//创建XML解析对象
		$xml_handler =  new Data_mapping_handler($GLOBALS["all"]["BASE"]."xml/color.xml");
		
		foreach($vehicle as $value){
		 $direction = resolvingDirection($value[6]);
		 $file_path = str_ireplace("west.png","",$xml_handler->getTextData("color","#".$value[9])); 
		 
		 $arr['map_point'] .= " marker1 =new LTMarker(new LTPoint(round(".$value[3]."),round(".$value[4].")),".
												 	  "new LTIcon('".$arr['host']."/".$file_path."/".$direction.".png'));".
		 					  "addInfoWin(marker1,'车牌号:".$value[0]."');map.addOverLay(marker1);";
		 
		 $scope .= "points.push( new LTPoint(round(".$value[3]."),round(".$value[4].") ) );";
		}
		
		$arr['map_point'] .= $scope."map.getBestMap(points);";
		echo $db->display($arr,"list");
		break;
}


?>
<?php
/** 
* 信息查询
* @copyright		秦运恒, 2010
* @author			郭英涛
* @create date		2010.08.02
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/

$act = $GLOBALS["all"]["operate"];

switch($act)
{
	case "main":	//填写信息内容页面
		echo $GLOBALS['db']->display(null,$act);
		break;
	case "trace":
		require_once 'traceInfo.php';
	
		$parser = new position_parser("tracedata/2010080312.log",35241,"13300920355");
		$datalist = $parser->getDataList();
		
		$point_info = array();
		$trace_info = array();
		
		foreach($datalist as $k=>$v)
		{
			$point_info[0]= $v->longitude;
			$point_info[1]= $v->latitude;
			$point_info[2]= resolvingDirection($v->direction); 
			$point_info[3]= $v->speed;

			array_push($trace_info,$point_info);
		}
		
		echo json_encode($trace_info);
		break;
	break;
}
?>
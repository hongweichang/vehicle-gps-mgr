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
	
		$parser = new position_parser("D:\\Work\\vehicle-gps-mgr\\trunk\\file\\2010080312.log",35241,"13300920355");
		$datalist = $parser->getDataList();
		
		$trace_info = "";
		
		foreach($datalist as $k=>$v)
		{
			$trace_info = $trace_info."{".$v->longitude.",".$v->latitude.",".resolvingDirection($v->direction).",".$v->speed."},";
		}
		
		$trace_info = trim($trace_info,',');
		$trace_info = "{".$trace_info."}";
		
		echo json_encode($trace_info);
		break;
	break;
}
?>
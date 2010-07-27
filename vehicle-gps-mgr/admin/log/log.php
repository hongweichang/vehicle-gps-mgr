<?php
/** 
* 日志
* @copyright		company, 2010
* @author			苏元元
* @create date		2010.07.26
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/
$act = $GLOBALS["all"]["operate"];

$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction

if(!$sidx) $sidx =1;

switch($act)
{
	case "list":		//模拟测试
		//数据页面
		include("include/templates.php");
		echo $GLOBALS['db']->display(null,$act);
		break;

	case "list_data":
			$log	= new Log();	//模拟打印润色后的字符串值
			$count = $log->get_all_count();

			if( $count >0 ) {
				$total_pages = ceil($count/$limit);
			} else {
				$total_pages = 0;
			}
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit;
			if ($start<0) $start = 0;

			$rtn = $log->get_all_logs($wh,$sidx,$sord,$start,$limit);

			$responce->page	= $page;
			$responce->total = $total_pages;
			$responce->records = $count;

			foreach($rtn as	$key=>$rtn_log)
			{
				$responce->rows[$key]['id']=$rtn_log['id'];
				$responce->rows[$key]['cell']=array($rtn_log['id'],$rtn_log['user_id'],$rtn_log['company_id'],$rtn_log['time'],$rtn_log['description']);
			}
			//打印json格式的数据
			echo json_encode($responce);

		break;

	//测试例
	case "add_log":

		$log = new Log();
		$log->write_log("aaaa");
		break;
}
?>
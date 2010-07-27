<?php
/** 
* 人员管理
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

			$driver	= new Driver();	//模拟打印润色后的字符串值
			$count = $driver->get_all_count();

			if( $count >0 ) {
				$total_pages = ceil($count/$limit);
			} else {
				$total_pages = 0;
			}
			if ($page > $total_pages) $page=$total_pages;
			$start = $limit*$page - $limit;
			if ($start<0) $start = 0;

			$rtn = $driver->get_all_drivers($wh,$sidx,$sord,$start,$limit);

			$responce->page	= $page;
			$responce->total = $total_pages;
			$responce->records = $count;

			foreach($rtn as	$key=>$rtn_driver)
			{
				$responce->rows[$key]['id']=$rtn_driver['id'];
				$responce->rows[$key]['cell']=array($rtn_driver['id'],$rtn_driver['name'],$rtn_driver['driving_licence_id'],$rtn_driver['sex'],$rtn_driver['birthday'],$rtn_driver['company_id'],$rtn_driver['career_time'],$rtn_driver['job_number'],$rtn_driver['driving_type'],$rtn_driver['mobile'],$rtn_driver['driving_state'],$rtn_driver['phone_email'],$rtn_driver['address'],$rtn_driver['create_id'],$rtn_driver['create_time'],$rtn_driver['update_id'],$rtn_driver['update_time']);
			}

			//打印json格式的数据
			echo json_encode($responce);

		break;
}
?>
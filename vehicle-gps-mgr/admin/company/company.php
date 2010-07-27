<?php
/** 
* 公司管理
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

		include("include/templates.php");
		echo $GLOBALS['db']->display(null,$act);
		break;

	case "list_data":

		$comp	= new Company();	//模拟打印润色后的字符串值

		$count = $comp->get_all_count();

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;

		//得到所有公司
		$rtn = $comp->get_all_companys($wh,$sidx,$sord,$start,$limit);

		$responce->page	= $page;
		$responce->total = $total_pages;
		$responce->records = $count;

		foreach($rtn as	$key=>$rtn_company)
		{
			$responce->rows[$key]['id']=$rtn_company['id'];
			$responce->rows[$key]['cell']=array($rtn_company['id'],$rtn_company['login_id'],$rtn_company['name'],$rtn_company['register_num'],$rtn_company['area1'],$rtn_company['area2'],$rtn_company['area3'],$rtn_company['description'],$rtn_company['contact'],$rtn_company['address'],$rtn_company['zipcode'],$rtn_company['tel'],$rtn_company['fax'],$rtn_company['mobile'],$rtn_company['email'],$rtn_company['site_url'],$rtn_company['state'],$rtn_company['service_start_time'],$rtn_company['service_end_time '],$rtn_company['charge_standard'],$rtn_company['create_id'],$rtn_company['create_time'],$rtn_company['update_id'],$rtn_company['update_time']);
		}

		//打印json格式的数据
		echo json_encode($responce);

		break;
}
?>
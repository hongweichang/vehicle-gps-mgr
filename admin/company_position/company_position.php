<?php
/** 
* 公司标注管理
* @copyright		user, 2010
* @author			赵将伟
* @create date		2010.09.10
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

if(!$sidx) $sidx =1;

switch($act)
{
	case "list":			//加载公司标注管理的html页面
		
		//include("include/templates.php");
		//$param['kobe']='kobe';
		echo $GLOBALS['db']->display(null,$act);
		break;
		
	case "get_company_position":  //添加所有公司标注内容
		require_once("home_page.class.php");
		$home_page = new home_page();
		$companies = $home_page->get_company_position();
		
		$count = count($companies);
		if( $count >0 ) {
			$total_pages = ceil($count/$limit); //获取总页数
		} else {
			$total_pages = 0;
		}
		
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;
		
		/*if(empty($searchfil) or empty($searchstr))
			$wh = "";
		else
		{
			$type = $home_page->get_type($searchfil);
			$searchstr = $db->prepare_value($searchstr,$type);
			$wh = "where ".$searchfil." = ".$searchstr;
		}*/

	
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach($companies as $key => $val){
			$response->rows[$key]['id'] = $val['id'];
			$response->rows[$key]['cell']=array($val['id'],$val['name'],
											"<a href='#' id=".$val['id']." onclick='delete_position(".$val['id'].")'>删除>></a>");
		}
		echo json_encode($response);
		break;
}

?>
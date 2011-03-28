<?php
/** 
 * 信息查询
 * @copyright		秦运恒, 2011
 * @author			郭英涛
 * @create date		2011.03.28
 * @modify			修改人
 * @modify date		修改日期
 * @modify describe	修改内容
 */

$act = $GLOBALS ["all"] ["operate"];


$company_id = get_session("company_id"); //得到公司id


switch ($act) {
	case "main" : //填写信息内容页面vehicle_list 402
		echo $GLOBALS ['db']->display ( null, $act );
		break;
		
	case "manage" : //填写信息内容页面vehicle_list 402
		echo $GLOBALS ['db']->display ( null, $act );
		break;

	case "set_vehicle_region" : //填写信息内容页面vehicle_list 402
		echo $GLOBALS ['db']->display ( null, $act );
		break;		
}
?>
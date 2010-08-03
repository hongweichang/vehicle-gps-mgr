<?php
/** 
* 信息查询
* @copyright		秦运恒, 2010
* @author			郭英涛
* @create date		2010.08.03
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
	break;
}
?>
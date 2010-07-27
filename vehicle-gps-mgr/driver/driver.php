<?
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

switch($act)
{
	case "list":		//模拟测试

		$driver	= new Driver();	//模拟打印润色后的字符串值

		$rtn = $driver->get_all_drivers();


		$responce->page	= 2;
		$responce->total = 2;
		$responce->records = 3;

		foreach($rtn as	$key=>$rtn_driver)
		{
			$responce->rows[$key]['id']=$rtn_driver['id'];
			$responce->rows[$key]['cell']=array($rtn_driver['id'],$rtn_driver['name'],$rtn_driver['driving_licence_id'],$rtn_driver['sex'],$rtn_driver['birthday'],$rtn_driver['company_id'],$rtn_driver['career_time'],$rtn_driver['job_number'],$rtn_driver['driving_type'],$rtn_driver['mobile'],$rtn_driver['driving_state'],$rtn_driver['phone_email'],$rtn_driver['address'],$rtn_driver['create_id'],$rtn_driver['create_time'],$rtn_driver['update_id'],$rtn_driver['update_time']);
		}

		$responce->userdata['amount'] =	100;
		$responce->userdata['tax'] = 100;
		$responce->userdata['total'] = 100;
		$responce->userdata['name']	= 'Totals:';

		file_put_contents(dirname(__FILE__)."\driver_cache.php","<? echo '".json_encode($responce)."' ?>");

		echo $GLOBALS['db']->display(null,$act);
		break;

	case "list_data":		//模拟测试
		

		break;
}
?>
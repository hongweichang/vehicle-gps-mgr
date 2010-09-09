<?php
/**
*首页处理类
* @copyright 	  秦运恒, 2010
* @author 　　叶稳
* @create date 　 2010.09.08
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   
* @todo			  n/a
*/
class home_page extends BASE{
	private $tablename_company_position ="company_position";
	
	function company_position($position_data){
		
		$company_position['company_id'] = $GLOBALS['db']->prepare_value($position_data['company_id'],"INT");
		$company_position['name'] = $GLOBALS['db']->prepare_value($position_data['name'],"VARCHAR");
		$company_position['longitude'] = $GLOBALS['db']->prepare_value($position_data['longitude'],"INT");
		$company_position['latitude'] = $GLOBALS['db']->prepare_value($position_data['latitude'],"INT"); 
		 
		return $GLOBALS['db']->insert_row($this->tablename_company_position,$company_position);
	}
}

?>
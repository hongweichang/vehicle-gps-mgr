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
	
	function get_company_position(){
		$company_id = get_session("company_id");
		$sql = "select * from ".$this->tablename_company_position." where company_id=".$company_id;
		$result = $GLOBALS['db']->query($sql);
		return $result;
	}
	
	/**
	 * 删除公司标注信息
	 * @param $position_id 公司标注ID
	 */
	function delete_company_position($position_id){
		$sql = "delete from ".$this->tablename_company_position." where id=".$position_id;
		$result = $GLOBALS['db']->query($sql);
		return $result;
	}
	
	/**
	 * 根据经纬度查找标注点信息
	 */
	function find_company_position($longitude,$latitude){
		$sql = "select co.contact,zipcode,tel,fax,email,site_url,address,cp.id from ".$this->tablename_company_position.
				" cp left join company co on cp.company_id=co.id where cp.longitude=".$longitude." and cp.latitude=".$latitude;
		$result =  $GLOBALS['db']->query_once($sql);
		return $result;
	}
}

?>
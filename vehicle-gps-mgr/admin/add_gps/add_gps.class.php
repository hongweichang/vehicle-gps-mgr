<?php
/**
* 	添加GPS设备类
*
*
* @copyright 	  vehicle, 2010
* @author 　　赵将伟
* @create date 　 2010.10.19
* @modify  　　　 n/a
* @modify date　　n/a
* @todo			  n/a
*/
class add_gps extends BASE
{
	//	以下为每个类都必须有的变量
	public $tablename = "gps_equipment";
	public $data = false;                //数据
	public $data_list = false;					 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息
	
	private $id = false;		//主键ID
	
	/**
	*		构造函数
	*		@param $id 
	*		@return no
	*/
	function add_gps($id=false)
	{
		if($id && !empty($id))
		{
			$this->id = $id;
			$this->retrieve_data();
		}
	}
	
   /**
	*		查询得到指定系统信息
	*		@param $id 
	*		@return no
	*/
	private function retrieve_data()
	{
		$this->sql = sprintf("select * from %s where id = %d",$this->tablename,$this->id);
		if ($this->data = $GLOBALS["db"]->query_once($this->sql))
			return $this->data;
		else
			return false;
	}
	
	/**
	*		得到指定字段类型
	*		@param $searchfield 字段名
	*		@return mixed
	*/
	function get_type($searchfield=false)
	{
		
		if(!$searchfield)
		{
			$this->message = 'error,Searchfield is not exists!';
			return false;
		}
		$type = $GLOBALS["db"]->get_field_type($this->tablename,$searchfield);
		if(!$type)
		{
			$this->message = 'error,Get Field type failed!';
			return false;
		}
		return $type;
	}
	
	/**
	 *  添加GPS设备信息
	 *  @gps gps设备信息
	 */
	function add_gps_number($gps){
		if(!$gps)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		$result = $GLOBALS['db']->insert_row("gps_equipment",$gps);
		if(!$result)
		{
			$this->message = "error,insert data failed!";
			return false;
		}
		return true;
	}
	
	/**
	 * 查询公司所有的GPS设备
	 */
	function get_all_gps($company_id,$wh,$sidx,$sord,$start,$limit){
		$this->sql = "select * from ".$this->tablename." ".$wh." and company_id = ".$company_id." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}
	
	/**
	 * 查询业务员所有GPS设备
	 */
	function get_offer_gps($explorer_id,$wh,$sidx,$sord,$start,$limit){
		$sql = "select id from company where explorer_id = ".$explorer_id;
		$result = $GLOBALS['db']->query($sql);
		
		if($result){
			foreach($result as $key=>$value){
				$company_ids[$key] = $value[0];
			}
		}
		$company_ids = implode($company_ids,",");
		
		$this->sql = "select g.*,c.name company_name from gps_equipment g left join company c on g.company_id = c.id where g.company_id in(".$company_ids.") order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		$this->data = $GLOBALS['db']->query($this->sql);
		return $this->data;
	}
	
	/*
	 * 查询业务员所有GPS设备总数
	 */
	function get_offer_gps_count($explorer_id){
		$sql = "select id from company where explorer_id = ".$explorer_id;
		$result = $GLOBALS['db']->query($sql);
		
		if($result){
			foreach($result as $key=>$value){
				$company_ids[$key] = $value[0];
			}
		}
		$company_ids = implode($company_ids,",");
		
		$this->sql = "select count(id) from gps_equipment where company_id in(".$company_ids.")";
		$this->data = $GLOBALS['db']->query_once($this->sql);
		return $this->data[0];
	}	
	
	/*
	 * 获取gps设备号总数
	 * @param $gps_number GPS设备号
	 */
	function get_gps_number_count($gps_number)
	{
		$this->sql = "select count(id) from gps_equipment where gps_number=".$gps_number;
		$this->data = $GLOBALS["db"]->query_once($this->sql);
		return $this->data[0];
	}
	
	/**
	 * 查询所有GPS总数
	 */
	function get_count_gps($company_id){
		$this->sql = "select count(id) from gps_equipment where company_id=".$company_id;
		$this->data = $GLOBALS["db"]->query_once($this->sql);
		return $this->data[0];
	}
	
	/**
	 * 删除gps设备
	 * @gps_id ID
	 */
	function delete_gps($gps_id){
		$this->sql = "delete from gps_equipment where id=".$gps_id;
		return $result = $GLOBALS["db"]->query($this->sql);
	}
	
	/**
	 * 解除gps与车辆关联关系
	 */
	function remove_gps($gps_index_id){
		$this->sql = "update vehicle_manage set gps_index_id = null,gps_id = null where 
						gps_index_id=".$gps_index_id;
		$result = $GLOBALS['db']->query($this->sql);
		return $result;
	}
	
	/**
	 * 修改GPS设备
	 */
	function edit_gps($gps_number,$gps_id){
		$sql_gps = "update gps_equipment set gps_number=".$gps_number." where id=".$gps_id;
		$sql_vehicle = "update vehicle_manage set gps_id=".$gps_number." where gps_index_id=".$gps_id;
		$result_one = $GLOBALS['db']->query($sql_gps);
		$result_two = $GLOBALS['db']->query($sql_vehicle);
		if($result_one && $result_two){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 查询业务员所管辖的所有公司
	 */
	function get_companies($user_id){
		$this->sql = "select * from company where explorer_id=".$user_id;
		return $result = $GLOBALS['db']->query($this->sql);
	}

}



?>
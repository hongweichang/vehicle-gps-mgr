<?php
/**
* 	车辆管理类
*
*
* @copyright 	  vehicle, 2010
* @author 　　李少杰
* @create date 　 2010.07.24
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   2010.07.24 18:45	文件生成
*/
class Vehicle extends BASE
{
	//	以下为每个类都必须有的变量
	public $tablename = "vehicle_manage";
	public $data = false;                //数据
	public $data_list = false;					 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息
	
	private $id = false;		//车辆管理表主键ID
	private $tablename_vehicle_group = "vehicle_group";		//车辆分组表
	//private $tablename_vehicle_type = "vehicle_type_manage";		//车辆类型表
	
	/**
	*		构造函数
	*		@param $id 
	*		@return no
	*/
	function Vehicle($id=false)
	{
		if($id && !empty($id))
		{
			$this->id = $id;
			$this->retrieve_data();
		}
	}
	
	/**
	*		查询得到指定车辆信息
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
	*	添加车辆
	*	@param $vehicle
	*	@return boolean
	*/
	function add_vehicle($vehicle)
	{
		if(!$vehicle)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		$result = $GLOBALS['db']->insert_row($this->tablename,$vehicle);
		if(!$result)
		{
			$this->message = "error,insert data failed!";
			return false;
		}
		//return true;
		return $result;
	}
	
	/**
	 * 	添加车辆时给车辆授权驾驶员
	 * 	@param  $vehicle_id 车辆ID $driver_id 驾驶员ID
	 */
	function set_authority($vehicle_driver){
		if(!$vehicle_driver){
			$this->message = "error,object must be not empty!";
			return false;
		}
		if(!$GLOBALS['db']->insert_row("driver_vehicle",$vehicle_driver))
		{
			$this->message = "error,insert data failed!";
			return false;
		}
		return true;
	} 
	
	/**
	*	修改车辆
	*	@param $vehicle
	*	@return boolean
	*/
	function edit_vehicle($vehicle)
	{
		if(!$vehicle)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		//添加主键ID
		$vehicle['id'] = $this->id;
		if(!$GLOBALS['db']->update_row($this->tablename,$vehicle,"id"))
		{
			$this->message = "error,edit data failed!";
			return false;
		}
		return true;
	}
	
	/**
	*	删除车辆
	*	@param $vehicle
	*	@return boolean
	*/
	function del_vehicle($vehicle)
	{
		if(!$vehicle)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		$this->sql = sprintf("delete from %s where id = %d",$this->tablename,$this->id);
		if(!$GLOBALS['db']->query($this->sql))
		{
			$this->message = "error,delete data failed!";
			return false;
		}
		return true;
	}
	
	/**
	 *  解除车辆与驾驶员的关系 
	 *  @param $vehicle_id 车辆ID $driver_id 驾驶员ID
	 */
	function remove_vehicle_driver($vehicle_id){
		$this->sql = sprintf("delete from driver_vehicle where vehicle_id = %d",$vehicle_id);
		if(!$GLOBALS['db']->query($this->sql))
		{
			$this->message = "error,delete data failed!";
			return false;
		}
		return true;
	}
	
	/**
	 * 设置gps设备号为使用中
	 * @gps_id ID
	 * 
	 */
	function change_gps_state($gps_id){
		$this->sql = sprintf("update gps_equipment set state=1 where id=".$gps_id);
		if(!$GLOBALS['db']->query($this->sql))
		{
			$this->message = "error,delete data failed!";
			return false;
		}
		return true;
	}
	
	/**
	 * 设置gps设备号为未使用
	 * @gps_id ID
	 * 
	 */
	function remove_gps_state($gps_id){
		$this->sql = sprintf("update gps_equipment set state=0 where id=".$gps_id);
		if(!$GLOBALS['db']->query($this->sql))
		{
			$this->message = "error,delete data failed!";
			return false;
		}
		return true;
	}
	
	/**
	 * 查询gps设备号是否被使用
	 */
	function get_gps_state($gps_id){
		$this->sql = sprintf("select state from %s where id = %d","gps_equipment",$gps_id);
		if ($this->data = $GLOBALS["db"]->query_once($this->sql))
			return $this->data[0];
		else
			return false;
	}
	
	/**
	*	实体函数的render，车辆对指定的列名称（字符串）进行润色、翻译
	*	@param $col_name 列名称（字符串）
	*	@return $o  润色翻译后的数值
	*/
	function child_render($col_name)
	{
		switch ($col_name)
		{
			case "vehicle_group_name":
				$vehicle_group = new Vehicle_group($this->get_data("vehicle_group_id"));
				$value = $vehicle_group->get_data("name");
				break;
			case "driver_name":
				$driver = new Driver($this->get_data("driver_id"));
				$value = $driver->data["name"];
				break;
			case "type_name":
				$type = new Vehicle_type($this->get_data("type_id"));
				$value = $type->get_data("name");
				break;
			case "v_alert_state":
				$par = "vehicle_manage";
				$child = "alert_state";
				$xml  = new Xml($par,$child);
				$xmldata = $xml->get_array_xml();
				$value = $xmldata[$this->get_data("alert_state")];
				break;
		}
		return $value;
	}
	
	/**
	*	查询所有车辆数目
	*	@param null
	*	@return numeric or null
	*/
	function get_vehicle_count()
	{
		$this->sql = "select count(*) from ".$this->tablename." where company_id = ".get_session("company_id");
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}
	
	/**
	*		查询所有车辆
	*		@param $wh 条件 $sidx 字段 $sord 排序 $start&$limit 取值区间
	*		@return no
	*/
	function get_all_vehicles($wh="",$sidx="",$sord="",$start="",$limit="")
	{
		//$this->sql = "select * from ".$this->tablename." ".$wh." and vehicle_group_id in(select id from ".$this->tablename_vehicle_group." where company_id = ".get_session("company_id").") order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		//$this->sql = "select * from ".$this->tablename." ".$wh." and company_id = ".get_session("company_id")." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;;		
		$this->sql = "select vm.*,ge.gps_number from ".$this->tablename." vm left join gps_equipment ge on vm.gps_id = ge.id ".$wh." and vm.company_id = ".get_session("company_id")." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;;
		return $this->data_list = $GLOBALS["db"]->query($this->sql);
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
	*		得到外键对应的所有name选择下拉列表（有选定状态）
	*		@param $tablename 外键对应的表名 $fieldname 字段名
	*		@return mixed
	*/
	function get_select_driver($tablename,$fieldname,$vehicle_id)
	{
		//$this->sql = sprintf("select id,%s from %s where company_id = %d",$fieldname,$tablename,get_session("company_id"));
		//file_put_contents("a.txt",$this->sql);
		$this->sql = sprintf("select distinct dr.id,dr.%s from %s dr left join driver_vehicle  dv on dr.id = dv.driver_id
								 where (dv.vehicle_id = %d or dr.id not in (select driver_id from driver_vehicle)) and company_id = %d"
								,$fieldname,$tablename,$vehicle_id,get_session('company_id'));
		$result = $GLOBALS['db']->query($this->sql);
		$select = '<select id="driver_option">
								<option value="-1">请选择</option>
								';
		foreach($result as $temp)
		{
			$select .= "<option value='".$temp['id']."'>".$temp['name']."</option>";
		}
		$select .= '<select>';
		return $select;
	}
	
	/**
	*		得到外键对应的所有name选择下拉列表（有选定状态）
	*		@param $tablename 外键对应的表名 $fieldname 字段名
	*		@return mixed
	*/
	function get_select($tablename,$fieldname)
	{
		$select_id = false;
		if("vehicle_group" == $tablename){
			$select_id = "group_options";
		}else if("driver_manage" == $tablename){
			$select_id = "driver_options";
		}else if("vehicle_type_manage" == $tablename){
			$select_id = "type_options";
		}
		
		$this->sql = sprintf("select id,%s from %s where company_id = %d",$fieldname,$tablename,get_session("company_id"));
		file_put_contents("a.txt",$this->sql);
		$result = $GLOBALS['db']->query($this->sql);
		$select = '<select id='.$select_id.'>
								<option value="-1">请选择</option>
								';
		foreach($result as $temp)
		{
			$select .= "<option value='".$temp['id']."'>".$temp['name']."</option>";
		}

		$select .= '<select>';
		return $select;
	}
	
	/**
	*		得到外键对应的所有gps设备号选择下拉列表（有选定状态）
	*		@param $tablename 外键对应的表名 $fieldname 字段名
	*		@return mixed
	*/
	function get_select_gps($tablename,$fieldname)
	{
		$this->sql = sprintf("select id,%s from %s where company_id = %d and state = 0",$fieldname,$tablename,get_session("company_id"));
		file_put_contents("a.txt",$this->sql);
		$result = $GLOBALS['db']->query($this->sql);
		$select = '<select id="gps_select">
								<option value="-1">请选择</option>
								';
		foreach($result as $temp)
		{
			$select .= "<option value='".$temp['id']."'>".$temp['gps_number']."</option>";
		}

		$select .= '<select>';
		return $select;
	}
	

	/**
	*		根据组ID 查出所有的车辆
	*		@param 
	*		@return array
	*/
	function get_all_vehicle($vehicle_group_id)
	{
		$this->sql = "select id,number_plate from ".$this->tablename." where vehicle_group_id = ".$vehicle_group_id;
		return $this->data_list = $GLOBALS["db"]->query($this->sql); 
	}
	
	/**
	 *  更换驾驶员
	 */
	function change_driver($vehicle_id,$driver_id){
		$this->sql = sprintf("update vehicle_manage set driver_id=".$driver_id." where id=".$vehicle_id);
		if(!$GLOBALS['db']->query($this->sql))
		{
			$this->message = "error,delete data failed!";
			return false;
		}
		return true;
	}
	
	/**
	 * 根据车辆表中的gps_id查出gps设备中的id号
	 * @param gps_id 车辆表中的gps_id
	 */
	function get_gps_id($gps_number){
		$this->sql = "select id from gps_equipment where gps_number = '".$gps_number."'";
		$this->data = $GLOBALS['db']->query_once($this->sql);
		return $this->data[0];
	}
	
	/**
	 * 根据id查出gps设备号
	 * @id gps_equipment表中的id
	 */
	function get_gps_number($gps_id){
		$this->sql = "select gps_number from gps_equipment where id = ".$gps_id;
		$this->data = $GLOBALS['db']->query_once($this->sql);
		return $this->data[0];
	}
}



?>
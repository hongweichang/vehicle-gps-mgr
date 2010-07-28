<?php
/**
* 	车辆管理类
*
*
* @copyright 	  Vehicle_group_group, 2010
* @author 　　李少杰
* @create date 　 2010.07.24
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   2010.07.24 18:45	文件生成
* @todo			  n/a
*/
class Vehicle_group extends BASE
{
	//	以下为每个类都必须有的变量
	public $tablename = "Vehicle_group";
	public $data = false;                //数据
	public $data_list = false;					 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息
	
	private $id = false;		//车辆管理表主键ID
	//private $tablename_Vehicle_group_group = "Vehicle_group_group";		//车辆分组表
	//private $tablename_Vehicle_group_type = "Vehicle_group_type_manage";		//车辆类型表
	
	/**
	*		构造函数
	*		@param $id 
	*		@return no
	*/
	function Vehicle_group($id=false)
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
	*	@param $vehicle_group
	*	@return boolean
	*/
	function add_vehicle_group($vehicle_group)
	{
		if(!$vehicle_group)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		if(!$GLOBALS['db']->insert_row($this->tablename,$vehicle_group))
		{
			$this->message = "error,insert data failed!";
			return false;
		}
		return true;
	}
	
	/**
	*	修改车辆组
	*	@param $vehicle_group
	*	@return boolean
	*/
	function edit_vehicle_group($vehicle_group)
	{
		if(!$vehicle_group)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		//添加主键ID
		$vehicle_group['id'] = $this->id;
		if(!$GLOBALS['db']->update_row($this->tablename,$vehicle_group,"id"))
		{
			$this->message = "error,edit data failed!";
			return false;
		}
		return true;
	}
	
	/**
	*	删除车辆组
	*	@param $vehicle_group
	*	@return boolean
	*/
	function del_vehicle_group($vehicle_group)
	{
		if(!$vehicle_group)
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
	*	实体函数的render，车辆组对指定的列名称（字符串）进行润色、翻译
	*	@param $col_name 列名称（字符串）
	*	@return $o  润色翻译后的数值
	*/
	function child_render($col_name)
	{
		switch ($col_name)
		{
			case "vehicle_group_red_name":
				//$value = '<font color="red">------test child_render:'.$this->get_data('number_plate').'</font>';
				break;
		}
		return $value;
	}
	
	/**
	*	查询所有车辆数目
	*	@param null
	*	@return numeric or null
	*/
	function get_vehicle_group_count()
	{
		$this->sql = "select count(*) from ".$this->tablename;
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}
	
	/**
	*		查询所有车辆
	*		@param $wh 条件 $sidx 字段 $sord 排序 $start&$limit 取值区间
	*		@return no
	*/
	function get_all_vehicle_groups($wh="",$sidx="",$sord="",$start="",$limit="")
	{
		$this->sql = "select * from ".$this->tablename." ".$wh." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
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
	
	
}



?>
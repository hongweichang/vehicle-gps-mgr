<?php
/**
* 	车辆组管理类
*
*
* @copyright 	  Vehicle_type, 2010
* @author 　　李少杰
* @create date 　 2010.07.24
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   2010.07.24 18:45	文件生成
* @todo			  n/a
*/
class Vehicle_type extends BASE
{
	//	以下为每个类都必须有的变量
	public $tablename = "vehicle_type_manage";
	public $data = false;                //数据
	public $data_list = false;					 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息
	
	private $id = false;		//车辆管理表主键ID
	
	/**
	*		构造函数
	*		@param $id 
	*		@return no
	*/
	function Vehicle_type($id=false)
	{
		if($id && !empty($id))
		{
			$this->id = $id;
			$this->retrieve_data();
		}
	}
	
	/**
	*		查询得到指定车辆组信息
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
	*	添加车辆组
	*	@param $vehicle_type
	*	@return boolean
	*/
	function add_vehicle_type($vehicle_type)
	{
		if(!$vehicle_type)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		if(!$GLOBALS['db']->insert_row($this->tablename,$vehicle_type))
		{
			$this->message = "error,insert data failed!";
			return false;
		}
		return true;
	}
	
	/**
	*	修改车辆组
	*	@param $vehicle_type
	*	@return boolean
	*/
	function edit_vehicle_type($vehicle_type)
	{
		if(!$vehicle_type)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		//添加主键ID
		$vehicle_type['id'] = $this->id;
		if(!$GLOBALS['db']->update_row($this->tablename,$vehicle_type,"id"))
		{
			$this->message = "error,edit data failed!";
			return false;
		}
		return true;
	}
	
	/**
	*	删除车辆组
	*	@param $vehicle_type
	*	@return boolean
	*/
	function del_vehicle_type($vehicle_type)
	{
		if(!$vehicle_type)
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
	*	实体函数的render，车辆对指定的列名称（字符串）进行润色、翻译
	*	@param $col_name 列名称（字符串）
	*	@return $o  润色翻译后的数值
	*/
	function child_render($col_name)
	{
		switch ($col_name)
		{
			case "abc":
				break;
		}
		return $value;
	}
	
	/**
	*	查询所有车辆组数目
	*	@param null
	*	@return numeric or null
	*/
	function get_vehicle_type_count()
	{
		$this->sql = "select count(*) from ".$this->tablename." and company_id = ".get_session("company_id");
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}
	
	/**
	*		查询所有车辆组
	*		@param $wh 条件 $sidx 字段 $sord 排序 $start&$limit 取值区间
	*		@return no
	*/
	function get_all_vehicle_types($wh="",$sidx="",$sord="",$start="",$limit="")
	{
		$this->sql = "select * from ".$this->tablename." ".$wh." and company_id = ".get_session("company_id")." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
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
	function get_select($tablename,$fieldname)
	{
		$this->sql = sprintf("select id,%s from %s",$fieldname,$tablename);
		//file_put_contents("a.txt",$this->sql);
		$result = $GLOBALS['db']->query($this->sql);
		$select = '<select>';
		foreach($result as $temp)
		{
			$select .= "<option value='".$temp['id']."'>".$temp['name']."</option>";
		}
		$select .= '<select>';
		return $select;
	}
}



?>
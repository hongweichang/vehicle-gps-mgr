<?php

/**
* 	人员管理类
*
*
* @copyright 	  company, 2010
* @author 　　	苏元元
* @create date 　 2010.07.24
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   2010.07.26
* @todo			  n/a
*/

class Driver
{

	//	以下为每个类都必须有的变量
	public $tablename = "driver_manage";
	public $data = false;                //数据
	public $data_list = false;			 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息

	private $driver_id = false;			//人员ID

	/**
	*		构造函数
	*		@param $driver_id 
	*		@return no
	*/
	function Driver($driver_id=false)
	{
		if($driver_id && !empty($driver_id))
		{
			$this->driver_id = $driver_id;
			$this->retrieve_data();
		}
	}

	/**
	*		查询得到指定用户信息
	*		@param $driver_id 
	*		@return no
	*/
	function retrieve_data()
	{
		$this->sql = sprintf("select * from %s where id = %d",$this->tablename,$this->driver_id);
		if ($this->data = $GLOBALS["db"]->query_once($this->sql))
			return $this->data;
		else
			return false;
	}

	
	/**
	*		查询所有人员
	*		@param $driver_id 
	*		@return no
	*/
	function get_all_drivers($wh="",$sidx="",$sord="",$start="",$limit="")
	{
		$this->sql = "select * from ".$this->tablename." where company_id = ".get_session("company_id")." ".$wh." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}

	/**
	*		查询所有人员
	*		@param $
	*		@return no
	*/
	function get_all_count()
	{
		$this->sql = "select count(*) from ".$this->tablename." where company_id =".get_session("company_id");
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}

	/**
	 * function:字段数据是否存在
	 * @param  $feild 字段名
	 * @param  $data 字段数据
	 * @param  $is_edit	 是否编辑状态(用于判断非字段以外的数据存在)
	 *
	 * @author yewen
	 */
	function exist_feild_data($feild='',$data='',$is_edit=false)
	{
		if($is_edit)
		{
			$this->sql = "select count(id) from {$this->tablename} where id <>{$this->driver_id} and {$feild}=$data";	
		}else 
		{
			$this->sql = "select count(id) from {$this->tablename} where {$feild}=".$data;
		}
		
		$this->data = $GLOBALS["db"]->query_once($this->sql);
		if($this->data[0]>0)
		{
			return true;
		}else 
		{
			return false;
		}
	}
	
	/**
	*		修改单条数据
	*		@param $
	*		@return no
	*/
	function edit_data($parms,$pk)
	{
		$result = $GLOBALS["db"]->update_row($this->tablename,$parms,$pk);
		return $result;
	}

	/**
	*		删除单条数据
	*		@param $
	*		@return no
	*/
	function delete_data($parms,$wh="")
	{
		$pk = array_keys($parms);
		$this->sql = "delete from ".$this->tablename." where ".$pk[0]." = ".$parms[$pk[0]]." ".$wh;
		$result = $GLOBALS["db"]->query($this->sql);
		return $result;
	}

	/**
	*		添加单条数据
	*		@param $
	*		@return no
	*/
	function add_data($parms)
	{
		$result = $GLOBALS["db"]->insert_row($this->tablename,$parms);
		return $result;
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
	*		检查同一公司的人员是否重复
	*		@param $searchfield 字段名
	*		@return mixed
	*/
	function checkName($name,$company_id,$driver_id = "")
	{
		if(!empty($driver_id))
		{
			$wh = " and id <> ".$driver_id;
		}
		else
		{
			$wh = "";
		}
		$this->sql = "select * from ".$this->tablename." where name = '".$name."' and company_id =".$company_id.$wh;
		$result = $GLOBALS["db"]->query_once($this->sql);
		return $result;
	}

	/**
	*		授权管理
	*		@param 
	*		@return 
	*/
	function driver_vehicle($parms)
	{
		$result = $GLOBALS["db"]->insert_row("driver_vehicle",$parms);
		return $result;
	}

	/**
	*		已经授权车辆
	*		@param 
	*		@return 
	*/
	function get_driver_vehicle($driver_id)
	{
		$this->sql = "select * from driver_vehicle where driver_id = ".$driver_id;
		$result = $GLOBALS["db"]->query($this->sql);
		return $result;
	}

	/**
	*		删除已经授权车辆
	*		@param 
	*		@return 
	*/
	function del_driver_vehicle($driver_id)
	{
		$this->sql = "delete from driver_vehicle where driver_id = ".$driver_id;
		$result = $GLOBALS["db"]->query($this->sql);
		return $result;
	}
}
?>
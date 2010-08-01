<?php

/**
* 	信息管理类
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

class Message extends BASE
{

	//	以下为每个类都必须有的变量
	public $tablename = "info_issue";
	public $data = false;                //数据
	public $data_list = false;			 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息

	private $message_id = false;			//信息ID

	/**
	*		构造函数
	*		@param $driver_id 
	*		@return no
	*/
	function Message($message_id=false)
	{
		if($message_id && !empty($message_id))
		{
			$this->message_id = $message_id;
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
		$this->sql = sprintf("select * from %s where id = %d",$this->tablename,$this->message_id);
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
	function get_all_messages($wh="",$sidx="",$sord="",$start="",$limit="")
	{
		$this->sql = "select * from ".$this->tablename." ".$wh." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}

	/**
	*		查询所有人员
	*		@param $
	*		@return no
	*/
	function get_all_count()
	{
		$this->sql = "select count(*) from ".$this->tablename;
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}

	/**
	*	所有接收到信息的人员
	*
	*/
	function get_all_receivers($id)
	{
		$this->sql = "select * from info_receive_driver where info_id =".$id;
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}

	/**
	*	所有接收到信息的人员总数
	*
	*/
	function get_all_receiver_count($id)
	{
		$this->sql = "select * from info_receive_driver where info_id =".$id;
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}

	/**
	*	影响区域
	*
	*/
	function get_all_areas($id)
	{
		$this->sql = "select * from area_info where info_id =".$id;
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}

	/**
	*	所有接收到信息的人员总数
	*
	*/
	function get_all_area_count($id)
	{
		$this->sql = "select * from area_info where info_id =".$id;
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}
}
?>
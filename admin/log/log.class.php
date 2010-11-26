<?php

/**
* 	日志操作类
*	操作日志
*
* @copyright 	  company, 2010
* @author 　　	苏元元
* @create date 　 2010.07.24
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   2010.07.26
* @todo			  n/a
*/

class Log
{
	private $user_id;
	private $company_id;
	private $desc;
	private $tablename = "operation_log_manage";

	function __construct()
	{

	}

	
	/**
	*		查询所有人员
	*		@param $driver_id 
	*		@return no
	*/
	function get_all_logs($wh="",$sidx="",$sord="",$start="",$limit="")
	{
		$this->sql = "select * from ".$this->tablename." ".$wh." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}

	/**
	*		查询所有日志
	*		@param $
	*		@return no
	*/
	function get_all_count()
	{
		$this->sql = "select count(*) from ".$this->tablename." where company_id = ".get_session("company_id");
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}

	/**
	*		写日志
	*		@param $desc
	*		@return no
	*/
	function write_log($desc = "")
	{

		// $des 为空的话，使用映射关系
		if(empty($desc))
		{
			//引入 $log_arr
			include("include/function_log.php");

			$this->desc = $log_arr[$_REQUEST["a"]];
			if(empty($this->desc))
				$this->desc = '未知操作！请检查 function_log.php';
		}
		else			//不为 描述则使用传过来的值
		{
			$this->desc = $desc;
		}

		$parms["user_id"] = $GLOBALS["db"]->prepare_value(get_session("user_id"),"INT");
		$parms["company_id"] = $GLOBALS["db"]->prepare_value(get_session("company_id"),"INT");
		$parms["time"] = $GLOBALS["db"]->prepare_value(get_sysdate(),"VARCHAR");
		$parms["description"] = $GLOBALS["db"]->prepare_value($this->desc,"VARCHAR");
//		print_r($parms);
		$result = $GLOBALS["db"]->insert_row($this->tablename,$parms);
	}

	/**
	*	得到上一次操作描述
	*
	*/
	function get_last_desc()
	{
		return $this->desc;
	}
}
?>
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

class Login_log
{
	private $user_id;
	private $company_id;
	private $desc;
	private $tablename = "login_log";

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
		$this->sql = "select * from ".$this->tablename." ".$wh." and company_id = ".get_session("company_id")." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
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
}
?>
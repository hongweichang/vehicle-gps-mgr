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

class Company
{

	//	以下为每个类都必须有的变量
	public $tablename = "company";
	public $data = false;                //数据
	public $data_list = false;			 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息

	private $id = false;			//公司ID

	/**
	*		构造函数
	*		@param $id 
	*		@return no
	*/
	function Company($id=false)
	{
		if($id && !empty($id))
		{
			$this->id = $id;
			$this->retrieve_data();
		}
	}

	/**
	*		查询得到指定用户信息
	*		@param $id 
	*		@return no
	*/
	function retrieve_data()
	{
		$this->sql = sprintf("select * from %s where id = %d",$this->tablename,$this->id);
		if ($this->data = $GLOBALS["db"]->query_once($this->sql))
			return $this->data;
		else
			return false;
	}

	/**
	*		查询所有公司
	*		@param $id 
	*		@return no
	*/
	function get_all_companys($wh="",$sidx="",$sord="",$start="",$limit="")
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
		$r1 = $GLOBALS["db"]->query($this->sql);
		
		$this->sql = "delete from user where company_id = ".$parms['id'];
		$r2 = $GLOBALS["db"]->query($this->sql);
		
		if($r1 && $r2){
			return true;
		}
		
		return false;
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
	*		检查重复的登录ID
	*		@param $
	*		@return no
	*/
	function checkLoignid($login_id)
	{
		$this->sql = "select * from ".$this->tablename." where login_id = '".$login_id."'";
		$result = $GLOBALS["db"]->query_once($this->sql);
		return $result;
	}
	
	/**
	 * 	添加默认公司平台管理员和公司内部管理人员
	 * @$rtn 公司ID
	 */
	function add_admin($rtn){
		//默认公司平台管理员
		$admin['login_name'] = $GLOBALS['db']->prepare_value("sysadmin","VARCHAR");
		$admin['password'] = $GLOBALS['db']->prepare_value("111111","VARCHAR");
		$admin['company_id'] = $GLOBALS['db']->prepare_value($rtn,"INT");
		$admin['role_id'] = $GLOBALS['db']->prepare_value(2,"INT");
		$admin['state'] = $GLOBALS['db']->prepare_value(1,"INT");
		
		//默认公司内部管理员
		$normal['login_name'] = $GLOBALS['db']->prepare_value("admin","VARCHAR");
		$normal['password'] = $GLOBALS['db']->prepare_value("111111","VARCHAR");
		$normal['company_id'] = $GLOBALS['db']->prepare_value($rtn,"INT");
		$normal['role_id'] = $GLOBALS['db']->prepare_value(3,"INT");
		$normal['state'] = $GLOBALS['db']->prepare_value(1,"INT");
		
		$r1 = $GLOBALS['db']->insert_row("user",$admin);
		$r2 = $GLOBALS['db']->insert_row("user",$normal);
		if($r1 && $r2)
			return true;
		return false;
	}
}
?>
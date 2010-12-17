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
	function get_all_companys($explorer_ids,$wh="",$sidx="",$sord="",$start="",$limit="")
	{
		//$this->sql = "select * from ".$this->tablename." ".$wh." and explorer_id in(".$explorer_ids.") order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		$this->sql = "select * from ".$this->tablename." WHERE explorer_id in(".$explorer_ids.")";
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}

	/**
	*		查询所有公司总数
	*		@param $
	*		@return no
	*/
	function get_all_count($explorer_ids)
	{
		$this->sql = "select count(*) from ".$this->tablename." where explorer_id in(".$explorer_ids.")";
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}
	
	/**
	 * 查询子业务员ID
	 */
	function get_child_ids($explorer_ids){
		$this->sql = "select id from user where parent_id in(".$explorer_ids.")";
		$this->data = $GLOBALS['db']->query($this->sql);
		foreach($this->data as $key=>$value){
			$ids[$key] = $value[0];
		}
		if(count($ids)>0){
			$result = implode($ids,",");
		}else{
			$result = false;
		}
		return $result;
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
		//删除公司
		$pk = array_keys($parms);
		$this->sql = "delete from ".$this->tablename." where ".$pk[0]." = ".$parms[$pk[0]]." ".$wh;
		$r1 = $GLOBALS["db"]->query($this->sql);
		
		//删除用户
		$this->sql = "delete from user where company_id = ".$parms['id'];
		$r2 = $GLOBALS["db"]->query($this->sql);
		
		//删除速度颜色对应数据
		$this->sql = "delete from speed_color where company_id = ".$parms['id'];
		$r3 = $GLOBALS["db"]->query($this->sql);
		
		//删除配置数据
		$this->sql = "delete from common_setting where company_id = ".$parms['id'];
		$r4 = $GLOBALS["db"]->query($this->sql);
		
		if($r1 && $r2 && $r3 && $r4){
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
		//默认公司内部管理员
		$normal['login_name'] = $GLOBALS['db']->prepare_value("admin","VARCHAR");
		$normal['password'] = $GLOBALS['db']->prepare_value("111111","VARCHAR");
		$normal['company_id'] = $GLOBALS['db']->prepare_value($rtn,"INT");
		$normal['role_id'] = $GLOBALS['db']->prepare_value(3,"INT");
		$normal['state'] = $GLOBALS['db']->prepare_value(1,"INT");

		$result = $GLOBALS['db']->insert_row("user",$normal);
		if($result)
			return true;
		return false;
	}
}
?>
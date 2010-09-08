<?php
/**
* 	用户类（模拟）
*
*
* @copyright 	  company, 2010
* @author 　　李少杰
* @create date 　 2010.07.24
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   2010.07.24 18:45	文件生成
* @todo			  n/a
*/
class User extends BASE
{
	//	以下为每个类都必须有的变量
	public $tablename = "user";
	public $data = false;                //数据
	public $data_list = false;					 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息
	
	private $user_id = false;		//用户ID
	private $tablename_company = "company";		//公司表
	private $tablename_log = "login_log";		//登录、登出日志
	/**
	*		构造函数
	*		@param $user_id 
	*		@return no
	*/
	function User($user_id=false)
	{
		if($user_id && !empty($user_id))
		{
			$this->user_id = $user_id;
			$this->retrieve_data();
		}
	}
	
	/**
	*		查询得到指定用户信息
	*		@param $user_id 
	*		@return no
	*/
	private function retrieve_data()
	{
		$this->sql = sprintf("select * from %s where id = %d",$this->tablename,$this->user_id);
		if ($this->data = $GLOBALS["db"]->query_once($this->sql))
			return $this->data;
		else
			return false;
	}
	
	/**
	*	用户login
	*	@param $username $password $companyloginid
	*	@return boolean
	*/
	function login($user_name,$user_pass,$companyloginid)
	{
		$this->sql = sprintf("select u.* from %s u,%s c where u.login_name = '%s' and u.password = '%s' and u.company_id = c.id and c.login_id = '%s' and u.state = 1",$this->tablename,$this->tablename_company,$user_name,$user_pass,$companyloginid);
		//echo $this->sql;exit;
		if(!$result = $GLOBALS['db']->query_once($this->sql))
		{
			$this->message = "公司登录ID、用户名或密码错误，登录失败！";
			return false;
		}
		set_session("user_id",$result['id']);
		set_session("login_id",$companyloginid);
		set_session("company_id",$result['company_id']);
		set_session("user_name",$result['login_name']);
		//记录登陆日志
		$ip = get_user_ip();
		$log['user_id'] = $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
		$log['company_id'] = $GLOBALS['db']->prepare_value(get_session("company_id"),"INT");
		$log['ip'] = $GLOBALS['db']->prepare_value($ip,"VARCHAR");
		$log['login_time'] = $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
		$log['logout_time'] = $GLOBALS['db']->prepare_value('NULL',"RAW");
		if(!$GLOBALS['db']->insert_row($this->tablename_log,$log))
		{
			$this->message = "登录日志记录失败！";
			return false;
		}
		return true;
	}
	
	/**
	*	用户loginout
	*	@param no
	*	@return boolean
	*/
	function logout()
	{
		$ip = get_user_ip();
		$log['user_id'] = $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
		$log['company_id'] = $GLOBALS['db']->prepare_value(get_session("company_id"),"INT");
		$log['ip'] = $GLOBALS['db']->prepare_value($ip,"VARCHAR");
		$log['login_time'] = $GLOBALS['db']->prepare_value('NULL',"RAW");
		$log['logout_time'] = $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
		if(!$GLOBALS['db']->insert_row($this->tablename_log,$log))
		{
			$this->message = "登出日志记录失败！";
			return false;
		}
		session_start();
		session_unset();
		session_destroy();
		return true;
	}
	
	/**
	*	添加用户
	*	@param $user
	*	@return boolean
	*/
	function add_user($user)
	{
		if(!$user)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		if(!$GLOBALS['db']->insert_row($this->tablename,$user))
		{
			$this->message = "error,insert data failed!";
			return false;
		}
		return true;
	}
	
	/**
	*	修改用户
	*	@param $user
	*	@return boolean
	*/
	function edit_user($user)
	{
		if(!$user)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		//添加主键ID
		$user['id'] = $this->user_id;
		if(!$GLOBALS['db']->update_row($this->tablename,$user,"id"))
		{
			$this->message = "error,edit data failed!";
			return false;
		}
		return true;
	}
	
	/**
	*	删除用户
	*	@param $user
	*	@return boolean
	*/
	function del_user($user)
	{
		if(!$user)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		$this->sql = sprintf("delete from %s where id = %d",$this->tablename,$this->user_id);
		if(!$GLOBALS['db']->query($this->sql))
		{
			$this->message = "error,delete data failed!";
			return false;
		}
		return true;
	}
	
	/**
	*	实体函数的render，用户对指定的列名称（字符串）进行润色、翻译
	*	@param $col_name 列名称（字符串）
	*	@return $o  润色翻译后的数值
	*/
	function child_render($col_name)
	{
		switch ($col_name)
		{
			case "user_red_name":		//模拟实现一位用户姓名的显示方式，显示成红色
				$value = '<font color="red">------test child_render:'.$this->get_data('user_name').'</font>';
				break;
			case "v_state":
				$par = "user";
				$child = "state";
				$xml = new Xml($par,$child);
				$xmldata = $xml->get_array_xml();
				$value = $xmldata[$this->get_data("state")];
				break;
		}
		return $value;
	}
	
	/**
	*	查询所有用户
	*	@param null
	*	@return no
	*/
	function get_user_count()
	{
		$this->sql = "select count(*) from ".$this->tablename." where company_id = ".get_session("company_id");
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}
	
	/**
	*		查询所有用户
	*		@param $wh 条件 $sidx 字段 $sord 排序 $start&$limit 取值区间
	*		@return no
	*/
	function get_all_users($wh="",$sidx="",$sord="",$start="",$limit="")
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
	
}



?>
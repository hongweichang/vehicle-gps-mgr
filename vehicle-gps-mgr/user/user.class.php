<?
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
		$this->sql = sprintf("select * from %s where user_id = %d",$this->tablename,$this->user_id);
		if ($this->data = $GLOBALS["db"]->query_once($this->sql))
			return $this->data;
		else
			return false;
	}
	
	/**
	*	用户login
	*	@param $username $password $verify
	*	@return boolean
	*/
	function login($user_name,$user_pass)
	{
		$this->sql = sprintf("select * from %s where login_name = '%s' and password = '%s'",$this->tablename,$user_name,$user_pass);
		if(!$result = $GLOBALS['db']->query_once($this->sql))
		{
			$this->message = "用户名或密码错误，登录失败！";
			return false;
		}
		set_session("admin_id",$result['id']);
		return true;
	}
	
	/**
	*	添加用户
	*	@param $user
	*	@return boolean
	*/
	private function add_user($user)
	{
		
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
		}
		return $value;
	}
	
	
	
	
	
	
}



?>
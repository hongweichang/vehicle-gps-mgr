<?php
/**
* 	密码管理类
*
*
* @copyright 	  vehicle, 2010
* @author 　　赵将伟
* @create date 　 2010.09.19
* @modify  　　　 n/a
* @modify date　　n/a
* @todo			  n/a
*/
class Password extends BASE
{
	//	以下为每个类都必须有的变量
	public $tablename = "user";
	public $data = false;                //数据
	public $data_list = false;					 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息
	
	private $id = false;		//主键ID
	
	/**
	*		构造函数
	*		@param $id 
	*		@return no
	*/
	function Password($id=false)
	{
		if($id && !empty($id))
		{
			$this->id = $id;
			$this->retrieve_data();
		}
	}
	
   /**
	*		查询得到指定系统信息
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
	 *  修改密码
	 *  @param $user 用户
	 */
	function update_password($user){
		if(!$GLOBALS['db']->update_row("user",$user,"id")){
				return false;
			}
			return true;
	}
	
	/**
	 * 检查旧密码是否正确
	 * @param $old_password
	 */
	function check_old($old_password){
		$user_id = get_session("user_id");
		$this->sql = "select * from ".$this->tablename." where id=".$user_id." and password='".$old_password."'";
		return $this->data = $GLOBALS['db']->query_once($this->sql);
	}
	
	/**
	 * 查询用户
	 * @param $id 用户ID
	 */
	function get_user($id){
		$this->sql = "select * from ".$this->tablename." where id=".$id;
		$this->data = $GLOBALS['db']->query_once($this->sql);
		return $this->data;
	}

}



?>
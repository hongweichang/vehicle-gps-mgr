<?php
/**
* 	首页消息管理类
*
*
* @copyright 	  vehicle, 2010
* @author 　　赵将伟
* @create date 　 2010.11.29
* @modify  　　　 n/a
* @modify date　　n/a
* @todo			  n/a
*/
class Message extends BASE
{
	//	以下为每个类都必须有的变量
	public $tablename = "history_message";
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
	function Message($id=false)
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
	 * 插入留言
	 */
	function add_message($message){
		if(!$message){
			$this->message = "error,object must be not empty!";
			return false;
		}
		
		if(!$GLOBALS['db']->insert_row($this->tablename,$message))
		{
			$this->message = "error,insert data failed!";
			return false;
		}
		return true;
	}
	
	/**
	 * 获取历史消息记录
	 */
	function history_message(){
		$sql = "select * from ".$this->tablename." where company_id=".get_session('company_id');
		$this->data_list = $GLOBALS['db']->query($sql);
		return $this->data_list;
	}

}

?>
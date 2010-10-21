<?php
/**
* 	添加GPS设备类
*
*
* @copyright 	  vehicle, 2010
* @author 　　赵将伟
* @create date 　 2010.10.19
* @modify  　　　 n/a
* @modify date　　n/a
* @todo			  n/a
*/
class add_gps extends BASE
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
	function add_gps($id=false)
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
	 *  添加GPS设备
	 *  @gps gps设备信息
	 */
	function add_gps_number($gps){
		if(!$gps)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		$result = $GLOBALS['db']->insert_row("gps_equipment",$gps);
		if(!$result)
		{
			$this->message = "error,insert data failed!";
			return false;
		}
		return true;
	}

}



?>
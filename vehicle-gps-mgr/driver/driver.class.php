<?

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

class Driver extends BASE
{

	//	以下为每个类都必须有的变量
	public $tablename = "driver_manage";
	public $data = false;                //数据
	public $data_list = false;			 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息

	private $driver_id = false;			//人员ID

	/**
	*		构造函数
	*		@param $driver_id 
	*		@return no
	*/
	function Driver($driver_id=false)
	{
		if($driver_id && !empty($driver_id))
		{
			$this->driver_id = $driver_id;
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
		$this->sql = sprintf("select * from %s where id = %d",$this->tablename,$this->driver_id);
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
	function get_all_drivers()
	{
		$this->sql = "select * from ".$this->tablename;
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}
}
?>
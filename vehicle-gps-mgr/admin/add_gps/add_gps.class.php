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
	public $tablename = "gps_equipment";
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
	 *  添加GPS设备信息
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
	
	/**
	 * 查询所有的GPS设备
	 */
	function get_all_gps($wh,$sidx,$sord,$start,$limit){
		$this->sql = "select * from ".$this->tablename." ".$wh." and company_id = ".get_session("company_id")." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		return $this->data = $GLOBALS["db"]->query($this->sql);
	}
	
	/**
	 * 查询所有GPS总数
	 */
	function get_count_gps(){
		$this->sql = "select count(*) from gps_equipment where company_id=".get_session("company_id");
		$this->data = $GLOBALS["db"]->query_once($this->sql);
		return $this->data[0];
	}
	
	/**
	 * 删除gps设备
	 * @gps_id ID
	 */
	function delete_gps($gps_id){
		$this->sql = "delete from gps_equipment where id=".$gps_id;
		return $result = $GLOBALS["db"]->query($this->sql);
	}
	
	/**
	 * 修改GPS设备
	 */
	function edit_gps($gps_number,$gps_id){
		$this->sql = "update gps_equipment set gps_number=".$gps_number." where id=".$gps_id;
		return $result = $GLOBALS['db']->query($this->sql);
	}

}



?>
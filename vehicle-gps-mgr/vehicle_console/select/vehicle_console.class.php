<?php
/**
* (首页)车辆选择功能
*
*
* @copyright 	  秦运恒, 2010
* @author 　　叶稳
* @create date 　 2010.07.30
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   
* @todo			  n/a
*/
class vehicle_console extends BASE{	 
	public $tablename = "user";
	public $data = false;                //数据
	public $data_list = false;					 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息
	private $user_id = false;		//用户ID
	private $tablename_vehicle_group = "vehicle_group";//车辆组
	private $tablename_vehicle_manage = "vehicle_manage";//车辆
			 
			/*
			 * 查询车辆组数量
			 */
			function get_vehicle_group_count(){
				$this->sql="select count(*) from ".$this->tablename_vehicle_group;
				$count = $GLOBALS["db"]->query_once($this->sql);
				return $count[0];				
			}
			
			/**
			 * 查询所有车辆组
			 */
			function get_all_vehicle_group(){
				$this->sql = "select * from ".$this->tablename_vehicle_group;
				return $this->data_list = $GLOBALS["db"]->query($this->sql);
			}
			
			/**
			 * 查询每组的所有车辆
			 * @ $wh 条件
			 */
			function get_group_vehicle($wh=""){
				$this->sql = "select * from ".$this->tablename_vehicle_manage." ".$wh." order by length(number_plate)";
				return $this->data_list = $GLOBALS["db"]->query($this->sql);
			}

}

?>
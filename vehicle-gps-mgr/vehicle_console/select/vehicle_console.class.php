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
	private $tablename_common_setting = "common_setting"; //系统内部参数表
	private $tablename_speed_color = "speed_color"; //速度关联
			 
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
			
			/***
			 * 查询车辆集合定位信息、速度颜色
			 * $where 车辆ID集合(例：1,2,6,3,4)
			 */
			function  get_vehicles($where=-1){
				
				$this->sql="SELECT v.id,v.cur_longitude,v.cur_latitude,v.cur_direction,s.color ".
									" FROM  ".
									"	".$this->tablename_vehicle_manage." as v ". 
									" INNER JOIN ".
									"	speed_color as s ". 
									" ON  ".
									"	v.id in(".$where.") ".
									"	AND ".
									"		v.company_id = s.company_id ". 
									"	AND ".
									"		v.cur_direction>=min". 
									" 	AND". 
									"		v.cur_direction<max";    
				return $this->data_list = $GLOBALS["db"]->query($this->sql);
			}
			
			/**
			 * 
			 * @param $commpany_id
			 */
			function get_default_color($commpany_id=-1){
				
				$this->sql = "SELECT default_color FROM ".$this->tablename_common_setting." WHERE commpany_id=".commpany_id;
				
				return $GLOBALS["db"]->query_once($this->sql);
			}
}

?>
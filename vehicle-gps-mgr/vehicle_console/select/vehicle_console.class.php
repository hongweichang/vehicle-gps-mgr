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
	public $tablename = "user"; //用户表
	public $data = false; //数据
	public $data_list = false; //数据集合
	public $sql; //SQL语句
	public $message;//消息
	private $user_id = false; //用户ID
	private $tablename_vehicle_group = "vehicle_group";//车辆组表
	private $tablename_vehicle_manage = "vehicle_manage";//车辆管理表
	private $tablename_common_setting = "common_setting"; //系统内部参数表
	private $tablename_speed_color = "speed_color"; //速度关联表
	private $tablename_driver_manage = "driver_manage"; //人员管理表
			 
			
			/**
			 * 查询所有车辆组
			 * @param $company_id  公司ID
			 */
			function get_all_vehicle_group($company_id=-1){
				$this->sql = "select * from ".$this->tablename_vehicle_group ." where company_id=".$company_id;
				return $this->data_list = $GLOBALS["db"]->query($this->sql);
			} 
			
			/**
			 * 查询每组的所有车辆
			 * @param $wh @ $wh 条件
			 * @param $company_id   公司ID
			 */
			function get_group_vehicle($wh="",$company_id=-1){
				$this->sql = "select * from ".$this->tablename_vehicle_manage." ".$wh." and company_id=".$company_id." order by length(number_plate)";
				return $this->data_list = $GLOBALS["db"]->query($this->sql);
			}
			
			/**
			 * 获取当前公司所有车辆信息
			 * @param $company_id 公司ID
			 */
			function company_all_vehicle($company_id=-1){ 
				
				$this->sql = "SELECT  v.id, v.company_id,v.number_plate,v.gps_id,v.location_time,v.cur_longitude,v.cur_latitude,v.cur_speed,v.cur_direction,g.name as group_name,d.name as driver_name, ".
							 "			(CASE WHEN s.color is null then c.default_color ".
							 "			    ELSE s.color END)as color ".
							 "				FROM ".$this->tablename_vehicle_manage." v ".
							 "					LEFT JOIN ".$this->tablename_speed_color." as s ".
							 "						ON s.company_id =".$company_id."  AND v.company_id=s.company_id AND (v.cur_speed>=s.min AND v.cur_speed<s.max) ".
							 "					LEFT JOIN  ".$this->tablename_vehicle_group." g ON g.company_id=v.company_id  ".
							 "					LEFT JOIN ".$this->tablename_driver_manage." d ON d.id= v.driver_id	 ".
							 "					LEFT JOIN ".$this->tablename_common_setting." c ON c.company_id =".$company_id.
							 "				WHERE v.company_id =".$company_id." AND (v.cur_longitude is not null OR v.cur_latitude is not null) AND v.gprs_status =1".
							 "				GROUP BY v.id";
				return $this->data_list = $GLOBALS["db"]->query($this->sql);
			}
			 
			/**
			 * 查询车辆集合定位信息、速度颜色
			 * @param $where 车辆ID集合(例：1,2,6,3,4)
			 * @param $company 公司ID
			 */
			function  get_vehicles($where=-1,$company=-1){
				
				
				$this->sql="SELECT v.id,v.cur_longitude,v.cur_latitude,v.cur_direction,(case when s.color is null then c.default_color  else s.color end)as color,".
						   " 		v.number_plate,v.gps_id,v.location_time,g.name as group_name,d.name as driver_name,v.cur_speed ".
						   "				FROM ". 				
						   "					".$this->tablename_vehicle_manage." as v ". 			
						   "					LEFT JOIN ".
						   "						".$this->tablename_speed_color." as s ". 			
						   "					    ON  s.company_id = ".$company."  AND  v.company_id = s.company_id  AND (v.cur_speed>=s.min AND v.cur_speed<s.max) ". 
						   "					LEFT JOIN ". 				
						   "						".$this->tablename_common_setting." c ". 			
						   "					    ON  c.company_id = s.company_id".		 
						   "					LEFT JOIN ".
						   "						".$this->tablename_vehicle_group." g ". 
						   "					    ON g.company_id=c.company_id and g.id=v.vehicle_group_id".  
						   "					LEFT JOIN ".
						   "						".$this->tablename_driver_manage." d ". 
						   "					   ON d.id= v.driver_id ".	 
						   "			WHERE v.id in(".$where.") group by v.id";
				  
				return $this->data_list = $GLOBALS["db"]->query($this->sql);
			}
			
			/**
			 * 根据车辆ID查询车辆详细信息
			 * @param $vehicle_id 车辆ID
			 */
			function get_vehicle($vehicle_id){
				$this->sql = "select vm.*,dm.name as driver_name,vg.name as group_name from ".$this->tablename_vehicle_manage." as vm left join "
								.$this->tablename_driver_manage." as dm on vm.driver_id=dm.id left join ".$this->tablename_vehicle_group
								." as vg on vm.vehicle_group_id=vg.id where vm.id=".$vehicle_id;
				return $this->data_list = $GLOBALS['db']->query($this->sql);
			}
			
}

?>
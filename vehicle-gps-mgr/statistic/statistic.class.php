<?php
/**
 * 统计分析类
   @copyright  秦运恒, 2010
 * @author 　　段贵山
 * @create date 　 2010.08.09
 */
class Statistic extends BASE {
	public $data = false; //数据
	public $data_list = false; //数据集合
	public $sql; //SQL语句
	public $message; //消息
	

	/**
	 * 查询车辆记录
	 * @param $wh
	 * @param $sidx
	 * @param $sord
	 * @param $start
	 * @param $limit
	 * @return 查询记录
	 * 

	 */
	function get_all_vehicle($wh = "", $sidx = "", $sord = "", $start = "", $limit = "") {
		$this->sql = 
"select vehicle_id,number_plate,distance,drive_time,stop_time,min_time,max_time from ".
"((select ss_cs.vehicle_id, ss_cs.distance, ss_cs.drive_time, ss_cs.stop_time,ti.min_time,ti.max_time  from ".
"(select cs.vehicle_id,ss.distance,ss.drive_time,cs.stop_time from ".
"(select vehicle_id,distance,drive_time from   continue_drive_statistic group by vehicle_id) as ss ".
"inner join ".
"(select vehicle_id,stop_time from   stop_statistic group by vehicle_id ) as  cs ".
"on ss.vehicle_id=cs.vehicle_id) as ss_cs ".
"inner join ". 
"(select vehicle_id ,min(start_time) as min_time ,max(end_time) as max_time from continue_drive_statistic group by vehicle_id) as ti ".
"on ti.vehicle_id=ss_cs.vehicle_id)) as vehi ".
"inner join ".
"(select id,number_plate from vehicle_manage) as manage ".
"on manage.id=vehi.vehicle_id " . $wh . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	/***
	 * 
	 * 得到车辆总记录数
	 * @return 总记录数
	 * 
	 */
	function get_vehicle_count() {
		
		$this->sql = "select count(*) from " . "(select cs.vehicle_id,ss.distance,ss.drive_time,cs.stop_time from " . "(select vehicle_id,distance,drive_time from   continue_drive_statistic group by vehicle_id) as ss " . "inner join " . "(select vehicle_id,stop_time from   stop_statistic group by vehicle_id ) as  cs " . " on ss.vehicle_id=cs.vehicle_id) as ss_cs " . " inner join " . "(select vehicle_id ,min(start_time) as min_time ,max(end_time) as max_time from continue_drive_statistic group by vehicle_id) as ti " . " on ti.vehicle_id=ss_cs.vehicle_id ";
		
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
	
	/**
	 * 查询所有驾驶员记录
	 * @param $wh
	 * @param $sidx
	 * @param $sord
	 * @param $start
	 * @param $limit
	 * @return 查询记录
	 */
	function get_all_driver($wh = "", $sidx = "", $sord = "", $start = "", $limit = "") {
		$this->sql =
"select driver_id,login_name,distance,drive_time,stop_time,min_time,max_time from ".
"(select ss_cs.driver_id, ss_cs.distance, ss_cs.drive_time, ss_cs.stop_time,ti.min_time,ti.max_time  from ".  
"(select cs.driver_id,ss.distance,ss.drive_time,cs.stop_time from ".
"(select driver_id,distance,drive_time from   continue_drive_statistic group by driver_id) as ss ".
"inner join ".
"(select driver_id,stop_time from   stop_statistic group by driver_id ) as  cs ". 
"on ss.driver_id=cs.driver_id) as ss_cs ".
"inner join ".
"(select driver_id ,min(start_time) as min_time ,max(end_time) as max_time from continue_drive_statistic group by driver_id) ". 
"as ti ".
"on ti.driver_id=ss_cs.driver_id) as drive ". 
"inner join  ".
"(select id,login_name from user) as usr ".
"on  usr.id=drive.driver_id " . $wh . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	/***
	 * 
	 * 得到驾驶员总记录数
	 * @return 总记录数
	 * 
	 */
	function get_driver_count() {
		
		$this->sql = "select count(*) from " . " ( select cs.driver_id,ss.distance,ss.drive_time,cs.stop_time from " . " ( select driver_id,distance,drive_time from   continue_drive_statistic group by driver_id) as ss " . " inner join " . " ( select driver_id,stop_time from   stop_statistic group by driver_id ) as cs" . " on ss.driver_id=cs.driver_id) as ss_cs " . " inner join" . " ( select driver_id ,min(start_time) as min_time ,max(end_time) as max_time from continue_drive_statistic group by driver_id) as ti" . " on ti.driver_id=ss_cs.driver_id ";
		
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
}
?>

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
	function get_all_vehicle($wh = "", $sidx = "", $sord = "", $start = "", $limit = "", $company_id="",$statistic_vehicle_single_id="") {
		if($statistic_vehicle_single_id!=""){
			$str=" and id in (". $statistic_vehicle_single_id .")";
		}
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
				"on manage.id=vehi.vehicle_id " . $wh . " and  vehicle_id in (select id from vehicle_manage where company_id=".$company_id.$str." ) order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	/***
	 * 
	 * 得到车辆总记录数
	 * @return 总记录数
	 * 
	 */
	function get_vehicle_count($company_id="",$statistic_vehicle_single_id="") {
	   if($statistic_vehicle_single_id!=""){
		   $str=" and id in (". $statistic_vehicle_single_id .")";
		}
		$this->sql = "select count(*) from " . "((select cs.vehicle_id,ss.distance,ss.drive_time,cs.stop_time from " . 
		             "(select vehicle_id,distance,drive_time from   continue_drive_statistic group by vehicle_id) as ss " . "inner join " . 
		             "(select vehicle_id,stop_time from   stop_statistic group by vehicle_id ) as  cs " . " on ss.vehicle_id=cs.vehicle_id) as ss_cs ". 
		             " inner join " . "(select vehicle_id ,min(start_time) as min_time ,max(end_time) as max_time from continue_drive_statistic group by vehicle_id) as ti " . 
		             " on ti.vehicle_id=ss_cs.vehicle_id) ".
		             " where ti.vehicle_id in (select id from vehicle_manage where company_id=".$company_id.$str.")";
		
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
	function get_all_driver($wh = "", $sidx = "", $sord = "", $start = "", $limit = "",$company_id="") {
		$this->sql =
				"select driver_id,name,distance,drive_time,stop_time,min_time,max_time from ".
				"((select ss_cs.driver_id, ss_cs.distance, ss_cs.drive_time, ss_cs.stop_time,ti.min_time,ti.max_time  from ".  
				"(select cs.driver_id,ss.distance,ss.drive_time,cs.stop_time from ".
				"(select driver_id,distance,drive_time from   continue_drive_statistic group by driver_id) as ss ".
				"inner join ".
				"(select driver_id,stop_time from   stop_statistic group by driver_id ) as  cs  ".
				"on ss.driver_id=cs.driver_id) as ss_cs ".
				"inner join ".
				"(select driver_id ,min(start_time) as min_time ,max(end_time) as max_time from continue_drive_statistic group by driver_id)  ".
				"as ti ".
				"on ti.driver_id=ss_cs.driver_id) as drive  ".
				"inner join  ".
				"(select id,name from driver_manage) as d_m ".
				"on  d_m.id=drive.driver_id) ".  $wh . " and  drive.driver_id in (select id from driver_manage where company_id=".$company_id.") order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;

		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
/***
	 * 
	 * 得到驾驶员总记录数
	 * @return 总记录数
	 */
	function get_driver_count($company_id) {		
		$this->sql = "select count(*) from " . 
						" ( select cs.driver_id,ss.distance,ss.drive_time,cs.stop_time from " . 
						" ( select driver_id,distance,drive_time from   continue_drive_statistic group by driver_id) as ss " . 
						" inner join " . " ( select driver_id,stop_time from   stop_statistic group by driver_id ) as cs" . 
						" on ss.driver_id=cs.driver_id) as ss_cs " . " inner join" . 
						" ( select driver_id ,min(start_time) as min_time ,max(end_time) as max_time from continue_drive_statistic group by driver_id) as ti" . 
						" on ti.driver_id=ss_cs.driver_id ".
		                " where ti.driver_id in (select id from driver_manage where company_id=".$company_id.")";
		
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
	/**
	 * 条件查询:在一段时间内
	 * 驾驶员信息
	 */
	function get_driver_time($sel_start_time="",$sel_end_time="",$wh = "", $sidx = "", $sord = "", $start = "", $limit = "",$company_id=""){
		$this->sql =
				"select driver_manage_data.id, driver_manage_data.name,driver_data.distance, driver_data.drive_time, driver_data.stop_time, driver_data.min_time, driver_data.max_time from
				    ((select 
				       drive_sta.driver_id, drive_sta.distance,drive_sta.drive_time, drive_sta.min_time,drive_sta.max_time, stop_sta.stop_time
				            from 		
				               (select driver_id,sum(distance) as distance ,sum(drive_time) as drive_time , min(start_time) as min_time ,max(end_time) as max_time
							from continue_drive_statistic 
							where 
							Concat(start_time)>'".$sel_start_time."'
							and 
							Concat(start_time)<'".$sel_end_time."'
							group by driver_id ) as drive_sta
				                inner join 
				               (select driver_id,sum(stop_time) as stop_time 
				                        from stop_statistic
						        where
				                        Concat(start_time)>'".$sel_start_time."'
						        and Concat(start_time)<'".$sel_end_time."' 
				                        and driver_id in 
				                               (select id from driver_manage where company_id=".$company_id.")
						        group by driver_id ) as stop_sta
				             on stop_sta.driver_id=drive_sta.driver_id) as driver_data
				inner join 
				   (select id,name from driver_manage) as driver_manage_data
				on 
				driver_manage_data.id=driver_data.driver_id)  order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		
		
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	/**
	 * 条件查询:在一段时间内
	 * 车辆信息
	 */
	function get_vehicle_time($id_array="",$sel_start_time="",$sel_end_time="",$wh = "", $sidx = "", $sord = "", $start = "", $limit = "",$company_id){
		if($id_array!=0){
			   $str=" and vehicle_data.vehicle_id in ($id_array) ";
		}else{	 
		  	  $str=" and vehicle_data.vehicle_id in (select id from vehicle_manage where company_id=".$company_id.")";
		}		
		$this->sql =
				"select vehicle_data.vehicle_id,vehicle_manage_data.number_plate,vehicle_data.distance,vehicle_data.drive_time,vehicle_data.stop_time,vehicle_data.min_time,vehicle_data.max_time  from 
				(select 
				    drive_sta.vehicle_id, drive_sta.distance,drive_sta.drive_time, drive_sta.min_time,drive_sta.max_time, stop_sta.stop_time    
					from 	
				          (select vehicle_id,sum(distance) as distance ,sum(drive_time) as drive_time , min(start_time) as min_time ,max(end_time) as max_time
				                from continue_drive_statistic 
						where 
				                Concat(start_time)>'".$sel_start_time."'
						and 
				                Concat(start_time)<'".$sel_end_time."'
				                
						group by vehicle_id ) as drive_sta
				            inner join 
				           (select vehicle_id,sum(stop_time) as stop_time from stop_statistic
						where Concat(start_time)>'".$sel_start_time."'
						and Concat(start_time)<'".$sel_end_time."'
						group by vehicle_id ) as stop_sta
				            on stop_sta.vehicle_id=drive_sta.vehicle_id) as vehicle_data
				inner join 
				  (select id,number_plate from vehicle_manage) as vehicle_manage_data
				on
				vehicle_manage_data.id=vehicle_data.vehicle_id ".$str ." order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;;
		
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	function sel_driver_count ($begin_data,$end_data,$company_id){
			$this->sql =
			"select count(*) from
				    (select 
				       drive_sta.driver_id, drive_sta.distance,drive_sta.drive_time, drive_sta.start_time,drive_sta.end_time, stop_sta.stop_time
				            from 		
				               (select driver_id,sum(distance) as distance ,sum(drive_time) as drive_time , min(start_time) as start_time ,max(end_time) as end_time
							from continue_drive_statistic 
							where 
							Concat(start_time)>'".$begin_data."'
							and 
							Concat(start_time)<'".$end_data."'
							group by driver_id ) as drive_sta
				                inner join 
				               (select driver_id,sum(stop_time) as stop_time 
				                        from stop_statistic
						        where
				                        Concat(start_time)>'".$begin_data."'
						        and Concat(start_time)<'".$end_data."'  
				                        and driver_id in 
				                               (select id from driver_manage where company_id=".$company_id.")
						        group by driver_id ) as stop_sta
				             on stop_sta.driver_id=drive_sta.driver_id) as driver_data
				inner join 
				   (select id,name from driver_manage) as driver_manage_data
				on 
				driver_manage_data.id=driver_data.driver_id";
		
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];	
}
	
	function sel_vehicle_count ($begin_data,$end_data,$id_array,$company_id){		    
			$this->sql =					
					"select count(*) from 
					(select 
					    drive_sta.vehicle_id, drive_sta.distance,drive_sta.drive_time, drive_sta.start_time,drive_sta.end_time, stop_sta.stop_time    
						from 	
					          (select vehicle_id,sum(distance) as distance ,sum(drive_time) as drive_time , min(start_time) as start_time ,max(end_time) as end_time
					                from continue_drive_statistic 
							where 
					                Concat(start_time)>'".$begin_data."'
							and 
					                Concat(start_time)<'".$end_data."'
					                
							group by vehicle_id ) as drive_sta
					            inner join 
					           (select vehicle_id,sum(stop_time) as stop_time from stop_statistic
							where Concat(start_time)>'".$begin_data."'
							and Concat(start_time)<'".$end_data."'
							group by vehicle_id ) as stop_sta
					            on stop_sta.vehicle_id=drive_sta.vehicle_id) as vehicle_data
					inner join 
					  (select id,number_plate from vehicle_manage) as vehicle_manage_data
					on
					vehicle_manage_data.id=vehicle_data.vehicle_id"; 					
			if($id_array!=0){
				$this->sql=$this->sql." and  vehicle_data.vehicle_id in ($id_array)";
			}else {
				 $this->sql=$this->sql." and vehicle_data.vehicle_id in (select id from vehicle_manage where company_id=".$company_id.")";
			}
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
	
	
	/**
	 * 根据驾驶员id显示驾驶员开车详细信息
	 * @param $driver_id
	 */
	function drive_detail_data($driver_id, $wh = "", $sidx = "", $sord = "", $start = "", $limit = ""){
		  $this->sql ="select cds.id, name,start_time,end_time,drive_time,distance from driver_manage dm,continue_drive_statistic cds 
                       where  cds.driver_id='".$driver_id."' and  dm.id=cds.driver_id ".  
		               " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		  return $this->data = $GLOBALS ["db"]->query ( $this->sql );		
	}
	/**
	 * 根据驾驶员id得到驾驶员开车信息数量
	 */
	function drive_detail_count($driver_id){
		$this->sql ="select count(*) from driver_manage dm,continue_drive_statistic cds 
                       where  cds.driver_id='".$driver_id."' and  dm.id=cds.driver_id ";
        $count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
	
	
	
	/**
	 * 根据驾驶员id显示驾驶员停车详细信息
	 */
	function stop_detail_data($driver_id,$wh = "", $sidx = "", $sord = "", $start = "", $limit = ""){
		  $this->sql ="select ss.id, name,start_time,end_time,stop_time from driver_manage dm ,stop_statistic ss 
                       where  ss.driver_id='".$driver_id."' and  dm.id =ss.driver_id ".
		               " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;;
	 return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	/**
	 * 根据驾驶员id得到驾驶员停车信息数量
	 */
	function stop_detail_count($driver_id){
		$this->sql ="select count(*) from driver_manage dm ,stop_statistic ss 
                     where  ss.driver_id='".$driver_id."' and  dm.id =ss.driver_id ";
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
	
	
	
	/**
	 * 根据车辆id得到行驶详细信息
	 */
       function vehicle_detail_data($vehicle_id, $wh = "", $sidx = "", $sord = "", $start = "", $limit = ""){
		  $this->sql ="select cds.id, number_plate,start_time,end_time,drive_time,distance from vehicle_manage vm,continue_drive_statistic cds 
                       where  cds.vehicle_id='".$vehicle_id."' and  vm.id=cds.vehicle_id ".  
		               " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		  return $this->data = $GLOBALS ["db"]->query ( $this->sql );		
	}	
	/**
	 * 根据车辆id得到行驶详细信息数量
	 */
	function vehicle_detail_count($vehicle_id){
		 $this->sql ="select count(*) from vehicle_manage vm,continue_drive_statistic cds 
                       where  cds.vehicle_id='".$vehicle_id."' and  vm.id=cds.vehicle_id ";  
		               
		  $count = $GLOBALS ["db"]->query_once ( $this->sql );
		  return $count [0];	
	}
	
	
	
	/**
	 * 根据车辆id得到停驶详细信息
	 */
	function vstop_detail_data($vehicle_id, $wh = "", $sidx = "", $sord = "", $start = "", $limit = ""){
		$this->sql ="select ss.id, number_plate,start_time,end_time,stop_time  from vehicle_manage vm,stop_statistic ss
                     where  ss.vehicle_id='".$vehicle_id."' and  vm.id=ss.vehicle_id  ".  
		             " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
	    return $this->data = $GLOBALS ["db"]->query ( $this->sql );	
	}
	/**
	 *  根据车辆id得到停驶详细信息数量
	 */
	function vstop_detail_count($vehicle_id){
		$this->sql ="select count(*) from vehicle_manage vm,stop_statistic ss
                     where  ss.vehicle_id='".$vehicle_id."' and  vm.id=ss.vehicle_id  ";  
		            
		  $count = $GLOBALS ["db"]->query_once ( $this->sql );
		  return $count [0];	
	}
	
	
	

	/**
	 * 根据驾驶员id查询驾驶员姓名
	 */
	function driver_name($driverID){
		 $this->sql ="select name from driver_manage where id=".$driverID;  
		            
		  $name= $GLOBALS ["db"]->query_once ( $this->sql );
		  return $name [0];	
	}
	
	
	
	/**
	 * 根据车辆id查询车牌号码
	 */
	function vehicle_plate_name($vehicleID){
		 $this->sql ="select number_plate from vehicle_manage where id=".$vehicleID;  
		            
		  $number_plate= $GLOBALS ["db"]->query_once ( $this->sql );
		  return $number_plate [0];	
	}
} 
?>

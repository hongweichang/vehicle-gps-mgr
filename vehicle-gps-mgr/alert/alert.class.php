<?php

/**
 * 告警信息类
@copyright  秦运恒, 2010
 * @author 　　段贵山
 * @create date 　 2010.07.30
 */

class Alert extends BASE {
	
	public $mysel_table_name = "alert_info"; //	以下为每个类都必须有的变量
	public $user_table_name = "user"; //FK 用户表名称
	public $alert_table_name="alert_info";//告警记录表
	public $vehicle_manage_table_name = "vehicle_manage"; // FK 车辆管理表名称

	public $data = false; //数据
	public $data_list = false; //数据集合
	public $sql; //SQL语句
	public $message; //消息
	

	private $alert_id = false; //人员ID
	

	/**
	 * 构造函数
	 * @param $alert_id 
	 * @return no
	 */
	function Alert($alert_id = false) {
		if ($alert_id && ! empty ( $alert_id )) {
			$this->alert_id = $alert_id;
		}
	}
	
	/**
	 * 查询所有记录
	 * @param $wh
	 * @param $sidx
	 * @param $sord
	 * @param $start
	 * @param $limit
	 * @return 查询记录
	 */
	function get_all_alerts($wh = "", $sidx = "", $sord = "", $start = "", $limit = "") {
		$this->sql = "select * from " . $this->mysel_table_name . "  " . $wh . " order by " . $sidx . " " . $sord . " LIMIT " . $start . " , " . $limit;
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	/***
	 * 
	 * 得到总记录数
	 * @return 总记录数
	 * 
	 */
	function get_all_count($condition) {
		$this->sql = "select count(*) from " . $this->mysel_table_name.$condition;
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
	/**
	 * @param $id
	 * 根据vehicle_id（车辆id）
	 * 得到vehicle_manage(车辆管理表)中的车牌号
	 */
	function get_vehicle_manage_number($id = "") {
		$this->sql = "select number_plate from " . $this->vehicle_manage_table_name . " where id=" . $id;
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
	/**
	 * @param $id
	 * 根据dispose_id（处理人id）
	 * 得到user(用户表)的用户姓名
	 */
	function get_user_name($id = "") {
		$this->sql = "select name from " . $this->user_table_name . " where id=" . $id;
		$count = $GLOBALS ["db"]->query_once ( $this->sql );
		return $count [0];
	}
	/**
	 * 修改告警处理意见
	 * @param $id
	 * @param $advice
	 */
	function edit_alert_advice($alert) {
		if (! $alert) {
			$this->message = "error,object must be not empty!";
			return false;
		}
		//添加主键ID
		$alert ['id'] = $this->alert_id;
		if (! $GLOBALS ['db']->update_row ( $this->mysel_table_name, $alert, "id" )) {
			$this->message = "error,edit data failed!";
			return false;
		}
		return true;
	}

	/**
	 * 查询所有车辆组数据
	 * @return 查询记录
	 */
	function get_vehicle_group() {
		$this->sql = "select id,name from vehicle_group";
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	/**
	 * 查询所有车辆数据
	 * @return 查询记录
	 */
	function get_vehicle() {
		$this->sql = "select id,number_plate from vehicle_manage";
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	/**
	 * 查询所有车辆数据(和车辆组联动)
	 * @param unknown_type $vehicle_group_id
	 */
	
	function get_linkage_vehicle($vehicle_group_id=""){
		$this->sql ="select vm.id,vm.number_plate from vehicle_manage as vm inner join  vehicle_group as vg".
                    " on vg.id=vm.vehicle_group_id and vm.vehicle_group_id=".$vehicle_group_id;
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	/**
	 * 算出车辆组下对应的车辆数
	 */
	function get_count_vehicle($vehicle_group_id=""){
		$this->sql ="select count(number_plate) from vehicle_manage as vm inner join  vehicle_group as vg
         on vg.id=vm.vehicle_group_id and vm.vehicle_group_id=".$vehicle_group_id;
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	/**
	 * 获得最新未处理告警记录
	 */
    function get_newest_alert(){
    	$company_id = get_session("company_id");
    	//格式化sql语句
    	$sql ="select a.id,a.alert_time, v.number_plate, a.description from alert_info a left join vehicle_manage v on v.id=a.vehicle_id left join company c on a.vehicle_id=c.id
    	where  (a.dispose_opinion is null or a.dispose_opinion=\"\") and c.id=%s  order by a.alert_time desc limit 0,1" ;
    	
    	$this->sql = sprintf($sql,$company_id);
    	
    	$record = $GLOBALS ["db"]->query_once ( $this->sql );
    	return $record;
    }

}
?>
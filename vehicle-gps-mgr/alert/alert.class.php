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
	public $vehicle_manage_table_name = "vehicle_manage"; // FK 车辆管理表名称
	

	public $data = false; //数据
	public $data_list = false; //数据集合
	public $sql; //SQL语句
	public $message; //消息
	

	private $alert_id = false; //人员ID
	
  /**
	*		构造函数
	*		@param $alert_id 
	*		@return no
	*/
	function Alert($alert_id=false)
	{
		if($alert_id && !empty($alert_id))
		{
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
	function get_all_count() {
		$this->sql = "select count(*) from " . $this->mysel_table_name;
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
	function edit_alert_advice($alert){
		if(!$alert)
		{
			$this->message = "error,object must be not empty!";
			return false;
		}
		//添加主键ID
		$alert['id'] = $this->alert_id;
		if(!$GLOBALS['db']->update_row($this->mysel_table_name,$alert,"id"))
		{
			$this->message = "error,edit data failed!";
			return false;
		}
		return true;
	}
}
?>
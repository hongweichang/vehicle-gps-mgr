<?php

/**
 * 系统内置参数设置类
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

class Setting {
	
	//	以下为每个类都必须有的变量
	public $tablename = "common_setting";
	public $data = false; //数据
	public $data_list = false; //数据集合
	public $sql; //SQL语句
	public $message; //消息
	

	private $company_id = false; //公司ID
	

	/**
	 * 构造函数
	 * @param $id 
	 * @return no
	 */
	function Setting($company_id = false) {
		if ($company_id && ! empty ( $company_id )) {
			$this->company_id = $company_id;
			$this->retrieve_data ();
		}
	}
	
	/**
	 * 查询指定公司的设置
	 * @param $id 
	 * @return no
	 */
	function retrieve_data() {
		$this->sql = sprintf ( "select * from %s where company_id = %d", $this->tablename, $this->company_id );
		if ($this->data = $GLOBALS ["db"]->query_once ( $this->sql ))
			return $this->data;
		else
			return false;
	}
	
	/**
	 * 查询指定公司的速度段
	 * @param $id 
	 * @return no
	 */
	function get_speeds() {
		$this->sql = "select * from speed_color where company_id = " . $this->company_id;
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	/**
	 * 插入系统设置
	 * @param $id 
	 * @return no
	 */
	function add_setting($parms) {
		$result = $GLOBALS ["db"]->insert_row ( $this->tablename, $parms );
		return $result;
	}
	
	/**
	 * 编辑系统设置
	 * @param $id 
	 * @return no
	 */
	function edit_setting($parms, $pk) {
		$result = $GLOBALS ["db"]->update_row ( $this->tablename, $parms, $pk );
		return $result;
	}
	
	/**
	 * 删除速度和颜色映射表
	 * @param $id 
	 * @return no
	 */
	function delete_speed_color() {
		$this->sql = "delete from speed_color where company_id = " . $this->company_id;
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	}
	
	/**
	 * 插入系统设置
	 * @param $id 
	 * @return no
	 */
	function add_speed_color($parms) {
		$result = $GLOBALS ["db"]->insert_row ( "speed_color", $parms );
		return $result;
	}
	/**
	 * 根据当前公司id查询所属旗下的所有车辆信息
	 */
	function select_speed_color($id) {
		$this->sql = "select min,max,color from speed_color where company_id = " . $id;
		return $this->data = $GLOBALS ["db"]->query ( $this->sql );
	
	}
}
?>
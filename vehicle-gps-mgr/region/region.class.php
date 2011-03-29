<?php
/**
 * 区域告警类
 */

class Region extends BASE {
	var $sql;
	var $region_table = "region";
	var $join_table = "region_vehicle";
	
	function save_region($params) {
		$result = $GLOBALS ["db"]->insert_row ( $this->region_table, $params );
		return $result;
	}
	
	/*
	 * 查询所有区域信息
	 */
	function query_regions() {
		$sql = "select r.*,u.login_name from $this->region_table r left join user u on r.userId = u.id where r.userId = " . get_session ( "user_id" );
		
		return $GLOBALS ["db"]->query ( $sql );
	}
	
	/**
	 * 删除区域信息
	 */
	function delete_region($id) {
		$sql = "delete from $this->region_table where id = $id";
		
		return $GLOBALS ['db']->query ( $sql );
	}
	
	/*
	 * 更新区域信息
	 */
	function update_region($param) {
		$sql = "update $this->region_table set xMin = " . $param ['xMin'] . ",yMin = " . $param ['yMin'] . ",xMax = " . $param ['xMax'] . ",yMax = " . $param ['yMax'] . " where id = " . $param ['id'];
		
		return $GLOBALS ['db']->query ( $sql );
	}
	
	/**
	 * 设置车辆区域关系
	 */
	function set_vehicle_region($region_id, $vehicle_ids) {
		foreach ( $vehicle_ids as $key => $value ) {
			$join_id = $this->query_region ( $value );
			
			if (empty ( $join_id )) {
				$sql = "insert into $this->join_table(region_id,vehicle_id) values($region_id,$value)";
			
			} else {
				$sql = "update $this->join_table set region_id = $region_id,vehicle_id = $value where id = " . $join_id [0] ['id'];
			}
			
			$result = $GLOBALS ['db']->query ( $sql );
			
			if (! $result) {
				return false;
			}
		}
		
		return true;
	}
	
	/*
	 * 查找该车辆是否已绑定区域
	 */
	function query_region($vehicle_id) {
		$sql = "select id from $this->join_table where vehicle_id = $vehicle_id";
		
		return $GLOBALS ['db']->query ( $sql );
	}
}
?>
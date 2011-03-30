<?php
/**
 * 车辆管理类
 *
 *
 * @copyright 	  秦运恒, 2011
 * @author 　　赵将伟
 * @create date 　 2011.03.30
 * @modify  　　　 n/a
 * @modify date　　n/a
 * @todo			  n/a
 */
class Photo extends BASE {
	//	以下为每个类都必须有的变量
	public $tablename = "mesg_wait"; //车辆信息表
	

	/**
	 * 构造函数
	 */
	function Photo() {
	
	}
	
	/*
	 * 下发指令
	 */
	function assign_photo($param) {
		return $GLOBALS ['db']->insert_row ( $this->tablename, $param );
	}

}
?>
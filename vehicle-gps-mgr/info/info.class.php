<?php
	class info extends BASE{
		public $tablename = "user";
		public $data_list = false;			 //数据集合
		public $sql="";                         //SQL语句

		
		/***
		 *自定义一个向文件写入数据的函数
		 */
		function writeMessage($filename,$message) {    
			$fp = fopen($filename,"a");
			if (flock($fp,LOCK_EX)) {
				fwrite($fp,$message);
				flock($fp,LOCK_UN);
			}else{
				return 0;
			}
			fclose($fp);
		}
		
		/**
		 * 经纬度转换
		 * @param 经度或纬度
		 */
		function arroud($v){
			$real = $v/100000;
			return $real;
		}
		
		/***
		 * 查询车辆司机的邮箱
		 */
		function get_phone_email($str) {
			$sql="select phone_email FROM driver_manage WHERE ". 
                  "id in (SELECT driver_id FROM vehicle_manage WHERE id in (".$str."))";

			return $GLOBALS["db"]->query($sql);
									
		}
		
		/**
		 *  将发布的信息存入到数据库中
		 *  @param $params 要插入的信息
		 */
		function save_info($params){
			$result = $GLOBALS ["db"]->insert_row ( "info_issue", $params);
			return $result;
		}
		
		/**
		 *  将最小经纬度区域信息存入到数据库中
		 *  @param $params 要插入的信息
		 */
		function save_area_info_one($params){
			$result = $GLOBALS ["db"]->insert_row ( "area_info", $params);
			return $result;
		}
		
		/**
		 *  将最大经纬度区域信息存入到数据库中
		 *  @param $params 要插入的信息
		 */
		function save_area_info_two($params){
			$result = $GLOBALS ["db"]->insert_row ( "area_info", $params);
			return $result;
		}
		
	}
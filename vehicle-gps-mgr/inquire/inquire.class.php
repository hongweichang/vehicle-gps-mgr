<?php
class Inquire extends BASE
{
	/**
	*		查询所有车辆
	*		@param $wh 条件 $sidx 字段 $sord 排序 $start&$limit 取值区间
	*		@return no
	*/
	function get_all_vehicles()
	{
		$this->sql = "select id, number_plate from vehicle_manage where company_id = ".get_session("company_id");
		return $this->data_list = $GLOBALS["db"]->query($this->sql);
	}
}
?>

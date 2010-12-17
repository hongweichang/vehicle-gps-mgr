<?php

	
class Color_mapper
{
	private $tablename_common_setting = "common_setting";
	private $speed_color_list = array();
	
	/**
	 * 根据行车速度得到相应的颜色值
	 * @param unknown_type $speed
	 */
	function get_color($speed, $company_id)	
	{
		//查看$speed_color_list中是否有关于速度颜色映射的信息
		//如果有，则直接利用
		if(isset($this->speed_color_list))
		{
			return $this->get_color_by_speed($speed, $speed_color_list,$company_id);
		}
		else //如果没有，则从数据库中查询出来，然后设置到$speed_color_list中
		{
			//查询数据库
			$sql = sprintf("select min, max, color from speed_color where company_id = %d", $company_id);
			$data = $GLOBALS["db"]->query($sql);
			
			foreach($data as $value)
			{
				$speedStr = $value['min']."-".$value['max'];
				$speed_color_list[$speedStr] = $value['color'];		
			}
			
			return $this->get_color_by_speed($speed, $speed_color_list,$company_id);
		} 
	}
	
	/**
	 * 根据速度颜色映射表得到所需的颜色值
	 * @param $speed 速度
	 * @param $speed_color_list  速度颜色列表
	 * @param $company_id  公司ID
	 */
	private function get_color_by_speed($speed, $speed_color_list,$company_id)
	{
		foreach($speed_color_list as $key=>$value)
		{
			$speedPair = explode('-',$key);
			$speed_min = $speedPair[0];
			$speed_max = $speedPair[1];
			
			if(($speed >= $speed_min) && ($speed < $speed_max))
			{
				return $value;
			}
		}
		return $this->get_default_color($company_id);
	}
	/**
	 * 获取公司默认速度颜色
	 * @param unknown_type $company_id
	 */
	private function get_default_color($company_id=-1){
			 require('include/config.php');
		
			  $sql = "select default_color from ".$this->tablename_common_setting." where company_id =".$company_id;
			  $common_setting = $GLOBALS["db"]->query_once($sql);
			  
			  $color = null;
			  if(!isset($common_setting['default_color'])){ 
			  	 	$color = $default_setting['default_color'];
			  }else{
			  	    $color = $common_setting['default_color'];
			  }
			  return $color;
	}
}
?>
<?php
/**
* 	车辆管理类
*
*
* @copyright 	  秦运恒, 2010
* @author 　　郭英涛
* @create date 　 2010.07.31
* @modify  　　　 n/a
* @modify date　　n/a
* @modify describe   2010.07.31 20:55	文件生成
* @todo			  n/a
*/
class Vehicle_status extends BASE
{
	//	以下为每个类都必须有的变量
	public $tablename = "vehicle_manage";  //车辆信息表
	public $tablename2 = "driver_manage"; //驾驶员表
	public $data = false; //数据
	public $data_list = false; //数据集合
	public $sql; //SQL语句
	public $message; //消息
	private $id = false; //车辆管理表主键ID
	
	/**
	*		构造函数
	*		@param $id 
	*		@return no
	*/
	function Vehicle_status($id=false)
	{
		if($id && !empty($id))
		{
			$this->id = $id;
			$this->retrieve_data();
		}
	}
	
	/**
	*		查询得到指定车辆信息
	*		@param $id 
	*		@return no
	*/
	private function retrieve_data()
	{
		$this->sql = sprintf("select * from %s where id = %d",$this->tablename,$this->id);
		if ($this->data = $GLOBALS["db"]->query_once($this->sql))
			return $this->data;
		else
			return false;
	}
	
	
	/**
	*	实体函数的render，车辆对指定的列名称（字符串）进行润色、翻译
	*	@param $col_name 列名称（字符串）
	*	@return $o  润色翻译后的数值
	*/
	function child_render($col_name)
	{
		switch ($col_name)
		{
			case "vehicle_group_name":
				$vehicle_group = new Vehicle_group($this->get_data("vehicle_group_id"));
				$value = $vehicle_group->get_data("name");
				break;
			case "driver_name":
				$driver = new Driver($this->get_data("driver_id"));
				$value = $driver->data["name"];
				break;
			case "type_name":
//				$driver = new Driver($this->get_data("driver_id"));
//				$value = $driver->get_data("name");
				$value = 'ooo';
				break;
			case "v_alert_state":
				$par = "vehicle_manage";
				$child = "alert_state";
				$xml  = new Xml($par,$child);
				$xmldata = $xml->get_array_xml();
				$value = $xmldata[$this->get_data("alert_state")];
				break;
		}
		return $value;
	}
	
	/**
	*	查询所有车辆数目
	*	@param null
	*	@return numeric or null
	*/
	function get_vehicle_count()
	{ 
		$company_id = get_session("company_id"); //获取公司ID
		$this->sql = "select count(*) from ".$this->tablename." where company_id=".$company_id;
		$count = $GLOBALS["db"]->query_once($this->sql);
		return $count[0];
	}
	
	/**
	 *  模糊查询某类车牌号车辆数目
	 *  @param 车牌号前缀
	 * 
	 */
	function get_vehicle_count_plate($number_plate){
		$company_id = get_session("company_id");//获取公司ID
		$this->sql = "select count(*) from ".$this->tablename." where company_id=".$company_id." and number_plate like '%".$number_plate."%'";
		$count = $GLOBALS['db']->query_once($this->sql);
		return $count[0];
	}
	
	/**
	 *   查询某个经纬度范围内的所有车辆数目
	 *   @param $lonMin 最小经度 $lonMax 最大经度 $latMin最小纬度 $latMax最大纬度
	 */
	function get_lon_lat_count($lonMin,$lonMax,$latMin,$latMax){
		$company_id = get_session("company_id");//获取公司ID
		$this->sql = "select count(*) from ".$this->tablename." where company_id=".$company_id." 
		              and (cur_longitude between ".$lonMin." and ".$lonMax.") and 
		              (cur_latitude between ".$latMin." and ".$latMax.")";
		$count = $GLOBALS['db']->query_once($this->sql);
		return $count[0];
	}
	
	/**
	 *   查询某个经纬度范围内的包含指定车牌号的所有车辆数目
	 *   @param $lonMin 最小经度 $lonMax 最大经度 $latMin最小纬度 $latMax最大纬度 $number_plate车牌号
	 */
	function get_lon_lat_plate_count($lonMin,$lonMax,$latMin,$latMax,$number_plate){
		$company_id = get_session("company_id");//获取公司ID
    	$this->sql = "select count(*) from ".$this->tablename." where company_id=".$company_id." 
		              and (cur_longitude between ".lonMin." and ".lonMax.") and 
		              (cur_latitude between ".latMin." and ".latMax.") and number_plate like 
		              '%".$number_plate."%'";
    	$count = $GLOBALS['db']->query_once($this->sql);
    	return $count[0];
	}
	
	/**
	*		查询所有车辆
	*		@param $wh 条件 $sidx 字段 $sord 排序 $start&$limit 取值区间
	*		@return no
	*/
	function get_all_vehicles($wh="",$sidx="",$sord="",$start="",$limit="")
	{
		//$company_id = get_session("company_id");
		$this->sql = "select * from ".$this->tablename." ".$wh." order by ".$sidx." ". $sord." LIMIT ".$start." , ".$limit;
		$this->data_list = $GLOBALS["db"]->query($this->sql);
		return $this->data_list;
	}
	
	/**
	 *   模糊查询匹配车牌号的所有车辆
	 *   @param $number_plate 车牌号前缀
	 */
	function get_all_vehicles_number($number_plate){
		$company_id = get_session("company_id");//获取公司ID
		$this->sql = "select * from ".$this->tablename." where company_Id=".$company_id." and number_plate like '%".$number_plate."%'";
		return $this->data_list = $GLOBALS['db']->query($this->sql);
	}
	
	/**
	*		得到指定字段类型
	*		@param $searchfield 字段名
	*		@return mixed
	*/
	function get_type($searchfield=false)
	{
		
		if(!$searchfield)
		{
			$this->message = 'error,Searchfield is not exists!';
			return false;
		}
		$type = $GLOBALS["db"]->get_field_type($this->tablename,$searchfield);
		if(!$type)
		{
			$this->message = 'error,Get Field type failed!';
			return false;
		}
		return $type;
	}
	
	/**
	*		得到外键对应的所有name选择下拉列表（有选定状态）
	*		@param $tablename 外键对应的表名 $fieldname 字段名
	*		@return mixed
	*/
	function get_select($tablename,$fieldname)
	{
		$this->sql = sprintf("select id,%s from %s",$fieldname,$tablename);
		//file_put_contents("a.txt",$this->sql);
		$result = $GLOBALS['db']->query($this->sql);
		$select = '<select>';
		foreach($result as $temp)
		{
			$select .= "<option value='".$temp['id']."'>".$temp['name']."</option>";
		}
		$select .= '<select>';
		return $select;
	}

     /**
      *     根据驾驶员id查询驾驶员信息
      *     @param $driver_id 驾驶员编号
      */
    function get_driver($driver_id){
    	$this->sql = "select * from ".$this->tablename2." where id=".$driver_id;
    	$driver = $GLOBALS['db']->query($this->sql);
    	return $driver;
    }
    
    /**
     * 经纬度转换
     * @param $v 经度或纬度在数据库中的值
     */
    function around($v=-1,$e=0){
				$v= $v*100000;
				$t=1;   
					for(;$e>0;$t*=10,$e--);   
					for(;$e<0;$t/=10,$e++);   

				return  round($v*$t)/$t;   
			  } 
			  
	/**
	 *  将经纬度转换成数据库中的值
	 *  @param $经度或纬度值
	 */
	function e_around($v){
		$v = $v/100000;
		return $v;
	}
    
   /**
     * 从xml文字格式中解析地址信息
     * @param 字符串 $location
     */
    private function parse_location_decs($location)
    {
    	$begin = stripos($location,"<msg>")+5; //获取地址信息开始位置
    	$end = stripos($location,"</msg>"); //获取结束位置
    	$length=$end-$begin; //获取地址长度
    	$location_decs = substr($location,$begin,$length); //得到地址信息
    	return $location_decs;
    }
			  
			  
     /**
     * 根据经纬度信息得到地址描述信息
     * @param $cur_longitude
     * @param $cur_latitude
     */
    function get_location_desc($cur_longitude,$cur_latitude)
    {
    	//将经纬度转换成51地图规定的经纬度
    	$longitude_51ditu=$this->around($cur_longitude, 0); 
    	$latitude_51ditu=$this->around($cur_latitude, 0);
    	
    	//把经纬度值的后三位置0
    	$longitude = intval($longitude_51ditu /1000) * 1000; 
    	$latitude = intval($latitude_51ditu / 1000) * 1000;
    	
    	$sql = "select * from gis_pos_info where pos_longitude =".$longitude." and pos_latitude = ".$latitude." LIMIT 1";
 		$gis_info = $GLOBALS["db"]->query($sql);
 		
 		$location_desc = ""; //声明地址描述信息
 		if($gis_info)
 		{
 			$location_desc = $gis_info[0]["pos_desc"];
 		}
 		else
 		{
 			//获取地址信息并将gb2312编码转换为utf-8
 			$location = iconv("gb2312", "utf-8",file_get_contents("http://ls.vip.51ditu.com/mosp/gc?pos=".$longitude_51ditu.",".$latitude_51ditu));
 		 	
 			$location_desc = $this->parse_location_decs($location);//从XML中解析地址信息
 			
 			
 		 	$parms["pos_longitude"] = $longitude;
 		 	$parms["pos_latitude"] = $latitude;
 		 	$parms["pos_desc"] = "\"".$location_desc."\"";
 		 	$parms["getdate"] = "\"".date("Y-m-d")."\"";
 		 	
 		 	if($location_desc != "")
 		 	{
 		 		$GLOBALS["db"]->insert_row("gis_pos_info",$parms);
 		 	}
 		}
 		
 		return $location_desc;
    }
    
    /**
     *     判断gps状态 ，0无，1有
     *     @param $gps_status gsp状态
     */
    function gps_status_boolean($gps_status){
    	
    	if($gps_status==null or $gps_status==0) return "离线";
    	if($gps_status==1) return "在线";
    }
    
    /**
     *  查询某经纬度之间的所有车辆
     *  @param $lonMin 最小经度 $lonMax 最大经度 $latMin最小纬度 $latMax最大纬度
     */
    function get_lon_lat_vehicle($lonMin,$lonMax,$latMin,$latMax){
        $company_id = get_session("company_id"); //获取公司ID
    	$this->sql = "select * from ".$this->tablename." where company_id=".$company_id." 
		              and (cur_longitude between ".$lonMin." and ".$lonMax.") and 
		              (cur_latitude between ".$latMin." and ".$latMax.")";
    	return $this->data_list = $GLOBALS['db']->query($this->sql);
    }
    
    /**
     *   查询某经纬度之间的包含指定车牌号的所有车辆
     *   @param $lonMin 最小经度 $lonMax 最大经度 $latMin最小纬度 $latMax最大纬度 $number_plate模糊车牌号
     */
    function get_lon_lat_plate_vehicle($lonMin,$lonMax,$latMin,$latMax,$number_plate){
    	$company_id = get_session("company_id");//获取公司ID
    	$this->sql = "select * from ".$this->tablename." where company_id=".$company_id." 
		              and (cur_longitude between ".$lonMin." and ".$lonMax.") and 
		              (cur_latitude between ".$latMin." and ".$latMax.") and number_plate like 
		              '%".$number_plate."%'";
    	$this->data_list = $GLOBALS['db']->query($this->sql);
    	return $this->data_list;
    }
    
    /**
     *   查询所有车辆的经纬度 
     */
    function get_lon_lat (){
    	$company_id = get_session("company_id");//获取公司ID
    	$this->sql = "select cur_longitude,cur_latitude from ".$this->tablename.
    	             " where company_id=".$company_id;
    	$this->data_list = $GLOBALS['db']->query($this->sql);
    	return $this->data_list;
    }
    
    /**
     *   精确定位经度
     *   @param $long 经度 
     */
    function exact_lon($long){
		$this->sql = "select longdisp from lingtu_map_info where minlong <".$long." and maxlong>".$long;
		$longs = $GLOBALS['db']->query($this->sql);
		$long = $longs[0][0]+$long;  	
		return $long;
    }
    
/**
     *   精确定位纬度
     *   @param $lat 纬度 
     */
    function exact_lat($lat){
		$this->sql = "select latdisp from lingtu_map_info where minlat <".$lat." and maxlat>".$lat;
		$lats = $GLOBALS['db']->query($this->sql);
		$lat = $lats[0][0]+$lat;  	
		return $lat;
    }
    
    
    /**
     *  根据经纬度查询地址 
     * @param $cur_longitude 经度
     * @param $cur_latitude 纬度
     */
 function get_location($cur_longitude,$cur_latitude)
    {
    	
    	//把经纬度值的后三位置0
    	$longitude = intval($cur_longitude /1000) * 1000; 
    	$latitude = intval($cur_latitude / 1000) * 1000;
    	
    	$sql = "select * from gis_pos_info where pos_longitude =".$longitude." and pos_latitude = ".$latitude." LIMIT 1";
 		$gis_info = $GLOBALS["db"]->query($sql);
 		
 		$location_desc = ""; //声明地址
 		if($gis_info)
 		{
 			$location_desc = $gis_info[0]["pos_desc"];
 		}
 		else
 		{
 			//获取地址信息并将gb2312编码转换为utf-8
 			$location = iconv("gb2312", "utf-8",file_get_contents("http://ls.vip.51ditu.com/mosp/gc?pos=".$cur_longitude.",".$cur_latitude));
 		 	
 			$location_desc = $this->parse_location_decs($location); //从xml中解析地址信息
 			
 		 	$parms["pos_longitude"] = $longitude;
 		 	$parms["pos_latitude"] = $latitude;
 		 	$parms["pos_desc"] = "\"".$location_desc."\"";
 		 	$parms["getdate"] = "\"".date("Y-m-d")."\"";
 		 	
 		 	if($location_desc != "")
 		 	{
 		 		$GLOBALS["db"]->insert_row("gis_pos_info",$parms);
 		 	}
 		}
 		
 		return $location_desc;
    }
}
?>
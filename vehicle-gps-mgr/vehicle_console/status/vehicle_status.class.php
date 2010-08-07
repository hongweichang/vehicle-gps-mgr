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
	public $tablename = "vehicle_manage"; 
	public $tablename2 = "driver_manage";//驾驶员表
	public $data = false;                //数据
	public $data_list = false;					 //数据集合
	public $sql;                         //SQL语句
	public $message;                     //消息
	
	private $id = false;		//车辆管理表主键ID
	
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
		$company_id = get_session("company_id");
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
		$company_id = get_session("company_id");
		$this->sql = "select count(*) from ".$this->tablename." where company_id=".$company_id." and number_plate like '".$number_plate."%'";
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
		$company_id = get_session("company_id");
		$this->sql = "select * from ".$this->tablename." where company_Id=".$company_id." and number_plate like '".$number_plate."%'";
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
    function around($v,$e){
				$v= $v*100000;
				$t=1;   
					for(;$e>0;$t*=10,$e--);   
					for(;$e<0;$t/=10,$e++);   

				return  round($v*$t)/$t;   
			  } 
    
    
    /**
     *     确定当前位置
     *     @param $cur_longitude 当前经度 $cur_latitude当前纬度
     */
    function get_cur_location($cur_longitude,$cur_latitude){
           
    	$longitude=$this->around($cur_longitude);
    	$latitude=$this->around($cur_latitude);
    	
        $location = iconv("gb2312", "utf-8",file_get_contents("http://ls.vip.51ditu.com/mosp/gc?pos=".$longitude.",".$latitude));

    	
    	$begin = stripos($location,"<msg>")+5;
    	$end = stripos($location,"</msg>");
    	$length=$end-$begin;
    	$location = substr($location,$begin,$length);
    	return $location;
    }
    
    /**
     *      判断告警信息 0无告警，1超速，2疲劳
     *      @param $alert_state告警状态
     */
    function alert_status($alert_state){
    	
    	if($alert_state==0 or $alert_state==null) return "无";
    	if($alert_state==1) return "超速";
    	if($alert_state==2) return "疲劳";
    	
    }
    
    /**
     *     判断gps状态 ，0无，1有
     *     @param $gps_status gsp状态
     */
    function gps_status_boolean($gps_status){
    	
    	if($gps_status==null or $gps_status==0) return "无";
    	if($gps_status==1) return "有";
    }
    
}
?>
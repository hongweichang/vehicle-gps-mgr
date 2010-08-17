<?php

/**
 * 
 * @author 郭英涛
 * gps信息类
 *
 */
class TraceInfo
{
	public $longitude;
	public $latitude;
	public $color;
	public $location_desc;
	public $speed;
	public $direction;
	public $img_path;
	public $location_time;
}

/**
 * 
 * @author 郭英涛
 * 位置信息解析类
 *
 */
class Position_parser
{
	private $file; //gps定位信息文件路径
	private $company_id; //公司id
	private $gps_id; //gps设备号
	private $index; //该gps_id前一条数据的索引值（代表从文件头偏移到该行数据的字节偏移量）
	private $first_gps_id;
	private $time; //时间
	
	private $info_list = array();
	
	/**
	 * 位置信息解析类 构造函数
	 * @param unknown_type $company_id  公司id
	 * @param unknown_type $filepath  文件路径 
	 * @param unknown_type $vehicle_id 车辆id
	 * @param unknown_type $time 时间
	 */
	function __construct($company_id,$filepath,$vehicle_id,$time)
	{
		$this->company_id = $company_id;
		$this->time = $time;
		$this->gps_id = $this->get_gps_id($vehicle_id);
		//判断session中index是否存在，如果存在，则用session中的index
		/*if($_SESSION["readfile_finished"] == 0)
		{
			$this->index = $_SESSION["gps_info_index"];
		}
		else 
		{
			$this->index = $this->get_file_index($this->gps_id,$this->time);
		}*/
		$this->index = $this->get_file_index($this->gps_id,$this->time);
		$this->file = fopen($filepath, "r") or exit("Unable to open file!");
		
		if($this->file)
		{
			rewind($this->file);
			$line_data = fgets($this->file);
			if($line_data)
			{
				$data_list = explode('~',$line_data);
				$this->first_gps_id = $data_list[1];
			}
		}
		
	}
	/**
	 * 获取GPS编号
	 * @param $vehicle_id 车辆id
	 */
	function get_gps_id($vehicle_id)
	{
		$sql = "select gps_id from vehicle_manage where id = ".$vehicle_id." Limit 1";
		$data = $GLOBALS["db"]->query_once($sql);
		return $data[0];		
	}
	
	/**
	 * 按GPS编号获取其文件索引值
	 * @param unknown_type $gps_id   GPS编号 
	 * @param unknown_type $time
	 */
	function get_file_index($gps_id,$time)
	{
		$sql = "select file_index from position_info where gps_id = ".$gps_id." and receive_time='".$time."'";
		$data = $GLOBALS["db"]->query_once($sql);
		return $data[0];
	}
	
	function __destruct()
	{
		fclose($this->file);
	}
	
	/**
	 * 读取车辆画线路径定位数据
	 */
	private function readLineData()
	{
		$vehicle_status = new Vehicle_status();
		$color_mapper = new Color_mapper();
		if($this->file)
		{
			fseek($this->file,$this->index);
			$line_data = fgets($this->file);
			if($line_data)
			{
				$data_list = explode('~',$line_data);
				
				/*
				if($_SESSION["readfile_finished"] == 1) //本次半小时处理尚未进行
				{
					if(intval(substr($data_list[6],2,2)) >= 30) //截取时间中的分钟部分和30分钟进行对比
					{
						$_SESSION["gps_info_index"] = $this->index;
						return "pause";
					}
				}*/
				
				$this->index = $data_list[0];
				
				$trace_info = new TraceInfo();
				
				/**纬度*/
				$trace_info->latitude = $data_list[3];  
				/**经度*/
				$trace_info->longitude = $data_list[4]; 
				/**定位时间*/
				$trace_info->location_time = $data_list[5].$data_list[6]; 
				/**速度*/
				$trace_info->speed = $data_list[11]; 
				/**方向*/
				$trace_info->direction = $data_list[12]; 
				/**颜色*/
				$trace_info->color = $color_mapper->get_color($trace_info->speed, $this->company_id); 
				/**地址描述*/
				//$trace_info->location_desc = $vehicle_status->get_location_desc($trace_info->longitude, $trace_info->latitude); 
				/**图片路径*/
				$trace_info->img_path = $this->get_img_path($trace_info->color, $trace_info->direction); 
				
				//堆栈定位数据数组
				array_push($this->info_list, $trace_info);
			}
		}
	}
	
	/**
	 * 按颜色、方向， 获取车辆对应图片路径
	 * @param $color   颜色
	 * @param $direction 方向
	 */
	function get_img_path($color, $direction)
	{
		require_once("include/data_mapping_handler.php");
		//创建XML解析对象
		$xml_handler =  new Data_mapping_handler($GLOBALS["all"]["BASE"]."xml/color.xml");
		$img_path = str_ireplace("west.png","",$xml_handler->getTextData("color","#".$color)); //图片路径
		$img_path = $img_path.resolvingDirection($direction).".png"; 
		
		return $img_path;
	}
	/**
	 * 获取数据列表 
	 */
	function getDataList()
	{
		if($this->file)
		{
			while(true)
			{
				if(0 == $this->index)
				{
					if($this->gps_id == $this->first_gps_id)
					{
						$this->readLineData();
					}
					//$_SESSION["readfile_finished"] = 1; //处理完一个文件后，设置完成标志位为1
					break;
				}
				else
				{
					$this->readLineData();
					/*if("pause" == $this->readLineData())
					{
						$_SESSION["readfile_finished"] = 0; //本次半小时处理已经进行
						break;
					}*/
				}
			}
			return $this->info_list;
		}
		else
		{
			return null;
		}
	}
}
?>
<?php
require_once 'areaInfo.php';

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
	private $TIME_BASE_LINE = "2010-11-10 22:00:00"; //从2010年11月11日起，轨迹信息文件按照gps设备的尾号分别存储。
	private $color_mapper;
	
	private $info_list = array();
	
	/**
	 * 位置信息解析类 构造函数
	 * @param unknown_type $company_id  公司id
	 * @param unknown_type $filepath  文件路径 
	 * @param unknown_type $vehicle_id 车辆id
	 * @param unknown_type $time 时间
	 */
	function __construct($company_id, $vehicle_id, $time)
	{
		$this->color_mapper = new Color_mapper();
		
		$this->company_id = $company_id;
		$this->time = $time;
		$this->gps_id = $this->get_gps_id($vehicle_id);
		$this->index = $this->get_file_index($this->gps_id,$this->time);
		
	
		$filepath = $this->get_logfile_path($time, $this->gps_id);
		
		//当文件存在的时候采取读，直接打开有时候服务器会长时间无反应
		if(file_exists($filepath)){
			$this->file = fopen($filepath, "r");
				
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
	}
	
   /**
     * TIME_BASE_LINE之后的轨迹信息按照时间和设备编号的尾号查找数据文件
     * 之前的轨迹信息只按照时间来查找数据文件
     * @param $time
     * @param $id
     */
    function get_logfile_path($time, $gps_id){
    	require('include/config.php');
    	$time_str = substr($time, 0, 4)."-".substr($time, 4, 2)."-".substr($time, 6, 2)
    	." ".substr($time, 8 ,2).":00:00";
    	$t1 = strtotime($time_str);
    	$t2 = strtotime($this->TIME_BASE_LINE);
    	if(strtotime($time_str) >= strtotime($this->TIME_BASE_LINE)){
    		$time = $time."_".substr($gps_id,-1);
    	}
    	
    	return $server_path_config["gps_info_path"]."/".$time.".log";
    	//return $gps_info_path = $GLOBALS["all"]["BASE"]."/log/".$time.".log"; //本地测试用路径
    }
	
	/**
	 * 获取GPS编号
	 * @param $vehicle_id 车辆id
	 */
	function get_gps_id($vehicle_id)
	{
		//$sql = "select gps_id from vehicle_manage where id = ".$vehicle_id." Limit 1";
		$sql = "select gps_number from gps_equipment where id=(select gps_index_id from vehicle_manage where id = ".$vehicle_id." Limit 1)";
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
		//当文件已经被打开的时候才进行关闭！
		if($this->file){
			fclose($this->file);
		}
	}
	
	/**
	 * 读取车辆画线路径定位数据
	 */
	private function readLineData()
	{
		$vehicle_status = new Vehicle_status();

		if($this->file)
		{
			fseek($this->file,$this->index);
			$line_data = fgets($this->file);
			if($line_data)
			{
				$data_list = explode('~',$line_data);
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
				$trace_info->color = $this->color_mapper->get_color($trace_info->speed, $this->company_id); 
				/**地址描述*/
				$trace_info->location_desc = $vehicle_status->get_location_desc($trace_info->longitude, $trace_info->latitude); 
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
					break;
				}
				else
				{
					$this->readLineData();
				}
			}
			return $this->info_list;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * 判断某辆车是否在指定区域内
	 * @param unknown_type $areaInfo
	 */
	function is_in_area($areaInfo)
	{
		if($this->file)
		{
			while(true)
			{
				if(0 == $this->index)
				{
					if($this->gps_id == $this->first_gps_id)
					{
						if($this->check_in_area($areaInfo))
						{
							return true;
						}
					}
					break;
				}
				else
				{
					if($this->check_in_area($areaInfo))
					{
						return true;
					}
				}
			}
			return false;
		}
		else
		{
			return false;
		}
		
	}
	
	/**
	 * 从文件中取出指定车辆的位置信息，并判断是否在指定区域内
	 * @param $areaInfo
	 */
	function check_in_area($areaInfo)
	{
		if($this->file)
		{
			fseek($this->file,$this->index);
			$line_data = fgets($this->file);
			if($line_data)
			{
				$data_list = explode('~',$line_data);
				$this->index = $data_list[0];
				$position = new Position(around($data_list[4]),around($data_list[3]));
				if($this->is_position_in_area($position, $areaInfo))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 判断一个位置点是否在指定的区域内
	 * @param unknown_type $position
	 * @param unknown_type $areaInfo
	 */
	function is_position_in_area($position, $areaInfo){
		return ($position->longitude >= $areaInfo->positionList[0]->longitude
		&& $position->longitude <= $areaInfo->positionList[1]->longitude
		&& $position->latitude >= $areaInfo->positionList[0]->latitude
		&& $position->latitude <= $areaInfo->positionList[1]->latitude);
	}
}

?>
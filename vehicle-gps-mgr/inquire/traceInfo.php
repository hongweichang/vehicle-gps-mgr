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
	
	private $info_list = array();
	
	function __construct($company_id,$filepath,$vehicle_id)
	{
		$this->company_id = $company_id;
		$this->gps_id = $this->get_gps_id($vehicle_id);
		$this->index = $this->get_file_index($this->gps_id);
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
	
	function get_gps_id($vehicle_id)
	{
		$sql = "select gps_id from vehicle_manage where id = ".$vehicle_id." Limit 1";
		$data = $GLOBALS["db"]->query_once($sql);
		return $data[0];		
	}
	
	function get_file_index($gps_id)
	{
		$sql = "select file_index from position_info where gps_id = ".$gps_id." Limit 1";
		$data = $GLOBALS["db"]->query_once($sql);
		return $data[0];
	}
	
	function __destruct()
	{
		fclose($this->$file);
	}
	
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
				$this->index = $data_list[0];
				
				$trace_info = new TraceInfo();
				$trace_info->latitude = $data_list[3];
				$trace_info->longitude = $data_list[4];
				$trace_info->location_time = $data_list[5].$data_list[6];
				$trace_info->speed = $data_list[11];
				$trace_info->direction = $data_list[12];
				$trace_info->color = $color_mapper->get_color($trace_info->speed, $this->company_id);
				$trace_info->location_desc = $vehicle_status->get_location_desc($trace_info->longitude, $trace_info->latitude);
				$trace_info->img_path = $this->get_img_path($trace_info->color, $trace_info->speed);
				array_push($this->info_list, $trace_info);
			}
		}
	}
	
	function get_img_path($color, $direction)
	{
		require_once("include/data_mapping_handler.php");
		//创建XML解析对象
		$xml_handler =  new Data_mapping_handler($GLOBALS["all"]["BASE"]."xml/color.xml");
		$img_path = str_ireplace("west.png","",$xml_handler->getTextData("color","#".$color)); //图片路径
		$img_path = $img_path.resolvingDirection($direction)."jpg";
		return $img_path;
	}
	
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
					else
					{
						break;
					}
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
}
?>
<?php

/**
 * 
 * @author 郭英涛
 * gps信息类
 *
 */
class gpsInfo
{
	public $longitude;
	public $latitude;
	public $speed;
	public $direction;
	public $location_time;
}

/**
 * 
 * @author 郭英涛
 * 位置信息解析类
 *
 */
class position_parser
{
	private $file; //gps定位信息文件路径
	private $gps_id; //gps设备号
	private $index; //该gps_id前一条数据的索引值（代表从文件头偏移到该行数据的字节偏移量）
	private $first_gps_id;
	
	private $info_list = array();
	
	function __construct($filepath,$index,$gps_id)
	{
		$this->gps_id = $gps_id;
		$this->index = $index;
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
	
	function __destruct()
	{
		fclose($this->$file);
	}
	
	private function readLineData()
	{
		if($this->file)
		{
			fseek($this->file,$this->index);
			$line_data = fgets($this->file);
			if($line_data)
			{
				$data_list = explode('~',$line_data);
				$this->index = $data_list[0];
				
				$gpsInfo = new gpsInfo();
				$gpsInfo->latitude = $data_list[3];
				$gpsInfo->longitude = $data_list[4];
				$gpsInfo->location_time = $data_list[5].$data_list[6];
				$gpsInfo->speed = $data_list[11];
				$gpsInfo->direction = $data_list[12];
				array_push($this->info_list, $gpsInfo);
			}
		}
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
<?php
class Position
{
	public $longitude = 0;
	public $latitude = 0;
	
	function __construct($longitude, $latitude)
	{
		$this->longitude = $longitude;
		$this->latitude = $latitude;
	}
}

class AreaInfo
{
	public $positionList = array();
	public $radius = 0;
	public $type = 0; //0-矩形 1-圆形 2-多边形
}
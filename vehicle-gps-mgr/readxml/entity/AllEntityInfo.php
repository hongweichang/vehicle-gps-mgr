<?php

class AllEntityInfo {
    public  $val; 
	public $text;
    
     /**
	 * 对所有属性的get set 方法
	 * @param $param
	 */
	function _get($param) {
		return $this->$param;
	}
	function _set($param, $value) {
		$this->$param = $value;
	}
}
?>
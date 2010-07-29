<?php
/** 
 * 功能说明      数据映射文件解析类
 * @copyright  秦运恒, 2010
 * @author 　　　　段贵山
 * @create date 　 2010-7-29 
 */

class Data_mapping_handler {
	private $file_handler;
	
	/**
	 * 构造函数
	 * @param $filepath xml文件全路径
	 */
	function Data_mapping_handler($filepath) {
		$this->file_handler = new DOMDocument ();
		$this->file_handler->load ( $filepath );
	}
	
	/**
	 * 返回xml文件映射数据库的字符串
	 * @param $tableName section名,对应的数据库中的表名
	 * @param $colName   section名,对应的数据库中的列名
	 * @param $value     item的属性值
	 * @return $displayText对应的返回字符串    
	 */
	function getMappingText($tableName, $colName, $value) {
		$XML_info = $this->file_handler->getElementsByTagName ( $tableName )->item ( 0 );
		foreach ( $XML_info->getElementsByTagName ( $colName ) as $xml_info ) {
			foreach ( $xml_info->getElementsByTagName ( 'item' ) as $items ) {
				$val = $items->getAttribute ( "value" );
				$text = $items->getAttribute ( "displayText" );
				if ($val == $value) {
					$displayText = iconv ( "UTF-8", "GB2312", $text );
				}
			}
		}
		return $displayText;
	}

	/**
	 * 
	 * @param $tableName section名,对应的数据库中的表名
	 * @param $colName   section名,对应的数据库中的列名
	 * @return $dataList 对应的返回数组
	 */
	function getMappingDataList($tableName, $colName) {
		$dataList = array ();
		$XML_info = $this->file_handler->getElementsByTagName ( $tableName )->item ( 0 );
		foreach ( $XML_info->getElementsByTagName ( $colName ) as $xml_info ) {
			foreach ( $xml_info->getElementsByTagName ( "item" ) as $items ) {
				$val = $items->getAttribute ( "value" );
				$text = $items->getAttribute ( "displayText" );
				
				$key = iconv ( "UTF-8", "GB2312", $val );
				$displayText = iconv ( "UTF-8", "GB2312", $text );
				$dataList [$key] = $displayText;
			}
		}
		return $dataList;
	}
}

?>
<?php
//require ('./../entity/AllEntityInfo.php'); //导入实体类


/**
 * @param unknown_type $paramStr
 */

class Xmlfile_resolve{
	
	function resolve($tableName,$colName) {
		$xml = new DOMDocument ();
		$xml->load ( 'xml/comm_setting.xml' );
		$array = array ();
		$XML_info = $xml->getElementsByTagName ( $tableName)->item ( 0 );
		foreach ( $XML_info->getElementsByTagName ( $colName ) as $xml_info ) {
			foreach  ($xml_info->getElementsByTagName ( 'item' ) as $items){
				$val= $items->getAttribute ( 'value' );
				$text=$items->getAttribute ( 'displayText' );
				
				$value = iconv ( "UTF-8", "GB2312", $val ); 
				$displayText = iconv ( "UTF-8", "GB2312", $text );
				
				$param=new AllEntityInfo();
				$param->val=$val;
				$param->text=$text;
				$array[$value]=$param;
			}
		}
		return $array;
	}
}
 

?>
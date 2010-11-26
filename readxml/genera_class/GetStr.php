<?php
//require ('./../entity/AllEntityInfo.php'); //导入实体类


/**
 * @param unknown_type $paramStr
 */

class GetStr {
	
	function resolve($tableName,$colName,$num) {
		$xml = new DOMDocument ();
		$xml->load ( 'xml/NewFile.xml' );
		$array = array ();
		$XML_info = $xml->getElementsByTagName ( $tableName)->item ( 0 );
		foreach ( $XML_info->getElementsByTagName ( $colName ) as $xml_info ) {
			foreach  ($xml_info->getElementsByTagName ( 'item' ) as $items){
				$val= $items->getAttribute ( 'value' );
				$text=$items->getAttribute ( 'displayText' );
				if($val==$num){
					$str = iconv ( "UTF-8", "GB2312", $text ); 
					
				}			
			}
		}
		return $str;
	}
}
 

?>

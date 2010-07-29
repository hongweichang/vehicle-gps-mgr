<?php

	/**
	*	得到 xml 内容
	*/

	$act = $GLOBALS["all"]["operate"];
	
	$par = $_REQUEST["par"];
	$child = $_REQUEST["child"];

	switch($act)
	{
		case "read_xml":

			// 性别
			$xml  = new Xml($par,$child);
			$html = $xml->get_html_xml();
			echo $html;

			break;
	}
?>
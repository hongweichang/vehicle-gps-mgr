<?php

	/**
	*	�õ� xml ����
	*/

	$act = $GLOBALS["all"]["operate"];
	
	$par = $_REQUEST["par"];
	$child = $_REQUEST["child"];

	switch($act)
	{
		case "read_xml":

			// �Ա�
			$xml  = new Xml($par,$child);
			$html = $xml->get_html_xml();
			echo $html;

			break;
	}
?>
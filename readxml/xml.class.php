<?php

	/**
	*	�õ� xml ����
	*/

class Xml{

	private $par;
	private $child;
	private $xmldata;
	private $newdata = array();

	function Xml($par,$child)
	{
		$this->par = $par;
		$this->child = $child;

		$isarea = new Xmlfile_resolve();
		$this->xmldata = $isarea->resolve ( $this->par, $this->child );
	}
	//�����õ�
	function get_array_xml()
	{
		foreach ( $this->xmldata as $key => $value )
		{
			$this->newdata[$value->val] = $value->text;
		}
		return $this->newdata;
	}

	//ҳ�����õ� ƴһ�� html
	function get_html_xml()
	{
		$html = "<select>";
		foreach ( $this->xmldata as $key => $value ) 
		{
			$html .= "<option value='".$value->val."'>".$value->text."</option>";
		}
		$html .= "</select>";
		return $html;
	}
}
?>
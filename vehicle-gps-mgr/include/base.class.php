<?php
/** 
* 功能说明        这是所有类的基类。用于处理render, render_all
* @copyright 	  Brother In Arms, 2008
* @author 　　　　李少杰
* @create date 　 2009-5-14 12:01
* @modify  　　　 李少杰
* @modify date　　2009-5-14 12:01
*/
class BASE
{
	/**
	*		对一个二位数组进行润色
	*/
	function render_all($col_list)
	{
		if (!$this->data_list)
			return false;
		
		foreach ($this->data_list as &$data_row)
		{
			$this->data = &$data_row;
			$this->post_retrieve();
			$rtn_array[] = $this->render($col_list);
		}
		unset($data_row);
		return $rtn_array;
	}

	/**
	*		判断类的数据是否已经正确取得。
	*/
	function is_valid()
	{
		if ($this->data)
			return true;
		else
			return false;
	}
		
	/**
	*		只是为了避免在子类没有post_retrieve的情况下出错
	*/
	function post_retrieve()
	{
	}

	/**
	*		根据属性名称取得属性值
	*			这个属性值可以是原始的名称，可以是翻译之后的名称
	*/
	function get_data($str)
	{
		if (!isset($this->data[$str]))
			$this->render(array($str));
		return $this->data[$str];
	}

	/**
	*		对当前的数据进行润色,以便于后面的显示工作
	*
	*		r_column_name，直接返回原始数值
	*		vx_column_name，返回润色后的数值
	*/
	function render($col_list)
	{
		if (!$col_list)
			return array();
		unset($rtn_array);
		foreach ($col_list as $col_name)			//对每个需求的列,逐行处理
		{
			if (!isset($this->data[$col_name]))
			{
				$pos = strpos($col_name,"_");			//这几行处理r_xxx，直接返回原始数值
				$prefix = substr($col_name,0,$pos);
				$column = substr($col_name,$pos+1);
				if ($prefix == "r")
					$this->data[$col_name] = $this->data[$column];
				else
					$this->data[$col_name] = $this->child_render($col_name);
			}
			$rtn_array[$col_name] = $this->data[$col_name];
		}
		return $rtn_array;
	}

}
?>
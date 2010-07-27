<?php
/** 
* ����˵��        ����������Ļ��ࡣ���ڴ���render, render_all
* @copyright 	  Brother In Arms, 2008
* @author �����������ٽ�
* @create date �� 2009-5-14 12:01
* @modify  ������ ���ٽ�
* @modify date����2009-5-14 12:01
*/
class BASE
{
	/**
	*		��һ����λ���������ɫ
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
	*		�ж���������Ƿ��Ѿ���ȷȡ�á�
	*/
	function is_valid()
	{
		if ($this->data)
			return true;
		else
			return false;
	}
		
	/**
	*		ֻ��Ϊ�˱���������û��post_retrieve������³���
	*/
	function post_retrieve()
	{
	}

	/**
	*		������������ȡ������ֵ
	*			�������ֵ������ԭʼ�����ƣ������Ƿ���֮�������
	*/
	function get_data($str)
	{
		if (!isset($this->data[$str]))
			$this->render(array($str));
		return $this->data[$str];
	}

	/**
	*		�Ե�ǰ�����ݽ�����ɫ,�Ա��ں������ʾ����
	*
	*		r_column_name��ֱ�ӷ���ԭʼ��ֵ
	*		vx_column_name��������ɫ�����ֵ
	*/
	function render($col_list)
	{
		if (!$col_list)
			return array();
		unset($rtn_array);
		foreach ($col_list as $col_name)			//��ÿ���������,���д���
		{
			if (!isset($this->data[$col_name]))
			{
				$pos = strpos($col_name,"_");			//�⼸�д���r_xxx��ֱ�ӷ���ԭʼ��ֵ
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
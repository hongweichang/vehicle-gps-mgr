<?php
/** 
* 功能说明       翻译
* @copyright 	  Brother In Arms, 2008
* @author 　　　　李少杰
* @create date 　 2008-8-13 10:31
* @modify  　　　 李少杰
* @modify date　　2009-1-2 17:33
* @modify describe 增加了使用memcached
*/
Class Translate
{
	public $tablename ;    //数据库中表名
	public $data;                        //数据
	public $raw_data;					 //从数据库直接读取到的数据
	public $sql;                         //SQL语句
	public $message;                     //一些消息
	
	private $keyname;
	private $key_value = false;

	/**
	*		从数据库里取出来表数据
	*/	
	function Translate($tbl_name,$key_name,$where_clause = "", $order_by = "1")
	{
		$cache_key = md5($where_clause);
		//	尝试从缓存中读取
		if (isset($GLOBALS["dm"][$tbl_name."_".$cache_key]))
		{
			foreach ($GLOBALS["dm"][$tbl_name."_".$cache_key] as $key=>$value)
				$this->$key = $value;
			return $this->data;
		}
		if ($dm_contents = $GLOBALS["db"]->get_cache($tbl_name,$cache_key))
		{
			foreach ($dm_contents as $key=>$value)
				$this->$key = $value;
			$GLOBALS["dm"][$tbl_name."_".$cache_key] = $this;
			return;
		}

		$this->tablename = $tbl_name;
		$this->keyname = $key_name;
		if (strlen($where_clause)>1)
			$where_clause = " WHERE ".$where_clause;
		$this->sql = "SELECT * FROM ".$this->tablename.$where_clause." order by ".$order_by;
		$tmp_data = $GLOBALS["db"]->query($this->sql);
		if (!$tmp_data)
		{
			$this->message = "数据库查询错误";
			return false;
		}
		$this->raw_data = $tmp_data;
		foreach ($tmp_data as $tmp_item)
			$this->data[$tmp_item[$this->keyname]] = $tmp_item;

		$GLOBALS["db"]->set_cache($tbl_name,$cache_key,$this);		//存储cache
		$GLOBALS["dm"][$tbl_name."_".$cache_key] = $this->this;
		return $this->data;
	}
	
	/**	获取所有数据
	*
	*/
	function get_all_data()
	{
		return $this->data;
	}
	
	/**	设置当前应该选用的key 值
	*
	*/
	function set_key_value($key_value)
	{
		$this->key_value = $key_value;
	}
	
	/**	根据keyid, 属性名称返回属性值
	*
	*/
	function get_attr($attr_name,$key = false)
	{
		if (!$key)
			$key = $this->key_value;
		return $this->data[$key][$attr_name];		
	}
	
	/**	根据keyid，批量获得属性
	*
	*/
	function get_attr_multi($attr_list,$key = false)
	{
		foreach ($attr_list as $attr_name)
		{
			$rtn[$attr_name] = $this->get_attr($attr_name,$key);
		}
		return $rtn;
	}
	
	/**
	*		根据keyid，或者整条数据
	*/
	function get_data($key = false)
	{
		if (!$key)
			$key = $this->key_value;
		return $this->data[$key];
	}

	/**	取得最大的ID
	*
	*/	
	function get_max_key()
	{
		$max = $this->data[0][$this->keyname];
		foreach ($this->data as $item)
			if ($item[$this->keyname]>$max)
				$max = $item[$this->keyname];
		return $max;
	}
}
?>
<?php
/**
*MySQL数据库操作类
*/

Class MySQL
{
	public $CONN,$error_msg,$error_no;
	public $sql;
	public $sql_list;
	public $n=0,$ns=0,$ni=0,$nu=0;		//sql num, num of select, num of insert, num or update
	public $cache;
	public $start_time,$end_time;
	private $handle = false;					//记录错误的
	public $DEBUG = false;

	/**
	*	打印本页面执行时间，执行的sql之类的
	*/
	function dump($label="")
	{
	}

	/**
	*	打印错误
	*/
	function error($text)
	{
	   $no = mysql_errno();
	   $msg = mysql_error();
	   echo "[$text] ( $no : $msg )<BR>\n";
	   exit;
	}

	/**
	*	向错误句柄输出错误
	*/
	function log_error($sql)
	{
		if (!$this->handle)
			$this->handle = fopen("../upload/sql_err.txt","a");
		if ($this->handle)
		{
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
			else
				$ip = $_SERVER["REMOTE_ADDR"];
			$all_str = sprintf("%s\t%s\t%s\n",$ip."|".date("Y-m-d H:i:s")."|",$_SERVER["REQUEST_URI"],$sql);
			$size = fwrite($this->handle,$all_str);
		}
		else
			echo "can't open error file";
	}

	/**
	*		构造函数
	*/
	function __construct()
	{
		$this->start_time = microtime(true);
		$this->init();
	}

	/**
	*		析构函数
	*/
	function __destruct()
	{
		$this->end_time = microtime(true);
		if ($this->handle)
			fclose($this->handle);
		if ($this->DEBUG)
			printf("<!--[%s]total query: %d, select %d,insert %d, update %d (in %f seconds)\n%s-->",$label,$this->n,$this->ns,$this->ni,$this->nu,$this->end_time-$this->start_time,print_r($this->sql_list,true));
	}

	/**
	*		真正的初始化函数
	*/
	function init ()
	{
		global $db_config,$memcache_config;
		if ($memcache_config["ENABLED"])
		{
			$this->cache = new MemCache1();
			if (!$this->cache->cache)
				$this->cache = false;
		}
		else
			$this->cache = false;

		$conn = mysql_connect($db_config["HOST"], $db_config["USERNAME"], $db_config["PASSWORD"]);
		if(!$conn)
		   $this->error("Connection attempt failed");
		if(!mysql_select_db($db_config["DB"], $conn))
		   $this->error("Database Select failed");
		$this->CONN = $conn;
		mysql_query("set names utf8");
//		mysql_query("set names gb2312");
		return true;
   }

	/**		向高速缓存保存数据
	*			例如：set_cache("can_shu",$can_shu,$value);
	*/
	function set_cache( $table, $key, $value,$lifetime = false )
	{
		if ($this->cache)
			return $this->cache->setData($table, $key, $value,$lifetime);
		else
			return false;
	}

	/**		读取高速缓存的数据
	*
	*/
	function get_cache($table,$key)
	{
		if ($this->cache)
			return $this->cache->getData($table,$key);
		else
			return false;
	}

	/**
	*		清除数据
	*/
	function clear_cache($table,$key)
	{
		if ($this->cache)
			return $this->cache->removeData($table,$key);
		else
			return false;
	}

	/**
	*		读取数据
	*/
	private function select ($sql="",$result_type = MYSQL_BOTH)
	{
		$this->ns++;
		$this->sql_list[] = $sql;
		$results = mysql_query($sql,$this->CONN);
		if((!$results) or (empty($results)) )
		{
		   return false;
		}
		$count = 0;
		$data = array();
		while ( $row = mysql_fetch_array($results,$result_type))
		{
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($results);
		return $data;
	}

	/**
	*		插入数据，成功则返回主键值
	*/
	private function insert ($sql="")
	{
		$this->ni++;
		$this->sql_list[] = $sql;
		$results = mysql_query($sql,$this->CONN);
		if( (!$results) or (empty($results)) )
		{
			$this->error_msg = mysql_error();
			$this->error_no = mysql_errno();
			return false;
		}
		if (mysql_insert_id())
			return mysql_insert_id();
		else
			return true;
	}

	/**
	*		更新数据库，成功则返回true
	*/
	private function update ($sql="")
	{
		$this->nu++;
		$this->sql_list[] = $sql;
		$results = mysql_query($sql,$this->CONN);
		if( (!$results) or (empty($results)) )
			return false;
		return true;
		//mysql_affected_rows();
	}

	/*
	*		删除数据
	*/
	private function delete ($sql="")
	{
		$this->nu++;
		$this->sql_list[] = $sql;
		$results = mysql_query($sql,$this->CONN);
		if( (!$results) or (empty($results)) )
		   return false;
		return true;
	}

	/**
	*		统一的执行sql的函数
	*/
	function query ($sql="",$result_type = MYSQL_BOTH)
	{
		$this->sql = $sql;
		if ($this->DEBUG)
   			$this->log_error($this->sql);
		$this->n++;
		if(empty($this->CONN)) return false;

		if(preg_match("/^select/i",$sql)||preg_match("/^set/i",$sql))
			return $this->select($sql,$result_type);
		elseif(preg_match("/^insert/i",$sql))
			return $this->insert($sql);
		elseif(preg_match("/^update/i",$sql) ||preg_match("/replace/i",$sql))
			return $this->update($sql);
		elseif(preg_match("/^delete/i",$sql))
			return $this->delete($sql);
		else
		{
		   echo "<H2>Wrong function silly@query!($sql)</H2>\n";
		   return false;
		}
   }

	/**
	*			向数据库插入一行
	*			$table_name	表名
	*			$parms		array($key=>$value,....)
	*
	*			返回：插入行的PK如果成功，false如果失败
	*/
	function insert_row($table_name,$parms)
	{
		$sql = sprintf("insert into %s (%s) values (%s)",$table_name,implode(",",array_keys($parms)),implode(",",$parms));
		if ($this->query($sql))
			return mysql_insert_id();
		else
		{
			if ($this->DEBUG)
				echo mysql_error()."@".$sql;
			return false;
		}
	}

	/**
	*		为插入数据库准备数据,避免sql注入攻击
	*		$value	原始变量
	*		$type	数据类型:STRING, NUM (STRING会被添加单引号,NUM数字,RAW不处理)
	* 	return	处理后的变量,可以直接用于insert
	*/
	function prepare_value($value,$type)
	{
		if(!$value && empty($value) && $value != 0)	//不论什么类型，如果值不存在，设置为NULL
		{
			return 'NULL';
		}
		switch ($type)
		{
			case "STRING":
			case "VARCHAR":
			case "TEXT":
				//return "'".mysql_real_escape_string($value)."'";
				return "'".htmlspecialchars($value,ENT_QUOTES)."'";
				break;

			case "NUM":
			case "INT":
				if (is_numeric($value))
					return $value;
				else
					return floatval($value);
				break;

			case "DATE":
				//return "to_date('".$value."','YYYY-MM-DD')";
				return "'".$value."'";
				break;

			case "ENUM":
				if (is_array($value))
				{
					foreach ($value as $val)
						$rtn[] = $val;
					return "'".implode(",",$rtn)."'";
				}
				else
					return "'N'";
				break;

			case "DATETIME":
				//return "to_date('".$value."','YYYY-MM-DD HH24:mi:ss')";
				return "'".$value."'";
				break;

			case "TIMESTAMP":
				return "now()";
				break;

			case "RAW":			//慎用.确保使用这个的时候,原始数据不是来自用户
				return $value;
				break;
		}
	}

	/**
	*		更新一行
	*		$table			表名
	* 	$parms			需要修改的参数数组 $key是列名, $value是新值
	*		$key_column		主键列名。列值从$parms里面取
	*/
	function update_row($table_name,$parms,$key_column)
	{
		foreach($parms as $keys => $value)
			if (strlen($value)>=1)
				$pairs[]= $keys."=".$value;
		if (!$pairs)
			return true;
		$str = implode(" , ",$pairs)." where ".$key_column." = ".$parms[$key_column];
		$this->sql = sprintf("update %s set %s",$table_name,$str);
		$this->clear_cache($table_name,$parms[$key_column]);
		return $this->query($this->sql);
	}

	/**
	*		查询返回第一条数据
	*/
	function query_once ($sql="")
	{
		$row = $this->query($sql);
		if ($row)
			return $row[0];
		else
			return false;
	}



	/** 
	*		获取服务器上正执行的文件名，扩展名为php 
	*/
	function getServerFilename()
	{
		$path = pathinfo($_SERVER["SCRIPT_FILENAME"]);
		return $path["basename"];
	}

	/**
	*		替换掉html页面中定义的{{xxx}}
	*		$data      是php文件中定义好的待替换的数组
	*		$template  是读取的对应的html文件内容
	*/
	function parse_string($data, $template)
	{
		$keys = array_keys($data);
		for ($i=0;$i<count($keys);$i++)
			$keys[$i]="{{".strtoupper($keys[$i])."}}";
		return str_ireplace($keys,$data,$template);
	}


	/** 
	*		从文件中替换
	*		$data      是php文件中定义好的待替换的数组
	*		$filename  是读取的对应的文件名称
	*/
	function parse_by_filename($data,$filename)
	{
		$contents = file_get_contents($filename);
		return $this->parse_string($data,$contents);
	}

	/**
	*		最终的显示html页面的函数
	*		$data  代表定义好的变量名和取到的内容组成的数组
	*		$act   代表当前执行的操作，并会作为需要调用的html文件的后缀使用
	*/
	function display($data,$act)
	{
		global $comm;
		$filename = $GLOBALS["all"]["BASE"].$GLOBALS["all"]["gong_neng"]["directory"]."/templates/".substr($GLOBALS["all"]["gong_neng"]["file_name"], 0,-4)."_".$act.".html";
		$contents = file_get_contents($filename);
		$data["top"] = @file_get_contents("include/templates.php");
		return $this->parse_string($data,$contents);
	}

	/**
	*		显示返回结果页面函数，与上一个不同的是，本函数执行后弹出对话框，然后直接跳转到下一个页面
	*		$result		是执行query后得到的返回值，为true或false（1或者0）
	*		$act			是传递操作参数，用来判断是否调用result.html
	*/
	function result_display_alert($result,$act="",$errormsg="操作失败！",$succlink="")
	{
		if (empty($succlink))
		{
			$succlink=$this->getServerFilename()."?act=list";
		}
		if (!$result)
		{
			$info = "<script language=javascript>alert('".$errormsg."返回重试！');\nhistory.go(-1)</script>";
		}
		else
		{
			$info = "<script language=javascript>alert('操作成功，请继续！');\n</script>";
			$info .= "<script language=javascript>window.location.href='".$succlink."';\n</script>";
		}
		return $info;
	}
	
	/**
	*		得到字段的数据类型
	*		@param $talblename 表名 $field 字段名
	*		@return mixed
	*/
	function get_field_type($talblename=false,$field=false)
	{
		if(!$talblename or !$field)
		{
			echo "error,The talblename or Field is not exists!";
			return false;
		}
		mysql_query("set names utf8");
		//得到表结构
		$structure = mysql_query("describe ".$talblename,$this->CONN) or die("db-line:437:error:".mysql_error());
		$count = 0;
		while($row = mysql_fetch_array($structure,MYSQL_BOTH))
		{
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($structure);
		foreach($data as $tmp)
		{
			if($tmp["Field"]==$field)
			{
				if(strpos($tmp["Type"],"int") ===false && strpos($tmp["Type"],"float") ===false && strpos($tmp["Type"],"double") ===false)
				{
					$type = "VARCHAR";
				}
				else
				{
					$type = "INT";
				}
			}
		}
		return $type;
	}
}

// End Class


?>

<?php
	/**
	*			这里放了一些只有系统才能使用的函数，例如为i.php写的函数。
	*			在普通的功能中，不允许使用这些函数。
	*			如果需要使用这里的函数，请将这些函数转移到common.php中
	*/
	
	/**	
	*	根据参数，拼接URL
	*	@parm	模块简称
	*	@parm	文件名
	*	@parm	操作
	*	@rtn	URL
	*	注意：	除非特别必要，否则不应该直接使用这个游戏，而应该使用URL函数
	*/
	function format_url_by_id($id)
	{
		$url = "index.php?a=".$id;
		return $url;
	}
	

	/**		
	*	从数据库或者cache读取系统功能数据（内部功能，外部尽量不要调用）
	*
	*/
	function get_xt_gong_neng_all()
	{
		$gong_neng_str = $GLOBALS["db"]->get_cache("module_function","all");		//	取自memcache
		if ($gong_neng_str)
		{
			$GLOBALS["all"]["gong_neng_dat"] = unserialize($gong_neng_str);
		}
		else		//	取自数据库
		{
			$sql = "SELECT * FROM module_function gn left join module mk on mk.abbreviation  = gn.module_abbreviation where mk.abbreviation <> '--'";
			$gong_neng_list = $GLOBALS["db"]->query($sql);
			foreach ($gong_neng_list as $gong_neng)
			{
				$func_str = $gong_neng["abbreviation"]."|".$gong_neng["file_name"]."|".$gong_neng["operate"];
				$func[md5($func_str)] = $gong_neng[0];
				//echo "set cache for ".$gong_neng["id"];
				$GLOBALS["db"]->set_cache("module_function",$gong_neng["id"],$gong_neng);	//	这个不经常用。所以分条存放
			}
			$GLOBALS["all"]["gong_neng_dat"] = $func;
			$GLOBALS["db"]->set_cache("module_function","all",serialize($GLOBALS["all"]["gong_neng_dat"]));
		}
	}


	/**	
	* 分析当前的url，并据此设置对应的operate，以便于后续的程序执行
	*	这个函数和上面的函数是一套的。上面是url拼接，这个是解释。将来这两个东西可以对应修改
	*	这个函数仅由i.php调用。其他人不应该调用
	*/
	function decode_func_url()
	{
		GLOBAL $all;
		$id = $_REQUEST["a"];
		$all["id"] = $id;
		if ($id <1)
			return;

		if ($all["gong_neng"] = $GLOBALS["db"]->get_cache("module_function",$all["id"]))
		{
			$all["operate"] = $all["gong_neng"]["operate"];
		}
		else
		{
			$sql = "SELECT * FROM module_function gn left join module mk on mk.abbreviation  = gn.module_abbreviation where gn.id = ".$all["id"];
			$all["gong_neng"] = $GLOBALS["db"]->query_once($sql);
			if ($all["gong_neng"])
			{
				$all["operate"] = $all["gong_neng"]["operate"];
				$GLOBALS["db"]->set_cache("module_function",$all["id"],$all["gong_neng"]);
			}
		}
		if ($all["gong_neng"]["subjoin_parameter"])
		{
			parse_str($all["gong_neng"]["subjoin_parameter"],$fu_jia);
			$_REQUEST = array_merge($_REQUEST,$fu_jia);
		}
	}

	/**
	*		自动加载类。使用这个函数之后，禁止自己去require任何class了
	*/
	function __autoload($class_name)
	{
//		if (!$class_parts = explode("_",$class_name))
//			return;
// 	cache 开始
		if (!$data_list = $GLOBALS["db"]->get_cache("module",1))
		{
			//	此处后期直接改为数组
			$sql = "select * from module";
			$data_list = $GLOBALS["db"]->query($sql);
			
			// cache 结束
			if ($data_list)
				$GLOBALS["db"]->set_cache("module",1,$data_list);		
		}
		
		foreach ($data_list as $data)
		{
			$muo_kuai_list[$data["abbreviation"]] = $data["directory"];
		}
		if (!isset($muo_kuai_list[strtolower($class_name)]))
		{
			echo "没有找到模块:".$class_name;
			return;
		}
		$directory = $muo_kuai_list[strtolower($class_name)];
		$class_path = $directory."/".strtolower($class_name).".class.php";
		require_once($class_path);
		
	}



?>
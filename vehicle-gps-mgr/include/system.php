<?
	/**
	*			�������һЩֻ��ϵͳ����ʹ�õĺ���������Ϊi.phpд�ĺ�����
	*			����ͨ�Ĺ����У�������ʹ����Щ������
	*			�����Ҫʹ������ĺ������뽫��Щ����ת�Ƶ�common.php��
	*/
	
	/**	
	*	���ݲ�����ƴ��URL
	*	@parm	ģ����
	*	@parm	�ļ���
	*	@parm	����
	*	@rtn	URL
	*	ע�⣺	�����ر��Ҫ������Ӧ��ֱ��ʹ�������Ϸ����Ӧ��ʹ��URL����
	*/
	function format_url_by_id($id)
	{
		$url = "index.php?a=".$id;
		return $url;
	}
	

	/**		
	*	�����ݿ����cache��ȡϵͳ�������ݣ��ڲ����ܣ��ⲿ������Ҫ���ã�
	*
	*/
	function get_xt_gong_neng_all()
	{
		$gong_neng_str = $GLOBALS["db"]->get_cache("module_function","all");		//	ȡ��memcache
		if ($gong_neng_str)
		{
			$GLOBALS["all"]["gong_neng_dat"] = unserialize($gong_neng_str);
		}
		else		//	ȡ�����ݿ�
		{
			$sql = "SELECT * FROM module_function gn left join module mk on mk.abbreviation  = gn.module_abbreviation where mk.abbreviation <> '--'";
			$gong_neng_list = $GLOBALS["db"]->query($sql);
			foreach ($gong_neng_list as $gong_neng)
			{
				$func_str = $gong_neng["abbreviation"]."|".$gong_neng["file_name"]."|".$gong_neng["operate"];
				$func[md5($func_str)] = $gong_neng[0];
				//echo "set cache for ".$gong_neng["id"];
				$GLOBALS["db"]->set_cache("module_function",$gong_neng["id"],$gong_neng);	//	����������á����Է������
			}
			$GLOBALS["all"]["gong_neng_dat"] = $func;
			$GLOBALS["db"]->set_cache("module_function","all",serialize($GLOBALS["all"]["gong_neng_dat"]));
		}
	}


	/**	
	* ������ǰ��url�����ݴ����ö�Ӧ��operate���Ա��ں����ĳ���ִ��
	*	�������������ĺ�����һ�׵ġ�������urlƴ�ӣ�����ǽ��͡������������������Զ�Ӧ�޸�
	*	�����������i.php���á������˲�Ӧ�õ���
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
	*		�Զ������ࡣʹ���������֮�󣬽�ֹ�Լ�ȥrequire�κ�class��
	*/
	function __autoload($class_name)
	{
//		if (!$class_parts = explode("_",$class_name))
//			return;
// 	cache ��ʼ
		if (!$data_list = $GLOBALS["db"]->get_cache("module",1))
		{
			//	�˴�����ֱ�Ӹ�Ϊ����
			$sql = "select * from module";
			$data_list = $GLOBALS["db"]->query($sql);
			
			// cache ����
			if ($data_list)
				$GLOBALS["db"]->set_cache("module",1,$data_list);		
		}
		
		foreach ($data_list as $data)
		{
			$muo_kuai_list[$data["abbreviation"]] = $data["directory"];
		}
		if (!isset($muo_kuai_list[strtolower($class_name)]))
		{
			echo "û���ҵ�ģ��:".$class_name;
			return;
		}
		$directory = $muo_kuai_list[strtolower($class_name)];
		$class_path = $directory."/".strtolower($class_name).".class.php";
		require_once($class_path);
		
	}



?>
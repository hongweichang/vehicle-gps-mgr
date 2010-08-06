<?php
/**	
	-----公用函数集合。没有批准不许加函数。大家都同意才能加-----
	$GLOBAL["all"]所包含的变量已经含义:
		$id_gong_neng		功能编号
		$gong_neng			功能的全部内容
		$act				本页面的act
		$gong_neng_dat		所有功能的列表
*/


	/**	根据参数，拼接URL
	*	@parm	模块简称
	*	@parm	文件名
	*	@parm	操作
	*	@rtn	URL
	*	例如：	get_func_url("yh","zhu_ce_do.php","add_submit")
	*/
	date_default_timezone_set('Asia/Shanghai');
	function URL($muo_kuai_jian_cheng,$wen_jian_ming,$act)
	{ 
		if (!$GLOBALS["all"]["gong_neng_dat"])		//保证只是读取一次。内存数组比memcache快，所以一次取出
			get_xt_gong_neng_all();

		$gong_str = $muo_kuai_jian_cheng."|".$wen_jian_ming."|".$act;
		$id_gong_neng = $GLOBALS["all"]["gong_neng_dat"][md5($gong_str)];
		if ($id_gong_neng>=1)
		{
			return format_url_by_id($id_gong_neng);
		}
		else
		{
			return URL("error","error.php","error");
		}
	}

	/*
	*	把分钟数显示成天、时、分的格式
	*	@params		$minutes		分钟数
	*	@return		x天y时z分
	*/
	function minutes_to_view($sj)
	{
		$o = ($sj%60)."分";
		if (floor($sj/60)>0)
		{
			$sj = floor($sj/60);
			$o = floor($sj%24)."时".$o;
			if (floor($sj/24)>0)
				$o = floor($sj/24)."天".$o;
		}
		return $o;
	}

	/*
	*	把秒数显示成时、分、秒的格式
	*	@params		$minutes		秒
	*	@return		y时z分x秒
	*/
	function seconds_to_view($sj)
	{
		$o = ($sj%60)."秒";
		if (floor($sj/60)>0)
		{
			$sj = floor($sj/60);
			$o = floor($sj%60)."分".$o;
			if (floor($sj/60)>0)
				$o = floor($sj/60)."时".$o;
		}
		return $o;
	}

	/**	
	*		用于输出调试信息。
	*		当输入$str是一个字符串的时候，简单打印出来
	*		当输入$str是一个二维数组的时候，用<TABLE>打印出来
	*/
	function msg($str,$DEBUG = 1)
	{
		//return;
		if (is_array($str))
		{
			$html[] = "<table border=1>";
			$html[]= "<tr><td>".implode("<td>",array_keys(current($str)));
			foreach ($str as $row)
			{
				$html[] = "<tr>";
				foreach ($row as $item)
					$html[] = "<td>".$item."</td>";
				$html[] = "</tr>";
			}
			$html[] = "</table>";
			echo implode("\n",$html);
		}
		else
			echo $str."<br/><br/>";
		flush();
		ob_flush();
	}



	/**
	*	获取访问者IP
	*/
	function get_user_ip()
	{
		if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else
			$ip = $_SERVER["REMOTE_ADDR"];

		if ($real_ip=strstr($ip,","))		//有时候会变态的返回两个ip，这里处理一下
			$ip = substr($real_ip,1);
		return $ip;
	}

	/**
	*	获取当前系统时间	格式：2009-05-13 11:28:50
	*/
	function get_sysdate()
	{
		date_default_timezone_set('Asia/Shanghai');
		return date("Y-m-d H:i:s");
	}

	/**	
	*	设置session值
	*	$name			要设置SESSION的相应名称
	*	$value		对应的值
	*/
	function set_session($name,$value)
	{
		switch($name)
		{
			case "user_id":
				return $_SESSION["user_id"] = $value ;
				break;
			case "company_id":
				return $_SESSION["company_id"] = $value ;
				break;
			case "login_id":
				return $_SESSION["login_id"] = $value ;
				break;
			case "user_name":
				return $_SESSION["user_name"] = $value ;
				break;
		}
	}

	/**	
	* 获取session值
	*	$name			相应名称
	*	$value			从SESSION中取得的值
	*	返回			session值
	*/
	function get_session($name)
	{
		switch($name)
		{
			case "user_id":
				return $_SESSION["user_id"];
				break;
			case "company_id":
				return $_SESSION["company_id"];
				break;
			case "login_id":
				return $_SESSION["login_id"];
				break;
			case "user_name":
				return $_SESSION["user_name"];
				break;
		}
	}

	/**	
	*		直接跳转到指定的URL
	*		如果$div_id空，则刷新弹出框。否则刷新指定的div
	*		$close_popup如果是true，则关闭当前的popup window
	*/
	function goto_url($pUrl = '/', $div_id = false, $close_popup = false)
	{
		echo "<meta http-equiv='refresh' content='0;URL={$pUrl}'>";
		exit;
	}

	/**
	*	得到新的时间类型
	*/
	function new_date($sj,$type = 1)
	{
		if(!$sj)
			return;
		$week   = date('D',strtotime($sj));

		switch ($week)
		{
			case "Mon":
				$current = "星期一";
				break;
			case "Tue":
				$current = "星期二";
				break;
			case "Wed":
				$current = "星期三";
				break;
			case "Thu":
				$current = "星期四";
				break;
			case "Fri":
				$current = "星期五";
				break;
			case "Sat":
				$current = "星期六";
				break;
			case "Sun":
				$current = "星期日";
				break;
		}
		$new_sj = explode(" ",$sj);
		if($type==1)
			return $new_sj[0]."　".$current;
		else
			return $new_sj[0]."　".$current."　".$new_sj[1];
	}


	/**
	*	关闭错误输出
	*	重试file_get_contents
	*	直到取到数据
	*/
	function multi_file_get_contents_old($url)
	{
		error_reporting(1);
		for($i=0;$i<30;$i++)
		{
			$receive_data = file_get_contents($url);
			if ($receive_data !== false)
			{
				return $receive_data;
			}
		}
		return "no receive data.";
	}

	function multi_file_get_contents($url)
	{
    $comp = parse_url($url);
    $host = $comp['host'];
    $addr = $comp['path'].'?'.$comp['query'];
    $fp=fsockopen($host,80,$errno,$errstr,30);
    if(!$fp) echo "$errstr ($errno)";
    else
    {
        $out="GET $addr HTTP/1.0\r\n";                
        $out.="Host: $host\r\n";
        $out.="Connection: Close\r\n\r\n";

        fputs($fp,$out);
        $str="";
        while(!feof($fp)) $str=fgets($fp,1024);
        fclose($fp);
    }
    return $str;
	}
	
	/**
	 * 分解度数换为方向
	 * @param unknown_type $cur_direction  角度
	 */
	function  resolvingDirection($cur_direction=-1){
		
		//方向数组(八个方向:东、东南、南、西南、西、西北、北、东北)
		$arr_direction =array("north","northwest","west","southwest","south","southeast","east","northeast");
		
		//返回车方向
		return $arr_direction[intval($cur_direction/45)+((($cur_direction%45)-(45/2))>=0?1:0)]; 
	}
?>
<?php
/** 
* 系统内置参数设置
* @copyright		company, 2010
* @author			苏元元
* @create date		2010.07.26
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/
$act = $GLOBALS["all"]["operate"];

switch($act)
{
	//读取设置
	case "get_setting":

		$set = new Setting(get_session("company_id"));

		//刷时间 5 10 15 30 45 60
		$sj = array(5,10,15,30,45,60);

		$sel_html = "<select name = 'page_refresh_time' id = 'page_refresh_time'>";
		foreach($sj as $temp_sj)
		{
			if($temp_sj == $set->data["page_refresh_time"])
			{
				$selected = " selected ";
			}
			else
				$selected = " ";

			$sel_html .= "<option value='".$temp_sj."' ".$selected.">".$temp_sj."秒</option>";
		}
		$sel_html .= "</select>";


		//刷新时间
		$dis["page_refresh_time"] = $sel_html;

		// 超速
		$dis["speed_limit"] = $set->data["speed_astrict"];

		// 疲劳
		$dis["fatigue_remind_time"] = $set->data["fatigue_remind_time"]/60;

		//引入xml操作文件
		require_once ("include/data_mapping_handler.php");

		$color_xml = "xml/color.xml"; //定义xml映射文件局对路径
		$dataMapping = new Data_mapping_handler ( $color_xml ); //从xml配置信息中读取颜色
	    $data_list_color=$dataMapping->getTextDataList('color');

		//颜色选择
		$i = 0;
		foreach($data_list_color as $key=>$temp_color)
		{
			$i++;

			$new .= '
			
				  <TR   onmouseover=this.style.backgroundColor="#0099ff"  onmouseout=this.style.backgroundColor="">   
						<TD   onclick="do_click_{{func}}(this);" id="color_{{selectedValue}}'.$i.'" value="'.$key.'">
							<IMG  hspace=2   src="'.$temp_color.'"   align=absMiddle   border=0>
						</TD>
					</TR> 
			
			';
		}

		$a = file_get_contents("setting/templates.php");

		$dis_sp["default_color"] = $new;
		$dis_sp["dropdownOption"] = "_l";
		$dis_sp["selectedValue"] = "_l";
		$dis_sp["selectLength"] = "_l";
		$dis_sp["func"] = "l";

		$dis["selectedValue"] = "_l";

		//显示当前已有的
		if(!empty($set->data["default_color"]))
		{ 
			$default_color = $dataMapping->getTextData("color","#".$set->data["default_color"]);
			$dis_sp["default_color_selected"] = '<IMG  hspace=2   src="'.$default_color.'"   align=absMiddle   border=0>';
			$dis_sp["d_value"] = "#".$set->data["default_color"];
		}
		else
		{
			$dis_sp["default_color_selected"] = "";
			$dis_sp["d_value"] = "";
		}

		$a = $GLOBALS["db"]->parse_string($dis_sp,$a);

		$dis["default_color"] = $a;

		//显示当前公司设置的 速度段

		$speeds = $set->get_speeds();

		if(empty($speeds))
		{
			$set_speed = "当前公司没有设置 速度颜色映射";
		}
		else
		{
			$html[] = '<table><tr>';
			$i = 0;

			foreach($speeds as $key=>$temp_s)
			{
				$i++;
				//$html_s .= "<tr><td><img src='".$data_list_color["#".$temp_s["color"]]."'>"."<br>".$temp_s["min"]."--".$temp_s["max"]."<br>";
				if($i <5 )
				{
					$html[] = "<td align='center'><img src='".$data_list_color["#".$temp_s["color"]]."'><br>".$temp_s["min"]."--".$temp_s["max"]."</td>";
				}
				else if($i == 5)
				{
					$html[] = "</tr><tr><td align='center'><img src='".$data_list_color["#".$temp_s["color"]]."'><br>".$temp_s["min"]."--".$temp_s["max"]."</td>";
				}
				else
					$html[] = "<td align='center'><img src='".$data_list_color["#".$temp_s["color"]]."'><br>".$temp_s["min"]."--".$temp_s["max"]."</td>";
			}
			$html[] = "</tr>";
			$html[] = '</table>';

			$set_speed = implode("\n",$html);
		}
		$dis["speeds"] = $set_speed;

		echo $GLOBALS["db"]->display($dis,$act);

		break;

	//设置参数
	case "set_setting":

		$part = $_REQUEST["part"];

		$set = new Setting(get_session("company_id"));

		//第一部分修改
		if($part == 1)
		{
			$parms["company_id"]			= $GLOBALS['db']->prepare_value(get_session("company_id"),"INT");
			$parms["page_refresh_time"]		= $GLOBALS['db']->prepare_value($_REQUEST["page_refresh_time"],"VARCHAR");
			$parms["default_color"]		= $GLOBALS['db']->prepare_value(substr($_REQUEST["default_color"],1),"VARCHAR");
			$parms["speed_astrict"]		= $GLOBALS['db']->prepare_value($_REQUEST["speed_limit"],"FLOAT");
			$parms["fatigue_remind_time"]		= floor($GLOBALS['db']->prepare_value($_REQUEST["fatigue_remind_time"],"FLOAT")*60);

			$parms["create_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
			$parms["create_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
			$parms["update_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
			$parms["update_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");

			if(empty($set->data))
			{
				//如果没有，则添加
				$rtn = $set->add_setting($parms);
			}
			else
			{
				//如果已有，则编辑
				$rtn = $set->edit_setting($parms,"company_id");
			}
		}
		else if($part == 2)
		{
			$parm = $_REQUEST["parm"];
//			file_put_contents("d:\a.txt",$parm);
			//去掉最后一个字符
			$parm = substr($parm, 0, -1);

			$parm_arr = explode("|",$parm);
			//检查一下是不是8个
			if(count($parm_arr) <> 8)
			{
				exit("未知错误，联系管理员");
			}

			//删除所有
			$set->delete_speed_color();

			//截取每组的 颜色，最小，最大
			foreach($parm_arr as $p_temp)
			{
				$p_temp_arr = explode("_",$p_temp);

				if(count($p_temp_arr) <> 3)
				{
					exit("设置数量错误，联系管理员");
				}

				if(!empty($p_temp_arr[1]) && !empty($p_temp_arr[2]))
				{
					//插入操作
					$parms["company_id"]		= $GLOBALS['db']->prepare_value(get_session("company_id"),"INT");
					$parms["min"]				= $GLOBALS['db']->prepare_value($p_temp_arr[0],"INT");
					$parms["max"]				= $GLOBALS['db']->prepare_value($p_temp_arr[1],"INT");
					$parms["color"]				= $GLOBALS['db']->prepare_value(substr($p_temp_arr[2],1),"VARCHAR");

					$parms["create_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
					$parms["create_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");
					$parms["update_id"]				= $GLOBALS['db']->prepare_value(get_session("user_id"),"INT");
					$parms["update_time"]			= $GLOBALS['db']->prepare_value(get_sysdate(),"VARCHAR");

					$rtn = $set->add_speed_color($parms);
				}
			}
			if($rtn > 1)
				exit("设置成功");
			else
				exit("设置失败，请重试");
		}

		break;

	// 速度颜色 映射
	case "speed_color":

		$set = new Setting(get_session("company_id"));
		
		//显示当前公司设置的 速度段
		$speeds = $set->get_speeds();

		//引入xml操作文件
		require_once ("include/data_mapping_handler.php");

		$color_xml = "xml/color.xml"; //定义xml映射文件局对路径
		$dataMapping = new Data_mapping_handler ( $color_xml ); //从xml配置信息中读取颜色
	    $data_list_color=$dataMapping->getTextDataList('color');

		//颜色选择
		$i = 0;
		foreach($data_list_color as $key=>$temp_color)
		{
			$i++;

			$new .= '
			
				  <TR   onmouseover=this.style.backgroundColor="#0099ff"  onmouseout=this.style.backgroundColor="">   
						<TD   onclick="do_click_{{func}}(this);" id="color'.$i.'" value="'.$key.'">
							<IMG  hspace=2   src="'.$temp_color.'"   align=absMiddle   border=0>
						</TD>
					</TR> 
			
			';
		}

		$html = "<table><tr><td align='center'>最小速度</td><td align='center'></td><td align='center'>最大速度</td><td align='center'>颜色选择</td></tr>";

		//剩余段数
		$temp_array = array();
		$c_hidden = count($speeds);
		for($i=$c_hidden;$i<8;$i++)
		{
			$temp_array = array($i=>array());
			$speeds = array_merge($speeds,$temp_array);

		}
	
		foreach($speeds as $key=>$temp)
		{
			$a = file_get_contents("setting/templates.php");

			$dis_sp["default_color"] = $new;
			$dis_sp["dropdownOption"] = "_p_".$key;
			$dis_sp["selectedValue"] = "_p_".$key;
			$dis_sp["selectLength"] = "_p_".$key;
			$dis_sp["func"] = "p_".$key;

			//显示当前已有的
			if(!empty($temp["color"]))
			{
				$dis_sp["default_color_selected"] = '<IMG  hspace=2   src="'.$data_list_color["#".$temp["color"]].'"   align=absMiddle   border=0>';
				$dis_sp["d_value"] = "#".$temp["color"];
			}
			else
			{
				$dis_sp["default_color_selected"] = '<IMG  hspace=2   src="images/vehicle/red/west.png"   align=absMiddle   border=0>';
				$dis_sp["d_value"] = "#9D1E23";
			}

			$a = $GLOBALS["db"]->parse_string($dis_sp,$a);

			if($key != 0)
			{
				$va = $temp["min"];

			}
			else
				$va = "0";

			$html .= "<tr>";
			$html .= "<td align='center'>".
			"<input readonly  style='border:1px solid #EFEFEF; text-align:center; background-color:#EFEFEF'  type='text' size='5' name='speed_".(2*$key)."'  id='speed_".(2*$key)."' value='".$va."' ></td>".
			"<td align='center'> -- </td>".
			"<td align='center'>".
			"<input type='text' onKeyUp='change_speed(this);' size='5' name='speed_".(2*$key+1)."' id='speed_".(2*$key+1)."' value='".$temp["max"]."'>".
			"</td>".
			"<td id='td".$key."' name='td_c[]'>".$a."</td>";
			$html .= "</tr>";
		}

		echo $html."</table><div id='message'></div><input type='hidden' name='c_hidden' id='c_hidden' value='".$c_hidden."'>";
		break;

	//刷新速度颜色映射
	case "re_speed_color":

		$set = new Setting(get_session("company_id"));

		$speeds = $set->get_speeds();

		if(empty($speeds))
		{
			$set_speed = "当前公司没有设置 速度颜色映射";
		}
		else
		{
			//引入xml操作文件
			require_once ("include/data_mapping_handler.php");

			$color_xml = "xml/color.xml"; //定义xml映射文件局对路径
			$dataMapping = new Data_mapping_handler ( $color_xml ); //从xml配置信息中读取颜色
			$data_list_color=$dataMapping->getTextDataList('color');

			$html[] = '<table><tr>';
			$i = 0;

			foreach($speeds as $key=>$temp_s)
			{
				$i++;
				//$html_s .= "<tr><td><img src='".$data_list_color["#".$temp_s["color"]]."'>"."<br>".$temp_s["min"]."--".$temp_s["max"]."<br>";
				if($i <5 )
				{
					$html[] = "<td align='center'><img src='".$data_list_color["#".$temp_s["color"]]."'><br>".$temp_s["min"]."--".$temp_s["max"]."</td>";
				}
				else if($i == 5)
				{
					$html[] = "</tr><tr><td align='center'><img src='".$data_list_color["#".$temp_s["color"]]."'><br>".$temp_s["min"]."--".$temp_s["max"]."</td>";
				}
				else
					$html[] = "<td align='center'><img src='".$data_list_color["#".$temp_s["color"]]."'><br>".$temp_s["min"]."--".$temp_s["max"]."</td>";
			}
			$html[] = "</tr>";
			$html[] = '</table>';

			$set_speed = implode("\n",$html);
		}
		echo $set_speed;
		break;
		
		case "display_speed_color" :
			
	   
		$data_text_color="";
		$companyId = get_session ( "company_id" );
		$setting = new Setting ();

		$array = $setting->select_speed_color ( $companyId );
		
		//引入xml操作文件
		require_once ("include/data_mapping_handler.php");
		$color_xml = "xml/color.xml"; //定义xml映射文件局对路径
		$dataMapping = new Data_mapping_handler ( $color_xml ); //从xml配置信息中读取颜色
		for($i = 0; $i < count ( $array ); $i++) {
			$a=$array [$i];
			$val="#".$a["color"];
			
			$text_color =$dataMapping->getTextData ( 'color',  $val);
		    $data_min=$a["min"];
		    $data_max=$a["max"];
		    
		    $data_text_color=$data_text_color.$text_color.",".$data_min.",".$data_max."|";
	 
		}
		echo $data_text_color;
		break;
}
?>
<?php
/** 
* 人员管理
* @copyright		秦运恒, 2010
* @author			郭英涛
* @create date		2010.08.02
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/

$act = $GLOBALS["all"]["operate"];

switch($act)
{
	case "main":	//填写信息内容页面
		echo $GLOBALS['db']->display(null,$act);
		break;
	case "sendmail":
		require_once("include/email.class.php");
		require_once("info.class.php");
		
		$address = $_POST["address"];
		$title = $_POST["title"];
		$content = $_POST["content"];

		if(is_string($address))
		{
			$addAddress = str_replace(",","~",$address);
		}
		
		$filename = "D:/phpWork/vehicle-gps-mgr/user/local/joycomm/email_src/waiting/email_info.eml"; //声明变量保存文件名,在这个文件中保存mail信息
		$writeMessage = new Message();

		if(isset($_POST["send"])){     //判断是否提交
				//接收 邮件地址~邮件标题~[[邮件内容]]~
				$message = "~".$addAddress."~".$title."~"."["."[".$content."]"."]"."~";
				$writeMessage->writeMessage($filename,$message);
			}
		/*
		function writeMessage($filename,$message) { //自定义一个向文件写入数据的函数

			$fp = fopen($filename,"a");
			if (flock($fp,LOCK_EX)) {
				fwrite($fp,$message);
				flock($fp,LOCK_UN);
			}else{
				return 0;
			}
			fclose($fp);
		}
		*/

		$sendMl = new Email($mail_config);
		$sendMl->sendMail($address,$title,$content);
		echo $sendMl->getErrorInfo();
		break;
	break;
}
?>
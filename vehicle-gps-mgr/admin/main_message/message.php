<?php
/** 
* 首页消息处理
* @copyright		vehicle, 2010
* @author			赵将伟
* @create date		2010.11.29
* @modify			修改人
* @modify date		修改日期
* @modify describe	修改内容
*/
$act = $GLOBALS["all"]["operate"];

$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
$searchfil = $_REQUEST['searchField']; // get the direction
$searchstr = $_REQUEST['searchString']; // get the direction

if(!$sidx) $sidx =1;

switch($act)
{
	case "main": //添加消息主页面
		echo $GLOBALS['db']->display(null,$act);
		break;
		
	case "add": //添加消息
		require 'message.class.php';
		$message = new Message();
		$messages = $_POST['messages'];//接收消息内容
		
		$encode_type = mb_detect_encoding($messages);//获取消息编码方式
		
		//如果编码不是"utf-8"则转换为"utf-8"
		if($encode_type!="UTF-8"){
			$messages = iconv($encode_type,"utf-8",$messages);
		}
		
		//将消息中的"'"前加转义字符"\",防止保存时单引号冲突
		$messages = str_replace("'","\'",$messages);
		
		require_once 'templates/new_message.php';
		$old_message = $new_message['messages'];//获取现在显示的消息内容以便保存进数据库
		
		//设置消息对象保存记录
		$arr['company_id'] = $db->prepare_value(get_session("company_id"),"INT");
		$arr['user_id'] = $db->prepare_value(get_session("user_id"),"INT");
		$arr['text'] = $db->prepare_value($old_message,"VARCHAR");
		$arr['add_date'] = $db->prepare_value(get_sysdate(),"VARCHAR");
				
		$result = $message->add_message($arr);
		
		if($result){
			$file_name="templates/new_message.php";//保存消息的文件
			file_put_contents($file_name, "<?php \$new_message=array('messages'=>'".$messages."'); ?>");//将消息写进文件
			echo "ok";
		}else{
			echo "fail";
		}
		break;
		
	case "history": //历史消息主界面
		echo $GLOBALS['db']->display(null,"history");
		break;	
		
	case "more": //获取历史消息
		require 'message.class.php';
		$message = new Message();		
		$history_messages = $message->history_message();//获取所有历史消息记录
		
		$count = count($history_messages);//消息总数

		if( $count >0 ) {
			$total_pages = ceil($count/$limit);
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages) $page=$total_pages;
		$start = $limit*$page - $limit;
		if ($start<0) $start = 0;
		
		$response->page	= $page;
		$response->total = $total_pages;
		$response->records = $count;

		foreach($history_messages as $key=>$value){
			$response->rows[$key]['id']=$value['id'];
			$response->rows[$key]['cell']=array($value['id'],$value['text'],$value['add_date']);
		}
		echo json_encode($response);
		break;
}


?>
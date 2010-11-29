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
		$messages = $_POST['messages'];
		
		require_once 'templates/new_message.php';
		$old_message = $new_message['messages'];
		
		$arr['company_id'] = $db->prepare_value(get_session("company_id"),"INT");
		$arr['user_id'] = $db->prepare_value(get_session("user_id"),"INT");
		$arr['text'] = $db->prepare_value($old_message,"VARCHAR");
		$arr['add_date'] = $db->prepare_value(get_sysdate(),"VARCHAR");
				
		$result = $message->add_message($arr);
		
		if($result){
			$file_name="templates/new_message.php";
			file_put_contents($file_name, "<?php \$new_message=array('messages'=>'".$messages."'); ?>");	
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
		$history_messages = $message->history_message();
		
		$count = count($history_messages);

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
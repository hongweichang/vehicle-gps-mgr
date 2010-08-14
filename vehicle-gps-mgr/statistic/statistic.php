<?php
/** 
 * 信息查询
 * @copyright		秦运恒, 2010
 * @author			郭英涛
 * @create date		2010.08.03
 * @modify			修改人
 * @modify date		修改日期
 * @modify describe	修改内容
 */

$act = $GLOBALS ["all"] ["operate"];

$page = $_REQUEST ['page']; // 得到当前页数
$limit = $_REQUEST ['rows']; // 得到一页的行数
$sidx = $_REQUEST ['sidx']; // 得第一列
$sord = $_REQUEST ['sord']; // 得到排序
$begin_data_str=$_REQUEST["begin_data"];//得到查询启示时间 
$end_data_str=$_REQUEST["end_data"];//得到查询结束时间

$begin_data=str_replace("/","-",$begin_data_str);
$end_data=str_replace("/","-",$end_data_str);

switch ($act) {
	case "main" : //填写信息内容页面
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	case "vehicle_list" :
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	case "vehicle_list_data" :
		if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->get_vehicle_count ();
		
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages)
			$page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		
		$dataList = $driver->get_all_vehicle ( $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值
			

			$response->rows [$key] ['vehicle_id'] = $value ['vehicle_id'];
			
			$response->rows [$key] ['cell'] = array ($value ['vehicle_id'], $value ['number_plate'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_vehicle(" . $value ['vehicle_id'] . ")' style='text-decoration:none;color:#0099FF'>详细内容</a>" );
		}
		echo json_encode ( $response ); //打印json格式的数据		
		break;
	
	case "driver_list" :
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	
	case "driver_list_data" :
		if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->get_driver_count ();
		
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages)
			$page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		
		$dataList = $driver->get_all_driver ( $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值
			

			$response->rows [$key] ['driver_id'] = $value ['driver_id'];
			
			$response->rows [$key] ['cell'] = array ($value ['driver_id'], $value ['name'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_driver(" . $value ['driver_id'] . ")' style='text-decoration:none;color:#0099FF'>详细内容</a>" );
		}
		echo json_encode ( $response ); //打印json格式的数据
		break;
	
	case "driver_time_data" :
		if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->sel_driver_count ($begin_data,$end_data);
		
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages)
			$page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		
		$dataList = $driver->get_driver_time ($begin_data,$end_data, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值
			

			$response->rows [$key] ['driver_id'] = $value ['driver_id'];
			
			$response->rows [$key] ['cell'] = array ($value ['driver_id'], $value ['name'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_driver(" . $value ['driver_id'] . ")' style='text-decoration:none;color:#0099FF'>详细内容</a>" );
		}
		echo json_encode ( $response ); //打印json格式的数据
		break;
	
	case "vehicle_time_data" :
		if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->sel_vehicle_count ($begin_data,$end_data);
		
		if ($count > 0) {
			$total_pages = ceil ( $count / $limit );
		} else {
			$total_pages = 0;
		}
		if ($page > $total_pages)
			$page = $total_pages;
		$start = $limit * $page - $limit;
		if ($start < 0)
			$start = 0;
		
		$dataList = $driver->get_vehicle_time($begin_data,$end_data, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值
			
			$response->rows [$key] ['vehicle_id'] = $value ['vehicle_id'];	
			$response->rows [$key] ['cell'] = array ($value ['vehicle_id'], $value ['number_plate'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_vehicle(" . $value ['vehicle_id'] . ")' style='text-decoration:none;color:#0099FF'>详细内容</a>" );
		}
		echo json_encode ( $response ); //打印json格式的数据	
		break;

}
?>
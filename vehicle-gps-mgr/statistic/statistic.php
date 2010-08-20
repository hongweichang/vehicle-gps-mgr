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
			$response->rows [$key] ['cell'] = array ($value ['vehicle_id'], $value ['number_plate'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_vehicle(" . $value ['vehicle_id'] . ")' style='text-decoration:none;color:#0099FF';font-size:12px;>详细内容</a>" );
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
			$response->rows [$key] ['cell'] = array ($value ['driver_id'], $value ['name'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_driver(" . $value ['driver_id'] . ")' style='text-decoration:none;color:#0099FF';font-size:12px;>详细内容</a>" );
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
			$response->rows [$key] ['cell'] = array ($value ['driver_id'], $value ['name'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_driver(" . $value ['driver_id'] . ")' style='text-decoration:none;color:#0099FF';font-size:12px;>详细内容</a>" );
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
			$response->rows [$key] ['cell'] = array ($value ['vehicle_id'], $value ['number_plate'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_vehicle(" . $value ['vehicle_id'] . ")' style='text-decoration:none;color:#0099FF';font-size:12px;>详细内容</a>" );
		}
		echo json_encode ( $response ); //打印json格式的数据	
		break;
		
     
	case "drive_detail_list" ://显示驾驶员开车列表
		   $driver = new Statistic ();                      
           $driver_id=$_REQUEST["driver_id"];//得到驾驶员id 
           $driver_name=$driver->driver_name($driver_id); //添加驾驶员姓名
           $param["DRIVERNAME"]=$driver_name;
		   $param["DRIVERID"] =$driver_id; 
   	       echo $GLOBALS ['db']->display ( $param, $act );
	    break;

	    
   case  "drive_detail_data" ://显示驾驶员开车列表数据
   	$driver_id=$_REQUEST["drive_id"];//得到驾驶员id 
   	if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->drive_detail_count($driver_id);
		
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
		
		$dataList = $driver->drive_detail_data($driver_id, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值		
			$response->rows [$key] ['id'] = $value ['id'];	
			$response->rows [$key] ['cell'] = array ($value ['id'],  $value ['start_time'], $value ['end_time'],$value ['drive_time'],$value ['distance']);
		}
		echo json_encode ( $response ); //打印json格式的数据	
   	    break;
    
   	    
	case "stop_detail_data" ://显示驾驶员停车列表数据
			$driver_id=$_REQUEST["drive_id"];//得到驾驶员id 
   	if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->stop_detail_count($driver_id);
		
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
		
		$dataList = $driver->stop_detail_data($driver_id, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值			
			$response->rows [$key] ['id'] = $value ['id'];	
			$response->rows [$key] ['cell'] = array ($value ['id'], $value ['start_time'], $value ['end_time'],$value ['stop_time']);
		}
		echo json_encode ( $response ); //打印json格式的数据	
		break;

		
	case "vehicle_detail_list":
		   $driver = new Statistic ();                               
		   $vehicle_id=$_REQUEST["vehicle_id"];//得到车辆id 
		   $number_plate=$driver->vehicle_plate_name($vehicle_id);//添加车辆车牌号码
		   $param["NUMBERPLATE"]=$number_plate;
		   $param["VEHICLEID"] =$vehicle_id; 
   	       echo $GLOBALS ['db']->display ( $param, $act );
		 break;
	
		 
	case "vehicle_detail_data":
		$vehicle_id=$_REQUEST["vehicle_id"];//得到驾驶员id 
   	if (! $sidx)
		$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->vehicle_detail_count($vehicle_id);
		
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
		
		$dataList = $driver->vehicle_detail_data($vehicle_id, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值		
			$response->rows [$key] ['id'] = $value ['id'];	
			$response->rows [$key] ['cell'] = array ($value ['id'],  $value ['start_time'], $value ['end_time'],$value['drive_time'],$value['distance']);
		}
		echo json_encode ( $response ); //打印json格式的数据	
		break;
		
		
	case "vstop_detail_data":
		$vehicle_id=$_REQUEST["vehicle_id"];//得到驾驶员id 
   	if (! $sidx)
		$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->vstop_detail_count($vehicle_id);
		
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
		
		$dataList = $driver->vstop_detail_data($vehicle_id, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值			
			$response->rows [$key] ['id'] = $value ['id'];	
			$response->rows [$key] ['cell'] = array ($value ['id'],  $value ['start_time'], $value ['end_time'],$value ['stop_time']);
		}
		echo json_encode ( $response ); //打印json格式的数据	
		break;
	
	case "driver_driving":
		  
		  $param["DRIVER_DRIVING_ID"]= $_REQUEST["driver_driving_id"];
		  $param["DRIVER_DRIVING_NAME"]= iconv("gb2312","UTF-8",$_REQUEST["driver_driving_name"]);
  	       
		  echo $GLOBALS ['db']->display ($param, $act );
		
		  break;

   case "driver_stop":
		  
 		  $param["DRIVER_STOP_ID"]=$_REQUEST["driver_stop_id"];
  	      $param["DRIVER_STOP_NAME"]=iconv("gb2312","UTF-8",$_REQUEST["driver_stop_name"]);;
  	      
		  echo $GLOBALS ['db']->display ($param, $act );
		
  	      break;

   case "vehicle_driving":
   	      
	   	   $param["VE_DRIVING_ID"]=$_REQUEST["vehicle_drinving_number"];
	   	   $param["VE_DRIVING_NUMBER_PLATE"]=iconv("gb2312","UTF-8",$_REQUEST["vehicle_drinving_numberplate"]);;
		   	      
   	       echo $GLOBALS ['db']->display ($param, $act );
		   
   	       break;
	
   case "vehicle_stop":
   	 	   
   	       $param["VE_STOP_ID"]=$_REQUEST["vehicle_stop_number"];
   	 	   $param["VE_STOP_NUMBER_PLATE"]=iconv("gb2312","UTF-8",$_REQUEST["vehicle_stop_numberplate"]);
		   
   	 	   echo $GLOBALS ['db']->display ($param, $act );
		   
		   break;
	
}
?>
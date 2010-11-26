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


$company_id = get_session("company_id"); //得到公司id


switch ($act) {
	case "main" : //填写信息内容页面vehicle_list 402
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	    
	case "vehicle_list" :  //生成车辆列表页面
		$param["VEHICLE_ID"]=$_REQUEST["vehicle_id"];//得到单一车辆信息id
		if(empty($param["VEHICLE_ID"])){
			$param["VEHICLE_ID"]=-1;//如果没有取得要查询的车辆 则让隐藏于为-1传到车辆列表页面
		}
		echo $GLOBALS ['db']->display ($param,$act);
		break;
	
	case "driver_list" :  //驾驶员列表页面
		echo $GLOBALS ['db']->display ( null, $act );
		break;
	
	case "driver_time_data" ://根据时间查询驾驶员信息
		if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->sel_driver_count ($begin_data,$end_data,$company_id);//根据时间和公司id查询驾驶员数量
		
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
		
		//得到驾驶员信息
		$dataList = $driver->get_driver_time ($begin_data,$end_data, $wh, $sidx, $sord, $start, $limit,$company_id );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值
			$response->rows [$key] ['id'] = $value ['id'];
			$response->rows [$key] ['cell'] = array ($value ['id'], $value ['name'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_driver(" . $value ['id'] . ")' style='text-decoration:none;color:#0099FF';font-size:12px;>详细内容</a>" );
		}
		echo json_encode ( $response ); //打印json格式的数据
		break;
	
		
	case "vehicle_time_data" ://根据选择的车辆id,公司id和起始查询和结束查询时间,查询车辆信息
		
		$vehicle_array = $_REQUEST ['select_vehicle']; 	//得到所选择车辆id	
		
		$vehicle_count = explode(",",$vehicle_array);
				
		if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
			
		$count = $driver->sel_vehicle_count ($begin_data,$end_data,$vehicle_array,$company_id);	//在时间段和所选车辆,车辆id的驾驶员数量
				
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
		
		///在时间段和所选车辆,车辆id的驾驶员数据信息
		$dataList = $driver->get_vehicle_time($vehicle_array,$begin_data,$end_data, $wh, $sidx, $sord, $start, $limit,$company_id );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		$id_array = null;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值
			$response->rows [$key] ['vehicle_id'] = $value ['vehicle_id'];	
			$id_array[$key]=$value['vehicle_id'];
			$response->rows [$key] ['cell'] = array ($value ['vehicle_id'], $value ['number_plate'], $value ['distance'], $value ['drive_time'], $value ['stop_time'], $value ['min_time'], $value ['max_time'], "<a href='#' onclick='show_vehicle(" . $value ['vehicle_id'] . ")' style='text-decoration:none;color:#0099FF';font-size:12px;>详细内容</a>" );
		}
		
		if($count<count($vehicle_count)){
			foreach($vehicle_count as $value){
				if(!in_array($value,$id_array)){
					$number = count($response);
					$number_plate_none = $driver->get_number_plate($value);
					$response->rows[$number]['vehicle_id']=$value;
					$response->rows[$number]['cell']=array($value,$number_plate_none['number_plate'],"无数据","无数据","无数据","无数据","无数据","无数据");
				}
			}
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

	    
   case  "drive_detail_data" ://显示驾驶员详细信息列表数据
   	$driver_id=$_REQUEST["drive_id"];//得到驾驶员id 
   	if (! $sidx)
			$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->drive_detail_count($driver_id,$begin_data,$end_data);//根据驾驶员id得到驾驶员详细数量
		
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
		//根据驾驶员id得到驾驶员详细数据信息
		$dataList = $driver->drive_detail_data($driver_id,$begin_data,$end_data, $wh, $sidx, $sord, $start, $limit );
		
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
		
		$count = $driver->stop_detail_count($driver_id,$begin_data,$end_data);
		
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
		
		$dataList = $driver->stop_detail_data($driver_id,$begin_data,$end_data, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值			
			$response->rows [$key] ['id'] = $value ['id'];	
			$response->rows [$key] ['cell'] = array ($value ['id'], $value ['start_time'], $value ['end_time'],$value ['stop_time']);
		}
		echo json_encode ( $response ); //打印json格式的数据	
		break;

		
	case "vehicle_detail_list"://显示车辆详细信息
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
		
		$count = $driver->vehicle_detail_count($vehicle_id,$begin_data,$end_data);
		
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
		
		$dataList = $driver->vehicle_detail_data($vehicle_id,$begin_data,$end_data, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值		
			$response->rows [$key] ['id'] = $value ['id'];	
			$response->rows [$key] ['cell'] = array ($value ['id'],  $value ['start_time'], $value ['end_time'],$value['drive_time'],$value['distance']);
		}
		echo json_encode ( $response ); //打印json格式的数据	
		break;
		
		
	case "vstop_detail_data"://停车详细信息数据
		$vehicle_id=$_REQUEST["vehicle_id"];//得到驾驶员id 
   	if (! $sidx)
		$sidx = 1;
		$wh = "where 1=1 "; //查询条件
		$limit_length = 8; //设置处理意见字符串最多显示8个字符
		$driver = new Statistic ();
		
		$count = $driver->vstop_detail_count($vehicle_id,$begin_data,$end_data);
		
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
		
		$dataList = $driver->vstop_detail_data($vehicle_id,$begin_data,$end_data, $wh, $sidx, $sord, $start, $limit );
		
		$response->page = $page; //分别赋值当前页,总页数，总数据条数
		$response->total = $total_pages;
		$response->records = $count;
		
		foreach ( $dataList as $key => $value ) { //从xml文件中映射相应的数据库字段值			
			$response->rows [$key] ['id'] = $value ['id'];	
			$response->rows [$key] ['cell'] = array ($value ['id'],  $value ['start_time'], $value ['end_time'],$value ['stop_time']);
		}
		echo json_encode ( $response ); //打印json格式的数据	
		break;
	
	case "driver_driving"://显示开车驾驶员的姓名
		  
		  $param["DRIVER_DRIVING_ID"]= $_REQUEST["driver_driving_id"];
		  $param["DRIVER_DRIVING_NAME"]= iconv("gb2312","UTF-8",$_REQUEST["driver_driving_name"]);
  	       
		  echo $GLOBALS ['db']->display ($param, $act );
		
		  break;

   case "driver_stop"://显示停车驾驶员的姓名
		  
 		  $param["DRIVER_STOP_ID"]=$_REQUEST["driver_stop_id"];
  	      $param["DRIVER_STOP_NAME"]=iconv("gb2312","UTF-8",$_REQUEST["driver_stop_name"]);;
  	      
		  echo $GLOBALS ['db']->display ($param, $act );
		
  	      break;
    
   
   case "vehicle_driving"://显示启用车辆的车牌号码
   	      
	   	   $param["VE_DRIVING_ID"]=$_REQUEST["vehicle_drinving_number"];
	   	   $param["VE_DRIVING_NUMBER_PLATE"]=iconv("gb2312","UTF-8",$_REQUEST["vehicle_drinving_numberplate"]);;
		   	      
   	       echo $GLOBALS ['db']->display ($param, $act );
		   
   	       break;
	
   
   case "vehicle_stop"://显示停用车辆的车牌号码
   	 	   
   	       $param["VE_STOP_ID"]=$_REQUEST["vehicle_stop_number"];
   	 	   $param["VE_STOP_NUMBER_PLATE"]=iconv("gb2312","UTF-8",$_REQUEST["vehicle_stop_numberplate"]);
		   
   	 	   echo $GLOBALS ['db']->display ($param, $act );
		   
		   break;
	
}
?>
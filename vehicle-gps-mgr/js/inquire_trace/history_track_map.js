	var map = null; //历史轨迹地图对象
	var arr_history = null; //历史轨迹时间队列数组
	var drawLine_arr = null;  //画线队列
	var vehicle_id = -1; //车辆ID
	var speed = 1000;  //速度/ms
	var progress_length = 0; //历史轨迹时间队列数组长度
	var cur_progress = 0; //当前进度
	
	/***
	 * 操作状态 :处理画历史轨迹操作状态    
	 * 	 'normal' 正常   
	 * 	 'stop' 停止
	 * 	 'search' 查询状态
	 */
	var state = "stop"; 
	var marker;   //地图标记对象
	
	history_map();
	  
	/**
	 * 初始化历史轨迹
	 */
	function history_map(){
		
		//因为地图上的进度条可能会影响折线的事件触发，因此先禁止进度条的显示
		window._LT_map_disableProgressBar=true;	
		map=new LTMaps("map");
		map.cityNameAndZoom( "beijing" , 13);
		var standControl = new LTSmallMapControl();
		map.addControl(standControl);
	 	map.handleMouseScroll();
		//绑定事件注册
		LTEvent.addListener(map,"dblclick",onDblHistoryClick);
	}
	
	var moveLsitener;
	
	/**
	 * 定义在双击的时候执行的函数
	 */
	function onDblClick(){
		//因为系统默认双击的时候会将地图定位到中心，因此，只需要定义地图在定位到中心完成之后放大地图即可
		moveLsitener=LTEvent.addListener(map,"moveend",onMoveEnd);
	}
	/**
	 * 定义地图在定位到中心完成之后执行的函数
	 */
	function onMoveEnd(){
		LTEvent.removeListener(moveLsitener);//删除事件注册
		map.zoomIn();//放大地图
	}
	 
	
	/**
	 * 定义在双击的时候执行的函数
	 */
	function onDblHistoryClick(){
		//因为系统默认双击的时候会将地图定位到中心，因此，只需要定义地图在定位到中心完成之后放大地图即可
		moveLsitener=LTEvent.addListener(map,"moveend",onMoveEnd);
	}
	
	/**
	 * 运行历史轨迹
	 */
	function runHistoryTrack(){   
		//如果当前画线数组还存在数据，继续执行画线
		if(drawLine_arr != null && state === "normal"){
			 
			if(drawLine_arr.length > 0 ){ 
				newDrawLine();
			} 
		}else{ //当画线队列数组不存在数据是，执行时间段取点  
			 
			if(arr_history.length>0){ 
				cur_progress ++;
				
				var progress_val = round((cur_progress/progress_length)*100,0);
				window.parent.progress_assignment(progress_val);
				
				var time = arr_history[0];  
				arr_history.shift();
				
				
				drawHistoryTrack(time,vehicle_id);
			}
		}
	}
	
	/**
	 * 清除所有标点，重新加载新数据
	 */
	function clearOverLay(){
		map.clearOverLays(); 
	}
	
	//等待线程
	function wait(){
		
		setTimeout(function(){
			if(drawLine_arr!=null){
				if(drawLine_arr.length<=0){ 
					drawLine_arr = null;
					runHistoryTrack(); 
				}else{
					wait();
				} 
			}
			},1000);
	}
	 
	/**
	 * 画历史轨迹路径
	 * @param {Object} time  画数据时间点
	 * @param {Object} vehicle_id 车辆编号
	 */
	function drawHistoryTrack(time,vehicle_id){   
	 	 //var time = '2010081017';
		$("#inquireing",parent.document).mask("查询中...");
		state = "search";
		$.ajax({
			type:"POST",
			url:window.parent.host+"/index.php?a=353&time="+time+"&vehicle_id="+vehicle_id, 
			dataType:"json",
			success:function(data){    
			$("#inquireing",parent.document).unmask();
			state = "normal"; 
			if(data==0 || data == null || data == ""){ //请求失败，转入下一个请求时间点
			   if(arr_history.length>0){
					runHistoryTrack();
				}
			}
			if(data!=null){

				var points = new Array();
				
				for(var i=0;i<data.length;i++){
					points.push( new LTPoint(data[i][0],data[i][1]));
				}
				map.getBestMap(points);
			} 
			drawLine_arr = data; 
			 
			newDrawLine();
			
			wait();
			
		 }
		}); 
	}
	/**
	 * 画新线
	 */
	function newDrawLine(){ 
		 
		if(drawLine_arr!=null && state==="normal"){ //等于‘正常’状态
			var length = drawLine_arr.length; 
			if(length>0){
				 	
			 		var points = new Array();
			 		 
			 		var longitude = drawLine_arr[0][0];  //经度
			 		var latitude = drawLine_arr[0][1];  //纬度
			 		var direction = drawLine_arr[0][2]; //方向
			 		var vehicle_speed = drawLine_arr[0][3]; //车辆速度
			 		var color = drawLine_arr[0][4]; //颜色
					var img_path =  drawLine_arr[0][5]; //图片路径
					var location_time = drawLine_arr[0][6]; //定位时间
			 		var newLongitude = -1; //新线点经度
			 		var newLatitude = -1; //新线点纬度

					//线的起始点
					points.push( new LTPoint(longitude,latitude));
				if(length>1){
					//线的终点
					newLongitude = drawLine_arr[1][0];
					newLatitude = drawLine_arr[1][1];
					points.push( new LTPoint(newLongitude,newLatitude));
				}else{
					//当前起始点为终点
					newLongitude = longitude;
					newLatitude = latitude;
					points.push( new LTPoint(newLongitude,newLatitude));
					
					//当历史轨迹画完之后，回到初始状态
					if(arr_history.length <=0 || arr_history == null || arr_history == ""){
						$("#suspend_history",parent.document).hide();
						$("#play_history",parent.document).show();
						 
						window.parent.empty_cur_vhicle_history();
						
						state = "stop";
						return false;
					}
				} 
				
				//删除并返回数组的第一个元素
				drawLine_arr.shift();

				//调用画线函数 
				drawRunLine(points,newLongitude,newLatitude,direction,color,vehicle_speed,img_path,location_time);
			} 
	 }
}
	
	/**
	 * 运行画线函数
	 * @param {Object} points 画线点
	 * @param {Object} lon 经度
	 * @param {Object} lat 纬度
	 * @param {Object} direction 方向
	 * @param {Object} color 颜色
	 * @param {Object} vehicle_speed 车辆速度 
	 * @param {Object} img_path 图片路径
	 * @param {Object} location_time 定位时间
	 */
	function drawRunLine(points,longitude,latitude,direction,color,vehicle_speed,img_path,location_time){  
		 
		 
		var polyLine = new LTPolyLine(points);
		polyLine.setLineColor("#"+color);	//设置折线颜色 
		polyLine.setLineStroke(3);	//设置折线线宽
		
		polyLine.setLineArrow("None","None");
		//将折线添加到地图
		map.addOverLay( polyLine );

		map.removeOverLay(marker); 
		marker = new LTMarker( new LTPoint(longitude,latitude),new LTIcon(window.parent.host+"/"+img_path));
 
		$("#location_info").css("display","inline");

		$("#direction",parent.document).html(direction_change(direction));
		$("#speed",parent.document).html(vehicle_speed);
		$("#longitude",parent.document).html(around(longitude));
		$("#latitude",parent.document).html(around(latitude));
		$("#location_time",parent.document).html(format_time(location_time));
		$("#address",parent.document).html("<a id='more' name="+longitude+" rel="+latitude+" href='javascript:history_track_frame.details();'>查看详情</a>")

		//点添入地图中
		map.addOverLay(marker); 

		setTimeout("newDrawLine();",speed);
	}
	/**
	 *  根据经纬度查看具体地址	
	 */
	function details(){ 
		  
		var long = $("#more",parent.document).attr("name");
		var lat = $("#more",parent.document).attr("rel");
		$.get(window.parent.host+"/index.php?a=503",{"longitude":long,"latitude":lat},function(data){
			 
			$("#address",parent.document).html(data.replace("[null,null]",""));
			});
	}
	/**
	 *  定位时间格式化
	 *  @param location_time 定位时间
	 */
	 function  format_time(location_time){
		var year = location_time.substring(0,4);
		var month = location_time.substring(4,6);
		var day = location_time.substring(6,8);
		var hour = location_time.substring(8,10);
		var minutes = location_time.substring(10,12);
		var seconds = location_time.substring(12,14);
		var time = year+"/"+month+"/"+day+" "+hour+":"+minutes+":"+seconds;
		return time;
		 }

	 /**
	  *  将51地图经纬度转换成真正的经纬度	
	  *  @param v 经度或纬度
	  */
	 function around(v){
			v = v/100000;
			return v;
		}

	 /**
	  *  转换方向
	  *  @param direction 方向
	  */
	 function direction_change(direction){
		 var directions = new Array(8);
		 directions['north']="北";
		 directions['east']="东";
		 directions['west']="西";
		 directions['south']="南";
		 directions['northeast']="东北";
		 directions['northwest']="西北";
		 directions['southeast']="东南";
		 directions['southwest']="西南";
		 
		return directions[direction];
			
		}  
	 /**
	  * 车辆请求定位
	  * @param {Object} str 车辆ID集合 格式"ID1,ID2,ID3,"
	  */
	function vehiclePosition(str){ 
		 
		 $.ajax({
				type:"POST",
				url:window.parent.host+"/index.php?a=2&vehicleIds="+str, 
				dataType:"json",
				success:function(data){ 
				
					var length = data.length; 
					var points = new Array();
					 
					if(length>0)clearOverLay();
					
					for(var i=0;i<length;i++){        
						  
						var point_longitude = data[i][1]; //点经度
						var point_latitude =  data[i][2]; //点纬度
						var file_path = data[i][4]; //文件目录
						var img_name = data[i][3];  //图片名称
						var number_plate = data[i][5]; //车牌号
						var gps_id = data[i][6]; //GPS编号 
						var location_time = data[i][7];//定位时间
						var vehcile_group_name = data[i][8];//车组名
						var driver_name = data[i][9]; //驾驶员
						var cur_speed = data[i][10];//速度
						var location_desc = data[i][11];//地址

						points.push( new LTPoint(point_longitude,point_latitude));

					 	
						//创建点对象
						marker =new LTMarker(new LTPoint(point_longitude,point_latitude),
										 	  new LTIcon(window.parent.host+"/"+file_path+"/"+img_name+".png"));

						//点添入地图中
						map.addOverLay(marker);

						var text = new LTMapText( new LTPoint(point_longitude,point_latitude ) );
						text.setLabel(number_plate ); 
						map.addOverLay( text ); 
					}
					map.getBestMap(points);
				 }
				});
	}
		/**
		 * 车辆提示信息
		 * @param {Object} obj  点对象
		 * @param {Object} context 对象提示内容
		 */
		function addInfoWin(obj,context){
			
			var info = new LTInfoWindow( obj );

			function shwoInfo(){
				info.setLabel(context);
				info.clear();
				map.addOverLay(info);
			}
			LTEvent.addListener(obj,"click",shwoInfo); 
		} 
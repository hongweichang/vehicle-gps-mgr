var map;
var vehicle_id = -1; // 车辆ID
var speed = 1000;  // 速度/ms
var progress_length = 0; // 历史轨迹时间队列数组长度
var cur_progress = 0; // 当前进度
var data_queue_state = 1;  // 数据队列状态，0 非新数据状态 1 新数据状态
var data_queue_point_second = 0; // 数据队列是否是第二个点 0 非 1 真
var second_longitude = -1; // 数据队列第二个点经度
var second_latitude = -1; // 数据队列第二个点纬度
var old_longitude = -1;// 旧点经度
var old_latitude = -1;// 旧点纬度
/**
 * 区域自动匹配用户查看设置 0 非匹配 1 匹配
 */
var chanage_state = 0; 

/*******************************************************************************
 * 操作状态 :处理画历史轨迹操作状态 'normal' 正常 'suspend' 暂停 'stop' 停止
 */
var state = "stop"; 
var marker;   // 地图标记对象

var map_type;//判断地图是卫星模式还是普通地图,初始设为卫星模式


var leftOffsetRatio = 0.05;  // 矩形左间距
var rightOffsetRatio = 0.1;  // 矩形右间距
var upOffsetRatio = 0.05;    // 矩形上间距
var downOffsetRatio = 0.1;   // 矩形下间距

function hitory_load_google() {
	geocoder = new google.maps.Geocoder();

	showAddress("北京");
}

function load_map(latlng) {
	var myOptions = {
		zoom : 8,
		center : latlng,
		mapTypeId : google.maps.MapTypeId.HYBRID
	};

	map = new google.maps.Map(document.getElementById("google_history"), myOptions);
	
	map_type = map.getMapTypeId();
	
	var change_type_event = google.maps.event.addListener(map,"maptypeid_changed",function(){
		map_type = map.getMapTypeId();
	});
	
	var pre_id = parent.document.getElementById("pre_vehicle_id").value;
	
	if(pre_id != ""){
		vehiclePosition(pre_id);
	}
}

function showAddress(address) {
	if (geocoder) {
		geocoder.geocode({
			'address' : address
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				load_map(results[0].geometry.location);
			} else {
				alert("Geocode was not successful for the following reason: "
						+ status);
			}
		});
	}
}

/**
 * 运行历史轨迹
 */
function runHistoryTrack(){  
	var myOptions = {
		mapTypeControl : false
	};
	
	map.setOptions(myOptions);
	
	// 如果当前画线数组还存在数据，继续执行画线
	if(window.parent.drawLine_arr != null  && state === "normal"){
		if(window.parent.drawLine_arr.length > 0 ){ 
			newDrawLine();
		} 
	}else{ // 当画线队列数组不存在数据时，执行时间段取点
		 
		if(window.parent.arr_history.length>0){ // 当时间数组还存在时间，连续查询
			cur_progress ++;
			// 按比例计算进度条进度数值
			var progress_val = round((cur_progress/progress_length)*100,0);
			window.parent.progress_assignment(progress_val);
			var time = window.parent.arr_history[0];  
			window.parent.arr_history.shift();// 删除已查询日期
			// 查询
			drawHistoryTrack(time,vehicle_id);
		}
		
	}
}

/**
 * 清除所有标点，重新加载新数据
 */
function clearOverLay(){
	if(marker != null){
		marker.setMap(null);
		marker = null;
	}
}
 
// 等待线程
function wait(){
	
	setTimeout(function(){
		if(window.parent.drawLine_arr!=null){// 唤醒线程
			if(window.parent.drawLine_arr.length<=0){ 
				window.parent.drawLine_arr = null;
				runHistoryTrack(); 
			}else{ // 等待
				wait();
			} 
		}
		},1000);
}
 
/**
 * 画历史轨迹路径
 * 
 * @param {Object}
 *            time 画数据时间点
 * @param {Object}
 *            vehicle_id 车辆编号
 */
function drawHistoryTrack(time,vehicle_id){   
	var space = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";   
	$("#google_history").mask(space+"查询中...<br>"+format_time(time,"yyyy/MM/DD/HH"));  

	$.ajax({
		type:"POST",
		url:window.parent.host+"/index.php?a=353&map_type="+map_type+"&time="+time+"&vehicle_id="+vehicle_id, 
		dataType:"json",
		success:function(data){  
		if(state == "stop") return false; // 停止状态不能运行
		 
		$("#google_history").unmask();
		
		/**
		 * 日期请求查询路线为空操作
		 */
		if(data==0 || data == null || data == ""){ 
		   if(window.parent.arr_history == null) return false; // 查询时间队列为空
		   if(window.parent.arr_history.length>0){ // 如果日期数组存在日期数据，连续请求查询
			   runHistoryTrack();
				return false;
			}else // 否则进入停止状态
				return end_history_line();
		}
		/**
		 * 日期请求查询路线存在操作
		 */	
		if(data!=null) 
			data_queue_state = 1; // 设置新数据状态
		
		// 路线赋值
		window.parent.drawLine_arr = data; 
		 
		// 新画路线
		newDrawLine();
		
		// 线程等待
		wait();
		
	 }
	}); 
}
/**
 * 画新路线
 */
function newDrawLine(){ 
	if(window.parent.drawLine_arr!=null && state==="normal"){ // 等于‘正常’状态
		var length = window.parent.drawLine_arr.length; 
		if(length>0){
			 	
		 		var points = new Array();
		 		 
		 		var longitude = around(window.parent.drawLine_arr[0][0]);  // 经度
		 		var latitude = around(window.parent.drawLine_arr[0][1]);  // 纬度
		 		var direction = window.parent.drawLine_arr[0][2]; // 方向
		 		var vehicle_speed = window.parent.drawLine_arr[0][3]; // 车辆速度
		 		var color = window.parent.drawLine_arr[0][4]; // 颜色
				var img_path =  window.parent.drawLine_arr[0][5]; // 图片路径
				var location_time = window.parent.drawLine_arr[0][6]; // 定位时间
		 		var newLongitude = -1; // 新线点经度
		 		var newLatitude = -1; // 新线点纬度
		 		var point_index = -1; // 数据点下标
		 		 
		 		// 初始始画线点1
		 		switch(parseInt(data_queue_state)){
					case 0: // 非新数据队列状态
						
						point_index = 1;// 非新数据队列状态，队列点2为终点线坐标
						
						if(data_queue_point_second==1){ 
							//points.push( new LTPoint(second_longitude,second_latitude));// 画线开始点
							points.push(new google.maps.LatLng(second_latitude,second_longitude));
							data_queue_point_second = 0;
						}else
							//points.push( new LTPoint(longitude,latitude));// 画线开始点
							points.push(new google.maps.LatLng(latitude,longitude));
						break;
					case 1: // 新数据队列状态
						
						point_index = 0;// 新数据队列状态 ，队列点1为终点线坐标
						data_queue_point_second = 1;
						
						if(old_longitude === -1 && old_latitude === -1)  
							points.push( new google.maps.LatLng(latitude,longitude));// 新路线开始点
						else
							points.push( new google.maps.LatLng(old_latitude,old_longitude));// 新路线连接点
						
						break;
		 		}
		 		
		 	/**
			 * 设置画线下一个点经纬度
			 */	
			if(length>1){ // 队列数据大于1
				 
				newLongitude = around(window.parent.drawLine_arr[point_index][0]);// 线的终点经度
				newLatitude = around(window.parent.drawLine_arr[point_index][1]); // 线的终点纬度
				
				second_longitude = newLongitude;
				second_latitude = newLatitude;
				
				points.push( new google.maps.LatLng(newLatitude,newLongitude));
			}else{ // 队列中 只存在最后一个点坐标
				
				// 当前起始点为终点
				newLongitude = longitude;
				newLatitude = latitude;
				points.push( new google.maps.LatLng(newLatitude,newLongitude));
				
				// 保存最后经纬度点
				old_longitude = newLongitude;
				old_latitude = newLatitude; 
				
				// 当历史轨迹画完之后，回到初始状态
				if(window.parent.arr_history.length <=0 || window.parent.arr_history == null || window.parent.arr_history == ""){
					return end_history_line();
				}
			} 
			
			// 改变数据队列状态 为非新数据队列状态
			if(data_queue_state ===1)
					data_queue_state = 0;
			
			// 删除并返回数组的第一个元素
			window.parent.drawLine_arr.shift(); 
			// 调用画线函数
			drawRunLine(points,newLongitude,newLatitude,direction,color,vehicle_speed,img_path,location_time,newLongitude,newLatitude);
		} 
 }
}
/**
 * 历史轨迹请求画完，返回初始状态
 * 
 * @return 停止运行 返回上一级操作
 */
function  end_history_line(){
	var myOptions = {
			mapTypeControl : true
		};
		
	map.setOptions(myOptions);
	
	window.parent.empty_cur_vhicle_history();
	
	state = "stop";
	return false;
}
/**
 * 运行画线函数
 * 
 * @param {Object}
 *            points 画线点
 * @param {Object}
 *            longitude 第一个经度
 * @param {Object}
 *            latitude 第一个纬度
 * @param {Object}
 *            direction 方向
 * @param {Object}
 *            color 颜色
 * @param {Object}
 *            vehicle_speed 车辆速度
 * @param {Object}
 *            img_path 图片路径
 * @param {Object}
 *            location_time 定位时间
 * @param {Object}
 *            newLongitude 第二个经度
 * @param {Object}
 *            newLatitude 第二个纬度
 */
function drawRunLine(points,longitude,latitude,direction,color,vehicle_speed,img_path,location_time,newLongitude,newLatitude){  
	var new_lnglat = new google.maps.LatLng(newLatitude,newLongitude);
	
	var best_bound = new google.maps.LatLngBounds(new_lnglat,new_lnglat);
	
	/**
	 * 区域自动匹配用户查看设置 1 匹配 0 非匹配
	 */
	switch (parseInt(chanage_state)) {
		case 1: // 匹配
			//获取当前地图矩形范围
			var bound = map.getBounds();
			var map_min = bound.getSouthWest();
			var map_max = bound.getNorthEast();
			
			var xmin = map_min.lng(); // 最小经度
			var ymin = map_min.lat(); // 最小纬度
			var xmax = map_max.lng(); // 最大经度
			var ymax = map_max.lat(); // 最大纬度 
			
			var longitudeRange = xmax - xmin; // 矩形经度范围
			var latitudeRange = ymax - ymin;  // 矩形纬度范围

			// 验证车辆当前位置是否超出范围
			var isOutofMapRange = (((longitude - xmin) / longitudeRange) <= leftOffsetRatio) ||
			(((xmax - longitude) / longitudeRange) <= rightOffsetRatio) ||
			(((latitude - ymin) / latitudeRange) <= downOffsetRatio) ||
			(((ymax - latitude) / latitudeRange) <= upOffsetRatio);
			
			if (isOutofMapRange) {// 超出范围
				// 重新获得最佳位置
				//map.fitBounds(best_bound);
				map.panTo(new_lnglat);
			}
			break;
		case 0:	// 非匹配
			chanage_state = 1;
			
			//map.fitBounds(best_bound);
			map.panTo(new_lnglat);
			
			break;
	} 

	var polyLine = new google.maps.Polyline({
		path: points,
		strokeColor: "#"+color,
		strokeWeight: 3
	}); 
	
	polyLine.setMap(map);
	
	// 删除车辆显示上一个点对象
	clearOverLay();
	
	// 新建车辆显示新一个点对象(注：实现车辆当前开动效果)
	marker = new google.maps.Marker({
		position: new_lnglat,
	    map: map,
	    icon: "/"+img_path,
	    draggable: false
	});
	
	/**
	 * 将最新点的信息数据载入‘定位信息’窗口中。
	 */
	$("#location_info").css("display","inline");

	$("#direction",parent.document).html(direction_change(direction));
	$("#speed",parent.document).html(vehicle_speed);
	$("#longitude",parent.document).html(longitude);
	$("#latitude",parent.document).html(latitude);
	$("#location_time",parent.document).html(format_time(location_time,"yyyy/MM/DD/HH:mm:ss"));
	$("#address",parent.document).html("<a id='more' name="+r_around(longitude)+" rel="+r_around(latitude)+" href='javascript:history_track_frame.details();'>查看详情</a>")

	setTimeout("newDrawLine();",speed);
}
/**
 * 根据经纬度查看具体地址
 */
function details(){ 
	  
	var long = $("#more",parent.document).attr("name");
	var lat = $("#more",parent.document).attr("rel");
	$.get(window.parent.host+"/index.php?a=503",{"longitude":long,"latitude":lat},function(data){
		 
		$("#address",parent.document).html(data.replace("[null,null]",""));
		});
}

 /**
	 * 将51地图经纬度转换成真正的经纬度
	 * 
	 * @param v
	 *            经度或纬度
	 */
 function around(v){
		v = v/100000;
		return v;
	}
 
 /*
  * 51经纬度转换为GOOGLE经纬度
  */
 function r_around(v){
	 v = v*100000;
	 return v;
 }

 /**
	 * 转换方向
	 * 
	 * @param direction
	 *            方向
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
	 * 
	 * @param {Object}
	 *            str 车辆ID集合 格式"ID1,ID2,ID3,"
	 */
function vehiclePosition(str){ 
	 $.ajax({
			type:"POST",
			url:window.parent.host+"/index.php?a=2&vehicleIds="+str, 
			dataType:"json",
			success:function(data){ 
				var length = data.length; 
				var points = new Array();
				var vehicle_latlng;
				 
				if(length>0 && marker != null)clearOverLay();
				
				for(var i=0;i<length;i++){        
					 vehicle_id = data[0]['id']; // 车辆id
					 number_plate = data[0]['number_plate']; // 车牌号
					 point_longitude = around(data[0]['cur_longitude']); // 当前经度
					 point_latitude = around(data[0]['cur_latitude']); // 当前纬度
					 alert_state = data[0]['alert_state'];// 告警状态
					 img_name = data[0]['cur_direction']; // 图片名
					 file_path = data[0]['file_path']; // 文件路径

					 vehicle_latlng = new google.maps.LatLng(point_latitude,point_longitude);
					// 车辆点位置添入数组中，地图视图显示
					 points.push( vehicle_latlng);
					
					// 创建点对象
					 var icon = "/"+file_path+"/"+img_name+".png";

					 marker = new google.maps.Marker({
					      position: vehicle_latlng,
					      map: map,
					      icon: icon,
					      title: number_plate
					});
				}
				
				var vehicles_bounds = new google.maps.LatLngBounds(vehicle_latlng,vehicle_latlng);
				map.fitBounds(vehicles_bounds);
			 }
			});
}

/**
 * 取消遮罩效果。 为了父窗口控制用。
 * 
 * @return
 */
function cancle_mask(){
	$("#google_history").unmask();
}
/**
 * commpany  秦运恒  
 * date  2010-08-16 11:56
 * function 首页地图脚本函数库
 * author 叶稳
 * update 
 * modifier
 */
	var map;  //声明51地图对象
	var control; //声明51地图控件
	var longitude = 3991104;  // 经度
	var latitude = 11636160;  //纬度
	var speed = 1000;  //速度/ms
	
	
	var overLay = new Array(); 		  //标注点对象数组集合
	var vehicleEvent =  new Array();  //车辆事件队列数组
	var longitudeArray = new Array(); // 所有车辆经度保存数组
	var latitudeArray = new Array();  // 所有车辆纬度保存数组
	var points = new Array(); 		  //当前显示标注点数组集合
	/**
	 * 点击过点记录，根据点记录这次与上次的点记录是否一致，如果一致，不重新加载点最新信息
	 */
	var backup_longitude = -1; //经度
	var backup_latitude = -1;  //纬度
	  
	/**
	 * 刷新状态(注：刷新不同操作，例：刷新公司车辆)
	 * 0 刷新公司所有车辆
	 * 1 刷新选择车辆
	 * 2 停止刷新
	 */
	var refresh_state = 0;  
	/**
	 * 刷新最新车辆集合
	 * 例:1,2,3,4,5  定位最新车辆集合
	 */
	var refresh_vehicles = null;
	  
	/**
	 * 区域自动匹配用户查看设置
	 * 0  非匹配
	 * 1  匹配
	 */
	var chanage_state = 0; 
	
	/**
	 * 车辆定位状态
	 * 0 不可定位状态
	 * 1 可定位状态
	 */
	var position_vehicle_state = 1;
	  
	var leftOffsetRatio = 0.05;  //	矩形左间距
	var rightOffsetRatio = 0.05;  //	矩形右间距
	var upOffsetRatio = 0.05;    //	矩形上间距
	var downOffsetRatio = 0.05;   //	矩形下间距
	var company_position_state=1;
	
	var poi=null; //公司标注位置信息
	var info = null;//信息弹框对象
	
	
	onLoadMap(); 
	  
	/**
	 * 加载地图
	 */
	function onLoadMap(){ 
	
		//因为地图上的进度条可能会影响折线的事件触发，因此先禁止进度条的显示
		window._LT_map_disableProgressBar=true;	
		map=new LTMaps("map");
		map.setMapCursor("hand","hand");
		
		var standControl = new LTStandMapControl();
		standControl.setTop(40);
		map.addControl(standControl);
	 
		/*添加拉框放大控件*/
		var ltControl = new LTZoomInControl();
		map.addControl( ltControl );
		ltControl.setRight(240);
		
		info = new LTInfoWindow();
		
			/*添加标注控件*/
		var ltmControl = new LTMarkControl(new LTIcon(window.parent.host+"/images/company.gif"));
		map.addControl( ltmControl );
		LTEvent.addListener( ltmControl , "mouseup" , getPoii );
		
		 
		function getPoii(){
			poi = ltmControl.getMarkControlPoint();
			parent.window.company_position_show();
		}
		
		map.handleMouseScroll();
		LTEvent.addListener(map,"dblclick",onDblClick); 
		
		//初始化车辆定位
		loadCompanyVehicle();
	} 
	$("#commit",parent.document).click(function(){
		var name = $("#name",parent.document).val();
		parent.window.company_position_close();
		getPoi(name);
	});

	function getPoi(name){
		if (name!=null && name!=""){
			$.ajax({
				type: "POST",
				url: window.parent.host+"/index.php?a=105&name="+encodeURI(name)+"&longitude="+poi.getLongitude()+"&latitude="+poi.getLatitude(),
				dataType: "json",
				success: function(data){
				 
					 var info = new Array();
					 info[0]="标注失败!";
					 info[1]="标注成功!";
					 
					 alert(info[parseInt(data)]);
					 
					 if(data==1){
						 var company_text = new LTMapText(new LTPoint(poi.getLongitude(), poi.getLatitude()));
						 company_text.setLabel(name);
						 map.addOverLay(company_text);
						 
						 company_position_state=1;
						 company_position(poi.getLongitude(),poi.getLatitude());
					 }						  
				}
			});
	    }else{
	    	alert("请输入公司名");
	    }
	}
	
	/**
	 * 标注点定位
	 * @param longitude 经度
	 * @param latitude  纬度
	 * @return
	 */
	function company_position(longitude,latitude){
		 
		 var ltPoint = new LTPoint(longitude,latitude);//标注点对象
		 var ltIcon = new LTIcon(window.parent.host+"/images/company.gif"); //标注点图标对象
		 
		 //创建标注点(结合 标注点对象与标注点图标对象)
		 var marker = new LTMarker(ltPoint,ltIcon);
		 
		 //添入地图中
		 map.addOverLay( marker ); 
		
		 overLay.push(marker);//将标注点,添入将删除标注点队列中,定期清除
		 position_add_showinfo(marker);
		 
		 //内存释放对象
		 ltPoint = null;
		 ltIcon = null;
	}
	/**
	 * 标注点定位显示信息
	 * @param obj 标注点对象
	 * @return
	 */
	function position_add_showinfo(obj){
		
		function show_maker_info(){
			info.setPoint(obj);
			info.setTitle("<div style='font-weight:700;font-size:12px;'>北京龙菲业</div>");
			info.setLabel( "<div><div class='lable'><div class='lable_title'>联系人：</div><div class='lable_content'>未填</div></div><div class='lable'><div class='lable_title'>邮编：</div><div class='lable_content'>未填 </div></div><div class='lable'><div class='lable_title'>电话：</div><div class='lable_content'>未填</div></div><div class='lable'><div class='lable_title'>传真：</div><div class='lable_content'>未填</div></div><div class='lable'><div class='lable_title'>邮箱：</div><div class='lable_content'>未填</div></div><div class='lable'><div class='lable_title'>网址：</div><div class='lable_content'>未填</div></div><div class='lable'><div class='lable_title'>地址：</div><div class='lable_content'>未填</div></div></div> " ); 
			info.moveToShow(); //如果信息浮窗超出屏幕范围，则移动到屏幕中显示 
			map.addOverLay( info );
		}
		//标注点添加点击事件 
		var clickEvent = LTEvent.addListener(obj,"click",show_maker_info);
		vehicleEvent.push(clickEvent); //添入事件队列中，重新加载时，在内存中清空历史事件
	}
	
	/**
	 * 设置地图初始状态
	 * @return
	 */
	function init_state(){
		
		/**初始点击过的经纬度*/
		backup_longitude=-1;
		backup_latitude=-1;
	}
	 
	/**
	 * 定位刷新操作
	 */
	$("#location_refresh",parent.document).click(function(){ 
		if($(this).attr('checked') ){ 
			position_vehicle_state=1;
			refresh_vehicle_info();
		}
	}); 
 
	/**
	 * 清空所有标注点，并将内存中标注点清空
	 * */
	function clearAllMarker(){
		
		var length = overLay.length;
		for(var i=0;i<length;i++){
			map.removeOverLay(overLay[0],true);
			overLay.shift();
		}	
		
		var vehicleEventLength = vehicleEvent.length;
		for(var i=0;i<vehicleEventLength;i++){ 
			 LTEvent.removeListener(vehicleEvent[0]);
			 vehicleEvent.shift();
		} 
	 
	} 
	/**
	 * 清空数组所有数据
	 * @param arrObj 数组
	 * @return
	 */
	 function clearArray(arrObj){ 
		 while(arrObj.length>0){
			 arrObj.pop(); 
		 }
	 }
	/**
	 * 初始化当前公司所有车辆定位信息
	 */
	function loadCompanyVehicle(){  
		 
		//不可直接定位验证
		if(position_vehicle_state == 0){
			if (!$("#location_refresh",parent.document).attr('checked') || refresh_state!=0) return false;
		}
		//点击选择车辆时第一次，设置可直接定位,然后第二次不可直接定位 
		position_vehicle_state = 0;
		
			$.ajax({
				type: "POST",
				url: window.parent.host+"/index.php?a=101",
				dataType: "json",
				success: function(data){ 
					if (data != null) {
					   //var ids=new Array();  
						var length = data.length;
						var run_index = length;
						  
						if (length > 0) 
							clearAllMarker(); //清空所有标注点
						
						if(company_position_state==1){
							$.ajax({
								type:"get",
								url:window.parent.host+"/index.php?a=106",
								dataType:"json",
								success:function(positiones){
								
									for(var j = 0;j<positiones.length;j++){
										var lon = positiones[j][3];
										var lat = positiones[j][4];
										var name = positiones[j][2];
										
										var company_text = new LTMapText(new LTPoint(lon, lat));
										company_text.setLabel(name);
										map.addOverLay(company_text);
										
										overLay.push(company_text);//将标注点,添入将删除标注点队列中,定期清除
										//定位标注点
										company_position(lon,lat);
										
										//标注完公司，清空公司数组队列
										if(j==positiones.length-1) 
												clearArray(positiones);  
										 
									} 
								}
							});
						}
						
						//循环获取加载车辆的基本
						for (var i = 0; i < length; i++) {
						
							var vehicle_id = data[i]['id']; //车辆id
							var number_plate = data[i]['number_plate']; //车牌号
							var point_longitude = data[i]['cur_longitude']; //当前经度
							var point_latitude = data[i]['cur_latitude']; //当前纬度 
							var alert_state = data[i]['alert_state'];// 告警状态
							var img_name = data[i]['cur_direction']; //图片名
							var file_path = data[i]['file_path']; //文件路径
							
							//ids[i] = vehicle_id;
							
							//循环
							//取得所有车的最大经度、最小经度、最大纬度、最小纬度
							longitudeArray[i] = point_longitude;
						 	latitudeArray[i] = point_latitude;
						 	
						 	var ltPoint =  new LTPoint(point_longitude, point_latitude); 
						 	var ltIcon = new LTIcon(window.parent.host + "/" + file_path + "/" + img_name + ".png");
							//创建点对象
							var marker = new LTMarker(ltPoint,ltIcon );
							 
							points.push(ltPoint); 
							
							//点对象设置内容 
							marker.openInfoWinElement("车牌号:"+number_plate);
							
							var title = "<span class='span'>"+number_plate+"</span>";
							addInfoWin(marker,title,vehicle_id);
							 
							//点添入地图中
							map.addOverLay(marker); 
							
							overLay.push(marker);
							
							ltPoint = null;
							ltIcon = null;
							marker = null; 
							 
							var text = new LTMapText(new LTPoint(point_longitude, point_latitude));
							if(alert_state==0){
								text.setLabel(number_plate+" 正常");
							}else if(alert_state==1){
								text.setBackgroundColor("red");//更改文字标签背景色
								text.setLabel(number_plate+" 超速");

							}else{
								text.setBackgroundColor("yellow");//更改文字标签背景色
								text.setLabel(number_plate+" 疲劳");
							}
							map.addOverLay(text);//标注点添入地图中
							overLay.push(text);//将标注点,添入将删除标注点队列中,定期清除
 							
							run_index--;
						} 
						/**
						 * 区域自动匹配用户查看设置
						 * 0 非匹配
						 * 1 匹配
						 */ 
						 switch (chanage_state) {
							case 0:	//非匹配
								chanage_state = 1;
								map.getBestMap(points); 
								map.zoomTo(map.getCurrentZoom()==0?1:map.getCurrentZoom()); 
								break;
							case 1: //匹配 
								var bound = map.getBoundsLatLng(); //矩形范围对象 
								var xmin = bound.getXmin(); // 最小经度
								var ymin = bound.getYmin(); // 最小纬度
								var xmax = bound.getXmax(); // 最大经度
								var ymax = bound.getYmax(); // 最大纬度 
								
								var longitudeRange = xmax - xmin; // 矩形经度范围
								var latitudeRange = ymax - ymin;  // 矩形纬度范围
								
								//取出所有车辆中的最大经纬度、最小经纬度。	
								var point_longitude_min = Math.min.apply(Math, longitudeArray);
								var point_longitude_max = Math.max.apply(Math, longitudeArray);
								var point_latitude_min = Math.min.apply(Math, latitudeArray);
								var point_latitude_max = Math.max.apply(Math, latitudeArray);
								
								//验证车辆当前位置是否超出范围
								var isOutofMapRange = (((point_longitude_min - xmin) / longitudeRange) <= leftOffsetRatio) ||
								(((xmax - point_longitude_max) / longitudeRange) <= rightOffsetRatio) ||
								(((point_latitude_min - ymin) / latitudeRange) <= downOffsetRatio) ||
								(((ymax - point_latitude_max) / latitudeRange) <= upOffsetRatio);
								 
								if (isOutofMapRange) {//超出范围
									//重新获得最佳位置
									map.getBestMap(points);
									map.zoomTo(map.getCurrentZoom()==0?1:map.getCurrentZoom()); 
								}
								break; 
						} 
						clearArray(longitudeArray); //清空矩形经度队列数组
						clearArray(latitudeArray);  //清空矩形纬度队列数组
						clearArray(points);			//清空当前显示标注点集合
						
						wait_load_vehicle();
						
						//等待加载完车辆定位，运行下一步
						function wait_load_vehicle(){
							if(run_index==0){
								clearArray(data); 
								refresh_vehicle_info();
							 }else{ 
								 setTimeout(function(){
									 wait_load_vehicle();
								 },1000); 
							 }	 
						 }
						
					}
				}
			});   
	}  
	/**
	 * 刷新公司车辆信息
	 * @return
	 */
	function refresh_vehicle_info(){  
		if (!$("#location_refresh",parent.document).attr('checked') || refresh_state==2)  return false;
		init_state();//设置地图初始状态
		
		switch(refresh_state){
			case 0:    //'0'代表刷新所有车辆
				refresh_state=0;
				setTimeout(function(){ 
					loadCompanyVehicle();
				}, window.parent.page_refresh_time * 1000);
				break;
			case 1:  //‘1’代表刷新选择监控车辆 
				refresh_state=1;
				setTimeout(function(){  
					 vehiclePosition();
				}, window.parent.page_refresh_time * 1000); 
				break; 
		} 
		
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
	
	//气泡关闭事件处理任务 
	function LTInfoWindow_close(){
		backup_longitude = -1;
		backup_latitude = -1;
	}
	 
	/**
	 * 车辆提示信息
	 * @param {Object} obj  点对象
	 * @param vehicle_id 车辆ID
	 * @PARAM title 车牌号，用于标题显示
	 */
	var info_old; //上一次打开的信息浮窗
	function addInfoWin(obj,title,vehicle_id){ 
		 
		function shwoInfo(){
			info.setPoint(obj);
			
			//当前车辆点信息窗口添加关闭监控事件
			var closeEvent = LTEvent.addListener(info,"close",LTInfoWindow_close);
			vehicleEvent.push(closeEvent);//添入事件队列中，重新加载时，在内存中清空历史事件
			
			
		    //如果当前车辆点未发现改变时，不进行重新加载
		    if(backup_longitude == obj.getPoint().getLongitude() && backup_latitude == obj.getPoint().getLatitude())
			  return false;
			
			//备份最新车辆点经纬度数据 
			backup_longitude = obj.getPoint().getLongitude();
		    backup_latitude = obj.getPoint().getLatitude();
		    
			info.setTitle(title);
			
			/**
			 * 如果上一次打开的信息浮窗不为空，则关闭它
			 */   
			if(info_old!=null){
				info_old.closeInfoWindow();
			}
			
			info_old = info; //将信息浮窗变量赋与info_old;
			info.setLabel("<div id='show_info_div'>正在载入....</div>") 
			$.ajax({
				type: "POST",
				url: window.parent.host+"/index.php?a=102&vehicle_id="+vehicle_id,
				dataType: "json",
				success: function(data){
					info.clear();//清除信息浮窗内容
					info.setLabel(get_data(data));
					info.moveToShow(); //如果信息浮窗超出屏幕范围，则移动到屏幕中显示 
				}
			}); 
			map.addOverLay(info);//添加新内容		
		}
		var vehicle_event =	LTEvent.addListener(obj,"click",shwoInfo);  
		vehicleEvent.push(vehicle_event); //添入事件队列中，重新加载时，在内存中清空历史事件
	} 
	
	/**
	 * 显示定位信息
	 * @param data 定位所有基本信息
	 * @return
	 */
	function get_data(data){
		if (data != null) {		 
			var vehicle_id = data['id']; //车辆id
			var gps_id = data['gps_id']; //GPS编号
			var location_time = data['location_time']; //当前定位时间 
			var cur_speed = data['cur_speed'];//当前速度
			var vehicle_group_name = data['group_name']; //车队
			var driver_name = data['driver_name']; //驾驶员
			var location_desc = data['location_desc']; //地址
			
			var context = 
					"<div class='content_div'><div class='title'>GPS编号：</div>" +
					"<div class='content'>"+gps_id + "</div></div>" +
					"<div class='content_div'><div class='title'>车队：</div>" +
					"<div class='content'>"+vehicle_group_name +"</div></div>" +
					"<div class='content_div'><div class='title'>驾驶员：</div>" +
					"<div class='content'>"+driver_name +"</div></div>"+
					"<div class='content_div'><div class='title'>速度：</div> " +
					"<div class='content'>"+cur_speed +"</div></div>" +
					"<div class='content_div'><div class='title'>定位时间：</div>" +
					"<div class='content'>"+location_time +"</div></div>" +
					"<div class='content_div'><div class='title'>地址：</div> " +
					"<div class='address_content'>"+location_desc +"</div></div></div>" +
					"<div class='oprate'><div class='send_info' url='index.php?a=201' showWidth=\"230\" showHeight=\"300\" title='发布信息' onclick='window.parent.showOperationDialog(this,\"index.php?a=201&vehicle_ids=" +
					vehicle_id +
					"\")'><a href='#'>发布信息</a></div>" +
									
					"<div class='statistics_info' url='index.php?a=402' showWidth=\"850\" showHeight=\"320\" title='车辆统计分析信息' onclick='window.parent.showOperationDialog(this,\"index.php?a=402&vehicle_id="+
					vehicle_id +
					"\")'><a href='#'>统计分析信息</a></div>" +
							
					"<div class='look_history' id='trace_ilook' url='index.php?a=201' showWidth=\"915\" showHeight=\"500\" title='查看历史轨迹' onclick='window.parent.showOperationDialog(this,\"index.php?a=352&logic=0&vehicle_id=" +
					vehicle_id +
					"\")'><a href='#'>查看历史轨迹</a></div>" +
							
					"<div class='real_time_monitor'><a href='javascript:refresh_vehicle_position("+vehicle_id+");save_cur_monitor_vehicles("+vehicle_id+")'>实时监控</a></div></div>";
			
			return context;
		}
	}
	/**
	 * 车辆刷新定位操作
	 * @param str 车辆ID (例:1,2,3,4)
	 * @return
	 */
	function refresh_vehicle_position(str){
			chanage_state=0; //非自动匹配
			refresh_state=1; 
			position_vehicle_state = 1; //车辆可定位状态
			
			refresh_vehicles = str;
			vehiclePosition(); 
	}
	
	/**
	 * 保存当前监控车辆
	 * @param vehicles 车辆ID (例:1,2,3,4)
	 * @return 
	 */
	function save_cur_monitor_vehicles(vehicles){
		//监控车辆状态时 可以默认选择上车辆 
		$("#vehicle_id_save",parent.document).val(refresh_vehicles);//将车辆信息保存在首页的隐藏域中
	}
	 
	 /**
	  * 车辆请求定位
	  * @param {Object} str 车辆ID集合 格式"ID1,ID2,ID3,"
	  */
	function vehiclePosition(){   
		  
		//不可直接定位验证
		if(position_vehicle_state == 0){
			if (!$("#location_refresh",parent.document).attr('checked') || refresh_state!=1)return false;
		}
		 
		//点击选择车辆时第一次，设置可直接定位,然后第二次不可直接定位 
		position_vehicle_state = 0;
		
		//保存当前监控车辆
		save_cur_monitor_vehicles(refresh_vehicles);
		 
		$.ajax({
				type:"POST",
				url:window.parent.host+"/index.php?a=2&vehicleIds="+refresh_vehicles, 
				dataType:"json",
				success:function(data){ 
					if(company_position_state==1){
						$.ajax({
							type:"get",
							url:window.parent.host+"/index.php?a=106",
							dataType:"json",
							success:function(positiones){
								for(var j = 0;j<positiones.length;j++){
									var lon = positiones[j][3];
									var lat = positiones[j][4];
									var name = positiones[j][2];
									
									var company_text = new LTMapText(new LTPoint(lon, lat));
									
									company_text.setLabel(name);
									map.addOverLay(company_text);
									
									overLay.push(company_text);//将标注点,添入将删除标注点队列中,定期清除
									//标注点定位
									company_position(lon,lat);
									
									//标注完公司，清空公司数组队列
									if(j==positiones.length-1) 
											clearArray(positiones);  
								}
							}
						});
					}
			 	 /**
				 	 * 获取当前地图矩形范围
				 	 **/
					var bound = map.getBoundsLatLng(); //矩形范围对象
					
					var xmin = bound.getXmin(); // 最小经度
					var ymin = bound.getYmin(); // 最小纬度
					var xmax = bound.getXmax(); // 最大经度
					var ymax = bound.getYmax(); // 最大纬度 
					
					var longitudeRange = xmax - xmin; // 矩形经度范围
					var latitudeRange = ymax - ymin;  // 矩形纬度范围
					  
					if(data == null || data =="")return false;
					
					var length = data.length;  // 数据长度
					var run_index  = length;  // 当前运行数据队列索引
					
					//当前数据存在时，清除所有
					if(length>0)clearAllMarker();
					 
					for(var i=0;i<length;i++){        
					 
						var vehicle_id = data[i][0]; //车辆id
						var point_longitude = data[i][1]; //点经度
						var point_latitude =  data[i][2]; //点纬度
						var file_path = data[i][4]; //文件目录
						var img_name = data[i][3];  //图片名称
						var number_plate = data[i][5]; //车牌号
						
						//循环
						//取得所有车的最大经度、最小经度、最大纬度、最小纬度
						longitudeArray[i] = point_longitude;
					 	latitudeArray[i] = point_latitude;
						  
						//存入点数据队列中
						points.push( new LTPoint(point_longitude,point_latitude));

					 	//创建点对象
						var marker =new LTMarker(new LTPoint(point_longitude,point_latitude),
										 	  new LTIcon(window.parent.host+"/"+file_path+"/"+img_name+".png"));
						//设置标题
						var title = "<span class='span'>"+number_plate+"</span>";
						
						addInfoWin(marker,title,vehicle_id);//点对象设置内容

						map.addOverLay(marker);//点添入地图中
						overLay.push(marker);//将标注点,添入将删除标注点队列中,定期清除
						
						//点显示内容
						var text = new LTMapText( new LTPoint(point_longitude,point_latitude ) );
						text.setLabel(number_plate ); 
						map.addOverLay( text ); 
						
						overLay.push(text);//将标注点,添入将删除标注点队列中,定期清除
						
						run_index --;
					}
					 
					/**
					 * 区域自动匹配用户查看设置
					 * 1 匹配
					 * 0 非匹配
					 */
					switch (chanage_state) {
						case 1: //匹配
						
							//取出所有车辆中的最大经纬度、最小经纬度。	
							var point_longitude_min = Math.min.apply(Math, longitudeArray);
							var point_longitude_max = Math.max.apply(Math, longitudeArray);
							var point_latitude_min = Math.min.apply(Math, latitudeArray);
							var point_latitude_max = Math.max.apply(Math, latitudeArray);
							
							//验证车辆当前位置是否超出范围
							var isOutofMapRange = (((point_longitude_min - xmin) / longitudeRange) <= leftOffsetRatio) ||
							(((xmax - point_longitude_max) / longitudeRange) <= rightOffsetRatio) ||
							(((point_latitude_min - ymin) / latitudeRange) <= downOffsetRatio) ||
							(((ymax - point_latitude_max) / latitudeRange) <= upOffsetRatio);
							
							if (isOutofMapRange) {//超出范围
								//重新获得最佳位置
								map.getBestMap(points);
								map.zoomTo(map.getCurrentZoom()==0?1:map.getCurrentZoom()); 
							}
							break;
						case 0:	//非匹配
							chanage_state = 1;
							map.getBestMap(points);
							map.zoomTo(map.getCurrentZoom()==0?1:map.getCurrentZoom()); 
							break;
					}	
					 
					clearArray(longitudeArray); //清空矩形经度队列数组
					clearArray(latitudeArray);  //清空矩形纬度队列数组
					clearArray(points);			//清空当前显示标注点集合
					
					wait_load_vehicle();//等待队列加载完
					
					//等待加载完车辆定位，运行下一步
					function wait_load_vehicle(){
						if(run_index==0){ 
							clearArray(data); 
							refresh_vehicle_info();
						 }else 
							 setTimeout(function(){
								 wait_load_vehicle();
							 },1000); 
					 }
				 } 
			 }); 
	 }
	 
	
	
	/**
	 * 手动画矩形
	 */
	function drawRectangle(){
		clearOverLay();
		//矩形地理区域的对象
		var drawBounds = control.getBoundsLatLng();

		var lonMin = drawBounds.getXmin();//最小经度
		var latMin = drawBounds.getYmin();//最小纬度
		var lonMax = drawBounds.getXmax();//最大经度
		var latMax = drawBounds.getYmax();//最大纬度

		//生成矩形地理区域
		var rectangleBounds =new LTBounds(lonMin,latMin,lonMax,latMax);

		//在地图画出矩形图 
		map.addOverLay(new LTRect(rectangleBounds));
	}
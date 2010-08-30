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
	var marker;   //地图标记对象
	/**
	 * 刷新状态
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
	

	onLoadMap();
	
	/**
	 * 加载地图
	 */
	function onLoadMap()
	{	
		//因为地图上的进度条可能会影响折线的事件触发，因此先禁止进度条的显示
		window._LT_map_disableProgressBar=true;	
		map=new LTMaps("map");
		
		//初始化车辆定位
		loadCompanyVehicle();
		
		var standControl = new LTStandMapControl();
		standControl.setTop(40);
		map.addControl(standControl);
	 
		/*添加拉框放大控件*/
		var ltControl = new LTZoomInControl();
		map.addControl( ltControl );
		ltControl.setRight(240);
		
		/*添加测距控件*/
		var PolyControl = new LTPolyLineControl();
		map.addControl( PolyControl );
		PolyControl.setTop( 10 ); 
		PolyControl.setRight(160);

		map.handleMouseScroll();
		//绑定事件注册
		LTEvent.addListener(map,"dblclick",onDblClick);
	} 

	
	/**
	 * 清除所有标点，重新加载新数据
	 */
	function clearOverLay(){
		map.clearOverLays(); 
	}
	/**
	 * 定位刷新操作
	 */
	$("#location_refresh",parent.document).click(function(){ 
		if($(this).attr('checked') ){ 
			refresh_vehicle_info();
		}
	}); 
	
	/**
	 * 初始化当前公司所有车辆定位信息
	 */
	 
	function loadCompanyVehicle(){
		if ($("#location_refresh",parent.document).attr('checked') && refresh_state===0) {
			$.ajax({
				type: "POST",
				url: window.parent.host+"/index.php?a=101",
				dataType: "json",
				success: function(data){
					if (data != null) {
						var length = data.length;
						
						if (length > 0) 
							clearOverLay();
						
						var points = new Array();
						for (var i = 0; i < length; i++) {
						
							var vehicle_id = data[i]['id']; //车辆id
							var number_plate = data[i]['number_plate']; //车牌号
							var point_longitude = data[i]['cur_longitude']; //当前经度
							var point_latitude = data[i]['cur_latitude']; //当前纬度 
							var img_name = data[i]['cur_direction']; //图片名
							var file_path = data[i]['file_path']; //文件路径

							//创建点对象
							marker = new LTMarker(new LTPoint(point_longitude, point_latitude), new LTIcon(window.parent.host + "/" + file_path + "/" + img_name + ".png"));
							
							points.push(new LTPoint(point_longitude, point_latitude));
							//点对象设置内容
							
							marker.openInfoWinElement("车牌号:"+number_plate);
							
							var title = "<span class='span'>"+number_plate+"</span>";
							addInfoWin(marker,title,vehicle_id);
							
							
							//点添入地图中
							map.addOverLay(marker);
							
							var text = new LTMapText(new LTPoint(point_longitude, point_latitude));
							text.setLabel(number_plate);
							map.addOverLay(text);
						}
						map.getBestMap(points);
					}
				}
			}); 
				refresh_vehicle_info();
			
		}	
	}
	
	/**
	 * 刷新公司车辆信息
	 * @return
	 */
	function refresh_vehicle_info(){
		if (!$("#location_refresh",parent.document).attr('checked') && refresh_state===2){
			alert("refresh_state: "+refresh_state);
			return false;
		}	
			 
		 
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
	 
	 
	/**
	 * 车辆提示信息
	 * @param {Object} obj  点对象
	 * @param vehicle_id 车辆ID
	 * @PARAM title 车牌号，用于标题显示
	 */
	function addInfoWin(obj,title,vehicle_id){ 
		
		var info = new LTInfoWindow( obj );
		//alert(refresh_state);
		var refresh_state_backup = refresh_state; //刷新操作状态备份

		function shwoInfo(){
			refresh_state = 2; //设置操作状态为不刷新
			info.setTitle(title);
			
			info.setLabel("<div id='show_info_div'>正在载入....</div>")
			map.addOverLay(info); 
			$.ajax({
				type: "POST",
				url: window.parent.host+"/index.php?a=102&vehicle_id="+vehicle_id,
				dataType: "json",
				success: function(data){
					refresh_state = refresh_state_backup;
					info.setLabel(get_data(data));					
				}
			});
			info.clear();
			map.addOverLay(info);			
		}
		LTEvent.addListener(obj,"click",shwoInfo); 
	} 
	
	
	
	/*显示定位信息*/
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
					
			"<div class='look_history' url='index.php?a=201' showWidth=\"900\" showHeight=\"400\" title='查看历史轨迹' onclick='window.parent.showOperationDialog(this,\"index.php?a=352&logic=0&vehicle_id=" +
			vehicle_id +
			"\")'><a href='#'>查看历史轨迹</a></div></div>";
			
			return context;
		}
	}
	
	function refresh_vehicle_position(str){
			
			refresh_state=1;
			refresh_vehicles = str;
			vehiclePosition(); 
	}
	 
	 /**
	  * 车辆请求定位
	  * @param {Object} str 车辆ID集合 格式"ID1,ID2,ID3,"
	  */
	function vehiclePosition(){ 
 
		 $.ajax({
				type:"POST",
				url:window.parent.host+"/index.php?a=2&vehicleIds="+refresh_vehicles, 
				dataType:"json",
				success:function(data){ 
				
					var length = data.length; 
					var points = new Array();
					 
					if(length>0)clearOverLay();

					for(var i=0;i<length;i++){        
					 
						var vehicle_id = data[i][0]; //车辆id
						var point_longitude = data[i][1]; //点经度
						var point_latitude =  data[i][2]; //点纬度
						var file_path = data[i][4]; //文件目录
						var img_name = data[i][3];  //图片名称
						var number_plate = data[i][5]; //车牌号

						points.push( new LTPoint(point_longitude,point_latitude));

					 	
						//创建点对象
						marker =new LTMarker(new LTPoint(point_longitude,point_latitude),
										 	  new LTIcon(window.parent.host+"/"+file_path+"/"+img_name+".png"));
						//设置标题
						var title = "<span class='span'>"+number_plate+"</span>";
						//点对象设置内容
						addInfoWin(marker,title,vehicle_id);

						//点添入地图中
						map.addOverLay(marker);

						var text = new LTMapText( new LTPoint(point_longitude,point_latitude ) );
						text.setLabel(number_plate ); 
						map.addOverLay( text ); 
					}
					map.getBestMap(points);
				 }
				});
		 
		 refresh_vehicle_info();
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
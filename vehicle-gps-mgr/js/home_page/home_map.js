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
	 
		var ltControl = new LTZoomInControl();
		map.addControl( ltControl );
		ltControl.setRight(320);
		
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
	$("#location_refresh",window.parent.document).click(function(){
		if($(this).attr('checked')){
			loadCompanyVehicle();
		}
	}); 
	
	/**
	 * 初始化当前公司所有车辆定位信息
	 */
	function loadCompanyVehicle(){
		if ($("#location_refresh",window.parent.document).attr('checked')) {
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
							var gps_id = data[i]['gps_id']; //GPS编号
							var location_time = data[i]['location_time']; //当前定位时间 
							var point_longitude = data[i]['cur_longitude']; //当前经度
							var point_latitude = data[i]['cur_latitude']; //当前纬度 
							var cur_speed = data[i]['cur_speed'];//当前速度
							var img_name = data[i]['cur_direction']; //图片名
							var vehicle_group_name = data[i]['group_name']; //车队
							var driver_name = data[i]['driver_name']; //驾驶员
							var file_path = data[i]['file_path']; //文件路径
							var location_desc = data[i]['location_desc']; //地址
							//创建点对象
							marker = new LTMarker(new LTPoint(point_longitude, point_latitude), new LTIcon(window.parent.host + "/" + file_path + "/" + img_name + ".png"));
							
							points.push(new LTPoint(point_longitude, point_latitude));
							//点对象设置内容
							var context = "车牌号：" + number_plate + "<br>GPS编号：" + gps_id +
							"<br>车队：" +
							vehicle_group_name +
							"<br>驾驶员：" +
							driver_name +
							"<br>速度: " +
							cur_speed +
							"<br>定位时间:" +
							location_time +
							"<br>地址: " +
							location_desc +
							"<br><br>" +
							"<div url='index.php?a=201' showWidth=\"230\" showHeight=\"300\" title='发布信息' onclick='window.parent.showOperationDialog(this,\"index.php?a=201&vehicle_id=" +
							vehicle_id +
							"\")'><a href='#'>发布信息</a></div>" +
							"&nbsp;&nbsp;&nbsp;<div url='index.php?a=201' showWidth=\"720\" showHeight=\"30\" title='查看历史轨迹' onclick='window.parent.showOperationDialog(this,\"index.php?a=352&vehicle_id=" +
							vehicle_id +
							"\")'><a href='#'>查看历史轨迹</a></div>";
							addInfoWin(marker, context);
							
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
			
			//自动刷新页面当前公司所有车辆最新定位信息。
			setTimeout(function(){
				//if (window.parent.state == 1) 
					loadCompanyVehicle();
			}, window.parent.page_refresh_time * 1000);
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
	 *  根据经纬度查看具体地址	
	 */
	function details(){
		var long = $("#more").attr("name");
		var lat = $("#more").attr("rel");
		$.get("index.php?a=503",{"longitude":long,"latitude":lat},function(data){
			$("#address").html(data);
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

						//点对象设置内容
						var context = "车牌号："+number_plate+"<br>GPS编号："+gps_id+
									  "<br>车队："+vehcile_group_name+
									  "<br>驾驶员："+driver_name+
									  "<br>速度: "+cur_speed+"<br>定位时间:"+location_time+
									  "<br>地址: "+location_desc+"<br><br>"+
									  "<a href='#'>发送信息</a>&nbsp;&nbsp;&nbsp;<a href='#'>历史轨迹</a>";
						//点对象设置内容
						addInfoWin(marker,context);

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
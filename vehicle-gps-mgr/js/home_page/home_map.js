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
	var page_refresh_time = (1000*60)*30; //地图页面刷新/30分钟
	
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
	
	var position_id = 0;//修改公司标注时保存的公司标注ID
	  
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
	var rightOffsetRatio = 0.05;  //矩形右间距
	var upOffsetRatio = 0.05;    //	矩形上间距
	var downOffsetRatio = 0.05;   //矩形下间距
	 
	
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
		
		//初始运行刷新操作
		init_run_refresh_operate();
	} 
	
	//公司标注点‘确定’事件 
	$("#commit",parent.document).click(function(){
		
		//获取公司名称
		var name = $("#name",parent.document).val();
		//关闭当前窗口
		parent.window.company_position_close();
		
		//公司标注
		getPoi(name);
	});
	/**
	 * 公司标注
	 * @param {Object} name 公司名称 
	 */
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
					 
					 //提示信息
					 alert(info[parseInt(data)]);
					 
					 if(data==1){ //成功
						 //创建标签点
						 var company_text = new LTMapText(new LTPoint(poi.getLongitude(), poi.getLatitude()));
						 
						 company_text.setLabel(name);  //添加标签
						 map.addOverLay(company_text); //添入地图中
						 
						 //公司标注点定位
						 company_position(poi.getLongitude(),poi.getLatitude());
						 
						 overLay.push(company_text);//将标签点,添入将删除标注点队列中,定期清除
						 company_text = null;//释放内存
					 }						  
				}
			});
	    }else{
	    	alert("请输入公司名");
	    }
	}
	
	//移动监听事件对象
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
	 * 气泡关闭事件处理任务 
	 */
	function LTInfoWindow_close(){
		backup_longitude = -1;
		backup_latitude = -1;
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
			var poi = obj.getPoint();
			var longitude= poi.getLongitude();
			var latitude = poi.getLatitude();

			$.get("/index.php?a=108&longitude="+longitude+"&latitude="+latitude,function(positions){
				var data = eval("("+positions+")");
				var contact = data[0];
				var zipcode = data[1];
				var tel = data[2];
				var fax = data[3];
				var mail = data[4];
				var url = data[5];
				var address = data[6];
				var id = data[7];
				position_id = id;
				
				info.setTitle("<div style='font-weight:700;font-size:12px;'>北京龙菲业</div>");
				info.setLabel( "<div><div class='lable'><div class='lable_title'>联系人：</div><div class='lable_content'>"+contact+"</div></div>" +
								"<div class='lable'><div class='lable_title'>邮编：</div><div class='lable_content'>"+zipcode+"</div></div>" +
								"<div class='lable'><div class='lable_title'>电话：</div><div class='lable_content'>"+tel+"</div></div>" +
								"<div class='lable'><div class='lable_title'>传真：</div><div class='lable_content'>"+fax+"</div></div>" +
								"<div class='lable'><div class='lable_title'>邮箱：</div><div class='lable_content'>"+mail+"</div></div>" +
								"<div class='lable'><div class='lable_title'>网址：</div><div class='lable_content'>"+url+"</div></div>" +
								"<div class='lable'><div class='lable_title'>地址：</div><div class='lable_content'>"+address+"</div></div>" +
								"<div class='lable'><div class='lable_title'>编辑：</div><div class='lable_content'><a href='javascript:modify_position("+id+")'>修改</a></div></div>" +
								"<div class='lable'><div class='lable_title'>删除：</div><div class='lable_content'><a href='javascript:delete_position("+id+")'>删除</a></div></div>" +
								"</div> " ); 
				
				map.addOverLay( info );
				info.moveToShow(); //如果信息浮窗超出屏幕范围，则移动到屏幕中显示 
			});
		}
		//标注点添加点击事件 
		var clickEvent = LTEvent.addListener(obj,"click",show_maker_info);
		vehicleEvent.push(clickEvent); //添入事件队列中，重新加载时，在内存中清空历史事件
	}
	
	$("#update_commit",parent.document).click(function(){
		
		//获取公司名称
		var name = $("#update_name",parent.document).val();
		//关闭当前窗口
		parent.window.update_position_close();
		//公司标注
		update_position(name,position_id);
	});
	
	/**
	 * 修改公司标注
	 * @return
	 */
	function modify_position(){
		parent.window.update_position_show();
	}
	
	function update_position(name,id){
		$.get("/index.php?a=109&position_id="+id+"&name="+encodeURI(name),function(data){
			if("ok"==data){
				alert("修改成功");				
				refresh_map_page();//刷新页面
			}else{
				alert("修改失败");
			}
		});		
	}
	
	//删除公司标注
	function delete_position(id){
		$.get("/index.php?a=107&position_id="+id,function(data){
			if("ok"==data){
				alert("删除成功");
				refresh_map_page();//刷新页面
			}else{
				alert("删除失败");
			}
		});
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
			refresh_map_page();
		}
	}); 
 
	/**
	 * 清空所有标注点，并将内存中标注点清空
	 * */
	function clearAllMarker(){
		
		//清空标注点
		var length = overLay.length;
		for(var i=0;i<length;i++){
			map.removeOverLay(overLay[0],true);
			overLay.shift();
		}	
		
		//清空标注点事件 
		var vehicleEventLength = vehicleEvent.length;
		for(var i=0;i<vehicleEventLength;i++){ 
			 LTEvent.removeListener(vehicleEvent[0]);
			 vehicleEvent.shift();
		} 
	} 
	
	/**
	 * 清空指定标注点
	 * @param obj_arr 标注点数组
	 * @return 
	 */
	function clearAssignMarker(obj_arr){
		
		var length = obj_arr.length;
		for(var i=0;i<length;i++){
			map.removeOverLay(obj_arr[0],true);
			obj_arr.shift();
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
	 * 刷新公司车辆信息
	 * @return
	 */
	function refresh_vehicle_info(){  
		if (!$("#location_refresh",parent.document).attr('checked') || refresh_state==2)  return false;
		init_state();//设置地图初始状态 
		
		switch(parseInt(refresh_state)){
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
	
	/**
	 * 初始运行刷新操作
	 * @return
	 */
	function init_run_refresh_operate(){
		 
		//获取当前刷新页面状态值
		var  cur_refresh_state = $("#cur_refresh_state",parent.document).val();
		
		//判断当前刷新页面已开始状态
		if(cur_refresh_state == "start"){
			
			//定时刷新未勾选上
			if (!$("#location_refresh",parent.document).attr('checked'))return false;
			
			//获取经纬度、比例集合
			var cur_longlat = $("#cur_longlat",parent.document).val();
			var cur_longlat_arr = cur_longlat.split("|");//经纬度、比例数组
			
			var cur_longitude = cur_longlat_arr[0];//经度
			var cur_latitude = cur_longlat_arr[1];//纬度
			var cur_zoom = cur_longlat_arr[2];//比例
			
			//定位车辆点
			var ltPoint = new LTPoint(cur_longitude,cur_latitude);
			map.centerAndZoom(ltPoint,cur_zoom);
			
			//获取当前所有状态
			var cur_states = $("#cur_states",parent.document).val();
			var cur_states_arr = cur_states.split("|"); //状态数组
			
			position_vehicle_state = cur_states_arr[0]; //定位状态
			refresh_state = cur_states_arr[1]; //刷新状态
			
			//获取监控车辆
			refresh_vehicles = $("#refresh_vehicles",parent.document).val();
			
			switch(parseInt(refresh_state)){
				case 0:    //'0'代表刷新所有车辆  
					  loadCompanyVehicle();
					break;
				case 1:  //‘1’代表刷新选择监控车辆  
					  vehiclePosition(); 
					break; 
			} 
			
			ltPoint = null;//内存释放点对象
		}else{
			//初始化车辆定位
			loadCompanyVehicle();
		}
		
		//地图页面刷新
		setTimeout("refresh_map_page();",page_refresh_time);
	}
	
	/**
	 * 刷新地图页面
	 * @return
	 */
	function refresh_map_page(){
		//定时刷新未勾选上
		if (!$("#location_refresh",parent.document).attr('checked'))return false;
		
		//获取当前地图经纬度、比例相关信息
		var centerPoint = map.getCenterPoint();
		
		//信息保存为：经度|纬度|当前比例
		$("#cur_longlat",parent.document).val(centerPoint.getLongitude()+"|"+centerPoint.getLatitude()+"|"+map.getCurrentZoom());
		//当前状态保存为：定位状态｜刷新状态
		$("#cur_states",parent.document).val(position_vehicle_state+"|"+refresh_state);
		//当前刷新车辆：当前刷新车辆集合
		$("#refresh_vehicles",parent.document).val(refresh_vehicles);
		//设置刷新地图页面为‘开始’状态
		$("#cur_refresh_state",parent.document).val('start');
		
		window.location.reload();
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
		  
		//获取车辆定位数据
		get_vehicle_location_data(0);   
	} 
	/**
	  * 车辆请求定位 
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
		
		//获取车辆定位数据
		get_vehicle_location_data(1);     
	 }
   /**
	 * 获取车辆定位数据
	 * @param {Object} operate 操作类型
	 */ 
	function get_vehicle_location_data(operate){
		
		var request_param = null;//请求参数
		switch(parseInt(operate)){
			case 0: //获取公司所有车辆
			  	request_param ="?a=101";
			  break;
			case 1: //获取指定的公司车辆
			    request_param = "?a=2&vehicleIds="+refresh_vehicles;
			  break;	
		} 
		//请求车辆定位数据
		$.ajax({
			type: "POST",
			url: window.parent.host+"/index.php"+request_param,
			dataType: "json",
			success: function(data){ 
				if (data != null) { 
					var length = data.length;
					var run_index = length;
					  
					if (length > 0) 
						clearAllMarker(); //清空所有标注点
					 
					//显示所有车辆定位
					run_index = show_all_vehicle_location(data,length,run_index);  
					
					//车辆范围定位
					range_location(longitudeArray,latitudeArray,points);
					
					//标注公司所有定位点	
					company_all_location(); 
					
					clearArray(longitudeArray); //清空矩形经度队列数组
					clearArray(latitudeArray);  //清空矩形纬度队列数组
					clearArray(points);			//清空当前显示标注点集合
										
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

					//等待队列加载完
					wait_load_vehicle();
				}
			}
		});
	}
	 /**
	  * 显示数据队列所有车辆定位
	  * @param {Object} data 所有车辆JSON集合对象
	  * @param {Object} length 所有车辆数量
	  * @param {Object} run_index 运行下标
	  * @return 返回当前运行集合下标
	  */
	 function show_all_vehicle_location(data,length,run_index){
	 	//循环获取加载车辆的基本
		for (var i = 0; i < length; i++) {
			
			var vehicle_id=-1,number_plate=null,point_longitude=-1,point_latitude=-1,alert_state=-1,img_name=null,file_path=null;
			
			 vehicle_id = data[0]['id']; //车辆id
			 number_plate = data[0]['number_plate']; //车牌号
			 point_longitude = data[0]['cur_longitude']; //当前经度
			 point_latitude = data[0]['cur_latitude']; //当前纬度 
			 alert_state = data[0]['alert_state'];// 告警状态
			 img_name = data[0]['cur_direction']; //图片名
			 file_path = data[0]['file_path']; //文件路径 
			 
			 xMin = data[0]['xMin'];
			 yMin = data[0]['yMin'];
			 xMax = data[0]['xMax'];
			 yMax = data[0]['yMax'];
			
			 data.shift();//移除已使用下标
			  
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
			
			overLay.push(marker);//将车辆点,添入将删除标注点队列中,定期清除
			
			//车辆点添加标签
			var text = new LTMapText(new LTPoint(point_longitude, point_latitude));
			
			var labelText = "";
			var backgroundColor = null;
			switch(parseInt(alert_state)){//当前车辆状态
				case 0: //正常状态
					labelText = number_plate+" 正常";
				 break; 
				case 1: //超速状态
					labelText = number_plate+" 超速";
				 	backgroundColor = "red";//更改文字标签背景色  
				 break;
			  default:  //
			  		labelText = number_plate+" 疲劳";
			  	 	text.setBackgroundColor("yellow");//更改文字标签背景色
			     break;	
			}
			
			if(xMin != "" && (point_longitude < xMin || point_longitude > xMax || point_latitude < yMin || point_latitude > yMax)){
				labelText += " 超出范围";
			}else{
				labelText += " 范围内";
			}
			
			//设置车辆点标签属性
			text.setLabel(labelText);
			text.setBackgroundColor(backgroundColor);
			 
			map.addOverLay(text);//车辆点添入地图中
			overLay.push(text);//将车辆点,添入将删除标注点队列中,定期清除
			
			//内存释放对象	
			text = null; 
			ltPoint = null;
			ltIcon = null;
			marker = null;
			 
			run_index--;
		}
		
		return run_index;
	 }
	 
	
	 /**
	  * 车辆范围定位
	  * @param {Object} longitudeArray 车辆经度点数组
	  * @param {Object} latitudeArray  车辆纬度点数组
	  * @param {Object} points 车辆点数组
	  */
	 function range_location(longitudeArray,latitudeArray,points){ 
			 	 
				/**
				 * 区域自动匹配用户查看设置
				 * 1 匹配
				 * 0 非匹配
				 */
				switch (parseInt(chanage_state)) {
					case 1: //匹配
					
				//获取当前地图矩形范围
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
					case 0:	//非匹配
						chanage_state = 1;
						map.getBestMap(points);
						map.zoomTo(map.getCurrentZoom()==0?1:map.getCurrentZoom()); 
						break;
				}	
	 }
	 
	/**
	 * 公司所有标注定位
	 */
	function company_all_location(){
		
		$.ajax({
				type:"get",
				url:window.parent.host+"/index.php?a=106",
				dataType:"json",
				success:function(positiones){
					if(positiones == null) return;
				 
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
			var gps_number = data['gps_number']; //GPS编号
			var location_time = data['location_time']; //当前定位时间 
			var cur_speed = data['cur_speed'];//当前速度
			var vehicle_group_name = data['group_name']; //车队
			var driver_name = data['driver_name']; //驾驶员
			var location_desc = data['location_desc']; //地址
			
			var context = 
					"<div class='content_div'><div class='title'>GPS编号：</div>" +
					"<div class='content'>"+gps_number + "</div></div>" +
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
					"<div class='oprate'><div class='send_info' url='index.php?a=201' showWidth=\"300\" showHeight=\"350\" title='发布信息' onclick='window.parent.showOperationDialog(this,\"index.php?a=201&hidden=1&vehicle_ids=" +
					vehicle_id +
					"\")'><a href='#'>发布信息</a></div>" +
									
					"<div class='statistics_info' url='index.php?a=402' showWidth=\"850\" showHeight=\"320\" title='车辆统计分析信息' onclick='window.parent.showOperationDialog(this,\"index.php?a=402&vehicle_id="+
					vehicle_id +
					"\")'><a href='#'>统计分析信息</a></div>" +
							
					"<div class='look_history' id='trace_ilook' url='index.php?a=201' showWidth=\"915\" showHeight=\"500\" title='查看历史轨迹' onclick='window.parent.showOperationDialog(this,\"index.php?a=352&vehicle_id=" +
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
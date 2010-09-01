	//子页面对象   iframe
	//var history_map =  document.getElementById("history_track_frame").contentDocument;


	var step_info = 0;

$(document).ready(function(){
	
	position(); //加载车辆最新定位
	run_play(3);//正常速度
	
	//初始始进度条
	$("#history_progress").progressbar({
			value:0
	});
	//定位向导信息
	$("#location_info").draggable({containment:'#drag'});

	//播放时间调整				
	$("#cur_run_time").slider({ 
		min : 1,
		max : 5,
		values : [3],
		animate:true,
		slide: function(event, ui) {
		run_play(ui.value); 
		}
	});

	$("#his_info").hide();
	$("#show_vehicles").hide();
	$("#areas").hide();
	$("#history_trace").attr("checked","checked");

	var myDate = new Date();
	$("#inquire_startTime").val(getTodayFormatDate()); //开始时间赋默认值
	$("#inquire_endTime").val(getNowFormatDate()); //结束时间赋默认值

	//暂停
	$("#suspend_history").click(function(){
		$("#location_info").show();
		$("#suspend_history").hide();
		$("#play_history").show();
		
		history_track_frame.state="suspend"; 
	});

	//停止 
	$("#stop_history").click(function(){
		
		if(history_track_frame.state === "search"){
			alert("正在查询，不能停止!");
		}else{
			empty_cur_vhicle_history();
			$("#play_history").show();
			$("#suspend_history").hide();

			history_track_frame.state="stop";
		}	
	});
 
	//播放
	$("#play_history").click(function(){ 

		 var state = document.history_track_frame.document.readyState;

		 if(state === "complete"){
			$("#location_info").show();
			$("#play_history").hide();
			$("#suspend_history").show();
			$("#location_info").show(); //显示定位信息
			 
			if(history_track_frame.state === "suspend"){
				history_track_frame.state = "normal";
			}
			
			progress_assignment(1);
			play_trace();	
		 }else
			 alert("地图未加载完，请等待地图加载完之后，点击操作！");
		
	});

	//选择车辆查询和区域查询切换
	$("#select_mode").change(function(){
		
		$("#show_area").hide();
		$("#location_info").hide();

		if("area_mode"==$("#select_mode option:selected").val()){
			$("#vehicle_info").hide();
			$("#areas").show();
			$("#show_vehicles").show();
			$("#play_history").hide();
			$("#speed_control").hide();
			$("#suspend_history").hide();
			$("#stop_history").hide();

			$("#mode_area").attr("src","inquire/templates/51ditu.html"); //区域查询时加载地图
		}else{
			$("#vehicle_info").show();
			$("#areas").hide();
			$("#show_vehicles").hide();
			$("#play_history").show();
			$("#speed_control").show();
			$("#suspend_history").show();
			$("#stop_history").show();
			}
		});

	//历史发布信息查询
	$("#history_post").click(function(){
		$("#show_area").hide();
		$("#vehicle_info").hide();
		$("#info_select_vehicle").hide();
		$("#play_history").hide();
		$("#speed_control").hide();
		$("#suspend_history").hide();
		$("#stop_history").hide();
		$("#areas").hide();
		$("#show_vehicles").hide();
		$("#location_info").hide();
		$("#his_info").show();
		$("#his_infoes").show();
		$("#inquireing").hide();
	});

	//历史轨迹查询
	$("#history_trace").click(function(){
		$("#location_info").hide();
		$("#show_area").hide();
		$("#his_info").hide();
		$("#his_infoes").hide();
		$("#info_select_vehicle").show();
		$("#vehicle_info").show();
		$("#play_history").show();
		$("#speed_control").show();
		$("#suspend_history").show();
		$("#stop_history").show();
		$("#areas").hide();
		$("#inquireing").show();
		$("#select_mode").get(0).selectedIndex=0; //点击查询历史轨迹时查询默认切换成选择车辆
	});

	//定位信息窗口操作
	$("#dragger").click(function(){
		$("#location_info .location_info").toggle(); //切换展开与隐藏状态

		if($("#location_info .location_info").is(":hidden")){
			$("#dragger").html("[展开]");
		 }else {
			$("#dragger").html("[隐藏]");
		 }
		});

	//查询显示历史发布信息
	$("#his_info").click(function(){
		
		var startTime = $("#inquire_startTime").attr("value"); //获取开始时间
		 
		if(startTime == ""){
			alert("开始时间不能为空!");
			$("#inquire_startTime").focus();
			return false;
		}
		var endTime = $("#inquire_endTime").attr("value");
		if(endTime == ""){
			alert("结束时间不能为空!");
			$("#inquire_endTime").focus();
			return false;
		}

		
		if(step_info==0){
		jQuery("#infoes").jqGrid({
			url:'index.php?a=354&begin_date='+$("#inquire_startTime").val()
				+'&end_date='+$("#inquire_endTime").val(),
			  datatype: "json",
		   	colNames:['ID','发布人', '类型', '发布时间','生效时间','失效时间','信息内容'],
		   	colModel:[
		   		{name:'id',index:'id', width:55,editable:false,hidden:true,editoptions:
																{readonly:true,size:10}},
		   		{name:'issuer_id',index:'issuer_id', width:80},
		   		{name:'type',index:'type', width:70},
		   		{name:'issue_time',index:'issue_time', width:140, align:"left"},   
		   		{name:'begin_time',index:'begin_time', width:140, align:"left"},
		   		{name:'end_time',index:'end_time', width:140, align:"left"},
		   		{name:'content',index:'content', width:900, align:"left"}
		   		
		   	],
		   	rowNum:10,
		   	rowList:[10,20,30],
		   	pager: '#pagernav_infoes',
		   	sortname: 'id',
		    viewrecords: true,
		    sortorder: "desc",
			height:290
		});

			jQuery("#infoes").jqGrid('navGrid','#pagernav_infoes',
			{edit:false,add:false,del:false});
			step_info++;
		}else{
			var url = 'index.php?a=354&begin_date='+$("#inquire_startTime").val()
						+'&end_date='+$("#inquire_endTime").val();
			jQuery("#infoes").jqGrid('setGridParam',{url:url}).trigger("reloadGrid");
			}
	
	});

	
	$("#inquire_startTime").datetimepicker({
		 ampm: false,//上午下午是否显示  
		 timeFormat: 'hh:mm:ss',//时间模式  
		 stepHour: 1,//拖动时间时的间隔  
		 dateFormat:"yy/mm/dd", //日期格式设定  
		 showHour: true,//是否显示小时，默认是true  
		 showMinute:false,
		 showSecond:false,
		 createButton:false
	 });	
     
	
	$("#inquire_endTime").datetimepicker({
		 ampm: false,//上午下午是否显示  
		 timeFormat: 'hh:mm:ss',//时间模式  
		 stepHour: 1,//拖动时间时的间隔  
		 dateFormat:"yy/mm/dd", //日期格式设定  
		 showHour: true,//是否显示小时，默认是true  
		 showMinute:false,
		 showSecond:false,
		 createButton:false
	 });

	 //区域查询车辆历史轨迹
	$("#show_vehicles").click(function(){
		$("#areas").mask("查询中,请稍侯...");

		/*获取经纬度范围*/
		var lonMin = document.getElementById("mode_area").contentWindow.document.getElementById("lonMin").value;
		var latMin = document.getElementById("mode_area").contentWindow.document.getElementById("latMin").value;
		var lonMax = document.getElementById("mode_area").contentWindow.document.getElementById("lonMax").value;
		var latMax = document.getElementById("mode_area").contentWindow.document.getElementById("latMax").value;

		var begin_time = $("#inquire_startTime").attr("value"); //获取开始时间
		var end_time = $("#inquire_endTime").attr("value"); //获取结束时间

		var idListStr = "";
		$("#vehicle_info > option").each(function(){
			if(idListStr != ""){
				idListStr += "-";
			}
			idListStr += $(this).val();
		});

			var hourList = getHourList();
		
		var hourListStr = "";
		for(i=0;i<hourList.length;i++){
			if(hourListStr != ""){
				hourListStr += "-";
			}

			hourListStr += hourList[i];
		}

 
		$.post("index.php",{"a":355,"hour_list":hourListStr,"lonMin":lonMin,"latMin":latMin,
			"lonMax":lonMax,"latMax":latMax,"vehicle_list":idListStr},
			function(data){
				$("#areas").unmask();
				$("#areas").hide();
				$("#his_infoes").hide();
				$("#inquireing").hide();
				$("#show_area").show();
				var idList = "";
				if(data != null){
					for(i=0;i<data.length;i++){
						idList += "-";
						idList += data[i];
					}
				}
				
				jQuery("#show_vehicles_page").jqGrid({
					url:'index.php?a=356&begin_time='+begin_time
						+'&end_time='+end_time+'&id_list='+data,
					  datatype: "json",
				   	colNames:['ID','车牌号','驾驶员','历史轨迹'],
				   	colModel:[
				   		{name:'id',index:'id', width:255,editable:false,hidden:true,editoptions:
																		{readonly:true,size:10}},
				   		{name:'number_plate',index:'number_plate', width:190},
				   		{name:'driver',index:'driver', width:190},
				   		{name:'trace',index:'trace', width:190}
				   		
				   	],
				   	rowNum:10,
				   	rowList:[10,20,30],
				   	pager: '#page_show',
				   	sortname: 'id',
				    viewrecords: true,
				    sortorder: "desc",
					height:290
				});

					jQuery("#show_vehicles").jqGrid('navGrid','#page_show',
					{edit:false,add:false,del:false});
								
			}
			,"json");			
	});
	
	
}); 
	/**
	 * 进度条赋值
	 * @progress_val 值
	 */
	function progress_assignment(progress_val){ 
		$( "#history_progress" ).progressbar( "option", "value", progress_val );
	} 
	
	/**
	 * 开始加载首先加载车辆的最新定位，判断IFRAME是否加载完
	 */
	function position(){
		 var state = document.history_track_frame.document.readyState;
		 if(state=="complete"){
			{{POSITION_VEHICLE}}
		 }else{
			setTimeout("position();",1000);
		 }	 
	} 
    
	
	//播放运行速度控制
	function run_play(value){
		
		var speed_explain = new Array();
		var play_speed = new Array();
		
		speed_explain[1] = "慢放2";
		speed_explain[2] = "慢放1";
		speed_explain[3] = "正常";
		speed_explain[4] = "快放1";
		speed_explain[5] = "快放2";

		play_speed[1] = 4000;
		play_speed[2] = 2000;
		play_speed[3] = 1000;
		play_speed[4] = 500;
		play_speed[5] = 250;
         
		$("#show_time").html(speed_explain[value]);
		 history_track_frame.speed = play_speed[value];
		  
	}	
	 
    if($("#vehicle_info option:selected").attr("name")=="have"){
			$("#his").hide();
    }
    //清空当前运行历史轨迹
	function empty_cur_vhicle_history(){
		
		history_track_frame.state = "normal"; 
		history_track_frame.vehicle_id = -1;
		history_track_frame.old_longitude = -1;
		history_track_frame.old_latitude = -1;
		history_track_frame.cur_progress = 0;
		history_track_frame.progress_length=0;
		history_track_frame.arr_history = null;
		history_track_frame.drawLine_arr = null;

		$("#direction").html(" ");
		$("#speed").html(" ");
		$("#longitude").html(" ");
		$("#latitude").html(" ");
		$("#location_time").html(" ");
		$("#address").html(" ");
	}
	
	
	 /**
	  * 轨迹播放函数
	  */
	  function play_trace(){
			//开始时间
		  	var startTime = $("#inquire_startTime").attr("value");
	   		if(startTime == ""){
				alert("开始时间不能为空!");
				$("#startTime").focus();
				return false;
			}
			//结束时间
			var endTime = $("#inquire_endTime").attr("value");
			if(endTime == ""){
				alert("结束时间不能为空!");
				$("#endTime").focus();
				return false;
			}
			//获取车辆编号
			var history_vehicle_id = $("#vehicle_info option:selected").val();
			     
			//当state为 stop时，点击播放为允许重新加载状态 
			if(history_track_frame.state === "stop"){  
					//设置state正常操作状态
					history_track_frame.state == "normal";

					//清空车辆历史轨迹所有遗留数据，重新加载新轨迹数据
					empty_cur_vhicle_history();

					//设置时间队列数组
					history_track_frame.arr_history = getHourList();   
					//设置时间队列数组长度
					history_track_frame.progress_length = getHourList().length;
					//设置所选车辆编号
					history_track_frame.vehicle_id = history_vehicle_id;
			
					//获取车牌号、GPS编号
					$.get("index.php?a=504&vehicle_id="+history_track_frame.vehicle_id,function(data){ 
			 			 var gps_plate = eval('(' + data + ')');  
						 $("#vehicle_id").html(gps_plate[0]);
						 $("#gps_id").html(gps_plate[1]);
						});
			
					 //清除地图所有标签
					 history_track_frame.clearOverLay();
					 //设置正常运行速度
					 //history_track_frame.speed = 1000;
			}	 
			 //运行历史轨迹
			 history_track_frame.runHistoryTrack(); 
		  }

	
	
	//获取系统当前时间并格式化为yyyy/mm/dd hh:mm:ss
	function getNowFormatDate()
	{
	   	var day = new Date();
	
	   	var Year = 0;
	   	var Month = 0;
	   	var Day = 0;
		var CurrentDate = "";
	   //初始化时间
	   	Year= day.getFullYear();
	   	Month= day.getMonth()+1;
	   	Day = day.getDate();
	   	Hour = day.getHours();
	 	Minute = day.getMinutes();
		Second = day.getSeconds();
	   	CurrentDate += Year + "/";
	   	if (Month >= 10){
	    	CurrentDate += Month + "/";
	   	}else{
	    	CurrentDate += "0" + Month + "/";
	   	}
	   	if (Day >= 10 ){
	    	CurrentDate += Day +" ";
	   	}else{
	    	CurrentDate += "0" + Day+" " ;
	   	}
	   	if(Hour>=10){
			CurrentDate+=Hour+":";
		}else{
			CurrentDate+="0"+Hour+":";
	   	}if (Minute >= 10 ){
	    	CurrentDate += Minute +":";
	   	}else{
	    	CurrentDate += "0" + Minute+":" ;
	   	}
	   	if(Second>=10){
			CurrentDate+=Second;
		}else{
			CurrentDate+="0"+Second;
	   	}
	   	return CurrentDate;
	}
	
	//获取系统当天零点时间并格式化为yyyy/mm/dd hh:mm:ss
	function getTodayFormatDate()
	{
	   	var day = new Date();
	
	   	var Year = 0;
	   	var Month = 0;
	   	var Day = 0;
		var CurrentDate = "";
	   //初始化时间
	   	Year= day.getFullYear();
	   	Month= day.getMonth()+1;
	   	Day = day.getDate();
	   	CurrentDate += Year + "/";
	   	if (Month >= 10){
	    	CurrentDate += Month + "/";
	   	}else{
	    	CurrentDate += "0" + Month + "/";
	   	}
	   	if (Day >= 10 ){
	    	CurrentDate += Day +" ";
	   	}else{
	    	CurrentDate += "0" + Day+" " ;
	   	}
	   	CurrentDate+="00:00:00";
	   	return CurrentDate;
	}
   
	//得到起始时间和结束时间之间的小时列表，以便于分段向服务器请求位置信息
	function getHourList(){
		var startTime = $("#inquire_startTime").attr("value");
		var endTime = $("#inquire_endTime").attr("value");

		var startdt0 = new Date(Date.parse(startTime));
		var enddt0 = new Date(Date.parse(endTime));

		startdt = startdt0.getTime();
		enddt = enddt0.getTime();

		var hourList = new Array();
		
		while(startdt <  enddt){
			var date = new Date();
			date.setTime(startdt);

			var hourStr = date.getFullYear() + "" + padLeft("" + (date.getMonth()+1),2) + "" + padLeft("" + date.getDate(),2) + "" + padLeft("" + date.getHours(),2);
			hourList.push(hourStr);
			
			startdt += 60*60*1000; //起始时间增加1小时
			//startdt += 30*60*1000; //起始时间增加半小时
		}
		
		return hourList;
	}

	//字符串左侧补零函数
	function padLeft(str,lenght){ 
		if(str.length >= lenght){
			return str;
		}
		else{ 
			return padLeft("0" +str,lenght); 
		}
	} 

	//位置信息类
	function Position(){
		var longitude;
		var latitude;
	}

	//区域信息类
	function AreaInfo(){
		var positionList;
		var radius = 0;
		var type = 0; //0-矩形 1-圆形 2-多边形
	}
 	
	function show_trace_area(vehicle_id){
		$("#show_area").hide();
		$("#show_vehicles").hide();
		$("#speed_control").show();
		$("#play_history").show();
		$("#suspend_history").show();
		$("#stop_history").show();
		$("#vehicle_info").show();
		$("#inquireing").show();
		$("#vehicle_info").val(vehicle_id);
		play_trace();
	}

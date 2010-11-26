	var is_vehicle_in_area_list_showed = false; //在查询指定时间经过指定区域车辆的时候，确认车辆列表是否已经产生过。

$(document).ready(function(){
	//是否要从父窗口继承设置值
	var isInherit = $("#inherit").attr("value");
	if(isInherit){
		$('#his').hide();
		$('#info_select_vehicle').hide();
		$("#vehicle_info").hide();
	}
	
	position(); //加载车辆最新定位
	run_play(3);//正常速度
	
	$(":button").button();
	$("#select_city").button();
	
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
	
	var myDate = new Date();
	if(isInherit){
		var startTime = $(window.parent.document).find("#inquire_startTime").attr("value");
		var endTime =$(window.parent.document).find("#inquire_endTime").attr("value");
		$("#inquire_startTime").val(startTime);
		$("#inquire_endTime").val(endTime);
	}else{
		$("#inquire_startTime").val(getTodayFormatDate()); //开始时间赋默认值
		$("#inquire_endTime").val(getNowFormatDate()); //结束时间赋默认值
	}
	
	$('#stop_history').button({
		text: false,
		icons: {
			primary: 'ui-icon-stop'
		}
	})
	.click(function() {
		$('#play_history').button('option', {
			label: '播放',
			icons: {
				primary: 'ui-icon-play'
			}
		});
		history_track_frame.cancle_mask();
		history_track_frame.end_history_line();
	});

	$('#play_history').button({
		text: false,
		icons: {
			primary: 'ui-icon-play'
		}
	})
	.click(function() {
		var options;
		var state = document.history_track_frame.document.readyState;
		if ($(this).text() == '播放') {
			options = {
				label: '暂停',
				icons: {
					primary: 'ui-icon-pause'
				}
			};
			 if(state === "complete"){
					$("#inquireing").show();
					$("#location_info").show(); //显示定位信息
					 
					if(history_track_frame.state === "suspend"){
						history_track_frame.state = "normal";
					}
					
					progress_assignment(1);
					play_trace();	
				 }else
					 alert("地图未加载完，请等待地图加载完之后，点击操作！");
		} else {
			options = {
				label: '播放',
				icons: {
					primary: 'ui-icon-play'
				}
			};
			$("#location_info").show();
			
			history_track_frame.state="suspend"; 
		}
		$(this).button('option', options);
	});

	$("#vehicle_info").change(function(){
		history_track_frame.cancle_mask();
		history_track_frame.end_history_line();
		$("#location_info").hide();
		$('#play_history').button('option', {
			label: '播放',
			icons: {
				primary: 'ui-icon-play'
			}
		});
	});
	
	//定位信息窗口操作
	$("#dragger").click(function(){
		$("#location_info .location_info_content").toggle(); //切换展开与隐藏状态

		if($("#location_info .location_info_content").is(":hidden")){
			$("#dragger").html("[展开]");
		 }else {
			$("#dragger").html("[隐藏]");
		 }
		});
}); //$(document).ready

	/**
	 * 进度条赋值
	 * @progress_val 值
	 */
	function progress_assignment(progress_val){ 
		$( "#history_progress" ).progressbar( "option", "value", progress_val );
	} 
    
	
	//播放运行速度控制
	function run_play(value){
		
		var speed_explain = "快";
		var play_speed = new Array();
		
		//初始化播放速度值
		play_speed[1] = 4000;
		play_speed[2] = 2000;
		play_speed[3] = 1000;
		play_speed[4] = 500;
		play_speed[5] = 250;
         
		$("#show_time").html(speed_explain);
		 history_track_frame.speed = play_speed[value];
		  
	}	
	 
    if($("#vehicle_info option:selected").attr("name")=="have"){
			$("#his").hide();
    }
    //清空当前运行历史轨迹
	function empty_cur_vhicle_history(){
		$('#play_history').button('option', {
			label: '播放',
			icons: {
				primary: 'ui-icon-play'
			}
		});
		/**
		 * 初始加载状态
		 */
		history_track_frame.state = "normal";   //初始当前操作为'正常'状态
		history_track_frame.vehicle_id = -1;    //初始车辆未选择状态
		history_track_frame.old_longitude = -1; //初始车辆经度未设置状态
		history_track_frame.old_latitude = -1;  //初始车辆纬度未设置状态
		history_track_frame.cur_progress = 0;	//初始当前进度值
		history_track_frame.progress_length=0;  //初始当前进度长度
		arr_history = null; //初始历史时间数组为空
		drawLine_arr = null;//初始画线数组为空

		$("#direction").html(" ");     //定位信息方向清空
		$("#speed").html(" ");		   //定位信息速度清空
		$("#longitude").html(" ");	   //定位信息经度清空
		$("#latitude").html(" ");	   //定位信息纬度清空
		$("#location_time").html(" "); //定位信息当前定位时间清空
		$("#address").html(" ");	   //定位信息位置文字信息清空
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
				arr_history = getHourList();   
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
				//设置当前操作为’正常状态‘
				history_track_frame.state = "normal"; 
				//清除地图所有标签
				history_track_frame.clearOverLay(); 
			}	 
			 //运行历史轨迹
			history_track_frame.runHistoryTrack(); 
		  }
	 


//点击查询搜索符合条件的数据
	function condition_driver_data() {
			$("#statistic_driver_table").jqGrid().setGridParam({url : "index.php?a=408&begin_data="+$("#driver_begin_data").val()
				+"&end_data="+$("#driver_end_data").val(),page:1}).trigger("reloadGrid");			
	}
  	
	var day = new Date();  		
   	var Year = 0;
   	var Month = 0;
   	var Day = 0;

    //初始化时间最初时间
	var CurrentDate = "";
   	Year= day.getFullYear();
   	Month= day.getMonth()+1;	   	
   	CurrentDate += Year + "/";
   	if (Month >= 10){
    	CurrentDate += Month + "/";
   	}else{
    	CurrentDate += "0" + Month + "/";
   	}  		   
    CurrentDate += "01";		   	
   	CurrentDate += " 00:00:00";
	$("#driver_begin_data").attr("value",CurrentDate); 

    //初始化最后时间
	var CurrentDateValue = "";
 
   	Day = day.getDate();
   	Hour = day.getHours();
 	Minute = day.getMinutes();
	Second = day.getSeconds();
	CurrentDateValue += Year + "/";
   	if (Month >= 10){
   		CurrentDateValue += Month + "/";
   	}else{
   		CurrentDateValue += "0" + Month + "/";
   	}
   	if (Day >= 10 ){
   		CurrentDateValue += Day +" ";
   	}else{
   		CurrentDateValue += "0" + Day+" " ;
   	}
   	if(Hour>=10){
   		CurrentDateValue+=Hour+":";
	}else{
		CurrentDateValue+="0"+Hour+":";
   	}if (Minute >= 10 ){
   		CurrentDateValue += Minute +":";
   	}else{
   		CurrentDateValue += "0" + Minute+":" ;
   	}
   	if(Second>=10){
   		CurrentDateValue+=Second;
	}else{
		CurrentDateValue+="0"+Second;
   	}
   	$("#driver_end_data").attr("value",CurrentDateValue);  	

	$("#driver_begin_data").datetimepicker( {
		ampm : false,//上午下午是否显示  
		timeFormat : 'hh:mm:ss',//时间模式  
		stepHour : 1,//拖动时间时的间隔  
		stepMinute : 1,//拖动分钟时的间隔  
		//stepSecond: 1,//拖动秒时的间隔
		dateFormat : "yy/mm/dd", //日期格式设定  
		showHour : true,//是否显示小时，默认是true  
		showMinute : true,
		showSecond : false,
		createButton : false
	});

	$("#driver_end_data").datetimepicker( {
		ampm : false,//上午下午是否显示  
		timeFormat : 'hh:mm:ss',//时间模式  
		stepHour : 1,//拖动时间时的间隔  
		stepMinute : 1,//拖动分钟时的间隔  
		//stepSecond: 1,//拖动秒时的间隔
		dateFormat : "yy/mm/dd", //日期格式设定  
		showHour : true,//是否显示小时，默认是true  
		showMinute : true,
		showSecond : false,
		createButton : false
	});
	
	 //查看数据详细内容
function show_driver(id){
		$("#driver_statistic_content").dialog({height:500,width:900,title:'驾驶员详细信息',
			                 autoOpen:true,hide:'blind',show:'blind'}); 		                	 			                 
	    $("#driver_statistic_content").html("");
		$("#driver_statistic_content").mask("处理中...");
		$.post("index.php?a=410&driver_id="+id,function(data){	
			$("#driver_statistic_content").html(data);
			$("#driver_statistic_content").unmask();
		});
 }
	
//直接按照加载页面后的日期去查询
jQuery("#statistic_driver_table").jqGrid( {
		url : "index.php?a=408&begin_data="+$("#driver_begin_data").val()+"&end_data="+$("#driver_end_data").val(),			
		datatype : "json",
		colNames : ['driver_id','驾驶员姓名', '累计行驶距离(公里)', '累计行驶时间(分钟)', '累计停车时间(分钟)', '最早开车时间', '最晚开车时间','详细说明' ],
		colModel : [ {
			name : 'driver_id',
			index : 'driver_id',
			width : 0,
			editable : false,
			hidden : true
		},{
			name : 'name',
			index : 'name',
			width : 65,
			align : "center",
			editable : false
		},{
			name : 'distance',
			index : 'distance',
			width : 125,
			align : "center",
			editable : false
		}, {
			name : 'drive_time',
			index : 'drive_time',
			width : 125,
			align : "center",
			editable : false
		}, {
			name : 'stop_time',
			index : 'stop_time',
			width : 125,
			align : "center",
			editable : false
		}, {
			name : 'min_time',
			index : 'min_time',
			width : 125,
			align : "center",
			editable : false
		}, {
			name : 'max_time',
			index : 'max_time',
			width : 125,
			align : "center",
			editable : false
		},{
			name : 'detail',
			index : 'detail',
			width : 80,
			align : "center",
			editable : false
		}],
		rowNum : 10,// 初始化每页10条数据
		rowList : [ 10, 20, 30 ],// 设置每页多少条数据
		gridview : true,
		pager : '#statistic_driver_div',
		viewrecords : true,
		sortorder : "asc",
		height : "230",
		width:"800"
	});
	
	jQuery("#statistic_driver_table").jqGrid('navGrid', '#statistic_driver_div', {
		del : false,
		add : false,
		edit : false,
		search:false,
		alerttext : "请选择需要操作的数据行!"
	});
	
	$(":button").button();
	
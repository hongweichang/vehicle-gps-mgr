



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
	$("#vehicle_begin_data").attr("value",CurrentDate); 

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
   	$("#vehicle_end_data").attr("value",CurrentDateValue);  	


    //设置日期控件
	$("#vehicle_begin_data").datetimepicker( {
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

	//设置日期控件
	$("#vehicle_end_data").datetimepicker( {
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
	
	 //查看数据详细内容vehicle_detail_listvehicle_statistic_content_val
	function show_vehicle(id){
		$("#vehicle_statistic_content").dialog({height:500,width:900,title:'车辆详细信息',
			                 autoOpen:true,hide:'blind',show:'blind'}); 		                	 			                 
					    $("#vehicle_statistic_content").html("");
						$("#vehicle_statistic_content").mask("处理中...");
						$.post("index.php?a=413&vehicle_id="+id,function(data){
						$("#vehicle_statistic_content").html(data);
						$("#vehicle_statistic_content").unmask();
		});
	  }
	
	
	//点击要查询的车辆
	function select_vehicle_data(){
			$("#select_statistic_content").dialog({height:400,width:900,title:'选择要查询的车辆',
	            autoOpen:true,hide:'blind',show:'blind',close:function(event, ui) {
				   $("#select_statistic_content").html("");
			}}); 		                	 			                 
	    $("#select_statistic_content").mask("处理中...");
		$.post("index.php?a=1&array_ID="+array_id,function(data){
		$("#select_statistic_content").html(data);
		$("#sel_vehicle_commit").click(function(){
			var vehicles = $(".vehicle:checked");
	     	var str="";
	     	vehicles.each(function(i){
		     	if(str != ""){
					str += ",";
			    }
         		str += $(this).val();
	      	});
	     	    array_id=str;
		      	$('#select_statistic_content').dialog('close');  
		      	condition_vehicle_data(); 
		 });		
		$("#select_statistic_content").unmask();	
	});
  }	
		
var begin_data=$('#vehicle_begin_data').val();
var end_data=$('#vehicle_end_data').val();
var vehicle_id_value=$("#vehicle_id_value").val();

jQuery("#vehicle_statistic_table").jqGrid( {
		url : 'index.php?a=409&select_vehicle='+vehicle_id_value+"&begin_data="+begin_data+"&end_data="+end_data,
		datatype : "json",
		colNames : ['vehicle_id','车牌号', '累计行驶距离(公里)', '累计行驶时间(分钟)', '累计停驶时间(分钟)', '最早启用时间', '最后停用时间','详细说明' ],
		colModel : [ {
			name : 'vehicle_id',
			index : 'vehicle_id',
			width : 0,
			resizable:true,
			editable : false,
			hidden : true
		},{
			name : 'number_plate',
			index : 'number_plate',
			width : 60,
			resizable:true,
			align : "center",
			editable : false
		},{
			name : 'distance',
			index : 'distance',
			width : 125,
			resizable:true,
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
		pager : '#vehicle_statistic_div',
		viewrecords : true,
		sortorder : "asc",
		height : "230",
		width: "800"
	});
	
	jQuery("#vehicle_statistic_table").jqGrid('navGrid', '#vehicle_statistic_div', {
		del : false,
		add : false,
		edit : false,
		search:false,
		alerttext : "请选择需要操作的数据行!"
	});
	
	$(":button").button();
	


jQuery("#vehicle_status_list").jqGrid({
	url:'index.php?a=502',
	  datatype: "json",
   	colNames:['ID','车牌号', 'GPS状态', '定位时间','当前位置','实速','限速','驾驶员','告警状态','轨迹','统计信息','信息发布','定位'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'number_plate',index:'number_plate', width:80},
   		{name:'gps_status',index:'gps_status', width:70},
   		{name:'location_time',index:'location_time', width:120, align:"left"},   
   		{name:'cur_location',index:'cur_location', width:600, align:"left"},
   		{name:'real_speed',index:'real_speed', width:60, align:"left"},
   		{name:'speed_limit',index:'speed_limit', width:50, align:"left"},
   		{name:'driver',index:'driver', editable:true,width:70, align:"left"},
   		{name:'alert_status',index:'alert_status', width:70, align:"left"},
   		{name:'trace',index:'trace', width:60, align:"left"},
   		{name:'statistic',index:'statistic', width:70, align:"left"},
   		{name:'info',index:'info', width:70, align:"left"},
   		{name:'location',index:'location', width:60,align:"left"}
   		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pager',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"车辆状态",
    editurl:"index.php?a=1012",
	height:290,
	
	loadComplete:function(){
	var ids = $("#vehicle_status_list").jqGrid('getDataIDs');
	for(var i = 1;i<=ids.length;i++){
		var value = $("#vehicle_status_list").jqGrid('getCell',i,8);
		if(value=="超速"){
			$("#vehicle_status_list").jqGrid('setRowData',i,"",{background:'#ff0000'});
		}
		if(value=="疲劳"){
			$("#vehicle_status_list").jqGrid('setRowData',i,"",{background:'#FFFF66'});
		}
	}
}
});

jQuery("#vehicle_status_list").jqGrid('navGrid','#pager',
{edit:false,add:false,del:false});

//根据车牌号查询
$("#commit").click(function(){
	var url = 'index.php?a=502&number_plate='+$("#number_plate").val();
	jQuery("#vehicle_status_list").jqGrid('setGridParam',{url:url}).trigger("reloadGrid");
	$("#area_result").hide();
	$("#commit").show();
	$("#area_select").show();
	$("#frame_map").hide();	
	$("#select").show();
	
	jQuery("#vehicle_status_list").jqGrid('setGridParam',{url:url}).trigger("reloadGrid");
	$("#commit").show();
	$("#area_result").hide();
	$("#area_select").show();
	$("#frame_map").hide();	
	$("#select").show();
});

//区域查询
$("#area_select").click(function(){
	$("#area_select").hide();
	$("#commit").hide();
	$("#area_result").show();
    $("#select").hide();
	$("#frame_map").show();
	$("#frame_map").attr("src","inquire/templates/51ditu.html");
});

//显示区域查询结果
$("#area_result").click(function(){	
	var lonMin = document.getElementById("frame_map").contentWindow.document.getElementById("lonMin").value;
	var latMin = document.getElementById("frame_map").contentWindow.document.getElementById("latMin").value;
	var lonMax = document.getElementById("frame_map").contentWindow.document.getElementById("lonMax").value;
	var latMax = document.getElementById("frame_map").contentWindow.document.getElementById("latMax").value;
	
	var url = 'index.php?a=502&number_plate='+$("#number_plate").val()+'&lonMin='+lonMin+'&latMin='+latMin+'&lonMax='+lonMax+'&latMax='+latMax;
	jQuery("#vehicle_status_list").jqGrid('setGridParam',{url:url}).trigger("reloadGrid");
	$("#commit").show();
	$("#area_result").hide();
	$("#area_select").show();
	$("#frame_map").hide();	
	$("#select").show();
});
jQuery("#vehicle_status_list").jqGrid({
	url:'index.php?a=502',
	datatype: "json",
   	colNames:['ID','车牌号', 'GPRS状态', '定位时间','当前位置','实速','驾驶员','告警状态','历史轨迹','统计信息','信息发布','定位'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'number_plate',index:'number_plate', width:80},
   		{name:'gps_status',index:'gps_status', width:80},
   		{name:'location_time',index:'location_time', width:90, align:"left"},   
   		{name:'cur_location',index:'cur_location', width:80, align:"left"},
   		{name:'real_speed',index:'real_speed', width:60, align:"left"},
   		{name:'driver',index:'driver', editable:true,width:70, align:"left"},
   		{name:'alert_status',index:'alert_status', width:80, align:"left"},
   		{name:'trace',index:'trace', width:80, align:"left"},
   		{name:'statistic',index:'statistic', width:80, align:"left"},
   		{name:'info',index:'info', width:80, align:"left"},
   		{name:'location',index:'location', width:60,align:"left"}
   		
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pager',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    editurl:"index.php?a=1012",
    multiselect: true, 
	height:276,

	//翻译告警状态字段
	loadComplete:function(){
	var ids = $("#vehicle_status_list").jqGrid('getDataIDs');
	for(var i = 0;i<ids.length;i++){
		var value = $("#vehicle_status_list").jqGrid('getCell',ids[i],8);
		if("超速"==value){
			$("#vehicle_status_list").jqGrid('setRowData',ids[i],"",{background:'#ff0000'});
		}
		if("疲劳"==value){
			$("#vehicle_status_list").jqGrid('setRowData',ids[i],"",{background:'#FFFF66'});
		}
	}
}
});

jQuery("#vehicle_status_list").jqGrid('navGrid','#pager',
{edit:false,add:false,del:false,search:false});

/*批量发布信息*/
jQuery("#m1").click( function() {
		var s; 
		s = jQuery("#vehicle_status_list").jqGrid('getGridParam','selarrrow'); //获取所有选中车辆的ID
		if(s==null || s==""){
			alert("请选择车辆");
			return false;
		}
		showOperationDialog(this,"index.php?a=201&hidden=1&vehicle_ids="+s,"info_issue");
	}); 

/*定位车辆*/
jQuery("#m2").click( function() {
	var s; 
	s = jQuery("#vehicle_status_list").jqGrid('getGridParam','selarrrow'); //获取所有选中车辆的ID
	vehicle_position(s);
}); 

/*统计信息*/
jQuery("#m3").click( function() {
	var s; 
	s = jQuery("#vehicle_status_list").jqGrid('getGridParam','selarrrow');//获取所有选中车辆的ID
	if(s==null || s==""){
		alert("请选择车辆");
		return false;
	}
	showOperationDialog(this,"index.php?a=402&vehicle_id="+s,"static_show"); //显示该车辆的统计信息
});

//根据车牌号查询
$("#commit_vehicle").click(function(){
	var url = 'index.php?a=502&number_plate='+$("#number_plate").val();
	jQuery("#vehicle_status_list").jqGrid('setGridParam',{url:url}).trigger("reloadGrid"); //获取新数据刷新JQGrid
	$("#area_result").hide();
	$("#commit_vehicle").show();
	$("#area_select").show();
	$("#frame_map").hide();	
	$("#select").show();
	
	jQuery("#vehicle_status_list").jqGrid('setGridParam',{url:url}).trigger("reloadGrid");
	$("#commit_vehicle").show();
	$("#area_result").hide();
	$("#area_select").show();
	$("#frame_map").hide();	
	$("#select").show();
});

//区域查询
$("#area_select").click(function(){
	$("#area_select").hide();
	$("#locates").hide();
	$("#commit_vehicle").hide();
	$("#area_result").show();
    $("#select").hide();
	$("#frame_map").show();
	$("#frame_map").attr("src","templates/51map.html"); //Iframe加载地图
});

//显示区域查询结果
$("#area_result").click(function(){	
	/*获取经纬度范围*/
	var lonMin = document.getElementById("frame_map").contentWindow.document.getElementById("lonMin").value;
	var latMin = document.getElementById("frame_map").contentWindow.document.getElementById("latMin").value;
	var lonMax = document.getElementById("frame_map").contentWindow.document.getElementById("lonMax").value;
	var latMax = document.getElementById("frame_map").contentWindow.document.getElementById("latMax").value;
	
	var url = 'index.php?a=502&number_plate='+$("#number_plate").val()+'&lonMin='+lonMin+'&latMin='+latMin+'&lonMax='+lonMax+'&latMax='+latMax;
	jQuery("#vehicle_status_list").jqGrid('setGridParam',{url:url}).trigger("reloadGrid");//获取结果刷新JqGrid显示数据
	$("#commit_vehicle").show();
	$("#area_result").hide();
	$("#area_select").show();
	$("#frame_map").hide();	
	$("#select").show();
});

$(":button").button();//按钮替换成JQUERY样式
$("#frame_map").hide();

var vehicle_id_str = ""; //声明要定位车辆的ID
//定位事件
function  vehicle_position(vehicle_ids){
	 if(typeof(vehicle_ids)==="number"){
		 vehicle_ids = vehicle_ids.toString();
		 vehicle_ids = vehicle_ids.split(",");
	 }

	if(vehicle_ids==null || vehicle_ids=="") {
		alert("请选择车辆");
		return false;
	}
	$("#locates").mask("载入中.....");
	vehicle_id_str="";
	vehicle_id_str = vehicle_ids;
	
	locate_vehicle(); //显示车辆定位信息
}

/*显示车辆定位信息*/
function locate_vehicle(){ 
	$("#locates").show();
	if($("#locates").dialog('isOpen')){
		$("#locates").dialog('close');
	}
	$("#locates").dialog({height:356,width:610,title:'定位',zIndex:1000000,
			                 autoOpen:true,hide:'blind',show:'blind'}); 
	
	 var state = document.locate.document.readyState;
	 if(state=="complete"){
		 locate.location(vehicle_id_str);
	 }else{
		setTimeout("locate_vehicle();",1000);
	 }	
}
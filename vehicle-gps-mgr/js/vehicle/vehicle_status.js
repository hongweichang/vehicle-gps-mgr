$(document).ready(function(){
	
 jQuery("#vehicle_status_list").jqGrid({
   	url:'index.php?a=502',
		datatype: "json",
   	colNames:['ID','车牌号', 'GPS状态', '定位时间','当前位置','实速','限速','驾驶员','更换驾驶员','告警状态','轨迹','统计信息','信息发布','定位','jindu','weidu'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'number_plate',index:'number_plate', width:80},
   		{name:'gps_status',index:'gps_status', width:70},
   		{name:'location_time',index:'location_time', width:70, align:"left"},
   		{name:'cur_location',index:'cur_location', width:70, align:"left"},
   		{name:'real_speed',index:'real_speed', width:60, align:"left"},
   		{name:'speed_limit',index:'speed_limit', width:50, align:"left"},
   		{name:'driver',index:'driver', editable:true,width:70, align:"left"},
   		{name:'drivernames',index:'drivernames',editable:true,edittype:"select",editoptions:{value:"drivernames"},width:80,align:"left"},
   		{name:'alert_status',index:'alert_status', width:70, align:"left"},
   		{name:'trace',index:'trace', width:60, align:"left"},
   		{name:'statistic',index:'statistic', width:70, align:"left"},
   		{name:'info',index:'info', width:70, align:"left"},
   		{name:'location',index:'location', width:60,align:"left"},
   		{name:'jindu',index:'jindu', width:55,editable:false,hidden:true,editoptions:{size:10}},
   		{name:'weidu',index:'weidu', width:55,editable:false,hidden:true,editoptions:{size:10}}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pager',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"车辆状态",
    editurl:"index.php?a=1012",
	height:300,
	
	gridComplete:function(){
	var ids = $("#vehicle_status_list").jqGrid('getDataIDs');
	for(var i = 1;i<=ids.length;i++){
		var value = $("#vehicle_status_list").jqGrid('getCell',i,9);
		if(value=="超速"){
			$("#vehicle_status_list").jqGrid('setRowData',i,"",{background:'#ff0000'});
		}
		if(value=="疲劳"){
			$("#vehicle_status_list").jqGrid('setRowData',i,"",{background:'#FFFF66'});
		}
		
		/* var longitude = round($("#vehicle_status_list").jqGrid('getCell',i,14));
		 var latitude = round($("#vehicle_status_list").jqGrid('getCell',i,15));
		 var position;
		  
		  $.ajax({
			 type:"GET",
			 url:"http://ls.vip.51ditu.com/mosp/gc?pos="+longitude+","+latitude+"&callback=?", 
		     dataType:"jsonp",
		     jsonp:'callback',
		     complete:function(json){
			    $(json).find('R').each(function(i){
			    	 var name=$(this).children('msg').text();
			    	 alert(name);
			     });
			 
		     } 
		 });

		$("#vehicle_status_list").jqGrid('setCell',i,4,position);*/
	}
}
});

jQuery("#vehicle_status_list").jqGrid('navGrid','#pager',
{edit:false,add:false,del:false});
});
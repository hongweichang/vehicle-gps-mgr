jQuery("#vehicle_status_list").jqGrid({
   	url:'index.php?a=502',
		datatype: "json",
   	colNames:['ID','车牌号', 'GPS状态', '定位时间','当前位置','实速','限速','驾驶员','告警状态','轨迹','统计信息','信息发布','定位'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'number_plate',index:'number_plate', width:80},
   		{name:'gps_status',index:'gps_status', width:40},
   		{name:'location_time',index:'location_time', width:60, align:"right"},
   		{name:'cur_location',index:'cur_location', width:60, align:"right"},
   		{name:'real_speed',index:'real_speed', width:60, align:"right"},
   		{name:'speed_limit',index:'speed_limit', width:50, align:"right"},
   		{name:'driver',index:'driver', width:50, align:"right"},
   		{name:'alert_status',index:'alert_status', width:60, align:"right"},
   		{name:'trace',index:'trace', width:60, align:"right"},
   		{name:'statistic',index:'statistic', width:60, align:"right"},
   		{name:'info',index:'info', width:60, align:"right"},
   		{name:'location',index:'location', width:80,align:"right"}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pager',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"车辆状态",
    editurl:"index.php?a=1012",
	height:300
});
jQuery("#vehicle_status_list").jqGrid('navGrid','#pager',
{edit:false,add:false,del:false});

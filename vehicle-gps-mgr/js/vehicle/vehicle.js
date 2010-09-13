//'备用字段1','备用字段2','备用字段3','备用字段4','创建人','创建时间','更新人','更新时间'
jQuery("#navgrid_vehicle").jqGrid({
   	url:'index.php?a=1011',
		datatype: "json",
   	colNames:['ID','车牌号', 'GPS设备编号', '车辆组','驾驶员','车型','当前经度','当前纬度','当前速度','当前方向','告警状态','颜色','当前累计的行驶时间'],
   	colModel:[
   		{name:'id',index:'id',align:"center", width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'number_plate',index:'number_plate',align:"center", width:80,editable:true,editrules:{required:true},editoptions:{size:10}},
   		{name:'gps_id',index:'gps_id',align:"center", width:60,editable:true,editrules:{required:true},editoptions:{size:25}},
   		{name:'vehicle_group_id',index:'vehicle_group_id', width:60, align:"center",editrules:{required:true},editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=vehicle_group_id'}},
   		{name:'driver_id',index:'driver_id', width:60, align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=driver_id'}},
   		{name:'type_id',index:'type_id', width:60, align:"center",editable:true,edittype:"select",editrules:{required:true},editoptions:{dataUrl:'index.php?a=1013&p=type_id'}},
   		{name:'cur_longitude',index:'cur_longitude', width:50, align:"center",editable:true,editoptions:{size:10}},
   		{name:'cur_latitude',index:'cur_latitude', width:50, align:"center",editable:true,editoptions:{size:10}},
   		{name:'cur_speed',index:'cur_speed', width:60, align:"center",editable:true,editoptions:{size:10}},
   		{name:'cur_direction',index:'cur_direction', width:60, align:"center",editable:true,editoptions:{size:10}},
   		{name:'alert_state',index:'alert_state', width:60, align:"center",editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=alert_state'}},
   		{name:'color',index:'color', width:60, align:"center",editable:true,editoptions:{size:10}},
   		{name:'running_time',index:'running_time', width:80,align:"center",editable:true,editoptions:{size:30}}
   	],
   	width:750,
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_vehicle',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"车辆管理",
    editurl:"index.php?a=1012",
	height:350
});

jQuery("#navgrid_vehicle").jqGrid('navGrid','#pagernav_vehicle',
{edit:true,add:true,del:true,search:false}, //options
{del:false,add:true,edit:true,alerttext:"请选择需要操作的数据行!"});


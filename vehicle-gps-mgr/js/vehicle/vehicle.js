//'备用字段1','备用字段2','备用字段3','备用字段4','创建人','创建时间','更新人','更新时间'
jQuery("#navgrid_vehicle").jqGrid({
   	url:'index.php?a=1011',
		datatype: "json",
   	colNames:['ID','车牌号', 'GPS设备编号', '车辆组','驾驶员','车型','当前经度','当前纬度','当前速度','当前方向','告警状态','颜色','当前累计的行驶时间'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'number_plate',index:'number_plate', width:80,editable:true,editoptions:{size:10}},
   		{name:'gps_id',index:'gps_id', width:60,editable:true,editoptions:{size:25}},
   		{name:'vehicle_group_id',index:'vehicle_group_id', width:60, align:"right",editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=vehicle_group_id'}},
   		{name:'driver_id',index:'driver_id', width:60, align:"right",editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=driver_id'}},
   		{name:'type_id',index:'type_id', width:60, align:"right",editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=type_id'}},
   		{name:'cur_longitude',index:'cur_longitude', width:50, align:"right",editable:true,editoptions:{size:10}},
   		{name:'cur_latitude',index:'cur_latitude', width:50, align:"right",editable:true,editoptions:{size:10}},
   		{name:'cur_speed',index:'cur_speed', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'cur_direction',index:'cur_direction', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'alert_state',index:'alert_state', width:60, align:"right",editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=alert_state'}},
   		{name:'color',index:'color', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'running_time',index:'running_time', width:80,align:"right",editable:true,editoptions:{size:30}}
//   		{name:'backup1',index:'backup1', width:60,align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup2',index:'backup2', width:60,align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup3',index:'backup3', width:60,align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup4',index:'backup4', width:60,align:"right",editable:true,editoptions:{size:10}},
//   		{name:'create_id',index:'create_id', width:60,align:"right",editable:true,editoptions:{size:10}},
//		{name:'create_time',index:'create_time',width:55,align:'center',editable:true,editoptions:{size:30}},
//		{name:'update_id',index:'update_id',width:70, editable: true,editoptions:{size:10}},
//   		{name:'update_time',index:'update_time', width:100, sortable:false,editable: true, editoptions:{size:30}}
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
{edit:true,add:true,del:true}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:true,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/

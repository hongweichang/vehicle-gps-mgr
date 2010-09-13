jQuery("#navgrid_system_set").jqGrid({
   	url:'index.php?a=1041',
		datatype: "json",
   	colNames:['ID','公司','页面刷新时间', '默认车辆/轨迹颜色', '超速限制(例120公里/h)','疲劳驾驶提醒时间段'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'company_id',index:'company_id',hidden:true,editable:true,editoptions:{size:10}},
   		{name:'page_refresh_time',index:'page_refresh_time', width:60,editable:true,editoptions:{size:15}},
   		{name:'default_color',index:'default_color', width:60, align:"right",editable:true,editoptions:{size:15}},
   		{name:'speed_limit',index:'speed_astrict', width:60, align:"right",editable:true,editoptions:{size:15}},
   		{name:'fatigue_remind_time',index:'fatigue_remind_time', width:60, align:"right",editable:true,editoptions:{size:15}}
//   		{name:'backup1',index:'backup1', width:60,align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup2',index:'backup2', width:60,align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup3',index:'backup3', width:60,align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup4',index:'backup4', width:60,align:"right",editable:true,editoptions:{size:10}},
//   		{name:'create_id',index:'create_id', width:60,align:"right",editable:true,editoptions:{size:10}},
//		{name:'create_time',index:'create_time',width:55,align:'center',editable:true,editoptions:{size:30}},
//		{name:'update_id',index:'update_id',width:70, editable: true,editoptions:{size:10}},
//   		{name:'update_time',index:'update_time', width:100, sortable:false,editable: true, editoptions:{size:30}}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_system_set',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"系统设置",
    editurl:"index.php?a=1042",
    width:750,
		height:350
});
jQuery("#navgrid_system_set").jqGrid('navGrid','#pagernav_system_set',
{edit:true,add:true,del:true,search:false}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:true,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/

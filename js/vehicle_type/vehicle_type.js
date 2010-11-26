jQuery("#navgrid_vehicle_type").jqGrid({
   	url:'index.php?a=1031',
		datatype: "json",
   	colNames:['ID','公司ID', '类型名称','耗油指标','载重','描述'],
   	colModel:[
   		{name:'id',index:'id',align:"center", width:55,editable:false,hidden:true,editoptions:{size:10}},
   		{name:'company_id',index:'company_id',align:"center",hidden:true,editable:true,editoptions:{size:10}},
   		{name:'name',index:'name', align:"center",width:80,editable:true,editrules:{required:true},editoptions:{size:10}},
   		{name:'fuel_consumption',index:'fuel_consumption',align:"center",width:40,editable:true,editoptions:{size:10}},
   		{name:'load_capacity',index:'load_capacity',align:"center",width:40,editable:true,editoptions:{size:10}},
   		{name:'description',index:'description',align:"center",width:60,editable:true,editoptions:{size:10}}
//   		{name:'create_id',index:'driver_id', width:60, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'create_time',index:'type_id', width:60, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'update_id',index:'cur_longitude', width:50, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'update_time',index:'cur_latitude', width:50, align:"right",editable:true,editoptions:{size:10}}
   	],
   	width:750,
   	rowNum:10,
   	rowList:[10,20,40],
   	pager: '#pagernav_vehicle_type',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"车辆类型管理",
    editurl:"index.php?a=1032",
	height:350
});
jQuery("#navgrid_vehicle_type").jqGrid('navGrid','#pagernav_vehicle_type',
{edit:true,add:true,del:true,search:false}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:true,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/

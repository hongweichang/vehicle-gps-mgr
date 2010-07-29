//, '描述','创建人','创建时间','更新人','更新时间'
jQuery("#navgrid_vehicle_group").jqGrid({
   	url:'index.php?a=1021',
		datatype: "json",
   	colNames:['ID','组名', '所属公司'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,editoptions:{readonly:true,size:10}},
   		{name:'name',index:'number_plate', width:80,editable:true,editoptions:{size:10}},
   		{name:'company_id',index:'gps_id', width:40,editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=1023&p=company_id'}}
//   		{name:'description',index:'vehicle_group_id', width:60, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'create_id',index:'driver_id', width:60, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'create_time',index:'type_id', width:60, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'update_id',index:'cur_longitude', width:50, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'update_time',index:'cur_latitude', width:50, align:"right",editable:true,editoptions:{size:10}}
   	],
   	rowNum:10,
   	rowList:[10,20,40],
   	pager: '#pagernav_vehicle_group',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"车辆组管理",
    editurl:"index.php?a=1022",
	height:300
});
jQuery("#navgrid_vehicle_group").jqGrid('navGrid','#pagernav_vehicle_group',
{edit:true,add:true,del:true}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:true,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/

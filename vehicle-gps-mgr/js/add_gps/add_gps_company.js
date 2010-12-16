jQuery("#navgrid_gps").jqGrid({
   	url:'index.php?a=7002',
	datatype: "json",
	colNames:['ID', 'GPS设备号', '是否可用','公司ID'],
   	colModel:[
  		{name:'id',index:'id', width:20,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
  		{name:'gps_number',index:'gps_number', editable:true,width:20, align:"left"},
  		{name:'is_use',index:'is_use', editable:false,width:20, align:"left"},
  		{name:'company_id',index:'company_id',hidden:true,editable:false,width:20,align:"left"}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_gps',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption:"GPS管理",
    editurl:"index.php?a=7003",
	height:"350",
	width:"450"
});

jQuery("#navgrid_gps").jqGrid('navGrid','#pagernav_gps',
{edit:true, add:true, del:true,view:true,search:false}, 
{
	afterSubmit:processAddEdit,
	closeAfterAdd:true,
	closeAfterEdit:true,
	reloadAfterSubmit:true
},
{
	afterSubmit:processAddEdit,
	closeAfterAdd:true,
	closeAfterEdit:true,
	reloadAfterSubmit:true
}
);

//处理添加，编辑的返回信息
function processAddEdit(response){
	var success =true;
	var message ="";
	var json = eval('('+ response.responseText + ')');

	if(!json.success){
	   success =json.success;
	   message =json.errors;
	}
	return [success,message,0];
}
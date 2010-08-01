jQuery("#navgrid").jqGrid({
   	url:'index.php?a=5006',
	datatype: "json",
	colNames:['ID','用户', '所属公司', '操作时间','操作描述'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,editoptions:{readonly:true,size:10}},
   		{name:'user_id',index:'user_id', width:80,editable:true,editoptions:{size:20}},
   		{name:'company_id',index:'company_id', width:90,editable:true,editoptions:{size:18}},
   		{name:'time',index:'time', width:60, align:"right",editable:true,editoptions:{size:1}},
   		{name:'description',index:'description', width:60, align:"right",editable:true,editoptions:{size:10}}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption:"日志管理",
    editurl:"index.php?a=5006",
	height:350,
	width:750
});
jQuery("#navgrid").jqGrid('navGrid','#pagernav',
{edit:false,add:false,del:false,view:true}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:false,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/
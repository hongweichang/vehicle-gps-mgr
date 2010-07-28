jQuery("#navgrid2").jqGrid({
   	url:'index.php?a=1002',
		datatype: "json",
   	colNames:['ID','登录用户名', '密码', '用户名','所属公司ID','角色ID','邮箱','状态','备用字段1','备用字段2','备用字段3','备用字段4','创建人','创建时间','更新人','更新时间'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,editoptions:{readonly:true,size:10}},
   		{name:'login_name',index:'login_name', width:80,editable:true,editoptions:{size:10}},
   		{name:'password',index:'password', width:40,editable:true,editoptions:{size:25}},
   		{name:'name',index:'name', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'company_id',index:'company_id', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'role_id',index:'role_id', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'email',index:'email', width:50, align:"right",editable:true,editoptions:{size:10}},
   		{name:'state',index:'state', width:50, align:"right",editable:true,editoptions:{size:10}},
   		{name:'backup1',index:'backup1', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'backup2',index:'backup2', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'backup3',index:'backup3', width:60, align:"right",editable:true,editoptions:{size:10}},
   		{name:'backup4',index:'backup4', width:60, align:"right",editable:true,editoptions:{size:10}},		
   		{name:'create_id',index:'create_id', width:60,align:"right",editable:true,editoptions:{size:10}},
		{name:'create_time',index:'create_time',width:55,align:'center',editable:true,editoptions:{size:10}},
		{name:'update_id',index:'update_id',width:70, editable: true,editoptions:{size:10}},
   		{name:'update_time',index:'update_time', width:100, sortable:false,editable: true, editoptions:{rows:"2",cols:"20"}}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav2',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"用户管理",
    editurl:"index.php?a=1007",
	height:300
});

jQuery("#navgrid2").jqGrid('navGrid','#pagernav2',
{edit:true,add:true,del:true}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:true,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/
//jQuery("#navgrid2").jqGrid('editGridRow', rowid, properties );


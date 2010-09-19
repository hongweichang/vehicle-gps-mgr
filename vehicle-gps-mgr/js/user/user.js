//'密码','所属公司ID','角色ID',,'备用字段1','备用字段2','备用字段3','备用字段4','创建人','创建时间','更新人','更新时间'
jQuery("#navgrid2").jqGrid({
   	url:'index.php?a=1002',
		datatype: "json",
   	colNames:['ID','登录用户名','密码','用户名','所属公司','角色ID','邮箱','状态'],
   	colModel:[
   		{name:'id',index:'id', align:"center",width:55,editable:false,hidden:true,editoptions:{size:10}},
   		{name:'login_name',index:'login_name',align:"center", width:80,editable:true,editrules:{required:true},editoptions:{size:15}},
   		{name:'password',index:'password', align:"center",width:40,hidden:true,editrules:{required:true,edithidden:true},editable:true,editoptions:{size:15}},
   		{name:'name',index:'name', width:60, align:"center",editrules:{required:true},editable:true,editoptions:{size:15}},
   		{name:'company_id',index:'company_id', width:60, hidden:true,align:"center",editable:true,editoptions:{size:10}},
   		{name:'role_id',index:'role_id', width:60, hidden:true,align:"center",editable:true,editoptions:{size:10}},
   		{name:'email',index:'email', align:"center",editable:true,editrules:{required:false,email:true},editoptions:{size:40}},
   		{name:'state',index:'state', width:50, align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{dataUrl:'index.php?a=1008&p=state'}},
//   		{name:'backup1',index:'backup1', width:60, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup2',index:'backup2', width:60, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup3',index:'backup3', width:60, align:"right",editable:true,editoptions:{size:10}},
//   		{name:'backup4',index:'backup4', width:60, align:"right",editable:true,editoptions:{size:10}},		
//   		{name:'create_id',index:'create_id', width:60,align:"right",editable:true,editoptions:{size:10}},
//		{name:'create_time',index:'create_time',width:55,align:'center',editable:true,editoptions:{size:10}},
//		{name:'update_id',index:'update_id',width:70, editable: true,editoptions:{size:10}},
//   		{name:'update_time',index:'update_time', width:100, sortable:false,editable: true, editoptions:{rows:"2",cols:"20"}}
   	],
   	width:750,
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav2',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"用户管理",
    editurl:"index.php?a=1007",
	height:350
});

jQuery("#navgrid2").jqGrid('navGrid','#pagernav2',
{edit:true,add:true,del:true,view:true,search:false}, //options
{
	afterSubmit:processAddEdit,
	closeAfterAdd:true,
	closeAfterEdit:true,
	reloadAfterSubmit:true
},
{
	afterSubmit:processAddEdit ,
	closeAfterAdd:true,
	closeAfterEdit:true,
	reloadAfterSubmit:true
}
);
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/
//jQuery("#navgrid2").jqGrid('editGridRow', rowid, properties );
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


//'密码','所属公司ID','角色ID',,'备用字段1','备用字段2','备用字段3','备用字段4','创建人','创建时间','更新人','更新时间'
jQuery("#navgrid2").jqGrid({
   	url:'index.php?a=1002',
		datatype: "json",
   	colNames:['ID','登录用户名','用户名','所属公司','公司','角色ID','邮箱','状态','角色'],
   	colModel:[
   		{name:'id',index:'id', align:"center",width:55,editable:false,hidden:true,editoptions:{size:10}},
   		{name:'login_name',index:'login_name',align:"center", width:80,editable:true,editrules:{required:true},editoptions:{size:15}},
   		{name:'name',index:'name', width:60, align:"center",editrules:{required:true},editable:true,editoptions:{size:15}},
   		{name:'company_id',index:'company_id', width:60, hidden:true,align:"center",editable:true,editoptions:{size:10}},
   		{name:'company_name',index:'company_name', width:60, hidden:true,align:"center",editable:true,editoptions:{size:10}},
   		{name:'role_id',index:'role_id', width:60, hidden:true,align:"center",editable:true,editoptions:{size:10}},
   		{name:'email',index:'email', align:"center",editable:true,editrules:{required:false,email:true},editoptions:{size:40}},
   		{name:'state',index:'state', width:50, align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{dataUrl:'index.php?a=1008&p=state'}},
   		{name:'role',index:'role', width:50, align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{dataUrl:'index.php?a=1008&p=role'}},
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


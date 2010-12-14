//'密码','所属公司ID','角色ID',,'备用字段1','备用字段2','备用字段3','备用字段4','创建人','创建时间','更新人','更新时间'
jQuery("#navgrid2_child").jqGrid({
   	url:'index.php?a=6022',
		datatype: "json",
   	colNames:['ID','登录用户名','用户名','邮箱','状态'],
   	colModel:[
   		{name:'id',index:'id', align:"center",width:55,editable:false,hidden:true,editoptions:{size:10}},
   		{name:'login_name',index:'login_name',align:"center", width:80,editable:false},
   		{name:'name',index:'name', width:60, align:"center",editable:false},
   		{name:'email',index:'email', align:"center",editable:false},
   		{name:'state',index:'state', width:50, align:"center",editable:false}
   	],
   	width:750,
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav2_child',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"用户管理",
    editurl:"index.php?a=1007",
	height:350
});

jQuery("#navgrid2_child").jqGrid('navGrid','#pagernav2_child',
{edit:false,add:false,del:false,view:true,search:false}
);


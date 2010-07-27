jQuery("#navgrid").jqGrid({
   	url:'driver/driver_cache.php',
	datatype: "json",
	colNames:['ID','姓名', '驾驶证号', '性别','出生日期','所属公司Id','参加工作时间','工号','驾照类型','手机','驾驶状态','手机邮箱','家庭住址','创建人','创建时间','更新人','更新时间'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,editoptions:{readonly:true,size:10}},
   		{name:'name',index:'name', width:80,editable:true,editoptions:{size:20}},
   		{name:'driving_licence_id',index:'driving_licence_id', width:90,editable:true,editoptions:{size:18}},
   		{name:'sex',index:'sex', width:60, align:"right",editable:true,editoptions:{size:1}},
   		{name:'birthday',index:'birthday', width:60, align:"right",editable:true,editoptions:{size:10}},		
   		{name:'company_id',index:'company_id', width:60,align:"right",editable:true,editoptions:{size:10}},
		{name:'career_time',index:'career_time',width:55,align:'center',editable:true,editoptions:{size:10}},
		{name:'job_number',index:'job_number',width:70,align:'center', editable: true,editoptions:{size:10}},
   		{name:'driving_type',index:'driving_type', width:60, align:'center', editable: true,editoptions:{size:10}},
		{name:'mobile',index:'mobile', width:60, align:'center', editable: true,editoptions:{size:10}},
		{name:'driving_state',index:'driving_state', width:60, align:'center', editable: true,editoptions:{size:10}},
		{name:'phone_email',index:'phone_email', width:60, align:'center', editable: true,editoptions:{size:10}},
		{name:'address',index:'address', width:60, align:'center', editable: true,editoptions:{size:10}},
		{name:'create_id',index:'create_id', width:60, align:'center', editable: true,editoptions:{size:10}},
		{name:'create_time',index:'create_time', width:60, align:'center', editable: true,editoptions:{size:10}},
		{name:'update_id',index:'update_id', width:60, align:'center', editable: true,editoptions:{size:10}},
		{name:'update_time',index:'update_time', width:60, align:'center', editable: true,editoptions:{size:10}}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"人员管理",
    editurl:"index.php?a=5002",
	height:210
});
jQuery("#navgrid").jqGrid('navGrid','#pagernav',
{}, //options
//edit:false,add:false,del:false
{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options
);
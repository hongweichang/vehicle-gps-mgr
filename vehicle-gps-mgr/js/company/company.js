jQuery("#navgrid").jqGrid({
   	url:'index.php?a=5004',
	datatype: "json",
	colNames:['ID','登录id', '公司名', '公司注册号','地区1(省)','地区2(市)','地区3(区、县)','描述','联系人名字','地址','邮编','电话','传真','移动电话','邮箱','网址','状态','服务开始时间','服务结束时间','收费标准','创建人','创建时间','更新人','更新时间'],
   	colModel:[
   		{name:'id',index:'id', width:55,editable:false,editoptions:{size:10}},
		{name:'login_id',index:'login_id', width:55,editable:true,editoptions:{size:10}},
		{name:'name',index:'name', width:55,editable:true,editoptions:{size:10}},
		{name:'register_num',index:'register_num', width:55,editable:true,editoptions:{size:10}},
		{name:'area1',index:'area1', width:55,editable:true,editoptions:{size:10}},
		{name:'area2',index:'area2', width:55,editable:true,editoptions:{size:10}},
		{name:'area3',index:'area3', width:55,editable:true,editoptions:{size:10}},
		{name:'description',index:'description', width:55,editable:true,editoptions:{size:10}},
		{name:'contact',index:'contact', width:55,editable:true,editoptions:{size:10}},
		{name:'address',index:'address', width:55,editable:true,editoptions:{size:10}},
		{name:'zipcode',index:'zipcode', width:55,editable:true,editoptions:{size:10}},
		{name:'tel',index:'tel', width:55,editable:true,editoptions:{size:10}},
		{name:'fax',index:'fax', width:55,editable:true,editoptions:{size:10}},
		{name:'mobile',index:'mobile', width:55,editable:true,editoptions:{size:10}},
		{name:'email',index:'email', width:55,editable:true,editoptions:{size:10}},
		{name:'site_url',index:'site_url', width:55,editable:true,editoptions:{size:10}},
		{name:'state',index:'state', width:55,editable:true,editoptions:{size:10}},
		{name:'service_start_time',index:'service_start_time', width:55,editable:true,editoptions:{size:10}},
		{name:'service_end_time',index:'service_end_time', width:55,editable:true,editoptions:{size:10}},
		{name:'charge_standard',index:'charge_standard', width:55,editable:true,editoptions:{size:10}},
		{name:'create_id',index:'create_id', width:55,editable:true,editoptions:{size:10}},
		{name:'create_time',index:'create_time', width:55,editable:true,editoptions:{size:10}},
		{name:'update_id',index:'update_id', width:55,editable:true,editoptions:{size:10}},
		{name:'update_time',index:'update_time', width:55,editable:true,editoptions:{size:10}},

   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"公司管理",
    editurl:"index.php?a=5002",
	height:210
});
jQuery("#navgrid").jqGrid('navGrid','#pagernav',
{}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:false,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/
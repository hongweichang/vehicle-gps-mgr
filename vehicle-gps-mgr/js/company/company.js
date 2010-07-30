jQuery("#navgrid").jqGrid({
   	url:'index.php?a=5004',
	datatype: "json",
	colNames:['ID','登录id', '公司名', '公司注册号','地区1(省)','地区2(市)','地区3(区、县)','描述','联系人名字','地址','邮编','电话','传真','移动电话','邮箱','网址','状态','服务开始时间','服务结束时间','收费标准','创建人','创建时间','更新人','更新时间'],
   	colModel:[
   		{name:'id',index:'id', width:55,align:"center",editable:false,editoptions:{size:10}},
		{name:'login_id',index:'login_id', width:55,align:"center",editable:true,editoptions:{size:10}},
		{name:'name',index:'name', width:55,align:"center",editable:true,editoptions:{size:20,maxlength:20},formoptions:{elmsuffix:"(*)"},editrules:{required:true}},
		{name:'register_num',index:'register_num', width:55,align:"center",editable:true,editoptions:{size:20,maxlength:20},formoptions:{elmsuffix:"(*)"},editrules:{required:true}},
		{name:'area1',index:'area1', width:55,align:"center",editable:true,editoptions:{size:6}},
		{name:'area2',index:'area2', width:55,align:"center",editable:true,editoptions:{size:6}},
		{name:'area3',index:'area3', width:55,align:"center",editable:true,editoptions:{size:6}},
		{name:'description',index:'description', width:55,align:"center",editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
		{name:'contact',index:'contact', width:55,align:"center",editable:true,editoptions:{size:20}},
		{name:'address',index:'address', width:55,align:"center",editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
		{name:'zipcode',index:'zipcode', width:55,align:"center",editable:true,editoptions:{size:6}},
		{name:'tel',index:'tel', width:55,align:"center",editable:true,editoptions:{size:13}},
		{name:'fax',index:'fax', width:55,align:"center",editable:true,editoptions:{size:13}},
		{name:'mobile',index:'mobile', width:55,align:"center",editable:true,editoptions:{size:11}},
		{name:'email',index:'email', width:55,align:"center",editable:true,editoptions:{size:30}},
		{name:'site_url',index:'site_url', width:55,align:"center",editable:true,editoptions:{size:50}},
		{name:'state',index:'state', width:55,align:"center",editable:true,edittype:"select",editoptions:{value:"0:未激活;1:激活"}},
	   	{name:'service_start_time',index:'service_start_time', width:80,align:"center",
			editable:true,
			editoptions:{size:17,
				dataInit:function(el){
//					$(el).datepicker({dateFormat:'yy-mm-dd'});
				},
				defaultValue: function(){
					var currentTime = new Date();
					var month = parseInt(currentTime.getMonth() + 1);
					month = month <= 9 ? "0"+month : month;
					var day = currentTime.getDate();
					day = day <= 9 ? "0"+day : day;
					var year = currentTime.getFullYear();

					var h = currentTime.getHours();
					var m = currentTime.getMinutes();
					var s = currentTime.getSeconds();
					return year+"-"+month + "-"+day+" "+h+ ":" +m+":"+s;				
				}
			},
			formoptions:{elmsuffix:"  yyyy-mm-dd H:i:s" },
			editrules:{required:true}
		},
	   	{name:'service_end_time',index:'service_end_time', width:80,align:"center",
			editable:true,
			editoptions:{size:17,
				dataInit:function(el){
//					$(el).datepicker({dateFormat:'yy-mm-dd hh mm ss'});
				},
				defaultValue: function(){
					var currentTime = new Date();
					var month = parseInt(currentTime.getMonth() + 1);
					month = month <= 9 ? "0"+month : month;
					var day = currentTime.getDate();
					day = day <= 9 ? "0"+day : day;
					var year = currentTime.getFullYear();

					var h = currentTime.getHours();
					var m = currentTime.getMinutes();
					var s = currentTime.getSeconds();
					return year+"-"+month + "-"+day+" "+h+ ":" +m+":"+s;				
				}
			},
			formoptions:{elmsuffix:"  yyyy-mm-dd H:i:s" },
			editrules:{required:true}
		},
		{name:'charge_standard',index:'charge_standard', width:55,align:"center",editable:true,editoptions:{size:30}},
		{name:'create_id',index:'create_id', width:55,align:"center",editable:true,editoptions:{size:10}},
		{name:'create_time',index:'create_time', width:55,align:"center",editable:true,editoptions:{size:10}},
		{name:'update_id',index:'update_id', width:55,align:"center",editable:true,editoptions:{size:10}},
		{name:'update_time',index:'update_time', width:55,align:"center",editable:true,editoptions:{size:10}},

   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"公司管理",
    editurl:"index.php?a=5011",
//	multiselect: true, 
	height:210
});
jQuery("#navgrid").jqGrid('navGrid','#pagernav',
{view:true}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:false,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/
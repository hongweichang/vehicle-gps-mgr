jQuery("#navgrid").jqGrid({
   	url:'index.php?a=5004',
	datatype: "json",
	colNames:['ID','登录id', '公司名', '公司注册号','地区1(省)','地区2(市)','地区3(区、县)','描述','联系人名字','地址','邮编','电话','传真','移动电话','邮箱','网址','状态','服务开始时间','服务结束时间','收费标准','创建人','创建时间','更新人','更新时间'],
   	colModel:[
   		{name:'id',index:'id',hidden:true, width:55,align:"center",editable:false,editoptions:{size:10}},
		{name:'login_id',index:'login_id', width:55,align:"center",editable:true,editoptions:{size:10,maxlength:20},formoptions:{elmsuffix:"(*) 可添加不可修改"},editrules:{required:true}},
		{name:'name',index:'name', width:55,align:"center",editable:true,editoptions:{size:20,maxlength:50},formoptions:{elmsuffix:"(*)"},editrules:{required:true}},
		{name:'register_num',index:'register_num', width:55,align:"center",editable:true,editoptions:{size:20,maxlength:20},formoptions:{elmsuffix:"(*)"},editrules:{required:true}},
		{name:'area1',index:'area1', hidden:true,width:55,align:"center",editable:true,editoptions:{size:6}},
		{name:'area2',index:'area2', hidden:true,width:55,align:"center",editable:true,editoptions:{size:6}},
		{name:'area3',index:'area3', hidden:true,width:55,align:"center",editable:true,editoptions:{size:6}},
		{name:'description',index:'description', hidden:true, width:55,align:"center",editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20"},editrules:{edithidden:true}},
		{name:'contact',index:'contact', width:55,align:"center",editable:true,editoptions:{size:20,maxlength:20},formoptions:{elmsuffix:"(*)"},editrules:{required:true}},
		{name:'address',index:'address', hidden:true,width:55,align:"center",editable:true,edittype:"textarea", editoptions:{rows:"2",cols:"20",maxlength:100},editrules:{edithidden:true}},
		{name:'zipcode',index:'zipcode', hidden:true,width:55,align:"center",editable:true,editoptions:{size:6,maxlength:6},editrules:{edithidden:true}},
		{name:'tel',index:'tel', width:55,align:"center",editable:true,editoptions:{size:13,maxlength:13},formoptions:{elmsuffix:"(*)"},editrules:{required:true}},
		{name:'fax',index:'fax', hidden:true,width:55,align:"center",editable:true,editoptions:{size:13,maxlength:13},editrules:{edithidden:true}},
		{name:'mobile',index:'mobile', hidden:true,width:55,align:"center",editable:true,editoptions:{size:11,maxlength:11},editrules:{edithidden:true}},
		{name:'email',index:'email', width:55,align:"center",editable:true,editoptions:{size:30,maxlengh:30},formoptions:{elmsuffix:"(*)"},editrules:{required:true,email:true}},
		{name:'site_url',index:'site_url', hidden:true,width:55,align:"center",editable:true,editoptions:{size:50,maxlength:50},editrules:{edithidden:true}},
		{name:'state',index:'state', width:55,align:"center",editable:true,edittype:"select",editoptions:{value:"0:未激活;1:激活"}},
	   	{name:'service_start_time',index:'service_start_time', width:80,align:"center",
			editable:true,
			editoptions:{size:17,
				dataInit:function(el){
					$(el).datetimepicker({
						 ampm: false,//上午下午是否显示  
						 timeFormat: 'hh:mm:ss',//时间模式  
						 stepHour: 1,//拖动时间时的间隔  
						 stepMinute: 1,//拖动分钟时的间隔  
						 stepSecond: 1,//拖动秒时的间隔
						 dateFormat:"yy-mm-dd", //日期格式设定  
						 showHour: true,//是否显示小时，默认是true  
						 showMinute:true,
						 showSecond:true,
							 createButton:false
									});
				},
				defaultValue: ""
			},
			formoptions:{elmsuffix:"  yyyy-mm-dd H:i:s" }
//			editrules:{time:true}
		},
	   	{name:'service_end_time',index:'service_end_time', width:80,align:"center",
			editable:true,
			editoptions:{size:17,
				dataInit:function(el){
					$(el).datetimepicker({
						 ampm: false,//上午下午是否显示  
						 timeFormat: 'hh:mm:ss',//时间模式  
						 stepHour: 1,//拖动时间时的间隔  
						 stepMinute: 1,//拖动分钟时的间隔  
						 stepSecond: 1,//拖动秒时的间隔
						 dateFormat:"yy-mm-dd", //日期格式设定  
						 showHour: true,//是否显示小时，默认是true  
						 showMinute:true,
						 showSecond:true
									});
				},
				defaultValue: ""
			},
			formoptions:{elmsuffix:"  yyyy-mm-dd H:i:s" }
//			editrules:{time:true}
		},
		{name:'charge_standard',index:'charge_standard',width:55,align:"center",editable:true,editoptions:{size:30,maxlength:30}},
		{name:'create_id',index:'create_id', hidden:true,width:55,align:"center",editable:true,editoptions:{size:10}},
		{name:'create_time',index:'create_time', hidden:true,width:55,align:"center",editable:true,editoptions:{size:10}},
		{name:'update_id',index:'update_id',hidden:true, width:55,align:"center",editable:true,editoptions:{size:10}},
		{name:'update_time',index:'update_time', hidden:true,width:55,align:"center",editable:true,editoptions:{size:10}},

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
	height:"350",
	width:"750"
});
jQuery("#navgrid").jqGrid('navGrid','#pagernav',
{edit:true, add:true, del:true,view:true}, //options
//edit:false,add:false,del:false
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

/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/
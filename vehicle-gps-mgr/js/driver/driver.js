jQuery("#navgrid1").jqGrid({
   	url:'index.php?a=5002',
	datatype: "json",
	colNames:['姓名', '驾驶证号', '性别','出生日期','参加工作时间','工号','驾照类型','手机','驾驶状态','手机邮箱','家庭住址'],
   	colModel:[
//   		{name:'id',index:'id', width:55,align:"center",editable:false,editoptions:{readonly:true,size:10}},
   		{name:'name',index:'name',width:80,align:"center",editable:true,editoptions:{size:20},formoptions:{elmsuffix:"(*)"},editrules:{required:false}},
   		{name:'driving_licence_id',index:'driving_licence_id', width:90,align:"center",editable:true,editoptions:{size:18,maxlength:18},formoptions:{elmsuffix:"(*)"},editrules:{required:true,integer:true}},
   		{name:'sex',index:'sex', width:60, align:"right",align:"center",editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=5012&par=comm&child=sex'}},
	   	{name:'birthday',index:'birthday', width:80,align:"center",
			editable:true,
			editoptions:{size:17,
				dataInit:function(el){
					$(el).datepicker({dateFormat:'yy-mm-dd'});
				},
				defaultValue: function(){
					var currentTime = new Date();
					var month = parseInt(currentTime.getMonth() + 1);
					month = month <= 9 ? "0"+month : month;
					var day = currentTime.getDate();
					day = day <= 9 ? "0"+day : day;
					var year = currentTime.getFullYear();
					return year+"-"+month + "-"+day;				
				}
			},
			formoptions:{elmsuffix:"  yyyy-mm-dd" },
			editrules:{required:true}
		},
//   		{name:'company_id',index:'company_id', width:60,align:"right",editable:true,editoptions:{size:10}},
	   	{name:'career_time',index:'career_time', width:80,align:"center",
			editable:true,
			editoptions:{size:17,
				dataInit:function(el){
					$(el).datepicker({dateFormat:'yy-mm-dd'});
				},
				defaultValue: function(){
					var currentTime = new Date();
					var month = parseInt(currentTime.getMonth() + 1);
					month = month <= 9 ? "0"+month : month;
					var day = currentTime.getDate();
					day = day <= 9 ? "0"+day : day;
					var year = currentTime.getFullYear();
					return year+"-"+month + "-"+day;				
				}
			},
			formoptions:{elmsuffix:"  yyyy-mm-dd" },
			editrules:{required:true}
		},
		{name:'job_number',index:'job_number',width:70,align:'center', editable: true,editoptions:{size:10,maxlength:20}},
   		{name:'driving_type',index:'driving_type', width:60, align:'center', editable: true,editoptions:{size:10},formoptions:{elmsuffix:"(*)"},editrules:{required:true},edittype:"select",editoptions:{dataUrl:'index.php?a=5012&par=driver_manage&child=driving_type'}},
		{name:'mobile',index:'mobile', width:60, align:'center', editable: true,editoptions:{size:10,maxlength:11},formoptions:{elmsuffix:"(*)"},editrules:{required:true,integer:true}},
		{name:'driving_state',index:'driving_state', width:60, align:'center', editable: true,editoptions:{size:10},edittype:"select",editoptions:{dataUrl:'index.php?a=5012&par=driver_manage&child=driving_state'}},
		{name:'phone_email',index:'phone_email', width:60, align:'center', editable: true,editoptions:{size:10},formoptions:{elmsuffix:"(*)"},editrules:{required:true,email:true}},
		{name:'address',index:'address', width:60, align:'center', editable: true,editoptions:{size:10,maxlength:100},edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
//		{name:'create_id',index:'create_id', width:60, align:'center', editable: true,editoptions:{size:10}},
//		{name:'create_time',index:'create_time', width:60, align:'center', editable: true,editoptions:{size:10}},
//		{name:'update_id',index:'update_id', width:60, align:'center', editable: true,editoptions:{size:10}},
//		{name:'update_time',index:'update_time', width:60, align:'center', editable: true,editoptions:{size:10}}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav1',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption:"人员管理",
    editurl:"index.php?a=5010",
	height:"400",
	width:"1024"
});
jQuery("#navgrid1").jqGrid('navGrid','#pagernav1',
{view:true}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:false,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/
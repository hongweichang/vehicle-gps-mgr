jQuery("#navgrid_driver").jqGrid({
   	url:'index.php?a=5002',
	datatype: "json",
	colNames:['姓名', '驾驶证号', '性别','出生日期','参加工作时间','工号','驾照类型','手机','手机邮箱','家庭住址',"操作"],
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
//		{name:'driving_state',index:'driving_state', width:60, align:'center', editable: true,editoptions:{size:10},edittype:"select",editoptions:{dataUrl:'index.php?a=5012&par=driver_manage&child=driving_state'}},
		{name:'phone_email',index:'phone_email', width:60, align:'center', editable: true,editoptions:{size:10},formoptions:{elmsuffix:"(*)"},editrules:{required:true,email:true}},
		{name:'address',index:'address', width:60, align:'center', editable: true,editoptions:{size:10,maxlength:100},edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
//		{name:'create_id',index:'create_id', width:60, align:'center', editable: true,editoptions:{size:10}},
//		{name:'create_time',index:'create_time', width:60, align:'center', editable: true,editoptions:{size:10}},
//		{name:'update_id',index:'update_id', width:60, align:'center', editable: true,editoptions:{size:10}},
//		{name:'update_time',index:'update_time', width:60, align:'center', editable: true,editoptions:{size:10}}
		{name:"授权",index:"aaa", width:60, align:'center', editable: false,editoptions:{size:10}}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_driver',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption:"人员管理",
    editurl:"index.php?a=5010",
	height:"350",
	width:"750"
});
jQuery("#navgrid_driver").jqGrid('navGrid','#pagernav_driver',
{edit:true, add:true, del:true,view:true,search:false}, //options
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

//授权弹出层
function adviceDialog(driver_id,company_id){
    jQuery("#dialog").dialog({
			bgiframe: true,
			autoOpen: true, 
			height: 320, 
			width: 400,
			modal: true,
			title: '车辆授权',
			buttons: {
			    '取消': function() {
					jQuery(this).dialog('close');
			  },'提交': function() { 
					
					var check_list = document.getElementsByName("vehicle[]");

					var temp="";
					for(var i=0;i<check_list.length;i++) 
					{ 
						if(check_list[i].checked) 
						{
							temp+=check_list[i].value+",";
						} 
					}

					//提交
					 $.ajax({
						type: "POST",
						url: "index.php?a=5016",
						data: "temp="+temp+"&driver_id="+driver_id+"&company_id="+company_id,
						success: function(msg){
//							 alert(msg);
							 //关闭层
							 jQuery("#dialog").dialog('close');
						}
					 });
				}
            },open:function(event,ui){

		        getTraffic(driver_id,company_id);
            }
	 });

}

//ajax 得到授权的车辆
function getTraffic(driver_id,company_id)
{
	$.ajax({
		type: "POST",
		url: "index.php?a=5015",
		data: "driver_id="+driver_id+"&company_id="+company_id,
		success: function(msg){
			 jQuery("#canAdviceSelectDiv").html(msg); 
		}
	 });
}
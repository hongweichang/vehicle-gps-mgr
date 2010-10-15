var is_gps = true;
var gps_edit = false;
if("sysadmin"==$("#gps_role").val()){
	is_gps = false;
	gps_edit = true;
}

//'备用字段1','备用字段2','备用字段3','备用字段4','创建人','创建时间','更新人','更新时间'
jQuery("#navgrid_vehicle").jqGrid({
   	url:'index.php?a=1011',
		datatype: "json",
   	colNames:['ID','车牌号', 'GPS设备编号', '车辆组','驾驶员','车型','颜色','年检时间','更改驾驶员'],
   	colModel:[
   		{name:'id',index:'id',align:"center", width:55,editable:true,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'number_plate',index:'number_plate',align:"center", width:80,editable:true,editrules:{required:true},editoptions:{size:10,disabled:gps_edit}},
   		{name:'gps_id',index:'gps_id',align:"center", width:60,editable:true,editoptions:{size:25,disabled:is_gps}},
   		{name:'vehicle_group_id',index:'vehicle_group_id', width:60, align:"center",editrules:{required:true},editable:true,edittype:"select",editoptions:{disabled:gps_edit,dataUrl:'index.php?a=1013&p=vehicle_group_id'}},
   		{name:'driver_id',index:'driver_id', width:60, align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{disabled:gps_edit,dataUrl:'index.php?a=1013&p=driver_id'}},
   		{name:'type_id',index:'type_id', width:60, align:"center",editable:true,edittype:"select",editrules:{required:true},editoptions:{disabled:gps_edit,dataUrl:'index.php?a=1013&p=type_id'}},
   		{name:'color',index:'color', width:60, align:"center",editable:true,editoptions:{disabled:gps_edit,size:10}},
   		{name:'next_AS_date',index:'next_AS_date',align:"center", width:55,editable:true,editoptions:{disabled:gps_edit,size:30,
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
   		}},{name:'change_driver',index:'change_driver',width:60,align:'center'}
   	],
   	width:750,
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_vehicle',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"车辆管理",
    editurl:"index.php?a=1012",
	height:360
});

jQuery("#navgrid_vehicle").jqGrid('navGrid','#pagernav_vehicle',
{edit:true,add:is_gps,del:is_gps,search:false}, //options
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
});


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

function change_driver(vehicle_id){
	$.get("index.php?a=6001&vehicle_id="+vehicle_id,function(data){
		var drivers = eval("("+data+")");
		$("#drivers").html("<div style='text-align:center;margin-top:2px'>" + drivers+ "<button style='margin-left:14px' value='change' onclick='update_driver("+vehicle_id+")'>更改</button></div>" +
				"<p><ul>列表中驾驶员包括：<li>已经分配给该车辆的驾驶员</li><li>没有分配给任何车辆的驾驶员</li></ul></p>");
		$("#drivers").dialog({height:150,width:230,title:'更换驾驶员',
             autoOpen:true,position:[500,150],hide:'blind',show:'blind'});
	});
}

function update_driver(vehicle_id){
	var driver_id = $("#driver_options").val();
	$.get("index.php?a=1014&vehicle_id="+vehicle_id+"&driver_id="+driver_id,function(data){
		alert(data);
		if("ok"==data){
			$("#drivers").dialog('close');
			jQuery("#navgrid_vehicle").jqGrid('setGridParam',{url:'index.php?a=1011'}).trigger("reloadGrid");
		}else{
			alert("修改失败");
		}
	});
}

$(":button").button();



//'备用字段1','备用字段2','备用字段3','备用字段4','创建人','创建时间','更新人','更新时间'
jQuery("#navgrid_vehicle").jqGrid({
   	url:'index.php?a=1011',
		datatype: "json",
   	colNames:['ID','车牌号', 'GPS设备编号', '车辆组','驾驶员','车型','颜色','年检时间'/*,'更改驾驶员'*/,'gps_id','vehicle_group_id','type_id','driver_id'],
   	colModel:[
   		{name:'id',index:'id',align:"center", width:55,editable:true,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'number_plate',index:'number_plate',align:"center", width:80,editable:true,editrules:{required:true},editoptions:{size:10}},
   		{name:'gps_number',index:'gps_number', width:60, align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=gps_number'}},
   		{name:'vehicle_group_id',index:'vehicle_group_id', width:60, align:"center",editrules:{required:true},editable:true,edittype:"select",editoptions:{dataUrl:'index.php?a=1013&p=vehicle_group_id'}},
   		{name:'driver_id',index:'driver_id', width:60, align:"center",editable:true,editrules:{required:true},edittype:"select",editoptions:{disabled:true,dataUrl:'index.php?a=1013&p=driver_id'}},
   		{name:'type_id',index:'type_id', width:60, align:"center",editable:true,edittype:"select",editrules:{required:true},editoptions:{dataUrl:'index.php?a=1013&p=type_id'}},
   		{name:'color',index:'color', width:60, align:"center",editable:true,editoptions:{size:10}},
   		{name:'next_AS_date',index:'next_AS_date',align:"center", width:55,editable:true,editoptions:{size:30,
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
   		}},/*{name:'change_driver',index:'change_driver',width:60,align:'center'},*/
   		{name:'gps_id_two',index:'id',align:"center", width:55,editable:true,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'vehicle_group_id_two',index:'id',align:"center", width:55,editable:true,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'type_id_two',index:'id',align:"center", width:55,editable:true,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'driver_id_two',index:'id',align:"center", width:55,editable:true,hidden:true,editoptions:{readonly:true,size:10}}
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
{editfunc:edit_vehicle,addfunc:add_vehicle,del:true,search:false}, //options
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

var old_gps_id = null;
//编辑车辆
function edit_vehicle(){
	var grid = $("#navgrid_vehicle");
	 //选择选中的行
	var rowid = grid.jqGrid('getGridParam', 'selrow');
	var gps_id = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'gps_number');
	var number_plate = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'number_plate');
	var color = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'color');
	var vehicle_group_name =  jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'vehicle_group_id');
	var driver_name =  jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'driver_id');
	var type_name =  jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'type_id');
	var as_date = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'next_AS_date');
	
	old_gps_id = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'gps_id_two');

	//填充GPS设备号下拉列表
	$.get("index.php?a=1013&p=gps_number",function(data){
		$("#number_gps").html(data);
		if(gps_id!=null && gps_id!=""){
			var gps_id_two = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'gps_id_two');
			$("<option value="+gps_id_two+">"+gps_id+"</option>").appendTo($("#gps_select"));
			$("#gps_select").attr("value",gps_id_two);
		}else{
			$("#gps_select").get(0).selectedIndex=0;
		}
	});
	
	//填充车辆组下拉列表
	$.get("index.php?a=1013&p=vehicle_group_id",function(data){
		$("#group_vehicle").html(data);
		if(vehicle_group_name!=null && vehicle_group_name!=""){
			var vehicle_group_id_two = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'vehicle_group_id_two');
			$("#group_options").attr("value",vehicle_group_id_two);
		}else{
			$("#group_options").get(0).selectedIndex=0;
		}
	});
	
	//填充驾驶员下拉列表
	$.get("index.php?a=1013&p=driver_id&vehicle_id="+rowid,function(data){
		$("#drivers_vehicle").html(data);
		if(driver_name!=null && driver_name!=""){
			var driver_id_two = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'driver_id_two');
			$("#driver_option").attr("value",driver_id_two);
		}else{
			$("#driver_option").get(0).selectedIndex=0;
		}
	});
	
	//填充车辆类型下拉列表
	$.get("index.php?a=1013&p=type_id",function(data){
		$("#vehicle_style").html(data);
		if(type_name!=null && type_name!=""){
			var type_id_two = jQuery("#navgrid_vehicle").jqGrid('getCell', rowid, 'type_id_two');
			$("#type_options").attr("value",type_id_two);
		}else{
			$("#type_options").get(0).selectedIndex=0;
		}
	});

	$("#plate_number").val(number_plate);
	$("#color").val(color);
	$("#date_as").val(as_date);
	
	$("#add_edit").val("edit");
	
	//弹出编辑车辆DIALOG
	$("#gpses").dialog({height:300,width:280,title:'编辑车辆',
        autoOpen:true,position:[500,150],hide:'blind',show:'blind'});
}

//添加车辆
function add_vehicle(){
	//填充GPS设备号下拉列表
	$.get("index.php?a=1013&p=gps_number",function(data){
		$("#number_gps").html(data);
		$("#gps_select").get(0).selectedIndex=0;
	});
	
	//填充车辆类型下拉列表
	$.get("index.php?a=1013&p=vehicle_group_id",function(data){
		$("#group_vehicle").html(data);
		$("#group_options").get(0).selectedIndex=0;		
	});
	
	//填充驾驶员下拉列表
	$.get("index.php?a=1013&p=driver_id",function(data){
		$("#drivers_vehicle").html(data);
		$("#driver_option").get(0).selectedIndex=0;
	});
	
	//填充车辆类型下拉列表
	$.get("index.php?a=1013&p=type_id",function(data){
		$("#vehicle_style").html(data);
		$("#type_options").get(0).selectedIndex=0;	
	});

	$("#plate_number").val("");
	$("#color").val("");
	$("#date_as").val("");
	
	$("#add_edit").val("add");

	//弹出添加车辆DIALOG
	$("#gpses").dialog({height:300,width:280,title:'添加车辆',
        autoOpen:true,position:[500,150],hide:'blind',show:'blind'});
	
}

//提交编辑事件
$("#commit_edit").click(function(){
	var rowid = null;
	if($("#add_edit").val()=="edit"){
		var grid = $("#navgrid_vehicle");
		//选择选中的行
		rowid = grid.jqGrid('getGridParam', 'selrow');
	}
	var number_plate = $("#plate_number").val();
	var gps_index_id = $("#gps_select option:selected").val();
	var vehicle_group_id = $("#group_options option:selected").val();
	var type_id = $("#type_options option:selected").val();
	var driver_id = $("#driver_option option:selected").val();
	var color = $("#color_vehicle").val();
	var next_AS_date = $("#date_as").val();
	var gps_id = $("#gps_select option:selected").text();
	
	var add_edit = null;
	//判断是编辑车辆还是添加车辆
	if($("#add_edit").val()=="add"){
		add_edit = "add";
		if(number_plate=="" || number_plate==undefined){
			alert("车牌号不能为空");
			return false;
		}
	}else{
		add_edit = "edit";
	}
	
	$.post("index.php?a=1012",{oper:add_edit,id:rowid,number_plate:number_plate,gps_index_id:gps_index_id,gps_id:gps_id,old_gps_id:old_gps_id,vehicle_group_id:vehicle_group_id,type_id:type_id,driver_id:driver_id,color:color,next_AS_date:next_AS_date},function(data){
		if("success"==data){
			$("#gpses").dialog('close');
			jQuery("#navgrid_vehicle").jqGrid('setGridParam',{url:'index.php?a=1011'}).trigger("reloadGrid");
		}else{
			alert("操作失败");
		}
	});
});

//配置年检时间日期插件
$("#date_as").datetimepicker({
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

$(":button").button();//按钮换成JQUERY样式
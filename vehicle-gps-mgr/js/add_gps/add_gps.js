$(":button").button();

jQuery("#navgrid_gps").jqGrid({
   	url:'index.php?a=7002&action=direct',
	datatype: "json",
	colNames:['ID', 'GPS设备号', '是否可用','公司名'],
   	colModel:[
  		{name:'id',index:'id', width:20,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
  		{name:'gps_number',index:'gps_number', editable:true,width:20, align:"left"},
  		{name:'is_use',index:'is_use', editable:false,width:20, align:"left"},
  		{name:'company_id',index:'company_id', editable:true,edittype:"select",editrules:{required:true},editoptions:{dataUrl:'index.php?a=7005&p=company_name'},width:20, align:"left"}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_gps',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption:"GPS管理",
    editurl:"index.php?a=7003",
	height:"350",
	width:"450"
});

jQuery("#navgrid_gps").jqGrid('navGrid','#pagernav_gps',
{editfunc:edit_gps, addfunc:add_gps, del:true,view:true,search:true},
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

	var company_id = json.company_id;
	
	if(!json.success){
	   success =json.success;
	   message =json.errors;
	}
	return [success,message,0];
}

function add_gps(){
	$.get('index.php?a=7005&p=company_name',function(data){
		$("#companies_add").html(data);
		var company_id = $("#company_names").attr("name");
		
		if(company_id!="" && company_id!=undefined && $("#company_names option:selected").val()!=company_id){
			$("#company_names").attr("value",company_id);
		}
	});
	
	$("#add_gps").dialog({height:140,width:380,title:'添加GPS',
        autoOpen:true,position:[500,150],hide:'blind',show:'blind'});
}

function edit_gps(){
	
	var grid = $("#navgrid_gps");
	
	 //选择选中的行
	var id = grid.jqGrid('getGridParam', 'selrow');
	var gps_number = jQuery("#navgrid_gps").jqGrid('getCell', id, 'gps_number');
	
	$("#gps_number_edit").val(gps_number);
	$("#edit_gps").dialog({height:120,width:380,title:'编辑GPS',
        autoOpen:true,position:[500,150],hide:'blind',show:'blind'});
}

$("#commit_add").click(function(){
	
	var gps_number = $("#gps_number_add").val();
	var company_id = $("#company_names option:selected").val();
	
	if(isNaN(gps_number)){
		$("#check_gps_add").text("GPS设备号必须为数字");
		return false;
	}
	
	$("#company_id").val(company_id);
	$.get("index.php?a=7003&oper=add&gps_number="+gps_number+"&company_id="+company_id,function(){
		$("#add_gps").dialog('close');
		$("#companies_add").empty();
		$("#gps_number_add").val("");
		jQuery("#navgrid_gps").jqGrid('setGridParam',{url:'index.php?a=7002&action=direct'}).trigger("reloadGrid");
	});
});

$("#commit_edit").click(function(){
	
	var gps_number = $("#gps_number_edit").val();
	var grid = $("#navgrid_gps");
	
	if(isNaN(gps_number)){
		$("#check_gps_edit").text("GPS设备号必须为数字");
		return false;
	}
	
	 //选择选中的行
	var id = grid.jqGrid('getGridParam', 'selrow');
	
	$.get("index.php?a=7003&oper=edit&gps_number="+gps_number+"&id="+id,function(){
		$("#edit_gps").dialog('close');
		$("#gps_number_edit").val("");
		jQuery("#navgrid_gps").jqGrid('setGridParam',{url:'index.php?a=7002&action=direct'}).trigger("reloadGrid");
	});
});
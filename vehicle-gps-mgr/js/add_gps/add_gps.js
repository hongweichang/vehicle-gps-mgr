$(":button").button();

/*$("#commit_gps").click(function(){
	var gps_number = $("#add_gps_number").val();
	if(gps_number.length!=11 || !checkNum(gps_number)){
		alert("gps长度必须为11位数字");
		return;
	}
	$.get("index.php?a=7002&gps_number="+gps_number,function(data){
		if("add success"==data){
			alert("修改成功");
		}else{
			alert("修改失败");
		}
	});
});

function checkNum(str){
	return str.match(/\D/)==null;
}*/

jQuery("#navgrid_gps").jqGrid({
   	url:'index.php?a=7002',
	datatype: "json",
	colNames:['ID', 'GPS设备号', '是否可用'],
   	colModel:[
  		{name:'id',index:'id', width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
  		{name:'gps_number',index:'gps_number', editable:true,width:70, align:"left"},
  		{name:'is_use',index:'is_use', editable:false,width:70, align:"left"}
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
	width:"750"
});

jQuery("#navgrid_gps").jqGrid('navGrid','#pagernav_gps',
{edit:true, add:true, del:true,view:true,search:false}, 
{
	afterSubmit:processAddEdit,
	closeAfterAdd:true,
	closeAfterEdit:true,
	reloadAfterSubmit:true
},
{
	afterSubmit:processAddEdit,
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

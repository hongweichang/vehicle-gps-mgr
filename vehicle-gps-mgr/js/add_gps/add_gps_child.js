$(":button").button();
company_id = $("#companies_child option:selected").attr("id");

jQuery("#navgrid_gps_child").jqGrid({
   	url:'index.php?a=7002&action=child&company_id='+company_id,
	datatype: "json",
	colNames:['ID', 'GPS设备号', '是否可用','公司ID'],
   	colModel:[
  		{name:'id',index:'id', width:20,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
  		{name:'gps_number',index:'gps_number', editable:false,width:20, align:"left"},
  		{name:'is_use',index:'is_use', editable:false,width:20, align:"left"},
  		{name:'company_id',index:'company_id',hidden:true,editable:false,width:20,align:"left"}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_gps_child',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption:"GPS管理",
    editurl:"index.php?a=7003",
	height:"350",
	width:"450"
});

jQuery("#navgrid_gps_child").jqGrid('navGrid','#pagernav_gps_child',
{edit:false, add:false, del:false,view:true,search:false}
);

//切换公司获取公司GPS设备列表
$("#companies_child").change(function(){
	var company_id = $("#companies_child option:selected").attr("id");
	var url = "index.php?a=7002&action=child&company_id="+company_id;
	jQuery("#navgrid_gps_child").jqGrid('setGridParam',{url:url}).trigger("reloadGrid"); //获取新数据刷新JQGrid
});
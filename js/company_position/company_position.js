jQuery("#company_position").jqGrid({
   	url:'index.php?a=5023',
		datatype: "json",
   	colNames:['ID','标注名','删除'],
   	colModel:[
   		{name:'id',index:'id', align:"center",width:55,editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'name',index:'name',align:"center", width:40},
   		{name:'delete',index:'delete',align:"center",width:40}
   	],
   	width:400,
   	rowNum:10,
   	rowList:[10,20,40],
   	pager: '#company_position_pager',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    caption:"公司标注管理",
	height:350
});
jQuery("#company_position").jqGrid('navGrid','#company_position_pager',
{edit:false,add:false,del:false,search:false});

function delete_position(id){
	$.get("index.php?a=107&position_id="+id,function(data){
		alert(data);
		if("ok"==data){
			alert("删除成功");
			jQuery("#company_position").jqGrid('setGridParam',{url:"index.php?a=5023"}).trigger("reloadGrid");
		}else{
			alert("删除失败");
		}
	});
}
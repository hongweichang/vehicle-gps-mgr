jQuery("#history_message").jqGrid({
   	url:'index.php?a=8004',
	datatype: "json",
	colNames:['ID','消息','添加时间'],
   	colModel:[
   	    {name:'id',index:'id', width:55,align:"center",editable:false,hidden:true,editoptions:{readonly:true,size:10}},
   		{name:'text',index:'text', width:90,align:"center"},
   		{name:'add_date',index:'add_date', width:60,align:"center"}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_history',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption:"消息管理",
	height:"350",
	width:"750"
});
jQuery("#history_message").jqGrid('navGrid','#pagernav_history',
{edit:false, add:false, del:false,view:true,search:false}//options
);

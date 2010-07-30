jQuery("#navgrid_message").jqGrid({
   	url:'index.php?a=5009',
	datatype: "json",
	colNames:['是否是区域信息', '信息发布人', '信息类型','发布时间','生效时间','失效时间','信息标题','信息内容'],
   	colModel:[
//   		{name:'id',index:'id', width:55,editable:false,editoptions:{readonly:true,size:10}},
   		{name:'is_area_info',index:'is_area_info', width:80,align:"center",editable:true,editoptions:{size:20}},
   		{name:'issuer_id',index:'issuer_id', width:90,align:"center",editable:true,editoptions:{size:18}},
   		{name:'type',index:'type', width:60, align:"center",editable:true,editoptions:{size:1}},
   		{name:'issue_time',index:'issue_time', width:60, align:"center",editable:true,editoptions:{size:10}},		
   		{name:'begin_time',index:'begin_time', width:60,align:"right",editable:true,editoptions:{size:10}},
		{name:'end_time',index:'end_time',width:55,align:'center',editable:true,editoptions:{size:10}},
		{name:'title',index:'title',width:70,align:'center', editable: true,editoptions:{size:10}},
   		{name:'content',index:'content', width:60, align:'center', editable: true,editoptions:{size:10}}
   	],
   	rowNum:10,
   	rowList:[10,20,30],
   	pager: '#pagernav_message',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
    caption:"信息管理",
    editurl:"index.php?a=5002",
	height:210,
	width:700,
	onSelectRow: function(ids) {
		if(ids == null) {
			ids=0;
			if(jQuery("#list10_d").jqGrid('getGridParam','records') >0 )
			{
				jQuery("#list10_d").jqGrid('setGridParam',{url:"index.php?a=5013&id="+ids,page:1});
				jQuery("#list10_d").jqGrid('setCaption',"接受信息人员列表").trigger('reloadGrid');

				//影响区域
				jQuery("#list11_d").jqGrid('setGridParam',{url:"index.php?a=5014&id="+ids,page:1});
				jQuery("#list11_d").jqGrid('setCaption',"接受信息人员列表").trigger('reloadGrid');
			}
		} else {
			jQuery("#list10_d").jqGrid('setGridParam',{url:"index.php?a=5013&id="+ids,page:1});
			jQuery("#list10_d").jqGrid('setCaption',"影响区域列表").trigger('reloadGrid');			

			//影响区域
			jQuery("#list11_d").jqGrid('setGridParam',{url:"index.php?a=5014&id="+ids,page:1});
			jQuery("#list11_d").jqGrid('setCaption',"影响区域列表").trigger('reloadGrid');			
		}
	}
});
jQuery("#navgrid_message").jqGrid('navGrid','#pagernav_message',
{del:false,add:false,edit:false,view:true}, //options
//edit:false,add:false,del:false
{del:false,add:true,edit:false,alerttext:"请选择需要操作的数据行!"});
/*{height:200,reloadAfterSubmit:false}, // edit options
{height:280,reloadAfterSubmit:false}, // add options
{reloadAfterSubmit:false}, // del options
{} // search options 
);*/

//查询接受人员列表

jQuery("#list10_d").jqGrid({
	height: 100,
   	url:'index.php?a=5013&id=0',
	datatype: "json",
   	colNames:['ID','信息id', '接收人员类型', '接收人员'],
   	colModel:[
   		{name:'id',index:'id',hidden:true, width:55,align:"center"},
   		{name:'info_id',index:'info_id', width:180,align:"center",hidden:true},
   		{name:'receiver_type',index:'receiver_type', width:80, align:"center"},
   		{name:'receiver_id',index:'receiver_id', width:80, align:"center"},
   	],
   	rowNum:5,
   	rowList:[5,10,20],
   	pager: '#pager10_d',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	width:350,
	caption:"接受信息人员列表"
})
//jQuery("#list10_d").jqGrid('navGrid','#pager10_d',{add:false,edit:false,del:false});

//影响区域列表
jQuery("#list11_d").jqGrid({
	height: 100,
   	url:'index.php?a=5014&id=0',
	datatype: "json",
   	colNames:['ID','信息Id', '类型', '经度','纬度','半径','next_Id'],
   	colModel:[
   		{name:'id',index:'id',hidden:true, width:55,align:"center"},
   		{name:'info_id',index:'info_id', width:180,align:"center",hidden:true},
   		{name:'type',index:'type', width:80, align:"center"},
   		{name:'log',index:'log', width:80, align:"center"},
   		{name:'lat',index:'radius', width:80, align:"center"},
   		{name:'radius',index:'log', width:80, align:"center"},
   		{name:'next_Id',index:'next_Id', width:80, align:"center",hidden:true},
   	],
   	rowNum:5,
   	rowList:[5,10,20],
   	pager: '#pager11_d',
   	sortname: 'id',
    viewrecords: true,
    sortorder: "asc",
	width:350,
	caption:"影响区域列表"
})
var driverID=$("#dri_stop_id").val();	

jQuery("#drive_stop_data").jqGrid( {
		url : "index.php?a=412&drive_id="+driverID,
		datatype : "json",
		colNames : ['id','开始停车时间','最后停车时间','停车时间间隔(分钟)'],
		colModel : [{
			name : 'id',
			index : 'id',
			width : 0,
			editable : false,
			hidden : true
		},{
			name : 'start_time',
			index : 'start_time',
			width : 100,
			align : "center",
			editable : false
		},{
			name : 'end_time',
			index : 'end_time',
			width : 100,
			align : "center",
			editable : false
		}, {
			name : 'stop_time',
			index : 'stop_time',
			width : 50,
			align : "center",
			editable : false
		}],
		rowNum : 10,// 初始化每页10条数据
		rowList : [ 10, 20, 30 ],// 设置每页多少条数据
		mtype : "GET",
		rownumbers : true,
		rownumWidth : 60,
		gridview : true,
		pager : '#drive_stop_data_page',
		viewrecords : true,
		sortorder : "asc",
		caption : "驾驶员停车信息",
		height : 230,
		width :  800
	});
	
	jQuery("#drive_stop_data").jqGrid('navGrid', '#drive_stop_data_page', {
		del : false,
		add : false,
		edit : false,
		search:false,
		alerttext : "请选择需要操作的数据行!"
	});
	
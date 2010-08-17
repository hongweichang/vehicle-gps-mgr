	

jQuery("#navgrid_driver").jqGrid( {
		url : 'index.php?a=405',
		datatype : "json",
		colNames : ['driver_id','驾驶员姓名', '累计行驶距离', '累计行驶时间', '累计停车时间', '最早开车时间', '最晚开车时间','详细说明' ],
		colModel : [ {
			name : 'driver_id',
			index : 'driver_id',
			width : 0,
			editable : false,
			hidden : true
		},{
			name : 'name',
			index : 'name',
			width : 120,
			align : "center",
			editable : false
		},{
			name : 'distance',
			index : 'distance',
			width : 120,
			align : "center",
			editable : false
		}, {
			name : 'drive_time',
			index : 'drive_time',
			width : 120,
			align : "center",
			editable : false
		}, {
			name : 'stop_time',
			index : 'stop_time',
			width : 120,
			align : "center",
			editable : false
		}, {
			name : 'min_time',
			index : 'min_time',
			width : 120,
			align : "center",
			editable : false
		}, {
			name : 'max_time',
			index : 'max_time',
			width : 120,
			align : "center",
			editable : false
		},{
			name : 'detail',
			index : 'detail',
			width : 80,
			align : "center",
			editable : false
		}],
		rowNum : 10,// 初始化每页10条数据
		rowList : [ 10, 20, 30 ],// 设置每页多少条数据
		gridview : true,
		pager : '#pagernav_driver',
		viewrecords : true,
		sortorder : "asc",
		height : "230"
	});
	
	jQuery("#navgrid_driver").jqGrid('navGrid_driver', '#pagernav_driver', {
		del : false,
		add : false,
		edit : false,
		alerttext : "请选择需要操作的数据行!"
	});
	

    
	

	



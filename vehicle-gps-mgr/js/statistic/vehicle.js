	jQuery("#navgrid_vehicle").jqGrid( {
		url : 'index.php?a=403',
		datatype : "json",
		colNames : ['vehicle_id','车牌号', '累计行驶距离', '累计行驶时间', '累计停驶时间', '最早启用时间', '最后停用时间','详细说明' ],
		colModel : [ {
			name : 'driver_id',
			index : 'driver_id',
			width : 0,
			resizable:true,
			editable : false,
			hidden : true
		},{
			name : 'number_plate',
			index : 'number_plate',
			width : 120,
			resizable:true,
			align : "center",
			editable : false
		},{
			name : 'distance',
			index : 'distance',
			width : 120,
			resizable:true,
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
			index : 'max_time',
			width : 80,
			align : "center",
			editable : false
		}],
		rowNum : 10,// 初始化每页10条数据
		rowList : [ 10, 20, 30 ],// 设置每页多少条数据
		mtype : "GET",
		rownumbers : true,
		rownumWidth : 60,
		gridview : true,
		pager : '#pagernav_vehicle',
		viewrecords : true,
		sortorder : "asc",
		caption : "车辆信息",
		height : "230"
	});
	
	jQuery("#navgrid_vehicle").jqGrid('navGrid_vehicle', '#pagernav_vehicle', {
		del : false,
		add : false,
		edit : false,
		alerttext : "请选择需要操作的数据行!"
	});
    



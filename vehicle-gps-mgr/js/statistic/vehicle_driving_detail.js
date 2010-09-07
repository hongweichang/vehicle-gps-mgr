var vel=$("#ve_dring_id").val();

jQuery("#vehicle_driving_detail").jqGrid( {
		url : "index.php?a=414&vehicle_id="+vel,
		datatype : "json",
		colNames : [ 'id',  '开车时间', '停车时间', '车辆行驶时间(分钟)', '车辆行驶距离(公里)'],
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
		}, {
			name : 'end_time',
			index : 'end_time',
			width : 100,
			align : "center",
			editable : false
		}, {
			name : 'drive_time',
			index : 'drive_time',
			width : 120,
			align : "center",
			editable : false
		}, {
			name : 'distance',
			index : 'distance',
			width : 120,
			align : "center",
			editable : false
		}],
		rowNum : 10,// 初始化每页10条数据
		rowList : [ 10, 20, 30 ],// 设置每页多少条数据
		mtype : "GET",
		gridview : true,
		pager : '#vehicle_driving_detail_page',
		viewrecords : true,
		sortorder : "asc",
		caption : "车辆启用信息",
		height : 230,
		width : 800
	});

	jQuery("#vehicle_driving_detail").jqGrid('navGrid', '#vehicle_driving_detail_page', {
		del : false,
		add : false,
		edit : false,
		alerttext : "请选择需要操作的数据行!"
	});
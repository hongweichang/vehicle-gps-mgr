var driverID=$("#dri_driving_id").val();
var begin_date = $("#driver_begin_data").val();
var end_date = $("#driver_end_data").val();

jQuery("#drive_begin_data").jqGrid( {
		url : "index.php?a=411&drive_id="+driverID+"&begin_data="+begin_date+"&end_data="+end_date,
		datatype : "json",
		colNames : [ 'cds', '开车起始时间', '开车结束时间', '起止开车时间间隔(分钟)', '起止开车路程(公里)'],
		colModel : [{
			name : 'cds',
			index : 'cds',
			width : 0,
			editable : false,
			hidden : true
		},  {
			name : 'start_time',
			index : 'start_time',
			width : 80,
			align : "center",
			editable : false
		}, {
			name : 'end_time',
			index : 'end_time',
			width : 80,
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
		rownumbers : true,
		rownumWidth : 60,
		gridview : true,
		pager : '#drive_begin_data_page',
		viewrecords : true,
		sortorder : "asc",
		caption : "驾驶员开车信息",
		height : 230,
		width : 800
	});

	jQuery("#drive_begin_data").jqGrid('navGrid', '#drive_begin_data_page', {
		del : false,
		add : false,
		edit : false,
		search:false,
		alerttext : "请选择需要操作的数据行!"
	});

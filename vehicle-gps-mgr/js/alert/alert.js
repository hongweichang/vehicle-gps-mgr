	$( "#opinion" ).dialog({
		   close: function(event, ui) { reloadGrid(); }
	});
	
	jQuery("#navgrid1").jqGrid( {
		url : "index.php?a=902&group_id="+$("#vehicle_group").val()+"&vehicle_id="+$("#vehicle_sel").val()+"&deal="+$("#data_condition").attr("checked"),
		datatype : "json",
		colNames : [ 'id', '告警时间', '告警类型', '车牌号码', '处理人姓名', '描述', '处理状态' ],
		colModel : [ {
			name : 'id',
			index : 'id',
			width : 55,
			editable : false,
			hidden : true

		}, {
			name : 'alert_time',
			index : 'alert_time',
			width : 50,
			align : "center",
			editable : false
		}, {
			name : 'alert_type',
			index : 'alert_type',
			width : 50,
			align : "center",
			editable : false
		}, {
			name : 'vehicle_id',
			index : 'vehicle_id',
			width : 50,
			align : "center",
			editable : false
		}, {
			name : 'name',
			index : 'name',
			width : 50,
			align : "center",
			editable : false
		}, {
			name : 'description',
			index : 'description',
			width : 50,
			align : "center",
			editable : false
		}, {
			name : 'dealStatus',

			width : 50,
			align : "center",
			editable : false
		} ],
		rowNum : 10,// 初始化每页10条数据
		rowList : [ 10, 20, 30 ],// 设置每页多少条数据
		mtype : "GET",
		rownumbers : true,
		rownumWidth : 60,
		gridview : true,
		pager : '#pagernav1',
		viewrecords : true,
		sortorder : "asc",
		caption : "告警信息",
		height : 230,
		width : 800
		
	});
	jQuery("#navgrid1").jqGrid('navGrid', '#pagernav1', {
		del : false,
		add : false,
		edit : false,
		alerttext : "请选择需要操作的数据行!"
	});

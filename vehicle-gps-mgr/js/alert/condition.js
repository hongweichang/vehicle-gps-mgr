function selVehicle() {// 车辆组联动车辆编号
	var vehicle_group_id = $("#vehicle_group").val();
	var vehicle_content = "";
	jQuery.ajax( {
		url : "index.php?a=906",// 读取车辆组的名称数据
		type : "post",
		data : "vehicle_id=" + vehicle_group_id,
		success : function(data) {
			if (data == 0) {
				$("#vehicle").empty();
				$("#vehicle").append(
						"<option select='selected'>--无对应车辆编号--</option>");
			} else {
				$("#vehicle").empty();
				var arry = data.split("|");
				vehicle_content = vehicle_content + "<option value=@"
						+ vehicle_group_id + ">--全部车辆 --</option>";
				for ( var i = 0; i < arry.length - 1; i++) {
					var vehicle_value = arry[i].split(",");
					vehicle_content = vehicle_content + "<option value="
							+ vehicle_value[0] + ">" + vehicle_value[1]
							+ "</option>";

				}
				$("#vehicle").append(vehicle_content);
			}
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("Errors: " + errorThrown);
		}
	});
}

function condition() {
	var vehicle_group_content = "<option select='selected'>--请选择车辆组--</option>";
	var vehicle_content = "<option select='selected'>--请选择车辆编号--</option>";
	jQuery.ajax( {
		url : "index.php?a=905",// 读取车辆组的名称数据
		type : "post",
		data : null,
		success : function(data) {
			var arry_vel = data.split("@");

			var arry = arry_vel[0].split("|");
			for ( var i = 0; i < arry.length - 1; i++) {
				var vehicle_group_value = arry[i].split(",");
				vehicle_group_content = vehicle_group_content
						+ "<option value=" + vehicle_group_value[0] + ">"
						+ vehicle_group_value[1] + "</option>";

			}

			$("#vehicle_group").append(vehicle_group_content);
			$("#vehicle").append(vehicle_content);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown) {
			alert("Errors: " + errorThrown);
		}
	});
}

function conditionSel() {

	id = $("#vehicle").val();
	$("#navgrid1").clearGridData();
	var mygrid = jQuery("#navgrid1")[0];

	var val = $("#deal").val();

	if ($('#deal').attr('checked')) {
		var str = "index.php?a=902&vehicle_id=" + id
				+ "&page=1&rows=10&sidx=1&sord=asc&deal=" + deal;
	} else {
		var str = "index.php?a=902&vehicle_id=" + id
				+ "&page=1&rows=10&sidx=1&sord=asc";
	}
	$.post(str, function(json) {
		var jsonObject = eval('(' + json + ')');
		mygrid.addJSONData(jsonObject);
	}, "json");
}

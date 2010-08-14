function selVehicle() {// 车辆组联动车辆编号
	var vehicle_group_id = $("#vehicle_group").val();
	if(-1 == vehicle_group_id){
		$("#vehicle_sel").empty();
		$("#vehicle_sel").append(
		"<option select='selected' value=-1>全部车辆 </option>");
	}
	else{
		var vehicle_content = "";
		jQuery.ajax( {
			url : "index.php?a=906",// 读取车辆组的名称数据
			type : "post",
			data : "vehicle_id=" + vehicle_group_id,
			success : function(data) {
				
				if (data == 0) {
					$("#vehicle_sel").empty();
					$("#vehicle_sel").append(
							"<option select='selected' value=-2>无对应车辆编号</option>");
				} else {
					$("#vehicle_sel").empty();

					$("#vehicle_sel").append(
					"<option select='selected' value=-1>全部车辆 </option>");
					
					var arry = data.split("|");
					
					for ( var i = 0; i < arry.length - 1; i++) {			
						var vehicle_value = arry[i].split(",");
						vehicle_content = vehicle_content + "<option value="
								+ vehicle_value[0] + ">" + vehicle_value[1]
								+ "</option>";
					}
					
					$("#vehicle_sel").append(vehicle_content);
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown) {
				alert("Errors: " + errorThrown);
			}
		});
	}	
}

/*function condition() {
	jQuery.ajax( {
		url : "index.php?a=905",// 读取车辆组的名称数据
		type : "post",
		data : null,
		success : function(data) {
		    var vehicle_group_content="";
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
}*/




function reloadGrid() {

	$("#navgrid1").jqGrid().setGridParam({url : "index.php?a=902&group_id="+$("#vehicle_group")
		.val()+"&vehicle_id="+$("#vehicle_sel").val()+"&deal="+$("#data_condition").attr("checked"),page:1}).trigger("reloadGrid");

}

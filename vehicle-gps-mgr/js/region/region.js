$(document).ready(function() {
	$("#create_info").dialog({
		autoOpen : false,
		width : 360,
		height : 240,
		position : 'center'
	});

	$("#manage_region").dialog({
		autoOpen : false,
		width : 900,
		height : 450,
		position : 'center',
		stack : true
	});

	$("#manage_create").dialog({
		autoOpen : false,
		width : 360,
		height : 240,
		position : 'center'
	});

	$("#regiondiv").dialog({
		autoOpen : false,
		width : 800,
		height : 400,
		position : 'center'
	});
});

function create_region(xMin, yMin, xMax, yMax) {
	$("#region_name").val("");
	
	if($("#create_info").dialog("isOpen")){
		$("#create_info").dialog("close");
	}

	$("#xMin").val(xMin);
	$("#yMin").val(yMin);
	$("#xMax").val(xMax);
	$("#yMax").val(yMax);

	$("#create_info").dialog("open");
}

function update_region(xMin, yMin, xMax, yMax) {
	if($("#manage_create").dialog("isOpen")){
		$("#manage_create").dialog("close");
	}

	$("#create_xMin").val(xMin);
	$("#create_yMin").val(yMin);
	$("#create_xMax").val(xMax);
	$("#create_yMax").val(yMax);

	$("#manage_create").dialog("open");
}

function exam_region(id, xMin, yMin, xMax, yMax) {
	if($("#manage_region").dialog("isOpen")){
		$("#manage_region").dialog("close");
	}

	$("#manage_xMin").val(xMin);
	$("#manage_yMin").val(yMin);
	$("#manage_xMax").val(xMax);
	$("#manage_yMax").val(yMax);
	$("#manage_id").val(id);

	$("#manage_frame").attr("src", "templates/51map.html");
	$("#manage_region").dialog("open");
}

/*
 * 为区域指定车辆
 */
function set_vehicles(id) {
	$.post("index.php?a=1", function(data) {
		$("#regiondiv").html(data);
		$("#regiondiv").dialog("open");

		$("#sel_vehicle_commit").click(
				function() {
					var vehicles = $(".vehicle:checked");
					var str = "";
					
					vehicles.each(function(i) {
						if (i === (vehicles.length - 1)) {
							str = str + $(this).val();

							$.get("index.php?a=620&region_id=" + id + "&vehicle_ids=" + str, function(data) {
								$("#regiondiv").html("");
								$("#regiondiv").dialog("close");
								
								if ("success" === data) {
									alert("指定成功");
								} else {
									alert("指定失败，请稍候再试");
								}
							});
						} else {
							str = str + $(this).val() + ",";
						}
					});
				});
	});
}

function delete_region(id) {
	$.get("index.php?a=650&id=" + id, function(data) {
		if ("success" == data) {
			$("#" + id).remove();
		}
	})
}

$("#region_submit").click(function() {
	// 验证姓名格式
	var name = $("#region_name").val();
	$("span[name='name_message']").remove();

	if (name === "" || name === undefined) {
		$("#region_name").after("<span name='name_message'>姓名不能为空</span>");
		return false;
	}

	$("#region_mask").mask("正在加载,请稍候....");

	$.get("index.php?a=640", {
		region_name : $("#region_name").val(),
		xMin : $("#xMin").val(),
		yMin : $("#yMin").val(),
		xMax : $("#xMax").val(),
		yMax : $("#yMax").val()
	}, function(data) {
		$("#region_mask").unmask();

		if ("success" === data) {
			alert("保存成功");
			$("#create_info").dialog("close");
		}
	});
});

$("#manage_submit").click(function() {
	$("#operation").mask("正在加载，请稍候....");

	$.get("index.php?a=660", {
		region_id : $("#manage_id").val(),
		xMin : $("#create_xMin").val(),
		yMin : $("#create_yMin").val(),
		xMax : $("#create_xMax").val(),
		yMax : $("#create_yMax").val()
	}, function(data) {
		$("#operation").unmask();

		if ("success" === data) {
			alert("修改成功");
			$("#manage_create").dialog("close");
		}
	});
});
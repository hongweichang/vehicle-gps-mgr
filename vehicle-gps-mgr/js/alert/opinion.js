$(document).ready(function() {
	$("#opinion").dialog({ title:"处理意见", autoOpen: false, modal: false, width: 240, height: 310 });
});

function showOpinion(id,alertType,vehicle_id){
	$("#opinion").html("");
	$("#opinion").dialog({
		  height: 360,   
          width: 230
	});
	$("#opinion").dialog("open");
	$("#opinion").mask("处理中...");
	$.get("index.php?a=903&id="+id+"&alertType="+alertType+"&vehicleId="+vehicle_id, function(data) {
		$("#opinion").html(data);
		$("#opinion").unmask();
    });
}


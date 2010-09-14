$(document).ready(function() {
	$("#opinion").dialog({ title:"处理意见", autoOpen: false, modal: false, width: 240, height: 310 });
});

function showOpinion(id,alertType,vehicle_id,vehicle_number,alert_type_display){
	encodeURI(vehicle_number);
	encodeURI(alert_type_display);
	$("#opinion").html("");
	$("#opinion").dialog({
		  height: 390,   
          width: 340
	});
	$("#opinion").dialog("open");
	$("#opinion").mask("处理中...");
	$.get("index.php?a=903&id="+id+"&alertType="+alertType+"&vehicleId="+vehicle_id+"&vehicle_number="+vehicle_number+"&alert_type_display="+alert_type_display, function(data) {
		$("#opinion").html(data);
		
		$("#opinion").unmask();
		
    });
}


$(document).ready(function() {
	$("#opinion").dialog({ title:"处理意见", autoOpen: false, modal: false, width: 240, height: 280 });
});

function showOpinion(id){
	$("#opinion").html("");
	$("#opinion").dialog("open");
	$("#opinion").mask("处理中...");
	$.get("index.php?a=903&id="+id, function(data) {
		$("#opinion").html(data);
		$("#opinion").unmask();
    });
}

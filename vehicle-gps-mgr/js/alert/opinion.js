$(document).ready(function() {
	$("#opinion").dialog({ title:"处理意见", autoOpen: false, modal: true, overlay: { color: '#006' }, width: 450, height: 450 });
});

function showOpinion(id){
	$("#opinion").html("");
	$.get("index.php?a=903&id="+id, function(data) {
        $("#opinion").html(data);
    });
	$("#opinion").dialog("open");
}

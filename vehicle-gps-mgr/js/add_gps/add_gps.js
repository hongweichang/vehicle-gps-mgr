$(":button").button();

$("#commit_gps").click(function(){
	var gps_number = $("#add_gps_number").val();
	if(gps_number.length!=11 || !checkNum(gps_number)){
		alert("gps长度必须为11位数字");
		return;
	}
	$.get("index.php?a=7002&gps_number="+gps_number,function(data){
		if("add success"==data){
			alert("修改成功");
		}else{
			alert("修改失败");
		}
	});
});

function checkNum(str){
	return str.match(/\D/)==null;
} 
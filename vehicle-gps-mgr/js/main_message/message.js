$(document).ready(function(){
	$("#add_message").button();
	$("#add_message").click(function(){
		var message = $("#messages").val();
		$.post("index.php",{"a":8002,"messages":message},function(data){
			if("ok"==data){
				alert("添加成功");
				window.location.href="login.php";
			}else{
				alert("添加失败,请重试");
			}
		});
	});
});


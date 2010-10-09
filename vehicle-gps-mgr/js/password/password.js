$(":button").button();
$("#reset_password").button();


$("#pass_commit").click(function(){
	$("#old_show").html("");
	$("#new_one_show").html("");
	$("#new_two_show").html("");
	
	var old_password = $("#old_password").val();
	var new_password_one = $("#new_password_one").val();
	var new_password_two = $("#new_password_two").val();
	
	if(old_password==""){
		$("#old_show").html("不能为空");
		return;
	}
	
	if(new_password_one==""){
		$("#new_one_show").html("不能为空");
		return;
	}
	
	if(new_password_two==""){
		$("#new_two_show").html("不能为空");
		return;
	}
	
	if(new_password_one!=new_password_two){
		$("#new_two_show").html("两次密码不一致");
		return;
	}else{
		$("#delay").mask("正在处理........");
		$.post("index.php?a=5025",{old_pass:old_password,new_pass:new_password_one},function(data){
			if("ok"==data){
				$("#delay").unmask();
				alert("修改成功");
			}else if("old_wrong"==data){
				$("#old_show").html("旧密码错误");
				$("#delay").unmask();
			}else{
				$("#delay").unmask();
				alert("修改失败");
			}
		});
	}
});
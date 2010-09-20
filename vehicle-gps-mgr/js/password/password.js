$(":button").button();


$("#pass_commit").click(function(){
	var old_password = $("#old_password").val();
	var new_password_one = $("#new_password_one").val();
	var new_password_two = $("#new_password_two").val();
	
	if(new_password_one==new_password_two){
		$("#two_show").hide();
		$("#old_show").hide();
	}
	
	if(new_password_one!=new_password_two){
		$("#old_show").hide();
		$("#two_show").show();
		$("#two_show").text("两次密码不一致");
		return;
	}else{
		$.post("index.php?a=5025",{old_pass:old_password,new_pass:new_password_one},function(data){
			if("ok"==data){
				$("#old_show").hide();
				alert("修改成功");
			}else if("old_wrong"==data){
				$("#old_show").show();
				$("#old_show").text("旧密码错误");
			}else{
				$("#old_show").hide();
				alert("修改失败");
			}
		});
	}
});
$(":button").button();


$("#pass_commit").click(function(){
	var old_password = $("#old_password").val();
	var new_password_one = $("#new_password_one").val();
	var new_password_two = $("#new_password_two").val();
	
	if(new_password_one!=new_password_two){
		alert("两次密码不一致");
		return;
	}else{
		$.post("index.php?a=5025",{old_pass:old_password,new_pass:new_password_one},function(data){
			if("ok"==data){
				alert("修改成功");
			}else{
				alert("修改失败");
			}
		});
	}
});
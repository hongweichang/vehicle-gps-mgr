function $(objStr){return document.getElementById(objStr);}
window.onload = function(){
    //分析cookie值，显示上次的登陆信息
	var companyIdValue = getCookieValue("companyId");
     $("#companyId").val(companyIdValue);
	 
    var userNameValue = getCookieValue("userName");
     $("#userName").val( userNameValue);
	 
    var passwordValue = getCookieValue("password");
     $("#password").val(passwordValue);
	 
     var checkValue = getCookieValue("saveall");
     $("#saveall").attr("checked" , checkValue);
     
    //写入点击事件
     $("#loginCar").click( function()
     {
        if( $("#saveall").attr("checked")==true){  
			setCookie("companyId",$("#companyId").val(),24*30,"/");  
		 	setCookie("userName",$("#userName").val(),24*30,"/"); 
		 	setCookie("password",$("#password").val(),24*30,"/");
		 	setCookie("saveall",$("#saveall").attr("checked"),24*30,"/");
		 } else{
			 deleteCookie("companyId","/");  
			 deleteCookie("userName","/"); 
			 deleteCookie("password","/");
			 deleteCookie("saveall","/");
		 }
	});  
}
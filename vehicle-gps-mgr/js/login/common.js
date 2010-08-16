function $(objStr){return document.getElementById(objStr);}
window.onload = function(){
    //分析cookie值，显示上次的登陆信息
	var companyIdValue = getCookieValue("companyId");
     $("companyId").value = companyIdValue;
	 
    var userNameValue = getCookieValue("userName");
     $("userName").value = userNameValue;
	 
    var passwordValue = getCookieValue("password");
     $("password").value = passwordValue;
	 
     var checkValue = getCookieValue("saveall");
     $("saveall").checked = checkValue;
     
    //写入点击事件
     $("loginCar").onclick = function()
     {
        if( $("saveall").checked){  
			setCookie("companyId",$("companyId").value,24*30,"/");  
		 	setCookie("userName",$("userName").value,24*30,"/"); 
		 	setCookie("password",$("password").value,24*30,"/");
		 	setCookie("saveall",$("saveall").checked,24*30,"/");
		 } else{
			 deleteCookie("companyId","/");  
			 deleteCookie("userName","/"); 
			 deleteCookie("password","/");
			 deleteCookie("saveall","/");
		 }
	}  
}
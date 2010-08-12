function $(objStr){return document.getElementById(objStr);}
window.onload = function(){
    //分析cookie值，显示上次的登陆信息
	var companyIdValue = getCookieValue("companyId");
     $("companyId").value = companyIdValue;
	 
    var userNameValue = getCookieValue("userName");
     $("userName").value = userNameValue;
	 
    var passwordValue = getCookieValue("password");
     $("password").value = passwordValue;    
	 
    //写入点击事件
     $("loginCar").onclick = function()
     {
	 	var companyIdValue = $("companyId").value;
        var userNameValue = $("userName").value;
        var passwordValue = $("password").value;
	 	
            if( $("saveCompanyId").checked){  
				 setCookie("companyId",$("companyId").value,24,"/");  
             } 
			 if($("saveUserName").checked){
			 	setCookie("userName",$("userName").value,24,"/"); 
			 }
			  if($("savePassWord").checked) 
			 {
			 	setCookie("password",$("password").value,24,"/");
			 } 
		}  
}
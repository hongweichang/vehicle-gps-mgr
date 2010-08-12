<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>GPS智能车辆监控调度系统-登录</title>
	<link href="css/login.css" rel="stylesheet" />
	
	<script language="javascript" src="../js/login/cookie.js"></script>
	<script language="javascript" src="../js/login/common.js"></script>
	<script type="text/javascript">
		function mouseOver(){
			document.getElementById("loginCar").src='images/car.png';
			document.getElementById("loginCar").style.cursor='hand';
		}
		function mouseOut(){
			document.getElementById("loginCar").src='images/ycar.png';
		}
	
		function login(){
				document.getElementById("login_from").submit();
		}
	</script>
</head>

<body>
<form method="post" id="login_from" action="login_check.php" name='theForm'>
   <div class="body_div">
   		<input type="hidden" name="act" value="signin" />
		<div class="title"></div>
		<div class="content">
		    <div style="width:100%; height:7px;">
		  		<div style="background-image:url(images/login_r1_c1.jpg);width:156px; height:171px; float:left;"></div>
		   		<div style="background-image:url(images/login_r1_c2.jpg);width:149px; height:171px; float:left;"></div>
		   		<div style="background-image:url(images/login_r1_c3.jpg);width:168px; height:171px; float:left;"></div>
		  		<div style="background-image:url(images/login_r1_c4.jpg);width:184px; height:171px; float:left;"></div>
		   		<div style="background-image:url(images/login_r1_c5.jpg);width:148px; height:171px; float:left;"></div>
		   		<div style="background-image:url(images/spacer.gif);width:1px; height:171px; float:left;"></div>
		   </div>
	       <div>
		 		<div style="background-image:url(images/login_r2_c1.jpg);width:156px; height:180px; float:left;"></div>
		   		<div style="background-image:url(images/login_r2_c2.jpg);width:149px; height:180px; float:left;"></div>
		   		<div style="background-image:url(images/login_r2_c3.jpg);width:168px; height:180px; float:left;"></div>
		   		<div style="background-image:url(images/login_r2_c4.jpg);width:184px; height:180px; float:left;"></div>
		   		<div style="background-image:url(images/login_r2_c5.jpg);width:148px; height:180px; float:left;"></div>
		   		<div style="background-image:url(images/spacer.gif);width:1px; height:180px; float:left;"></div>
	       </div>
			<div class="conetnt_div">
				<div class="companyID" style="border:1px red solid;">
					<div class="float_text" style="border:1px red solid;">公司ID：</div>
					<div class="float_left" style="border:1px red solid;">
						<input type="text" name="companyloginid" id="companyId" class="text_password_input" />
						<input id="saveCompanyId" type="checkbox" checked="checked" value="" />
						<span style="font-size:12px; color:blue;">记住</span>
					</div>
				</div>
				<div class="input_div" style="border:1px red solid;">
					<div class="float_text" style="border:1px red solid;">用户ID：</div>
					<div class="float_left" style="border:1px red solid;">
						<input type="text" name="username" id="userName"  class="text_password_input"/>
						<input id="saveUserName" type="checkbox" checked="checked" value="" />
						<span style="font-size:12px; color:blue;">记住</span>
					</div>
				</div>
				<div class="input_div" style="border:1px red solid;">
					<div class="float_text" style="border:1px red solid;">密</div><div class="text" style="border:1px red solid;">码：</div>
					<div class="float_left" style="border:1px red solid;">
						<input type="password" name="password" id="password" class="text_password_input" />
						<input id="savePassWord" type="checkbox" value="" />
						<span style="font-size:12px; color:blue;">记住</span>
					</div>
				</div>
				<div class="submit_div">
				<input type="image" class="submit"  src="images/ycar.png" id="loginCar" onmouseover="mouseOver()" onmouseout="mouseOut()" onsubmit="login();"/> 
				</div>
			</div>
		</div>
	</div>
</form>
</body>
</html>
		
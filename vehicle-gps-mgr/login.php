<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS智能车辆监控调度系统-登录</title>
<link href="css/login.css" rel="stylesheet" />
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
				<div class="conetnt_div">
					<div class="companyID">
						<div class="float_text">公司ID：</div>
						<div class="float_left">
							<input type="text" name="companyloginid" class="text_password_input" />
						</div>
					</div>
					<div class="input_div">
						<div class="float_text">用户ID：</div>
						<div class="float_left">
							<input type="text" name="username"  class="text_password_input"/>
						</div>
					</div>
					<div class="input_div">
						<div class="float_text">密&nbsp;&nbsp;&nbsp;&nbsp;码：</div>
						<div class="float_left">
							<input type="password" name="password" class="text_password_input" />
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

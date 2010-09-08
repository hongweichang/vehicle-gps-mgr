<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>GPS智能车辆监控调度系统-登录</title>
	
	<link href="css/login.css" rel="stylesheet" />
	<link type="text/css" href="css/jquery.loadmask.small.css"  media="screen" rel="stylesheet" />
	
	<script language="javascript" src="../js/login/cookie.js"></script>
	<script language="javascript" src="../js/login/login.js"></script>
	<script language="javascript" src="../js/jquery-1.4.2.js" ></script>
	<script language="javascript" src="../js/jquery.loadmask.min.js" ></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#loginCar").mouseover(function(){
				document.getElementById("loginCar").src='images/car.png';
				document.getElementById("loginCar").style.cursor='hand';
			});
			$("#loginCar").mouseout(function(){
				document.getElementById("loginCar").src='images/ycar.png';
			});
			$("#loginCar").click(function(){
				$("#body").mask("验证中，请稍候...");
				$("#clue").html("");
				var companyId=document.getElementById("companyId").value;
				var userName=document.getElementById("userName").value;
				var password=document.getElementById("password").value;
				$.post("login_check.php?companyloginid="+companyId+"&username="+userName+"&password="+password,function(data){
					$("#body").unmask();
					if(data==1){
						document.location= "index.php?a=1003";
						$("#body").mask("页面跳转中，请稍候...");
					}else{
						$("#clue").html("<img src='images/sad.png' alt='禁止通行'/>公司ID或用户名或密码错误！");
					}
				});
			});
			
		});
		
	</script>
</head>

<body>
   <div id="body" class="body_div">
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
				<div class="companyID">
					<div class="float_text">公司ID：</div>
					<div class="float_left">
						<div class="common">
							<input type="text" name="companyloginid" id="companyId" class="text_password_input" />
						</div>
					</div>
				</div>
				<div class="input_div">
					<div class="float_text" >用户ID：</div>
					<div class="float_left" >
						<div style="height:21px; width:81px;float:left;">
							<input type="text" name="username" id="userName"  class="text_password_input"/>
						</div>
					</div>
				</div>
				<div class="input_div">
					<div class="float_text">密</div><div class="text">码：</div>
					<div class="float_left">
					    <div class="common">
							<input type="password" name="password" id="password" class="text_password_input" />
						</div>
						<div class="commonCheck">
							<input id="saveall" type="checkbox" value="" />
							<span style="color:#999999; font-weight:normal;">记住</span>
						</div>
					</div>
				</div> 
				<div class="submit_div" >
					<input type="image" class="submit"  src="images/ycar.png" id="loginCar"/> 
					<div id="clue" style="font-size:10px;color:red;"></div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
		
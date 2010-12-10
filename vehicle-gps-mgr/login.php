<?php 
	session_start();
	$user_id = $_SESSION["user_id"]; 
	$user_name = $_SESSION["user_name"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>GPS智能车辆监控调度系统-登录</title>
	
	<link href="css/login.css" rel="stylesheet" /> 
	<link type="text/css" href="css/jquery.loadmask.small.css"  media="screen" rel="stylesheet" />
	
	<style type="text/css">
			img, div, a, input { 
				behavior: url(images/css/resources/iepngfix.htc)
			}
	</style> 
	
	<script language="javascript" src="js/login/cookie.js"></script>
	<script language="javascript" src="js/login/login.js"></script>
	<script language="javascript" src="js/jquery-1.4.2.js" ></script>
	<script language="javascript" src="js/jquery.loadmask.min.js" ></script>
	
	<script type="text/javascript">
		var prohibitWayleave ="<img src='images/sad.png' alt='禁止通行'/>";
	    var page_skip="页面跳转中，请稍候...";
	    var in_validate="验证中，请稍候...";
	
		$(document).ready(function(){

			//登录验证
			$("#loginCar").click(function(){

				$("#login_model").mask(in_validate);
				$("#clue").html("");

				//获取登录参数
				var companyId=$("#companyId").val();
				var userName=$("#userName").val();
				var password=$("#password").val();

				//验证是否存在非法字符
				if(validate_chars(companyId,userName,password)) 
				{ 
					$("#login_model").unmask();
					$("#clue").html(prohibitWayleave+"公司ID或用户名或密码含有非法字符!"); 
				}else { 

					//提交登录 
					$.post("login_check.php",{
						companyloginid:companyId,
						username:userName,
						password:password
					},function(data,textStatus){
						
						$("#login_model").unmask();
						if(data==1){
							// document.location= "index.php?a=1003";
							 $("#login_model").mask(page_skip);
							 window.location.reload();
							 
						}else if(2==data){
							document.location= "index.php?a=1004";
							$("#login_model").mask(page_skip);
						}else{
							$("#clue").html(prohibitWayleave+"公司ID或用户名或密码错误！");
						} 
					}); 
				}
			});

			//验证是否存在非法字符
			function validate_chars(){
				var pat=new RegExp("[^a-zA-Z0-9\_\u4e00-\u9fa5]","i"); 
				
				var companyId = arguments[0];
				var userName = arguments[1];
				var password = arguments[2]; 
				 
				if(pat.test(companyId)==true){
					return true;
				}
				if(pat.test(userName)==true){
					return true;
				}
				if(pat.test(password)==true){
					return true;
				}
 				return false;
				
			}
			//全屏浏览
			$("#fullScreen").click(function(){ 
				 
				var height =window.screen.availHeight-57;
				var width = window.screen.width-7;
				window.opener = null;
				window.open('','_self');
				window.close();
				window.open ("index.php?a=100", "newwindow", "height="+height+", width="+width+
						 ", top=0, left=0, toolbar =no, menubar=no, scrollbars=yes,"+
						 " resizable=no, location=no, status=yes");
			});

			//正常浏览
			$("#normal").click(function(){
				window.open("index.php?a=100","_self");
			});
			
			$("#manager_center").click(function(){
				document.location= "index.php?a=1004";
			});
			
			$("#logout").click(function(){
				document.location= "index.php?a=1005";
			});
			
		});
 
		function more_message(){
			$("#show_div").show();
			var messages = $("#new_message").val();
			$("#show_message").text(messages);
		}

		function close_message(){
			$("#show_div").hide();
		}  
	</script>
</head>

<body>   
	<div class="body_div">
		<input type="hidden" name="act" value="signin" />
		<!-- logo -->
		<div class="top">		
			<?php 
				require_once 'include/interface_manage.php';
				
				echo "<div class='logo'><img src='".$image['image_url']."'/></div>";
				echo "<div class='title'>".$image['name']."</div>";
			?>
		</div>
		<div class="content mt5">
		<!-- 背景图 -->
			<div style="width:609px; height:100%;float:left; margin:0;padding:0;">
			 <div>
			   <div style="background-image:url(images/gps-login_r1_c1.png);width:92px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r1_c2.png);width:78px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r1_c3.png);width:80px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r1_c4.png);width:78px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r1_c5.png);width:87px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r1_c6.png);width:96px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r1_c7.png);width:98px; height:86px; float:left;"></div>
			  </div>
			  <div>
			   <div style="background-image:url(images/gps-login_r2_c1.png);width:92px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r2_c2.png);width:78px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r2_c3.png);width:80px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r2_c4.png);width:78px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r2_c5.png);width:87px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r2_c6.png);width:96px; height:86px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r2_c7.png);width:98px; height:86px; float:left;"></div>
			  </div>
			  <div>
			   <div style="background-image:url(images/gps-login_r3_c1.png);width:92px; height:78px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r3_c2.png);width:78px; height:78px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r3_c3.png);width:80px; height:78px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r3_c4.png);width:78px; height:78px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r3_c5.png);width:87px; height:78px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r3_c6.png);width:96px; height:78px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r3_c7.png);width:98px; height:78px; float:left;"></div>
			  </div>
			  <div>
			   <div style="background-image:url(images/gps-login_r4_c1.png);width:92px; height:101px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r4_c2.png);width:78px; height:101px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r4_c3.png);width:80px; height:101px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r4_c4.png);width:78px; height:101px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r4_c5.png);width:87px; height:101px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r4_c6.png);width:96px; height:101px; float:left;"></div>
			   <div style="background-image:url(images/gps-login_r4_c7.png);width:98px; height:101px; float:left;"></div>
			  </div>
			</div>
			<div class="content_right">
				<!--最新消息-->
				<div class="new_info">
					<ul>
						<li>★最新消息</li>
						<li class="mt5">
							<?php 
								require_once 'templates/new_message.php';
								
								$blank ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								
								$messages = $new_message['messages'];
								$count_message = mb_strlen($messages,"utf-8");
								if(mb_strlen($messages,"utf-8")>91){
									$sub_messages = substr($messages,0,270);
									echo "<textarea id='new_message' style='display:none;font-size:12px;'>".htmlspecialchars($messages)."</textarea>";
									echo $blank
										."<span style='line-height:15px;font-size:12px;'>"
											.htmlspecialchars($sub_messages)
										."</span>"
										.".... <a href=javascript:more_message()>查看更多</a>";		
								}else{
									echo $blank.htmlspecialchars($messages);
								}
							?>
						</li>
					</ul>
				</div>
				<!--表单-->
				<?php  
					if(empty($user_id)){
				?>
				<div class="form_info mt65" id="login_model">
					<ul>
						<li>
							公司ID：
							<span>
								<input type="text" name="companyloginid" id="companyId" class="input"/>
							</span>
						</li>
						<li class="mt10">
							用户ID：
							<span>
								<input type="text" name="username" id="userName"  class="input"/>
							</span>
						</li>
						<li class="mt10">
							密&nbsp;&nbsp;&nbsp;&nbsp;码：
							<span>
								<input type="password" name="password" id="password" class="input" />
							</span>
						</li>
						<li class="mt15 tc">
							 <input id="saveall" type="checkbox" value="" /> 
							<span class="remenber">记住</span>&nbsp;&nbsp;
							<span>
								<input type="submit" class="submit" id="loginCar" name="login" value="登录" />
								<div id="clue" style="font-size:10px;color:red;"></div>
							</span>
						</li>
					</ul>
				</div>
				<?php 
					}else{
				?>
				<div class="form_info mt65">
					<div style="font-size:13px;font-weight:100;text-align:left;color:#666666"> 
						<img src="/images/smiley.png" />
						<?php echo $user_name; ?> 您已经登录成功!<br><br>请选择以下选项：
					</div> 
					<ul style="height:30px;margin-top:20px;">
						<li class="fl " ><input type="button" id="fullScreen" class="button" value="全屏"/></li>
						<li class="fl ml5"><input type="button" id="normal"  class="button" value="非全屏"/></li>
					</ul>
					<ul style="height:30px;margin-top:4px;">
						<li class="fl  " ><input type="button" id="manager_center"  class="button" value="管理中心"/></li>
						<li class="fl  ml5"><input type="button" id="logout"  class="button" value="退出登录"/></li>
					</ul> 
				</div>
				<?php }?>
				<!--内容-->
				<div id="show_div" class="display_none" style="display:none;">
					<div class="show_message_title">
					<ul>
						<li class="show_title">详细信息</li>
						<li class="close_message" onclick="close_message()">关闭</li>
					</ul>
					</div>
					<div id="show_message" class="show_message"></div>
				</div>
			</div>
		</div>
		<div class="help_info mt5">
			<ul>
				<li>推荐使用ie浏览器。（使用ie8浏览器可以获得最佳的体验效果）</li>
				 
			</ul>
		</div>
	</div>
</body>
</html>

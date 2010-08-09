	
				var id=0;
				/**获得24小时内未处理的告警记录**/
	 			function alertInfo(){
	 				$.post("index.php",{
						 "a":921}
						,function(data){
							
							var array=data.split("|");
							if(array.length==0){
								$("#lamp").html("<img alt='警灯' src='images/lamp.jpg' style='height:56px; width:46px;'></img>");
								$("#record").html();
								$("#newAlert").html("最新告警记录");
								$("#record").html(data);
								$("#operate").html("<a href='index?a=901'>查看更多</a>");
							}else
							{       
								    id=array[0];
									$("#lamp").html("<img alt='警灯' src='images/lamp.gif' style='height:56px; width:46px;'></img>");
									$("#record").html();
									$("#newAlert").html("最新告警记录："+"在"+array[1]+"时间点内");
								    $("#record").html("车牌号为"+array[2]+"的告警记录为："+array[3]+"<bgsound balance='0' loop='2' src='voice/sound.mp3' volume='10' />");
									document.getElementById("operate").style.display="block";
						     }
						 }
					);
				  setTimeout(alertInfo,30000);
	 			}
	 			
	 			
	 			/**弹出层jquery代码**/
				$(document).ready(function() {
					$('.jsbutton').wrapInner('<span class="hover"></span>').css('textIndent','0')								
					 .each(function () {
						$('span.hover').css('opacity', 0).hover(function () {
							$(this).stop().fadeTo(650, 1);
						}, function () {
							$(this).stop().fadeTo(650, 0);
						});
					});
					$("#vehicle").append("<div class='button_font'>选择车辆</div>");
					$("#sendInfo").append("<div class='button_font'>发布信息</div>");
					$("#searchInfo").append("<div class='button_font'>查询信息</div>");
					$("#statistic").append("<div class='button_font'>统计分析</div>");
					$("#setting").append("<div class='button_font'>设置</div>");

					appearDiv("#vehicle");
					appearDiv("#sendInfo");
					appearDiv("#searchInfo");
					appearDiv("#statistic");
					appearDiv("#setting");
					alertInfo();
					appearDiv("#addAdvice");
					appearDiv("#lookMore");
					$("#record").html("正在加载数据，请稍后....");
				});
				 function appearDiv(function_Id){
					    $(function_Id).click(function(){ 
							if(function_Id=="#vehicle"){ 
								$.msgbox({
									closeIcon: '关闭', // closeIcon: {type:'image', content:'close.gif'}
									height:500,
									title:'车辆选择',
									width:1200,
									content:{type:'iframe', content:'index.php?a=1'}
								});
							}
							if(function_Id=="#sendInfo"){
								 $.msgbox({
											closeIcon: '关闭', // closeIcon: {type:'image', content:'close.gif'}
											height:500,
											title:'发布信息',
											width:1200,
											content:{type:'iframe', content:'index.php?a=201'}
										});
							}
								if(function_Id=="#searchInfo"){
									$searchInfo =$.msgbox({
											closeIcon: '关闭', // closeIcon: {type:'image', content:'close.gif'}
											height:500,
											title:'查询信息',
											width:1200,
											content:{type:'iframe', content:'index.php?a=301'}
										});
							}
							if(function_Id=="#statistic"){
								 $.msgbox({
											closeIcon: '关闭', // closeIcon: {type:'image', content:'close.gif'}
											height:500,
											title:'统计分析',
											width:1200,
											content:{type:'iframe', content:'index.php?a=401'}
										});
							}
							if(function_Id=="#setting"){
								 $.msgbox({
											closeIcon: '关闭', // closeIcon: {type:'image', content:'close.gif'}
											height:500,
											title:'设置',
											width:1200,
											content:{type:'iframe', content:'index.php?a=5017'}
										});
							}
							if(function_Id=="#addAdvice"){
								 $.msgbox({
											closeIcon: '关闭', // closeIcon: {type:'image', content:'close.gif'}
											height:500,
											title:'设置',
											width:1200,
											content:{type:'iframe', content:'index.php?a=903&id=id'}
										});
							}
							if(function_Id=="#lookMore"){
								 $.msgbox({
											closeIcon: '关闭', // closeIcon: {type:'image', content:'close.gif'}
											height:500,
											title:'告警列表',
											width:1200,
											content:{type:'iframe', content:'index.php?a=901'}
										});
							}
						});
					} 
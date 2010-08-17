/**
 * commpany  秦运恒  
 * date  2010-08-16 11:56
 * function 首页脚本函数库
 * author 叶稳
 * update 
 * modifier
 */


//子页面对象   iframe
var home_map =  document.getElementById("home_map_frame").contentWindow;

//初始化
$(document).ready(function() {
	/**展开动画效果**/
	$('#dock2').Fisheye(
			{
				maxWidth: 60,
				items: 'a',
				itemsText: 'span',
				container: '.dock-container2',
				itemWidth: 40,
				proximity: 80,
				alignment : 'left',
				valign: 'bottom',
				halign : 'center'
			});
		$("#location_info").draggable();
		
		$('#addAdvice').click(function(e) {
			$("#opinion").html("");
			$("#opinion").dialog("open");
			$("#opinion").mask("处理中...");
			$.post("index.php?a=903&id="+id,function(data){
				$("#opinion").html(data);
				$("#opinion").unmank();
			});
		});
 
		$( "#opinion" ).dialog({
			   close: function(event, ui) { alertInfo(); }
		});

		$('.console_btn').click(function(e) {
			e.preventDefault();
			showOperationDialog(this, $(this).attr('href'));
		});
		$('.button_font').click(function(e) {
			e.preventDefault();
			showOperationDialog(this, $(this).attr('url'));
		});

		$('.showMeInDialog').click(function(e) {
			e.preventDefault();
			showOperationDialog(this, $(this).attr('url'));   
		});

		//播放时间调整				
		$("#cur_run_time").slider();
		$("#cur_run_time").slider({ min:0,max:1000,step:10,animate:true}); 
		$("#cur_run_time").slider({ range: 'min' });
		 
		get_cur_run_time();
		
		$( "#cur_run_time" ).slider({
			slide: function(event, ui) {
					get_cur_run_time();
			   }
			});
		 
		$("#start_time").datetimepicker({
			ampm: false,//上午下午是否显示  
			timeFormat: 'hh:mm:ss',//时间模式  
			stepHour: 1,//拖动时间时的间隔  
			dateFormat: "yy/mm/dd", //日期格式设定  
			showHour: true,//是否显示小时，默认是true  
			showMinute: false,
			showSecond: false,
			createButton: false
		});
		
		$("#end_time").datetimepicker({
			ampm: false,//上午下午是否显示  
			timeFormat: 'hh:mm:ss',//时间模式  
			stepHour: 1,//拖动时间时的间隔  
			dateFormat: "yy/mm/dd", //日期格式设定  
			showHour: true,//是否显示小时，默认是true  
			showMinute: false,
			showSecond: false,
			createButton: false
		});
		 $(document).bind("contextmenu",function(e){
		        return false;
	});
				 		
	$('#addAdvice').click(function(e) {
		$("#opinion").html("");
		$("#opinion").dialog("open");
		$("#opinion").mask("处理中...");
		$.post("index.php?a=903&id="+id,function(data){
			$("#opinion").html(data);
			$("#opinion").unmask(); 
		})
	});
			
	/**动态生成车辆代表的速度**/		
	$.post("index.php",{
		 "a":5021}
		,function(data){
			
			var array=data.split("|");
			if(array.length==0){
				$("#header").unmask();
				alert("没有数据");
			}else
			{    
				var image="";
				for(var i=0;i<array.length-1;i++){
					var data_list_min=array[i].split(",");
					image=image+"<div style='float:left;heigth:17px;'>" +
							    "&nbsp;<img src="+data_list_min[0]+" style='width:25px;height:12px;margin-top:6px;'/>&nbsp;" 
							    +data_list_min[1]+"-"+data_list_min[2]+"</div>";
				}		
				$("#header").unmask();
				$("#carInfor").html(image);
		    }
		});
	 
	$('.jsbutton').wrapInner('<span class="hover"></span>').css('textIndent','0')								
	 .each(function () {
		$('span.hover').css('opacity', 0).hover(function () {
			$(this).stop().fadeTo(650, 1);
		}, function () {
			$(this).stop().fadeTo(650, 0);
		});
	});
	
	
	//加载导航条
	$("#vehicle").append("<div class='button_font'>选择车辆</div>");
	$("#sendInfo").append("<div class='button_font'>发布信息</div>");
	$("#searchInfo").append("<div class='button_font'>查询信息</div>");
	$("#statistic").append("<div class='button_font'>统计分析</div>");
	$("#setting").append("<div class='button_font'>设置</div>");

	alertInfo();
	$("#content").mask("告警正在查询中,请耐心等候...");
	$("#header").mask("车辆速度正在查询中,请耐心等候...");
});

	//获取播放运行时间
	function get_cur_run_time(){
		$("#show_time").html( $( "#cur_run_time" ).slider("option","value"));
	}	
	 
	var id=0;
	/**获得24小时内未处理的告警记录**/
	function alertInfo(){
		$.post("index.php",{
			 "a":921}
			,function(data){
				
				if("-1" == data){
					$("#lamp").html("<img alt='警灯' src='images/lamp.jpg' style='height:56px; width:46px;'></img>");
					$("#content").unmask();
					$("#newAlert").html("最新告警记录");
					$("#record").html("没有未处理的告警记录");
					$("#operate").html("<a href='index?a=901'><img alt='查看更多' src='images/lookMore.jpg' style='width:20px; height:19px;margin-left:5px;'></a>");
				}else
				{   
					var array=data.split("|");
				    id=array[0];
					$("#lamp").html("<img alt='警灯' src='images/lamp.gif' style='height:56px; width:46px;'></img>");
					$("#content").unmask();
					$("#newAlert").html("最新告警记录："+"在"+array[1]+"时间点内");
				    $("#record").html("车牌号为"+array[2]+"的告警记录为："+array[3]+"<bgsound balance='0' loop='2' src='voice/sound.mp3' volume='10' />");
					document.getElementById("operate").style.display="block";
			     }
			 }
		); 
	}
	 			 
			
	var id=0;
	/**获得24小时内未处理的告警记录**/
	function alertInfo(){
		$.post("index.php",{
			 "a":921}
			,function(data){
				
				if("-1" == data){
					$("#lamp").html("<img alt='警灯' src='images/lamp.jpg' style='height:56px; width:46px;'></img>");
					$("#content").unmask();
					$("#newAlert").html("最新告警记录");
					$("#record").html("没有未处理的告警记录");
					$("#operate").html("<a href='index?a=901'><img alt='查看更多' src='images/lookMore.jpg' style='width:20px; height:19px;margin-left:5px;'></a>");
				}else
				{   
					var array=data.split("|");
				    id=array[0];
					$("#lamp").html("<img alt='警灯' src='images/lamp.gif' style='height:56px; width:46px;'></img>");
					$("#content").unmask();
					$("#newAlert").html("最新告警记录："+"在"+array[1]+"时间点内");
				    $("#record").html("车牌号为"+array[2]+"的告警记录为："+array[3]);
					document.getElementById("operate").style.display="block";
			     }
			 }
		);
	  setTimeout("alertInfo()",30000);
	} 
	
	function showOperationDialog(htmlObj, url){
		var $this = $(htmlObj);
		var horizontalPadding = 0;
		var verticalPadding = 0;

		var showWidth = ($this.attr('showWidth')) ? $this.attr('showWidth') : '1000';
		var showHeight = ($this.attr('showHeight')) ? $this.attr('showHeight') : '400';
		$('#operation').css('overflow','hidden');//隐藏滚动条 
		$("#operation").dialog({
            title: ($this.attr('title')) ? $this.attr('title') : 'External Site',
    	            autoOpen: true,
    	            show:'blind',
        	        hide:'blind',
    	            width: showWidth,
    	            height: showHeight,
    	            modal: false,
    	            resizable: true,
    				autoResize: true
    	        }).width(showWidth - horizontalPadding).height(showHeight - verticalPadding);

		$( "#operation" ).dialog({
			   close: function(event, ui) { $("#operation").html(""); }
		});
	
		$( "#operation" ).mask("载入中...");

		$.post(url,function(data){
			$("#operation").html(data);

			$( "#operation" ).unmask();

			if($this.attr('id') == "sel_vehicle_btn"){
				$("#sel_vehicle_commit").click(function(){
		            var vehicles = $(".vehicle:checked");
		            var str="";
		            vehicles.each(function(i){
		                
		                	str = str+$(this).val()+",";
		                
		             });
		            if(str === null || str=== ""){
		            	alert("请选择您所需要定位的车辆!");
		            	return false;
		            }else{
		            	home_map.vehiclePosition(str.substr(0,str.length-1));
				   		closeDialog();
		            }
		            
					});
			 }
		});
	}

 	/**
	 * 关闭窗口
	 */
	function closeDialog(){
		$("#operation").dialog("close");
	}
	
	function getSendInfoDialog(obj, url){
		var $this = obj;
		var horizontalPadding = 0;
		var verticalPadding = 0;

		var showWidth = ($this.attr('showWidth')) ? $this.attr('showWidth') : '1000';
		var showHeight = ($this.attr('showHeight')) ? $this.attr('showHeight') : '400';
		$.post(url,function(data){
			$("#operation").html(data);

			$("#operation").dialog({
	            title: ($this.attr('title')) ? $this.attr('title') : 'External Site',
	    	            autoOpen: true,
	    	            width: showWidth,
	    	            height: showHeight,
	    	            modal: true,
	    	            resizable: true,
	    				autoResize: true,
	    	            overlay: {
	                    opacity: 0.5,
	                    background: "black"
	    	            }
	    	        }).width(showWidth - horizontalPadding).height(showHeight - verticalPadding);

			$( "#operation" ).dialog({
				   close: function(event, ui) { $("#operation").html(""); }
			});
		});
	}  
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
	//提示年检时间
	$.ajax({
		type: "get",
		url: window.parent.host+"/index.php?a=103",
		dataType: "json",
		success: function(data){
			if(data==null || data==""){
				$("#as_date").hide();
			}else{
				$("#as_date").show();
				var str="<div>";
				for(var i=0;i<data.length;i++){
					str = str+data[i]['number_plate']+"的年检时间为:"+data[i]['next_AS_date']+"&nbsp;&nbsp;<input type='button' id="+(i+1)*3+" class='modify_as'" +
							" value='修改时间'><br/><div id=tijiao"+(i+1)+" class='tijiao' style='display:none'><input type='text' id="+(i+1)*2+" class='new_as_date'><input type='button' id="+(i+1)+" class='commit_new_date'" +
							" value='确定' name="+data[i]['id']+"></div><br/>";
				}
				str = str+"</div><script language='javascript' src='/js/home_page/as_date.js' ></script>"
				$("#as_date").html(str);
				$("#as_date").dialog({height:150,width:350,title:'年检提示',
	                 autoOpen:true,position:[1200,900],hide:'blind',show:'blind'});
			}
		}
	});
	
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
				$("#opinion").unmask();
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

		//禁止查看源码
	/*$(document).bind("contextmenu",function(e){
		        return false;
	});*/
				 		
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
	 

	alertInfo();
	$("#content").mask("告警正在查询中,请耐心等候...");
	$("#header").mask("车辆速度正在查询中,请耐心等候...");
});

	

 	
	var id=0;
	/**获得24小时内未处理的告警记录**/
	function alertInfo(){
		$.post("index.php",{
			 "a":921}
			,function(data){
				
				if("-1" == data){
					$("#lamp").html("<img alt='警灯' src='images/lamp.png' style='height:56px; width:46px;'></img>");
					$("#content").unmask();
					$("#record").html("没有未处理的告警记录");
					$("#operate").html("<a href='index?a=901'><img alt='查看更多' src='images/lookMore.jpg' style='width:20px; height:19px;margin-left:5px;'></a>");
				}else
				{   
					var array=data.split("|");
				    id=array[0];
					$("#lamp").html("<img alt='警灯' src='images/lamp.gif' style='height:56px; width:46px;'></img>");
					$("#content").unmask();
				  $("#record").html("告警时间："+array[1]+"&nbsp;&nbsp;&nbsp;&nbsp;车牌号："+array[2]+"&nbsp;&nbsp;&nbsp;&nbsp;告警类型："+array[3]);
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
		$("#operation").html("");
		
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
    	            position:'center',
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
		            	home_map.refresh_vehicle_position(str.substr(0,str.length-1));
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
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
	show_as_date();
	
	/**处理告警**/
	$('#addAdvice').click(function(e) {
		showOpinion(id,alertTypeId,vehicle_id,alert_vehicle_num,alertType);
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
		
	
 
		$( "#opinion" ).dialog({
			   close: function(event, ui) { alertInfo(); }
		});

		$('.console_btn').click(function(e) {
			if($(this).attr("id")=="sel_vehicle_btn"){
				$("#vehicle_id_save").val();
				var href = $(this).attr("href");
				var ids = $("#vehicle_id_save").val();
				$(this).attr("href",href+"&vehicleIds="+ids);
			}
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
	/**动态生成车辆代表的速度**/		
	$("#header").mask("加载中...");
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
});

	 
	var id=0;
	var alertTypeId="";
	var alertType="";
	var alert_vehicle_num="";
	var vehicle_id=0;
	/**获得最新未处理的告警记录**/
	function alertInfo(){
		$("#content").mask("告警正在查询中,请耐心等候...");
		$.ajax({
			type:"POST",
			url:"index.php?a=921", 
			success:function(data){
					if(data=="conn_fail"){
						no_alertInfo();
					}else{
						if(data == "-1"){
							no_alertInfo();
						}else{   
								var array=data.split("|");
								id=array[0];
								alertTypeId=array[4];//获得告警类型的编号
								vehicle_id=array[5];//获得车辆id
								alertType=array[3];//告警类型
								alert_vehicle_num=array[2];//车牌号
								
								if(array[0]=="undefined" || array[1]=="undefined"||array[2]=="undefined" || array[3]=="undefined" || array[4]=="undefined" || array[5]=="undefined"){
									no_alertInfo();
								}else{
								$("#lamp").html("<img alt='警灯' src='images/lamp.gif' style='height:56px; width:46px;'></img>");
								$("#content").unmask();
								$("#record").html("告警时间："+array[1]+"&nbsp;&nbsp;&nbsp;&nbsp;车牌号："+array[2]+"&nbsp;&nbsp;&nbsp;&nbsp;告警类型："+array[3]);
								$("#lookMore").show();
								$("#addAdvice").show(); 
							}
						}
				  }
			 },
			 error:function (){
				 no_alertInfo();
			 }
		});
	  setTimeout("alertInfo()",60000);
	} 
	
	function no_alertInfo(){
		$("#lamp").html("<img alt='警灯' src='images/lamp.png' style='height:56px; width:46px;'></img>");
		$("#content").unmask();
		$("#record").html("没有未处理的告警记录");
		$("#lookMore").show();
	}
	

	//提示年检时间
function show_as_date(){
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
							" value='确定' style='height:24px;' name="+data[i]['id']+"></div><br/>";
				}
				str = str+"</div><script language='javascript' src='/js/home_page/as_date.js' ></script>"
				$("#as_date").html(str);
				$("#as_date").dialog({height:150,width:370,title:'年检提示',
	                 autoOpen:true,position:[1200,900],hide:'blind',show:'blind'});
			}
		}
	});
}
	
	function showOperationDialog(htmlObj, url, div_name){ 
		var div_param = "operation";
		
		if(div_name){
			div_param = div_name;
		}
		
		var $this = $(htmlObj);
		var horizontalPadding = 0;
		var verticalPadding = 0;
		$("#"+div_param).html("");
		
		var showWidth = ($this.attr('showWidth')) ? $this.attr('showWidth') : '1000';
		var showHeight = ($this.attr('showHeight')) ? $this.attr('showHeight') : '400';
		$('#'+div_param).css('overflow','hidden');//隐藏滚动条 
		
 
		$("#"+div_param).dialog({
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
		 

		$( "#"+div_param ).dialog({
			   close: function(event, ui) { 			       
			         $("#"+div_param).html("");
			   }
		});
		$( "#"+div_param ).mask("载入中...");
			
		$.post(url,function(data){
			$("#"+div_param).html(data);

			$( "#"+div_param ).unmask();
				 
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
				   		closeDialog(div_param);
		            }
		            
					});
			 }
		});
	}

 	/**
	 * 关闭窗口
	 */
	function closeDialog(param){
		$("#"+param).dialog("close");
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
	
	$(":button").button();
	
	function company_position_show(){
		$("#show_company_position").show();
		$("#show_company_position").dialog({height:140,width:340,title:'添加公司标注',
                autoOpen:true,position:[300,100],hide:'blind',show:'blind'});
	}
	
	//关闭公司标注输入框
	function company_position_close(){
		$("#show_company_position").dialog('close');
	}
	
	$("#cancle").click(function(){
		$("#show_company_position").dialog('close');
	});
	
	function update_position_show(){
		$("#update_company_position").show();
		$("#update_company_position").dialog({height:140,width:340,title:'修改公司标注',
                autoOpen:true,position:[300,100],hide:'blind',show:'blind'});
	}
	
	//关闭公司标注输入框
	function update_position_close(){
		$("#update_company_position").dialog('close');
	}
	
	$("#update_cancle").click(function(){
		$("#update_company_position").dialog('close');
	});
	
	

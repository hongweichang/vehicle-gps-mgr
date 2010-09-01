$(".modify_as").click(function(){
	var id = $(this).attr("id")/3;
	$("#tijiao"+id).show();
});

$(".commit_new_date").click(function(){
	var id = $(this).attr("name");
	var date_id = $(this).attr("id")*2;
	var real_date = $("#"+date_id).val();
	if(real_date=="" || real_date==null){
		alert("日期不能为空");
		return;
	}
	$.get("index.php?a=104&vehicle_id="+id+"&new_date="+real_date,function(data){
		alert(data);
	});
	$("#tijiao"+id).hide();
});

$(".new_as_date").datetimepicker({
	 ampm: false,//上午下午是否显示  
	 timeFormat: 'hh:mm:ss',//时间模式  
	 stepHour: 1,//拖动时间时的间隔  
	 dateFormat:"yy/mm/dd", //日期格式设定  
	 showHour: true,//是否显示小时，默认是true  
	 showMinute:false,
	 showSecond:false,
	 createButton:false
 });	
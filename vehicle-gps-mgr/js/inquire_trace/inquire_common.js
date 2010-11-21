$("#inquire_startTime").datetimepicker({
	 ampm: false,//上午下午是否显示  
	 timeFormat: 'hh:mm:ss',//时间模式  
	 stepHour: 1,//拖动时间时的间隔  
	 dateFormat:"yy/mm/dd", //日期格式设定  
	 showHour: true,//是否显示小时，默认是true  
	 showMinute:false,
	 showSecond:false,
	 createButton:false
});	 
 
$("#inquire_endTime").datetimepicker({
	 ampm: false,//上午下午是否显示  
	 timeFormat: 'hh:mm:ss',//时间模式  
	 stepHour: 1,//拖动时间时的间隔  
	 dateFormat:"yy/mm/dd", //日期格式设定  
	 showHour: true,//是否显示小时，默认是true  
	 showMinute:false,
	 showSecond:false,
	 createButton:false
});

$("#inquire_startTime").val(getTodayFormatDate()); //开始时间赋默认值
$("#inquire_endTime").val(getNowFormatDate()); //结束时间赋默认值

//获取系统当前时间并格式化为yyyy/mm/dd hh:mm:ss
function getNowFormatDate()
{
   	var day = new Date();

   	var Year = 0;
   	var Month = 0;
   	var Day = 0;
	var CurrentDate = "";
   //初始化时间
   	Year= day.getFullYear();
   	Month= day.getMonth()+1;
   	Day = day.getDate();
   	Hour = day.getHours();
 	Minute = day.getMinutes();
	Second = day.getSeconds();
   	CurrentDate += Year + "/";
   	if (Month >= 10){
    	CurrentDate += Month + "/";
   	}else{
    	CurrentDate += "0" + Month + "/";
   	}
   	if (Day >= 10 ){
    	CurrentDate += Day +" ";
   	}else{
    	CurrentDate += "0" + Day+" " ;
   	}
   	if(Hour>=10){
		CurrentDate+=Hour+":";
	}else{
		CurrentDate+="0"+Hour+":";
   	}if (Minute >= 10 ){
    	CurrentDate += Minute +":";
   	}else{
    	CurrentDate += "0" + Minute+":" ;
   	}
   	if(Second>=10){
		CurrentDate+=Second;
	}else{
		CurrentDate+="0"+Second;
   	}
   	return CurrentDate;
}


//字符串左侧补零函数
function padLeft(str,length){ 
	if(str.length >= length){
		return str;
	}
	else{ 
		return padLeft("0" +str,length); 
	}
} 

//得到起始时间和结束时间之间的小时列表，以便于分段向服务器请求位置信息
function getHourList(){
	var startTime = $("#inquire_startTime").attr("value");
	var endTime = $("#inquire_endTime").attr("value");
	 
	var startdt0 = new Date(Date.parse(startTime));
	var enddt0 = new Date(Date.parse(endTime));

	startdt = startdt0.getTime(); //得到用毫秒数表示的起始时间
	enddt = enddt0.getTime(); //得到用毫秒数表示的结束时间

	var hourList = new Array(); //定义小时列表，用来向服务器获取相应小时内的历史轨迹数据
	
	while(startdt <  enddt){
		var date = new Date();
		date.setTime(startdt);
	
		var hourStr = date.getFullYear() + "" + padLeft("" + (date.getMonth()+1),2) + "" + padLeft("" + date.getDate(),2) + "" + padLeft("" + date.getHours(),2);
		hourList.push(hourStr);
		
		startdt += 60*60*1000; //起始时间增加1小时
	}
	return hourList;
}


//从A列表中移除B列表中和A相同的元素
function removeElement(srcList, elementList){
	if(srcList.length >0 && elementList.length >0){
		for(i=0;i<srcList.length;i++){
			for(j=0;j<elementList.length;j++){
				if(srcList[i] === elementList[j]){
					srcList.splice(i,1); //从列表中删除当前元素
					i--;
				}
			}
		}
		return srcList;
	}
}

//把数组转为指定字符分割的字符串
function convertArrayToStr(srcArray, seperator){
	var str = "";
	for(i=0;i<srcArray.length;i++){
		if(str != ""){
			str += seperator;
		}

		str += srcArray[i];
	}
	
	return str;
}
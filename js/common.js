/**
 * 四舍五入
 * @param v 值
 * @param e 保留几位小数点
 * @return  返回四舍五入数组
 */
function   round(v,e)   
  {   
    var   t=1;   
    for(;e>0;t*=10,e--);   
    for(;e<0;t/=10,e++);   
    return   Math.round(v*t)/t;   
  } 

/**
 *  定位时间格式化
 *  @param location_time 定位时间
 */
 function  format_time(location_time,dataformat){
	 
	var year = location_time.substring(0,4);
	var month = location_time.substring(4,6);
	var day = location_time.substring(6,8);
	var hour = location_time.substring(8,10);
	var minutes = location_time.substring(10,12);
	var seconds = location_time.substring(12,14);
	
	var ms = "";
	if(dataformat=="yyyy/MM/DD/HH:mm:ss")
		ms = ":"+minutes+":"+seconds; 
	else if(dataformat=="yyyy/MM/DD/HH")
		ms = ":00:00";
	var time = year+"/"+month+"/"+day+" "+hour + ms;
	return time;
	 }



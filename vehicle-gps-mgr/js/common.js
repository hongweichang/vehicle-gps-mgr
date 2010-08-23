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



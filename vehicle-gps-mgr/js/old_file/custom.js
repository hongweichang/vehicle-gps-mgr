var error = false;	//pt，判断首页是否要切换,防止加载其他页面报错，可背景是空白。
/**
 *	关闭弹出框
 */
function hide_popup()
{
	//弹出框的id，每关闭一个递减
	float_id--;
	$("#floatBox"+float_id).remove();
	if ($(".floatBox").css("display")=="block")
	{
		$("#floatBoxBg").after($("#floatBoxBg").prev());
	}
	else
	{
		$("#floatBoxBg").remove();
	}
}

/**
 *	关闭弹出框,并刷新上一层
 *	$("#floatBoxBg").prev() bg框的上一个内容框
 *	after 将bg移到内容框后面
 */
function hide_popup_refresh()
{

	float_id--;							//float_id以一次调用后就加一了，所以此处要减一取当前弹出divid
	//alert(float_id);
	$("#floatBox"+float_id).remove();
	if ($(".floatBox").css("display")=="block")
	{
		//	load问题，load执行的顺序可能跟代码顺序不符，所以load完后的操作，写到，load的fun参数里面去
		var aurl = $("#floatBoxBg").prev().attr("aurl");
		//var shu_ju = get_url(aurl);
		//alert(shu_ju);
		$("#floatBoxBg").prev().load(
			aurl,"",
			function(){
				//$("#floatBoxBg").prev().html($("#floatBoxBg").prev().html()+"刷新");
				$("#floatBoxBg").after($("#floatBoxBg").prev());
			}
		);
	}
	else
	{
		//sy_div   pt_div
		if(right_type == 'PT')
		{
			dialog(right_url, "right_content");
			//$("#right_content").load(right_url);		//未完成
		}
		else
		{
			$("#floatBoxBg").remove();
			$("#sy_wai_div").load($("#sy_wai_div").attr("aurl"),"",function(){
				$("#pt_div").hide();
				$("#sy_wai_div").show();
			});
		}
	}
}
/**
 *	关闭弹出框，right_content跳转
 */
function goto_right_content(arr)
{
	$(".floatBox").each(function(){
		$(this).remove();
	});
	$("#floatBoxBg").remove();
	menu_click(arr[0],arr[1],arr[2]);
}
/**
 *	分页的时候，根据用户输入的数字，拼url
 */
function ch_url(obj)
{
	var aurl = $(obj).attr("aurl")+$(".fen_ye_list:last").val();
		dialog(aurl,"right_content");
		return false;
}

function jin_du_tiao() {
    alert(2);
    $(".jin_du_tiao").each(function(){
        alert(1);
        // 初始化进度 width=100
        var width_done = 100*parseInt(0.4);
        $(this).append("<div class='jin_du_css' style='width:"+width_done+"'></div>");

    });
}
/**
 *	倒计时
 *	value 秒数
 *	<span class="count_down" mes="截止" value="{{xun_lian_shi_jian}}" aurl></span>
 */
function count_down()
{
	$(".count_down").each(function(){
		var mes = $(this).attr("mes");
		var spantime  = $(this).attr("value");
		if(!spantime)
			spantime  = 0;
		if(spantime  <= 0)
		{
			$(this).html(mes);
			var type = $(this).attr("type");
			if(typeof type != "undefined")
			{
				if(type == "left")
					$("#data_xun_lian_ren_shu").load($(this).attr("aurl"));
			}
			var aurl = $(this).attr("aurl");
			if(typeof aurl != "undefined")
			{
				var popaurl = aurl.split("&");
				if (popaurl[0] == "i.php?a=15104")
				{
					dialog(aurl,"pop");
					$(this).removeClass("count_down");
				}
			}
			
			return;
		}
		spantime --;
		$(this).attr("value",spantime);

		var d = Math.floor(spantime / (24 * 3600));
		var h = Math.floor((spantime % (24*3600))/3600);
		var m = Math.floor((spantime % 3600)/(60));
		var s = Math.floor(spantime%60);
		var clock = "";
		//alert(d*24+h);
		if((d*24+h) < 10)
			clock += "0"+(d*24+h)+":";
		else
			clock += (d*24+h)+":";
		//clock+= d*24+h<=0?"0"+(d*24+h)+":":;//+"小时"
		if(m < 10)
			clock+="0"+m+":";
		else
			clock+=m+":";
		//clock+=m+":";	//+"分钟"
		if(s < 10)
			clock+="0"+s;
		else
			clock+=s;
		//clock+=s;	//+"秒"
		var content = $(this).attr("content");
		if(typeof content != "undefined")
			$(this).html(clock+content);
		else
			$(this).html(clock);
	});
}
/**
 *	倒计时（定时处理的js模块）
 *	value 秒数
 *	<span class="count_down_ding_shi" mes="截止" value="{{xun_lian_shi_jian}}"></span>
 *  type left刷新左侧
 *  content 完成后秒数后面拼内容
 *  php要返回 result:true/result:false,time:....差的时间

    echo '<span class="count_down_ding_shi" aurl="i.php?a=1124" mes="截止" value="3"></span>';

    $arr["result"] = false;
    $arr["time"] = 5;
    echo json_encode($arr);
    exit;
 */
function count_down_ding_shi()
{

    $(".count_down_ding_shi").each(function(i){
        // 初始化数据
        if(!$(this).hasClass("jin_du_tiao"))
        {
            $(this).addClass("jin_du_tiao");
        }
        if(!$(this).children().hasClass("jin_du_css"))
        {
            $(this).append("<div class='jin_du_css'></div>");
        }
        if(!$(this).children().children().hasClass("jin_du_time"))
        {
            $(this).children().append("<div class='jin_du_time'></div>");
        }

        var spantime = parseInt($(this).attr("value"));
        var maxtime  = parseInt($(this).attr("max"));

        var mes = $(this).attr("mes");
        if(!spantime)
            spantime  = 0;
        if(spantime  <= 0)
        {
             // 定时处理
            var aurl = $(this).attr("aurl");
            if(typeof aurl != "undefined")
            {
                var act_url_param = aurl.split("?");
                var count_obj = $(this);            // this用法注意
                $.ajax({
                    type: "POST",
                    url : act_url_param[0],
                    data:act_url_param[1],
                    success: function(html){
                        try{
                            var html_array = html.split('<!--|||-->');
                            obj   = eval('(' + html_array[0] + ')');			//转换成对象
                        }catch(e){
                            obj= {"result":"true"};
                        }
                        if(obj.result)
                        {
                            count_obj.removeClass("count_down_ding_shi");
                        }else
                        {
                            count_obj.attr("value",obj.time);
                            return;
                        }
                    }
                });
            }
            $(this).children(".jin_du_css").width("100%");
            $(this).children().children(".jin_du_time").html(mes);

            // 是否刷新
            var type = $(this).attr("type");
            if(typeof type != "undefined")
            {
                if(type == "left")
                    $("#data_xun_lian_ren_shu").load($(this).attr("aurl"));
            }
            return;
        }
        spantime --;
        $(this).attr("value",spantime);

        var d = Math.floor(spantime / (24 * 3600));
        var h = Math.floor((spantime % (24*3600))/3600);
        var m = Math.floor((spantime % 3600)/(60));
        var s = Math.floor(spantime%60);
        var clock = "";
        //alert(d*24+h);
        if((d*24+h) < 10)
            clock += "0"+(d*24+h)+":";
        else
            clock += (d*24+h)+":";
        //clock+= d*24+h<=0?"0"+(d*24+h)+":":;//+"小时"
        if(m < 10)
            clock+="0"+m+":";
        else
            clock+=m+":";
        //clock+=m+":";	//+"分钟"
        if(s < 10)
            clock+="0"+s;
        else
            clock+=s;
        //clock+=s;	//+"秒"
        //var content = $(this).attr("content");

        // 进度
        var percent = parseInt((maxtime-spantime)/maxtime*100);
        $(this).children(".jin_du_css").width(percent+"%");
        //if(typeof content != "undefined")
        //    $(this).children().children(".jin_du_time").html(clock);
       // else {
            $(this).children().children(".jin_du_time").html(clock);
        //}
    });
}

/**
 *   点击触发事件,ready作用域中函数外面调不到
 */
$(document).ready(function() {
	$(window).resize(function(){
		var container = $('div#floatBoxBg');
		if(typeof container != "undefined")
		{
			$("#floatBoxBg").css({height:$(document).height(),width:$(document).width()}).show();
		}
	});
    //setInterval(jin_du_tiao,1000);
	setInterval(count_down,1000);
    setInterval(count_down_ding_shi,1000);
//count_down_ding_shi();
	/**		【tooltips提示层】【插件】
	*
	*		功能    : 鼠标放在某个目标上面，出提示框。
	*		用法    : 1，定义属性为tips的控件。
					  2，页面事先定义好要显示的内容，内容id等于tips属性值
	*		注意	: 此处只定义span可以换为别的
	*		例子	: 1，<span tips="bbb" class='tt'>点这里</span>
							 <div id="bbb" style="display:none">
							 链接名称：{{link_name}}
							 </div>
	*/
	$(".tt").livequery(function(){
		$(this).tooltip({
			delay: 0,
			track: true,
			bodyHandler: function() {
                $("#tooltip").attr("style","");     //如果不置为空，样式第一次加载了就不变了
				return $("#"+$(this).attr("tips")).html();
			},
			showURL: false,
            success: function(){
                // 改模板div的大小即可
                var width = $("#tooltip").width();
                //alert(width);
                var helper_width = width > 250 ? 250 : width;
                $("#tooltip").css({width:helper_width});
            }
		});
	});
	// <span class='stt' mes=''></span>
	$(".stt").livequery(function(){
		$(this).tooltip({
			delay: 0,
			track: true,
			bodyHandler: function() {
                $("#tooltip").attr("style","");
				return $(this).attr("mes");
			},
			showURL: false,
            success: function(){
                // 改模板div的大小即可
                var width = $("#tooltip").width();
                //alert(width);
                var helper_width = width > 200 ? 200 : width;
                $("#tooltip").css({width:helper_width});
            }
		});
	});
	/**
	*	关闭弹出框，right_content跳转
	*/
	$(".goto").live("click", function(){
		var action_url = $(this).attr("aurl");
		goto_right_content(action_url.split(","));
		return false;
	});
	/**		【弹出层】
	*		功能	: 弹出层，层内容通过url取得
	*		用法	: 1，控件需要有aurl的属性来定义显示的路径
					  2，class是pop
	*		注意	: 此处只定义span可以换为别的
	*		例子	: 1，<span act_url="getuser.php?id=1" class="pop">弹出窗口</span>
					  2，<a act_url="getuser.php?id=1" href='javascript:void(0);' class="popup_inner">弹出窗口</a>
	*/
	$(".pop").live("click", function(){
		var action_url = $(this).attr("aurl");
		dialog(action_url,"pop");
		return false;
	});
	/**
	*	关闭当前，开启新的
	*/
	$(".cpop").live("click", function(){
		if ($(this).hasClass("pagego"))
		{
			var pagenum = "&p=" + $(".fen_ye_list:last").val();
		}
		else
		{
			var pagenum = "";
		}
		
		var action_url = $(this).attr("aurl");
		var queryString = "";
		// 当连接需要提交表单的时候
		if(typeof $(this).attr("frmid") != "undefined")
			queryString = $("#"+$(this).attr("frmid")).formSerialize();			//取所有表单数据并拼好
		action_url += "&haha=5&"+queryString+pagenum;
		dialog(action_url,"cpop");
		return false;
	});
	 /**
	 *		 class=cd    功能	: 关闭弹出框  close_div
	 */
	$(".cd").live("click",function(){
		hide_popup();
	});
	 /**
	 *		 class='cdr' 功能	: 关闭弹出框，并刷新上一层
	 */
	$(".cdr").live("click",function(){
		hide_popup_refresh();
	});
	 /**
	 *		 class='ccdr' 功能	: 关闭2个弹出框，并刷新上一层
	 */
	$(".ccdr").live("click",function(){
		hide_popup();
		hide_popup_refresh();
	});

	/*
	*	【提交表单】
	*/
	$("form").livequery("submit",function(){				//livequery插件，加载动态dom  1124
		action = $(this).attr("action");
		//$(this).blur();
		// 合法性验证
		var frm = true;																// 可提交
		$("textarea").each(function(){
			var str = $.trim($(this).val());
			if(str !="" && str != null )
			{
				if(isNaN(str))
				{
					if (is_political_words(str)||is_dirty_words(str))
					{
						frm = false;
					}
				}
			}
		});
		$(":text").each(function(){
			var str = $.trim($(this).val());
			if(str !="" && str != null )
			{
				if(isNaN(str))
				{
					if (is_political_words(str)||is_dirty_words(str))
					{
						frm = false;
					}
				}
			}
		});
		if(!frm)
        {
            dialog("i.php?a=1124","pop");
			return frm;
        }
		var queryString = $(this).formSerialize();			//取所有表单数据并拼好

		action_url = action+"&"+queryString;
		//alert(encodeURI(action_url));
		class_name = $(this).attr("goto");
		//alert(action_url);
		if(class_name == "right_content")
			dialog(action_url,"right_content");
		else if(class_name == "search")
		{
			//弹出框的id，每关闭一个递减
			dialog(action_url,"cpop");
		}
		else
			dialog(action_url,"pop");
		return false;
	});

	/*
	*	帮助
	*/
	$(".bang_zhu").click(function(){
		id_menu = initialtab[0];
		id_sub  = initialtab[1];
		//alert(id_menu+"!!"+id_sub);
		url = $(this).attr("aurl");
		$(this).attr("href",url+"&id="+id_menu+"_"+id_sub);
		//return false;
	})
	/*
	*	刷新左侧
	*/

	/*
	*	【连接更新】
		$html["err"] = "Y";
		$html["msg"] = "123";
		echo json_encode($html);
		exit;
		//异步处理
	*/
	$(".pt").live("click",function(){
		if ($(this).hasClass("pagego"))
		{
			var pagenum = "&haha=5&p=" + $(".fen_ye_list:last").val();
		}
		else
		{
			var pagenum = "&haha=5&";
		}
		var aurl = $(this).attr("aurl") + pagenum;
		dialog(aurl,"right_content");
		return false;
	});
});
/**
*	首页时间
*/
//<!-- Begin
function sys_clock()
{
	sec = SEC+a;                   //(GMT+8:00)时区:中国标准时间
	S=sec%60;                                               //秒
	I=Math.floor(sec/60)%60;                                //分
	H=Math.floor(sec/3600)%24;                              //时
	if(S<10) S='0'+S;
	if(I<10) I='0'+I;
	if(H<10) H='0'+H;
	if (H=='00' & I=='00' & S=='00') D=D+1;                 //日进位
	//------------------------------------------------------判断是否为二月份******
	if (M==2)
	{
		//--------------------------------------------------是闰年(二月有29天)
		if (Y%4==0 & Y%100!=0 || Y%400==0)
		{
			if (D==30){M=M+1;D=1;}                          //月份进位
		}
		//--------------------------------------------------非闰年(二月有28天)
		else
		{
			if (D==29){M=M+1;D=1;}                          //月份进位
		}
	}
	//------------------------------------------------------不是二月份的月份******
	else
	{
		//--------------------------------------------------小月(30天)
		if (M==4 || M==6 || M==9 || M==11)
		{
			if (D==31) {M=M+1;D=1;}                         //月份进位
		}
		//--------------------------------------------------大月(31天)
		else
		{
			if (D==32){M=M+1;D=1;}                          //月份进位
		}
	}
	if (M==13) {Y=Y+1;M=1;}                             //年份进位
	M = Math.abs(M);
	D = Math.abs(D);
	if(M<10) M='0'+M;
	if(D<10) D='0'+D;
	timeStr = Y + "-" + M + "-" + D + " " + H + ":" + I + ":" + S;
	document.getElementById('sj_xi_tong').innerHTML = timeStr;
	a++;
}
//  End -->
/*
*	首页标签
*/
/*
舌签构造函数【如果此函数失效，请修改页面控件id命名】
SubShowClass(ID[,eventType][,defaultID][,openClassName][,closeClassName])
version 1.30
*/
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('m 6(a,b,c,d,e){5.u=6.$(a);7(5.u==k&&a!="n"){M y N("6(p)参数错误:p 对像存在!(Z:"+a+")")};7(!6.q){6.q=y 1r()};5.p=6.q.9;6.q.1d(5);5.1s=r;5.8=[];5.10=c==k?0:c;5.E=5.10;5.11=d==k?"1t":d;5.13=e==k?"":e;5.F=r;j f=P("6.q["+5.p+"].F = z");j g=P("6.q["+5.p+"].F = r");7(a!="n"){7(5.u.v){5.u.v("14",f)}w{5.u.Q("15",f,r)}};7(a!="n"){7(5.u.v){5.u.v("16",g)}w{5.u.Q("17",g,r)}};7(18(b)!="1u"){b="1v"};b=b.1w();1x(b){U"14":5.B="15";G;U"16":5.B="17";G;U"1y":5.B="1z";G;U"1A":5.B="1B";G;1C:5.B="1D"};5.H=r;5.A=k;5.R=1E};6.t.1F="1.1G";6.t.1H="1I";6.t.1e=m(a,b,c,d,e){7(6.$(a)==k&&a!="n"){M y N("1e(1f)参数错误:1f 对像存在!(Z:"+a+")")};j f=5.8.9;7(c==""){c=k};5.8.1d([a,b,c,d,e]);j g=P(\'6.q[\'+5.p+\'].C(\'+f+\')\');7(a!="n"){7(6.$(a).v){6.$(a).v("1J"+5.B,g)}w{6.$(a).Q(5.B,g,r)}};7(f==5.10){7(a!="n"){6.$(a).V=5.11};7(6.$(b)){6.$(b).J.W=""};7(5.p!="n"){7(c!=k){5.u.J.1g=c}};7(d!=k){S(d)}}w{7(a!="n"){6.$(a).V=5.13};7(6.$(b)){6.$(b).J.W="n"}};j h=P("6.q["+5.p+"].F = z");j i=P("6.q["+5.p+"].F = r");7(6.$(b)){7(6.$(b).v){6.$(b).v("14",h)}w{6.$(b).Q("15",h,r)};7(6.$(b).v){6.$(b).v("16",i)}w{6.$(b).Q("17",i,r)}}};6.t.C=m(a,b){7(18(a)!="19"){M y N("C(1h)参数错误:1h 不是 19 类型!(Z:"+a+")")};7(b!=z&&5.E==a){K};j i;T(i=0;i<5.8.9;i++){7(i==a){7(5.8[i][0]!="n"){6.$(5.8[i][0]).V=5.11};7(6.$(5.8[i][1])){6.$(5.8[i][1]).J.W=""};7(5.p!="n"){7(5.8[i][2]!=k){5.u.J.1g=5.8[i][2]}};7(5.8[i][3]!=k){S(5.8[i][3])}}w 7(5.E==i||b==z){7(5.8[i][0]!="n"){6.$(5.8[i][0]).V=5.13};7(6.$(5.8[i][1])){6.$(5.8[i][1]).J.W="n"};7(5.8[i][4]!=k){S(5.8[i][4])}}};5.E=a};6.t.1a=m(){7(s.9!=5.8.9){M y N("1a()参数错误:参数数量与标签数量不符!(9:"+s.9+")")};j a=0,i;T(i=0;i<s.9;i++){a+=s[i]};j b=1K.1a(),1b=0;T(i=0;i<s.9;i++){1b+=s[i]/a;7(b<1b){5.C(i);G}}};6.t.1i=m(){7(s.9!=5.8.9){M y N("1i()参数错误:参数数量与标签数量不符!(9:"+s.9+")")};7(!(/^\\d+$/).1j(6.D)){K};j a=0,i;T(i=0;i<s.9;i++){a+=s[i]};j b=6.D%a;7(b==0){b=a};j c=0;T(i=0;i<s.9;i++){c+=s[i];7(c>=b){5.C(i);G}}};6.t.1L=m(a){7(18(a)=="19"){5.R=a};X(5.A);5.A=1c("6.q["+5.p+"].Y()",5.R);5.H=z};6.t.Y=m(){7(5.H==r||5.F==z){K};5.1k()};6.t.1k=m(){j a=5.E;a++;7(a>=5.8.9){a=0};5.C(a);7(5.H==z){X(5.A);5.A=1c("6.q["+5.p+"].Y()",5.R)}};6.t.1M=m(){j a=5.E;a--;7(a<0){a=5.8.9-1};5.C(a);7(5.H==z){X(5.A);5.A=1c("6.q["+5.p+"].Y()",5.R)}};6.t.1N=m(){X(5.A);5.H=r};6.$=m(a){7(x.1l){K S(\'x.1l("\'+a+\'")\')}w{K S(\'x.1O.\'+a)}};6.1m=m(l){j i="",I=l+"=";7(x.L.9>0){j a=x.L.1n(I);7(a!=-1){a+=I.9;j b=x.L.1n(";",a);7(b==-1)b=x.L.9;i=1P(x.L.1Q(a,b))}};K i},6.1o=m(O,o,l,I){j i="",c="";7(l!=k){i=y 1p((y 1p).1R()+l*1S);i="; 1T="+i.1U()};7(I!=k){c=";1V="+I};x.L=O+"="+1W(o)+i+c};6.D=6.1m("1q");7((/^\\d+$/).1j(6.D)){6.D++}w{6.D=1};6.1o("1q",6.D,12);',62,121,'|||||this|SubShowClass|if|label|length||||||||||var|null||function|none||ID|childs|false|arguments|prototype|parentObj|attachEvent|else|document|new|true|autoPlayTimeObj|eventType|select|sum|selectedIndex|mouseIn|break|autoPlay||style|return|cookie|throw|Error||Function|addEventListener|spaceTime|eval|for|case|className|display|clearInterval|autoPlayFunc|value|defaultID|openClassName||closeClassName|onmouseover|mouseover|onmouseout|mouseout|typeof|number|random|percent|setInterval|push|addLabel|labelID|background|num|order|test|nextLabel|getElementById|readCookie|indexOf|writeCookie|Date|SSCSum|Array|lock|selected|string|onmousedown|toLowerCase|switch|onclick|click|onmouseup|mouseup|default|mousedown|5000|version|30|author|mengjia|on|Math|play|previousLabel|stop|all|unescape|substring|getTime|3600000|expires|toGMTString|domain|escape'.split('|'),0,{}))
/* 081104001 ws end */
function PLabel(SubObjID,SubName){
	var SubObj = SubShowClass.childs[SubObjID];
	SubObj.previousLabel();
	ChkArr(SubObjID,SubName);
}
function NLabel(SubObjID,SubName){
	var SubObj = SubShowClass.childs[SubObjID];
	SubObj.nextLabel();
	ChkArr(SubObjID,SubName);
}
function ChkArr(SubObjID,SubName){
	var SubObj = SubShowClass.childs[SubObjID];
	if(SubObj.selectedIndex == 0){
		SubShowClass.$("SSArr_" + SubName + "_L").className = "arrLeft_d";
		SubShowClass.$("SSArr_" + SubName + "_L").onclick = null;
	}else{
		SubShowClass.$("SSArr_" + SubName + "_L").className = "arrLeft";
		SubShowClass.$("SSArr_" + SubName + "_L").onclick = Function("PLabel(" + SubObjID + ",'" + SubName + "')");
	};
	if(SubObj.selectedIndex >= SubObj.label.length - 1){
		SubShowClass.$("SSArr_" + SubName + "_R").className = "arrRight_d";
		SubShowClass.$("SSArr_" + SubName + "_R").onclick = null;
	}else{
		SubShowClass.$("SSArr_" + SubName + "_R").className = "arrRight";
		SubShowClass.$("SSArr_" + SubName + "_R").onclick = Function("NLabel(" + SubObjID + ",'" + SubName + "')");
	};
}

try{document.execCommand('BackgroundImageCache', false, true);}catch(e){}
/*
*	首页滚动提示
*/
var marqueeInterval=new Array();
var marqueeId=0;
var marqueeDelay=2000;
var marqueeHeight=16;
function initMarquee() {
	var str=marqueeContent[0];
	document.write('<div id="marqueeBox" style="overflow:hidden;width:180px;height:'+marqueeHeight+'px" onmouseover="clearInterval(marqueeInterval[0])" onmouseout="marqueeInterval[0]=setInterval(\'startMarquee()\',marqueeDelay)"><div>'+str+'</div></div>');
	marqueeId++;
	marqueeInterval[0]=setInterval("startMarquee()",marqueeDelay);
}
function startMarquee() {
	var str=marqueeContent[marqueeId];
	marqueeId++;
	if(marqueeId>=marqueeContent.length) marqueeId=0;
	if(document.getElementById("marqueeBox").childNodes.length==1) {
	var nextLine=document.createElement('DIV');
	nextLine.innerHTML=str;
	document.getElementById("marqueeBox").appendChild(nextLine);
	}
	else {
	document.getElementById("marqueeBox").childNodes[0].innerHTML=str;
	document.getElementById("marqueeBox").appendChild(document.getElementById("marqueeBox").childNodes[0]);
	document.getElementById("marqueeBox").scrollTop=0;
	}
	clearInterval(marqueeInterval[1]);
	marqueeInterval[1]=setInterval("scrollMarquee()",20);
}
function scrollMarquee() {
	document.getElementById("marqueeBox").scrollTop++;
	if(document.getElementById("marqueeBox").scrollTop%marqueeHeight==(marqueeHeight-1)){
	clearInterval(marqueeInterval[1]);
	}
}


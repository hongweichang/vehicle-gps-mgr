
function assignToAdvice(){
	var defaul=$('#defaultAdvice option:selected').text();
	$("#advice").text(defaul);	
}
function clearText(){
	$("#advice").text("");	
}

function adviceDialog(id,length,str){

    jQuery("#dialog").dialog({
			bgiframe: true,
			autoOpen: true, 
			height: 375, 
			width: 280,
			modal: true,
			title: '填写告警处理意见',
			buttons: {
			    '取消': function() {
					jQuery(this).dialog('close');
			  },'提交': function() { 
				      var val = $("#advice").val(); 
				      if(val==""||val==null){
					      alert("请填写告警处理意见");
					      return false;
				      }else{
				    	  jQuery.ajax({
								url : "index.php?a=903",								
								type : "post", 
								data:"advice="+val+"&id="+id, 
								success : function(data) { 
								           if(data == "success"){							
								              location.href='index.php?a=901'; //刷新当前页面.
								           }else  if(data == "fail"){									           
								              alert("添加处理意见失败！"); 
								           }
								},error : function(XMLHttpRequest,textStatus,errorThrown){
							                  alert("Errors: "+errorThrown);
							                  jQuery(this).dialog('close');
							    }
							}); 
				      }       	    
				}
            },open:function(event,ui){
			     var selHtml="";
            	 var arr  = str.split("|");

                 for(var i=0;i<length;i++){
                     var a=arr[i].split(",");
                     selHtml=selHtml+"<option value="+a[0]+">"+a[1]+"</option>";             
                 }
            	
            	              
            	jQuery('body').css('overflow','hidden');
		        jQuery("#canAdviceSelectDiv").html("<font style='font-weight:bold'>默认告警处理意见:</font>"+
				       "<div margin-top='3'><select id='defaultAdvice' style='width:100px' onchange='assignToAdvice()'>"+selHtml+"</select></div><br>"+
				       "<font style='font-weight:bold'>您的告警处理意见:</font><br>"+
				       "<textarea style='OVERFLOW:hidden;"+
				       "border-style:groove; height:160px; width:200px;' name='advice' id='advice' onFocus='clearText();' >"+
				        "--告警处理意见--</textarea> "); 
            }
	 });

}

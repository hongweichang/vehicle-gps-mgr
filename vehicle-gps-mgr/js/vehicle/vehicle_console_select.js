
	$("#tabs").tabs(); //加载JQUERY UI				

			//选择本组所有车辆
	        $(".selectall").click(function(){
				if($(this).attr("checked")==true){
				    $("input[name='"+$(this).attr("value")+"']").each(function(){
					    $(this).attr("checked",true);
					});
				}else{
				      $("input[name='"+$(this).attr("value")+"']").each(function(){
					    $(this).attr("checked",false);
				});
				}
			});

	        //选择所有车辆
			$("#all").click(function(){
			     if($(this).attr("checked")==true){
				     $(".vehicle").each(function(){
					     $(this).attr("checked",true);
					 });
				     $(".selectall").each(function(){
					     $(this).attr("checked",true);
					 });
				 }else{
				     $(".vehicle").each(function(){
					     $(this).attr("checked",false);
					 });
				     $(".selectall").each(function(){
					     $(this).attr("checked",false);
					 });
				 }
			});

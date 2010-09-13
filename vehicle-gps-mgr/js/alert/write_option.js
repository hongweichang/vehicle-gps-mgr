function setAdvice(){
			var opinion = $("#preparedOpinion option:selected").text(); 
		 	$("#advice").text(opinion);
		}

		setAdvice();
		 
		$("#preparedOpinion").change(function(){
			setAdvice();
		});

		$("#addOpinion").click(function(){

			$("#opinion").mask("修改中,请耐心等候...");
			var totalDeal=$("#totalDeal").attr('checked');
	         
			//得到告警时间
			var type=$("#alertType").val();
			var id = $("#alertId").val();
			var vehicleID=$("#vehicleId").val();
			$.post("index.php",{
				"a":904,
				"id":id,
				"type":type,
				"vehicleID":vehicleID,
				"totalDeal":totalDeal,
				"advice":$("#advice").text()},
				function(data){					 
					$("#opinion").unmask();
					alert(data);
					if("意见添加成功"==data){
						$("#opinion").dialog('close');
					}
			});
		});

		$("#return").click(function(){
			$("#opinion").dialog("close");
		});
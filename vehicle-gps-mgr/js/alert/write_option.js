function setAdvice(){
			var opinion = $("#preparedOpinion option:selected").text(); 
		 	$("#advice").text(opinion);
		}

		setAdvice();
		 
		$("#preparedOpinion").change(function(){
			setAdvice();
		});

		$("#addOpinion").click(function(){
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
				$("#result").text(data);
			});
		});

		$("#return").click(function(){
			$("#opinion").dialog("close");
		});
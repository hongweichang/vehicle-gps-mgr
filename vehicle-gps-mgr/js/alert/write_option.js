function setAdvice(){
			var opinion = $("#preparedOpinion option:selected").text(); 
		 	$("#advice").text(opinion);
		}

		setAdvice();
		 
		$("#preparedOpinion").change(function(){
			setAdvice();
		});

		$("#addOpinion").click(function(){
			var id = $("#alertId").val();
			$.post("index.php",{
				"a":904,
				"id":id,
				"advice":$("#advice").text()},
				function(data){
				$("#result").text(data);
			});
		});

		$("#return").click(function(){
			$("#opinion").dialog("close");
		});
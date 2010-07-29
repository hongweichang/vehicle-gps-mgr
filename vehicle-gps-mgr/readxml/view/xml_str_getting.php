<?php
require ("./../genera_class/GetStr.php");//导入读取信息类
?>
<html>
<head>
<title>根据表明，列名，信息值，读取信息内容</title>
</head>
<body>

<!-- area_info type -->
      <?php
						$isarea = new GetStr();
						$str = $isarea->resolve ( "area_info", "type",2);					
						echo $str;
	  ?>
  
</body>
</html>


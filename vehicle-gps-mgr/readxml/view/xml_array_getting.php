<?php
require ("./../genera_class/Xmlfile_resolve.php");//导入读取信息类
?>
<html>
<head>
<title>根据表名列名读取xml内容</title>
</head>
<body>
<select>
<!-- area_info type -->
      <?php
						$isarea = new Xmlfile_resolve();
						$array = $isarea->resolve ( "area_info", "type" );
						
						foreach ( $array as $key => $value ) {
							echo "<option value='" . $value->val . "'>" . $value->text . "</option>";
						}
						
	  ?>
   </select>
</body>
</html>

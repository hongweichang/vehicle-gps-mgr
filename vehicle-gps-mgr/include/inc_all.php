<?
//if ($_REQUEST["id"] == $_COOKIE["id"])
//{$_REQUEST["id"]="";}
//if ($_GET["id"])
//{$_REQUEST["id"]=$_GET["id"];}
//if ($_POST["id"])
//{$_REQUEST["id"]=$_POST["id"];}

	GLOBAL $db,$all;

	//		确定根目录在什么地方
	$sname = $_SERVER["SCRIPT_NAME"];
	$slash_pos = strpos($sname,"/",2);
	$all["BASE"] = $_SERVER["DOCUMENT_ROOT"].substr($sname,0,$slash_pos+1);

	require_once("include/config.php");
	require_once("include/memcache.class.php");
	require_once("include/db.php");
	require_once("include/base.class.php");
	require_once("include/system.php");
	require_once("include/common.php");
	
	$db = new MySQL();

	//	开始session
	session_start();	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<script type='text/javascript' src='js/jquery.js'></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<link href="css/main.css" rel="stylesheet" type="text/css" />

<!-- jqgrid -->
<!-- 加载 css 文件库 -->
<link rel="stylesheet" type="text/css" media="screen" href="css/redmond/jquery-ui-1.8.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />

<!-- 加载 js 文件库 -->
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
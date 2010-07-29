<?php
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
	require_once("include/common.php");
	//xml
	require_once("readxml/genera_class/GetStr.php");
	require_once("readxml/genera_class/Xmlfile_resolve.php");
	require_once("readxml/entity/AllEntityInfo.php");
	require_once("readxml/xml.class.php");
	$db = new MySQL();

	//	开始session
	session_start();	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS车辆定位服务系统 - 管理中心</title>

<script type='text/javascript' src='js/jquery.js'></script>
<!-- jqgrid -->
<!-- 加载 css 文件库 -->
<link rel="stylesheet" type="text/css" media="screen" href="css/redmond/jquery-ui-1.8.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />

<!-- 加载 js 文件库 -->
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.1.custom.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-timepicker-addon-0.5.js" type="text/javascript"></script>
<script src="js/jquery.layout.js" type="text/javascript"></script>

<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<link href="css/main.css" rel="stylesheet" type="text/css" />

<style>
html, body {
	margin: 0;			/* Remove body margin/padding */
	padding: 0;
	overflow: hidden;	/* Remove scroll bars on browser window */	
    font-size: 75%;
}
/*Splitter style */


#LeftPane {
	/* optional, initial splitbar position */
	overflow: auto;
}
/*
 * Right-side element of the splitter.
*/

#RightPane {
	padding: 2px;
	overflow: auto;
}
.ui-tabs-nav li {position: relative;}
.ui-tabs-selected a span {padding-right: 10px;}
.ui-tabs-close {display: none;position: absolute;top: 3px;right: 0px;z-index: 800;width: 16px;height: 14px;font-size: 10px; font-style: normal;cursor: pointer;}
.ui-tabs-selected .ui-tabs-close {display: block;}
.ui-layout-west .ui-jqgrid tr.jqgrow td { border-bottom: 0px none;}
.ui-datepicker {z-index:1200;}
.rotate
    {
        /* for Safari */
        -webkit-transform: rotate(-90deg);

        /* for Firefox */
        -moz-transform: rotate(-90deg);

        /* for Internet Explorer */
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }

</style>
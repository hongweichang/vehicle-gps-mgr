<?php
session_start();
$_SESSION["login_verify"]=$_REQUEST["content"];

header ("Content-type: image/png");
$im = @imagecreatetruecolor (45, 20)
      or die ("Cannot Initialize new GD image stream");
$text_color = imagecolorallocate ($im, 233, 14, 91);
imagestring ($im, 5, 5, 3,  $_REQUEST["content"], $text_color);
imagepng ($im);
imagedestroy ($im);
?>
<?php
	class Message {	
		function writeMessage($filename,$message) {    //自定义一个向文件写入数据的函数
			$fp = fopen($filename,"a");
			if (flock($fp,LOCK_EX)) {
				fwrite($fp,$message);
				flock($fp,LOCK_UN);
			}else{
				return 0;
			}
			fclose($fp);
		}
	}
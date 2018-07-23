<?php
	function connect(){
		//DB接続設定
		$server = "xxxx.ne.jp";
		$mydb = "xxxx";
		$usr = "xxxx";
		$pass = "xxxx";
		$result = "";
		
		// 接続
		$link = mysql_connect($server, $usr, $pass);
		if ($link) {
			mysql_set_charset('utf8');
			// DB接続
			$db = mysql_select_db($mydb, $link);
			if ($db){
				$result = 0;
			} else {
				$result = '{"error" : 1 ,"message : "データベース選択失敗です"}';
			}
		} else {
			$result = '{"error" : 1 ,"message : "接続失敗しました"}';
		}
		return $result;
	}
?>
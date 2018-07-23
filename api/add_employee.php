<?php
	require('connect.php');
	
	//DB接続
	$result = connect();
	if($result){
		echo '{"result" : "' . $result . '"}';
		return;
	}
	//URLからのパラメーターを取得
	if(!(isset($_GET['name']))) {
		$result = '{"error" : 1 ,"message : "パラメーターが入っていません:name"}';
		echo '{"result" : ' . $result . '}';
		return;
	}
	$name = $_GET['name'];
	// 社員登録
	$query = "INSERT EmployeeMaster(name) VALUES ($name)";
	$queryResult = mysql_query($query);
	if($queryResult){
		$sql = 'SELECT LAST_INSERT_ID() AS id FROM AttendanceMaster';
		$queryResult = mysql_query( $sql );
		$row = mysql_fetch_array( $queryResult );
		$result = '{"error" : 0, "id" : ' . $row['id'] . '}';
	} else {
		$result = '{"error" : 1 ,"message : "クエリーが失敗しました:社員登録"}';
	}
	echo '{"result" : ' . $result . '}';
?>
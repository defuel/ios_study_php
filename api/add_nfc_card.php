<?php
	require('connect.php');
	
	//DB接続
	$result = connect();
	if($result){
		echo '{"result" : "' . $result . '"}';
		return;
	}
	//URLからのパラメーターを取得
	if(!(isset($_GET['id']))) {
		$result = '{"error" : 1 ,"message : "パラメーターが入っていません:id"}';
		echo '{"result" : ' . $result . '}';
		return;
	}
	if(!(isset($_GET['uid']))) {
		$result = '{"error" : 1 ,"message : "パラメーターが入っていません:uid"}';
		echo '{"result" : ' . $result . '}';
		return;
	}
	$id = $_GET['id'];
	$uid = $_GET['uid'];
	// NFCカード登録
	$query = "INSERT NfcCardMaster(employee_id, card_uid) VALUES ($id, '$uid')";
	$queryResult = mysql_query($query);
	if($queryResult){
		$sql = 'SELECT LAST_INSERT_ID() AS id FROM NfcCardMaster';
		$queryResult = mysql_query( $sql );
		$row = mysql_fetch_array( $queryResult );
		$result = '{"error" : 0, "id" : ' . $row['id'] . '}';
	} else {
		$result = '{"error" : 1 ,"message : "クエリーが失敗しました:NFCカード登録"}';
	}
	echo '{"result" : ' . $result . '}';
?>
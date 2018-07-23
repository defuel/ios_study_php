<?php
	require('connect.php');
	
	function insert($id, $attendance){
		// 勤怠マスタに登録
		$time = date('Y-m-d H:i:s');
		$query = "INSERT AttendanceMaster(time, attendance) VALUES ('$time', $attendance)";
		$queryResult = mysql_query($query);
		if($queryResult){
			// 勤怠テーブルに登録
			$sql = 'SELECT LAST_INSERT_ID() AS id FROM AttendanceMaster';
			$queryResult = mysql_query( $sql );
			$row = mysql_fetch_array( $queryResult );
			$result = $row['id'];
			$query = "INSERT AttendanceTable(employee_id, attendance_id) VALUES ($id, $result)";
			$queryResult = mysql_query($query);
			if($queryResult){
				$result = '{"error" : 0, "time" : "' . $time . '", "attendance" : ' . $attendance . '}';
			} else {
				$result = '{"error" : 1 ,"message : "クエリーが失敗しました:勤怠テーブル登録"}';
			}
		} else {
			$result = '{"error" : 1 ,"message : "クエリーが失敗しました:勤怠マスタ登録"}';
		}
		return $result;
	}
	
	//DB接続
	$result = connect();
	if($result){
		echo '{"result" : ' . $result . '}';
		return;
	}
	//URLからのパラメーターを取得
	if(!(isset($_GET['uid']))) {
		$result = '{"error" : 1 ,"message : "パラメーターが入っていません:uid"}';
		echo '{"result" : "' . $result . '"}';
		return;
	}
	$uid = $_GET['uid'];
	// 社員情報取得
	$query = "SELECT employee_id FROM NfcCardMaster WHERE card_uid = '$uid' ORDER BY id DESC LIMIT 1";
	$queryResult = mysql_query($query);
	if($queryResult){
		$row = mysql_fetch_assoc($queryResult);
		$id = $row['employee_id'];
		if($id){
			// 最新の勤怠取得
			$query = "SELECT attendance_id FROM AttendanceTable WHERE employee_id = $id ORDER BY id DESC LIMIT 1";
			$queryResult = mysql_query($query);
			if($queryResult){
				$row = mysql_fetch_assoc($queryResult);
				$result = $row['attendance_id'];
				if($result){
					// 最新の勤怠種別を取得
					$query = "SELECT attendance FROM AttendanceMaster WHERE id = $result";
					$queryResult = mysql_query($query);
					if($queryResult){
						$row = mysql_fetch_assoc($queryResult);
						$attendance = $row['attendance'];
						if($attendance){
							$attendance = 0;
						} else {
							$attendance = 1;
						}
						$result = insert($id, $attendance);
					} else {
						$result = '{"error" : 1 ,"message : "クエリーが失敗しました:勤怠種別取得"}';
					}
				} else {
					$result = insert($id, 1);
				}
			} else {
				$result = '{"error" : 1 ,"message : "クエリーが失敗しました:勤怠取得"}';
			}
		} else {
			$result = '{"error" : 1 ,"message : "クエリーが失敗しました:社員情報取得"}';
		}
	} else {
		$result = '{"error" : 1 ,"message : "クエリーが失敗しました:勤怠取得"}';
	}
	echo '{"result" : ' . $result . '}'
?>